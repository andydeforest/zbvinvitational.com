<?php

namespace App\Products\Types;

use App\Models\Event\Golfer;
use Illuminate\Validation\ValidationException;

class GolfRegistrationProduct implements ProductTypeInterface
{
    public static function name(): string
    {
        return 'Golf Registration';
    }

    public static function identifier(): string
    {
        return 'golf';
    }

    public static function eventModel(): ?string
    {
        return Golfer::class;
    }

    public static function validateMetadata(array $metadata): void
    {
        // disabling metadata validation for now
        // if (! isset($metadata['notes']) || ! isset($metadata['partner_name'])) {
        //     throw ValidationException::withMessages([
        //         'metadata' => 'Notes and partner name are required.',
        //     ]);
        // }
    }

    public static function checkoutFormSchema(): array
    {
        return [
            [
                'containerClass' => 'is-one-third',
                'label' => 'Golfer Name',
                'type' => 'text',
                'required' => true,
                'key' => 'name',
            ],
            [
                'containerClass' => 'is-two-thirds',
                'label' => 'Special Instructions',
                'type' => 'textarea',
                'required' => false,
                'key' => 'instructions',
            ],
        ];
    }
}
