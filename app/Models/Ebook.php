<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    //
    protected $fillable = [
        'chapter_id',
        'title',
        'subtitle',
        'video_url'
    ];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
