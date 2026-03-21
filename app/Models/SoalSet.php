<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoalSet extends Model
{
    protected $fillable = [
        'soal_section_id',
        'title',
        'total_questions',
        'duration',
        'points',
        'badge'
    ];

    public function section()
    {
        return $this->belongsTo(SoalSection::class, 'soal_section_id', 'id');
    }

    public function questions()
    {
        return $this->hasMany(SoalQuestion::class, 'soal_set_id', 'id');
    }
}
