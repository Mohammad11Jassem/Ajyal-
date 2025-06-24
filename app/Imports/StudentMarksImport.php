<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class StudentMarksImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
   public function collection(Collection $rows)
    {
        // Skip the first row if it contains headings
        $rows->skip(1)->each(function ($row) {
            $studentNumber = $row[0];
            $markValue     = $row[3]; // Assuming mark is column D

            $student = Student::where('student_Id_number', $studentNumber)->first();

            if ($student) {
                // Store the Paper exam mark
                // Mark::updateOrCreate(
                //     [
                //         'student_id' => $student->id,
                //         'subject_id' => $this->subjectId,
                //         'course_id'  => $this->courseId,
                //     ],
                //     [
                //         'mark' => $markValue,
                //     ]
                // );
            }
        });
    }
}
