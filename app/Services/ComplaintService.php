<?php

namespace App\Services;

use App\Models\Complaint;

class ComplaintService
{
    public function stroe(array $data){
        try {
            $complaint = Complaint::create([
                'content' => $data['content'],
                'student_id' => $data['student_id'],
            ]);


            return [
                'success' => true,
                'message' => 'تم إضافة الشكوى بنجاح',
                'data' => $complaint->load(['student'])
            ];

        } catch (\Exception $e) {
            return[
                'success' => false,
                'message' => 'فشل إضافة الشكوى',
                'error' => $e->getMessage()
            ];
        }
    }
    public function show($id){
        try {
            $complaint = Complaint::with(['student'])
                ->findOrFail($id);

            return [
                'success' => true,
                'message'=>'تفاصيل الشكوى',
                'data' => $complaint
            ];

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return [
                'success' => false,
                'message'=>'فشل جلب تفاصيل الشكوى',
                'message' => 'Complaint not found'
            ];
        }
    }

    public function getStudentComplaints($studentId)
    {
        try {
            $complaints = Complaint::
                where('student_id', $studentId)
                ->orderBy('created_at', 'desc')
                ->get();

            return[
                'success' => true,
                'message' => 'كل شكاوي هذا الطالب',
                'data' => $complaints
            ];

        } catch (\Exception $e) {
            return[
                'success' => false,
                'message' => 'Error retrieving student complaints'
            ];
        }
    }

    public function getComplaints()
    {
        try {
            $complaints = Complaint::
                with('student')->
                orderBy('created_at', 'desc')
                ->get();

            return[
                'success' => true,
                'message' => 'كل الشكاوي ',
                'data' => $complaints
            ];

        } catch (\Exception $e) {
            return[
                'success' => false,
                'message' => 'Error retrieving complaints'
            ];
        }
    }

}
