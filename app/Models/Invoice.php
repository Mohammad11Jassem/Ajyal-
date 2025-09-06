<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable=[
        'course_id',
        'value',
        'due_date',
    ];
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}
