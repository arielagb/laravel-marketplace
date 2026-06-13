<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $shop = auth()->user()->shop;

        $products = Product::where('shop_id', $shop->id)
                        ->where('is_deleted', false)
                        ->latest()
                        ->get();

        $orders = \App\Models\Order::where('shop_id', $shop->id)
                                ->where('is_deleted', false)
                                ->with(['items.product', 'user'])
                                ->latest()
                                ->take(5)
                                ->get();

        $salesData = [];
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d/m');
            $salesData[] = \App\Models\Order::where('shop_id', $shop->id)
                                            ->where('status', '!=', 'cancelled')
                                            ->whereDate('created_at', $date)
                                            ->sum('total_amount');
        }

        $totalRevenue = \App\Models\Order::where('shop_id', $shop->id)
                                        ->whereIn('status', ['paid', 'shipped', 'delivered'])
                                        ->sum('total_amount');

        $totalOrders = \App\Models\Order::where('shop_id', $shop->id)
                                        ->where('is_deleted', false)
                                        ->count();

        $commissionData = \App\Models\Commission::where('shop_id', $shop->id)
            ->selectRaw('
                SUM(amount) as total_commissions,
                SUM(CASE WHEN is_settled = 0 THEN amount ELSE 0 END) as pending_commissions,
                SUM(CASE WHEN is_settled = 1 THEN amount ELSE 0 END) as settled_commissions
            ')
            ->first();

        return view('users.sellers.dashboard', compact(
            'products', 'shop', 'orders',
            'salesData', 'labels', 'totalRevenue', 'totalOrders',
            'commissionData'
        ));
    }

    public function create()
    {
        $categories = Category::all();
        return view('users.sellers.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'images.*'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'title.required'         => 'Le titre du produit est obligatoire.',
            'category_id.required'   => 'Choisissez une catégorie.',
            'price.required'         => 'Le prix est obligatoire.',
            'price.numeric'          => 'Le prix doit être un nombre.',
            'stock_quantity.integer' => 'Le stock doit être un nombre entier.',
            'images.*.image'         => 'Les fichiers doivent être des images.',
            'images.*.max'           => 'Chaque image ne doit pas dépasser 2MB.',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/products'), $filename);
                $imagePaths[] = 'uploads/products/' . $filename;
            }
        }

        $shop = auth()->user()->shop;

        Product::create([
            'shop_id'        => $shop->id,
            'category_id'    => $request->category_id,
            'title'          => $request->title,
            'slug'           => Str::slug($request->title) . '-' . uniqid(),
            'description'    => $request->description,
            'price'          => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'images'         => $imagePaths,
            'is_published'   => $request->has('is_published'),
        ]);

        return redirect()->route('seller.products')->with('success', 'Produit ajouté avec succès ✅');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('users.sellers.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $imagePaths = $product->images ?? [];
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $filename = time() . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/products'), $filename);
                $imagePaths[] = 'uploads/products/' . $filename;
            }
        }

        $product->update([
            'category_id'    => $request->category_id,
            'title'          => $request->title,
            'slug'           => Str::slug($request->title) . '-' . uniqid(),
            'description'    => $request->description,
            'price'          => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'images'         => $imagePaths,
            'is_published'   => $request->has('is_published'),
        ]);

        return redirect()->route('seller.products')->with('success', 'Produit modifié avec succès ✅');
    }

    public function destroy(Product $product)
    {
        $product->update(['is_deleted' => true]);
        return redirect()->route('seller.products')->with('success', 'Produit supprimé ✅');
    }

    public function publicIndex(Request $request)
    {
        $categories = Category::all();

        $query = Product::where('is_published', true)
                        ->where('is_deleted', false)
                        ->with(['shop', 'category']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(12);

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        if (!$product->is_published || $product->is_deleted) {
            abort(404);
        }
        return view('products.show', compact('product'));
    }

    public function products()
    {
        $shop = auth()->user()->shop;
        $products = Product::where('shop_id', $shop->id)
                        ->where('is_deleted', false)
                        ->latest()
                        ->get();

        return view('users.sellers.products.index', compact('products', 'shop'));
    }
}