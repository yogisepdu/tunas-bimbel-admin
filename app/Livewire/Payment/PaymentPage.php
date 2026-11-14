<?php

namespace App\Livewire\Payment;

use App\Models\Transaction;
use App\Notifications\PaymentProofSubmittedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class PaymentPage extends Component
{
    use WithFileUploads;

    /**
     * =========================================================
     * PROPERTIES
     * =========================================================
     */

    public Transaction $transaction;

    public $proof = null;


    /**
     * =========================================================
     * MOUNT
     * =========================================================
     */
    public function mount(string $token): void
    {
        $this->transaction = Transaction::query()
            ->with([
                'package',
                'paymentMethod',
            ])
            ->where('public_token', $token)
            ->firstOrFail();

        $this->synchronizeExpiry();
    }


    /**
     * =========================================================
     * VALIDATION RULES
     * =========================================================
     */
    protected function rules(): array
    {
        return [
            'proof' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],
        ];
    }


    /**
     * =========================================================
     * VALIDATION MESSAGES
     * =========================================================
     */
    protected function messages(): array
    {
        return [
            'proof.required' =>
            'Silakan pilih bukti pembayaran terlebih dahulu.',

            'proof.file' =>
            'Bukti pembayaran harus berupa file yang valid.',

            'proof.mimes' =>
            'Bukti pembayaran harus berformat JPG, JPEG, PNG, atau PDF.',

            'proof.max' =>
            'Ukuran bukti pembayaran maksimal 5 MB.',
        ];
    }


    /**
     * =========================================================
     * UPLOAD BUKTI PEMBAYARAN
     * =========================================================
     */
    public function uploadProof(): void
    {
        /*
         * Pastikan status transaksi terbaru.
         */
        $this->synchronizeExpiry();

        /*
         * Validasi file.
         */
        $this->validate();

        /*
         * Pastikan transaksi masih dapat menerima pembayaran.
         */
        if (! $this->canUploadProof()) {
            throw ValidationException::withMessages([
                'proof' =>
                'Bukti pembayaran tidak dapat dikirim untuk status transaksi saat ini.',
            ]);
        }

        /*
         * Pastikan metode pembayaran memang membutuhkan bukti.
         */
        if (! $this->requiresProof()) {
            throw ValidationException::withMessages([
                'proof' =>
                'Metode pembayaran ini tidak mewajibkan upload bukti.',
            ]);
        }

        /*
         * =====================================================
         * AMBIL INFORMASI FILE
         * =====================================================
         *
         * Data ini diambil sebelum transaksi database.
         */
        $originalName = $this->proof->getClientOriginalName();

        $mimeType = $this->proof->getMimeType();

        $extension = strtolower(
            (string) $this->proof->getClientOriginalExtension()
        );

        /*
         * Jika extension kosong, tentukan berdasarkan MIME.
         */
        if ($extension === '') {
            $extension = match ($mimeType) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'application/pdf' => 'pdf',
                default => 'bin',
            };
        }


        /*
         * =====================================================
         * DIREKTORI STORAGE
         * =====================================================
         */
        $directory = sprintf(
            'payment-proofs/%s/%s',
            now()->format('Y'),
            now()->format('m')
        );


        /*
         * =====================================================
         * NAMA FILE
         * =====================================================
         *
         * Contoh:
         *
         * INV-TB-20260925-ABC123-x8kP2m9Qw3Ls7dRt.jpg
         */
        $fileName = sprintf(
            '%s-%s.%s',
            Str::slug($this->transaction->invoice_no),
            Str::lower(Str::random(16)),
            $extension
        );


        /*
         * =====================================================
         * SIMPAN FILE
         * =====================================================
         *
         * Menggunakan disk local/private.
         */
        $newPath = $this->proof->storeAs(
            $directory,
            $fileName,
            'local'
        );


        /*
         * Simpan path file lama untuk dihapus
         * setelah transaksi berhasil.
         */
        $oldPath = $this->transaction->proof_path;


        try {

            /*
             * =================================================
             * UPDATE DATABASE ATOMIC
             * =================================================
             */
            DB::transaction(function () use (
                $newPath,
                $originalName,
                $mimeType
            ): void {

                /*
                 * Lock transaksi untuk mencegah
                 * double submit / race condition.
                 */
                $lockedTransaction = Transaction::query()
                    ->lockForUpdate()
                    ->findOrFail($this->transaction->id);


                /*
                 * Pastikan transaksi masih valid.
                 */
                $this->assertCanSubmitPayment(
                    $lockedTransaction
                );


                /*
                 * Update transaksi.
                 */
                $lockedTransaction->update([
                    'proof_path' =>
                    $newPath,

                    'proof_original_name' =>
                    $originalName,

                    'proof_mime_type' =>
                    $mimeType,

                    'proof_uploaded_at' =>
                    now(),

                    'status' =>
                    Transaction::STATUS_WAITING_VERIFICATION,

                    /*
                     * Jika sebelumnya ditolak,
                     * bersihkan data review lama.
                     */
                    'reviewed_by' =>
                    null,

                    'reviewed_at' =>
                    null,

                    'rejection_reason' =>
                    null,
                ]);
            });
        } catch (\Throwable $e) {

            /*
             * =================================================
             * ROLLBACK FILE
             * =================================================
             *
             * Jika database gagal, file baru dihapus
             * supaya tidak menjadi file yatim.
             */
            if (
                $newPath
                && Storage::disk('local')->exists($newPath)
            ) {
                Storage::disk('local')->delete($newPath);
            }

            /*
             * Lempar kembali error supaya
             * Livewire dapat menanganinya.
             */
            throw $e;
        }


        /*
         * =====================================================
         * HAPUS FILE LAMA
         * =====================================================
         *
         * File lama baru dihapus setelah database berhasil.
         */
        if (
            $oldPath
            && $oldPath !== $newPath
            && Storage::disk('local')->exists($oldPath)
        ) {
            Storage::disk('local')->delete($oldPath);
        }


        /*
         * =====================================================
         * RESET INPUT FILE
         * =====================================================
         */
        $this->reset('proof');


        /*
         * =====================================================
         * REFRESH TRANSACTION
         * =====================================================
         */
        $this->refreshTransaction();


        /*
         * =====================================================
         * KIRIM NOTIFIKASI EMAIL
         * =====================================================
         *
         * Email tidak boleh membuat transaksi gagal.
         */
        $this->sendPaymentProofNotifications();


        /*
         * =====================================================
         * SUCCESS MESSAGE
         * =====================================================
         */
        session()->flash(
            'success',
            'Bukti pembayaran berhasil dikirim dan sedang menunggu verifikasi admin.'
        );
    }


    /**
     * =========================================================
     * KONFIRMASI PEMBAYARAN TANPA UPLOAD BUKTI
     * =========================================================
     *
     * Digunakan jika payment method tidak membutuhkan
     * bukti pembayaran.
     */
    public function confirmWithoutProof(): void
    {
        /*
         * Sinkronisasi status transaksi.
         */
        $this->synchronizeExpiry();


        /*
         * Metode pembayaran yang membutuhkan bukti
         * tidak boleh menggunakan konfirmasi tanpa file.
         */
        if ($this->requiresProof()) {
            throw ValidationException::withMessages([
                'proof' =>
                'Metode pembayaran ini mewajibkan upload bukti pembayaran.',
            ]);
        }


        /*
         * =====================================================
         * UPDATE TRANSAKSI
         * =====================================================
         */
        DB::transaction(function (): void {

            $lockedTransaction = Transaction::query()
                ->lockForUpdate()
                ->findOrFail($this->transaction->id);


            /*
             * Validasi transaksi.
             */
            $this->assertCanSubmitPayment(
                $lockedTransaction
            );


            /*
             * Update status.
             */
            $lockedTransaction->update([
                'status' =>
                Transaction::STATUS_WAITING_VERIFICATION,

                'proof_uploaded_at' =>
                now(),

                'reviewed_by' =>
                null,

                'reviewed_at' =>
                null,

                'rejection_reason' =>
                null,
            ]);
        });


        /*
         * Refresh transaksi.
         */
        $this->refreshTransaction();


        /*
         * Kirim email customer dan admin.
         */
        $this->sendPaymentProofNotifications();


        /*
         * Pesan sukses.
         */
        session()->flash(
            'success',
            'Konfirmasi pembayaran berhasil dikirim dan sedang menunggu verifikasi admin.'
        );
    }


    /**
     * =========================================================
     * KIRIM NOTIFIKASI PEMBAYARAN
     * =========================================================
     *
     * Email dikirim kepada:
     *
     * 1. Customer
     * 2. Admin
     *
     * Jika email gagal, transaksi tetap berhasil.
     */
    private function sendPaymentProofNotifications(): void
    {
        try {

            /*
             * Pastikan relasi sudah tersedia.
             */
            $this->transaction->loadMissing([
                'package',
                'paymentMethod',
            ]);


            /*
             * =================================================
             * EMAIL CUSTOMER
             * =================================================
             */
            $customerEmail = trim(
                (string) $this->transaction->customer_email
            );


            if (
                $customerEmail !== ''
                && filter_var(
                    $customerEmail,
                    FILTER_VALIDATE_EMAIL
                )
            ) {

                Notification::route(
                    'mail',
                    $customerEmail
                )->notify(
                    PaymentProofSubmittedNotification::forCustomer(
                        $this->transaction
                    )
                );
            }


            /*
             * =================================================
             * EMAIL ADMIN
             * =================================================
             */
            $adminEmail = trim(
                (string) config(
                    'tunas_bimbel.admin_email'
                )
            );


            if (
                $adminEmail !== ''
                && filter_var(
                    $adminEmail,
                    FILTER_VALIDATE_EMAIL
                )
            ) {

                /*
                 * Jangan kirim dua email jika email admin
                 * sama dengan email customer.
                 */
                if (
                    strtolower($adminEmail)
                    !== strtolower($customerEmail)
                ) {

                    Notification::route(
                        'mail',
                        $adminEmail
                    )->notify(
                        PaymentProofSubmittedNotification::forAdmin(
                            $this->transaction
                        )
                    );
                }
            }
        } catch (\Throwable $e) {

            /*
             * =================================================
             * LOG EMAIL ERROR
             * =================================================
             *
             * Kegagalan email tidak boleh membuat
             * upload pembayaran dianggap gagal.
             */
            Log::error(
                'Gagal mengirim email notifikasi pembayaran.',
                [
                    'transaction_id' =>
                    $this->transaction->id,

                    'invoice_no' =>
                    $this->transaction->invoice_no,

                    'customer_email' =>
                    $this->transaction->customer_email,

                    'admin_email' =>
                    config('tunas_bimbel.admin_email'),

                    'error' =>
                    $e->getMessage(),
                ]
            );
        }
    }


    /**
     * =========================================================
     * VALIDASI TRANSAKSI
     * =========================================================
     */
    private function assertCanSubmitPayment(
        Transaction $transaction
    ): void {

        /*
         * Hanya transaksi berikut yang boleh
         * menerima pembayaran:
         *
         * - pending_payment
         * - rejected
         */
        if (
            ! in_array(
                $transaction->status,
                [
                    Transaction::STATUS_PENDING_PAYMENT,
                    Transaction::STATUS_REJECTED,
                ],
                true
            )
        ) {

            throw ValidationException::withMessages([
                'proof' =>
                'Transaksi ini tidak dapat menerima konfirmasi pembayaran.',
            ]);
        }


        /*
         * =====================================================
         * CEK EXPIRED
         * =====================================================
         */
        if (
            $transaction->expires_at
            && $transaction->expires_at->isPast()
        ) {

            /*
             * Tandai invoice sebagai expired.
             */
            $transaction->update([
                'status' =>
                Transaction::STATUS_EXPIRED,
            ]);


            throw ValidationException::withMessages([
                'proof' =>
                'Invoice sudah kedaluwarsa. Silakan kembali ke halaman paket dan buat invoice baru.',
            ]);
        }
    }


    /**
     * =========================================================
     * SINKRONISASI EXPIRY
     * =========================================================
     */
    private function synchronizeExpiry(): void
    {
        /*
         * Ambil data terbaru dari database.
         */
        $this->transaction->refresh();


        /*
         * Hanya status pending/rejected yang
         * dapat berubah menjadi expired.
         */
        if (
            in_array(
                $this->transaction->status,
                [
                    Transaction::STATUS_PENDING_PAYMENT,
                    Transaction::STATUS_REJECTED,
                ],
                true
            )
            && $this->transaction->expires_at
            && $this->transaction->expires_at->isPast()
        ) {

            $this->transaction->update([
                'status' =>
                Transaction::STATUS_EXPIRED,
            ]);
        }


        /*
         * Load ulang transaction beserta relasi.
         */
        $this->refreshTransaction();
    }


    /**
     * =========================================================
     * REFRESH TRANSACTION
     * =========================================================
     */
    private function refreshTransaction(): void
    {
        $this->transaction = Transaction::query()
            ->with([
                'package',
                'paymentMethod',
            ])
            ->findOrFail(
                $this->transaction->id
            );
    }


    /**
     * =========================================================
     * CAN UPLOAD PROOF
     * =========================================================
     */
    public function canUploadProof(): bool
    {
        return in_array(
            $this->transaction->status,
            [
                Transaction::STATUS_PENDING_PAYMENT,
                Transaction::STATUS_REJECTED,
            ],
            true
        )
            && ! $this->transaction->isExpired();
    }


    /**
     * =========================================================
     * REQUIRE PROOF
     * =========================================================
     */
    public function requiresProof(): bool
    {
        return (bool) (
            $this->transaction
            ->paymentMethod
            ?->requires_proof
            ?? true
        );
    }


    /**
     * =========================================================
     * PAYMENT TYPE
     * =========================================================
     */

    public function isQris(): bool
    {
        return $this->transaction
            ->paymentMethod
            ?->type === 'qris';
    }


    public function isBankTransfer(): bool
    {
        return $this->transaction
            ->paymentMethod
            ?->type === 'bank_transfer';
    }


    public function isEwallet(): bool
    {
        return $this->transaction
            ->paymentMethod
            ?->type === 'ewallet';
    }


    /**
     * =========================================================
     * MASKED EMAIL
     * =========================================================
     */
    public function maskedEmail(): string
    {
        $email = trim(
            (string) $this->transaction->customer_email
        );


        /*
         * Jika email kosong.
         */
        if ($email === '') {
            return '-';
        }


        /*
         * Jika format email tidak valid.
         */
        if (! str_contains($email, '@')) {
            return $email;
        }


        [$name, $domain] = explode(
            '@',
            $email,
            2
        );


        /*
         * Mask nama email.
         */
        if (mb_strlen($name) <= 2) {

            $maskedName =
                mb_substr($name, 0, 1)
                . '*';
        } else {

            $maskedName =
                mb_substr($name, 0, 2)
                . str_repeat(
                    '*',
                    max(
                        2,
                        mb_strlen($name) - 2
                    )
                );
        }


        return $maskedName . '@' . $domain;
    }


    /**
     * =========================================================
     * MASKED PHONE
     * =========================================================
     */
    public function maskedPhone(): string
    {
        $phone = preg_replace(
            '/\s+/',
            '',
            (string) $this->transaction->customer_phone
        );


        /*
         * Jika nomor kosong.
         */
        if (! $phone) {
            return '-';
        }


        /*
         * Jika nomor terlalu pendek.
         */
        if (mb_strlen($phone) <= 6) {
            return $phone;
        }


        return mb_substr(
            $phone,
            0,
            4
        )
            . str_repeat(
                '*',
                max(
                    3,
                    mb_strlen($phone) - 7
                )
            )
            . mb_substr(
                $phone,
                -3
            );
    }


    /**
     * =========================================================
     * RENDER
     * =========================================================
     */
    public function render()
    {
        return view(
            'livewire.payment.payment-page'
        )->layout(
            'layouts.landing'
        );
    }
}
