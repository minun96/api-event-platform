<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'category_id' => fake()->numberBetween(1, 5),
            'description' => fake()->text(70),
            'artist' => fake()->name(),
            'event_date' => fake()->dateTimeBetween('+1 week', '+1 year'),
            'city' => fake()->city(),
            'price' => fake()->randomFloat(2, 21, 217),
            'tickets_number' => fake()->numberBetween(50, 2000),
        ];
    }
}
