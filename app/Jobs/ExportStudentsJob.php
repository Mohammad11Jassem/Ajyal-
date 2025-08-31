<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Exports\StudentsExport;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
class ExportStudentsJob implements ShouldQueue
{
     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $courseId;
    protected $classroomCourseId;
    protected $filename;

    /**
     * Create a new job instance.
     */
    public function __construct($courseId, $classroomCourseId, $filename)
    {
        $this->courseId = $courseId;
        $this->classroomCourseId = $classroomCourseId;
        $this->filename = $filename;

    }

    /**
     * Execute the job.
     */
    // public function handle(): void
    // {


    //     //  Excel::store(
    //     //     new StudentsExport($this->courseId, $this->classroomCourseId),
    //     //     "exports/{$this->filename}",
    //     //     'public'
    //     // );

    //      Excel::store(
    //         new StudentsExport($this->courseId, $this->classroomCourseId),
    //         $this->filename,     // لا تكتب "exports/..." لأن الـ root صار public/exports
    //         'exports'            // disk الجديد
    //     );
    //     logger("Excel file generated at: ");

    // }

    // public function handle(): void
    // {
    //     // try {
    //     //     Excel::store(
    //     //         new StudentsExport($this->courseId, $this->classroomCourseId),
    //     //         $this->filename,
    //     //         'exports'
    //     //     );

    //     //     Log::info("Excel file generated at public/exports/{$this->filename}");
    //     // } catch (\Throwable $e) {
    //     //     Log::error("ExportStudentsJob failed: " . $e->getMessage());
    //     // }
    //     Log::info('Job handle reached.');
    //     if (!file_exists(public_path('exports'))) {
    //         mkdir(public_path('exports'), 0777, true);
    //     }
    //     file_put_contents(public_path('exports/test.txt'), 'hello world');
    // }

    public function handle(): void
    {
            Excel::store(
                new StudentsExport($this->courseId, $this->classroomCourseId),
                "exports/{$this->filename}",
                'public'
            );

    }

}
