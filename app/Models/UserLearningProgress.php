<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLearningProgress extends Model
{
    //
    protected $fillable = [
        'user_id',
        'chapter_id',
        'video_id',
        'pdf_id',
        'quiz_id',
        'status',
        'progress_percent'
    ];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
