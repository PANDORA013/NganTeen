<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            throw new RuntimeException('AdminUserSeeder tidak boleh dijalankan di production. Gunakan php artisan admin:create.');
        }

        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! $email || ! $password || strlen($password) < 12) {
            throw new RuntimeException('Isi ADMIN_EMAIL dan ADMIN_PASSWORD (minimal 12 karakter), atau gunakan php artisan admin:create.');
        }

        User::updateOrCreate([
            'email' => $email,
        ], [
            'name' => env('ADMIN_NAME', 'Administrator'),
            'email_verified_at' => now(),
            'password' => Hash::make($password),
            'role' => 'admin',
        ]);

        $this->command?->info("Admin {$email} berhasil dibuat atau diperbarui.");
    }
}
