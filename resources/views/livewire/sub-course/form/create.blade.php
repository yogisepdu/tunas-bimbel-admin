<form wire:submit.prevent="save">

    <!-- PILIH KELAS -->
    <div class="mb-6">
        <label for="sub-course" class="block mb-2.5 text-sm font-medium text-heading">Select Kelas</label>
        <select
            id="class_id"
            wire:model.defer="class_id"
            required
            class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs">
             <option value="">Pilih Kelas</option>
                
            @foreach($classes as $class)
                <option value="{{ $class->id }}">
                    {{ $class->name }}
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
    <button 
        type="submit"
        wire:loading.attr="disabled"
        class="text-white bg-brand hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium rounded-base text-sm px-4 py-2.5">

        <span wire:loading.remove wire:target="save">
            Simpan Chapter
        </span>

        <span wire:loading wire:target="save">
            Saving...
        </span>

    </button>

</form>