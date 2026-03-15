<?php

namespace Database\Factories;

use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SchoolClass>
 */
class SchoolClassFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'bucketname' => $this->faker->unique()->slug(2),
            'displayorder' => $this->faker->numberBetween(1, 10),
            'current' => true,
            'enrolment' => $this->faker->numberBetween(10, 30),
        ];
    }
}
