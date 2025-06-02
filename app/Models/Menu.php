<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    protected $fillable = [
        'user_id', 'nama_menu', 'harga', 'stok', 'area_kampus', 'nama_warung',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}