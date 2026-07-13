<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command?->warn('DatabaseSeeder dilewati di production. Jalankan seeder tertentu secara eksplisit bila diperlukan.');
            return;
        }

        // Create a test seller
        User::factory()->create([
            'name' => 'Test Seller',
            'email' => 'seller@example.com',
            'role' => 'penjual',
        ]);

        // Create a test buyer
        User::factory()->create([
            'name' => 'Test Buyer',
            'email' => 'buyer@example.com',
            'role' => 'pembeli',
        ]);

        // Create some random users
        User::factory(5)->create();
    }
}
