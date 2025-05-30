<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\OrderItem
 */
class OrderItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'unit_price_cents' => $this->unit_price_cents,
            'unit_price_dollars' => $this->unit_price_dollars,
            'product' => [
                'id' => $this->product->id,
                'display_name' => $this->product->display_name,
                'price_dollars' => $this->product->price_dollars,
            ],
        ];
    }
}
