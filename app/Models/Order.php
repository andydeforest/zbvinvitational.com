<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    // keep the default auto‐incrementing PK
    public $incrementing = true;

    protected $keyType = 'int';

    protected static function booted()
    {
        static::creating(function ($order) {
            $order->public_id = (string) Str::uuid();
        });
    }

    protected $fillable = [
        'public_id', 'status', 'total_cents', 'first_name', 'last_name', 'address', 'city', 'state', 'zip',
        'phone', 'email', 'notes', 'stripe_payment_intent_id', 'stripe_charge_id', 'paid_at',
    ];

    protected $casts = [
        'total_cents' => 'integer',
        'paid_at' => 'datetime',
    ];

    protected $appends = ['total_dollars'];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public function getTotalDollarsAttribute(): float
    {
        return $this->total_cents / 100;
    }
}
