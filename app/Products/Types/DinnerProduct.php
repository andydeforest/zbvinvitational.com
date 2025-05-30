<?php

namespace App\Products\Types;

use App\Models\Event\DinnerOnly;

class DinnerProduct implements ProductTypeInterface
{
    public static function name(): string
    {
        return 'Dinner';
    }

    public static function identifier(): string
    {
        return 'dinner';
    }

    public static function eventModel(): ?string
    {
        return DinnerOnly::class;
    }

    public static function validateMetadata(array $metadata): void
    {
        // no extra validation needed
    }

    public static function checkoutFormSchema(): array
    {
        return [
            [
                'label' => 'Dietary Restrictions',
                'type' => 'text',
                'required' => false,
                'key' => 'dietary_restrictions',
            ],
        ];
    }
}
