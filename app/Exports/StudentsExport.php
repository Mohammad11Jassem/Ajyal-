<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class StudentsExport implements FromCollection , WithHeadings , ShouldAutoSize, WithStyles
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

        //
          return Student::whereHas('courses', function($query) use($courseId){
                    $query->where('courses.id',$courseId);
            })->whereHas('courses.classroomCourse.sortStudents', function ($query) use ($classroomCourseId) {
                        $query->where('sort_students.classroom_course_id', $classroomCourseId);
                    })
                    // ->select(['student_Id_number','first_name','father_name'])
                    ->get()
                     ->map(function ($student) {
                        return [
                            'student_number' =>$student->student_Id_number,
                            'student_name' =>"$student->first_name"." $student->last_name" ,
                            'father_name' => $student->father_name,
                            'mark' => '',
                        ];
                    });
    }

    public function styles(Worksheet $sheet)
    {
        // Apply center alignment to all cells
        $sheet->getStyle('A1:Z1000')->getAlignment()->setHorizontal('center');

        // Optional: Center vertically too
        $sheet->getStyle('A1:Z1000')->getAlignment()->setVertical('center');

        return [];
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
