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
            // Use firstOrCreate with 'name' to avoid duplicates on multiple seed runs
            $schools = School::factory(10)->make();
            foreach ($schools as $school) {
                School::firstOrCreate(
                    ['name' => $school->name],
                    $school->toArray()
                );
            }

            return;
        }

        // Use firstOrCreate with 'name' to avoid duplicates on multiple seed runs
        $schools = School::factory(10)->make();
        foreach ($schools as $school) {
            $schoolModel = School::firstOrCreate(
                ['name' => $school->name],
                $school->toArray()
            );
            $randomEvents = $events->random(rand(1, 2));
            $schoolModel->events()->sync($randomEvents);
        }
    }
}
