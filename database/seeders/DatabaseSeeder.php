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
