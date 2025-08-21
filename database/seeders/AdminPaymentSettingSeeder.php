<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AdminPaymentSetting;

class AdminPaymentSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdminPaymentSetting::create([
            'payment_method' => 'qris',
            'merchant_name' => 'NganTeen Official',
            'merchant_id' => 'NGT001',
            
            // Bank transfer backup
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'NganTeen Official',
            
            // Commission settings
            'platform_fee_percentage' => 5.00, // 5%
            'payment_fee_fixed' => 2500, // Rp 2,500
            
            'is_active' => true
        ]);
    }
}
