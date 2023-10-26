<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::factory()->create([
            'name' => 'Matematika',
            'year' => '2022/2023',
            'day' => 'Monday',
            'status' => 'Active',
            'grade_id' => '1',
            'teacher_id' => '1',
        ]);

        Course::factory()->create([
            'name' => 'Biologi',
            'year' => '2022/2023',
            'day' => 'Tuesday',
            'status' => 'Active',
            'grade_id' => '1',
            'teacher_id' => '2',
        ]);
    }
}
