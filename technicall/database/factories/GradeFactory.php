<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Assign this grade to a random, existing school
            'school_id' => School::inRandomOrder()->first()->id,
            'name' => 'Class '.$this->faker->randomElement(['1A', '1B', '2A', '2B', '3C', '4A', '5B']),
        ];
    }
}
