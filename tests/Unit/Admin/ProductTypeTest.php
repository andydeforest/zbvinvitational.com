<?php

namespace Tests\Unit\Admin;

use App\Products\Types\GolfRegistrationProduct;
use App\Products\Types\ProductTypeRegistry;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductTypeTest extends TestCase
{
    #[Test]
    public function it_resolves_the_golf_product_type(): void
    {
        $type = ProductTypeRegistry::getByIdentifier('golf');
        $this->assertInstanceOf(GolfRegistrationProduct::class, $type);
    }

    #[Test]
    public function it_passes_with_valid_golf_metadata(): void
    {
        $data = [
            'notes' => 'Late tee time',
            'partner_name' => 'Tiger Woods',
        ];

        (new GolfRegistrationProduct)->validateMetadata($data);

        $this->assertTrue(true); // no exception = pass
    }
}
