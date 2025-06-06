<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
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
            'number_civial' => fake()->optional()->numerify('##########'),
            'address' => fake()->optional()->address(),
            'mother_name' => fake()->optional()->firstName('female'),
            'father_name' => fake()->optional()->firstName('male'),
            'QR' => fake()->optional()->uuid(),
            'location' => "LocaT1",
            'access_code' => 'ABCD1234',
        ]);

        $user->assignRole(Role::findByName('Student', 'api'));
    }
}
