<?php
require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Models\User;
use App\Models\Warung;

echo "=== CHECKING PENJUAL USERS ===\n\n";

try {
    $penjualUsers = User::where('role', 'penjual')->get(['id', 'name', 'email', 'role']);
    
    if ($penjualUsers->count() > 0) {
        echo "Found " . $penjualUsers->count() . " penjual users:\n";
        foreach ($penjualUsers as $user) {
            echo "- ID: {$user->id} | Name: {$user->name} | Email: {$user->email}\n";
            
            // Check if user has warung
            $warung = Warung::where('user_id', $user->id)->first();
            if ($warung) {
                echo "  ✅ Has warung: {$warung->nama_warung}\n";
            } else {
                echo "  ❌ No warung found\n";
            }
        }
    } else {
        echo "❌ No penjual users found!\n";
        echo "Creating a test penjual user...\n";
        
        // Create test user
        $testUser = User::create([
            'name' => 'Test Penjual',
            'email' => 'penjual@test.com',
            'password' => bcrypt('password123'),
            'role' => 'penjual',
            'email_verified_at' => now()
        ]);
        
        echo "✅ Created test user: {$testUser->email} (password: password123)\n";
        
        // Create test warung
        $testWarung = Warung::create([
            'user_id' => $testUser->id,
            'nama_warung' => 'Test Warung',
            'deskripsi' => 'Test warung for navigation',
            'alamat' => 'Test Address',
            'nomor_telepon' => '08123456789'
        ]);
        
        echo "✅ Created test warung: {$testWarung->nama_warung}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== CREDENTIALS FOR LOGIN ===\n";
echo "Email: penjual@test.com\n";
echo "Password: password123\n";
echo "Role: penjual\n";
echo "\nLogin URL: http://localhost:8000/login\n";
?>
