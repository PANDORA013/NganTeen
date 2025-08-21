<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Warung extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_warung',
        'lokasi',
        'no_hp',
        'rekening_bank',
        'no_rekening',
        'nama_pemilik',
        'deskripsi',
        'jam_operasional',
        'foto',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Alias for user relationship to represent warung owner
     * @return BelongsTo<User, $this>
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return HasMany<Menu, $this>
     */
    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, 'user_id', 'user_id');
    }

    /**
     * @return HasOne<Wallet, $this>
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * @return HasMany<GlobalOrderItem, $this>
     */
    public function globalOrderItems(): HasMany
    {
        return $this->hasMany(GlobalOrderItem::class);
    }

    /**
     * @return HasMany<Payout, $this>
     */
    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class);
    }

    public function getBalanceAttribute()
    {
        return $this->wallet?->balance ?? 0;
    }

    public function getPendingBalanceAttribute()
    {
        return $this->wallet?->pending_balance ?? 0;
    }

    public function getTotalOrdersAttribute()
    {
        return $this->globalOrderItems()->count();
    }

    public function getTotalRevenueAttribute()
    {
        return $this->globalOrderItems()->sum('subtotal');
    }
}
