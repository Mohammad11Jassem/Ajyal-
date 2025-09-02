<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    protected $fillable = ['curriculum_id'];

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }
}
