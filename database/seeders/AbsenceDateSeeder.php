<?php

namespace Database\Seeders;

use App\Models\Absence;
use App\Models\AbsenceDate;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AbsenceDateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         AbsenceDate::create([
            'absence_date' => Carbon::today()->addDays(12),
            'classroom_course_id' => 1,
        ]);

        AbsenceDate::create([
            'absence_date' => Carbon::today()->addDays(15),
            'classroom_course_id' => 1,
        ]);

         Absence::create([
            'absence_date_id' => 1,
            'registration_id' => 1, 
        ]);

        Absence::create([
            'absence_date_id' => 2,
            'registration_id' => 1,
        ]);

        // Student with id = 2
        Absence::create([
            'absence_date_id' => 2,
            'registration_id' => 2,
        ]);

        Absence::create([
            'absence_date_id' => 3,
            'registration_id' => 2,
        ]);
    }
}
