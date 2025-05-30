<?php

namespace App\Models;

use App\Models\Event\DinnerOnly;
use App\Models\Event\Golfer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Order $order
 */
class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'product_id', 'quantity', 'unit_price_cents', 'metadata',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price_cents' => 'integer',
        'metadata' => 'array',
    ];

    protected $appends = ['unit_price_dollars'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function golfers(): HasMany
    {
        return $this->hasMany(Golfer::class);
    }

    public function dinners(): HasMany
    {
        return $this->hasMany(DinnerOnly::class);
    }

    public function getUnitPriceDollarsAttribute(): float
    {
        return $this->unit_price_cents / 100;
    }
}
