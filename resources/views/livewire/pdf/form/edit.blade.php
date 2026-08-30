<div class="space-y-6">
    <div>
        <flux:heading size="xl">Edit Materi PDF</flux:heading>
        <flux:text class="mt-2">
            Gunakan file private untuk materi berbayar. URL eksternal tetap tersedia untuk data lama.
        </flux:text>
    </div>

    <form
        class="space-y-5 rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900"
        wire:submit="update">
        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-semibold">Kelas</label>
                <select class="w-full rounded-xl border border-zinc-300 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800"
                    wire:model.live="class_id">
                    <option value="">Pilih kelas</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
                @error('class_id')
                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Sub Materi</label>
                <select class="w-full rounded-xl border border-zinc-300 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800"
                    wire:model="chapter_id">
                    <option value="">Pilih sub materi</option>
                    @foreach ($chapters as $chapter)
                        <option value="{{ $chapter->id }}">{{ $chapter->title }}</option>
                    @endforeach
                </select>
                @error('chapter_id')
                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold">Judul PDF</label>
            <input class="w-full rounded-xl border border-zinc-300 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800"
                type="text" wire:model="title">
            @error('title')
                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold">Penyimpanan</label>
            <select class="w-full rounded-xl border border-zinc-300 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800"
                wire:model.live="storage_type">
                <option value="private_file">File Private (disarankan)</option>
                <option value="external_url">URL Eksternal / Legacy</option>
            </select>
        </div>

        @if ($storage_type === 'private_file')
            <div>
                <label class="mb-2 block text-sm font-semibold">Upload PDF</label>
                <input accept=".pdf"
                    class="block w-full rounded-xl border border-zinc-300 p-3 dark:border-zinc-700 dark:bg-zinc-800"
                    type="file" wire:model="pdf_file">
                <div class="mt-2 text-xs text-zinc-500">
                    PDF maksimal 20 MB. File disimpan pada disk private dan hanya dapat dibuka student yang memiliki
                    akses.
                </div>
                @error('pdf_file')
                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                @enderror
            </div>
        @else
            <div>
                <label class="mb-2 block text-sm font-semibold">URL PDF</label>
                <input class="w-full rounded-xl border border-zinc-300 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800"
                    placeholder="https://..." type="url" wire:model="pdf_url">
                @error('pdf_url')
                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                @enderror
            </div>
        @endif

        <button
            class="rounded-xl bg-violet-600 px-5 py-3 text-sm font-bold text-white hover:bg-violet-700 disabled:opacity-60"
            type="submit" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="save">Simpan Perubahan</span>
            <span wire:loading wire:target="save">Menyimpan...</span>
        </button>
    </form>
</div>
