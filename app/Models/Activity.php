<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'description',
        'properties',
        'subject_type',
        'subject_id',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * Get the user that performed the activity
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject model that the activity is performed on
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Scope for filtering by activity type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get recent activities
     */
    public static function recent($limit = 10)
    {
        return static::with('user')
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Log a new activity
     */
    public static function log($type, $description, $user = null, $subject = null, $properties = [])
    {
        return static::create([
            'user_id' => $user ? $user->id : auth()->id(),
            'type' => $type,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->id : null,
            'properties' => $properties,
        ]);
    }
}
