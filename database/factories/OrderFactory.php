<?php

namespace Database\Factories;

use App\Models\Car;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
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
        $date = now()->format('Y-m-d');

        return [
            'car_id' => Car::inRandomOrder()->first()->id,
            'order_date' => $date,
            'pickup_date' => Carbon::parse($date)->addMonths(rand(1, 4))->format('Y-m-d'),
            'dropoff_date' => Carbon::parse($date)->addMonths(rand(5, 9))->format('Y-m-d'),
            'pickup_location' => fake()->locale(),
            'dropoff_location' => fake()->locale(),
        ];
    }
}
