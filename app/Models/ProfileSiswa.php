<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileSiswa extends Model
{
    //
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'gender',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'postal_code',
        'avatar',
    ];

    // 🔗 RELASI KE USER
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
