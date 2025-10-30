<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\School; // Import Event
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = Event::all();

        if ($events->isEmpty()) {
            $this->command->warn('No events found. Skipping school-event attachment.');
            School::factory(10)->create();

            return;
        }

        School::factory(10)->create()->each(function ($school) use ($events) {
            $randomEvents = $events->random(rand(1, 2));

            $school->events()->attach($randomEvents);
        });
    }
}
