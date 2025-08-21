<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Menu;
use App\Models\User;

// Get penjual user
$user = User::where('email', 'thomas@penjual.com')->first();

if (!$user) {
    echo "User penjual tidak ditemukan!\n";
    exit;
}

// Create sample menus
$menus = [
    [
        'nama_menu' => 'Nasi Gudeg Jogja',
        'harga' => 15000,
        'stok' => 20,
        'area_kampus' => 'Kampus A',
        'kategori' => 'Makanan',
        'deskripsi' => 'Nasi gudeg asli Jogja dengan ayam kampung, telur, dan sambal krecek yang mantap',
        'nama_warung' => 'Warung Thomas',
        'user_id' => $user->id,
    ],
    [
        'nama_menu' => 'Mie Ayam Spesial',
        'harga' => 12000,
        'stok' => 15,
        'area_kampus' => 'Kampus A',
        'kategori' => 'Makanan',
        'deskripsi' => 'Mie ayam dengan potongan ayam besar, pangsit goreng, dan kuah kaldu yang gurih',
        'nama_warung' => 'Warung Thomas',
        'user_id' => $user->id,
    ],
    [
        'nama_menu' => 'Es Teh Manis',
        'harga' => 3000,
        'stok' => 50,
        'area_kampus' => 'Kampus A',
        'kategori' => 'Minuman',
        'deskripsi' => 'Es teh manis segar untuk menemani makan siang Anda',
        'nama_warung' => 'Warung Thomas',
        'user_id' => $user->id,
    ],
    [
        'nama_menu' => 'Bakso Malang',
        'harga' => 18000,
        'stok' => 10,
        'area_kampus' => 'Kampus B',
        'kategori' => 'Makanan',
        'deskripsi' => 'Bakso malang dengan isian tahu, siomay, pangsit, dan kuah kaldu sapi yang kental',
        'nama_warung' => 'Warung Thomas',
        'user_id' => $user->id,
    ],
    [
        'nama_menu' => 'Keripik Singkong',
        'harga' => 5000,
        'stok' => 0,
        'area_kampus' => 'Kampus A',
        'kategori' => 'Snack',
        'deskripsi' => 'Keripik singkong renyah dengan berbagai varian rasa',
        'nama_warung' => 'Warung Thomas',
        'user_id' => $user->id,
    ],
];

foreach ($menus as $menuData) {
    Menu::create($menuData);
    echo "Menu '{$menuData['nama_menu']}' berhasil dibuat\n";
}

echo "\nTotal " . count($menus) . " menu berhasil dibuat!\n";
