<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model {
    use HasFactory;    protected $fillable = [
        'pembeli_id',
        'total_harga',
        'status'
    ];

    // Relasi: order punya banyak order item
    public function items(): HasMany {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the user (pembeli) that owns the order.
     */
    public function pembeli(): BelongsTo {
        return $this->belongsTo(User::class, 'pembeli_id');
    }
}