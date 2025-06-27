<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model {
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'status',
        'total_harga',
        'payment_method',
        'payment_status',
        'delivery_address',
        'notes'
    ];

    /**
     * Relasi ke model User
     * 
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model OrderItem
     * 
     * @return HasMany
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relasi ke model Menu melalui OrderItem
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function menus()
    {
        return $this->hasManyThrough(
            Menu::class,
            OrderItem::class,
            'order_id', // Foreign key on order_items table
            'id', // Foreign key on menus table
            'id', // Local key on orders table
            'menu_id' // Local key on order_items table
        );
    }
}