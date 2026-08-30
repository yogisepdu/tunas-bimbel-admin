<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailCustom;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Profile Relations
    |--------------------------------------------------------------------------
    */

    public function student(): HasOne
    {
        return $this->hasOne(
            Student::class
        );
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(
            Teacher::class
        );
    }

    public function profile(): HasOne
    {
        return $this->hasOne(
            ProfileSiswa::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Packages
    |--------------------------------------------------------------------------
    */

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(
            Packages::class,
            'user_packages',
            'user_id',
            'package_id'
        )
            ->withPivot([
                'transaction_id',
                'status',
                'activated_at',
                'expires_at',
            ])
            ->withTimestamps();
    }

    /**
     * Paket yang masih dapat digunakan student.
     *
     * expires_at NULL diperlakukan sebagai legacy/unlimited.
     */
    public function activePackages(): BelongsToMany
    {
        return $this->belongsToMany(
            Packages::class,
            'user_packages',
            'user_id',
            'package_id'
        )
            ->withPivot([
                'transaction_id',
                'status',
                'activated_at',
                'expires_at',
            ])
            ->withTimestamps()
            ->wherePivot('status', 'active')
            ->where(function ($query) {
                $query
                    ->whereNull(
                        'user_packages.expires_at'
                    )
                    ->orWhere(
                        'user_packages.expires_at',
                        '>',
                        now()
                    );
            });
    }

    public function hasActivePackage(
        int $packageId
    ): bool {
        return $this
            ->activePackages()
            ->where(
                'packages.id',
                $packageId
            )
            ->exists();
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
            'user_id'
        );
    }

    /**
     * Transaksi yang diverifikasi oleh admin ini.
     */
    public function reviewedTransactions(): HasMany
    {
        return $this->hasMany(
            Transaction::class,
            'reviewed_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Email Verification
    |--------------------------------------------------------------------------
    */

    public function sendEmailVerificationNotification()
    {
        $this->notify(
            new VerifyEmailCustom()
        );
    }

    public function sendPasswordResetNotification(
        $token
    ) {
        $this->notify(
            new ResetPasswordNotification(
                $token
            )
        );
    }
}
