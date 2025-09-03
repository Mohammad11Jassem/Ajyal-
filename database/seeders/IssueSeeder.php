<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\Image;
use App\Models\Issue;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IssueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $community = Community::first();

        $teacher = Teacher::first();
        $student = Student::first();

        // Issue by a teacher
       $issue=Issue::create([
            'community_id' => $community->id,
            'author_id'    => $teacher->id,
            'author_type'  => Teacher::class,
            'body'         => 'سؤال من الأستاذ',
            'is_fqa'       => true,
        ]);
        Image::create([
            'path'          => 'issues/17568878256.png',
            'imageable_id'  => $issue->id,
            'imageable_type'=> Issue::class,
        ]);
        // Issue by a student
       $issue=Issue::create([
            'community_id' => $community->id,
            'author_id'    => $student->id,
            'author_type'  => Student::class,
            'body'         => 'سؤال من الطالب',
            'is_fqa'       => false,
        ]);
         Image::create([
            'path'          => 'issues/17568878256.png',
            'imageable_id'  => $issue->id,
            'imageable_type'=> Issue::class,
        ]);
    }
}
