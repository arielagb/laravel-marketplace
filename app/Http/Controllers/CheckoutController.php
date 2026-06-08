<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::where('user_id', auth()->id())
                             ->with('product.shop')
                             ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Ton panier est vide.');
        }

        // Vérifie disponibilité de tous les produits
        foreach ($cartItems as $item) {
            if ($item->product->is_deleted || !$item->product->is_published) {
                return redirect()->route('cart.index')
                    ->with('error', "Le produit \"{$item->product->title}\" n'est plus disponible.");
            }
            if ($item->quantity > $item->product->stock_quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Stock insuffisant pour \"{$item->product->title}\".");
            }
        }

        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        return view('checkout.index', compact('cartItems', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:mixx,moov,card',
            'phone'          => 'required_if:payment_method,mixx,moov|nullable|string|max:20',
            'card_number'    => 'required_if:payment_method,card|nullable|string|max:19',
            'card_name'      => 'required_if:payment_method,card|nullable|string|max:255',
            'card_expiry'    => 'required_if:payment_method,card|nullable|string|max:5',
            'card_cvv'       => 'required_if:payment_method,card|nullable|string|max:4',
        ], [
            'payment_method.required' => 'Choisissez un moyen de paiement.',
            'phone.required_if'       => 'Le numéro de téléphone est obligatoire.',
            'card_number.required_if' => 'Le numéro de carte est obligatoire.',
            'card_name.required_if'   => 'Le nom sur la carte est obligatoire.',
            'card_expiry.required_if' => 'La date d\'expiration est obligatoire.',
            'card_cvv.required_if'    => 'Le CVV est obligatoire.',
        ]);

        $cartItems = CartItem::where('user_id', auth()->id())
                             ->with('product.shop')
                             ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Ton panier est vide.');
        }

        // Regroupe par boutique
        $itemsByShop = $cartItems->groupBy(fn($item) => $item->product->shop_id);

        $orderIds = [];

        DB::transaction(function () use ($itemsByShop, $request, &$orderIds) {
            foreach ($itemsByShop as $shopId => $items) {
                $totalAmount = $items->sum(fn($i) => $i->product->price * $i->quantity);

                $order = Order::create([
                    'user_id'            => auth()->id(),
                    'shop_id'            => $shopId,
                    'status'             => 'paid',
                    'total_amount'       => $totalAmount,
                    'shipping_fee'       => 0,
                    'payment_provider'   => $request->payment_method,
                    'payment_reference'  => strtoupper(uniqid('PAY-')),
                ]);

                $orderIds[] = $order->id;

                foreach ($items as $item) {
                    $unitPrice   = $item->product->price;
                    $totalPrice  = $unitPrice * $item->quantity;

                    // On récupère le taux de la boutique (override ou défaut 10%)
                    $shop = \App\Models\Shop::find($shopId);
                    $commissionRate   = $shop->getCommissionRate();
                    $commissionAmount = round($totalPrice * $commissionRate / 100, 2);

                    // Crée la ligne de commande
                    $orderItem = OrderItem::create([
                        'order_id'          => $order->id,
                        'product_id'        => $item->product_id,
                        'quantity'          => $item->quantity,
                        'unit_price'        => $unitPrice,
                        'total_price'       => $totalPrice,
                        'commission_rate'   => $commissionRate,
                        'commission_amount' => $commissionAmount,
                    ]);

                    // Crée l'entrée dans la table commissions
                    \App\Models\Commission::create([
                        'order_item_id'  => $orderItem->id,
                        'shop_id'        => $shopId,
                        'rate'           => $commissionRate,
                        'amount'         => $commissionAmount,
                        'is_settled'     => false,
                        'calculated_at'  => now(),
                    ]);

                    // Diminue le stock
                    $item->product->decrement('stock_quantity', $item->quantity);

                    // Supprime l'article du panier
                    $item->delete();
                }
            }
        });

        return redirect()->route('orders.index')
            ->with('success', '🎉 Paiement validé ! Ta commande a été créée avec succès.');
    }
}