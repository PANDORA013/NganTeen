<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodNews extends Model
{
    use HasFactory;

    protected $table = 'food_news';

    protected $fillable = [
        'title',
        'content',
        'image',
        'menu_id',
        'warung_id',
        'created_by',
        'type',
        'is_active',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    /**
     * Get the menu associated with this news
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Get the warung associated with this news
     */
    public function warung()
    {
        return $this->belongsTo(Warung::class);
    }

    /**
     * Get the user who created this news
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for active news
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for published news
     */
    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now());
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return $this->menu ? $this->menu->gambar_url : asset('images/default-food.jpg');
    }
}
