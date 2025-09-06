<?php

namespace App\Payments;

use App\Interfaces\PayableContract;
use App\Models\Invoice;

class InvoicePayment implements PayableContract
{
    protected Invoice $invoice;
    protected int $studentId;
    protected int $userId;

    public function __construct(Invoice $invoice, int $studentId, int $userId)
    {
        $this->invoice  = $invoice;
        $this->studentId = $studentId;
        $this->userId = $userId;
    }

    public function getTitle(): string
    {
        return "فاتورة #{$this->invoice->id}";
    }

    public function getAmount(): int
    {
        return (int) $this->invoice->value;
    }

    public function getMetadata(): array
    {
        return [
            'invoice_id' => $this->invoice->id,
            'student_id' => $this->studentId,
            'user_id'    => $this->userId,
            'type'       => 'invoice',
        ];
    }
}
