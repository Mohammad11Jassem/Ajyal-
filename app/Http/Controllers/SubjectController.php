<?php

namespace App\Http\Controllers;

use App\Enum\SubjectType;
use App\Http\Requests\Subject\CreateSubjectRequest;
use App\Http\Requests\Subject\SubjectFilterRequest;
use App\Services\SubjectService;
use App\Traits\HttpResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    use HttpResponse;
    protected $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    public function all(SubjectFilterRequest $subjectFilterRequest)
    {
        $typeValue = $subjectFilterRequest->validated('subjects_type');
        $subjects = $this->subjectService->all($typeValue);
        return $this->success('Subjects retrieved successfully.', $subjects);
    }

    public function allWithTopics(SubjectFilterRequest $subjectFilterRequest)
    {
        try {
            $typeValue = $subjectFilterRequest->validated('subjects_type');
            $subjects = $this->subjectService->allWithTopics($typeValue);

            return $this->success('Subjects with topics retrieved successfully.', $subjects);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve subjects with topics.', 500, $e->getMessage());
        }
    }

    public function findWithTopics($id)
    {
        try {
            $subject = $this->subjectService->findWithTopics($id);
            return $this->success('Subject with topics retrieved successfully', $subject);
        } catch (ModelNotFoundException $e) {
            return $this->notFound();
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }

    public function find($id)
    {
        try {
            $subject = $this->subjectService->find($id);
            return $this->success('Subject retrieved successfully', $subject);
        } catch (ModelNotFoundException $e) {
            return $this->notFound();
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }

    public function create(CreateSubjectRequest $createSubjectRequest)
    {
        $data = $createSubjectRequest->validated();

        try {
            // return $data['subjects_type'];
            $subject = $this->subjectService->create($data);
            return $this->success("Subject created successfully", $subject);
        } catch (\Exception $e) {
            return $this->error("Failed to create subject", 500, $e->getMessage());
        }
    }

    public function deleteSubject($id)
    {
        try {
            $deleted = $this->subjectService->deleteSubject($id);
            return $this->success('Subject deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->notFound();
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }

    public function toggleArchive($id)
    {
        $subject = $this->subjectService->toggleArchive($id);

        if (!$subject) {
            return $this->notFound();
        }

        return $this->success('Subject archive status toggled successfully', $subject);
    }

    public function getClasses(){
        return $this->success('Classes Type',SubjectType::cases());
    }
}
