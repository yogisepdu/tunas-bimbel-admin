<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Show extends Component
{
    public Transaction $transaction;

    public string $rejection_reason = '';

    public function mount(int $id): void
    {
        $this->transaction = Transaction::findOrFail($id);

        $this->synchronizeExpiry();

        $this->loadTransaction();
    }

    public function verifyPayment(
        TransactionService $transactionService
    ): void {
        $this->loadTransaction();

        $transactionService->verifyAndActivate(
            $this->transaction,
            auth()->user()
        );

        $this->rejection_reason = '';

        $this->loadTransaction();

        session()->flash(
            'success',
            'Pembayaran berhasil diverifikasi dan paket student sudah diaktifkan.'
        );
    }

    public function rejectPayment(
        TransactionService $transactionService
    ): void {
        $this->validate(
            [
                'rejection_reason' => [
                    'required',
                    'string',
                    'min:5',
                    'max:1000',
                ],
            ],
            [
                'rejection_reason.required' =>
                'Alasan penolakan wajib diisi.',

                'rejection_reason.min' =>
                'Alasan penolakan minimal 5 karakter.',

                'rejection_reason.max' =>
                'Alasan penolakan maksimal 1000 karakter.',
            ]
        );

        $transactionService->reject(
            $this->transaction,
            auth()->user(),
            $this->rejection_reason
        );

        $this->rejection_reason = '';

        $this->loadTransaction();

        session()->flash(
            'success',
            'Pembayaran ditolak. Pelanggan dapat mengirim ulang bukti pembayaran selama invoice belum kedaluwarsa.'
        );
    }

    public function refreshTransaction(): void
    {
        $this->synchronizeExpiry();

        $this->loadTransaction();

        session()->flash(
            'success',
            'Status transaksi berhasil diperbarui.'
        );
    }

    private function synchronizeExpiry(): void
    {
        $transaction = Transaction::findOrFail(
            $this->transaction->id
        );

        if (
            in_array(
                $transaction->status,
                [
                    Transaction::STATUS_PENDING_PAYMENT,
                    Transaction::STATUS_REJECTED,
                ],
                true
            )
            && $transaction->expires_at
            && $transaction->expires_at->isPast()
        ) {
            $transaction->update([
                'status' => Transaction::STATUS_EXPIRED,
            ]);
        }
    }

    private function loadTransaction(): void
    {
        $this->transaction = Transaction::query()
            ->with([
                'user',
                'package',
                'paymentMethod',
                'reviewer',
            ])
            ->findOrFail(
                $this->transaction->id
            );
    }

    public function render()
    {
        $entitlement = null;

        if (
            $this->transaction->user_id
            && $this->transaction->package_id
        ) {
            $entitlement = DB::table('user_packages')
                ->where(
                    'user_id',
                    $this->transaction->user_id
                )
                ->where(
                    'package_id',
                    $this->transaction->package_id
                )
                ->first();
        }

        return view(
            'livewire.transactions.show',
            [
                'entitlement' => $entitlement,
            ]
        )->layout(
            'layouts.admin',
            [
                'title' => 'Detail Transaksi',
            ]
        );
    }
}
