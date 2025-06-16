<?php

namespace App\Repositories;

use App\Enum\SubjectType;
use App\Interfaces\SubjectRepositoryInterface;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class SubjectRepository implements SubjectRepositoryInterface
{
   public function allWithTopics(?SubjectType $type = null)
    {
        $query = Subject::nonArchived()->with('topics');

        if ($type) {
            $query->where('type', $type->value);
        }

        return $query->get();
    }

    public function all(?SubjectType $type = null)
    {
        $query = Subject::nonArchived()->where('type', $type)->get();

        // if ($type) {
        //     $query->where('type', $type);
        // }

        return $query;
    }

    public function findWithTopics($id)
    {
        return Subject::nonArchived()->with('topics')->findOrFail($id);
    }
    public function find($id)
    {
        return Subject::nonArchived()->findOrFail($id);
    }

    public function create(array $data)
    {
        // create subject
        return DB::transaction(function () use ($data) {

            $subject = Subject::create($data);

            // create topics if provided
            if (isset($data['topics']) && is_array($data['topics'])) {
                foreach ($data['topics'] as $topicName) {
                    $subject->topics()->create(['topic_name' => $topicName]);
                }
            }

            return $this->findWithTopics($subject->id);
        });
    }

    public function delete($id)
    {
        $subject = Subject::findOrFail($id);
        return $subject->delete();
    }
}
