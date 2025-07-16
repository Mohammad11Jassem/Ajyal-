<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Models\VerifyCode;
use App\Services\TeacherService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Contracts\Role;
use Illuminate\Http\UploadedFile;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

    //     $teacherService = new TeacherService();

    //     Storage::fake('public'); // use fake disk for test images

    //     for ($i = 0; $i < 5; $i++) {
    //         $data = [
    //             'name' => fake()->name,
    //             'email' => fake()->unique()->safeEmail,
    //             'date_of_contract' => now()->subDays(rand(1, 365))->format('Y-m-d'),
    //             'phone_number' => fake()->phoneNumber,
    //             'bio' => fake()->paragraph,
    //             'subjects' => [1, 2], // assuming subject IDs exist in DB
    //             'avatar' => UploadedFile::fake()->image('avatar.jpg', 300, 300),

    //             'password'=>'123456',
    //         ];

    //         $result = $teacherService->createTeacher($data);

    //         $registerTeacher=$teacherService->RegisterTeacher($data);
    //         $verifyCode=VerifyCode::where('user_id',$registerTeacher['data']['teacher']->user_id)->first();
    //         $verifyCode['confirmed']=true;
    //         $verifyCode->save();

    //         if (!$result['success'] || !$registerTeacher['success']) {
    //             echo "Failed: " . $result['error'] . PHP_EOL;
    //         }


    //         }
    // }

    $teacherService = new TeacherService();

        Storage::fake('public'); // use fake disk for test images

        for ($i = 0; $i < 5; $i++) {
            $data = [
                'name' => fake()->name,
                'email' => fake()->unique()->safeEmail,
                'date_of_contract' => now()->subDays(rand(1, 365))->format('Y-m-d'),
                'phone_number' => fake()->phoneNumber,
                'bio' => fake()->paragraph,
                'subjects' => [1, 2], // assuming subject IDs exist in DB
                'avatar' => UploadedFile::fake()->image('avatar.jpg', 300, 300),

                'password'=>'123456',
            ];

            $result = $teacherService->createTeacher($data);

            $registerTeacher=$teacherService->RegisterTeacher($data);
            $verifyCode=VerifyCode::where('user_id',$registerTeacher['data']['teacher']->user_id)->first();
            $verifyCode['confirmed']=true;
            $verifyCode->save();

            if (!$result['success'] || !$registerTeacher['success']) {
                echo "Failed: " . $result['error'] . PHP_EOL;
            }


            }
    }
}
