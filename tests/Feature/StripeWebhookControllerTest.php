<?php

namespace Tests\Feature;

use App\Services\StripeWebhookHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Stripe\Event as StripeEvent;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StripeWebhookControllerTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
    use RefreshDatabase;

    #[Test]
    public function payment_intent_succeeded_triggers_handler_and_returns_200(): void
    {
        $payloadArray = [
            'id' => 'evt_123',
            'type' => 'payment_intent.succeeded',
            'data' => ['object' => []],
        ];
        $payload = json_encode($payloadArray);
        $sigHeader = 't=1615301138,v1=signature';

        Config::set('services.stripe.webhook_secret', 'whsec_test');

        Mockery::mock('alias:Stripe\\Webhook')
            ->shouldReceive('constructEvent')
            ->once()
            ->with($payload, $sigHeader, 'whsec_test')
            ->andReturn($fakeEvent = StripeEvent::constructFrom(
                ['id' => 'evt_123', 'type' => 'payment_intent.succeeded', 'data' => ['object' => []]],
                null
            ));

        $handler = Mockery::mock(StripeWebhookHandler::class);
        $handler->shouldReceive('handlePaymentIntentSucceeded')
            ->once()
            ->with($fakeEvent);
        $this->app->instance(StripeWebhookHandler::class, $handler);

        $response = $this
            ->withHeaders(['Stripe-Signature' => $sigHeader])
            ->postJson('/stripe/webhook', $payloadArray);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertSeeText('Webhook handled');
    }

    #[Test]
    public function invalid_signature_returns_400(): void
    {
        $data = ['foo' => 'bar'];
        $payload = json_encode($data);
        $sigHeader = 'bad_signature';

        Config::set('services.stripe.webhook_secret', 'whsec_test');

        Mockery::mock('alias:Stripe\\Webhook')
            ->shouldReceive('constructEvent')
            ->once()
            ->with($payload, $sigHeader, 'whsec_test')
            ->andThrow(new \UnexpectedValueException('Invalid payload'));

        $this->app->instance(StripeWebhookHandler::class, Mockery::mock(StripeWebhookHandler::class));

        $response = $this
            ->withHeaders(['Stripe-Signature' => $sigHeader])
            ->postJson('/stripe/webhook', $data);

        $response
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertSeeText('Invalid payload or signature');
    }
}
