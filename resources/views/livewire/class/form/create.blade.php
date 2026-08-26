<form wire:submit.prevent="save">

    <!-- NAMA KELAS -->
    <div class="mb-6">
        <label class="text-heading mb-2.5 block text-sm font-medium">
            Nama Kelas
        </label>

        <input
            class="bg-neutral-secondary-medium border-default-medium text-heading rounded-base focus:ring-brand focus:border-brand shadow-xs block w-full border px-3 py-2.5 text-sm"
            placeholder="Masukkan nama kelas" type="text" wire:model.defer="name" />

        @error('name')
            <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
    </div>


    <!-- DESKRIPSI KELAS -->
    <div class="mb-6">
        <label class="text-heading mb-2.5 block text-sm font-medium">
            Deskripsi Kelas
        </label>

        <textarea
            class="bg-neutral-secondary-medium border-default-medium text-heading rounded-base focus:ring-brand focus:border-brand shadow-xs block w-full border px-3 py-2.5 text-sm"
            placeholder="Masukkan deskripsi kelas" rows="4" wire:model.defer="description"></textarea>

        @error('description')
            <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
    </div>

    <div class="mb-6">
        <label class="text-heading mb-2.5 block text-sm font-medium">
            Teacher yang Mengajar
        </label>

        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
            @forelse ($teachers as $teacher)
                <label
                    class="border-default-medium bg-neutral-secondary-medium flex cursor-pointer items-center gap-3 rounded-lg border p-3">
                    <input class="rounded border-gray-300" type="checkbox" value="{{ $teacher->id }}"
                        wire:model="teacher_ids">

                    <div>
                        <p class="text-heading font-medium">
                            {{ $teacher->user->name }}
                        </p>

                        <p class="text-xs text-gray-500">
                            {{ $teacher->user->email }}
                        </p>
                    </div>
                </label>
            @empty
                <p class="text-sm text-gray-500">
                    Belum ada akun teacher.
                </p>
            @endforelse
        </div>

        @error('teacher_ids.*')
            <span class="mt-1 text-sm text-red-500">
                {{ $message }}
            </span>
        @enderror
    </div>


    <!-- BUTTON -->
    <button
        class="bg-brand hover:bg-brand-strong focus:ring-brand-medium shadow-xs rounded-base px-4 py-2.5 text-sm font-medium text-white focus:ring-4"
        type="submit" wire:loading.attr="disabled">

        <span wire:loading.remove wire:target="save">
            Simpan Kelas
        </span>

        <span wire:loading wire:target="save">
            Saving...
        </span>

    </button>

</form>
