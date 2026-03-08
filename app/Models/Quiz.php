<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    //
    protected $fillable = [
        'chapter_id',
        'title',
        'duration'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function results()
    {
        return $this->hasMany(QuizResult::class);
    }
}
