<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Food extends Model
{
    use SoftDeletes;

    protected $table = 'foods';

    protected $fillable = [
        'penjual_id',
        'nama',
        'harga',
        'foto',
        'lokasi_kampus',
        'description',
        'price',
        'photo_url',
        'location',
        'estimated_time',
        'status'
    ];

    protected $casts = [
        'price' => 'integer',
        'estimated_time' => 'integer',
        'status' => 'string',
    ];

    /**
     * Get the seller that owns the food
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function penjual()
    {
        return $this->belongsTo(User::class, 'penjual_id');
    }
}