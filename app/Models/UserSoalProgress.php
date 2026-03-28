<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSoalProgress extends Model
{
    //
    protected $fillable = [
        'user_id',
        'soal_set_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soalSet()
    {
        return $this->belongsTo(SoalSet::class);
    }
}
