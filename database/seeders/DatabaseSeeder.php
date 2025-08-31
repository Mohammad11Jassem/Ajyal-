<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Permissions must be created before roles
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            ManagerSeeder::class,
            StudentSeeder::class,
            ParentModelSeeder::class,
            ClassroomSeeder::class,
            SubjectSeeder::class,
            TeacherSeeder::class,
            CourseSeeder::class,
            CurriculumFileSeeder::class,
            RegistrationSeeder::class,
            QuizSeeder::class,
            SubmittedQuizSeeder::class,
            SortStudentSeeder::class,
            AbsenceDateSeeder::class,
        ]);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
