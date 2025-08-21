<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role', 
        'last_login_at', 
        'password_updated_at',
        'qris_image', 
        'profile_photo',
        'phone',
        'is_online',
        'last_activity',
        'registration_date'
    ];

    protected $hidden = [
        'password', 
        'remember_token'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'password_updated_at' => 'datetime',
            'last_activity' => 'datetime',
            'registration_date' => 'datetime',
            'is_online' => 'boolean',
        ];
    }

    /**
     * Boot model events for data cleanup
     */
    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            // Clean up cart items
            $user->carts()->delete();
            
            // Clean up seller-specific data
            if ($user->isPenjual()) {
                $user->cleanupSellerData();
            }
        });
    }

    /**
     * Clean up seller data when account is deleted
     */
    private function cleanupSellerData(): void
    {
        $this->menus->each(function ($menu) {
            if ($menu->gambar && Storage::disk('public')->exists($menu->gambar)) {
                Storage::disk('public')->delete($menu->gambar);
            }
        });
        
        $this->menus()->delete();
    }

    /**
     * Get user's menus (as seller)
     * 
     * @return HasMany<Menu, $this>
     */
    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

    /**
     * Get user's orders (as buyer)
     * 
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get user's cart items
     * 
     * @return HasMany<Cart, $this>
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Check if user is a seller
     */
    public function isPenjual(): bool
    {
        return $this->role === 'penjual';
    }

    /**
     * Check if user is a buyer
     */
    public function isPembeli(): bool
    {
        return $this->role === 'pembeli';
    }
    
    /**
     * New payment system relationships
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<Warung, $this>
     */
    public function warung(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Warung::class);
    }

    /**
     * @return HasMany<GlobalOrder, $this>
     */
    public function globalOrders(): HasMany
    {
        return $this->hasMany(GlobalOrder::class, 'buyer_id');
    }

    /**
     * @return HasMany<Transaction, $this>
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    /**
     * @return HasMany<Payout, $this>
     */
    public function processedPayouts(): HasMany
    {
        return $this->hasMany(Payout::class, 'processed_by');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Update user activity
     */
    public function updateActivity()
    {
        $this->update([
            'last_activity' => now(),
            'is_online' => true
        ]);
    }

    /**
     * Set user offline
     */
    public function setOffline()
    {
        $this->update(['is_online' => false]);
    }

    /**
     * Get online status text
     */
    public function getOnlineStatusAttribute()
    {
        if ($this->is_online) {
            return 'Online';
        }

        if ($this->last_activity) {
            return 'Last seen ' . $this->last_activity->diffForHumans();
        }

        return 'Never logged in';
    }

    /**
     * Get registration time in human format
     */
    public function getRegistrationTimeAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}