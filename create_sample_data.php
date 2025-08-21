<?php

use App\Models\Warung;
use App\Models\Wallet;
use App\Models\User;

// Create sample warungs and wallets
$users = User::where('role', 'penjual')->take(2)->get();

if ($users->count() < 2) {
    echo "Creating sample penjual users...\n";
    
    $user1 = User::create([
        'name' => 'Budi Santoso',
        'email' => 'budi@warung.com',
        'password' => bcrypt('password'),
        'role' => 'penjual'
    ]);
    
    $user2 = User::create([
        'name' => 'Siti Rahayu',
        'email' => 'siti@warung.com',
        'password' => bcrypt('password'),
        'role' => 'penjual'
    ]);
    
    $users = collect([$user1, $user2]);
}

echo "Creating warungs...\n";

$warung1 = Warung::create([
    'user_id' => $users[0]->id,
    'nama_warung' => 'Warung Makan Sederhana',
    'nama_pemilik' => $users[0]->name,
    'lokasi' => 'Kantin Utama',
    'no_hp' => '081234567890',
    'rekening_bank' => 'BCA',
    'no_rekening' => '1234567890',
    'status' => 'aktif'
]);

$warung2 = Warung::create([
    'user_id' => $users[1]->id,
    'nama_warung' => 'Warung Nasi Gudeg',
    'nama_pemilik' => $users[1]->name,
    'lokasi' => 'Kantin Timur',
    'no_hp' => '081987654321',
    'rekening_bank' => 'Mandiri',
    'no_rekening' => '0987654321',
    'status' => 'aktif'
]);

echo "Creating wallets...\n";

// Create wallets for each warung
$wallet1 = Wallet::create([
    'warung_id' => $warung1->id,
    'available_balance' => 50000,
    'pending_balance' => 25000,
    'total_balance' => 75000
]);

$wallet2 = Wallet::create([
    'warung_id' => $warung2->id,
    'available_balance' => 100000,
    'pending_balance' => 30000,
    'total_balance' => 130000
]);

echo "Sample data created successfully!\n";
echo "Warung 1: {$warung1->nama_warung} (Balance: Rp " . number_format($wallet1->available_balance) . ")\n";
echo "Warung 2: {$warung2->nama_warung} (Balance: Rp " . number_format($wallet2->available_balance) . ")\n";
