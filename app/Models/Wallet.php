<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'warung_id',
        'balance',
        'pending_balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
    ];

    /**
     * @return BelongsTo<Warung, $this>
     */
    public function warung(): BelongsTo
    {
        return $this->belongsTo(Warung::class);
    }

    public function addBalance($amount)
    {
        $this->increment('balance', $amount);
    }

    public function addPendingBalance($amount)
    {
        $this->increment('pending_balance', $amount);
    }

    public function moveToBalance($amount)
    {
        $this->decrement('pending_balance', $amount);
        $this->increment('balance', $amount);
    }

    public function withdrawBalance($amount)
    {
        if ($this->balance >= $amount) {
            $this->decrement('balance', $amount);
            return true;
        }
        return false;
    }
}
