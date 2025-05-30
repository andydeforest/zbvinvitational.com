<?php

namespace Database\Factories\Event;

use App\Models\Event\Golfer;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class GolferFactory extends Factory
{
    protected $model = Golfer::class;

    public function definition(): array
    {

        $start = '2020-01-01 00:00:00';
        $end = '2024-12-31 23:59:59';

        return [
            'order_item_id' => OrderItem::factory(),
            'name' => $this->faker->name(),
            'instructions' => $this->faker->boolean(70)
                                ? $this->faker->sentence()
                                : null,
            'holes' => $this->faker->randomElement([9, 18]),
            // 'created_at' => $this->faker->dateTimeBetween($start, $end),
            // 'updated_at' => $this->faker->dateTimeBetween($start, $end),
        ];
    }
}
