<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackagesClasses extends Model
{
    protected $table = 'package_classes';

    protected $fillable = [
        'package_id',
        'class_id'
    ];

    public function package()
    {
        return $this->belongsTo(Packages::class, 'package_id');
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }
}