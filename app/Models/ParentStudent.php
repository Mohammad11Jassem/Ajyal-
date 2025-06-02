<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentStudent extends Model
{
    protected $fillable=[
        'parent_id','student_id'
    ];

    public function parent()
    {
        return $this->belongsTo(ParentModel::class,'parent_id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class,'student_id');
    }
}
