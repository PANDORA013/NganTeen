<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\Pembeli\CartController as PembeliCartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MenuRatingController;
use App\Http\Controllers\GlobalCheckoutController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Penjual\DashboardController as PenjualDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

// Broadcasting routes
Broadcast::routes(['middleware' => ['auth']]);

// Home route - handles role-based redirects
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public routes for contact form
Route::post('/contact', [App\Http\Controllers\Admin\ContentManagementController::class, 'storeContactMessage'])->name('contact.store');

// Development test routes - remove in production
if (app()->environment(['local', 'testing'])) {
    Route::prefix('dev-tools')->group(function () {
        Route::get('/test-broadcasting', fn() => view('test-broadcasting'))->name('test.broadcasting');
        Route::get('/test-formatting', fn() => view('test-formatting'))->name('test.formatting');
        Route::get('/test-harga', fn() => view('test-harga'))->name('test.harga');
        Route::get('/test-submission', fn() => view('test-submission'))->name('test.submission');
        Route::get('/test-login-penjual', fn() => view('test-login-penjual'))->name('test.login.penjual');
        
        // API testing routes
        Route::post('/api/test/menu-broadcast', [App\Http\Controllers\Api\TestBroadcastController::class, 'testMenuBroadcast']);
        Route::post('/api/test/order-broadcast', [App\Http\Controllers\Api\TestBroadcastController::class, 'testOrderBroadcast']);
    });
}

// AJAX Cart API routes
Route::middleware(['auth', 'role:pembeli'])->prefix('api/cart')->name('api.cart.')->group(function () {
    Route::controller(App\Http\Controllers\Api\CartApiController::class)->group(function () {
        Route::post('/add', 'addToCart')->name('add');
        Route::put('/update/{id}', 'updateCartItem')->name('update');
        Route::delete('/remove/{id}', 'removeFromCart')->name('remove');
        Route::get('/count', 'getCartCount')->name('count');
        Route::get('/items', 'getCartItems')->name('items');
    });
});

// Global Checkout Routes
Route::middleware(['auth', 'role:pembeli'])->prefix('global')->name('global.')->group(function () {
    Route::controller(GlobalCheckoutController::class)->group(function () {
        Route::get('/checkout', 'index')->name('checkout');
        Route::post('/checkout', 'process')->name('checkout.process');
        
        // Centralized payment routes
        Route::get('/payment/{orderNumber}', 'payment')->name('payment');
        Route::post('/payment/{orderNumber}/confirm', 'confirmPayment')->name('payment.confirm');
    });
});

// Payment Status & Simulation Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/payment/status/{orderNumber}', [GlobalCheckoutController::class, 'checkStatus']);
    Route::post('/payment/simulate/{orderNumber}', [GlobalCheckoutController::class, 'simulatePayment']);
    Route::post('/payment/webhook', [GlobalCheckoutController::class, 'webhook'])->withoutMiddleware(['auth']);
});

// Admin Routes - SECURE ACCESS ONLY
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin API Routes for Header
    Route::prefix('api')->name('api.')->group(function () {
        Route::controller(App\Http\Controllers\Admin\Api\QuickStatsController::class)->group(function () {
            Route::get('/quick-stats', 'quickStats')->name('quick-stats');
            Route::get('/notifications', 'notifications')->name('notifications');
            Route::get('/search', 'search')->name('search');
        });
    });

    Route::controller(AdminDashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        
        // Order Management
        Route::get('/orders', 'orders')->name('orders');
        Route::get('/orders/{orderNumber}', 'orderDetail')->name('orders.detail');
        Route::post('/orders/{orderNumber}/mark-paid', 'markOrderAsPaid')->name('orders.mark-paid');
        
        // Warung Management  
        Route::get('/warungs', 'warungs')->name('warungs');
        Route::get('/warungs/{warung}', 'warungDetail')->name('warungs.detail');
        
        // Payout Management
        Route::get('/payouts', 'payouts')->name('payouts');
        Route::post('/payouts/create', 'createPayout')->name('payouts.create');
        Route::post('/payouts/{payout}/process', 'processPayout')->name('payouts.process');
        Route::post('/payouts/{payout}/complete', 'completePayout')->name('payouts.complete');
        Route::post('/payouts/{payout}/fail', 'failPayout')->name('payouts.fail');
        Route::post('/payouts/process-all', 'processAllPayouts')->name('payouts.process-all');
        Route::get('/payouts/{payout}/details', 'payoutDetails')->name('payouts.details');
        Route::get('/payouts/export', 'exportPayouts')->name('payouts.export');
        
        // Transaction Management
        Route::get('/transactions', 'transactions')->name('transactions');
        Route::get('/transactions/{transaction}', 'transactionDetail')->name('transactions.detail');
    });
    
    // Payment Settings & Settlement Management
    Route::controller(App\Http\Controllers\Admin\PaymentSettingController::class)->group(function () {
        Route::get('/payment-settings', 'index')->name('payment.settings');
        Route::put('/payment-settings', 'update')->name('payment.settings.update');
        
        // Settlement management
        Route::get('/settlements', 'settlements')->name('settlements');
        Route::post('/settlements/{order}/settle', 'settleOrder')->name('settlements.settle');
        Route::post('/settlements/bulk', 'settleBulk')->name('settlements.bulk');
        Route::post('/settlements/settle-all', 'settleAll')->name('settlements.all');
    });

    // User Management
    Route::controller(App\Http\Controllers\Admin\UserManagementController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{user}', 'show')->name('show');
        Route::get('/{user}/edit', 'edit')->name('edit');
        Route::put('/{user}', 'update')->name('update');
        Route::delete('/{user}', 'destroy')->name('destroy');
        Route::post('/{user}/toggle-status', 'toggleStatus')->name('toggle-status');
        Route::post('/bulk-delete', 'bulkDelete')->name('bulk-delete');
    });

    // Food News Management
    Route::controller(App\Http\Controllers\Admin\FoodNewsController::class)->prefix('food-news')->name('food-news.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{news}/edit', 'edit')->name('edit');
        Route::put('/{news}', 'update')->name('update');
        Route::delete('/{news}', 'destroy')->name('destroy');
        Route::post('/{news}/toggle-status', 'toggleStatus')->name('toggle-status');
    });

    // Content Management
    Route::controller(App\Http\Controllers\Admin\ContentManagementController::class)->prefix('content')->name('content.')->group(function () {
        Route::get('/', 'index')->name('index');
        
        // Landing Page Features
        Route::get('/features', 'features')->name('features');
        Route::post('/features', 'storeFeatures')->name('features.store');
        
        // Testimonials
        Route::get('/testimonials', 'testimonials')->name('testimonials');
        Route::post('/testimonials', 'storeTestimonial')->name('testimonials.store');
        Route::post('/testimonials/{id}/update', 'updateTestimonial')->name('testimonials.update');
        Route::post('/testimonials/{id}/toggle-featured', 'toggleTestimonialFeatured')->name('testimonials.toggle-featured');
        Route::delete('/testimonials/{id}', 'deleteTestimonial')->name('testimonials.delete');
        
        // Help Center
        Route::get('/help-center', 'helpCenter')->name('help-center');
        Route::post('/help-center', 'storeHelpArticle')->name('help-center.store');
        Route::post('/help-center/{id}/update', 'updateHelpArticle')->name('help-center.update');
        Route::post('/help-center/{id}/toggle-featured', 'toggleHelpArticleFeatured')->name('help-center.toggle-featured');
        Route::delete('/help-center/{id}', 'deleteHelpArticle')->name('help-center.delete');
        
        // Contact Messages
        Route::get('/contact-messages', 'contactMessages')->name('contact-messages');
        Route::post('/contact-messages/{id}/read', 'markMessageAsRead')->name('contact-messages.read');
        Route::delete('/contact-messages/{id}', 'deleteContactMessage')->name('contact-messages.delete');
        Route::post('/contact-messages/mark-all-read', 'markAllMessagesAsRead')->name('contact-messages.mark-all-read');
        Route::delete('/contact-messages/delete-read', 'deleteReadMessages')->name('contact-messages.delete-read');
        
        // Website Settings
        Route::get('/website-settings', 'websiteSettings')->name('website-settings');
        Route::post('/website-settings', 'storeWebsiteSettings')->name('website-settings.store');
    });

    // Analytics & Reports
    Route::controller(App\Http\Controllers\Admin\AnalyticsController::class)->prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/user-registrations', 'userRegistrations')->name('user-registrations');
        Route::get('/order-analytics', 'orderAnalytics')->name('order-analytics');
        Route::get('/revenue-analytics', 'revenueAnalytics')->name('revenue-analytics');
    });
});

// Enhanced Penjual Routes
Route::middleware(['auth', 'role:penjual'])->prefix('penjual')->name('penjual.')->group(function () {
    // Dashboard (enhanced)
    Route::get('/dashboard', [PenjualDashboardController::class, 'index'])->name('dashboard');
    
    // Menu Management (complete CRUD)
    Route::controller(MenuController::class)->prefix('menu')->name('menu.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('/professional', 'indexProfessional')->name('index.professional');
        Route::get('/create', 'create')->name('create');
        Route::get('/create/professional', 'createProfessional')->name('create.professional');
        Route::post('/', 'store')->name('store');
        Route::get('/{menu}/edit', 'edit')->name('edit');
        Route::get('/{menu}/edit/professional', 'editProfessional')->name('edit.professional');
        Route::put('/{menu}', 'update')->name('update');
        Route::delete('/{menu}', 'destroy')->name('destroy');
    });
    
    // Payout Requests
    Route::post('/payouts/request', [PenjualDashboardController::class, 'requestPayout'])->name('payouts.request');
    Route::get('/payouts', [PenjualDashboardController::class, 'payouts'])->name('payouts');
    
    // Order Management (enhanced for both systems)
    Route::get('/orders', [PenjualDashboardController::class, 'orders'])->name('orders');
    Route::patch('/orders/{orderItem}/status', [PenjualDashboardController::class, 'updateOrderStatus'])->name('orders.updateStatus');
    
    // Dashboard API endpoints for real-time data
    Route::get('/api/dashboard-data', [PenjualDashboardController::class, 'getDashboardData'])->name('api.dashboard.data');
    Route::get('/api/chart-data', [PenjualDashboardController::class, 'getChartData'])->name('api.chart.data');
    
    // Warung Management (for penjual)
    Route::get('/warung/setup', [PenjualDashboardController::class, 'warungSetup'])->name('warung.setup');
    Route::post('/warung/store', [PenjualDashboardController::class, 'storeWarung'])->name('warung.store');
    Route::get('/warung/edit', [PenjualDashboardController::class, 'editWarung'])->name('warung.edit');
    Route::put('/warung/update', [PenjualDashboardController::class, 'updateWarung'])->name('warung.update');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

// Pembeli routes - restructured for better organization
Route::middleware(['auth', 'verified', 'role:pembeli'])->prefix('pembeli')->name('pembeli.')->group(function () {
    // Dashboard with food news
    Route::get('/dashboard', function() {
        $menus = \App\Models\Menu::where('stok', '>', 0)->latest()->get();
        $foodNews = \App\Models\FoodNews::active()->published()->latest()->take(6)->get();
        return view('pembeli.dashboard', compact('menus', 'foodNews'));
    })->name('dashboard');

    // Menu browsing
    Route::controller(MenuController::class)->prefix('menu')->name('menu.')->group(function() {
        Route::get('/', 'publicIndex')->name('index');
        Route::get('/{menu}', 'publicShow')->name('show');
    });

    // Cart management
    Route::controller(PembeliCartController::class)->prefix('cart')->name('cart.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::get('/count', 'count')->name('count');
    });
    
    // Order management
    Route::controller(OrderController::class)->prefix('orders')->name('orders.')->group(function () {
        Route::get('/', 'userOrders')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/{order}/cancel', 'cancel')->name('cancel');
    });
});

// Order and payment processing
Route::middleware('auth')->group(function () {
    Route::post('/order/{id}/paid', [OrderController::class, 'markAsPaid'])->name('order.paid');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Password management routes
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    // QRIS management routes (for penjual)
    Route::get('/profile/qris', [ProfileController::class, 'editQris'])->name('profile.qris.edit');
    Route::post('/profile/qris', [ProfileController::class, 'uploadQris'])->name('profile.qris.upload');
    Route::delete('/profile/qris', [ProfileController::class, 'deleteQris'])->name('profile.qris.delete');
    
    // Menu rating routes
    Route::post('/menu/{menu}/rating', [MenuRatingController::class, 'store'])->name('menu.rating.store');
    Route::get('/menu/{menu}/rating', [MenuRatingController::class, 'show'])->name('menu.rating.show');
    
    // Menu favorite routes
    Route::post('/menu/{menu}/favorite', [MenuController::class, 'toggleFavorite'])->name('menu.favorite.toggle');
});

require __DIR__.'/auth.php';
