<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    //
    protected $fillable = [
        'category',
        'title',
        'description',
        'is_new',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime', // 🔥 ini kuncinya
    ];
}
