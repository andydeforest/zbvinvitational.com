<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Order
 */
class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'public_id' => $this->public_id,
            'status' => $this->status,
            'created_at' => $this->created_at->toIso8601String(),
            'total_cents' => $this->total_cents,
            'total_dollars' => $this->total_dollars,
            'items' => OrderItemResource::collection($this->items),
        ];
    }
}
