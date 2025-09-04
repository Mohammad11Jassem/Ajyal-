<?php

namespace Database\Seeders;

use App\Models\SortStudent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SortStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //s1
        SortStudent::firstOrCreate([
            'registration_id' => 1,
            'classroom_course_id' =>1,
        ]);
        // SortStudent::firstOrCreate([
        //     'registration_id' => 2,
        //     'classroom_course_id' =>4,
        // ]);

        //s2
        SortStudent::firstOrCreate([
            'registration_id' => 2,
            'classroom_course_id' =>1,
        ]);
        // SortStudent::firstOrCreate([
        //     'registration_id' => 4,
        //     'classroom_course_id' =>4,
        // ]);

        //s3
        SortStudent::firstOrCreate([
            'registration_id' => 3,
            'classroom_course_id' =>1,
        ]);
        //s4
        SortStudent::firstOrCreate([
            'registration_id' => 4,
            'classroom_course_id' =>1,
        ]);
        //s5
        SortStudent::firstOrCreate([
            'registration_id' => 5,
            'classroom_course_id' =>1,
        ]);



    }
}
