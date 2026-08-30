<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Packages extends Model
{
    protected $table = 'packages';

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Classes
    |--------------------------------------------------------------------------
    */

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(
            ClassRoom::class,
            'package_classes',
            'package_id',
            'class_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_packages',
            'package_id',
            'user_id'
        )
            ->withPivot([
                'transaction_id',
                'status',
                'activated_at',
                'expires_at',
            ])
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Transactions
    |--------------------------------------------------------------------------
    */

    public function transactions(): HasMany
    {
        return $this->hasMany(
            Transaction::class,
            'package_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Package Classes Pivot
    |--------------------------------------------------------------------------
    */

    public function packageClasses(): HasMany
    {
        return $this->hasMany(
            PackagesClasses::class,
            'package_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Image
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::deleting(function ($package) {

            if (
                $package->image
                && Storage::disk('public')
                ->exists($package->image)
            ) {
                Storage::disk('public')
                    ->delete($package->image);
            }
        });
    }
}
