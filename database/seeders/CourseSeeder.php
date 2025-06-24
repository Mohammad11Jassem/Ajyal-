<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Curriculum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // course 1
        $course = Course::create([
            'name' => 'Advanced Math Course',
            'cost' => 199.99,
            'type' => 'البكالوريا العلمية',
            'start_date' => now()->addDays(3)->toDateString(),    // e.g. 2025-06-26
            'end_date' => now()->addMonths(3)->toDateString(),   // e.g. 2025-09-26
            'capacity' => 40,
        ]);

        // 2. Attach classrooms
        $course->classRooms()->attach([1, 2, 3]);

        // 3. Attach subjects with teachers
        $subjects = [
            [
                'subject_id' => 1,
                'teachers' => [1, 2]
            ],
            [
                'subject_id' => 2,
                'teachers' => [1]
            ]
        ];

        foreach ($subjects as $subject) {
            $curriculum = Curriculum::create([
                'course_id' => $course->id,
                'subject_id' => $subject['subject_id'],
            ]);

            $curriculum->teachers()->attach($subject['teachers']);
        }

        // course 2
        $course = Course::create([
            'name' => 'Advanced Math Course',
            'cost' => 199.99,
            'type' => 'البكالوريا الأدبية',
            'start_date' => now()->addDays(10)->toDateString(),    // e.g. 2025-06-26
            'end_date' => now()->addMonths(5)->toDateString(),   // e.g. 2025-09-26
            'capacity' => 40,
        ]);

        // 2. Attach classrooms
        $course->classRooms()->attach([1]);

        // 3. Attach subjects with teachers
        $subjects = [
            [
                'subject_id' => 1,
                'teachers' => [1, 2]
            ],
            [
                'subject_id' => 2,
                'teachers' => [1]
            ]
        ];

        foreach ($subjects as $subject) {
            $curriculum = Curriculum::create([
                'course_id' => $course->id,
                'subject_id' => $subject['subject_id'],
            ]);

            $curriculum->teachers()->attach($subject['teachers']);
        }
    }
}
