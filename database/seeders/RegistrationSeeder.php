<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Registration;
use Carbon\Carbon;
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
        Payment::create([
            'registration_id'=>$rege->id,
            'invoice_id'=>1,
            'payment_date'=>Carbon::now()->addDays(rand(8, 11)),
        ]);


    //    $rege= Registration::create([
    //         'student_id'=>1,
    //         'course_id'=>2,
    //         'registered_at'=> now()->subDays(30)->format('Y-m-d'),
    //     ]);
        //   Payment::create([
        //     'registration_id'=>$rege->id,
        //     'invoice_id'=>1,
        //     'payment_date'=>Carbon::now()->addDays(rand(8, 11)),
        // ]);
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
          Payment::create([
            'registration_id'=>$rege->id,
            'invoice_id'=>1,
            'payment_date'=>Carbon::now()->addDays(rand(8, 11)),
        ]);
    //     Payment::create([
    //         'registration_id'=>$rege->id,
    //         'price'=>5000,
    //     ]);

        //  $rege= Registration::create([
        //     'student_id'=>2,
        //     'course_id'=>2,
        //     'registered_at'=> now()->subDays(30)->format('Y-m-d'),
        // ]);
    //     Payment::create([
    //         'registration_id'=>$rege->id,
    //         'price'=>5000,
    //     ]);



    //s3
    $rege= Registration::create([
                'student_id'=>3,
                'course_id'=>1,
                'registered_at'=> now()->subDays(30)->format('Y-m-d'),
            ]);

              Payment::create([
            'registration_id'=>$rege->id,
            'invoice_id'=>1,
            'payment_date'=>Carbon::now()->addDays(rand(8, 11)),
        ]);

    //s4
        $rege= Registration::create([
            'student_id'=>4,
            'course_id'=>1,
            'registered_at'=> now()->subDays(30)->format('Y-m-d'),
            ]);
              Payment::create([
            'registration_id'=>$rege->id,
            'invoice_id'=>1,
            'payment_date'=>Carbon::now()->addDays(rand(8, 11)),
        ]);

    //s5
            $rege= Registration::create([
                'student_id'=>5,
                'course_id'=>1,
                'registered_at'=> now()->subDays(30)->format('Y-m-d'),
            ]);
              Payment::create([
            'registration_id'=>$rege->id,
            'invoice_id'=>1,
            'payment_date'=>Carbon::now()->addDays(rand(8, 11)),
        ]);
    }
}
