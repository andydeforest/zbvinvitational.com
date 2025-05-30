<?php

namespace Tests\Unit;

use App\Events\OrderPaid;
use App\Models\Order;
use App\Services\StripeWebhookHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use Stripe\Event as StripeEvent;
use Tests\TestCase;

class StripeWebhookHandlerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_updates_the_order_and_fires_an_event_when_payment_intent_succeeds()
    {
        Event::fake();

        $order = Order::factory()->create([
            'status' => 'pending',
        ]);

        // fake stripe event
        $piData = [
            'id' => 'pi_123',
            'metadata' => ['orderId' => (string) $order->id],
            'latest_charge' => 'ch_abc123',
        ];

        $stripeEvent = StripeEvent::constructFrom([
            'type' => 'payment_intent.succeeded',
            'data' => ['object' => $piData],
        ]);

        $handler = new StripeWebhookHandler;
        $handler->handlePaymentIntentSucceeded($stripeEvent);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'paid',
            'stripe_charge_id' => 'ch_abc123',
        ]);

        Event::assertDispatched(OrderPaid::class, function (OrderPaid $e) use ($order) {
            return $e->order->is($order);
        });
    }

    #[Test]
    public function it_logs_warning_and_skips_when_object_missing_from_payload(): void
    {
        Event::fake();
        \Log::spy();

        $stripeEvent = StripeEvent::constructFrom([
            'type' => 'payment_intent.succeeded',
            'data' => ['object' => null],
        ]);

        (new StripeWebhookHandler)->handlePaymentIntentSucceeded($stripeEvent);

        \Log::shouldHaveReceived('warning')
            ->once()
            ->with('StripeWebhookHandler: could not extract payment_intent data.');

        Event::assertNotDispatched(OrderPaid::class);
    }

    #[Test]
    public function it_logs_warning_and_skips_when_order_not_found(): void
    {
        Event::fake();
        \Log::spy();

        $piData = [
            'id' => 'pi_67890',
            'metadata' => ['orderId' => '9999'],
            'latest_charge' => 'ch_xyz123',
        ];
        $stripeEvent = StripeEvent::constructFrom([
            'type' => 'payment_intent.succeeded',
            'data' => ['object' => $piData],
        ]);

        (new StripeWebhookHandler)->handlePaymentIntentSucceeded($stripeEvent);

        \Log::shouldHaveReceived('warning')
            ->once()
            ->with('StripeWebhookHandler: Order ID 9999 not found.');

        Event::assertNotDispatched(OrderPaid::class);
    }
}
