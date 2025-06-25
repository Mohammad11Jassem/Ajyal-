<?php

namespace App\Http\Controllers;

use App\Exports\StudentsExport;
use App\Http\Requests\ExcelFile\DownloadFileRequest;
use App\Http\Requests\ExcelFile\ImportExcelRequest;
use App\Imports\StudentMarksImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function downloadStudentsExcel(DownloadFileRequest $downloadFileRequest)
    {
        $data=$downloadFileRequest->validated();
        // $data['classroom_course_id']=;
        // $data['course_id']=;
        $filename = "طلاب الشعبة (رقم {$data['classroom_course_id']}).xlsx";
        return Excel::download(new StudentsExport($data['course_id'],$data['classroom_course_id']), $filename);
    }

    public function importExcel(ImportExcelRequest $request)
    {
        $file = $request->file('file');
        // Excel::import(new StudentMarksImport, $file);

        return response()->json([
            'message' => 'تم استيراد البيانات بنجاح',
        ]);
    }
}
