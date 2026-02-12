<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;//tjrs penser a importer le controller 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home')->name('home');
});

Route::get('/register', [UserController::class, 'showSignUp'])->name('register');

Route::get('/login', [UserController::class, 'showFormLogin'])->name('login');


//uniquement pour les visiteurs 
// Route::middleware(['guest'])->group(function () {
//     Route::get('/Home', function () {
//         return view('Home') ;
//     });
// });