<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all();

        foreach ($courses as $course) {
           for($i=0 ;$i<3;$i++){
                 Invoice::create([
                    'course_id' => $course->id,
                    'value'     => rand(100, 1000), // قيمة عشوائية
                    'due_date'  => Carbon::now()->addDays(rand(5, 30)), // تاريخ استحقاق عشوائي
                ]);
           }
        }
    }
}
