<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $cartItems = CartItem::where('user_id', auth()->id())
                             ->with('product.shop')
                             ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Ton panier est vide.');
        }

        // Vérifie que tous les produits sont disponibles
        foreach ($cartItems as $item) {
            if ($item->product->is_deleted || !$item->product->is_published) {
                return back()->with('error', "Le produit \"{$item->product->title}\" n'est plus disponible.");
            }
            if ($item->quantity > $item->product->stock_quantity) {
                return back()->with('error', "Stock insuffisant pour \"{$item->product->title}\".");
            }
        }

        // Groupe les articles par boutique
        $itemsByShop = $cartItems->groupBy(fn($item) => $item->product->shop_id);

        DB::transaction(function () use ($itemsByShop) {
            foreach ($itemsByShop as $shopId => $items) {
                $totalAmount = $items->sum(fn($i) => $i->product->price * $i->quantity);

                // Crée une commande par boutique
                $order = Order::create([
                    'user_id'      => auth()->id(),
                    'shop_id'      => $shopId,
                    'status'       => 'created',
                    'total_amount' => $totalAmount,
                    'shipping_fee' => 0,
                ]);

                foreach ($items as $item) {
                    $unitPrice        = $item->product->price;
                    $totalPrice       = $unitPrice * $item->quantity;
                    $commissionRate   = 10; // 10% de commission
                    $commissionAmount = $totalPrice * $commissionRate / 100;

                    // Crée la ligne de commande
                    OrderItem::create([
                        'order_id'          => $order->id,
                        'product_id'        => $item->product_id,
                        'quantity'          => $item->quantity,
                        'unit_price'        => $unitPrice,
                        'total_price'       => $totalPrice,
                        'commission_rate'   => $commissionRate,
                        'commission_amount' => $commissionAmount,
                    ]);

                    // Diminue le stock
                    $item->product->decrement('stock_quantity', $item->quantity);

                    // Supprime l'article du panier
                    $item->delete();
                }
            }
        });

        return redirect()->route('orders.index')->with('success', 'Commande passée avec succès ! 🎉');
    }

    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
                       ->where('is_deleted', false)
                       ->with(['items.product', 'shop'])
                       ->latest()
                       ->get();

        return view('users.buyers.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.product', 'shop']);
        return view('users.buyers.order_show', compact('order'));
    }
}