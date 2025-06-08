<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Home route that will redirect based on role
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Penjual routes
Route::middleware(['auth', 'verified', 'role:penjual'])->prefix('penjual')->group(function () {
    Route::get('/dashboard', function () {
        return view('penjual.dashboard');
    })->name('penjual.dashboard');
    
    Route::resource('menu', MenuController::class)->names([
        'index' => 'penjual.menu.index',
        'create' => 'penjual.menu.create',
        'store' => 'penjual.menu.store',
        'show' => 'penjual.menu.show',
        'edit' => 'penjual.menu.edit',
        'update' => 'penjual.menu.update',
        'destroy' => 'penjual.menu.destroy',
    ]);
    Route::get('orders', [OrderController::class, 'index'])->name('penjual.orders.index');
    Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('penjual.orders.update-status');
});

// Pembeli routes
Route::middleware(['auth', 'verified', 'role:pembeli'])->prefix('pembeli')->group(function () {
    Route::get('/dashboard', function () {
        $menus = \App\Models\Menu::where('stok', '>', 0)->latest()->get();
        return view('pembeli.dashboard', compact('menus'));
    })->name('pembeli.dashboard');
    
    Route::get('/menu', [App\Http\Controllers\MenuController::class, 'index'])->name('pembeli.menu.index');
    
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    
    // Order routes for pembeli
    Route::get('/orders', [OrderController::class, 'userOrders'])->name('pembeli.orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('pembeli.orders.store');
});

// Shared routes for authenticated users
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
