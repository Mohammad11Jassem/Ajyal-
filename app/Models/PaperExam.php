<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaperExam extends Model
{
    protected $fillable = [
        'curriculum_id',
        'title',
        'description',
        'exam_date',
        'max_degree',
        'file_path',
    ];

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class,'paper_exam_students')
                    // ->using(PaperExamStudent::class)
                    ->withPivot('mark');
                    // ->withTimestamps();
    }
}
