<div class="space-y-6">

    @php
        $statusClass = match ($transaction->status) {
            \App\Models\Transaction::STATUS_PENDING_PAYMENT
                => 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-950/30 dark:text-amber-300',

            \App\Models\Transaction::STATUS_WAITING_VERIFICATION
                => 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-950/30 dark:text-blue-300',

            \App\Models\Transaction::STATUS_PAID
                => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-950/30 dark:text-emerald-300',

            \App\Models\Transaction::STATUS_REJECTED
                => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-950/30 dark:text-red-300',

            default => 'bg-zinc-100 text-zinc-700 ring-zinc-600/20 dark:bg-zinc-800 dark:text-zinc-300',
        };
    @endphp

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <a class="mb-3 inline-flex items-center gap-2 text-sm font-semibold text-violet-600 hover:text-violet-700"
                href="{{ route('transactions.index') }}" wire:navigate>
                ← Kembali ke Transaksi
            </a>

            <div class="flex flex-wrap items-center gap-3">
                <flux:heading size="xl">
                    {{ $transaction->invoice_no }}
                </flux:heading>

                <span class="{{ $statusClass }} inline-flex rounded-full px-3 py-1 text-xs font-bold ring-1 ring-inset">
                    {{ $transaction->status_label }}
                </span>
            </div>

            <flux:text class="mt-2">
                Dibuat {{ $transaction->created_at?->format('d M Y, H:i') }}
            </flux:text>
        </div>

        <button
            class="inline-flex items-center justify-center rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-700 shadow-sm hover:bg-zinc-50 disabled:opacity-60 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800"
            type="button" wire:click="refreshTransaction" wire:loading.attr="disabled" wire:target="refreshTransaction">
            Perbarui Status
        </button>
    </div>

    {{-- FLASH --}}
    @if (session()->has('success'))
        <div
            class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    {{-- PAID ALERT --}}
    @if ($transaction->status === \App\Models\Transaction::STATUS_PAID)
        <div
            class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-900 dark:bg-emerald-950/20">
            <div class="text-sm font-black text-emerald-800 dark:text-emerald-200">
                Pembayaran telah diverifikasi
            </div>

            <div class="mt-2 text-sm leading-6 text-emerald-700 dark:text-emerald-300">
                Paket sudah diaktifkan pada akun student.
                Verifikasi dilakukan
                @if ($transaction->reviewer)
                    oleh <strong>{{ $transaction->reviewer->name }}</strong>
                @endif

                @if ($transaction->reviewed_at)
                    pada {{ $transaction->reviewed_at->format('d M Y, H:i') }}.
                @endif
            </div>
        </div>
    @endif

    {{-- REJECTED ALERT --}}
    @if ($transaction->status === \App\Models\Transaction::STATUS_REJECTED && $transaction->rejection_reason)
        <div class="rounded-2xl border border-red-200 bg-red-50 p-5 dark:border-red-900 dark:bg-red-950/20">
            <div class="text-sm font-black text-red-800 dark:text-red-200">
                Pembayaran ditolak
            </div>

            <div class="mt-2 text-sm leading-6 text-red-700 dark:text-red-300">
                {{ $transaction->rejection_reason }}
            </div>
        </div>
    @endif

    <div class="grid gap-6 xl:grid-cols-[1fr_380px]">

        {{-- LEFT --}}
        <div class="space-y-6">

            {{-- CUSTOMER --}}
            <section
                class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mb-5">
                    <h2 class="text-lg font-black text-zinc-900 dark:text-white">
                        Data Pembeli / Student
                    </h2>

                    <p class="mt-1 text-sm text-zinc-500">
                        Paket akan diberikan kepada user student yang tercatat pada transaksi ini.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-800/70">
                        <div class="text-xs text-zinc-500">
                            Nama
                        </div>

                        <div class="mt-1 font-bold text-zinc-900 dark:text-white">
                            {{ $transaction->customer_name }}
                        </div>
                    </div>

                    <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-800/70">
                        <div class="text-xs text-zinc-500">
                            User ID
                        </div>

                        <div class="mt-1 font-bold text-zinc-900 dark:text-white">
                            {{ $transaction->user_id ?: '-' }}
                        </div>
                    </div>

                    <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-800/70">
                        <div class="text-xs text-zinc-500">
                            Email Student
                        </div>

                        <div class="mt-1 break-all font-bold text-zinc-900 dark:text-white">
                            {{ $transaction->customer_email }}
                        </div>
                    </div>

                    <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-800/70">
                        <div class="text-xs text-zinc-500">
                            WhatsApp
                        </div>

                        <div class="mt-1 font-bold text-zinc-900 dark:text-white">
                            {{ $transaction->customer_phone }}
                        </div>
                    </div>
                </div>
            </section>

            {{-- PACKAGE --}}
            <section
                class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mb-5">
                    <h2 class="text-lg font-black text-zinc-900 dark:text-white">
                        Paket & Nilai Transaksi
                    </h2>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                        <div class="text-xs text-zinc-500">
                            Paket
                        </div>

                        <div class="mt-1 font-bold text-zinc-900 dark:text-white">
                            {{ $transaction->package_name }}
                        </div>
                    </div>

                    <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                        <div class="text-xs text-zinc-500">
                            Periode
                        </div>

                        <div class="mt-1 font-bold text-zinc-900 dark:text-white">
                            {{ $transaction->billing_label }}
                            •
                            {{ $transaction->duration_months }} bulan
                        </div>
                    </div>

                    <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                        <div class="text-xs text-zinc-500">
                            Subtotal
                        </div>

                        <div class="mt-1 font-bold text-zinc-900 dark:text-white">
                            Rp {{ number_format((float) $transaction->subtotal, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                        <div class="text-xs text-zinc-500">
                            Diskon
                        </div>

                        <div class="mt-1 font-bold text-emerald-600">
                            Rp {{ number_format((float) $transaction->discount, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div
                    class="mt-4 flex flex-col gap-2 rounded-xl bg-violet-600 p-5 text-white sm:flex-row sm:items-center sm:justify-between">
                    <span class="text-sm font-semibold text-violet-100">
                        Total Pembayaran
                    </span>

                    <strong class="text-2xl font-black">
                        Rp {{ number_format((float) $transaction->total, 0, ',', '.') }}
                    </strong>
                </div>
            </section>

            {{-- PAYMENT PROOF --}}
            <section
                class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mb-5">
                    <h2 class="text-lg font-black text-zinc-900 dark:text-white">
                        Pembayaran & Bukti
                    </h2>

                    <p class="mt-1 text-sm text-zinc-500">
                        Cocokkan metode, rekening tujuan, nominal, dan bukti pembayaran sebelum melakukan verifikasi.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-800/70">
                        <div class="text-xs text-zinc-500">
                            Metode
                        </div>

                        <div class="mt-1 font-bold text-zinc-900 dark:text-white">
                            {{ $transaction->payment_method_name ?: '-' }}
                        </div>
                    </div>

                    <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-800/70">
                        <div class="text-xs text-zinc-500">
                            Provider
                        </div>

                        <div class="mt-1 font-bold text-zinc-900 dark:text-white">
                            {{ $transaction->payment_provider ?: '-' }}
                        </div>
                    </div>

                    <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-800/70">
                        <div class="text-xs text-zinc-500">
                            Rekening / Nomor Tujuan
                        </div>

                        <div class="mt-1 font-bold text-zinc-900 dark:text-white">
                            {{ $transaction->payment_account_number ?: '-' }}
                        </div>
                    </div>

                    <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-800/70">
                        <div class="text-xs text-zinc-500">
                            Atas Nama
                        </div>

                        <div class="mt-1 font-bold text-zinc-900 dark:text-white">
                            {{ $transaction->payment_account_name ?: '-' }}
                        </div>
                    </div>
                </div>

                @if ($transaction->proof_path)
                    <div
                        class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-900 dark:bg-emerald-950/20">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <div class="text-sm font-bold text-emerald-800 dark:text-emerald-200">
                                    Bukti pembayaran tersedia
                                </div>

                                <div class="mt-1 break-all text-xs text-emerald-700 dark:text-emerald-300">
                                    {{ $transaction->proof_original_name ?: 'Bukti pembayaran' }}
                                </div>

                                @if ($transaction->proof_uploaded_at)
                                    <div class="mt-1 text-xs text-emerald-700/80 dark:text-emerald-300/80">
                                        Dikirim {{ $transaction->proof_uploaded_at->format('d M Y, H:i') }}
                                    </div>
                                @endif
                            </div>

                            <a class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-emerald-700"
                                href="{{ route('transactions.proof', ['transaction' => $transaction->id]) }}"
                                rel="noopener noreferrer" target="_blank">
                                Lihat Bukti
                            </a>
                        </div>
                    </div>
                @else
                    <div
                        class="mt-5 rounded-xl border border-dashed border-zinc-300 p-6 text-center dark:border-zinc-700">
                        <div class="font-bold text-zinc-700 dark:text-zinc-200">
                            Belum ada bukti pembayaran
                        </div>

                        <div class="mt-1 text-sm text-zinc-500">
                            Pelanggan belum mengirim file bukti pembayaran.
                        </div>
                    </div>
                @endif
            </section>

            {{-- REVIEW ACTION --}}
            @if ($transaction->status === \App\Models\Transaction::STATUS_WAITING_VERIFICATION)
                <section
                    class="rounded-2xl border border-blue-200 bg-blue-50 p-6 shadow-sm dark:border-blue-900 dark:bg-blue-950/20">
                    <div class="mb-5">
                        <h2 class="text-lg font-black text-blue-900 dark:text-blue-100">
                            Verifikasi Pembayaran
                        </h2>

                        <p class="mt-1 text-sm leading-6 text-blue-700 dark:text-blue-300">
                            Pastikan pembayaran benar-benar diterima sebelum menekan tombol verifikasi.
                            Ketika diterima, paket langsung aktif pada akun student.
                        </p>
                    </div>

                    <div class="grid gap-5 lg:grid-cols-2">
                        {{-- REJECT --}}
                        <div class="rounded-xl border border-red-200 bg-white p-5 dark:border-red-900 dark:bg-zinc-900">
                            <h3 class="font-black text-red-700 dark:text-red-300">
                                Tolak Pembayaran
                            </h3>

                            <p class="mt-1 text-xs leading-5 text-zinc-500">
                                Isi alasan yang jelas agar pelanggan dapat memperbaiki bukti pembayaran.
                            </p>

                            <textarea
                                class="mt-4 w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm outline-none focus:border-red-500 dark:border-zinc-700 dark:bg-zinc-800"
                                placeholder="Contoh: nominal transfer tidak sesuai invoice..." rows="5" wire:model="rejection_reason"></textarea>

                            @error('rejection_reason')
                                <div class="mt-2 text-xs font-semibold text-red-600">
                                    {{ $message }}
                                </div>
                            @enderror

                            <button
                                class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-red-600 px-4 py-3 text-sm font-bold text-white hover:bg-red-700 disabled:opacity-60"
                                type="button" wire:click="rejectPayment"
                                wire:confirm="Yakin ingin menolak pembayaran ini?" wire:loading.attr="disabled"
                                wire:target="rejectPayment">
                                <span wire:loading.remove wire:target="rejectPayment">
                                    Tolak Pembayaran
                                </span>

                                <span wire:loading wire:target="rejectPayment">
                                    Memproses...
                                </span>
                            </button>
                        </div>

                        {{-- APPROVE --}}
                        <div
                            class="rounded-xl border border-emerald-200 bg-white p-5 dark:border-emerald-900 dark:bg-zinc-900">
                            <h3 class="font-black text-emerald-700 dark:text-emerald-300">
                                Terima Pembayaran
                            </h3>

                            <p class="mt-1 text-xs leading-5 text-zinc-500">
                                Tindakan ini akan mengubah transaksi menjadi lunas dan menjalankan
                                aktivasi/perpanjangan paket student melalui TransactionService.
                            </p>

                            <div
                                class="mt-5 rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-200">
                                <strong>Setelah diterima:</strong>

                                <div class="mt-2 leading-6">
                                    transaksi = paid<br>
                                    user_packages = active<br>
                                    masa aktif = diperbarui otomatis
                                </div>
                            </div>

                            <button
                                class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white hover:bg-emerald-700 disabled:opacity-60"
                                type="button" wire:click="verifyPayment"
                                wire:confirm="Pastikan pembayaran sudah benar-benar diterima. Aktifkan paket student sekarang?"
                                wire:loading.attr="disabled" wire:target="verifyPayment">
                                <span wire:loading.remove wire:target="verifyPayment">
                                    Verifikasi & Aktifkan Paket
                                </span>

                                <span wire:loading wire:target="verifyPayment">
                                    Mengaktifkan paket...
                                </span>
                            </button>
                        </div>
                    </div>
                </section>
            @endif

            {{-- ENTITLEMENT --}}
            @if ($entitlement)
                <section
                    class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <h2 class="text-lg font-black text-zinc-900 dark:text-white">
                        Akses Paket Student
                    </h2>

                    <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-800/70">
                            <div class="text-xs text-zinc-500">Status</div>

                            <div class="mt-1 font-bold text-zinc-900 dark:text-white">
                                {{ $entitlement->status ?? '-' }}
                            </div>
                        </div>

                        <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-800/70">
                            <div class="text-xs text-zinc-500">Transaction ID</div>

                            <div class="mt-1 font-bold text-zinc-900 dark:text-white">
                                {{ $entitlement->transaction_id ?? '-' }}
                            </div>
                        </div>

                        <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-800/70">
                            <div class="text-xs text-zinc-500">Aktif Sejak</div>

                            <div class="mt-1 font-bold text-zinc-900 dark:text-white">
                                {{ $entitlement->activated_at ? \Carbon\Carbon::parse($entitlement->activated_at)->format('d M Y, H:i') : '-' }}
                            </div>
                        </div>

                        <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-800/70">
                            <div class="text-xs text-zinc-500">Berlaku Sampai</div>

                            <div class="mt-1 font-bold text-zinc-900 dark:text-white">
                                @if ($entitlement->expires_at)
                                    {{ \Carbon\Carbon::parse($entitlement->expires_at)->format('d M Y, H:i') }}
                                @else
                                    Tanpa batas / legacy
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        </div>

        {{-- RIGHT SUMMARY --}}
        <aside class="space-y-6">
            <section
                class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="text-lg font-black text-zinc-900 dark:text-white">
                    Ringkasan Invoice
                </h2>

                <div class="mt-5 space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <span class="text-sm text-zinc-500">
                            Invoice
                        </span>

                        <strong class="text-right text-sm text-zinc-900 dark:text-white">
                            {{ $transaction->invoice_no }}
                        </strong>
                    </div>

                    <div class="flex items-start justify-between gap-4">
                        <span class="text-sm text-zinc-500">
                            Status
                        </span>

                        <strong class="text-right text-sm text-zinc-900 dark:text-white">
                            {{ $transaction->status_label }}
                        </strong>
                    </div>

                    <div class="flex items-start justify-between gap-4">
                        <span class="text-sm text-zinc-500">
                            Metode
                        </span>

                        <strong class="text-right text-sm text-zinc-900 dark:text-white">
                            {{ $transaction->payment_method_name ?: '-' }}
                        </strong>
                    </div>

                    <div class="flex items-start justify-between gap-4">
                        <span class="text-sm text-zinc-500">
                            Invoice berakhir
                        </span>

                        <strong class="text-right text-sm text-zinc-900 dark:text-white">
                            {{ $transaction->expires_at?->format('d M Y, H:i') ?: '-' }}
                        </strong>
                    </div>

                    @if ($transaction->reviewed_at)
                        <div class="flex items-start justify-between gap-4">
                            <span class="text-sm text-zinc-500">
                                Ditinjau
                            </span>

                            <strong class="text-right text-sm text-zinc-900 dark:text-white">
                                {{ $transaction->reviewed_at->format('d M Y, H:i') }}
                            </strong>
                        </div>
                    @endif
                </div>

                <div class="mt-6 rounded-xl bg-violet-600 p-5 text-white">
                    <div class="text-xs font-semibold text-violet-100">
                        TOTAL PEMBAYARAN
                    </div>

                    <div class="mt-2 text-2xl font-black">
                        Rp {{ number_format((float) $transaction->total, 0, ',', '.') }}
                    </div>
                </div>
            </section>

            <section
                class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="text-base font-black text-zinc-900 dark:text-white">
                    Keamanan Verifikasi
                </h2>

                <div class="mt-4 space-y-3 text-sm leading-6 text-zinc-500">
                    <p>
                        Jangan memverifikasi hanya berdasarkan screenshot.
                        Cocokkan bukti dengan mutasi rekening/merchant apabila memungkinkan.
                    </p>

                    <p>
                        Tombol verifikasi langsung mengaktifkan atau memperpanjang paket student.
                    </p>

                    <p>
                        Bukti pembayaran dibuka melalui controller private dan tidak melalui
                        <code>/storage/...</code>.
                    </p>
                </div>
            </section>
        </aside>
    </div>
</div>
