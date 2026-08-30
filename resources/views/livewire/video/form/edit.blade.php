<div class="space-y-6">
    <div>
        <flux:heading size="xl">Edit Video</flux:heading>
        <flux:text class="mt-2">
            Video YouTube tetap didukung. Untuk video yang benar-benar private, upload file langsung.
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
            <label class="mb-2 block text-sm font-semibold">Judul Video</label>
            <input class="w-full rounded-xl border border-zinc-300 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800"
                type="text" wire:model="title">
            @error('title')
                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold">Subtitle</label>
            <input class="w-full rounded-xl border border-zinc-300 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800"
                type="text" wire:model="subtitle">
            @error('subtitle')
                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold">Sumber Video</label>
            <select class="w-full rounded-xl border border-zinc-300 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800"
                wire:model.live="source_type">
                <option value="youtube">YouTube</option>
                <option value="private_file">File Video Private</option>
            </select>
        </div>

        @if ($source_type === 'youtube')
            <div>
                <label class="mb-2 block text-sm font-semibold">YouTube ID</label>
                <input class="w-full rounded-xl border border-zinc-300 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800"
                    placeholder="Contoh: dQw4w9WgXcQ" type="text" wire:model="youtube_id">
                <div class="mt-2 text-xs text-zinc-500">
                    YouTube ID hanya diberikan API setelah StudentAccess lolos, tetapi tidak dapat dibuat benar-benar
                    private setelah ID diterima client.
                </div>
                @error('youtube_id')
                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                @enderror
            </div>
        @else
            <div>
                <label class="mb-2 block text-sm font-semibold">Upload Video Private</label>
                <input accept=".mp4,.webm,.mov"
                    class="block w-full rounded-xl border border-zinc-300 p-3 dark:border-zinc-700 dark:bg-zinc-800"
                    type="file" wire:model="video_file">
                <div class="mt-2 text-xs text-zinc-500">
                    MP4/WEBM/MOV maksimal 200 MB. Untuk file besar, sesuaikan PHP dan Livewire upload limits.
                </div>
                @error('video_file')
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
