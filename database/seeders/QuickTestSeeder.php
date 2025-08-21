<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Warung;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;

class QuickTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@nganteen.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567890',
                'email_verified_at' => now(),
            ]
        );

        // Create pembeli user if not exists
        $pembeli = User::firstOrCreate(
            ['email' => 'customer@test.com'],
            [
                'name' => 'Customer Test',
                'password' => Hash::make('password'),
                'role' => 'pembeli',
                'phone' => '081234567891',
                'email_verified_at' => now(),
            ]
        );

        // Create penjual users and warungs
        for ($i = 1; $i <= 3; $i++) {
            $penjual = User::firstOrCreate(
                ['email' => "penjual$i@test.com"],
                [
                    'name' => "Penjual $i",
                    'password' => Hash::make('password'),
                    'role' => 'penjual',
                    'phone' => '08123456789' . $i,
                    'email_verified_at' => now(),
                ]
            );

            $warung = Warung::firstOrCreate(
                ['user_id' => $penjual->id],
                [
                    'nama_warung' => "Warung Makan $i",
                    'lokasi' => "Jl. Test No. $i, Jakarta",
                    'no_hp' => $penjual->phone,
                    'rekening_bank' => 'BCA',
                    'no_rekening' => '12345678' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'nama_pemilik' => $penjual->name,
                    'deskripsi' => "Warung makan enak nomor $i",
                    'status' => 'aktif',
                ]
            );

            // Create wallet for warung if not exists
            Wallet::firstOrCreate(
                ['warung_id' => $warung->id],
                ['balance' => 0]
            );
        }

        $this->command->info('Created admin, pembeli, 3 penjual users and warungs with wallets.');
    }
}
