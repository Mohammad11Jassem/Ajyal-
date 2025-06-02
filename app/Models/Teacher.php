<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    //
     protected $fillable=[
        'user_id','bio','avatar','date_of_contract','email','name'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
