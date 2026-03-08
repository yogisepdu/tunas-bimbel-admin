<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriPdf extends Model
{
    //
    protected $fillable = [
        'chapter_id',
        'title',
        'pdf_url'
    ];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
