<?php

namespace Database\Seeders;

use App\Enum\SubjectType;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Create a user first (assuming user_id is required)
        $user = User::create([
            'password'=>123456789
        ]);

        // Create a student
       $student=Student::create([
            'user_id' => $user->id,
            'student_Id_number' => 1000,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'number_civial' => "150150150",
            'address' => fake()->address(),
            'mother_name' => fake()->firstName('female'),
            'father_name' => fake()->firstName('male'),
            'birthdate'=>Carbon::now()->format('Y-m-d'),
            'class_level'=>SubjectType::ScientificBaccalaureate,
            // 'QR' => fake()->optional()->uuid(),
            // 'location' => "LocaT1",
            'access_code' => 'ABCD1234',
        ]);

        $user->assignRole(Role::findByName('Student', 'api'));

         // Create a user first (assuming user_id is required)
        $user = User::create([
            'password'=>123456789
        ]);

        // Create a student
       $student=Student::create([
            'user_id' => $user->id,
            'student_Id_number' => 1001,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'number_civial' => "205205205",
            'address' => fake()->address(),
            'mother_name' => fake()->firstName('female'),
            'father_name' => fake()->firstName('male'),
            'birthdate'=>Carbon::now()->format('Y-m-d'),
            'class_level'=>SubjectType::NinthGrade,
            // 'QR' => fake()->optional()->uuid(),
            // 'location' => "LocaT1",
            'access_code' => 'EFGH1234',
        ]);

        $user->assignRole(Role::findByName('Student', 'api'));

        /////////////////////////

        // Create a user first (assuming user_id is required)
        $user = User::create([
            'password'=>123456789
        ]);

        // Create a student
       $student=Student::create([
            'user_id' => $user->id,
            'student_Id_number' => 1002,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'number_civial' => "205205205",
            'address' => fake()->address(),
            'mother_name' => fake()->firstName('female'),
            'father_name' => fake()->firstName('male'),
            'birthdate'=>Carbon::now()->format('Y-m-d'),
            'class_level'=>SubjectType::NinthGrade,
            // 'QR' => fake()->optional()->uuid(),
            // 'location' => "LocaT1",
            'access_code' => 'MMMM1111',
        ]);

        $user->assignRole(Role::findByName('Student', 'api'));

        ///////////////

        // Create a user first (assuming user_id is required)
        $user = User::create([
            'password'=>123456789
        ]);

        // Create a student
       $student=Student::create([
            'user_id' => $user->id,
            'student_Id_number' => 1001,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'number_civial' => "205205205",
            'address' => fake()->address(),
            'mother_name' => fake()->firstName('female'),
            'father_name' => fake()->firstName('male'),
            'birthdate'=>Carbon::now()->format('Y-m-d'),
            'class_level'=>SubjectType::NinthGrade,
            // 'QR' => fake()->optional()->uuid(),
            // 'location' => "LocaT1",
            'access_code' => 'AAAA1111',
        ]);

        $user->assignRole(Role::findByName('Student', 'api'));

        ////////////////////////

        // Create a user first (assuming user_id is required)
        $user = User::create([
            'password'=>123456789
        ]);

        // Create a student
       $student=Student::create([
            'user_id' => $user->id,
            'student_Id_number' => 1001,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'number_civial' => "205205205",
            'address' => fake()->address(),
            'mother_name' => fake()->firstName('female'),
            'father_name' => fake()->firstName('male'),
            'birthdate'=>Carbon::now()->format('Y-m-d'),
            'class_level'=>SubjectType::NinthGrade,
            // 'QR' => fake()->optional()->uuid(),
            // 'location' => "LocaT1",
            'access_code' => 'BBBB1111',
        ]);

        $user->assignRole(Role::findByName('Student', 'api'));

        //////////////////////////
    }
}
