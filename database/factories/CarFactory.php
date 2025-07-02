<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'car_name' => $this->faker->sentence(1),
            'day_rate' => $this->faker->randomFloat(8, 0, 1000),
            'month_rate' => $this->faker->randomFloat(8, 0, 10000),
            'image' => $this->faker->imageUrl(640, 480),
        ];
    }
}
