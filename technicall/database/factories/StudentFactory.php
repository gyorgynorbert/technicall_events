<?php

namespace Database\Factories;

use App\Models\Grade;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'grade_id' => Grade::inRandomOrder()->first()->id,
            'name' => $this->faker->name(),
            'access_key' => Str::random(16),
        ];
    }
}
