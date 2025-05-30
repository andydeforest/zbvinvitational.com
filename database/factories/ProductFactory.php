<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->words(2, true);
        $shortName = Str::limit($name, 20, '');

        return [
            'product_category_id' => null,
            'name' => $name,
            'short_name' => $shortName,
            'cover_image' => null,
            'type' => $this->faker->randomElement(['golf', 'donation', 'dinner', 'sponsorship']),
            'price' => $this->faker->numberBetween(1000, 15000), // cents
            'is_active' => true,
            'metadata' => null,
            'description' => $this->faker->optional()->paragraph(),
        ];
    }
}
