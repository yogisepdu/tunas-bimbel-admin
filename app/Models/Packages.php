<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Packages extends Model
{
    protected $table = 'packages';

    protected $fillable = [
        'name',
        'description',
        'price',
        'image'
    ];

    // 🔥 relasi ke kelas (pivot)
    public function classes()
    {
        return $this->belongsToMany(
            ClassRoom::class,
            'package_classes',
            'package_id',
            'class_id'
        );
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'user_packages',
            'package_id',
            'user_id'
        );
    }

    // 🔥 optional (kalau mau akses pivot langsung)
    public function packageClasses()
    {
        return $this->hasMany(PackagesClasses::class, 'package_id');
    }

    protected static function booted()
    {
        static::deleting(function ($package) {

            // 🔥 hapus gambar
            if ($package->image && Storage::disk('public')->exists($package->image)) {
                Storage::disk('public')->delete($package->image);
            }

        });
    }
}