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
        // Use firstOrCreate with 'name' to avoid duplicates on multiple seed runs
        $events = Event::factory(5)->make();
        foreach ($events as $event) {
            Event::firstOrCreate(
                ['name' => $event->name],
                $event->toArray()
            );
        }
    }
}
