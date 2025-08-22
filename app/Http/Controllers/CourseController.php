<?php

namespace App\Http\Controllers;

use App\Http\Requests\Course\AddScheduleImageRequest;
use App\Http\Requests\Course\GetAllStudentWithClassRequest;
use App\Http\Requests\Course\RegisterStudentRequest;
use App\Http\Requests\Course\SortStudentRequest;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\File\AddFileRequest;
use App\Models\Course;
use App\Models\Curriculum;
use App\Services\CourseService;
use App\Traits\HttpResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    use HttpResponse;

    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }
    public function store2(StoreCourseRequest $storeCourseRequest){
        $validated=$storeCourseRequest->validated();
        return DB::transaction(function() use($validated){
             $course = Course::create([
                'name' => $validated['name'],
                'cost' => $validated['cost'],
                'type' => $validated['type'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'code' => "COU123",
                'capacity' => $validated['capacity'],
            ]);
            $course->classrooms()->attach($validated['classrooms']);

            foreach ($validated['subjects'] as $subject) {
                $curriculum = Curriculum::create([
                    'course_id' => $course->id,
                    'subject_id' => $subject['subject_id'],
                ]);

                $curriculum->teachers()->attach($subject['teachers']);
            }
             return response()->json([
                'message' => 'Course created successfully.',
                'course' => $course,
            ], 201);
        });
    }


    public function store(StoreCourseRequest $request)
    {
        $course = $this->courseService->store($request->validated());
        $courseDetails=$this->courseService->show($course['data']['id']);
        if($courseDetails['succuss']){
            return $this->success('تم إضافة الكورس بنجاح',$courseDetails['data']);
        }
        // return $this->badRequest($courseDetails['message']);
        return $this->badRequest('فشل انشاء الكورس');
    }

    public function show($id)
    {

        $courseDetails=$this->courseService->show($id);
        if($courseDetails['succuss']){
            return $this->success('تفاصيل الكورس',$courseDetails['data']);
        }
        return $this->badRequest($courseDetails['message']);

    }

    public function destroy($id)
    {
        $deleted = $this->courseService->destroy($id);

        if($deleted){
            return $this->success('تم حذف الكورس بنجاح');
        }
        return $this->badRequest('فشل حذف الكورس');
    }

    public function storeFile(AddFileRequest $request)
    {
        $data=$request->validated();
        $file = $this->courseService->storeFile($data['curriculum_id'], $data);

        return response()->json(['message' => 'File uploaded successfully', 'file' => $file]);
    }

    public function AllCourses(){
        $courses=$this->courseService->AllCourses();
        return $this->success('الكورسات',$courses);
    }

    public function getCurrentAndIncomingCourses()
    {
        $courses = $this->courseService->getCurrentAndIncomingCourses();
        return $this->success('الكورسات الحالية والمستقبلية',$courses);
    }
    public function AllfileForCourse($courseId){
        try {
            $files = $this->courseService->AllfileForCourse($courseId);
            return $this->success('الملفات', $files);

        } catch (ModelNotFoundException $e) {
            return $this->notFound('الكورس غير موجود');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function classRoomsCourse($courseId){
        $data= $this->courseService->classRoomsCourse($courseId);
        return $this->success('قاعات الكورس',$data);
    }

    public function registerAtCourse(RegisterStudentRequest $registerStudentRequest){

        $result = $this->courseService->registerStudent($registerStudentRequest->validated());

        if(!$result['success'])
        return $this->error($result['message'], 500);

        return $this->success($result['message']);
    }
    public function AllStudent($course_id)
    {
        $result = $this->courseService->AllStudentAtCourse($course_id);

        if(!$result['success'])
        return $this->error($result['error'], 500);

        return $this->success($result['message'],$result['data']);

    }


    public function curriculumsCourse($courseId){
        $curricula=$this->courseService->curriculumsCourse($courseId);
        return $this->success('مواد الكورس',$curricula);
    }
    public function sortStudent(SortStudentRequest $sortStudentRequest){
        $result=$this->courseService->sortStudentAtClassRoom($sortStudentRequest->validated());
        // if(!$result['success'])
        // return $this->error($result['error'], 500);
        if(!$result['success']){
            return $this->badRequest($result['message']);
        }
        return $this->success($result['message']);

    }

    public function AllStudentAtClass(GetAllStudentWithClassRequest $getAllStudentWithClassRequest)
    {
        $result = $this->courseService->AllStudentAtCourseAtClass($getAllStudentWithClassRequest->validated());

        if(!$result['success'])
        return $this->error($result['error'], 500);

        return $this->success($result['message'],$result['data']);
    }

    public function getFiles($curriculumId)
    {
        try {
            $curriculum=$this->courseService->getFiles($curriculumId);
            return $this->success('ملفات المادة', $curriculum);

        } catch (ModelNotFoundException $e) {
            return $this->notFound(' المادة غير موجودة');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function showFile($fileId)
    {
        try {
            $file=$this->courseService->showFile($fileId);
            return $this->success('الملف', $file);

        } catch (ModelNotFoundException $e) {
            return $this->notFound(' الملف غير موجود');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function studentCourses(){
        $courses=$this->courseService->studentCourses();
        return $this->success('كورسات هذا الطالب',$courses);
    }

    public function studentCoursesWithDetails(){
        $courses=$this->courseService->studentCoursesWithDetails();
        return $this->success('تفاصيل كورسات هذا الطالب',$courses);
    }
    public function addScheduleToClassroom(AddScheduleImageRequest $addScheduleImageRequest){
        $result=$this->courseService->addScheduleToClassroom($addScheduleImageRequest->validated());
        return $this->success('تم إضافة البرنامج إلى الشعبة الصفية  ',$result['data']);
    }

}
