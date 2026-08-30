<div class="space-y-6">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">
                Metode Pembayaran
            </flux:heading>

            <flux:text class="mt-2">
                Kelola rekening bank, e-wallet, dan QRIS
                yang digunakan pelanggan Tunas Bimbel.
            </flux:text>
        </div>

        <div class="flex flex-wrap gap-2">
            <div class="rounded-xl border border-zinc-200 bg-white px-4 py-3 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="text-xs text-zinc-500">
                    Total
                </div>

                <div class="text-lg font-bold">
                    {{ $totalMethods }}
                </div>
            </div>

            <div
                class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 dark:border-emerald-800 dark:bg-emerald-950/30">
                <div class="text-xs text-emerald-600 dark:text-emerald-400">
                    Aktif
                </div>

                <div class="text-lg font-bold text-emerald-700 dark:text-emerald-300">
                    {{ $totalActive }}
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================
        ALERT
    ========================================================== --}}
    @if (session()->has('success'))
        <div
            class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div
            class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-800 dark:bg-red-950/30 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid gap-6 xl:grid-cols-[420px_1fr]">

        {{-- =====================================================
            FORM
        ====================================================== --}}
        <div>
            <form
                class="space-y-5 rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900"
                wire:submit="save">

                <div>
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white">
                        {{ $editingId ? 'Edit Metode Pembayaran' : 'Tambah Metode Pembayaran' }}
                    </h2>

                    <p class="mt-1 text-sm text-zinc-500">
                        Data ini nantinya tampil pada
                        halaman pembayaran pelanggan.
                    </p>
                </div>

                {{-- NAME --}}
                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Nama Metode
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm outline-none focus:border-violet-500 focus:ring-2 focus:ring-violet-500/10 dark:border-zinc-700 dark:bg-zinc-800"
                        placeholder="Contoh: Bank BRI Tunas Bimbel" type="text" wire:model="name">

                    @error('name')
                        <p class="mt-1 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- TYPE --}}
                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Jenis Pembayaran
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm outline-none focus:border-violet-500 dark:border-zinc-700 dark:bg-zinc-800"
                        wire:model.live="type">
                        <option value="bank_transfer">
                            Transfer Bank
                        </option>

                        <option value="ewallet">
                            E-Wallet
                        </option>

                        <option value="qris">
                            QRIS
                        </option>

                        <option value="manual">
                            Pembayaran Manual Lainnya
                        </option>
                    </select>

                    @error('type')
                        <p class="mt-1 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- PROVIDER --}}
                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Provider
                    </label>

                    <input
                        class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm outline-none focus:border-violet-500 dark:border-zinc-700 dark:bg-zinc-800"
                        placeholder="
@if ($type === 'bank_transfer') BRI / BCA / BNI / Mandiri
@elseif($type === 'ewallet')DANA / OVO / GoPay
@elseif($type === 'qris')QRIS
@else Nama provider @endif"
                        type="text" wire:model="provider">

                    @error('provider')
                        <p class="mt-1 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- BANK / EWALLET --}}
                @if (in_array($type, ['bank_transfer', 'ewallet'], true))
                    <div>
                        <label class="mb-2 block text-sm font-medium">
                            Nama Pemilik
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm outline-none focus:border-violet-500 dark:border-zinc-700 dark:bg-zinc-800"
                            placeholder="Contoh: Tunas Bimbel" type="text" wire:model="account_name">

                        @error('account_name')
                            <p class="mt-1 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium">
                            {{ $type === 'bank_transfer' ? 'Nomor Rekening' : 'Nomor E-Wallet' }}
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm outline-none focus:border-violet-500 dark:border-zinc-700 dark:bg-zinc-800"
                            placeholder="
                                {{ $type === 'bank_transfer' ? 'Masukkan nomor rekening' : 'Masukkan nomor e-wallet' }}
                            "
                            type="text" wire:model="account_number">

                        @error('account_number')
                            <p class="mt-1 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                @endif

                {{-- QRIS --}}
                @if ($type === 'qris')
                    <div>
                        <label class="mb-2 block text-sm font-medium">
                            Gambar QRIS
                            <span class="text-red-500">*</span>
                        </label>

                        <input accept=".jpg,.jpeg,.png,.webp"
                            class="block w-full rounded-xl border border-zinc-300 bg-white p-3 text-sm dark:border-zinc-700 dark:bg-zinc-800"
                            type="file" wire:model="qr_image">

                        @error('qr_image')
                            <p class="mt-1 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                        <div wire:loading wire:target="qr_image">
                            <p class="mt-2 text-xs text-violet-500">
                                Mengupload preview...
                            </p>
                        </div>

                        @if ($qr_image)
                            <div class="mt-4">
                                <p class="mb-2 text-xs text-zinc-500">
                                    Preview QRIS baru:
                                </p>

                                <img class="h-48 w-48 rounded-xl border object-contain p-2"
                                    src="{{ $qr_image->temporaryUrl() }}">
                            </div>
                        @elseif($existingQrImage)
                            <div class="mt-4">
                                <p class="mb-2 text-xs text-zinc-500">
                                    QRIS saat ini:
                                </p>

                                <img class="h-48 w-48 rounded-xl border object-contain p-2"
                                    src="{{ asset('storage/' . $existingQrImage) }}">
                            </div>
                        @endif
                    </div>
                @endif

                {{-- INSTRUCTIONS --}}
                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Petunjuk Pembayaran
                    </label>

                    <textarea
                        class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm outline-none focus:border-violet-500 dark:border-zinc-700 dark:bg-zinc-800"
                        placeholder="Contoh: Transfer sesuai total invoice dan upload bukti pembayaran." rows="4"
                        wire:model="instructions"></textarea>

                    @error('instructions')
                        <p class="mt-1 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- SORT --}}
                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Urutan Tampil
                    </label>

                    <input
                        class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm outline-none focus:border-violet-500 dark:border-zinc-700 dark:bg-zinc-800"
                        min="0" type="number" wire:model="sort_order">
                </div>

                {{-- CHECKBOX --}}
                <div class="space-y-3">

                    <label class="flex cursor-pointer items-center gap-3">
                        <input class="h-4 w-4 rounded" type="checkbox" wire:model="requires_proof">

                        <span class="text-sm">
                            Wajib upload bukti pembayaran
                        </span>
                    </label>

                    <label class="flex cursor-pointer items-center gap-3">
                        <input class="h-4 w-4 rounded" type="checkbox" wire:model="is_active">

                        <span class="text-sm">
                            Aktif dan dapat dipilih pelanggan
                        </span>
                    </label>
                </div>

                {{-- ACTION --}}
                <div class="flex gap-3 pt-2">

                    @if ($editingId)
                        <button
                            class="flex-1 rounded-xl border border-zinc-300 px-4 py-3 text-sm font-semibold hover:bg-zinc-50 dark:border-zinc-700 dark:hover:bg-zinc-800"
                            type="button" wire:click="cancelEdit">
                            Batal
                        </button>
                    @endif

                    <button
                        class="flex-1 rounded-xl bg-violet-600 px-4 py-3 text-sm font-semibold text-white hover:bg-violet-700 disabled:opacity-60"
                        type="submit" wire:loading.attr="disabled" wire:target="save,qr_image">
                        <span wire:loading.remove wire:target="save">
                            {{ $editingId ? 'Simpan Perubahan' : 'Tambah Metode' }}
                        </span>

                        <span wire:loading wire:target="save">
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        {{-- =====================================================
            LIST PAYMENT METHODS
        ====================================================== --}}
        <div class="space-y-4">

            @forelse ($methods as $method)

                <div
                    class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">

                        {{-- INFO --}}
                        <div class="flex min-w-0 items-center gap-4">

                            <div
                                class="flex h-14 w-14 flex-shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-violet-100 text-violet-600 dark:bg-violet-950/40">
                                @if ($method->type === 'qris' && $method->qr_image)
                                    <img class="h-full w-full object-cover"
                                        src="{{ asset('storage/' . $method->qr_image) }}">
                                @elseif($method->type === 'bank_transfer')
                                    <span class="text-xl font-black">
                                        B
                                    </span>
                                @elseif($method->type === 'ewallet')
                                    <span class="text-xl font-black">
                                        E
                                    </span>
                                @else
                                    <span class="text-xl font-black">
                                        P
                                    </span>
                                @endif
                            </div>

                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="font-bold text-zinc-900 dark:text-white">
                                        {{ $method->name }}
                                    </h3>

                                    @if ($method->is_active)
                                        <span
                                            class="rounded-full bg-emerald-100 px-2.5 py-1 text-[10px] font-bold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300">
                                            AKTIF
                                        </span>
                                    @else
                                        <span
                                            class="rounded-full bg-zinc-100 px-2.5 py-1 text-[10px] font-bold text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                                            NONAKTIF
                                        </span>
                                    @endif
                                </div>

                                <p class="mt-1 text-sm text-zinc-500">
                                    {{ match ($method->type) {
                                        'bank_transfer' => 'Transfer Bank',
                                        'ewallet' => 'E-Wallet',
                                        'qris' => 'QRIS',
                                        default => 'Manual',
                                    } }}

                                    @if ($method->provider)
                                        • {{ $method->provider }}
                                    @endif
                                </p>

                                @if ($method->account_number)
                                    <p class="mt-1 text-sm font-medium">
                                        {{ $method->account_number }}

                                        @if ($method->account_name)
                                            —
                                            {{ $method->account_name }}
                                        @endif
                                    </p>
                                @endif

                                <div class="mt-2 flex flex-wrap gap-2 text-xs text-zinc-500">
                                    <span>
                                        {{ $method->transactions_count }}
                                        transaksi
                                    </span>

                                    <span>•</span>

                                    <span>
                                        Urutan:
                                        {{ $method->sort_order }}
                                    </span>

                                    @if ($method->requires_proof)
                                        <span>•</span>

                                        <span>
                                            Wajib bukti
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- ACTION --}}
                        <div class="flex flex-wrap items-center gap-2">
                            <button
                                class="rounded-lg border border-zinc-300 px-3 py-2 text-xs font-semibold hover:bg-zinc-50 dark:border-zinc-700 dark:hover:bg-zinc-800"
                                type="button"
                                wire:click="
                                    toggleActive(
                                        {{ $method->id }}
                                    )
                                ">
                                {{ $method->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>

                            <button
                                class="rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-600 hover:bg-blue-100 dark:bg-blue-950/30"
                                type="button"
                                wire:click="
                                    edit(
                                        {{ $method->id }}
                                    )
                                ">
                                Edit
                            </button>

                            <button
                                class="rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-100 dark:bg-red-950/30"
                                type="button"
                                wire:click="
                                    delete(
                                        {{ $method->id }}
                                    )
                                "
                                wire:confirm="
                                    Yakin ingin menghapus
                                    metode pembayaran ini?
                                ">
                                Hapus
                            </button>
                        </div>

                    </div>
                </div>

            @empty
                <div class="rounded-2xl border border-dashed border-zinc-300 p-12 text-center dark:border-zinc-700">
                    <div class="text-lg font-bold text-zinc-700 dark:text-zinc-200">
                        Belum ada metode pembayaran
                    </div>

                    <p class="mt-2 text-sm text-zinc-500">
                        Tambahkan rekening bank,
                        e-wallet, atau QRIS terlebih dahulu.
                    </p>
                </div>
            @endforelse

        </div>

    </div>
</div>
