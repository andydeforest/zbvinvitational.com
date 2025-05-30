<?php

namespace App\Services;

use App\Models\OrderItem;

class ProductMetadataService
{
    /**
     * @param  array<string, mixed>  $metadataEntry
     * @return array<string, mixed> filtered metadata saved to order_item
     */
    public function handle(OrderItem $orderItem, array $metadataEntry): array
    {
        $product = $orderItem->product;
        $typeInstance = $product->typeInstance();
        if (! $typeInstance) {
            return [];
        }

        $schema = $typeInstance->checkoutFormSchema();
        if (empty($schema)) {
            return [];
        }

        // which keys are we allowing?
        $allowedKeys = collect($schema)
            ->pluck('key')
            ->filter()   // removes any null/empty keys
            ->all();

        // grab only those from the single entry
        $filtered = collect($metadataEntry)
            ->only($allowedKeys)
            ->toArray();

        $clean = array_filter($filtered, function ($value) {
            return ! is_null($value) && $value !== '';
        });

        // if this product type has an event model, instantiate one record
        if ($modelClass = $typeInstance::eventModel()) {
            if (! empty($clean)) {
                // probably need to figure out a better way to handle this
                $holes = str_contains($product->name, '9') ? 9 : 18;

                if (! array_key_exists('name', $clean)) {
                    $clean['name'] = $orderItem->order->first_name.' '.$orderItem->order->last_name;
                }

                $modelClass::create(array_merge([
                    'order_item_id' => $orderItem->id,
                    'holes' => $holes,
                ], $clean));
            }
        }

        return $filtered;
    }
}
