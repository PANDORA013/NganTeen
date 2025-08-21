<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Warung;

$user = User::where('email', 'thomas@penjual.com')->first();

if ($user) {
    echo "User found:\n";
    echo "ID: " . $user->id . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Role: " . $user->role . "\n";
    
    // Cek warung
    $warung = Warung::where('user_id', $user->id)->first();
    if ($warung) {
        echo "\nWarung found:\n";
        echo "ID: " . $warung->id . "\n";
        echo "Nama: " . $warung->nama_warung . "\n";
    } else {
        echo "\nWarung not found, creating...\n";
        $warung = Warung::create([
            'nama_warung' => 'Warung Thomas',
            'lokasi' => 'Jakarta Pusat',
            'no_hp' => '081234567890',
            'rekening_bank' => 'BCA',
            'no_rekening' => '1234567890',
            'nama_pemilik' => 'Thomas Sumihar Aruan',
            'deskripsi' => 'Warung makanan enak dan terjangkau',
            'user_id' => $user->id,
        ]);
        echo "Warung created with ID: " . $warung->id . "\n";
    }
} else {
    echo "User not found\n";
}
