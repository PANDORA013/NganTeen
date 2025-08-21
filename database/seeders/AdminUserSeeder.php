<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus admin yang ada jika ada
        User::where('email', 'admin@nganteen.com')->delete();
        
        // Buat admin user yang fix
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@nganteen.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Admin123!@#'),
            'role' => 'admin', // Pastikan role admin
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $this->command->info('✅ Admin user berhasil dibuat:');
        $this->command->info('Email: admin@nganteen.com');
        $this->command->info('Password: Admin123!@#');
        $this->command->info('Role: admin');
        
        // Verifikasi admin
        $check = User::where('email', 'admin@nganteen.com')->first();
        if ($check && $check->role === 'admin') {
            $this->command->info('✅ Verifikasi: Admin user valid dan siap digunakan');
        } else {
            $this->command->error('❌ Error: Admin user tidak valid');
        }
    }
}
