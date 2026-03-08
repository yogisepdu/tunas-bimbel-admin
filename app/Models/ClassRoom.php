<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    //
    protected $table = 'classes';

    protected $fillable = [
        'name',
        'description'
    ];

    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'class_id');
    }

    protected static function booted()
    {
        static::deleting(function ($class) {

            foreach ($class->chapters as $chapter) {

                $chapter->ebooks()->delete();
                $chapter->videos()->delete();
                $chapter->materiPdf()->delete();

                foreach ($chapter->quizzes as $quiz) {
                    $quiz->questions()->delete();
                    $quiz->results()->delete();
                    $quiz->delete();
                }

                $chapter->delete();
            }
        });
    }
}
