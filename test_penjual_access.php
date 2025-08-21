<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

// Cek user penjual
$user = User::where('email', 'thomas@penjual.com')->first();

if (!$user) {
    echo "User tidak ditemukan!\n";
    exit;
}

echo "User ditemukan: " . $user->name . " (Role: " . $user->role . ")\n";

// Test password
if (Hash::check('password123', $user->password)) {
    echo "Password benar\n";
} else {
    echo "Password salah\n";
    exit;
}

// Test manual login
Auth::login($user);

if (Auth::check()) {
    echo "Login berhasil!\n";
    echo "Current user: " . Auth::user()->name . "\n";
    echo "Current role: " . Auth::user()->role . "\n";
} else {
    echo "Login gagal!\n";
}

// Test akses ke menu
try {
    $menuController = new \App\Http\Controllers\MenuController();
    echo "\nTesting MenuController...\n";
    echo "MenuController tersedia\n";
} catch (Exception $e) {
    echo "Error dengan MenuController: " . $e->getMessage() . "\n";
}
