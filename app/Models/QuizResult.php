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
        'empty'
    ];
}
