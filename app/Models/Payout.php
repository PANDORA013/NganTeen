<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'warung_id',
        'payout_number',
        'amount',
        'status',
        'method',
        'bank_name',
        'account_number',
        'account_name',
        'reference_number',
        'notes',
        'processed_at',
        'completed_at',
        'failed_at',
        'processed_by',
        'completed_by',
        'failed_by',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($payout) {
            $payout->payout_number = 'PAY-' . strtoupper(uniqid());
        });
    }

    /**
     * @return BelongsTo<Warung, $this>
     */
    public function warung(): BelongsTo
    {
        return $this->belongsTo(Warung::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function markAsProcessing()
    {
        $this->update(['status' => 'processing']);
    }

    public function markAsSuccess($referenceNumber = null)
    {
        $this->update([
            'status' => 'success',
            'reference_number' => $referenceNumber,
            'processed_at' => now(),
        ]);
    }

    public function markAsFailed($notes = null)
    {
        $this->update([
            'status' => 'failed',
            'notes' => $notes,
            'processed_at' => now(),
        ]);
    }
}
