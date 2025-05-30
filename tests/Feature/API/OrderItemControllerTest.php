<?php

namespace Tests\Feature\API;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderItemControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
    }

    #[Test]
    public function returns_all_order_items_sorted_by_created_at_desc()
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create();

        $first = OrderItem::factory()
            ->for($order)
            ->for($product)
            ->create(['created_at' => Carbon::now()->subDays(2)]);
        $second = OrderItem::factory()
            ->for($order)
            ->for($product)
            ->create(['created_at' => Carbon::now()->subDay()]);
        $third = OrderItem::factory()
            ->for($order)
            ->for($product)
            ->create(['created_at' => Carbon::now()]);

        $response = $this->getJson('/api/order-items');

        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'quantity',
                    'unit_price_cents',
                    'created_at',
                    'product' => [
                        'id',
                    ],
                    'order' => [
                        'id',
                    ],
                ],
            ]);

        $ids = array_column($response->json(), 'id');

        $this->assertEquals(
            [$third->id, $second->id, $first->id],
            $ids
        );
    }

    #[Test]
    public function limits_results_when_latest_parameter_is_provided()
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create();

        $first = OrderItem::factory()
            ->for($order)
            ->for($product)
            ->create(['created_at' => Carbon::now()->subDays(2)]);
        $second = OrderItem::factory()
            ->for($order)
            ->for($product)
            ->create(['created_at' => Carbon::now()->subDay()]);
        $third = OrderItem::factory()
            ->for($order)
            ->for($product)
            ->create(['created_at' => Carbon::now()]);

        $response = $this->getJson('/api/order-items?latest=2');

        $response->assertOk()
            ->assertJsonCount(2);

        $ids = array_column($response->json(), 'id');

        $this->assertEquals(
            [$third->id, $second->id],
            $ids
        );
    }
}
