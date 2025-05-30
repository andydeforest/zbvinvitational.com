<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderProcessingTest extends TestCase
{
    use RefreshDatabase;

    private function validBillingPayload(): array
    {
        return [
            'firstName' => 'John', 'lastName' => 'Doe', 'address' => '123 Main St',
            'city' => 'Lakewood', 'state' => 'CA', 'zip' => '90712',
            'phone' => '555-123-4567', 'email' => 'john@example.com', 'notes' => 'Filler',
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.stripe.secret' => 'sk_test_abc']);

        $fakeIntent = \Stripe\PaymentIntent::constructFrom(
            [
                'id' => 'pi_test',
                'client_secret' => 'secret_test',
            ],
            config('services.stripe.secret')
        );

        $gatewayMock = $this->getMockBuilder(\App\Services\StripeGateway::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createPaymentIntent', 'updatePaymentIntent'])
            ->getMock();
        $gatewayMock->method('createPaymentIntent')->willReturn($fakeIntent);
        $gatewayMock->method('updatePaymentIntent')->willReturn($fakeIntent);

        $this->app->instance(\App\Services\StripeGateway::class, $gatewayMock);
    }

    #[Test]
    public function it_processes_a_checkout_and_creates_an_order()
    {
        $product = Product::factory()->create([
            'price' => 7500,
            'type' => 'donation',
        ]);

        $cartPayload = [
            [
                'product' => [
                    'id' => $product->id,
                    'price' => $product->price,
                    'metadata' => [],
                ],
                'quantity' => 1,
            ],
        ];

        $billingPayload = [
            'firstName' => 'Jane',
            'lastName' => 'Doe',
            'address' => '123 Main St',
            'city' => 'Anytown',
            'state' => 'CA',
            'zip' => '90210',
            'phone' => '555-123-4567',
            'email' => 'jane@example.com',
            'notes' => 'Lorem ipsum.',
        ];

        $response = $this->postJson('/api/payment-intent', [
            'cart' => $cartPayload,
            'billing' => $billingPayload,
            'metadata' => [],
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'clientSecret' => 'secret_test',
            ])
            ->assertJsonStructure(['orderId', 'clientSecret']);

        $this->assertDatabaseHas('orders', [
            'stripe_payment_intent_id' => 'pi_test',
            'total_cents' => 7500,
            'status' => 'pending',
            'first_name' => 'Jane',
            'email' => 'jane@example.com',
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price_cents' => 7500,
        ]);
    }

    #[Test]
    public function it_requires_cart_and_billing_in_payload()
    {
        $response = $this->postJson('/api/payment-intent', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'cart',
                'billing.firstName',
                'billing.lastName',
                'billing.address',
                'billing.city',
                'billing.state',
                'billing.zip',
                'billing.phone',
                'billing.email',
            ]);
    }

    #[Test]
    public function it_respects_custom_price_override()
    {
        $product = Product::factory()->create([
            'price' => 5000,
            'allow_custom_price' => true,
            'type' => 'donation',
        ]);

        $cartPayload = [[
            'product' => ['id' => $product->id, 'price' => 7000, 'metadata' => []],
            'quantity' => 1,
        ]];

        $this->postJson('/api/payment-intent', [
            'cart' => $cartPayload,
            'billing' => $this->validBillingPayload(),
            'metadata' => [],
        ])->assertStatus(200);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'unit_price_cents' => 7000,
        ]);
    }

    #[Test]
    public function it_handles_multiple_cart_items()
    {
        $p1 = Product::factory()->create(['price' => 1000, 'type' => 'donation']);
        $p2 = Product::factory()->create(['price' => 2000, 'type' => 'donation']);

        $payload = [
            ['product' => ['id' => $p1->id, 'price' => 1000, 'metadata' => []], 'quantity' => 2],
            ['product' => ['id' => $p2->id, 'price' => 2000, 'metadata' => []], 'quantity' => 1],
        ];

        $this->postJson('/api/payment-intent', [
            'cart' => $payload,
            'billing' => $this->validBillingPayload(),
            'metadata' => [],
        ])->assertStatus(200);

        // (2 x 1000) + (1 x 2000) = 4000
        $this->assertDatabaseHas('orders', ['total_cents' => 4000]);
        $this->assertDatabaseCount('order_items', 2);
    }

    #[Test]
    public function it_creates_golfer_records_for_golf_registration()
    {
        // 1) Create a 'golf' product
        $product = Product::factory()->create([
            'name' => '18-holes',
            'price' => 10000,
            'type' => 'golf',
        ]);

        $cartPayload = [[
            'product' => ['id' => $product->id, 'price' => $product->price, 'metadata' => []],
            'quantity' => 1,
        ]];

        $billing = $this->validBillingPayload();

        $metadata = [[
            'product' => ['id' => $product->id, 'price' => $product->price],
            'name' => 'Alice Fairway',
            'instructions' => 'Left-handed clubs, please.',
        ]];

        $this->postJson('/api/payment-intent', [
            'cart' => $cartPayload,
            'billing' => $billing,
            'metadata' => $metadata,
        ])->assertStatus(200);

        $this->assertDatabaseHas('golfers', [
            'name' => 'Alice Fairway',
            'instructions' => 'Left-handed clubs, please.',
        ]);
    }

    #[Test]
    public function it_processes_golf_and_dinner_with_metadata()
    {
        $golf = Product::factory()->create([
            'name' => '9-holes',
            'price' => 5000,
            'type' => 'golf',
        ]);

        $dinner = Product::factory()->create([
            'name' => 'Dinner Only',
            'price' => 2500,
            'type' => 'dinner',
        ]);

        $cartPayload = [
            [
                'product' => ['id' => $golf->id,   'price' => $golf->price],
                'quantity' => 1,
            ],
            [
                'product' => ['id' => $dinner->id, 'price' => $dinner->price],
                'quantity' => 1,
            ],
        ];

        $metadata = [
            [
                'product' => ['id' => $golf->id,   'price' => $golf->price],
                'name' => 'Tiger Woods',
                'instructions' => 'Lorem ipsum',
            ],
            [
                'product' => ['id' => $dinner->id, 'price' => $dinner->price],
                'dietary_restrictions' => 'Foo bar',
            ],
        ];

        // Send checkout request
        $response = $this->postJson(
            '/api/payment-intent',
            [
                'cart' => $cartPayload,
                'billing' => $this->validBillingPayload(),
                'metadata' => $metadata,
            ]
        );

        $response->assertStatus(200);

        // Golf registration: one Golfer record created
        $this->assertDatabaseHas(
            'golfers',
            [
                'name' => 'Tiger Woods',
                'instructions' => 'Lorem ipsum',
                'holes' => 9, // assuming your service sets holes from quantity or product name
            ]
        );

        // Dinner-only registration: one DinnerOnly record created
        $this->assertDatabaseHas(
            'dinner_only',
            [
                'dietary_restrictions' => 'Foo bar',
            ]
        );

        // Ensure exactly one of each
        $this->assertDatabaseCount('golfers', 1);
        $this->assertDatabaseCount('dinner_only', 1);
    }
}
