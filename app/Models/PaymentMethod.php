<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'type',
        'provider',
        'account_name',
        'account_number',
        'qr_image',
        'instructions',
        'mode',
        'gateway_provider',
        'requires_proof',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'requires_proof' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Semua transaksi yang menggunakan metode pembayaran ini.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(
            Transaction::class,
            'payment_method_id'
        );
    }

    /**
     * Hanya metode pembayaran aktif.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Urutan tampilan metode pembayaran.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    /**
     * Apakah pembayaran manual.
     */
    public function isManual(): bool
    {
        return $this->mode === 'manual';
    }

    /**
     * Apakah menggunakan payment gateway.
     */
    public function isGateway(): bool
    {
        return $this->mode === 'gateway';
    }
}
