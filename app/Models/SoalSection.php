<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoalSection extends Model
{
    protected $fillable = ['title'];

    public function sets()
    {
        return $this->hasMany(SoalSet::class, 'soal_section_id', 'id');
    }
}