<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class SellerOrderController extends Controller
{
    public function index()
    {
        $shop = auth()->user()->shop;

        $orders = Order::where('shop_id', $shop->id)
                       ->where('is_deleted', false)
                       ->with(['items.product', 'user'])
                       ->latest()
                       ->get();

        return view('users.sellers.orders', compact('orders', 'shop'));
    }

    public function shipMultiple(Request $request)
    {
        $request->validate([
            'order_ids'   => 'required|array',
            'order_ids.*' => 'exists:orders,id',
        ]);

        Order::whereIn('id', $request->order_ids)
             ->where('shop_id', auth()->user()->shop->id)
             ->where('status', 'paid')
             ->update(['status' => 'shipped']);

        return back()->with('success', count($request->order_ids) . ' commande(s) marquée(s) comme expédiée(s) ✅');
    }
}