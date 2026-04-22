<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

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

// Dashboards (accessible seulement si connecté)
    Route::middleware('auth')->group(function () {
    Route::get('/seller/dashboard', [ProductController::class, 'index'])->name('dashboard_seller');    Route::get('/buyer/dashboard', fn() => view('users.buyers.dashboard'))->name('dashboard_buyer');

    // Onboarding vendeur
    Route::get('/seller/onboarding', [ShopController::class, 'onboarding'])->name('seller.onboarding');
    Route::post('/seller/onboarding', [ShopController::class, 'storeOnboarding'])->name('seller.onboarding.store');
    Route::get('/seller/pending', [ShopController::class, 'pending'])->name('seller.pending');

    // Produits Seller
    Route::get('/seller/products', [ProductController::class, 'index'])->name('seller.products');
    Route::get('/seller/products/create', [ProductController::class, 'create'])->name('seller.products.create');
    Route::post('/seller/products', [ProductController::class, 'store'])->name('seller.products.store');
    Route::get('/seller/products/{product}/edit', [ProductController::class, 'edit'])->name('seller.products.edit');
    Route::put('/seller/products/{product}', [ProductController::class, 'update'])->name('seller.products.update');
    Route::delete('/seller/products/{product}', [ProductController::class, 'destroy'])->name('seller.products.destroy');

    //Pour le panier
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

});

// Routes admin
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/shops/{shop}/approve', [AdminController::class, 'approveShop'])->name('shops.approve');
    Route::post('/shops/{shop}/reject', [AdminController::class, 'rejectShop'])->name('shops.reject');
});