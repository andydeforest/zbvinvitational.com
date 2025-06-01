<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\ProductMetadataService;
use App\Services\StripeGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Stripe\PaymentIntent;
use Tests\TestCase;

class StripeControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function stripe_create_endpoint_creates_order_and_items_and_returns_json()
    {
        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create([
            'product_category_id' => $category->id,
            'price' => 2000,
            'allow_custom_price' => false,
        ]);

        $fakeIntent = PaymentIntent::constructFrom([
            'id' => 'pi_test_123',
            'client_secret' => 'secret_xyz',
        ], '');

        $this->instance(StripeGateway::class, \Mockery::mock(StripeGateway::class, function ($m) use ($fakeIntent) {
            $m->shouldReceive('createPaymentIntent')
                ->once()
                ->with([
                    'amount' => 2000 * 2,
                    'currency' => 'usd',
                    'automatic_payment_methods' => ['enabled' => true],
                ])
                ->andReturn($fakeIntent);

            $m->shouldReceive('updatePaymentIntent')
                ->once()
                ->with('pi_test_123', ['metadata' => ['orderId' => 1]])
                ->andReturn($fakeIntent);
        }));

        $this->instance(ProductMetadataService::class, \Mockery::mock(ProductMetadataService::class, function ($m) {
            $m->shouldReceive('handle')
                ->andReturn(['foo' => 'bar']);
        }));

        $payload = [
            'cart' => [
                ['product' => ['id' => $product->id, 'price' => 2000], 'quantity' => 2],
            ],
            'billing' => [
                'firstName' => 'Jane',
                'lastName' => 'Smith',
                'address' => '123 Elm St',
                'city' => 'Metropolis',
                'state' => 'NY',
                'zip' => '10001',
                'phone' => '212-555-1212',
                'email' => 'jane@smith.test',
                'notes' => 'Leave at front desk',
            ],
            'metadata' => [
                ['foo' => 'bar'],
            ],
        ];

        $response = $this->postJson(action([\App\Http\Controllers\API\StripeController::class, 'create']), $payload);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['orderId', 'clientSecret'])
            ->assertJson(['clientSecret' => 'secret_xyz']);

        $this->assertDatabaseHas('orders', [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'total_cents' => 4_000,
            'stripe_payment_intent_id' => 'pi_test_123',
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price_cents' => 2000,
            'metadata' => json_encode(['foo' => 'bar']),
        ]);

        $order = Order::first();
        $this->assertEquals($order->public_id, $response->json('orderId'));
    }
}
