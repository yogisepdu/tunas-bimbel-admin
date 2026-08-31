<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoalAttempt extends Model
{
    public const STATUS_ACTIVE =
    'active';

    public const STATUS_SUBMITTED =
    'submitted';

    public const STATUS_EXPIRED =
    'expired';

    protected $fillable = [
        'token',
        'user_id',
        'soal_set_id',
        'status',
        'started_at',
        'expires_at',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' =>
            'datetime',

            'expires_at' =>
            'datetime',

            'submitted_at' =>
            'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }

    public function soalSet()
    {
        return $this->belongsTo(
            SoalSet::class
        );
    }

    public function isExpired(): bool
    {
        if (
            $this->status ===
            self::STATUS_EXPIRED
        ) {
            return true;
        }

        if (! $this->expires_at) {
            return true;
        }

        return $this
            ->expires_at
            ->isPast();
    }
}
