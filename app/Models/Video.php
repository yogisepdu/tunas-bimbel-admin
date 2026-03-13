<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    //
    protected $fillable = [
        'chapter_id',
        'title',
        'subtitle',
        'youtube_id'
    ];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
