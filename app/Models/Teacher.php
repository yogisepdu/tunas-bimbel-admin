<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    //
    protected $fillable = [
        'user_id',
        'phone',
        'company',
        'specialization',
        'experience_years',
        'bio'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
