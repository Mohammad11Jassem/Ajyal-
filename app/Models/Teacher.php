<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Teacher extends Model
{
    //
    protected $fillable=[
        'user_id','bio','date_of_contract','email','name','phone_number'
    ];
    protected $hidden=[
        'pivot'
    ];
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

    public function curriculums()
    {
        return $this->belongsToMany(Curriculum::class, 'curriculum_teachers');
    }

    public function issues(): MorphMany
    {
        return $this->morphMany(Issue::class, 'author');
    }

    public function replies(): MorphMany
    {
        return $this->morphMany(Reply::class, 'author');
    }
}
