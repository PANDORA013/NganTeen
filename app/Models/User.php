<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

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
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // Clean up related data when user is deleted
        static::deleting(function (User $user) {
            // Delete all cart items for this user
            $user->carts()->delete();
            
            // For penjual (seller), handle their menus
            if ($user->isPenjual()) {
                // Delete menu images from storage
                $user->menus->each(function ($menu) {
                    if ($menu->gambar && Storage::disk('public')->exists($menu->gambar)) {
                        Storage::disk('public')->delete($menu->gambar);
                    }
                });
                
                // Delete all menus created by this seller
                $user->menus()->delete();
            }
            
            // For pembeli (buyer), their orders can remain for seller records
            // but we can anonymize the buyer information if needed
            if ($user->isPembeli()) {
                // Optionally anonymize orders instead of deleting them
                // This preserves seller's order history while protecting user privacy
                $user->orders()->update([
                    'pembeli_email' => 'deleted_user@example.com',
                    'pembeli_phone' => 'Akun telah dihapus'
                ]);
            }
        });
    }

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
     * @return HasMany
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Mendapatkan keranjang belanja user (single item).
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