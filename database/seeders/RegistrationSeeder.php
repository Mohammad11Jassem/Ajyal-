<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Registration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //s1
      $rege=Registration::create([
            'student_id'=>1,
            'course_id'=>1,
            'registered_at'=> now()->format('Y-m-d'),
        ]);
    //     Payment::create([
    //         'registration_id'=>$rege->id,
    //         'price'=>5000,
    //     ]);
       $rege= Registration::create([
            'student_id'=>1,
            'course_id'=>2,
            'registered_at'=> now()->subDays(30)->format('Y-m-d'),
        ]);
    //     Payment::create([
    //         'registration_id'=>$rege->id,
    //         'price'=>5000,
    //     ]);
    //     //s2
        $rege=Registration::create([
            'student_id'=>2,
            'course_id'=>1,
            'registered_at'=> now()->format('Y-m-d'),
        ]);
    //     Payment::create([
    //         'registration_id'=>$rege->id,
    //         'price'=>5000,
    //     ]);

         $rege= Registration::create([
            'student_id'=>2,
            'course_id'=>2,
            'registered_at'=> now()->subDays(30)->format('Y-m-d'),
        ]);
    //     Payment::create([
    //         'registration_id'=>$rege->id,
    //         'price'=>5000,
    //     ]);


    }
}
