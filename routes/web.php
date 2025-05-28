<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PenjualController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\Penjual\MakananController;

// Root route with role-based redirect
Route::get('/', function () {
    if (Auth::check() && Auth::user()?->role) {
        return match (Auth::user()->role) {
            'penjual' => redirect()->route('penjual.home'),
            'pembeli' => redirect()->route('pembeli.home'),
            default => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'actionlogin')->name('actionlogin');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'register')->name('register');
        Route::post('/register', 'actionregister')->name('actionregister');
    });
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Dashboard redirect
    Route::get('/dashboard', function () {
        $user = Auth::user();
        return match ($user->role) {
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

    // Test route
    Route::get('/test-role', function() {
        return Auth::user()->role;
    })->middleware(['auth', 'role:pembeli']);

    // Debug role route
    Route::get('/debug-role', function() {
        return [
            'user' => Auth::user(),
            'role' => Auth::user()?->role,
            'is_authenticated' => Auth::check()
        ];
    })->middleware(['auth']);
});

Route::get('/pembeli', [PembeliController::class, 'index'])
    ->middleware(['auth', 'role:pembeli'])
    ->name('pembeli.home');
