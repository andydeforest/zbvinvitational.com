<?php

namespace Database\Factories;

use App\Models\Donor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Donor>
 */
class DonorFactory extends Factory
{
    protected $model = Donor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
        ];
    }
}
