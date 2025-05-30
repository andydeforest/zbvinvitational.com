<?php

namespace App\Products\Types;

interface ProductTypeInterface
{
    public static function name(): string;

    public static function eventModel(): ?string;

    public static function identifier(): string;

    public static function validateMetadata(array $metadata): void;

    public static function checkoutFormSchema(): array;
}
