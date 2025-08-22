<?php

namespace App\Http\Controllers;

use App\Http\Requests\Absence\StoreAbsenceRequest;
use App\Services\AbsenceService;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    use HttpResponse;
    protected $absenceService;

    public function __construct(AbsenceService $absenceService)
    {
        $this->absenceService = $absenceService;
    }

    public function store(StoreAbsenceRequest $storeAbsenceRequest)
    {
        $data=$storeAbsenceRequest->validated();
        $createdAbsences = $this->absenceService->store($data);

        return $this->success('تم تسجيل الغياب بنجاح',$createdAbsences);
    }

}
