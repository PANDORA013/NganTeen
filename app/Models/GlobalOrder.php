<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GlobalOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'order_number',
        'total_amount',
        'platform_fee',
        'payment_fee',
        'gross_amount',
        'net_amount',
        'status',
        'payment_status',
        'payment_method',
        'admin_qris_used',
        'payment_reference',
        'paid_at',
        'is_settled',
        'settled_at',
        'settled_by',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'payment_fee' => 'decimal:2',
        'gross_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'is_settled' => 'boolean',
        'settled_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            $order->order_number = 'NGT-' . strtoupper(uniqid());
        });
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * @return HasMany<GlobalOrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(GlobalOrderItem::class);
    }

    /**
     * @return HasOne<Transaction, $this>
     */
    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function settledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'settled_by');
    }

    public function getWarungsAttribute()
    {
        return $this->items()->with('warung')->get()->groupBy('warung_id');
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'paid']);
    }

    public function markAsPaid($paymentReference = null)
    {
        $this->update([
            'status' => 'paid',
            'payment_status' => 'paid',
            'payment_reference' => $paymentReference,
            'paid_at' => now(),
        ]);
    }

    // Calculate fees and amounts
    public function calculateAmounts()
    {
        $paymentSetting = AdminPaymentSetting::getActive();
        
        $this->platform_fee = $paymentSetting->calculatePlatformFee($this->total_amount);
        $this->payment_fee = $paymentSetting->payment_fee_fixed;
        $this->gross_amount = $paymentSetting->calculateGrossAmount($this->total_amount);
        $this->net_amount = $paymentSetting->calculateNetAmount($this->total_amount);
        
        return $this;
    }

    // Settlement methods
    public function markAsSettled($adminId = null)
    {
        $this->update([
            'is_settled' => true,
            'settled_at' => now(),
            'settled_by' => $adminId ?? auth()->id()
        ]);
        
        // Distribute amounts to warung wallets
        $this->distributeToWarungs();
        
        return $this;
    }

    public function distributeToWarungs()
    {
        if (!$this->is_settled || $this->payment_status !== 'paid') {
            return false;
        }

        $warungs = $this->getWarungsAttribute();
        
        foreach ($warungs as $warungId => $items) {
            $warung = Warung::find($warungId);
            if (!$warung || !$warung->wallet) continue;
            
            $warungTotal = $items->sum('subtotal');
            $paymentSetting = AdminPaymentSetting::getActive();
            $netAmount = $paymentSetting->calculateNetAmount($warungTotal);
            
            // Add to warung wallet
            $warung->wallet->addBalance($netAmount);
        }
        
        return true;
    }

    // Status helpers
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    public function isSettled(): bool
    {
        return $this->is_settled;
    }

    public function needsSettlement(): bool
    {
        return $this->isPaid() && !$this->isSettled();
    }
}
