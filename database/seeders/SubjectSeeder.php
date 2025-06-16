<?php

namespace Database\Seeders;

use App\Enum\SubjectType;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subject1=Subject::create([
            'name'=>'S1',
            'subject_code'=>'SSS-888',
            'type'=>SubjectType::LiteraryBaccalaureate,
            'description'=>'Subject 1'
        ]);

        for($i=1;$i<=5;$i++){
            Topic::create([
                'subject_id'=>$subject1->id,
                'topic_name'=>"Topic $i For subject $subject1->id"
            ]);
        }

        $subject2=Subject::create([
            'name'=>'S2',
            'subject_code'=>'SSS-999',
            'type'=>SubjectType::NinthGrade,
            'description'=>'Subject 2'
        ]);

        for($i=1;$i<=5;$i++){
            Topic::create([
                'subject_id'=>$subject2->id,
                'topic_name'=>"Topic $i For subject $subject2->id"
            ]);
        }
        $subject3=Subject::create([
            'name'=>'S3',
            'subject_code'=>'SSS-777',
            'type'=>SubjectType::ScientificBaccalaureate,
            'description'=>'Subject 3'
        ]);

        for($i=1;$i<=5;$i++){
            Topic::create([
                'subject_id'=>$subject3->id,
                'topic_name'=>"Topic $i For subject $subject3->id"
            ]);
        }
    }
}
