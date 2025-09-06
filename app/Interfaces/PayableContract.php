<?php

namespace App\Interfaces;

interface PayableContract
{
    public function getTitle(): string;      // اسم العنصر المعروض في الدفع
    public function getAmount(): int;        // قيمة المبلغ (بالسنتات)
    public function getMetadata(): array;    // بيانات إضافية تحفظ مع session
}
