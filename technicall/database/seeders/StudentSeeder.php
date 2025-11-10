<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use firstOrCreate with 'access_key' to avoid duplicates on multiple seed runs
        $students = Student::factory(100)->make();
        foreach ($students as $student) {
            Student::firstOrCreate(
                ['access_key' => $student->access_key],
                $student->toArray()
            );
        }
    }
}
