<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $pendingShops = Shop::where('status', 'pending')->with('user')->get();
        $totalUsers   = User::count();
        $totalShops   = Shop::where('status', 'active')->count();

        return view('users.admin.dashboard', compact('pendingShops', 'totalUsers', 'totalShops'));    }

    public function approveShop(Shop $shop)
    {
        $shop->update(['status' => 'active', 'is_active' => true]);
        return redirect()->route('admin.dashboard')->with('success', "La boutique \"{$shop->name}\" a été approuvée ✅");
    }

    public function rejectShop(Shop $shop)
    {
        $shop->update(['status' => 'rejected', 'is_active' => false]);
        return redirect()->route('admin.dashboard')->with('success', "La boutique \"{$shop->name}\" a été rejetée.");
    }
}