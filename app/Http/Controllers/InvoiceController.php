<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invoice\AddInvoicesRequest;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
