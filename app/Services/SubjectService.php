<?php

namespace App\Services;

use App\Enum\SubjectType;
use App\Interfaces\SubjectRepositoryInterface;
use App\Models\Subject;

class SubjectService
{
    protected $subjectRepo;

    public function __construct(SubjectRepositoryInterface $subjectRepo)
    {
        $this->subjectRepo = $subjectRepo;
    }

    public function all($type)
    {
        $enumType=SubjectType::from($type);
        // return $enumType;
        return $this->subjectRepo->all($enumType);
    }
    public function allWithTopics($type)
    {
        $enumType=SubjectType::from($type);

        return $this->subjectRepo->allWithTopics($enumType);
    }
    public function findWithTopics($id)
    {
        return $this->subjectRepo->findWithTopics($id);
    }
    public function find($id)
    {
        return $this->subjectRepo->find($id);
    }

    public function create(array $data)
    {
        do {
            $code = strtoupper(substr($data['name'], 0, 3)) . '-' . rand(100, 999);
        } while (Subject::where('subject_code', $code)->exists());

        $data['subject_code'] = $code;
        $data['type'] = $data['subjects_type'];
        return $this->subjectRepo->create($data);
    }

    public function deleteSubject($id)
    {

        return $this->subjectRepo->delete($id);
    }

    public function toggleArchive($id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return false;
        }

        $subject->archived = !$subject->archived;
        $subject->save();

        return $subject;
    }

}
