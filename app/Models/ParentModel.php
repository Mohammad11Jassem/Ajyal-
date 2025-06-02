<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    //
     protected $fillable=[
        'user_id','phone_number','name',
    ];

    public function user()
    {
         return $this->belongsTo(User::class);
    }

    public function students()
    {
         return $this->belongsToMany(Student::class, 'parent_student', 'parent_id', 'student_id');
    }
}
