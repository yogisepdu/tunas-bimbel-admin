<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    //
    protected $fillable = [
        'class_id',
        'title',
        'duration'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function results()
    {
        return $this->hasMany(QuizResult::class);
    }

    protected static function booted()
    {
        static::deleting(function ($quiz) {

            foreach ($quiz->questions as $question) {
                $question->delete();
            }

            $quiz->results()->delete();

        });
    }
}
