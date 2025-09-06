<?php

namespace App\Payments;

use App\Interfaces\PayableContract;
use App\Models\Course;

class CoursePayment implements PayableContract
{
    protected Course $course;
    protected int $studentId;
    protected int $userId;

    public function __construct(Course $course, int $studentId, int $userId)
    {
        $this->course   = $course;
        $this->studentId = $studentId;
        $this->userId    = $userId;
    }

    public function getTitle(): string
    {
        return "دفع رسوم كورس {$this->course->name}";
    }

    public function getAmount(): int
    {
        return (int) $this->course->cost;
    }

    public function getMetadata(): array
    {
        return [
            'course_id' => $this->course->id,
            'student_id' => $this->studentId,
            'user_id'    => $this->userId,
            'type'       => 'course',
        ];
    }
}
