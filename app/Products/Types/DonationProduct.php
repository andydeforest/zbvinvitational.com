<?php

namespace App\Products\Types;

class DonationProduct implements ProductTypeInterface
{
    public static function name(): string
    {
        return 'Donation';
    }

    public static function identifier(): string
    {
        return 'donation';
    }

    public static function eventModel(): ?string
    {
        return null;
    }

    public static function validateMetadata(array $metadata): void
    {
        // no extra validation needed
    }

    public static function checkoutFormSchema(): array
    {
        return [];
    }
}
