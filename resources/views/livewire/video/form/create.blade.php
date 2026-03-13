<form wire:submit.prevent="save">

    <!-- PILIH KELAS -->
    <div class="mb-6">
        <label class="block mb-2.5 text-sm font-medium text-heading">
            Select Kelas
        </label>

        <select
                wire:model.live="class_id"
                class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base">

            <option value="">Pilih Kelas</option>

            @foreach($classes as $classRoom)
                <option value="{{ $classRoom->id }}">
                    {{ $classRoom->name }}
                </option>
            @endforeach

        </select>
    </div>

    <!-- PILIH SUB-Materi -->
    <div class="mb-6">
        <label class="block mb-2.5 text-sm font-medium text-heading">
            Select Sub-Materi
        </label>

        <select
            wire:model="chapter_id"
            required
            class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base">

            <option value="">Pilih Sub-Materi</option>

            @foreach($chapters as $chapter)
                <option value="{{ $chapter->id }}">
                    {{ $chapter->title }}
                </option>
            @endforeach

        </select>

        @error('chapter_id')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>


    <!-- TITLE VIDEO -->
    <div class="mb-6">
        <label class="block mb-2.5 text-sm font-medium text-heading">
            Judul Video
        </label>

        <input 
            type="text"
            wire:model.defer="title"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
            placeholder="Masukkan judul video" />

        @error('title')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>


    <!-- SUBTITLE -->
    <div class="mb-6">
        <label class="block mb-2.5 text-sm font-medium text-heading">
            Subtitle Video
        </label>

        <input 
            type="text"
            wire:model.defer="subtitle"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
            placeholder="Masukkan subtitle video" />

        @error('subtitle')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>


    <!-- YOUTUBE ID -->
    <div class="mb-6">
        <label class="block mb-2.5 text-sm font-medium text-heading">
            Youtube ID
        </label>

        <input 
            type="text"
            wire:model.defer="youtube_id"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
            placeholder="Contoh: dQw4w9WgXcQ" />

        <p class="text-xs text-gray-500 mt-1">
            Masukkan hanya ID video Youtube, bukan link penuh.
        </p>

        @error('youtube_id')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>


    <!-- BUTTON -->
    <button 
        type="submit"
        wire:loading.attr="disabled"
        class="text-white bg-brand hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium rounded-base text-sm px-4 py-2.5">

        <span wire:loading.remove wire:target="save">
            Simpan Video
        </span>

        <span wire:loading wire:target="save">
            Saving...
        </span>

    </button>

</form>