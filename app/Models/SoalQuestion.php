<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoalQuestion extends Model
{
    protected $fillable = [
        'soal_set_id',
        'question',
        'correct_answer'
    ];

    public function options()
    {
        return $this->hasMany(SoalOption::class, 'soal_question_id', 'id');
    }

    public function set()
    {
        return $this->belongsTo(SoalSet::class, 'soal_set_id', 'id');
    }
}