<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GlobalOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'global_order_id',
        'warung_id',
        'menu_id',
        'quantity',
        'unit_price',
        'subtotal',
        'status',
        'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'quantity' => 'integer',
    ];

    /**
     * @return BelongsTo<GlobalOrder, $this>
     */
    public function globalOrder(): BelongsTo
    {
        return $this->belongsTo(GlobalOrder::class);
    }

    /**
     * @return BelongsTo<Warung, $this>
     */
    public function warung(): BelongsTo
    {
        return $this->belongsTo(Warung::class);
    }

    /**
     * @return BelongsTo<Menu, $this>
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function canBeUpdated(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function markAsReady()
    {
        $this->update(['status' => 'ready']);
    }

    public function markAsCompleted()
    {
        $this->update(['status' => 'completed']);
    }
}
