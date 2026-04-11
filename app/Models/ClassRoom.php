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

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'class_id');
    }

    public function packages()
    {
        return $this->belongsToMany(
            Packages::class,
            'package_classes',
            'class_id',
            'package_id'
        );
    }

    protected static function booted()
    {
        static::deleting(function ($class) {

            // hapus quiz langsung dari class
            foreach ($class->quizzes as $quiz) {
                $quiz->questions()->delete();
                $quiz->results()->delete();
                $quiz->delete();
            }

            // hapus chapter dan materinya
            foreach ($class->chapters as $chapter) {

                $chapter->ebooks()->delete();
                $chapter->videos()->delete();
                $chapter->materiPdf()->delete();

                $chapter->delete();
            }

        });
    }
}
