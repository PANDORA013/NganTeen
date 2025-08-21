<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Warung;
use App\Models\Wallet;
use App\Models\User;

class PaymentSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if we have penjual users
        $users = User::where('role', 'penjual')->take(2)->get();

        if ($users->count() < 2) {
            $this->command->info('Creating sample penjual users...');
            
            $user1 = User::create([
                'name' => 'Budi Santoso',
                'email' => 'budi@warung.com',
                'password' => bcrypt('password'),
                'role' => 'penjual'
            ]);
            
            $user2 = User::create([
                'name' => 'Siti Rahayu',
                'email' => 'siti@warung.com',
                'password' => bcrypt('password'),
                'role' => 'penjual'
            ]);
            
            $users = collect([$user1, $user2]);
        }

        $this->command->info('Creating warungs...');

        // Create warungs if they don't exist
        $warung1 = Warung::firstOrCreate(
            ['user_id' => $users[0]->id],
            [
                'nama_warung' => 'Warung Makan Sederhana',
                'nama_pemilik' => $users[0]->name,
                'lokasi' => 'Kantin Utama',
                'no_hp' => '081234567890',
                'rekening_bank' => 'BCA',
                'no_rekening' => '1234567890',
                'status' => 'aktif'
            ]
        );

        $warung2 = Warung::firstOrCreate(
            ['user_id' => $users[1]->id],
            [
                'nama_warung' => 'Warung Nasi Gudeg',
                'nama_pemilik' => $users[1]->name,
                'lokasi' => 'Kantin Timur',
                'no_hp' => '081987654321',
                'rekening_bank' => 'Mandiri',
                'no_rekening' => '0987654321',
                'status' => 'aktif'
            ]
        );

        $this->command->info('Creating wallets...');

        // Create wallets for each warung
        $wallet1 = Wallet::firstOrCreate(
            ['warung_id' => $warung1->id],
            [
                'balance' => 50000,
                'pending_balance' => 25000
            ]
        );

        $wallet2 = Wallet::firstOrCreate(
            ['warung_id' => $warung2->id],
            [
                'balance' => 100000,
                'pending_balance' => 30000
            ]
        );

        $this->command->info('Sample data created successfully!');
        $this->command->info("Warung 1: {$warung1->nama_warung} (Balance: Rp " . number_format($wallet1->balance) . ")");
        $this->command->info("Warung 2: {$warung2->nama_warung} (Balance: Rp " . number_format($wallet2->balance) . ")");
        
        // Create admin user if doesn't exist
        $admin = User::firstOrCreate(
            ['email' => 'admin@nganteen.com'],
            [
                'name' => 'Admin NganTeen',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]
        );
        
        $this->command->info("Admin user created: {$admin->email}");
    }
}
