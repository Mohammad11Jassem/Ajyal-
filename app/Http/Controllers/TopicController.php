<?php

namespace App\Http\Controllers;

use App\Http\Requests\Topic\CreateTopicRequest;
use App\Http\Requests\Topic\UpdateTopicRequest;
use App\Services\TopicService;
use App\Traits\HttpResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    use HttpResponse;

    protected $topicService;

    public function __construct(TopicService $topicService)
    {
        $this->topicService = $topicService;
    }
    public function create(CreateTopicRequest $request)
    {
        $topic = $this->topicService->create($request->validated());
        return $this->success("Topic created", $topic);
    }

    public function update(UpdateTopicRequest $request, $id)
    {
        try {
            $topic = $this->topicService->update($id, $request->validated());
            return $this->success("Topic updated", $topic);
        } catch (ModelNotFoundException $e) {
            return $this->notFound();
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->topicService->delete($id);
            return $this->success("Topic deleted");
        } catch (ModelNotFoundException $e) {
            return $this->notFound();
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }
}
