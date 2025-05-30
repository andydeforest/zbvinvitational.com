<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'public_id' => Str::uuid(),
            'status' => 'pending',
            'total_cents' => $this->faker->numberBetween(1000, 10000),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->stateAbbr(),
            'zip' => $this->faker->postcode(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
            'notes' => $this->faker->optional()->sentence(),
            'stripe_payment_intent_id' => null,
            'stripe_charge_id' => null,
            'paid_at' => null,
        ];
    }
}
