<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'global_order_id',
        'buyer_id',
        'transaction_id',
        'amount',
        'type',
        'status',
        'payment_gateway',
        'gateway_reference',
        'gateway_response',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'processed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($transaction) {
            $transaction->transaction_id = 'TXN-' . strtoupper(uniqid());
        });
    }

    /**
     * @return BelongsTo<GlobalOrder, $this>
     */
    public function globalOrder(): BelongsTo
    {
        return $this->belongsTo(GlobalOrder::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function markAsSuccess()
    {
        $this->update([
            'status' => 'success',
            'processed_at' => now(),
        ]);
    }

    public function markAsFailed()
    {
        $this->update([
            'status' => 'failed',
            'processed_at' => now(),
        ]);
    }
}
