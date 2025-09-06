<?php

namespace App\Http\Controllers;

use App\Http\Requests\Note\AddNoteRequest;
use App\Services\NoteService;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
class NoteController extends Controller
{
    use HttpResponse;
    protected NoteService $noteService;

    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
    }
    public function storeNote(AddNoteRequest $addNoteRequest){
        $data=$addNoteRequest->validated();
        $result=$this->noteService->storeNote($data);
        return $this->success($result['message'],$result);
    }

    public function studentNotes($studentId){
        $result=$this->noteService->studentNotes($studentId);
        return $this->success($result['message'],$result['data']);
    }
    public function allNotes(){
    $result=$this->noteService->allNotes();
    return $this->success($result['message'],$result['data']);

    }
    public function show($id){
    $result=$this->noteService->show($id);
    return $this->success($result['message'],$result['data']);
    }

}
