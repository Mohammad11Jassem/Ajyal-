<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $fillable = ['community_id', 'author_id', 'author_type', 'body', 'is_fqa'];

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function author()
    {
        return $this->morphTo();
    }
    public function image()
    {
        return $this->morphOne(Image::class,'imageable');
    }
}
