<?php

namespace App\Http\Controllers;

use App\Http\Requests\Complaint\StoreComplaintRequest;
use App\Models\Complaint;
use App\Services\ComplaintService;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    use HttpResponse;
    protected ComplaintService $complaintService;

    public function __construct(ComplaintService $complaintService)
    {
        $this->complaintService = $complaintService;
    }
    public function store(StoreComplaintRequest $storeComplaintRequest)
    {
        $result=$this->complaintService->stroe($storeComplaintRequest->validated());
        if(!$result['success'])
          return $this->error($result['error']);
        else
        return $this->success($result['message'],$result['data']);
    }

    /**
     * Get a specific complaint
     */
    public function show($id)
    {
        $result=$this->complaintService->show($id);
        if(!$result['success'])
        return $this->error($result['message'],$result);
        else
        return $this->success($result['message'],$result['data']);
    }

    /**
     * Get complaints for a specific student
     */
    public function getStudentComplaints($studentId)
    {
        $result=$this->complaintService->getStudentComplaints($studentId);
        if(!$result['success'])
        return $this->error($result['message'],$result);
        else
        return $this->success($result['message'],$result['data']);
    }
    public function getComplaints()
    {
        $result=$this->complaintService->getComplaints();
        if(!$result['success'])
        return $this->error($result['message'],$result);
        else
        return $this->success($result['message'],$result['data']);
    }
}
