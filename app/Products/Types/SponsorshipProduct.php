<?php

namespace App\Products\Types;

class SponsorshipProduct implements ProductTypeInterface
{
    public static function name(): string
    {
        return 'Sponsorship';
    }

    public static function identifier(): string
    {
        return 'sponsorship';
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
