<?php

namespace App\Jobs;

use App\Models\CurriculumFile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
class StoreCurriculumFileJob implements ShouldQueue
{
     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $curriculumId;
    /**
     * Create a new job instance.
     */
    public function __construct($data,$curriculumId)
    {
        $this->data=$data;
        $this->curriculumId=$curriculumId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // $file=$this->data['file'];
        // $fileName = time().'.' . $file->getClientOriginalExtension();
        // $file->move(public_path('Curriculumfiles'), $fileName);
        // $filePath = 'Curriculumfiles/' . $this->data['fileName'];

         CurriculumFile::create([
            'curriculum_id' => $this->curriculumId,
            'title' => $this->data['title'],
            'file_path' =>$this->data['filePath'],
        ]);
    }
}
