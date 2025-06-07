<?php

namespace App\Models\Event;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DinnerOnly extends Model
{
    protected $fillable = ['order_item_id', 'dietary_restrictions'];

    protected $table = 'dinner_only';

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function scopeWherePaid($q)
    {
        return $q->whereHas('orderItem.order', fn ($qb) => $qb->where('status', 'paid'));
    }
}
