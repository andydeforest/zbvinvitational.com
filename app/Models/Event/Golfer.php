<?php

namespace App\Models\Event;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Golfer extends Model
{
    use HasFactory;

    protected $fillable = ['order_item_id', 'name', 'instructions', 'holes'];

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }
}
