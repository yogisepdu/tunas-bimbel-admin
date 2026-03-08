<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    //
    protected $fillable = [
        'class_id',
        'title',
        'description'
    ];

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function ebooks()
    {
        return $this->hasMany(Ebook::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function materiPdf()
    {
        return $this->hasMany(MateriPdf::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}
