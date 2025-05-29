<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PenjualController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\Penjual\MakananController;

// Guest routes - these should be accessible only when not logged in
Route::middleware('guest')->group(function () {
    // Login routes
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'actionlogin')->name('actionlogin');
    });

    // Register routes
    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'register')->name('register');
        Route::post('/register', 'actionregister')->name('register.action');
    });
});

// Authenticated routes - these require login
Route::middleware(['auth'])->group(function () {
    // Root route with role-based redirect
    Route::get('/', function () {
        return match (Auth::user()->role) {
            'penjual' => redirect()->route('penjual.home'),
            'pembeli' => redirect()->route('pembeli.home'),
            default => redirect()->route('login'),
        };
    });

    // Dashboard redirect
    Route::get('/dashboard', function () {
        return match (Auth::user()->role) {
            'penjual' => redirect()->route('penjual.home'),
            'pembeli' => redirect()->route('pembeli.home'),
            default => redirect()->route('login'),
        };
    })->name('dashboard');

    // Penjual routes
    Route::middleware('role:penjual')
        ->prefix('penjual')
        ->name('penjual.')
        ->group(function () {
            Route::get('/', [PenjualController::class, 'index'])->name('home');
            Route::resource('makanan', MakananController::class);
        });

    // Pembeli routes
    Route::middleware('role:pembeli')
        ->prefix('pembeli')
        ->name('pembeli.')
        ->group(function () {
            Route::get('/', [PembeliController::class, 'index'])->name('home');
        });

    // Logout route
    Route::post('/logout', [LoginController::class, 'actionlogout'])->name('logout');
});
