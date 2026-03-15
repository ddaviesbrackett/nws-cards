<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'paid' => 0,
            'payment' => 0,
            'deliverymethod' => 0,
            'profit' => 0,
            'saveon' => $this->faker->numberBetween(0, 5),
            'coop' => $this->faker->numberBetween(0, 5),
            'saveon_onetime' => 0,
            'coop_onetime' => 0,
        ];
    }
}
