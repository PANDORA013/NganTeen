<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Clear tables
        DB::table('foods')->truncate();
        DB::table('users')->truncate();

        // Create test users
        DB::table('users')->insert([
            [
                'name' => 'Penjual Test',
                'email' => 'penjual@test.com',
                'password' => Hash::make('password'),
                'role' => 'penjual',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Pembeli Test',
                'email' => 'pembeli@test.com',
                'password' => Hash::make('password'),
                'role' => 'pembeli',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }
}
