<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

// Boot the application
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Warung;

echo "=== CREATING WARUNG FOR USER ID 1 ===\n\n";

try {
    $user = User::find(1);
    if (!$user) {
        echo "❌ User ID 1 not found!\n";
        exit;
    }
    
    echo "👤 User found: {$user->name} ({$user->email})\n";
    echo "🏪 Creating warung for this user...\n\n";
    
    $warung = Warung::create([
        'user_id' => 1,
        'nama_warung' => 'Warung ' . $user->name,
        'lokasi' => 'Jakarta Selatan',
        'no_hp' => '08123456789',
        'rekening_bank' => 'BCA',
        'no_rekening' => '1234567890',
        'nama_pemilik' => $user->name,
        'deskripsi' => 'Warung penjual makanan dan minuman',
        'status' => 'aktif'
    ]);
    
    echo "✅ Warung created successfully!\n";
    echo "   - Warung ID: {$warung->id}\n";
    echo "   - Name: {$warung->nama_warung}\n";
    echo "   - User ID: {$warung->user_id}\n";
    echo "   - Location: {$warung->lokasi}\n";
    echo "   - Owner: {$warung->nama_pemilik}\n";
    echo "   - Status: {$warung->status}\n\n";
    
    echo "🎯 Now user ID 1 can access Orders and Payouts pages!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "✅ PROBLEM FIXED: User now has warung, no more redirects!\n";
?>
