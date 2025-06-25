<?php

namespace App\Services;

use App\Imports\StudentMarksImport;
use App\Models\PaperExam;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PaperExamService
{
    public function store($data){

        return DB::transaction(function()use($data){

            if (isset($data['file']) && $data['file']->isValid()) {

                $file=$data['file'];
                $fileName = time().'.' . $file->getClientOriginalExtension();
                $file->move(public_path('PaperExamfiles'), $fileName);
                $filePath = 'PaperExamfiles/' . $fileName;

                $exam = PaperExam::create([
                    'curriculum_id' => $data['curriculum_id'],
                    'title'         => $data['title'],
                    'description'   => $data['description'],
                    'exam_date'     => $data['exam_date'],
                    'max_degree'    => $data['max_degree'],
                    'file_path'     => $filePath,
                ]);

                Excel::import(new StudentMarksImport($exam->id), $filePath);

                return $exam;
            }
        });

    }
}
