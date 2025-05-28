<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'food_id',
        'user_id',
        'quantity',
        'total_price',
        'status',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'total_price' => 'decimal:2',
    ];

    public function food(): BelongsTo
    {
        return $this->belongsTo(Food::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
