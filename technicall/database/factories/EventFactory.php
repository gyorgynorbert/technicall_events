<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
            'name' => $this->faker->company().' Photoshoot',
            'description' => $this->faker->paragraph(2),
            'event_date' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
        ];
    }
}
