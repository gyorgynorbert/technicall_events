<?php

namespace Database\Seeders;

use App\Models\Grade; // <-- Import Grade
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 20 fake grades
        // Use firstOrCreate with 'name' and 'school_id' to avoid duplicates on multiple seed runs
        $grades = Grade::factory(20)->make();
        foreach ($grades as $grade) {
            Grade::firstOrCreate(
                ['name' => $grade->name, 'school_id' => $grade->school_id],
                $grade->toArray()
            );
        }
    }
}
