<form wire:submit.prevent="save">
    <div class="p-6">

        {{-- ALERT --}}
        @if (session()->has('error'))
            <div class="mb-4 p-3 text-red-700 bg-red-100 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if (session()->has('success'))
            <div class="mb-4 p-3 text-green-700 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif


        <div class="bg-neutral-primary-soft border border-default rounded-lg p-6">

            {{-- NAME --}}
            <div class="mb-4">
                <label class="text-sm text-gray-400">Nama Paket</label>
                <input type="text" wire:model.defer="name"
                    class="w-full mt-1 px-3 py-2 rounded bg-neutral-secondary-medium border border-default text-white">
            </div>

            {{-- IMAGE --}}
            <div class="mb-4">
                <label class="text-sm text-gray-400">Gambar Paket</label>

                <input 
                    type="file"
                    wire:model="image"
                    class="w-full mt-1 text-sm text-white"
                >

                {{-- loading --}}
                <div wire:loading wire:target="image" class="text-xs text-blue-400 mt-1">
                    Uploading...
                </div>

                {{-- preview NEW --}}
                @if ($image)
                    <img 
                        src="{{ $image->temporaryUrl() }}" 
                        class="mt-3 w-32 h-32 object-cover rounded"
                    >
                @elseif($oldImage)
                    {{-- preview OLD --}}
                    <img 
                        src="{{ asset('storage/' . $oldImage) }}"
                        class="mt-3 w-32 h-32 object-cover rounded"
                    >
                @endif
            </div>

            {{-- DESCRIPTION --}}
            <div class="mb-4">
                <label class="text-sm text-gray-400">Deskripsi</label>
                <textarea wire:model.defer="description"
                    class="w-full mt-1 px-3 py-2 rounded bg-neutral-secondary-medium border border-default text-white"></textarea>
            </div>

            {{-- PRICE --}}
            <div class="mb-6">
                <label class="text-sm text-gray-400">Harga</label>
                <input type="number" wire:model.defer="price"
                    class="w-full mt-1 px-3 py-2 rounded bg-neutral-secondary-medium border border-default text-white">
            </div>

            {{-- CHECKBOX --}}
            <div class="mb-6">
                <label class="text-sm text-gray-400 mb-2 block">
                    Pilih Kelas
                </label>

                <div class="grid grid-cols-2 gap-3">

                    @foreach($classes as $class)
                        <label wire:key="class-{{ $class->id }}"
                            class="flex items-center gap-2 bg-neutral-secondary-medium px-3 py-2 rounded border border-default cursor-pointer">

                            <input 
                                type="checkbox"
                                value="{{ $class->id }}"
                                wire:model.live="selectedClasses"
                                name="selectedClasses[]"
                            >

                            <span class="text-sm text-heading">
                                {{ $class->name }}
                            </span>

                        </label>
                    @endforeach

                </div>

                <div class="text-xs text-gray-400 mt-2">
                    {{ $this->selectedCount }} kelas dipilih
                </div>
            </div>

            {{-- ACTION --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('packages.index') }}"
                class="px-4 py-2 rounded bg-gray-500 text-white">
                    Batal
                </a>

                <!-- BUTTON -->
                <button 
                    type="submit"
                    wire:loading.attr="disabled"
                    class="text-white bg-brand hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium rounded-base text-sm px-4 py-2.5">

                    <span wire:loading.remove wire:target="save">
                        Simpan Materi
                    </span>

                    <span wire:loading wire:target="save">
                        Saving...
                    </span>

                </button>
            </div>

        </div>

    </div>
</form>