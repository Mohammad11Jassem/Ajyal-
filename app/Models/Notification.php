<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'title',
        'notifiable_type',
        'notifiable_id',
        'body',
    ];
}
