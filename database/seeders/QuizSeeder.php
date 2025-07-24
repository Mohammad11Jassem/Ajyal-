<?php

namespace Database\Seeders;

use App\Models\Choice;
use App\Models\Question;
use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $k=1;
        foreach ([1,2, 3] as $curriculumId) {

            $quiz = Quiz::create([
                'curriculum_teacher_id' => $curriculumId,
                'topic_id' => null,
                'name' => 'Quiz ' . $k,
                'type' => 'Timed',
                'available' => true,
                'start_time' => Carbon::now(),
                'duration' => 30,
            ]);

            for ($i = 1; $i <= 3; $i++) {
                $question = Question::create([
                    'quiz_id' => $quiz->id,
                    'parent_question_id' => null,
                    'mark' => 1,
                    'question_text' => "Question {$i}",
                    'hint' => "Hint for question {$i}",
                ]);

                // Add 2 choices, mark the first as correct
                Choice::create([
                    'question_id' => $question->id,
                    'choice_text' => "Correct Answer for Q{$i}",
                    'is_correct' => true,
                ]);

                Choice::create([
                    'question_id' => $question->id,
                    'choice_text' => "Wrong Answer for Q{$i}",
                    'is_correct' => false,
                ]);
            }

            for ($i = 1; $i <= 5; $i++) {

                // Create parent question
                $parent = Question::create([
                    'quiz_id' => $quiz->id,
                    'parent_question_id' => null,
                    'mark' => 2,
                    'question_text' => "Parent Question {$i}",
                    'hint' => "Hint for parent {$i}",
                ]);
                // Create child question
                for ($j = 1; $j <= 3; $j++) {

                    $child = Question::create([
                        'quiz_id' => $quiz->id,
                        'parent_question_id' => $parent->id,
                        'mark' => 1,
                        'question_text' => "Child Question {$j} related to parent {$i}",
                        'hint' => 'Hint for child',
                    ]);

                    // Add choices to child
                    Choice::create([
                        'question_id' => $child->id,
                        'choice_text' => 'Child Choice A',
                        'is_correct' => false,
                    ]);

                    Choice::create([
                        'question_id' => $child->id,
                        'choice_text' => 'Child Choice B is correct',
                        'is_correct' => true,
                    ]);
                }
            }
            $k++;
        }
    }
}
