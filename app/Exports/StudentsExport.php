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
        return Student::where('classroom_id', $this->classroomCourseId)
            ->whereHas('classroom.courses', function ($query) {
                $query->where('courses.id', $this->courseId);
            })
            ->select('id', 'name', 'email', 'created_at') // adjust fields as needed
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
