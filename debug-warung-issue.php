<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

// Boot the application
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Warung;

echo "=== DEBUGGING PAYOUTS REDIRECT ISSUE ===\n\n";

try {
    // Check all penjual users and their warungs
    echo "1. Checking all penjual users and their warungs:\n";
    $penjualUsers = User::where('role', 'penjual')->get(['id', 'name', 'email']);
    
    foreach ($penjualUsers as $user) {
        echo "   User ID: {$user->id} | Name: {$user->name} | Email: {$user->email}\n";
        
        $warung = Warung::where('user_id', $user->id)->first();
        if ($warung) {
            echo "     ✅ Has warung: ID={$warung->id}, Name='{$warung->nama_warung}'\n";
        } else {
            echo "     ❌ NO WARUNG FOUND - This will cause redirect to dashboard!\n";
        }
        echo "\n";
    }
    
    echo "2. Checking Warung table:\n";
    $allWarungs = Warung::all(['id', 'user_id', 'nama_warung']);
    foreach ($allWarungs as $warung) {
        echo "   Warung ID: {$warung->id} | User ID: {$warung->user_id} | Name: {$warung->nama_warung}\n";
    }
    
    if ($allWarungs->count() == 0) {
        echo "   ❌ NO WARUNGS FOUND IN DATABASE!\n";
        echo "   This is why payouts page redirects to dashboard.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "SOLUTION: Create warung for penjual users to fix redirect issue.\n";
?>
