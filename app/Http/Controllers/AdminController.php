<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $pendingShops  = Shop::where('status', 'pending')->with('user')->get();
        $totalUsers    = User::count();
        $totalShops    = Shop::where('status', 'active')->count();
        $totalOrders   = Order::where('is_deleted', false)->count();
        $totalRevenue  = Order::whereIn('status', ['paid', 'shipped', 'delivered'])->sum('total_amount');

        // Données graphe — inscriptions des 7 derniers jours
        $labels = [];
        $usersData = [];
        $ordersData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[]     = $date->format('d/m');
            $usersData[]  = User::whereDate('created_at', $date)->count();
            $ordersData[] = Order::whereDate('created_at', $date)->count();
        }

        return view('users.admin.dashboard', compact(
            'pendingShops', 'totalUsers', 'totalShops',
            'totalOrders', 'totalRevenue',
            'labels', 'usersData', 'ordersData'
        ));
    }

    public function approveShop(Shop $shop)
    {
        $shop->update(['status' => 'active', 'is_active' => true]);
        return redirect()->route('admin.dashboard')
            ->with('success', "La boutique \"{$shop->name}\" a été approuvée ✅");
    }

    public function rejectShop(Shop $shop)
    {
        $shop->update(['status' => 'rejected', 'is_active' => false]);
        return redirect()->route('admin.dashboard')
            ->with('success', "La boutique \"{$shop->name}\" a été rejetée.");
    }

    // USERS
    public function users()
    {
        $users = User::with('role')->latest()->paginate(20);
        return view('users.admin.users', compact('users'));
    }

    public function blockUser(User $user)
    {
        $user->update(['is_blocked' => !$user->is_blocked]);
        $msg = $user->is_blocked ? 'bloqué' : 'débloqué';
        return back()->with('success', "L'utilisateur a été {$msg}.");
    }

    // SHOPS
    public function shops()
    {
        $shops = Shop::with('user')->latest()->paginate(20);
        return view('users.admin.shops', compact('shops'));
    }

    // ORDERS
    public function orders()
    {
        $orders = Order::with(['user', 'shop'])
                       ->where('is_deleted', false)
                       ->latest()
                       ->paginate(20);
        return view('users.admin.orders', compact('orders'));
    }

    // CATEGORIES
    public function categories()
    {
        $categories = Category::withCount('products')->latest()->get();
        return view('users.admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ], [
            'name.required' => 'Le nom de la catégorie est obligatoire.',
            'name.unique'   => 'Cette catégorie existe déjà.',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
        ]);

        return back()->with('success', "Catégorie \"{$request->name}\" créée ✅");
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
        ]);

        return back()->with('success', "Catégorie mise à jour ✅");
    }

    public function destroyCategory(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', "Impossible de supprimer — {$category->products()->count()} produit(s) utilisent cette catégorie.");
        }

        $category->delete();
        return back()->with('success', "Catégorie supprimée ✅");
    }

    public function showUser(User $user)
    {
        $user->load('role');

        $shop = null;
        $products = collect();
        $orders = collect();

        if ($user->role?->label === 'Seller') {
            $shop = $user->shop?->load('category');
            $products = $shop ? $shop->products()->where('is_deleted', false)->get() : collect();
        }

        if ($user->role?->label === 'Buyer') {
            $orders = Order::where('user_id', $user->id)
                        ->where('is_deleted', false)
                        ->with(['items.product', 'shop'])
                        ->latest()
                        ->get();
        }

        return view('users.admin.user_show', compact('user', 'shop', 'products', 'orders'));
    }

    public function commissions()
    {
        // Charge toutes les boutiques avec leurs commissions non réglées
        $shops = \App\Models\Shop::where('status', 'active')
            ->withCount(['commissions as pending_commissions_count' => function($q) {
                $q->where('is_settled', false);
            }])
            ->withSum(['commissions as pending_amount' => function($q) {
                $q->where('is_settled', false);
            }], 'amount')
            ->withSum(['commissions as total_amount' => function($q) {
                $q->where('is_settled', true);
            }], 'amount')
            ->get();

        $totalPending = \App\Models\Commission::where('is_settled', false)->sum('amount');
        $totalSettled = \App\Models\Commission::where('is_settled', true)->sum('amount');

        return view('users.admin.commissions', compact('shops', 'totalPending', 'totalSettled'));
    }

    public function updateCommissionRate(Request $request, \App\Models\Shop $shop)
    {
        $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
        ], [
            'commission_rate.required' => 'Le taux est obligatoire.',
            'commission_rate.min'      => 'Le taux ne peut pas être négatif.',
            'commission_rate.max'      => 'Le taux ne peut pas dépasser 100%.',
        ]);

        $shop->update(['commission_override' => $request->commission_rate]);

        return back()->with('success', "Taux de \"{$shop->name}\" mis à jour : {$request->commission_rate}%");
    }

    public function settleCommissions(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'notes'   => 'nullable|string|max:500',
        ]);

        // Marque toutes les commissions non réglées de cette boutique comme payées
        $count = \App\Models\Commission::where('shop_id', $request->shop_id)
            ->where('is_settled', false)
            ->update([
                'is_settled' => true,
                'notes'      => $request->notes,
            ]);

        $shop = \App\Models\Shop::find($request->shop_id);

        return back()->with('success', "{$count} commission(s) de \"{$shop->name}\" marquée(s) comme réglées.");
    }
}