<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoalSection extends Model
{
    protected $fillable = ['title', 'class_id'];

    // 🔥 RELASI KE CLASS
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function sets()
    {
        return $this->hasMany(SoalSet::class, 'soal_section_id', 'id');
    }
}