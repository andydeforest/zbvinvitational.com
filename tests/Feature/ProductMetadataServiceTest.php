<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\ProductMetadataService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductMetadataServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProductMetadataService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ProductMetadataService;
    }

    #[Test]
    public function it_filters_and_stores_metadata_and_creates_related_records_for_golf()
    {
        // arrange
        $order = Order::factory()->create();

        $product = Product::factory()->create([
            'type' => 'golf',
            'name' => '18-holes',
        ]);

        $orderItem = $order->items()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price_cents' => $product->price,
        ]);

        $metadataEntry = [
            'name' => 'John Doe',
            'instructions' => 'Pair me w/Jane Doe',
            'extra_field' => 'should be ignored',
        ];

        // act
        $filtered = $this->service->handle($orderItem, $metadataEntry);

        // assert
        $this->assertSame([
            'name' => 'John Doe',
            'instructions' => 'Pair me w/Jane Doe',
        ], $filtered);

        // assert: a single Golfer record was created
        $this->assertDatabaseHas('golfers', [
            'order_item_id' => $orderItem->id,
            'name' => 'John Doe',
            'instructions' => 'Pair me w/Jane Doe',
        ]);
    }

    #[Test]
    public function it_handles_product_types_without_event_models()
    {
        // arrange
        $order = Order::factory()->create();

        $product = Product::factory()->create(['type' => 'donation']);
        $orderItem = OrderItem::factory()->create([
            'product_id' => $product->id,
        ]);

        // supply a single flat entry (anything not in schema)
        $metadataEntry = ['foo' => 'bar'];

        // act
        $filtered = $this->service->handle($orderItem, $metadataEntry);

        $this->assertEmpty($filtered);

        // and no golf records were ever created
        $this->assertDatabaseCount('golfers', 0);
    }
}
