<?php

namespace App\Models;

use App\Events\NewMenuAdded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Menu extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', 
        'nama_menu', 
        'harga', 
        'stok', 
        'area_kampus', 
        'kategori', 
        'deskripsi', 
        'nama_warung', 
        'gambar',
    ];

    protected $appends = ['photo_url'];

    protected function casts(): array
    {
        return [
            'harga' => 'integer',
            'stok' => 'integer',
        ];
    }

    /**
     * Boot model events
     */
    protected static function booted(): void
    {
        static::created(function (Menu $menu) {
            event(new NewMenuAdded($menu));
        });
    }

    /**
     * Get full photo URL
     */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->gambar ? Storage::url($this->gambar) : null;
    }

    // Relationships
    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @return HasMany<MenuRating, $this>
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(MenuRating::class);
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_favorites', 'menu_id', 'user_id')
               ->withTimestamps();
    }

    /**
     * @return HasMany<GlobalOrderItem, $this>
     */
    public function globalOrderItems(): HasMany
    {
        return $this->hasMany(GlobalOrderItem::class);
    }

    /**
     * @return BelongsTo<Warung, $this>
     */
    public function warung(): BelongsTo
    {
        return $this->belongsTo(Warung::class, 'user_id', 'user_id');
    }

    // Helper methods
    public function averageRating(): float
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    public function isFavoritedBy(User $user): bool
    {
        return $this->favoritedBy()->where('user_id', $user->id)->exists();
    }
}