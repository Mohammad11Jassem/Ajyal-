<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    //
     protected $fillable=[
        'user_id',
        'student_Id_number',
        'first_name',
        'last_name',
        'number_civial',
        'address',
        'mother_name',
        'father_name',
        // 'QR',
        // 'location',
        'access_code',
        'class_level',
        'birthdate',
    ];
    // protected $hidden=[
    //     'pivot'
    // ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // to AUTO_INCREMENT
     protected static function booted()
    {
        static::creating(function ($student) {
            $max = DB::table('students')->lockForUpdate()->max('student_Id_number') ?? 999;
            $student->student_Id_number = $max + 1;
        });
    }

    public function parents():BelongsToMany
    {
        return $this->belongsToMany(ParentModel::class, 'parent_student', 'student_id', 'parent_model_id');
    }
    public function courses():BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'registrations', 'student_id', 'course_id');
    }
        public function registration()
    {
        return $this->hasMany(Registration::class );
    }


    // public function files()
    // {
    //     return $this->hasManyThrough(
    //         ClassroomCourse::class,
    //         Course::class,
    //         'course_id',       // Foreign key on the Curriculum table
    //         'curriculum_id',   // Foreign key on the CurriculumFile table
    //         'id',              // Local key on the Course table
    //         'id'          // Local key on the Curriculum table
    //     );
    // }


    public function paperExams()
    {
        return $this->belongsToMany(PaperExam::class,'paper_exam_students')
                    // ->using(PaperExamStudent::class)
                    ->withPivot('mark');
                    // ->withTimestamps();
    }
    public function scopePaperExamForSubject($query,$id)
    {
        return $query->with('paperExams',function($q) use($id){
            $q->where('curriculum_id',$id)->orderBy('exam_date', 'asc');
        });
    }

    public function quizzes(): BelongsToMany
    {
        return $this->belongsToMany(Quiz::class, 'student_quizzes', 'student_id', 'quiz_id');
    }

    public function studentQuizzes()
    {
        return $this->hasMany(StudentQuiz::class);
    }
    public function scopeStudentQuizzesForSubject($query,$id)
    {
        return $query->with([
                'studentQuizzes' => function ($q) use ($id) {
                    $q->whereHas('quiz.assignment', function ($q2) use ($id) {
                        $q2->where('curriculum_id', $id);
                    })->orderBy('created_at','asc');
                }]);
        // return $query->with('studentQuizzes.quiz.assignment',function($q) use($id){
        //     $q->where('curriculum_id',$id);
        // });
    }



}
