<?php

namespace App\Services;

use App\Models\Choice;
use App\Models\Question;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class QuestionService
{
    public function create(array $data)
    {
        try{
            return DB::transaction(function () use ($data) {

                $question = Question::create([
                    'quiz_id' => $data['quiz_id'],
                    'question_text' => $data['question_text'],
                    'mark' => $data['mark']??1,
                    'hint' => $data['hint'],
                ]);
                // Save image if exists
                if (isset($data['image']) && $data['image']->isValid()) {
                        // $imageFile = $data['image'];
                        // $image = $question->image()->create([
                        //     'path' => '' // Temporary, will be updated after saving the file
                        // ]);
                        // $imageName = time().$image->id. '.' . $imageFile->getClientOriginalExtension();
                        // $imageFile->move(public_path('questions'), $imageName);
                        // $imagePath = 'questions/' . $imageName;
                        // $image->path=$imagePath;
                        // $image->save();
                        $this->saveQuestionImage($data['image'],$question);
                }
                // Add children if any
                if (!empty($data['children'])) {
                    foreach ($data['children'] as $child) {
                        $subQuestion =$question->children()->create([
                            'quiz_id' => $data['quiz_id'],
                            'question_text' => $child['question_text'],
                            'mark' => $child['mark']??1,
                            'hint' => $child['hint'],
                        ]);

                        // Save image if exists
                        if (isset($child['image']) && $child['image']->isValid()) {
                            // $imageFile = $child['image'];
                            // $image = $subQuestion->image()->create([
                            //     'path' => '' // Temporary, will be updated after saving the file
                            // ]);
                            // $imageName = time().$image->id. '.' . $imageFile->getClientOriginalExtension();
                            // $imageFile->move(public_path('questions'), $imageName);
                            // $imagePath = 'questions/' . $imageName;
                            // $image->path=$imagePath;
                            // $image->save();
                            $this->saveQuestionImage($child['image'],$subQuestion);

                        }
                        if (isset($child['choices']) && is_array($child['choices'])) {
                            $subQuestion->choices()->createMany($child['choices']);
                        }
                    }
                }else{
                        // This question has NO children → can have choices
                    if (isset($data['choices']) && is_array($data['choices'])) {
                        $question->choices()->createMany($data['choices']);
                    }

                }

                return [
                    'success' => true,
                    'message' => 'Question created successfully',
                    'data' =>$question->load('choices','image','children.choices','children.image')
                ];
            });

        }catch(Exception $e){
            return[
                'success' => false,
                'message' => 'Failed to create Question',
                'error' => $e->getMessage()
            ];
        }
    }

    public function saveQuestionImage(UploadedFile $imageFile, $relatedModel, string $folder = 'questions')
    {
        // Create a temporary image record
        $image = $relatedModel->image()->create([
            'path' => '' // Temporary, updated after file move
        ]);

        $imageName = time() . $image->id . '.' . $imageFile->getClientOriginalExtension();
        $imageFile->move(public_path($folder), $imageName);

        $imagePath = $folder . '/' . $imageName;
        $image->path = $imagePath;
        $image->save();

    }


    public function storeQuestions(array $questions, $parentId = null)
    {
        try{
            $created = [];

            return DB::transaction(function () use ($questions,$parentId) {

                // foreach ($questions['questions'] as $questionData) {
                //     $question = Question::create([
                //         'quiz_id' => $questionData['quiz_id'],
                //         'question_text' => $questionData['question_text'],
                //         'hint' => $questionData['hint'] ?? null,
                //         'mark' => $questionData['mark'] ?? 10,
                //         'parent_question_id' => $parentId,
                //     ]);

                //     // ✅ If the current question has children, call recursively
                //     if (isset($questionData['children']) && is_array($questionData['children'])) {
                //         $this->storeQuestions($questionData['children'], $question->id);
                //     } else {
                //         // ✅ Only add choices if this question has no children
                //         if (isset($questionData['choices']) && is_array($questionData['choices'])) {
                //             foreach ($questionData['choices'] as $choiceData) {
                //                 $question->choices()->create([
                //                 'choice_text' => $choiceData['text'],
                //                 'is_correct' => $choiceData['is_correct'] ?? false,
                //             ]);
                //             }
                //         }
                //     }
                //     $created[] = $question->load('children');
                // }
                foreach ($questions['questions'] as $questionData) {
                    $question = Question::create([
                        'quiz_id' => $questions['quiz_id'],
                        'question_text' => $questionData['question_text'],
                        'hint' => $questionData['hint'] ?? null,
                        'mark' => $questionData['mark'] ?? 10,
                        // 'parent_question_id' => $parentId,
                    ]);
                    // Save image if exists
                    if (isset($questionData['image']) && $questionData['image']->isValid()) {
                        $imageFile = $questionData['image'];
                        $image = $question->image()->create([
                            'path' => '' // Temporary, will be updated after saving the file
                        ]);
                        $imageName = time().$image->id. '.' . $imageFile->getClientOriginalExtension();
                        $imageFile->move(public_path('questions'), $imageName);
                        $imagePath = 'questions/' . $imageName;
                        $image->path=$imagePath;
                        $image->save();

                    }

                    if (isset($questionData['children']) && is_array($questionData['children'])) {

                        foreach ($questionData['children'] as $subQ) {

                            $subQuestion = $question->children()->create([
                            'quiz_id' => $questions['quiz_id'],
                                'question_text' => $subQ['question_text'],
                                'hint' => $subQ['hint'] ?? null,
                                'mark' => $subQ['mark'] ?? 10,
                                // 'parent_question_id' => $parentId,
                            ]);

                            // Save image if exists
                            if (isset($subQ['image']) && $subQ['image']->isValid()) {
                                $imageFile = $subQ['image'];
                                $image = $subQuestion->image()->create([
                                    'path' => '' // Temporary, will be updated after saving the file
                                ]);
                                $imageName = time().$image->id. '.' . $imageFile->getClientOriginalExtension();
                                $imageFile->move(public_path('questions'), $imageName);
                                $imagePath = 'questions/' . $imageName;
                                $image->path=$imagePath;
                                $image->save();

                            }
                            if (isset($subQ['choices']) && is_array($subQ['choices'])) {
                                $subQuestion->choices()->createMany($subQ['choices']);
                            }
                            // $subQuestion->choices()->createMany($subQ['choices']);
                        }
                    } elseif (isset($questionData['choices']) && is_array($questionData['choices'])) {
                            $question->choices()->createMany($questionData['choices']);
                    }
                        // $question->choices()->createMany($questionData['choices']);
                }

                return [
                'success' => true,
                'message' => 'Question created successfully',
                ];
            });


    }catch(Exception $e){
                    return[
                'success' => false,
                'message' => 'Failed to create Question',
                'error' => $e->getMessage()
        ];
    }
    }

    public function showQuestion($questionID){

    return [
                'success' => true,
                'message' => 'Question',
                'data' =>Question::with(['children.choices', 'choices','image','children.image'])->findOrFail($questionID)

            ];

    }


    public function update(array $data){
        return DB::transaction(function () use ($data) {
            $question = Question::findOrFail($data['question_id']);

            //delete choices
            $question?->choices()?->delete();
            //delete child question
            $question?->children()?->delete();

            $question->update([
                'parent_question_id'=>$data['parent_question_id']??$question['parent_question_id'],
                'question_text' => $data['question_text']??$question['question_text'],
                'hint' => $data['hint']??$question['hint'],
            ]);

            // // Update choices
            // if(isset($data['choices'])){
            //     foreach ($data['choices'] as $choiceData) {
            //         // $choice=Choice::where('id',$choiceData['id']??null)->first()??null;
            //         // Choice::updateOrCreate(
            //         //     ['id' => $choiceData['id'] ?? null, 'question_id' => $question->id],
            //         //     [
            //         //         'choice_text' => $choiceData['choice_text']??$choice['choice_text'],
            //         //         'is_correct' => $choiceData['is_correct']??$choice['is_correct'],
            //         //     ]
            //         // );
            //         Choice::create([
            //             'choice_text' => $choiceData['choice_text'],
            //             'is_correct' => $choiceData['is_correct'],
            //         ]);
            //     }

            // }
            //add child question if there
           // Add children if any
                if (isset($data['children'])) {
                    foreach ($data['children'] as $child) {
                        $subQuestion =$question->children()->create([
                            'quiz_id' => $question['quiz_id'],
                            'question_text' => $child['question_text'],
                            'mark' => $child['mark']??1,
                            'hint' => $child['hint']??null,
                        ]);

                        // Save image if exists
                        if (isset($child['image'])) {
                            $this->saveQuestionImage($child['image'],$subQuestion);

                        }
                        if (isset($child['choices']) && is_array($child['choices'])) {
                            $subQuestion->choices()->createMany($child['choices']);
                        }
                    }
                }else{
                        // This question has NO children → can have choices
                    if (isset($data['choices']) && is_array($data['choices'])) {
                        $question->choices()->createMany($data['choices']);
                    }

                }

            if (isset($data['image'])) {
                // Delete old image if exists
                if ($question->image) {
                    File::delete($question->image->path);
                    $question->image()->delete();
                }
                $this->saveQuestionImage($data['image'],$question);
                // $path = $request->file('image')->store('question_images', 'public');

                // $question->image()->create([
                //     'path' => $path,
                // ]);
            }
            return $this->showQuestion($data['question_id'])['data'];
        });
    }

    public function delete($questionId){
        Question::findOrFail($questionId)->delete();
        return[
            'success'=>true,
            'message'=>'Question has been deleted'
        ];
    }
}
