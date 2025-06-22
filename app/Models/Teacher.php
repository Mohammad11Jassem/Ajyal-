<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    //
     protected $fillable=[
        'user_id','bio','avatar','date_of_contract','email','name','phone_number'
    ];

    protected $hidden=['avatar'];
    // protected $with=['image'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function subjects()
    {
        return $this->belongsToMany(Subject::class,'teacher_subjects');
    }

    public function advertisements()
    {
        return $this->morphMany(Advertisement::class, 'advertisable');
    }

}
