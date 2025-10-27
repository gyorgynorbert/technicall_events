<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Assign this school to a random, existing event
            'event_id' => Event::inRandomOrder()->first()->id,
            'name' => $this->faker->streetName().' School',
            'location' => $this->faker->city(),
        ];
    }
}
