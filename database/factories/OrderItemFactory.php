<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $created = $this->faker->dateTimeBetween('2018-01-01', 'now');

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 4),
            'unit_price_cents' => $this->faker->numberBetween(1000, 20000),
            'metadata' => [],
            'created_at' => $created,
            'updated_at' => $created,
        ];
    }

    /**
     * this factory creates OrderIrems that all share the same calendar year
     */
    public function fromYear(int $year)
    {
        return $this->state(function (array $attributes) use ($year) {
            // build a start/end datetime for that year
            $start = "{$year}-01-01 00:00:00";
            $end = "{$year}-12-31 23:59:59";

            return [
                'created_at' => $this->faker->dateTimeBetween($start, $end),
                'updated_at' => $this->faker->dateTimeBetween($start, $end),
            ];
        });
    }
}
