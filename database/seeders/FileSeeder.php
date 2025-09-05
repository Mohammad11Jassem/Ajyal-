<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\Course;
use App\Models\Curriculum;
use App\Models\CurriculumTeacher;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $records=Storage::json('Json_file/subjects.json');
        foreach($records as $record){
            Subject::create($record);
        }
        $records=Storage::json('Json_file/topics.json');
        foreach($records as $record){
            Topic::create($record);
        }
        $records=Storage::json('Json_file/users.json');
        foreach($records as $record){
            User::create($record);
        }
        $records=Storage::json('Json_file/students.json');
        foreach($records as $record){
            $Student=Student::create($record);
            $user=User::find($Student->user_id);
            $user->assignRole(Role::findByName('Student', 'api'));
        }
        $records=Storage::json('Json_file/teachers.json');
        foreach($records as $record){
            $teacher=Teacher::create($record);
            $user=User::find($teacher->user_id);
            $user->assignRole(Role::findByName('Teacher', 'api'));
            $teacher->image()->create([
                'path'=>'teachers/teacher.jfif'
            ]);
        }
        $records=Storage::json('Json_file/teacher_subjects.json');
        foreach($records as $record){
            TeacherSubject::create($record);
        }
        $records=Storage::json('Json_file/advertisements.json');
        foreach($records as $record){
            $Advertisement=Advertisement::create($record);
            if ($Advertisement->advertisable instanceof \App\Models\Teacher) {
                $Advertisement->images()->create([
                    'path'=>'advertisements/teacher.jfif'
                ]);
            }
            elseif($Advertisement->advertisable instanceof \App\Models\Course){
                $Advertisement->images()->create([
                    'path'=>'advertisements/course.jpg'
                ]);
            }
            else{
                $Advertisement->images()->create([
                    'path'=>'advertisements/course.jpg'
                ]);
            }
        }
        $records=Storage::json('Json_file/courses.json');
        foreach($records as $record){
            Course::create($record);
        }
        $records=Storage::json('Json_file/curricula.json');
        foreach($records as $record){
            Curriculum::create($record);
        }
        $records=Storage::json('Json_file/curriculum_teachers.json');
        foreach($records as $record){
            CurriculumTeacher::create($record);
        }

        $records=Storage::json('Json_file/registrations.json');
        foreach($records as $record){
            Registration::create($record);
        }

        $records=Storage::json('Json_file/invoices.json');
        foreach($records as $record){
            Invoice::create($record);
        }
        $records=Storage::json('Json_file/payments.json');
        foreach($records as $record){
            Payment::create($record);
        }
    }
}
