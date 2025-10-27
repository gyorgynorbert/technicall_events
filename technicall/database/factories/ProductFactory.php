<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(2, true);

        return [
            'name' => ucwords($name),
            'code' => Str::slug($name),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 5, 150),
        ];
    }
}
