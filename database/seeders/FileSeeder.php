<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\Choice;
use App\Models\Course;
use App\Models\Curriculum;
use App\Models\CurriculumTeacher;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Question;
use App\Models\Quiz;
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
        //ادخال المواد الرئيسية
        $records=Storage::json('Json_file/subjects.json');
        foreach($records as $record){
            Subject::create($record);
        }

        // ادخال التوبيك لكل مادة
        $records=Storage::json('Json_file/topics.json');
        foreach($records as $record){
            Topic::create($record);
        }

        //ادخال المستخدمين
        $records=Storage::json('Json_file/users.json');
        foreach($records as $record){
            User::create($record);
        }


        // ادخال الطلاب
        $records=Storage::json('Json_file/students.json');
        foreach($records as $record){
            $Student=Student::create($record);
            $user=User::find($Student->user_id);
            $user->assignRole(Role::findByName('Student', 'api'));
        }


        //ادخال الاساتذة
        $records=Storage::json('Json_file/teachers.json');
        foreach($records as $record){
            $teacher=Teacher::create($record);
            $user=User::find($teacher->user_id);
            $user->assignRole(Role::findByName('Teacher', 'api'));
            $teacher->image()->create([
                'path'=>'teachers/teacher.jpg'
            ]);
        }

        //تسجيل كل استاذ وشو بدرس
        $records=Storage::json('Json_file/teacher_subjects.json');
        foreach($records as $record){
            TeacherSubject::create($record);
        }

        // ادخال كورسات
        $records=Storage::json('Json_file/courses.json');
        foreach($records as $record){
            Course::create($record);
        }

        // ادخال مواد لكل كورس
        $records=Storage::json('Json_file/curricula.json');
        foreach($records as $record){
            Curriculum::create($record);
        }

        // ادخال كل استاذ وشو بدرس مواد مع ادخال الكويزات
        $records = Storage::json('Json_file/curriculum_teachers.json');
        $quizData = Storage::json('Json_file/quiz.json');
        $questionsData = Storage::json('Json_file/questions_choices.json');

        foreach ($records as $record) {
            $curriculumTeacher = CurriculumTeacher::create($record);

            $quiz= Quiz::create( [
                'duration'=>$quizData['duration'],
                'name'=>$quizData['name'],
                'type'=>$quizData['type'],
                'available'=>$quizData['available'],
                'curriculum_teacher_id' => $curriculumTeacher->id,
                'start_time' => now()->addMinutes(10),
            ]);

             // الآن نبني الأسئلة
            foreach ($questionsData['questions'] as $questionData) {
                // أولاً أنشئ السؤال الأساسي
                $question = Question::create([
                    'quiz_id' => $quiz->id,
                    'parent_question_id' => $questionData['parent_question_id'],
                    'mark' => $questionData['mark'],
                    'question_text' => $questionData['question_text'],
                    'hint' => $questionData['hint'] ?? null,
                ]);
                $question->image()->create([
                    'path'=>'questions/question.png'
                ]);
                // إضافة الخيارات إن وُجدت
                if (!empty($questionData['choices'])) {
                    foreach ($questionData['choices'] as $choiceData) {
                        Choice::create([
                            'question_id' => $question->id,
                            'choice_text' => $choiceData['choice_text'],
                            'is_correct' => $choiceData['is_correct'],
                        ]);
                    }
                }

                // إضافة الأسئلة الفرعية لو فيه
                if (!empty($questionData['children'])) {
                    foreach ($questionData['children'] as $childData) {
                        $childQuestion = Question::create([
                            'quiz_id' => $quiz->id,
                            'parent_question_id' => $question->id, // ربطه بالسؤال الأب
                            'mark' => $childData['mark'],
                            'question_text' => $childData['question_text'],
                            'hint' => $childData['hint'] ?? null,
                        ]);

                        // إضافة خيارات السؤال الفرعي
                        if (!empty($childData['choices'])) {
                            foreach ($childData['choices'] as $choiceData) {
                                Choice::create([
                                    'question_id' => $childQuestion->id,
                                    'choice_text' => $choiceData['choice_text'],
                                    'is_correct' => $choiceData['is_correct'],
                                ]);
                            }
                        }
                    }
                }

            }
        }

        // تسجيل الطلاب بكورس
        $records=Storage::json('Json_file/registrations.json');
        foreach($records as $record){
            Registration::create($record);
        }

        //ادخال فواتير لكل كورس
        $records=Storage::json('Json_file/invoices.json');
        foreach($records as $record){
            Invoice::create($record);
        }
        // ادخال دفعات
        $records=Storage::json('Json_file/payments.json');
        foreach($records as $record){
            Payment::create($record);
        }

         //ادخال اعلانات
        $records=Storage::json('Json_file/advertisements.json');
        foreach($records as $record){
            $Advertisement=Advertisement::create($record);

            if ($Advertisement->advertisable instanceof \App\Models\Teacher) {
                $Advertisement->images()->create([
                    'path'=>'advertisements/teacher.jpg'
                ]);
            }
            elseif($Advertisement->advertisable instanceof \App\Models\Course){
                $Advertisement->images()->create([
                    'path'=>'advertisements/course.jpg'
                ]);
            }
            else{
                $Advertisement->images()->create([

                    'path'=>'advertisements/general.png'
                ]);
            }
        }
    }
}
