<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'address1' => $this->faker->streetAddress(),
            'address2' => '',
            'city' => $this->faker->city(),
            'province' => '',
            'postal_code' => $this->faker->regexify('[A-Z][0-9][A-Z] [0-9][A-Z][0-9]'),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'pickupalt' => '',
            'employee' => $this->faker->numberBetween(0,1),
            'payment' => $this->faker->numberBetween(0,1),
            'deliverymethod' => $this->faker->numberBetween(0,1),
            'saveon' => $this->faker->numberBetween(0,4),
            'coop' => $this->faker->numberBetween(0,4),
            'saveon_onetime' => 0,
            'coop_onetime' => 0,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
