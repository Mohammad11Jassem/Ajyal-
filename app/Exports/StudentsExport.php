<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection , WithHeadings
{
    protected $courseId;
    protected $classroomCourseId;

    public function __construct($courseId, $classroomCourseId)
    {
        $this->courseId = $courseId;
        $this->classroomCourseId = $classroomCourseId;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $courseId=$this->courseId;
        $classroomCourseId=$this->classroomCourseId;
        // return Student::whereHas('courses', function($query) use($courseId){
        //             $query->where('id',$courseId);
        //     })
        //     ->whereHas('courses.classroomCourse', function ($query) use($classroomCourseId) {
        //         $query->where('course_id', $classroomCourseId);
        //     })
        //     ->get();
          return Student::whereHas('courses', function($query) use($courseId){
                    $query->where('courses.id',$courseId);
            })->whereHas('courses.classroomCourse.sortStudents', function ($query) use ($classroomCourseId) {
                        $query->where('sort_students.classroom_course_id', $classroomCourseId);
                    })

                    ->get();
    }

    public function headings(): array
    {
        return [
            'رقم الطالب',
            'اسم الطالب',
            'اسم الاب',
            'الدرجة',
        ];
    }
}
