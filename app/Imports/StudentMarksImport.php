<?php

namespace App\Imports;

use App\Models\PaperExamStudent;
use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class StudentMarksImport implements ToCollection
{
    protected $paperExamId;

    public function __construct($paperExamId){
        $this->paperExamId=$paperExamId;
    }
    /**
    * @param Collection $collection
    */
   public function collection(Collection $rows)
    {

        $rows->skip(1)->each(function ($row) {
            $studentNumber = $row[0];
            $markValue     = $row[3];

            $student = Student::where('student_Id_number', $studentNumber)->first();

            if ($student) {
              
                PaperExamStudent::create([
                    'student_id'=>$student->id,
                    'paper_exam_id'=>$this->paperExamId,
                    'mark'=>$markValue,
                ]);

            }
        });
    }
}
