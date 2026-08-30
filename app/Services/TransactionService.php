<?php

namespace App\Services;

use App\Models\Packages;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\PaymentVerifiedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TransactionService
{
    /*
    |--------------------------------------------------------------------------
    | Create Transaction
    |--------------------------------------------------------------------------
    */

    public function create(
        User $user,
        Packages $package,
        PaymentMethod $paymentMethod,
        array $customer,
        string $billing
    ): Transaction {
        /*
        |--------------------------------------------------------------------------
        | Pastikan akun Student
        |--------------------------------------------------------------------------
        */

        if ($user->role !== 'student') {
            throw ValidationException::withMessages([
                'customer_email' =>
                'Paket hanya dapat dibeli untuk akun student.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan metode pembayaran aktif
        |--------------------------------------------------------------------------
        */

        if (! $paymentMethod->is_active) {
            throw ValidationException::withMessages([
                'payment_method_id' =>
                'Metode pembayaran tidak tersedia.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi billing
        |--------------------------------------------------------------------------
        */

        if (! in_array(
            $billing,
            [
                Transaction::BILLING_MONTHLY,
                Transaction::BILLING_YEARLY,
            ],
            true
        )) {
            throw ValidationException::withMessages([
                'billing' =>
                'Periode paket tidak valid.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Perhitungan Harga
        |--------------------------------------------------------------------------
        */

        $basePrice = (float) $package->price;

        if (
            $billing ===
            Transaction::BILLING_YEARLY
        ) {
            /*
             * Harga normal:
             * 12 x harga bulanan
             */
            $subtotal = $basePrice * 12;

            /*
             * Paket tahunan pada sistem Tunas Bimbel:
             * bayar 10 bulan.
             */
            $discount = $basePrice * 2;

            $total = $basePrice * 10;

            $durationMonths = 12;
        } else {
            $subtotal = $basePrice;
            $discount = 0;
            $total = $basePrice;

            $durationMonths = 1;
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan Transaksi
        |--------------------------------------------------------------------------
        */

        return DB::transaction(
            function () use (
                $user,
                $package,
                $paymentMethod,
                $customer,
                $billing,
                $basePrice,
                $subtotal,
                $discount,
                $total,
                $durationMonths
            ) {
                return Transaction::create([
                    /*
                     * Invoice
                     */
                    'invoice_no' =>
                    $this->generateInvoiceNumber(),

                    'public_token' =>
                    (string) Str::uuid(),

                    /*
                     * Relation
                     */
                    'user_id' =>
                    $user->id,

                    'package_id' =>
                    $package->id,

                    'payment_method_id' =>
                    $paymentMethod->id,

                    /*
                     * Package Snapshot
                     */
                    'package_name' =>
                    $package->name,

                    'package_base_price' =>
                    $basePrice,

                    /*
                     * Billing
                     */
                    'billing' =>
                    $billing,

                    'duration_months' =>
                    $durationMonths,

                    /*
                     * Harga
                     */
                    'subtotal' =>
                    $subtotal,

                    'discount' =>
                    $discount,

                    'total' =>
                    $total,

                    /*
                     * Customer
                     */
                    'customer_name' =>
                    $customer['name'],

                    'customer_email' =>
                    $customer['email'],

                    'customer_phone' =>
                    $customer['phone'],

                    /*
                     * Payment Method Snapshot
                     */
                    'payment_method_name' =>
                    $paymentMethod->name,

                    'payment_provider' =>
                    $paymentMethod->provider,

                    'payment_account_name' =>
                    $paymentMethod->account_name,

                    'payment_account_number' =>
                    $paymentMethod->account_number,

                    'payment_mode' =>
                    $paymentMethod->mode,

                    'gateway_provider' =>
                    $paymentMethod->gateway_provider,

                    /*
                     * Status
                     */
                    'status' =>
                    Transaction::STATUS_PENDING_PAYMENT,

                    /*
                     * Invoice berlaku 24 jam
                     */
                    'expires_at' =>
                    now()->addHours(24),
                ]);
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Verify Payment + Activate Package
    |--------------------------------------------------------------------------
    */

    public function verifyAndActivate(
        Transaction $transaction,
        User $reviewer
    ): Transaction {
        $this->ensureReviewerAllowed(
            $reviewer
        );

        $verifiedTransaction = DB::transaction(
            function () use (
                $transaction,
                $reviewer
            ) {
                /*
                 * Lock transaction supaya admin tidak bisa
                 * memverifikasi transaksi sama dua kali.
                 */
                $lockedTransaction =
                    Transaction::query()
                    ->lockForUpdate()
                    ->findOrFail(
                        $transaction->id
                    );

                if (
                    $lockedTransaction->status !==
                    Transaction::STATUS_WAITING_VERIFICATION
                ) {
                    throw ValidationException::withMessages([
                        'transaction' =>
                        'Transaksi ini tidak sedang menunggu verifikasi.',
                    ]);
                }

                if (
                    ! $lockedTransaction->user_id
                    || ! $lockedTransaction->package_id
                ) {
                    throw ValidationException::withMessages([
                        'transaction' =>
                        'User atau paket pada transaksi tidak ditemukan.',
                    ]);
                }

                $now = now();

                /*
                |--------------------------------------------------------------------------
                | Cari entitlement lama
                |--------------------------------------------------------------------------
                */

                $existingPackage = DB::table(
                    'user_packages'
                )
                    ->where(
                        'user_id',
                        $lockedTransaction->user_id
                    )
                    ->where(
                        'package_id',
                        $lockedTransaction->package_id
                    )
                    ->lockForUpdate()
                    ->first();

                /*
                |--------------------------------------------------------------------------
                | Jika student sudah pernah memiliki paket
                |--------------------------------------------------------------------------
                */

                if ($existingPackage) {
                    /*
                     * Legacy package:
                     *
                     * data lama sebelum sistem expiry dibuat.
                     *
                     * transaction_id NULL
                     * expires_at NULL
                     *
                     * Jangan menurunkan unlimited access
                     * menjadi paket berjangka.
                     */
                    $legacyUnlimited =
                        $existingPackage->transaction_id === null
                        && $existingPackage->expires_at === null;

                    if ($legacyUnlimited) {
                        $newExpiresAt = null;
                    } else {
                        /*
                         * Jika masih aktif:
                         * perpanjang dari expires_at lama.
                         *
                         * Jika sudah expired:
                         * mulai dari sekarang.
                         */
                        if (
                            $existingPackage->expires_at
                            && Carbon::parse(
                                $existingPackage->expires_at
                            )->isFuture()
                        ) {
                            $startFrom = Carbon::parse(
                                $existingPackage->expires_at
                            );
                        } else {
                            $startFrom = $now->copy();
                        }

                        $newExpiresAt =
                            $startFrom->copy()
                            ->addMonthsNoOverflow(
                                $lockedTransaction
                                    ->duration_months
                            );
                    }

                    DB::table('user_packages')
                        ->where(
                            'id',
                            $existingPackage->id
                        )
                        ->update([
                            'transaction_id' =>
                            $lockedTransaction->id,

                            'status' =>
                            'active',

                            'activated_at' =>
                            $existingPackage->activated_at
                                ?? $now,

                            'expires_at' =>
                            $newExpiresAt,

                            'updated_at' =>
                            $now,
                        ]);
                } else {
                    /*
                    |--------------------------------------------------------------------------
                    | Paket baru
                    |--------------------------------------------------------------------------
                    */

                    $expiresAt = $now
                        ->copy()
                        ->addMonthsNoOverflow(
                            $lockedTransaction
                                ->duration_months
                        );

                    DB::table('user_packages')
                        ->insert([
                            'user_id' =>
                            $lockedTransaction->user_id,

                            'package_id' =>
                            $lockedTransaction->package_id,

                            'transaction_id' =>
                            $lockedTransaction->id,

                            'status' =>
                            'active',

                            'activated_at' =>
                            $now,

                            'expires_at' =>
                            $expiresAt,

                            'created_at' =>
                            $now,

                            'updated_at' =>
                            $now,
                        ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Update transaksi
                |--------------------------------------------------------------------------
                */

                $lockedTransaction->update([
                    'status' =>
                    Transaction::STATUS_PAID,

                    'reviewed_by' =>
                    $reviewer->id,

                    'reviewed_at' =>
                    $now,

                    'paid_at' =>
                    $now,

                    'rejection_reason' =>
                    null,
                ]);

                return $lockedTransaction
                    ->fresh([
                        'user',
                        'package',
                        'paymentMethod',
                        'reviewer',
                    ]);
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Kirim email SETELAH transaksi database commit
        |--------------------------------------------------------------------------
        */

        DB::afterCommit(
            function () use (
                $verifiedTransaction
            ) {
                try {
                    if ($verifiedTransaction->user) {
                        $verifiedTransaction
                            ->user
                            ->notify(
                                new PaymentVerifiedNotification(
                                    $verifiedTransaction
                                )
                            );
                    }
                } catch (\Throwable $e) {
                    /*
                     * Gagal email tidak boleh membatalkan
                     * pembayaran yang sudah berhasil.
                     */
                    Log::warning(
                        'Gagal mengirim notifikasi pembayaran.',
                        [
                            'transaction_id' =>
                            $verifiedTransaction->id,

                            'message' =>
                            $e->getMessage(),
                        ]
                    );
                }
            }
        );

        return $verifiedTransaction;
    }

    /*
    |--------------------------------------------------------------------------
    | Reject Payment
    |--------------------------------------------------------------------------
    */

    public function reject(
        Transaction $transaction,
        User $reviewer,
        string $reason
    ): Transaction {
        $this->ensureReviewerAllowed(
            $reviewer
        );

        $reason = trim($reason);

        if (mb_strlen($reason) < 5) {
            throw ValidationException::withMessages([
                'rejection_reason' =>
                'Alasan penolakan minimal 5 karakter.',
            ]);
        }

        return DB::transaction(
            function () use (
                $transaction,
                $reviewer,
                $reason
            ) {
                $lockedTransaction =
                    Transaction::query()
                    ->lockForUpdate()
                    ->findOrFail(
                        $transaction->id
                    );

                if (
                    $lockedTransaction->status !==
                    Transaction::STATUS_WAITING_VERIFICATION
                ) {
                    throw ValidationException::withMessages([
                        'transaction' =>
                        'Transaksi ini tidak sedang menunggu verifikasi.',
                    ]);
                }

                $lockedTransaction->update([
                    'status' =>
                    Transaction::STATUS_REJECTED,

                    'reviewed_by' =>
                    $reviewer->id,

                    'reviewed_at' =>
                    now(),

                    'rejection_reason' =>
                    $reason,
                ]);

                return $lockedTransaction->fresh();
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Invoice Number
    |--------------------------------------------------------------------------
    */

    private function generateInvoiceNumber(): string
    {
        do {
            $invoice =
                'INV-TB-'
                . now()->format('Ymd')
                . '-'
                . Str::upper(
                    Str::random(6)
                );
        } while (
            Transaction::query()
            ->where(
                'invoice_no',
                $invoice
            )
            ->exists()
        );

        return $invoice;
    }

    /*
    |--------------------------------------------------------------------------
    | Reviewer
    |--------------------------------------------------------------------------
    */

    private function ensureReviewerAllowed(
        User $reviewer
    ): void {
        if (
            ! in_array(
                $reviewer->role,
                [
                    'administrator',
                    'admin',
                ],
                true
            )
        ) {
            throw ValidationException::withMessages([
                'reviewer' =>
                'Anda tidak memiliki hak untuk memverifikasi transaksi.',
            ]);
        }
    }
}
