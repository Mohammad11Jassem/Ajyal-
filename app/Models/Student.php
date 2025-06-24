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
        'access_code'
    ];
    protected $hidden=[
        'pivot'
    ];
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
}
