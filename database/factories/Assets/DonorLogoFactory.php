<?php

namespace Database\Factories\Assets;

use App\Models\Assets\DonorLogo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assets\DonorLogo>
 */
class DonorLogoFactory extends Factory
{
    protected $model = DonorLogo::class;

    public function definition(): array
    {
        $filename = $this->faker->word().'.'.$this->faker->randomElement(['jpg', 'png', 'gif', 'jpeg']);

        return [
            'name' => $filename,
        ];
    }
}
