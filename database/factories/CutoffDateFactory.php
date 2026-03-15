<?php

namespace Database\Factories;

use App\Models\CutoffDate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CutoffDate>
 */
class CutoffDateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cutoff = $this->faker->dateTimeBetween('+1 week', '+2 weeks');
        $charge = (clone $cutoff)->modify('+2 days');
        $delivery = (clone $cutoff)->modify('+5 days');

        return [
            'cutoff' => $cutoff,
            'charge' => $charge,
            'delivery' => $delivery,
            'first' => 0,
            'saveon_cheque_value' => 0,
            'saveon_card_value' => 0,
            'coop_cheque_value' => 0,
            'coop_card_value' => 0,
        ];
    }
}
