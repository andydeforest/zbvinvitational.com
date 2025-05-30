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

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
    }

    #[Test]
    public function index_returns_all_orders_sorted_by_created_at_desc()
    {
        // Create three orders at different times
        $oldest = Order::factory()->create(['created_at' => Carbon::now()->subDays(2)]);
        $middle = Order::factory()->create(['created_at' => Carbon::now()->subDay()]);
        $newest = Order::factory()->create(['created_at' => Carbon::now()]);

        // Attach one item+product to each so the relation is loaded
        foreach ([$oldest, $middle, $newest] as $order) {
            $product = Product::factory()->create();
            OrderItem::factory()->for($order)->for($product)->create();
        }

        $response = $this->getJson('/api/orders');

        $response->assertOk()
            ->assertJsonCount(3)
                 // newest first
            ->assertJsonPath('0.id', $newest->id)
            ->assertJsonPath('1.id', $middle->id)
            ->assertJsonPath('2.id', $oldest->id)
                 // and each entry has its items.product
            ->assertJsonStructure([
                '*' => ['id', 'created_at', 'items' => [
                    '*' => ['id', 'product' => ['id']],
                ]],
            ]);
    }

    #[Test]
    public function index_limits_results_when_latest_parameter_is_provided()
    {
        $first = Order::factory()->create(['created_at' => Carbon::now()->subDays(2)]);
        $second = Order::factory()->create(['created_at' => Carbon::now()->subDay()]);
        $third = Order::factory()->create(['created_at' => Carbon::now()]);

        foreach ([$first, $second, $third] as $order) {
            $product = Product::factory()->create();
            OrderItem::factory()->for($order)->for($product)->create();
        }

        $response = $this->getJson('/api/orders?latest=2');

        $response->assertOk()
            ->assertJsonCount(2)
                 // should be the two most recent
            ->assertJsonPath('0.id', $third->id)
            ->assertJsonPath('1.id', $second->id);
    }

    #[Test]
    public function show_returns_order_resource_with_expected_fields_and_item_structure()
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 3419,
        ]);

        $item = OrderItem::factory()
            ->for($order)
            ->for($product)
            ->create([
                'quantity' => 1,
                'unit_price_cents' => 3419,
            ]);

        $response = $this->getJson("/api/orders/{$order->public_id}");

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'public_id',
                    'status',
                    'created_at',
                    'total_cents',
                    'total_dollars',
                    'items' => [
                        '*' => [
                            'id',
                            'quantity',
                            'unit_price_cents',
                            'unit_price_dollars',
                            'product' => [
                                'id',
                                'display_name',
                                'price_dollars',
                            ],
                        ],
                    ],
                ],
            ])
            ->assertJsonPath('data.public_id', $order->public_id)
            ->assertJsonPath('data.total_cents', $order->total_cents)
            ->assertJsonPath('data.total_dollars', $order->total_dollars)
            ->assertJsonPath('data.items.0.id', $item->id)
            ->assertJsonPath('data.items.0.quantity', $item->quantity)
            ->assertJsonPath('data.items.0.unit_price_cents', $item->unit_price_cents)
            ->assertJsonPath('data.items.0.unit_price_dollars', $item->unit_price_dollars)
            ->assertJsonPath('data.items.0.product.id', $product->id)
            ->assertJsonPath('data.items.0.product.display_name', $product->display_name)
            ->assertJsonPath('data.items.0.product.price_dollars', $product->price_dollars);
    }

    #[Test]
    public function destroy_deletes_order()
    {
        $order = Order::factory()
            ->has(OrderItem::factory()->count(1), 'items')
            ->create();

        $response = $this->deleteJson("/api/orders/{$order->public_id}");

        $response->assertOk()
            ->assertJson(['status' => 'deleted']);

        $this->assertDatabaseMissing('orders', [
            'id' => $order->id,
        ]);
    }
}
