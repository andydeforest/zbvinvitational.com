<?php

namespace Tests\Unit;

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
    public function handle_returns_empty_for_simple_donation()
    {
        $product = Product::factory()->create(['type' => 'donation']);
        $orderItem = OrderItem::factory()->create(['product_id' => $product->id]);
        $result = $this->service->handle($orderItem, []);
        $this->assertSame([], $result);
        $this->assertDatabaseCount('golfers', 0);
        $this->assertDatabaseCount('dinner_only', 0);
    }

    #[Test]
    public function handle_creates_golfer_for_golf_metadata()
    {
        $product = Product::factory()->create(['type' => 'golf']);
        $orderItem = OrderItem::factory()->create(['product_id' => $product->id]);
        $meta = ['product' => ['id' => $product->id], 'name' => 'Test Name', 'instructions' => 'OK'];
        $result = $this->service->handle($orderItem, $meta);
        $this->assertDatabaseHas('golfers', ['order_item_id' => $orderItem->id, 'name' => 'Test Name', 'instructions' => 'OK']);
        $this->assertEquals(
            ['name' => 'Test Name', 'instructions' => 'OK'],
            $result
        );
    }

    #[Test]
    public function handle_creates_dinner_for_dinner_metadata()
    {
        $product = Product::factory()->create(['type' => 'dinner']);
        $orderItem = OrderItem::factory()->create(['product_id' => $product->id]);
        $meta = ['product' => ['id' => $product->id], 'dietary_restrictions' => 'Gluten-Free'];
        $result = $this->service->handle($orderItem, $meta);
        $this->assertDatabaseHas('dinner_only', ['order_item_id' => $orderItem->id, 'dietary_restrictions' => 'Gluten-Free']);
        $this->assertEquals(
            ['dietary_restrictions' => 'Gluten-Free'],
            $result
        );
    }

    #[Test]
    public function it_falls_back_to_order_name_when_missing_in_metadata()
    {
        $product = Product::factory()->create([
            'type' => 'golf',
        ]);

        $order = Order::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
        ]);

        $orderItem = OrderItem::factory()->create([
            'product_id' => $product->id,
            'order_id' => $order->id,
        ]);

        $metadata = [
            'instructions' => 'Please tee off at dawn',
            // name explicitly excluded
        ];

        $this->service->handle($orderItem, $metadata);

        // name should fallback to the order name
        $this->assertDatabaseHas('golfers', [
            'order_item_id' => $orderItem->id,
            'name' => 'Jane Smith',
        ]);
    }

    #[Test]
    public function it_does_not_create_a_model_when_all_metadata_is_blank_or_unrecognized()
    {
        $product = Product::factory()->create(['type' => 'golf']);
        $orderItem = OrderItem::factory()->create([
            'product_id' => $product->id,
        ]);

        $meta = [
            'foo' => 'bar', // not in schema
            'name' => '', // blank
            'instructions' => null,
        ];

        // 3) Call handle()
        $result = $this->service->handle($orderItem, $meta);

        // 4a) It should return ONLY the filtered keys (which are blank):
        $this->assertSame(
            ['name' => '', 'instructions' => null],
            $result
        );

        // 4b) And—even though golf has an eventModel()—no row should be created:
        $this->assertDatabaseCount('golfers', 0);
    }
}
