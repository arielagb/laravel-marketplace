<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\SellerOrderController;

// Pages publiques
Route::get('/', fn() => view('welcome'))->name('home');
Route::get('/products', [ProductController::class, 'publicIndex'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Auth (accessible seulement si NON connecté)
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserController::class, 'showFormLogin'])->name('login');
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/register', [UserController::class, 'showSignUp'])->name('register');
    Route::post('/register', [UserController::class, 'signUp']);
});

// Déconnexion
Route::post('/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');

// Routes protégées
Route::middleware('auth')->group(function () {

    // Buyer
    Route::get('/buyer/dashboard', fn() => view('users.buyers.dashboard'))->name('dashboard_buyer');

    // Panier
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    // Commandes buyer
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Seller — onboarding
    Route::get('/seller/onboarding', [ShopController::class, 'onboarding'])->name('seller.onboarding');
    Route::post('/seller/onboarding', [ShopController::class, 'storeOnboarding'])->name('seller.onboarding.store');
    Route::get('/seller/pending', [ShopController::class, 'pending'])->name('seller.pending');

    // Seller — dashboard
    Route::get('/seller/dashboard', [ProductController::class, 'index'])->name('seller.dashboard');

    // Seller — produits
    Route::get('/seller/products', [ProductController::class, 'products'])->name('seller.products');
    Route::get('/seller/products/create', [ProductController::class, 'create'])->name('seller.products.create');
    Route::post('/seller/products', [ProductController::class, 'store'])->name('seller.products.store');
    Route::get('/seller/products/{product}/edit', [ProductController::class, 'edit'])->name('seller.products.edit');
    Route::put('/seller/products/{product}', [ProductController::class, 'update'])->name('seller.products.update');
    Route::delete('/seller/products/{product}', [ProductController::class, 'destroy'])->name('seller.products.destroy');

    // Seller — commandes
    Route::get('/seller/orders', [SellerOrderController::class, 'index'])->name('seller.orders');
    Route::post('/seller/orders/ship', [SellerOrderController::class, 'shipMultiple'])->name('seller.orders.ship');

    // Seller — paramètres boutique
    Route::get('/seller/settings', [ShopController::class, 'settings'])->name('seller.settings');
    Route::put('/seller/settings', [ShopController::class, 'updateSettings'])->name('seller.settings.update');

    // Admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/shops/{shop}/approve', [AdminController::class, 'approveShop'])->name('shops.approve');
        Route::post('/shops/{shop}/reject', [AdminController::class, 'rejectShop'])->name('shops.reject');
    });

});