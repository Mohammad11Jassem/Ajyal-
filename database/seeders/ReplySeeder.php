<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Issue;
use App\Models\Reply;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $issue = Issue::find(2) ;
        $teacher = Teacher::first() ;
        $student = Student::first() ;

        // Reply from a student
       $reply= Reply::create([
            'issue_id'    => $issue->id,
            'author_id'   => $teacher->id,
            'author_type' => Teacher::class,
            'body'        => 'الجواب 1 للاستاذ',
        ]);
        Image::create([
            'path'          => 'replies/175689575114.jpg',
            'imageable_id'  => $reply->id,
            'imageable_type'=> Reply::class,
        ]);
       $reply= Reply::create([
            'issue_id'    => $issue->id,
            'author_id'   => $student->id,
            'author_type' => Student::class,
            'body'        => 'الجواب 1 للطالب',
        ]);
         Image::create([
            'path'          => 'replies/175689575114.jpg',
            'imageable_id'  => $reply->id,
            'imageable_type'=> Reply::class,
        ]);
       $reply= Reply::create([
            'issue_id'    => $issue->id,
            'author_id'   => $teacher->id,
            'author_type' => Teacher::class,
            'body'        => 'الجواب 2 للاستاذ',
        ]);
         Image::create([
            'path'          => 'replies/175689575114.jpg',
            'imageable_id'  => $reply->id,
            'imageable_type'=> Reply::class,
        ]);
    }
}
