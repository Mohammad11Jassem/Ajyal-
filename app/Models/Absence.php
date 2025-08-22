<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    protected $fillable=[
        'absence_date_id',
        'registration_id',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
