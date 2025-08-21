<?php
require_once 'vendor/autoload.php';

echo "Testing Penjual Orders and Payouts Routes...\n\n";

// Simulate authentication
$_SESSION['logged_in_user_id'] = 1; // Assuming user ID 1 exists

try {
    // Test orders route
    echo "1. Testing Orders Route:\n";
    $ordersUrl = 'http://localhost:8000/penjual/orders';
    echo "URL: $ordersUrl\n";
    
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => "User-Agent: Test Script\r\n"
        ]
    ]);
    
    $ordersResponse = @file_get_contents($ordersUrl, false, $context);
    
    if ($ordersResponse === false) {
        $error = error_get_last();
        echo "❌ Orders page failed: " . $error['message'] . "\n";
    } else {
        echo "✅ Orders page responded (Length: " . strlen($ordersResponse) . " chars)\n";
        // Check if it's an error page
        if (strpos($ordersResponse, 'error') !== false || strpos($ordersResponse, 'Exception') !== false) {
            echo "⚠️  Possible error in response\n";
        }
    }
    
    echo "\n2. Testing Payouts Route:\n";
    $payoutsUrl = 'http://localhost:8000/penjual/payouts';
    echo "URL: $payoutsUrl\n";
    
    $payoutsResponse = @file_get_contents($payoutsUrl, false, $context);
    
    if ($payoutsResponse === false) {
        $error = error_get_last();
        echo "❌ Payouts page failed: " . $error['message'] . "\n";
    } else {
        echo "✅ Payouts page responded (Length: " . strlen($payoutsResponse) . " chars)\n";
        // Check if it's an error page
        if (strpos($payoutsResponse, 'error') !== false || strpos($payoutsResponse, 'Exception') !== false) {
            echo "⚠️  Possible error in response\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "Done testing routes.\n";
?>
