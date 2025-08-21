<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model {
    protected $fillable = ['order_id', 'menu_id', 'jumlah', 'subtotal'];

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo<Menu, $this>
     */
    public function menu(): BelongsTo {
        return $this->belongsTo(Menu::class);
    }
}