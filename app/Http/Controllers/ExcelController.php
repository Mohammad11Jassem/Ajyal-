<?php

namespace App\Http\Controllers;

use App\Exports\StudentsExport;
use App\Http\Requests\ExcelFile\DownloadFileRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function downloadStudentsExcel(DownloadFileRequest $downloadFileRequest)
    {
        $data=$downloadFileRequest->validated();
        $filename = "طلاب الشعبة (رقم {$data['classroom_course_id']}).xlsx";
        return Excel::download(new StudentsExport($data['course_id'],$data['classroom_course_id']), $filename);
    }
}
