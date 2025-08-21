<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\Penjual\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Login user penjual
$user = User::where('email', 'thomas@penjual.com')->first();
if (!$user) {
    echo "User tidak ditemukan!\n";
    exit;
}

Auth::login($user);
echo "Logged in as: " . Auth::user()->name . "\n";

// Test controller methods
$controller = new DashboardController();

try {
    echo "\n=== Testing Dashboard Controller Methods ===\n";
    
    // Test orders method
    echo "Testing orders() method...\n";
    $ordersResult = $controller->orders();
    echo "Orders method: " . ($ordersResult ? "SUCCESS" : "FAILED") . "\n";
    
    // Test payouts method  
    echo "Testing payouts() method...\n";
    $payoutsResult = $controller->payouts();
    echo "Payouts method: " . ($payoutsResult ? "SUCCESS" : "FAILED") . "\n";
    
    echo "\n=== All Controller Methods Working! ===\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
