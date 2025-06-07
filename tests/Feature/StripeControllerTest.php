<?php

namespace Tests\Feature;

use App\Http\Controllers\API\StripeController;
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

        $this->instance(
            ProductMetadataService::class,
            \Mockery::mock(ProductMetadataService::class, function ($m) {
                $m->shouldReceive('handle')
                    ->twice()                           // two golfers
                    ->withAnyArgs()                     // we don’t care about args
                    ->andReturnUsing(fn ($orderItem, $entryMeta) => $entryMeta);
            })
        );

        $payload = [
            'cart' => [
                ['product' => ['id' => $product->id, 'price' => 2000], 'quantity' => 2],
            ],
            'billing' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'address' => '123 Elm St',
                'city' => 'Metropolis',
                'state' => 'NY',
                'zip' => '10001',
                'phone' => '212-555-1212',
                'email' => 'john@doe.test',
                'notes' => 'Early tee time',
            ],
            'metadata' => [
                ['name' => 'golfer 1'],
                ['name' => 'golfer 2'],
            ],
        ];

        $response = $this->postJson(
            action([StripeController::class, 'create']),
            $payload
        );

        $response->assertStatus(200)
            ->assertJsonStructure(['orderId', 'clientSecret'])
            ->assertJson(['clientSecret' => 'secret_xyz']);

        $this->assertDatabaseHas('orders', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'total_cents' => 4000,
            'stripe_payment_intent_id' => 'pi_test_123',
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price_cents' => 2000,
            'metadata' => json_encode([
                ['name' => 'golfer 1'],
                ['name' => 'golfer 2'],
            ]),
        ]);

        $item = \App\Models\OrderItem::first();
        $this->assertIsArray($item->metadata);
        $this->assertCount(2, $item->metadata);
        $this->assertEquals('golfer 1', $item->metadata[0]['name']);
        $this->assertEquals('golfer 2', $item->metadata[1]['name']);

        $order = \App\Models\Order::first();
        $this->assertEquals($order->public_id, $response->json('orderId'));
    }
}
