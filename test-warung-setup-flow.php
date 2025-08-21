<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

// Boot the application
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Warung;

echo "=== TESTING WARUNG SETUP SYSTEM ===\n\n";

try {
    // Delete warung for user ID 1 to test the setup flow
    echo "1. Deleting warung for user ID 1 to test setup flow...\n";
    $deleted = Warung::where('user_id', 1)->delete();
    echo "   Deleted {$deleted} warung(s) for user ID 1\n\n";
    
    echo "2. Current warung status:\n";
    $allWarungs = Warung::all(['id', 'user_id', 'nama_warung']);
    foreach ($allWarungs as $warung) {
        echo "   Warung ID: {$warung->id} | User ID: {$warung->user_id} | Name: {$warung->nama_warung}\n";
    }
    
    if ($allWarungs->count() == 0) {
        echo "   ❌ NO WARUNGS IN DATABASE\n";
    }
    
    echo "\n3. Testing scenario:\n";
    echo "   - User ID 1 now has NO warung\n";
    echo "   - When they try to access Orders/Payouts, they'll be redirected to warung setup\n";
    echo "   - They can register their warung through the new form\n";
    echo "   - After registration, they can access all features\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "✅ READY TO TEST: Login as user ID 1 and try accessing Orders/Payouts\n";
echo "   Expected: Redirect to warung setup page\n";
?>
