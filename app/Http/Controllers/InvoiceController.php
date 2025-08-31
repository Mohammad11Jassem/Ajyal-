<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invoice\AddInvoicesRequest;
use App\Http\Requests\Invoice\PayInvoicesRequest;
use App\Models\Invoice;
use App\Services\InvoiceService;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    use HttpResponse;
    protected InvoiceService $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }
    public function store(AddInvoicesRequest $addInvoicesRequest)
    {
        $result=$this->invoiceService->addInvoice($addInvoicesRequest->validated());
        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error']
            ], 422);
        }

        return response()->json([
            'message' => $result['message'],
            // 'data' => $result['data']
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show($courseID)
    {
        $result=$this->invoiceService->allInvoices($courseID);
        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'error' => $result['error']
            ], 422);
        }

        return response()->json([
            'message' => $result['message'],
            'data' => $result['data']
        ], 201);
    }


    public function payInvoices(PayInvoicesRequest $payInvoicesRequest){
        // return $this->success( 'test','$result');
        $result=$this->invoiceService->payInvoices($payInvoicesRequest->validated());
        if (!$result['success']) {
            return $this->error( $result['message'],$result);
        }
        return $this->success( $result['message'],$result);
    }


}
