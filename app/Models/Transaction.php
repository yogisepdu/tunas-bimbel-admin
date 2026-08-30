<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Status transaksi
    |--------------------------------------------------------------------------
    */

    public const STATUS_PENDING_PAYMENT = 'pending_payment';

    public const STATUS_WAITING_VERIFICATION = 'waiting_verification';

    public const STATUS_PAID = 'paid';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_EXPIRED = 'expired';

    /*
    |--------------------------------------------------------------------------
    | Billing
    |--------------------------------------------------------------------------
    */

    public const BILLING_MONTHLY = 'monthly';

    public const BILLING_YEARLY = 'yearly';

    protected $fillable = [
        'invoice_no',
        'public_token',

        'user_id',
        'package_id',
        'payment_method_id',

        'package_name',
        'package_base_price',

        'billing',
        'duration_months',

        'subtotal',
        'discount',
        'total',

        'customer_name',
        'customer_email',
        'customer_phone',

        'payment_method_name',
        'payment_provider',
        'payment_account_name',
        'payment_account_number',

        'payment_mode',

        'gateway_provider',
        'gateway_reference',
        'gateway_payload',

        'status',

        'proof_path',
        'proof_original_name',
        'proof_mime_type',
        'proof_uploaded_at',

        'reviewed_by',
        'reviewed_at',
        'rejection_reason',

        'paid_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'package_base_price' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'total' => 'decimal:2',

            'duration_months' => 'integer',

            'gateway_payload' => 'array',

            'proof_uploaded_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'paid_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(
            Packages::class,
            'package_id'
        );
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(
            PaymentMethod::class,
            'payment_method_id'
        );
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePendingPayment(
        Builder $query
    ): Builder {
        return $query->where(
            'status',
            self::STATUS_PENDING_PAYMENT
        );
    }

    public function scopeWaitingVerification(
        Builder $query
    ): Builder {
        return $query->where(
            'status',
            self::STATUS_WAITING_VERIFICATION
        );
    }

    public function scopePaid(
        Builder $query
    ): Builder {
        return $query->where(
            'status',
            self::STATUS_PAID
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helper status
    |--------------------------------------------------------------------------
    */

    public function isPendingPayment(): bool
    {
        return $this->status === self::STATUS_PENDING_PAYMENT;
    }

    public function isWaitingVerification(): bool
    {
        return $this->status === self::STATUS_WAITING_VERIFICATION;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isExpired(): bool
    {
        if ($this->status === self::STATUS_EXPIRED) {
            return true;
        }

        return $this->expires_at !== null
            && $this->expires_at->isPast()
            && ! $this->isPaid();
    }

    public function canUploadProof(): bool
    {
        if ($this->isExpired()) {
            return false;
        }

        return in_array(
            $this->status,
            [
                self::STATUS_PENDING_PAYMENT,
                self::STATUS_REJECTED,
            ],
            true
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Label
    |--------------------------------------------------------------------------
    */

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING_PAYMENT =>
            'Menunggu Pembayaran',

            self::STATUS_WAITING_VERIFICATION =>
            'Menunggu Verifikasi',

            self::STATUS_PAID =>
            'Lunas',

            self::STATUS_REJECTED =>
            'Pembayaran Ditolak',

            self::STATUS_CANCELLED =>
            'Dibatalkan',

            self::STATUS_EXPIRED =>
            'Kedaluwarsa',

            default =>
            'Tidak Diketahui',
        };
    }

    public function getBillingLabelAttribute(): string
    {
        return match ($this->billing) {
            self::BILLING_MONTHLY => 'Bulanan',
            self::BILLING_YEARLY => 'Tahunan',
            default => '-',
        };
    }
}
