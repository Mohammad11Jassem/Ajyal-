<?php

namespace Database\Seeders;

use App\Models\CurriculumFile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CurriculumFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Get all PDF files in public/pdf
        $pdfFiles = File::files(public_path('Curriculumfiles'));

        foreach ($pdfFiles as $file) {
            CurriculumFile::create([
                'title' => $file->getFilenameWithoutExtension(),
                'file_path' => 'Curriculumfiles/' . $file->getFilename(), // relative to public/
                'curriculum_id' => 1,
            ]);
        }
        //2
        foreach ($pdfFiles as $file) {
            CurriculumFile::create([
                'title' => $file->getFilenameWithoutExtension(),
                'file_path' => 'Curriculumfiles/' . $file->getFilename(), // relative to public/
                'curriculum_id' => 2,
            ]);
        }
        //3
        foreach ($pdfFiles as $file) {
            CurriculumFile::create([
                'title' => $file->getFilenameWithoutExtension(),
                'file_path' => 'Curriculumfiles/' . $file->getFilename(), // relative to public/
                'curriculum_id' => 3,
            ]);
        }
        //4
        foreach ($pdfFiles as $file) {
            CurriculumFile::create([
                'title' => $file->getFilenameWithoutExtension(),
                'file_path' => 'Curriculumfiles/' . $file->getFilename(), // relative to public/
                'curriculum_id' => 4,
            ]);
        }
    }
}
