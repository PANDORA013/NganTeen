<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminPaymentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_method',
        'qris_image',
        'merchant_name',
        'merchant_id',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'platform_fee_percentage',
        'payment_fee_fixed',
        'payment_gateway',
        'gateway_config',
        'is_active'
    ];

    protected $casts = [
        'platform_fee_percentage' => 'decimal:2',
        'payment_fee_fixed' => 'decimal:2',
        'is_active' => 'boolean',
        'gateway_config' => 'array'
    ];

    // Get active payment setting
    public static function getActive()
    {
        return static::where('is_active', true)->first() ?? static::getDefault();
    }

    // Get default payment setting
    public static function getDefault()
    {
        return new static([
            'payment_method' => 'qris',
            'merchant_name' => 'NganTeen Official',
            'platform_fee_percentage' => 5.00,
            'payment_fee_fixed' => 2500,
            'is_active' => true
        ]);
    }

    // Calculate platform fee for order
    public function calculatePlatformFee($amount)
    {
        return ($amount * $this->platform_fee_percentage) / 100;
    }

    // Calculate total gross amount (amount + fees)
    public function calculateGrossAmount($amount)
    {
        $platformFee = $this->calculatePlatformFee($amount);
        return $amount + $platformFee + $this->payment_fee_fixed;
    }

    // Calculate net amount for warungs (after fees)
    public function calculateNetAmount($amount)
    {
        $platformFee = $this->calculatePlatformFee($amount);
        return $amount - $platformFee;
    }

    // Get QRIS image URL
    public function getQrisImageUrlAttribute()
    {
        if ($this->qris_image) {
            return asset('storage/' . $this->qris_image);
        }
        return null;
    }

    // Check if QRIS payment is available
    public function isQrisAvailable()
    {
        return $this->payment_method === 'qris' && $this->qris_image;
    }

    // Check if bank transfer is available
    public function isBankTransferAvailable()
    {
        return $this->bank_name && $this->bank_account_number && $this->bank_account_name;
    }
}
