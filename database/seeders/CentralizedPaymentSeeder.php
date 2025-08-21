<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminPaymentSetting;
use App\Models\GlobalOrder;
use App\Models\Warung;
use App\Models\User;
use Carbon\Carbon;

class CentralizedPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin payment settings
        AdminPaymentSetting::create([
            'merchant_name' => 'NganTeen Platform',
            'merchant_id' => 'NGANTEEN001',
            'platform_fee_percentage' => 5.0,
            'payment_fee_fixed' => 2500,
            'qris_image' => null,
            'bank_name' => 'Bank Central Asia (BCA)',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'PT NganTeen Indonesia',
            'is_active' => true,
        ]);

        // Get existing warungs and users
        $warungs = Warung::all();
        $pembeli = User::where('role', 'pembeli')->first();

        if ($warungs->isEmpty() || !$pembeli) {
            $this->command->info('Warning: No warungs or pembeli found. Please run WarungSeeder and UserSeeder first.');
            return;
        }

        // Create sample orders for testing
        $this->createSampleOrders($warungs, $pembeli);
    }

    private function createSampleOrders($warungs, $pembeli)
    {
        $setting = AdminPaymentSetting::first();
        
        // Create 15 sample orders with different statuses
        for ($i = 1; $i <= 15; $i++) {
            $warung = $warungs->random();
            $orderAmount = rand(25000, 150000);
            
            // Calculate fees using AdminPaymentSetting
            $platformFee = $setting->calculatePlatformFee($orderAmount);
            $grossAmount = $setting->calculateGrossAmount($orderAmount);
            $netAmount = $setting->calculateNetAmount($orderAmount);
            
            $order = GlobalOrder::create([
                'order_number' => 'GO' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'buyer_id' => $pembeli->id,
                'warung_id' => $warung->id,
                'customer_name' => $pembeli->name,
                'customer_phone' => $pembeli->phone ?? '081234567890',
                'customer_email' => $pembeli->email,
                'total_amount' => $orderAmount,
                'platform_fee' => $platformFee,
                'payment_fee' => $setting->payment_fee_fixed,
                'gross_amount' => $grossAmount,
                'net_amount' => $netAmount,
                'payment_method' => 'qris_admin',
                'payment_status' => $this->getRandomPaymentStatus($i),
                'status' => $this->getRandomOrderStatus($i),
                'notes' => "Sample order #$i for testing centralized payment system",
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
                'updated_at' => Carbon::now(),
            ]);

            // For paid orders, randomly settle some of them
            if ($order->payment_status === 'paid') {
                $shouldSettle = rand(0, 100) < 70; // 70% chance to be settled
                
                if ($shouldSettle) {
                    $order->is_settled = true;
                    $order->settled_at = Carbon::now()->subDays(rand(0, 15));
                    $order->settlement_notes = "Auto-settled via sample data";
                    $order->save();
                }
            }
        }

        $this->command->info('Created 15 sample orders with varying payment and settlement statuses.');
    }

    private function getRandomPaymentStatus($index)
    {
        // Create variety of payment statuses for testing
        if ($index <= 10) {
            return 'paid'; // Most orders are paid for settlement testing
        } elseif ($index <= 13) {
            return 'pending';
        } else {
            return 'failed';
        }
    }

    private function getRandomOrderStatus($index)
    {
        $statuses = ['pending', 'paid', 'processing', 'completed', 'cancelled'];
        
        if ($index <= 10) {
            return $statuses[array_rand(['paid', 'processing', 'completed'])];
        } else {
            return $statuses[array_rand($statuses)];
        }
    }
}
