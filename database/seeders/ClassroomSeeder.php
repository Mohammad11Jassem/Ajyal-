<?php

namespace Database\Seeders;

use App\Models\Classroom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         for ($i = 1; $i <= 7; $i++) {
            Classroom::create([
                'class_number' => 'Class ' . $i,
                //'capacity' => 40,
            ]);
        }
    }
}
