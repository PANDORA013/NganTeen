<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MenuController;

Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Public menu routes
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{menu}', [MenuController::class, 'show'])->name('menu.show');

// Pembeli routes
Route::middleware(['auth', 'role:pembeli'])->group(function () {
    Route::post('/pembeli/cart', [CartController::class, 'store']);
    Route::get('/pembeli/cart', [CartController::class, 'index']);
    Route::delete('/pembeli/cart/{id}', [CartController::class, 'destroy']);
    Route::post('/pembeli/checkout', [CheckoutController::class, 'checkout']);
    Route::get('/pembeli/riwayat', [CheckoutController::class, 'riwayat']);
});

// Penjual routes
Route::middleware(['auth', 'role:penjual'])->group(function () {
    Route::get('/penjual/dashboard', function() {
        return view('penjual.dashboard');
    });
    Route::get('/penjual/menu', [MenuController::class, 'index']);
    Route::resource('penjual/menu', MenuController::class)->except(['index', 'show']);
});
