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

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get all menus owned by the user (as penjual).
     */
    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

    /**
     * Get all orders made by the user (as pembeli).
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'pembeli_id');
    }

    /**
     * Get the user's cart.
     */
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Check if user is a seller.
     * 
     * @return bool
     */
    public function isPenjual(): bool
    {
        return $this->role === 'penjual'; // Value must match 'penjual' in the database
    }

    /**
     * Check if user is a buyer.
     */
    public function isPembeli(): bool
    {
        return $this->role === 'pembeli';
    }
}