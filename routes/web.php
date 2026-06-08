<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    ProductController, ShopController, UserController, 
    CartController, CheckoutController, OrderController, 
    SellerOrderController, AdminController
};

// --- Pages publiques ---
Route::get('/', fn() => view('welcome'))->name('home');
Route::controller(ProductController::class)->prefix('products')->name('products.')->group(function () {
    Route::get('/', 'publicIndex')->name('index');
    Route::get('/{product}', 'show')->name('show');
});
Route::get('/shops/{shop:slug}', [ShopController::class, 'show'])->name('shop.show');


// --- Auth (Invités seulement) ---
Route::middleware('guest')->controller(UserController::class)->group(function () {
    Route::get('/login', 'showFormLogin')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showSignUp')->name('register');
    Route::post('/register', 'signUp');
});

Route::post('/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');


// --- Routes protégées (Connectés) ---
Route::middleware('auth')->group(function () {

    // Buyer & Dashboard
    Route::get('/buyer/dashboard', fn() => view('users.buyers.dashboard'))->name('dashboard_buyer');

    // Panier 
    Route::controller(CartController::class)->prefix('cart')->name('cart.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{product}', 'add')->name('add');
        Route::patch('/{cartItem}', 'update')->name('update');
        Route::delete('/{cartItem}', 'remove')->name('remove');
    });

    // Checkout
    Route::controller(CheckoutController::class)->prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'process')->name('process');
    });

    // Commandes Buyer
    Route::controller(OrderController::class)->prefix('orders')->name('orders.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{order}', 'show')->name('show');
    });

    // SELLER - Regroupement global du rôle vendeur
    Route::prefix('seller')->name('seller.')->group(function () {
        
        // Boutique & Onboarding
        Route::controller(ShopController::class)->group(function () {
            Route::get('/onboarding', 'onboarding')->name('onboarding');
            Route::post('/onboarding', 'storeOnboarding')->name('onboarding.store');
            Route::get('/pending', 'pending')->name('pending');
            Route::get('/settings', 'settings')->name('settings');
            Route::put('/settings', 'updateSettings')->name('settings.update');
        });

        // Produits du vendeur
        Route::controller(ProductController::class)->prefix('products')->group(function () {
            Route::get('/', 'products')->name('products'); // seller.products
            Route::get('/create', 'create')->name('products.create');
            Route::post('/', 'store')->name('products.store');
            Route::get('/{product}/edit', 'edit')->name('products.edit');
            Route::put('/{product}', 'update')->name('products.update');
            Route::delete('/{product}', 'destroy')->name('products.destroy');
            // Dashboard seller pointe vers index des produits
            Route::get('/dashboard', 'index')->name('dashboard'); 
        });

        // Commandes du vendeur
        Route::controller(SellerOrderController::class)->prefix('orders')->group(function () {
            Route::get('/', 'index')->name('orders');
            Route::post('/ship', 'shipMultiple')->name('orders.ship');
        });
    });

    // ADMIN
    Route::prefix('admin')->name('admin.')->controller(AdminController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');

        // Commissions
        Route::prefix('commissions')->name('commissions.')->group(function(){
            Route::get('/', 'commissions')->name('index');
            Route::post('/{shop}/rate', 'updateCommissionRate')->name('rate');
            Route::post('/settle', 'settleCommissions')->name('settle');
        });

        // Boutiques
        Route::prefix('shops')->name('shops.')->group(function () {
            Route::get('/', 'shops')->name('index');
            Route::post('/{shop}/approve', 'approveShop')->name('approve');
            Route::post('/{shop}/reject', 'rejectShop')->name('reject');
        });

        // Catégories
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', 'categories')->name('index');
            Route::post('/', 'storeCategory')->name('store');
            Route::put('/{category}', 'updateCategory')->name('update');
            Route::delete('/{category}', 'destroyCategory')->name('destroy');
        });

        // Utilisateurs
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', 'users')->name('index');
            Route::get('/{user}', 'showUser')->name('show');
            Route::post('/{user}/block', 'blockUser')->name('block');
        });

        Route::get('/orders', 'orders')->name('orders.index');
    });
});