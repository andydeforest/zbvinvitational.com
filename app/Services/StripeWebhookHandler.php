<?php

namespace App\Services;

use App\Events\OrderPaid;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Stripe\Event as StripeEvent;
use Stripe\PaymentIntent;

class StripeWebhookHandler
{
    public function handlePaymentIntentSucceeded(StripeEvent $stripeEvent): void
    {
        $payload = $stripeEvent->data->toArray();
        $piData = $payload['object'] ?? null;

        if (! is_array($piData)) {
            Log::warning('StripeWebhookHandler: could not extract payment_intent data.');

            return;
        }

        /** @var PaymentIntent $paymentIntent */
        $paymentIntent = PaymentIntent::constructFrom(
            $piData,
            config('services.stripe.secret')
        );

        $orderId = $paymentIntent->metadata->orderId ?? null;
        if (! $orderId) {
            Log::warning('StripeWebhookHandler: no orderId in metadata.');

            return;
        }

        try {
            $id = (int) $orderId;

            /** @var \App\Models\Order $order */
            $order = Order::whereKey($id)->firstOrFail();

            $stripeChargeId = $paymentIntent->latest_charge;

            $order->update([
                'status' => 'paid',
                'stripe_charge_id' => $stripeChargeId,
                'paid_at' => now(),
            ]);

            event(new OrderPaid($order));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("StripeWebhookHandler: Order ID {$orderId} not found.");
        }
    }
}
