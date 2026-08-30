<div class="space-y-6">

    @php
        $statusOptions = [
            'all' => 'Semua Status',
            \App\Models\Transaction::STATUS_PENDING_PAYMENT => 'Menunggu Pembayaran',
            \App\Models\Transaction::STATUS_WAITING_VERIFICATION => 'Menunggu Verifikasi',
            \App\Models\Transaction::STATUS_PAID => 'Lunas',
            \App\Models\Transaction::STATUS_REJECTED => 'Ditolak',
            \App\Models\Transaction::STATUS_EXPIRED => 'Kedaluwarsa',
            \App\Models\Transaction::STATUS_CANCELLED => 'Dibatalkan',
        ];

        $statusClasses = [
            \App\Models\Transaction::STATUS_PENDING_PAYMENT =>
                'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-950/30 dark:text-amber-300',

            \App\Models\Transaction::STATUS_WAITING_VERIFICATION =>
                'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-950/30 dark:text-blue-300',

            \App\Models\Transaction::STATUS_PAID =>
                'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-950/30 dark:text-emerald-300',

            \App\Models\Transaction::STATUS_REJECTED =>
                'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-950/30 dark:text-red-300',

            \App\Models\Transaction::STATUS_EXPIRED =>
                'bg-zinc-100 text-zinc-700 ring-zinc-600/20 dark:bg-zinc-800 dark:text-zinc-300',

            \App\Models\Transaction::STATUS_CANCELLED =>
                'bg-zinc-100 text-zinc-600 ring-zinc-500/20 dark:bg-zinc-800 dark:text-zinc-400',
        ];
    @endphp

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <flux:heading size="xl">
                Transaksi Paket
            </flux:heading>

            <flux:text class="mt-2">
                Kelola invoice, periksa bukti pembayaran, dan aktifkan paket student setelah pembayaran terverifikasi.
            </flux:text>
        </div>

        <button
            class="inline-flex items-center justify-center gap-2 rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 shadow-sm transition hover:bg-zinc-50 disabled:opacity-60 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800"
            type="button" wire:click="refreshTransactions" wire:loading.attr="disabled" wire:target="refreshTransactions">
            <span wire:loading.remove wire:target="refreshTransactions">
                Perbarui Data
            </span>

            <span wire:loading wire:target="refreshTransactions">
                Memperbarui...
            </span>
        </button>
    </div>

    {{-- FLASH --}}
    @if (session()->has('success'))
        <div
            class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    {{-- STATISTICS --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="text-xs font-medium text-zinc-500">
                Total Transaksi
            </div>

            <div class="mt-2 text-3xl font-black text-zinc-900 dark:text-white">
                {{ number_format($statistics['total']) }}
            </div>
        </div>

        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 dark:border-amber-900 dark:bg-amber-950/20">
            <div class="text-xs font-medium text-amber-700 dark:text-amber-300">
                Belum Bayar
            </div>

            <div class="mt-2 text-3xl font-black text-amber-800 dark:text-amber-200">
                {{ number_format($statistics['pending_payment']) }}
            </div>
        </div>

        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5 dark:border-blue-900 dark:bg-blue-950/20">
            <div class="text-xs font-medium text-blue-700 dark:text-blue-300">
                Menunggu Verifikasi
            </div>

            <div class="mt-2 text-3xl font-black text-blue-800 dark:text-blue-200">
                {{ number_format($statistics['waiting_verification']) }}
            </div>
        </div>

        <div
            class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-900 dark:bg-emerald-950/20">
            <div class="text-xs font-medium text-emerald-700 dark:text-emerald-300">
                Lunas
            </div>

            <div class="mt-2 text-3xl font-black text-emerald-800 dark:text-emerald-200">
                {{ number_format($statistics['paid']) }}
            </div>

            <div class="mt-2 text-xs text-emerald-700/80 dark:text-emerald-300/80">
                Rp {{ number_format($paidRevenue, 0, ',', '.') }}
            </div>
        </div>

        <div class="rounded-2xl border border-red-200 bg-red-50 p-5 dark:border-red-900 dark:bg-red-950/20">
            <div class="text-xs font-medium text-red-700 dark:text-red-300">
                Ditolak
            </div>

            <div class="mt-2 text-3xl font-black text-red-800 dark:text-red-200">
                {{ number_format($statistics['rejected']) }}
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <div class="grid gap-4 lg:grid-cols-[1fr_260px]">
            <div>
                <label class="mb-2 block text-sm font-semibold text-zinc-700 dark:text-zinc-200">
                    Cari Transaksi
                </label>

                <input
                    class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-500 focus:ring-2 focus:ring-violet-500/10 dark:border-zinc-700 dark:bg-zinc-800"
                    placeholder="Invoice, nama, email, WhatsApp, paket, metode pembayaran..." type="search"
                    wire:model.live.debounce.400ms="search">
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-zinc-700 dark:text-zinc-200">
                    Status
                </label>

                <select
                    class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-violet-500 dark:border-zinc-700 dark:bg-zinc-800"
                    wire:model.live="status">
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}">
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div
        class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800/80">
                    <tr>
                        <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">
                            Invoice
                        </th>

                        <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">
                            Student
                        </th>

                        <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">
                            Paket
                        </th>

                        <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">
                            Pembayaran
                        </th>

                        <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">
                            Total
                        </th>

                        <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">
                            Status
                        </th>

                        <th class="px-5 py-4 text-right text-xs font-bold uppercase tracking-wide text-zinc-500">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($transactions as $transaction)
                        <tr class="transition hover:bg-zinc-50/80 dark:hover:bg-zinc-800/40"
                            wire:key="transaction-row-{{ $transaction->id }}">
                            <td class="whitespace-nowrap px-5 py-4 align-top">
                                <div class="font-bold text-zinc-900 dark:text-white">
                                    {{ $transaction->invoice_no }}
                                </div>

                                <div class="mt-1 text-xs text-zinc-500">
                                    {{ $transaction->created_at?->format('d M Y, H:i') }}
                                </div>
                            </td>

                            <td class="px-5 py-4 align-top">
                                <div class="font-semibold text-zinc-800 dark:text-zinc-100">
                                    {{ $transaction->customer_name }}
                                </div>

                                <div class="mt-1 text-xs text-zinc-500">
                                    {{ $transaction->customer_email }}
                                </div>

                                <div class="mt-1 text-xs text-zinc-500">
                                    {{ $transaction->customer_phone }}
                                </div>
                            </td>

                            <td class="px-5 py-4 align-top">
                                <div class="font-semibold text-zinc-800 dark:text-zinc-100">
                                    {{ $transaction->package_name }}
                                </div>

                                <div class="mt-1 text-xs text-zinc-500">
                                    {{ $transaction->billing_label }}
                                    •
                                    {{ $transaction->duration_months }} bulan
                                </div>
                            </td>

                            <td class="px-5 py-4 align-top">
                                <div class="font-semibold text-zinc-800 dark:text-zinc-100">
                                    {{ $transaction->payment_method_name ?: '-' }}
                                </div>

                                @if ($transaction->payment_provider)
                                    <div class="mt-1 text-xs text-zinc-500">
                                        {{ $transaction->payment_provider }}
                                    </div>
                                @endif

                                @if ($transaction->proof_path)
                                    <div class="mt-2 text-xs font-semibold text-emerald-600">
                                        Bukti tersedia
                                    </div>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 align-top">
                                <div class="font-black text-zinc-900 dark:text-white">
                                    Rp {{ number_format((float) $transaction->total, 0, ',', '.') }}
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 align-top">
                                <span
                                    class="{{ $statusClasses[$transaction->status] ?? 'bg-zinc-100 text-zinc-700 ring-zinc-500/20' }} inline-flex rounded-full px-2.5 py-1 text-xs font-bold ring-1 ring-inset">
                                    {{ $transaction->status_label }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right align-top">
                                <a class="inline-flex items-center justify-center rounded-lg bg-violet-600 px-3.5 py-2 text-xs font-bold text-white transition hover:bg-violet-700"
                                    href="{{ route('transactions.show', ['id' => $transaction->id]) }}" wire:navigate>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-14 text-center" colspan="7">
                                <div class="text-base font-bold text-zinc-700 dark:text-zinc-200">
                                    Transaksi tidak ditemukan
                                </div>

                                <div class="mt-2 text-sm text-zinc-500">
                                    Coba ubah kata pencarian atau filter status.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transactions->hasPages())
            <div class="border-t border-zinc-200 px-5 py-4 dark:border-zinc-700">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
