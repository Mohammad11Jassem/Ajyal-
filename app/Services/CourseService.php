<?php

namespace App\Services;

use App\Jobs\StoreCurriculumFileJob;
use App\Models\Classroom;
use App\Models\ClassroomCourse;
use App\Models\Course;
use App\Models\Curriculum;
use App\Models\CurriculumFile;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\SortStudent;
use App\Models\Student;
use App\Repositories\CourseRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class CourseService
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }
    public function store(array $validated)
    {
        return DB::transaction(function () use ($validated) {

            do {
                $code = 'COU-' . strtoupper(Str::random(6));
            } while (Course::where('code', $code)->exists());

            $course = $this->courseRepository->store([
                'name' => $validated['name'],
                'cost' => $validated['cost'],
                'type' => $validated['type'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'code' => $code,
                'capacity' => $validated['capacity'],
            ]);

            $course->classrooms()->attach($validated['classrooms']);

            foreach ($validated['subjects'] as $subject) {
                $curriculum = Curriculum::create([
                    'course_id' => $course->id,
                    'subject_id' => $subject['subject_id'],
                ]);

                $curriculum->teachers()->attach($subject['teachers']);
            }

            return [
                'succuss'=>true,
                'data'=>$course,
            ];
        });


    }

    public function show($id)
    {
        try{

             return [
                'succuss'=>true,
                'data'=>$this->courseRepository->show($id),
            ];
        }catch(Exception $exception){
             return [
                'succuss'=>false,
                'message'=>$exception->getMessage(),
            ];
        }
    }

    public function destroy($id)
    {
        return $this->courseRepository->destroy($id);
    }

    public function storeFile($curriculumId, array $data)
    {
        return DB::transaction(function () use ($data,$curriculumId) {

            if (isset($data['file']) && $data['file']->isValid()) {
                    $file=$data['file'];
                    $fileName = time().'.' . $file->getClientOriginalExtension();
                    $file->move(public_path('Curriculumfiles'), $fileName);
                    $filePath = 'Curriculumfiles/' . $fileName;

                    return CurriculumFile::create([
                        'curriculum_id' => $curriculumId,
                        'title' => $data['title'],
                        'file_path' =>$filePath,
                    ]);
                // $fileName = time().'.' . $file->getClientOriginalExtension();
                // $file->move(public_path('Curriculumfiles'), $fileName);
                // $filePath = 'Curriculumfiles/' . $fileName;
                // // $data['fileName']=$fileName;
                // $jobData['title']=$data['title'];
                // $jobData['filePath']=$filePath;
                // StoreCurriculumFileJob::dispatch($jobData,$curriculumId);
                // $file=CurriculumFile::latest()->first();
                // return $file;
            }
        });
    }

    public function AllCourses(){
        return Course::all();
    }
    public function AllfileForCourse($courseId){
         $course = Course::findOrFail($courseId);

        $files = $course->files()
            ->with('curriculum.subject')
            ->get()
            ->map(function ($file) {
                return [
                    'id' => $file->id,
                    'title' => $file->title,
                    'file_path' => $file->file_path,
                    'curriculum_id' => $file->curriculum_id,
                    'created_at' => $file->created_at,
                    'updated_at' => $file->updated_at,
                    'subject' => optional($file->curriculum->subject)->only([
                        'id', 'name',
                    ])
                ];
            });

            return $files;
    }

    public function getCurrentAndIncomingCourses()
    {
        $today = Carbon::today();

        return Course::where('start_date', '>=', $today)
                    ->orderBy('start_date', 'asc')
                    ->get();
    }

    public function classRoomsCourse($courseId){
        return ClassroomCourse::where('course_id',$courseId)
                            ->with('classRoom')
                            ->get()
                            ->map(function ($classRoomCourse) {
                                return [
                                    'id' => $classRoomCourse->classRoom->id,
                                    'class_number' => $classRoomCourse->classRoom->class_number,
                                ];
                            });

    }


    public function registerStudent(array $data){
        try{
            DB::beginTransaction();

                $registration = Registration::create([
                    'course_id' => $data['course_id'],
                    'student_id' => $data['student_id']
                ]);

                $payment = Payment::create([
                    'registration_id' => $registration->id,
                    'price' => $data['payment']
                ]);

                DB::commit();

                return [
                    'success'=>true,
                    'message' => 'تم التسجيل والدفع بنجاح.',
                ];
            } catch (\Exception $e) {
                DB::rollBack();

                return [
                    'success'=>false,
                    'error' => 'فشل التسجيل والدفع',
                    'message' => $e->getMessage()
                ];
            }

    }

    public function AllStudentAtCourse($course_id){
        return [
            'success'=>true,
            'data'=>Registration::where('course_id',$course_id)->with('student')->get(),
            'message'=>'كل طلاب الكورس'
        ];

    }

    public function curriculumsCourse($courseId){
        return Course::with('subjects')->findOrFail($courseId);
    }

    public function sortStudentAtClassRoom(array $data){

        try{
            $classRoom=ClassroomCourse::where('id',$data['class_course_id'])->first();
            $classRoom->registrations()->attach($data['registration_id']);

                return [
                    'success'=>true,
                    'message' => 'تم فرز الطلاب على الشعب بنجاح',
                ];


        }catch(Exception $e){
            return [
                    'success'=>false,
                    'error' => 'فشل فرز الطلاب ',
                    'message' => $e->getMessage()
                ];
        }

    }

    public function AllStudentAtCourseAtClass(array $data)
    {

        $courseId=$data['courseId'];
        $classroomCourseId=$data['classroomCourseId'];

        //Registration::where('course_id',$course_id)->with('student')->get(),
        // $students=Student::whereHas('courses', function($query) use($courseId){
        //             $query->where('courses.id',$courseId,);
        //     })->whereHas('courses.classroomCourse.sortStudents', function ($query) use ($classroomCourseId) {
        //                 $query->where('sort_students.classroom_course_id', $classroomCourseId);
        //             })

        //             ->get();

        $students=Registration::where('course_id',$courseId)
                                ->whereHas('classroom_courses', function($query) use($classroomCourseId){
                            $query->where('classroom_course_id',$classroomCourseId,);
                        })->with('student')->get();

        return [
            'success'=>true,
            'data'=>$students,
            'message'=>'كل طلاب الكورس في صف محدد'
        ];

    }

    public function getFiles($curriculumId)
    {
        $curriculum = Curriculum::with('files')->findOrFail($curriculumId);
        return $curriculum;
    }

    public function showFile($fileId)
    {
         $file = CurriculumFile::findOrFail($fileId);
         return $file;
    }


}
