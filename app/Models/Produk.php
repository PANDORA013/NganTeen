<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Produk extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'produk';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'nama_produk',
        'deskripsi_produk',
        'harga_produk',
        'jumlah_produk',
        'foto',
        'penjual_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'harga_produk' => 'decimal:2',
        'jumlah_produk' => 'integer',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the seller that owns the product.
     */
    public function penjual()
    {
        return $this->belongsTo(User::class, 'penjual_id');
    }
}
