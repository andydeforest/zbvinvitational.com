<?php

namespace App\Http\Controllers;

use App\Services\StripeWebhookHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook as StripeWebhook;
use Symfony\Component\HttpFoundation\Response;

class StripeWebhookController extends Controller
{
    public function handle(Request $request, StripeWebhookHandler $handler)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $stripeEvent = StripeWebhook::constructEvent(
                $payload, $sigHeader, $webhookSecret
            );
        } catch (\UnexpectedValueException|\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe Webhook error: '.$e->getMessage());

            return response('Invalid payload or signature', Response::HTTP_BAD_REQUEST);
        }

        if ($stripeEvent->type === 'payment_intent.succeeded') {
            $handler->handlePaymentIntentSucceeded($stripeEvent);
        } else {
            Log::info("Unhandled Stripe event: {$stripeEvent->type}");
        }

        return response('Webhook handled', Response::HTTP_OK);
    }
}
