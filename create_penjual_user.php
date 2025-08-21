<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Warung;
use Illuminate\Support\Facades\Hash;

// Buat user penjual
$user = User::create([
    'name' => 'Thomas Sumihar Aruan',
    'email' => 'thomas@penjual.com',
    'password' => Hash::make('password123'),
    'role' => 'penjual',
    'email_verified_at' => now(),
]);

echo "User penjual berhasil dibuat:\n";
echo "ID: " . $user->id . "\n";
echo "Name: " . $user->name . "\n";
echo "Email: " . $user->email . "\n";
echo "Role: " . $user->role . "\n";

// Buat warung untuk user ini
$warung = Warung::create([
    'nama_warung' => 'Warung Thomas',
    'alamat' => 'Jl. Contoh No. 123',
    'lokasi' => 'Jakarta Pusat',
    'nomor_telepon' => '081234567890',
    'deskripsi' => 'Warung makanan enak dan terjangkau',
    'user_id' => $user->id,
]);

echo "\nWarung berhasil dibuat:\n";
echo "ID: " . $warung->id . "\n";
echo "Nama: " . $warung->nama_warung . "\n";
echo "User ID: " . $warung->user_id . "\n";
