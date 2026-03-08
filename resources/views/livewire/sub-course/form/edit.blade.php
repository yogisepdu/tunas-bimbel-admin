<form wire:submit.prevent="update">

    <!-- PILIH KELAS -->
    <div class="mb-6">
        <label for="class_id" class="block mb-2.5 text-sm font-medium text-heading">
            Select Kelas
        </label>

        <select
            id="class_id"
            wire:model.defer="class_id"
            required
            class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs">

            <option value="">Pilih Kelas</option>

            @foreach($classes as $item)
                <option value="{{ $item->id }}">
                    {{ $item->name }}
                </option>
            @endforeach

        </select>

        @error('class_id')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>


    <!-- TITLE CHAPTER -->
    <div class="mb-6">
        <label class="block mb-2.5 text-sm font-medium text-heading">
            Judul Chapter
        </label>

        <input 
            type="text"
            wire:model.defer="title"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
            placeholder="Masukkan judul chapter" />

        @error('title')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>


    <!-- DESKRIPSI CHAPTER -->
    <div class="mb-6">
        <label class="block mb-2.5 text-sm font-medium text-heading">
            Deskripsi Chapter
        </label>

        <textarea
            wire:model.defer="description"
            rows="4"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
            placeholder="Masukkan deskripsi chapter"></textarea>

        @error('description')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>


    <!-- BUTTON -->
    <div class="flex items-center gap-3">

        <button 
            type="submit"
            wire:loading.attr="disabled"
            class="text-white bg-brand hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium rounded-base text-sm px-4 py-2.5">

            <span wire:loading.remove wire:target="update">
                Update Chapter
            </span>

            <span wire:loading wire:target="update">
                Updating...
            </span>

        </button>

        <a 
            wire:navigate
            href="{{ route('sub-course.index') }}"
            class="px-4 py-2.5 text-sm border border-default rounded-base hover:bg-neutral-secondary-medium">
            Batal
        </a>

    </div>

</form>