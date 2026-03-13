<form wire:submit.prevent="update">

    <!-- PILIH KELAS -->
    <div class="mb-6">
        <label class="block mb-2.5 text-sm font-medium text-heading">
            Select Kelas
        </label>

        <select
            wire:model="class_id"
            class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base">

            <option value="">Pilih Kelas</option>

            @foreach($classes as $classRoom)
                <option value="{{ $classRoom->id }}">
                    {{ $classRoom->name }}
                </option>
            @endforeach

        </select>

        @error('class_id')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>


    <!-- TITLE QUIZ -->
    <div class="mb-6">
        <label class="block mb-2.5 text-sm font-medium text-heading">
            Judul Quiz
        </label>

        <input 
            type="text"
            wire:model.defer="title"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
            placeholder="Masukkan judul quiz" />

        @error('title')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>


    <!-- DURASI QUIZ -->
    <div class="mb-6">
        <label class="block mb-2.5 text-sm font-medium text-heading">
            Durasi Quiz (Menit)
        </label>

        <input 
            type="number"
            wire:model.defer="duration"
            min="1"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
            placeholder="Contoh: 30" />

        @error('duration')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>


    <!-- BUTTON -->
    <button 
        type="submit"
        wire:loading.attr="disabled"
        class="text-white bg-brand hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium rounded-base text-sm px-4 py-2.5">

        <span wire:loading.remove wire:target="update">
            Update Quiz
        </span>

        <span wire:loading wire:target="update">
            Updating...
        </span>

    </button>

</form>