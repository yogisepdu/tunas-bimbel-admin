<form wire:submit.prevent="save">

    <!-- NAMA KELAS -->
    <div class="mb-6">
        <label class="block mb-2.5 text-sm font-medium text-heading">
            Nama Kelas
        </label>

        <input 
            type="text"
            wire:model.defer="name"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
            placeholder="Masukkan nama kelas" />

        @error('name')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>


    <!-- DESKRIPSI KELAS -->
    <div class="mb-6">
        <label class="block mb-2.5 text-sm font-medium text-heading">
            Deskripsi Kelas
        </label>

        <textarea
            wire:model.defer="description"
            rows="4"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
            placeholder="Masukkan deskripsi kelas"></textarea>

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
            Simpan Kelas
        </span>

        <span wire:loading wire:target="save">
            Saving...
        </span>

    </button>

</form>