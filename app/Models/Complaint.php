<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = ['content','student_id'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

}
