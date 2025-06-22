<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{

    protected $fillable=[
        'title','body','advertisable_type','advertisable_id',
    ];
    public function advertisable()
    {
        return $this->morphTo();
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}

