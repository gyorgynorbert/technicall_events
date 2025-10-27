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
        Grade::factory(20)->create();
    }
}
