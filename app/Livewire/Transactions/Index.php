<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $status = 'all';

    public int $perPage = 15;

    public function mount(): void
    {
        $this->expireOldInvoices();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function refreshTransactions(): void
    {
        $this->expireOldInvoices();

        session()->flash(
            'success',
            'Data transaksi berhasil diperbarui.'
        );
    }

    private function expireOldInvoices(): void
    {
        Transaction::query()
            ->whereIn(
                'status',
                [
                    Transaction::STATUS_PENDING_PAYMENT,
                    Transaction::STATUS_REJECTED,
                ]
            )
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->update([
                'status' => Transaction::STATUS_EXPIRED,
                'updated_at' => now(),
            ]);
    }

    public function render()
    {
        $query = Transaction::query()
            ->with([
                'user',
                'package',
                'paymentMethod',
                'reviewer',
            ]);

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        $search = trim($this->search);

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query
                    ->where(
                        'invoice_no',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'customer_name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'customer_email',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'customer_phone',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'package_name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'payment_method_name',
                        'like',
                        '%' . $search . '%'
                    );
            });
        }

        $transactions = $query
            ->latest('id')
            ->paginate($this->perPage);

        $statistics = [
            'total' => Transaction::count(),

            'pending_payment' =>
            Transaction::where(
                'status',
                Transaction::STATUS_PENDING_PAYMENT
            )->count(),

            'waiting_verification' =>
            Transaction::where(
                'status',
                Transaction::STATUS_WAITING_VERIFICATION
            )->count(),

            'paid' =>
            Transaction::where(
                'status',
                Transaction::STATUS_PAID
            )->count(),

            'rejected' =>
            Transaction::where(
                'status',
                Transaction::STATUS_REJECTED
            )->count(),
        ];

        $paidRevenue = (float) Transaction::query()
            ->where(
                'status',
                Transaction::STATUS_PAID
            )
            ->sum('total');

        return view(
            'livewire.transactions.index',
            [
                'transactions' => $transactions,
                'statistics' => $statistics,
                'paidRevenue' => $paidRevenue,
            ]
        )->layout(
            'layouts.admin',
            [
                'title' => 'Transaksi Paket',
            ]
        );
    }
}
