<?php
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Create test pembeli if not exists
$pembeli = \App\Models\User::where('email', 'pembeli@test.com')->first();
if (!$pembeli) {
    $pembeli = \App\Models\User::create([
        'name' => 'Test Pembeli',
        'email' => 'pembeli@test.com',
        'password' => bcrypt('password'),
        'role' => 'pembeli',
        'email_verified_at' => now(),
    ]);
    echo "Created Test Pembeli: pembeli@test.com / password\n";
} else {
    echo "Test Pembeli already exists: pembeli@test.com\n";
}

// Create test penjual if not exists
$penjual = \App\Models\User::where('email', 'penjual@test.com')->first();
if (!$penjual) {
    $penjual = \App\Models\User::create([
        'name' => 'Test Penjual',
        'email' => 'penjual@test.com',
        'password' => bcrypt('password'),
        'role' => 'penjual',
        'email_verified_at' => now(),
    ]);
    echo "Created Test Penjual: penjual@test.com / password\n";
} else {
    echo "Test Penjual already exists: penjual@test.com\n";
}

echo "\n=== LOGIN CREDENTIALS UNTUK TESTING ===\n";
echo "PEMBELI:\n";
echo "Email: pembeli@test.com\n";
echo "Password: password\n\n";

echo "PENJUAL:\n"; 
echo "Email: penjual@test.com\n";
echo "Password: password\n\n";

echo "ADMIN:\n";
echo "Email: admin@nganteen.com\n";
echo "Password: Admin123!@#\n";
