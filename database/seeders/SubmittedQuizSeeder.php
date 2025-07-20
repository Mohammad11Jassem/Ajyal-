<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\StudentAnswer;
use App\Models\StudentQuiz;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubmittedQuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Two students
        $students = [1, 2];

        // All quizzes created previously
        $quizzes = Quiz::with(['questions.choices'])->get();

         foreach ($students as $studentId) {

            foreach ($quizzes as $quiz) {

                // Create student quiz submission
                $studentQuiz = StudentQuiz::create([
                    'student_id' => $studentId,
                    'quiz_id' => $quiz->id,
                    'is_submit' => true,
                    'result' => 0,
                ]);

                // Get only child questions (simulate realistic behavior)
                // $questions = $quiz->questions->has('parent_question_id');
                 $allQuestions = collect();

                // Loop through parent questions
                foreach ($quiz->questions as $question) {
                    if ($question->choices->isNotEmpty()) {
                        $allQuestions->push($question);
                    }

                    // Loop through each child of the parent
                    foreach ($question->children as $child) {
                        if ($child->choices->isNotEmpty()) {
                            $allQuestions->push($child);
                        }
                    }
                }

                foreach ($allQuestions as $question) {
                    $choices = $question->choices;

                    // Randomly pick a WRONG choice if available
                    $wrongChoice = $choices->where('is_correct', false)->random();

                    StudentAnswer::create([
                        'student_quiz_id' => $studentQuiz->id,
                        'question_id' => $question->id,
                        'selected_choice_id' => $wrongChoice->id,
                        'answered_at' => Carbon::now(),
                    ]);
                }

                // Optionally: calculate fake result (e.g., always 0 because all are wrong)
                $studentQuiz->update([
                    'result' => 0,
                ]);
            }
        }
    }
}
