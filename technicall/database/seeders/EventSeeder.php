<?php

namespace Database\Seeders;

use App\Models\Event; // <-- Import Event model
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 fake events using the factory
        Event::factory(5)->create();
    }
}
