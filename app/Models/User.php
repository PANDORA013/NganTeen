<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'last_login_at', 'qris_image', 'profile_photo'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Mendapatkan semua menu milik user (sebagai penjual).
     * 
     * @return HasMany
     */
    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

    /**
     * Mendapatkan semua pesanan yang dibuat user (sebagai pembeli).
     * 
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'pembeli_id');
    }

    /**
     * Mendapatkan keranjang belanja user.
     * 
     * @return HasOne
     */
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Mengecek apakah user berperan sebagai penjual.
     * 
     * @return bool
     */
    public function isPenjual(): bool
    {
        return $this->role === 'penjual';
    }

    /**
     * Mengecek apakah user berperan sebagai pembeli.
     * 
     * @return bool
     */
    public function isPembeli(): bool
    {
        return $this->role === 'pembeli';
    }
}