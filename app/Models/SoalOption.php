<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoalOption extends Model
{
    protected $fillable = [
        'soal_question_id',
        'key',
        'text'
    ];

    public function question()
    {
        return $this->belongsTo(SoalQuestion::class, 'soal_question_id', 'id');
    }
}
