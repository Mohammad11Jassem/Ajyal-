<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Curriculum;
use App\Models\CurriculumFile;
use App\Repositories\CourseRepository;
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
            }
        });
    }

    public function AllCourses(){
        return Course::all();
    }
    public function Allfile($courseId){

    }
}
