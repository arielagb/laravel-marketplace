<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShopController;

// Pages publiques
Route::get('/', fn() => view('welcome'))->name('home');

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
    Route::get('/admin/dashboard', fn() => view('users.admin.dashboard'))->name('dashboard_admin');
    Route::get('/seller/dashboard', fn() => view('users.sellers.dashboard'))->name('dashboard_seller');
    Route::get('/buyer/dashboard', fn() => view('users.buyers.dashboard'))->name('dashboard_buyer');

    // Onboarding vendeur
    Route::get('/seller/onboarding', [ShopController::class, 'onboarding'])->name('seller.onboarding');
    Route::post('/seller/onboarding', [ShopController::class, 'storeOnboarding'])->name('seller.onboarding.store');
    Route::get('/seller/pending', [ShopController::class, 'pending'])->name('seller.pending');
});