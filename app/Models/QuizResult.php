<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    //
    protected $fillable = [
        'user_id',
        'quiz_id',
        'score',
        'correct',
        'wrong',
        'empty',
        'answers'
    ];

    // QuizResult.php
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
