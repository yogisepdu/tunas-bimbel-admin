<?php

namespace App\Livewire\Payment;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class PaymentPage extends Component
{
    use WithFileUploads;

    public Transaction $transaction;

    public $proof = null;

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

    public function uploadProof(): void
    {
        $this->synchronizeExpiry();

        $this->validate();

        if (! $this->canUploadProof()) {
            throw ValidationException::withMessages([
                'proof' =>
                'Bukti pembayaran tidak dapat dikirim untuk status transaksi saat ini.',
            ]);
        }

        if (! $this->requiresProof()) {
            throw ValidationException::withMessages([
                'proof' =>
                'Metode pembayaran ini tidak mewajibkan upload bukti.',
            ]);
        }

        $extension = strtolower(
            (string) $this->proof->getClientOriginalExtension()
        );

        if ($extension === '') {
            $extension = match ($this->proof->getMimeType()) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'application/pdf' => 'pdf',
                default => 'bin',
            };
        }

        $directory = sprintf(
            'payment-proofs/%s/%s',
            now()->format('Y'),
            now()->format('m')
        );

        $fileName = sprintf(
            '%s-%s.%s',
            Str::slug($this->transaction->invoice_no),
            Str::lower(Str::random(16)),
            $extension
        );

        $newPath = $this->proof->storeAs(
            $directory,
            $fileName,
            'local'
        );

        $oldPath = $this->transaction->proof_path;

        try {
            DB::transaction(function () use ($newPath): void {
                $lockedTransaction = Transaction::query()
                    ->lockForUpdate()
                    ->findOrFail($this->transaction->id);

                $this->assertCanSubmitPayment($lockedTransaction);

                $lockedTransaction->update([
                    'proof_path' =>
                    $newPath,

                    'proof_original_name' =>
                    $this->proof->getClientOriginalName(),

                    'proof_mime_type' =>
                    $this->proof->getMimeType(),

                    'proof_uploaded_at' =>
                    now(),

                    'status' =>
                    Transaction::STATUS_WAITING_VERIFICATION,

                    /*
                     * Jika sebelumnya ditolak lalu upload ulang,
                     * data review lama dibersihkan.
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
            if (
                $newPath
                && Storage::disk('local')->exists($newPath)
            ) {
                Storage::disk('local')->delete($newPath);
            }

            throw $e;
        }

        if (
            $oldPath
            && $oldPath !== $newPath
            && Storage::disk('local')->exists($oldPath)
        ) {
            Storage::disk('local')->delete($oldPath);
        }

        $this->reset('proof');

        $this->refreshTransaction();

        session()->flash(
            'success',
            'Bukti pembayaran berhasil dikirim dan sedang menunggu verifikasi admin.'
        );
    }

    public function confirmWithoutProof(): void
    {
        $this->synchronizeExpiry();

        if ($this->requiresProof()) {
            throw ValidationException::withMessages([
                'proof' =>
                'Metode pembayaran ini mewajibkan upload bukti pembayaran.',
            ]);
        }

        DB::transaction(function (): void {
            $lockedTransaction = Transaction::query()
                ->lockForUpdate()
                ->findOrFail($this->transaction->id);

            $this->assertCanSubmitPayment($lockedTransaction);

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

        $this->refreshTransaction();

        session()->flash(
            'success',
            'Konfirmasi pembayaran berhasil dikirim dan sedang menunggu verifikasi admin.'
        );
    }

    private function assertCanSubmitPayment(
        Transaction $transaction
    ): void {
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

        if (
            $transaction->expires_at
            && $transaction->expires_at->isPast()
        ) {
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

    private function synchronizeExpiry(): void
    {
        $this->transaction->refresh();

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

        $this->refreshTransaction();
    }

    private function refreshTransaction(): void
    {
        $this->transaction = Transaction::query()
            ->with([
                'package',
                'paymentMethod',
            ])
            ->findOrFail($this->transaction->id);
    }

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

    public function requiresProof(): bool
    {
        return (bool) (
            $this->transaction
            ->paymentMethod
            ?->requires_proof
            ?? true
        );
    }

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

    public function maskedEmail(): string
    {
        $email = $this->transaction->customer_email;

        if (! str_contains($email, '@')) {
            return $email;
        }

        [$name, $domain] = explode('@', $email, 2);

        if (mb_strlen($name) <= 2) {
            $maskedName = mb_substr($name, 0, 1) . '*';
        } else {
            $maskedName =
                mb_substr($name, 0, 2)
                . str_repeat(
                    '*',
                    max(2, mb_strlen($name) - 2)
                );
        }

        return $maskedName . '@' . $domain;
    }

    public function maskedPhone(): string
    {
        $phone = preg_replace(
            '/\s+/',
            '',
            $this->transaction->customer_phone
        );

        if (mb_strlen($phone) <= 6) {
            return $phone;
        }

        return mb_substr($phone, 0, 4)
            . str_repeat(
                '*',
                max(3, mb_strlen($phone) - 7)
            )
            . mb_substr($phone, -3);
    }

    public function render()
    {
        return view(
            'livewire.payment.payment-page'
        )->layout('layouts.landing');
    }
}
