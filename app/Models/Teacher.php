<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    //
     protected $fillable=[
        'user_id','bio','avatar','date_of_contract','email','name',
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

}
