<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FoodNews;
use App\Models\Menu;
use App\Models\Warung;
use App\Models\User;
use Carbon\Carbon;

class FoodNewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $warungs = Warung::take(3)->get();
        $menus = Menu::take(5)->get();

        if (!$admin) {
            $this->command->warn('No admin user found. Creating news without creator.');
            return;
        }

        // Create sample food news
        $newsData = [
            [
                'title' => 'Menu Baru: Nasi Gudeg Istimewa',
                'content' => 'Warung Pak Slamet kini menyajikan nasi gudeg istimewa dengan bumbu rahasia turun temurun. Dikemas dengan cita rasa autentik Yogyakarta yang menggugah selera.',
                'type' => 'new_menu',
                'warung_id' => $warungs->first()?->id,
                'menu_id' => $menus->first()?->id,
            ],
            [
                'title' => 'Promo Spesial: Beli 2 Gratis 1 Bakso',
                'content' => 'Dapatkan promo fantastis beli 2 bakso gratis 1 di Warung Bu Yanti. Promo berlaku hingga akhir bulan ini. Jangan sampai terlewat!',
                'type' => 'promo',
                'warung_id' => $warungs->get(1)?->id,
            ],
            [
                'title' => 'Gado-Gado Segar dengan Bumbu Kacang Spesial',
                'content' => 'Menu gado-gado dengan sayuran segar dan bumbu kacang racikan khusus kini tersedia. Cocok untuk yang sedang diet sehat dan bergizi.',
                'type' => 'new_menu',
                'warung_id' => $warungs->get(2)?->id,
                'menu_id' => $menus->get(1)?->id,
            ],
            [
                'title' => 'Pengumuman: Warung Tutup Sementara',
                'content' => 'Warung Pak Budi akan tutup sementara untuk renovasi selama 3 hari. Mohon maaf atas ketidaknyamanannya. Terima kasih.',
                'type' => 'announcement',
                'warung_id' => $warungs->first()?->id,
            ],
            [
                'title' => 'Soto Ayam Kuning Pedas Level 5',
                'content' => 'Tantangan baru! Coba soto ayam kuning pedas level 5 untuk pecinta makanan pedas sejati. Berhasil habiskan dapat merchandise gratis!',
                'type' => 'new_menu',
                'warung_id' => $warungs->get(1)?->id,
                'menu_id' => $menus->get(2)?->id,
            ],
        ];

        foreach ($newsData as $index => $data) {
            FoodNews::create([
                'title' => $data['title'],
                'content' => $data['content'],
                'type' => $data['type'],
                'warung_id' => $data['warung_id'] ?? null,
                'menu_id' => $data['menu_id'] ?? null,
                'created_by' => $admin->id,
                'is_active' => true,
                'published_at' => Carbon::now()->subDays(rand(0, 7)),
                'created_at' => Carbon::now()->subDays(rand(0, 10)),
                'updated_at' => Carbon::now(),
            ]);
        }

        $this->command->info('Created 5 sample food news items.');
    }
}
