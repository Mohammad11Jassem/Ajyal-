<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurriculumFile extends Model
{
    protected $fillable = ['curriculum_id', 'title', 'file_path'];

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }
}
