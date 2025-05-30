<?php

namespace App\Services;

use Stripe\PaymentIntent;
use Stripe\StripeClient;

class StripeGateway
{
    public function __construct(protected StripeClient $client) {}

    public function createPaymentIntent(array $data): PaymentIntent
    {
        return $this->client->paymentIntents->create($data);
    }

    public function updatePaymentIntent(string $id, array $data): PaymentIntent
    {
        return $this->client->paymentIntents->update($id, $data);
    }
}
