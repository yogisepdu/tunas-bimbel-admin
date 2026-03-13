<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Question extends Model
{
    //
    protected $fillable = [
        'quiz_id',
        'question',
        'image',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer'
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    protected static function booted()
    {
        static::deleting(function ($question) {

            if ($question->image && Storage::disk('public')->exists($question->image)) {

                // hapus file gambar
                Storage::disk('public')->delete($question->image);

                // ambil folder dari path
                $folder = dirname($question->image);

                // cek apakah folder masih ada file
                $files = Storage::disk('public')->files($folder);

                // jika kosong maka hapus folder
                if (empty($files)) {
                    Storage::disk('public')->deleteDirectory($folder);
                }
            }

        });
    }
}
