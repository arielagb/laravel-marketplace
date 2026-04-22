<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::where('user_id', auth()->id())
                             ->with('product.shop')
                             ->get();

        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        return view('users.buyers.cart', compact('cartItems', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        if ($product->stock_quantity <= 0) {
            return back()->with('error', 'Ce produit est en rupture de stock.');
        }

        $cartItem = CartItem::where('user_id', auth()->id())
                            ->where('product_id', $product->id)
                            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            CartItem::create([
                'user_id'    => auth()->id(),
                'product_id' => $product->id,
                'quantity'   => 1,
            ]);
        }

        return back()->with('success', 'Produit ajouté au panier 🛒');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        if ($request->quantity > $cartItem->product->stock_quantity) {
            return back()->with('error', 'Stock insuffisant.');
        }

        $cartItem->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Panier mis à jour ✅');
    }

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();
        return back()->with('success', 'Produit retiré du panier.');
    }
}