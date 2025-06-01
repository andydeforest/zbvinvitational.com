<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    private array $validBilling = [
        'firstName' => 'Alice',
        'lastName' => 'Smith',
        'address' => '123 Main St',
        'city' => 'Springfield',
        'state' => 'IL',
        'zip' => '62704',
        'phone' => '555-1234',
        'email' => 'alice@example.com',
        'notes' => '',
    ];

    #[Test]
    public function donation_checkout_creates_order_and_items_without_events()
    {
        $donation = Product::factory()->create([
            'type' => 'donation',
            'price' => 5000,
        ]);

        $payload = [
            'cart' => [
                ['product' => ['id' => $donation->id, 'price' => $donation->price], 'quantity' => 2],
            ],
            'billing' => $this->validBilling,
            'metadata' => [
                [], // no schema => allowed
            ],
        ];

        $response = $this->postJson('/api/payment-intent', $payload);

        $response->assertOk()
            ->assertJsonStructure(['orderId', 'clientSecret']);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);
        $this->assertDatabaseCount('golfers', 0);
        $this->assertDatabaseCount('dinner_only', 0);

        $order = Order::first();
        $this->assertEquals(5000 * 2, $order->total_cents);
    }

    #[Test]
    public function mixed_cart_checkout_creates_golfer_and_dinner_records()
    {
        $golf = Product::factory()->create([
            'type' => 'golf',
            'name' => '9-holes',
            'price' => 12000,
        ]);
        $dinner = Product::factory()->create([
            'type' => 'dinner',
            'price' => 1500,
        ]);

        $payload = [
            'cart' => [
                ['product' => $golf->toArray(),   'quantity' => 1],
                ['product' => $dinner->toArray(), 'quantity' => 1],
            ],
            'billing' => $this->validBilling,
            'metadata' => [
                ['name' => 'John Doe', 'instructions' => 'Tee off at dawn'],
                ['dietary_restrictions' => 'Vegan'],
            ],
        ];

        $response = $this->postJson('/api/payment-intent', $payload);

        $response->assertOk()
            ->assertJsonStructure(['orderId', 'clientSecret']);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 2);

        $golfItem = OrderItem::where('product_id', $golf->id)->firstOrFail();

        $this->assertDatabaseHas('golfers', [
            'order_item_id' => $golfItem->id,
            'name' => 'John Doe',
            'instructions' => 'Tee off at dawn',
        ]);

        $dinnerItem = OrderItem::where('product_id', $dinner->id)->firstOrFail();

        $this->assertDatabaseHas('dinner_only', [
            'order_item_id' => $dinnerItem->id,
            'dietary_restrictions' => 'Vegan',
        ]);

        $order = Order::first();
        $this->assertEquals(12000 + 1500, $order->total_cents);
    }

    #[Test]
    public function missing_cart_returns_validation_error()
    {
        $response = $this->postJson('/api/payment-intent', [
            'billing' => $this->validBilling,
            'metadata' => [],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['cart']);

        $this->assertDatabaseCount('orders', 0);
    }

    #[Test]
    public function missing_billing_returns_validation_errors_on_each_field()
    {
        // need at least cart to reach billing rules
        $product = Product::factory()->create(['type' => 'donation', 'price' => 1000]);

        $response = $this->postJson('/api/payment-intent', [
            'cart' => [['product' => ['id' => $product->id, 'price' => $product->price], 'quantity' => 1]],
            'metadata' => [[]],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'billing.firstName',
                'billing.lastName',
                'billing.address',
                'billing.city',
                'billing.state',
                'billing.zip',
                'billing.phone',
                'billing.email',
            ]);

        $this->assertDatabaseCount('orders', 0);
    }
}
