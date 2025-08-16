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
        'user_id', 'nama_menu', 'harga', 'stok', 'area_kampus', 'nama_warung', 'gambar',
    ];

    protected $appends = ['photo_url'];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (Menu $menu) {
            // Dispatch event when new menu is created
            event(new NewMenuAdded($menu));
        });
    }

    /**
     * Get full photo URL
     * 
     * @return string|null
     */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->gambar ? Storage::url($this->gambar) : null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, OrderItem::class, 'menu_id', 'id', 'id', 'order_id');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(MenuRating::class);
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_favorites', 'menu_id', 'user_id')->withTimestamps();
    }

    public function recommendedMenus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'menu_recommendations', 'menu_id', 'recommended_id');
    }

    public function averageRating(): float
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    public function isFavoritedBy(User $user): bool
    {
        return $this->favoritedBy()->where('user_id', $user->id)->exists();
    }
}