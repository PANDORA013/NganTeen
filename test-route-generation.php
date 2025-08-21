<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

echo "=== TESTING ROUTE GENERATION ===\n\n";

try {
    // Test route generation
    echo "1. Testing route('penjual.orders'):\n";
    $ordersRoute = route('penjual.orders');
    echo "   Generated URL: $ordersRoute\n";
    
    echo "\n2. Testing route('penjual.payouts'):\n";
    $payoutsRoute = route('penjual.payouts');
    echo "   Generated URL: $payoutsRoute\n";
    
    echo "\n3. Testing route('penjual.dashboard'):\n";
    $dashboardRoute = route('penjual.dashboard');
    echo "   Generated URL: $dashboardRoute\n";
    
    echo "\n4. Testing all penjual routes:\n";
    $routes = [
        'penjual.dashboard',
        'penjual.menu.index', 
        'penjual.orders',
        'penjual.payouts'
    ];
    
    foreach ($routes as $routeName) {
        try {
            $url = route($routeName);
            echo "   ✅ $routeName → $url\n";
        } catch (Exception $e) {
            echo "   ❌ $routeName → ERROR: " . $e->getMessage() . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
?>
