<?php

namespace App\Products\Types;

class ProductTypeRegistry
{
    /**
     * @return array<class-string<ProductTypeInterface>>
     */
    public static function all(): array
    {
        return [
            DonationProduct::class,
            GolfRegistrationProduct::class,
            DinnerProduct::class,
            DonationProduct::class,
            SponsorshipProduct::class,
        ];
    }

    public static function getByIdentifier(string $id): ?ProductTypeInterface
    {
        foreach (self::all() as $type) {
            if ($type::identifier() === $id) {
                return new $type;
            }
        }

        return null;
    }
}
