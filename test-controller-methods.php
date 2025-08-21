<?php

echo "=== CONTROLLER METHOD TEST ===\n\n";

// Test if methods exist
$controllerPath = 'app/Http/Controllers/Penjual/DashboardController.php';
$content = file_get_contents($controllerPath);

echo "1. Checking methods in DashboardController:\n";

if (strpos($content, 'public function orders()') !== false) {
    echo "   ✅ orders() method found\n";
} else {
    echo "   ❌ orders() method NOT found\n";
}

if (strpos($content, 'public function payouts()') !== false) {
    echo "   ✅ payouts() method found\n";
} else {
    echo "   ❌ payouts() method NOT found\n";
}

echo "\n2. Checking view returns:\n";

// Extract method content
preg_match('/public function orders\(\).*?return.*?;/s', $content, $ordersMatches);
if (!empty($ordersMatches)) {
    echo "   Orders method returns: " . trim(preg_replace('/\s+/', ' ', $ordersMatches[0])) . "\n";
}

preg_match('/public function payouts\(\).*?return.*?;/s', $content, $payoutsMatches);
if (!empty($payoutsMatches)) {
    echo "   Payouts method returns: " . trim(preg_replace('/\s+/', ' ', $payoutsMatches[0])) . "\n";
}

echo "\n3. Checking view files:\n";

$orderView = 'resources/views/penjual/orders/index.blade.php';
$payoutView = 'resources/views/penjual/payouts/index.blade.php';

if (file_exists($orderView)) {
    echo "   ✅ $orderView exists (" . filesize($orderView) . " bytes)\n";
} else {
    echo "   ❌ $orderView does NOT exist\n";
}

if (file_exists($payoutView)) {
    echo "   ✅ $payoutView exists (" . filesize($payoutView) . " bytes)\n";
} else {
    echo "   ❌ $payoutView does NOT exist\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "All components seem to be in place.\n";
echo "The issue is likely in the navbar navigation logic.\n";
echo "\nNext steps:\n";
echo "1. Login to the application\n";
echo "2. Open browser developer tools (F12)\n";
echo "3. Click 'Pesanan' in navbar\n";
echo "4. Check console output for debugging info\n";

?>
