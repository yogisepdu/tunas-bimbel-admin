<div class="p-6">

    <h1 class="text-xl font-bold mb-4">Soal Section</h1>

    {{-- SUCCESS MESSAGE --}}
    @if (session()->has('success'))
        <div class="mb-4 p-3 text-sm text-green-700 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- FORM --}}
    <form wire:submit.prevent="save" class="space-y-4">

        <!-- TITLE -->
        <div>
            <label class="block mb-2 text-sm font-medium text-heading">
                Nama Section
            </label>

            <input 
                type="text"
                wire:model.defer="title"
                placeholder="Contoh: Tes Kemampuan Dasar"
                class="w-full px-3 py-2.5 text-sm border rounded-lg bg-neutral-secondary-medium border-default-medium focus:ring-brand focus:border-brand"
            />

            @error('title')
                <span class="text-red-500 text-sm mt-1 block">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <!-- BUTTON -->
        <button 
            type="submit"
            wire:loading.attr="disabled"
            class="w-full text-white bg-brand hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium font-medium rounded-lg text-sm px-4 py-2.5 transition"
        >

            <span wire:loading.remove wire:target="save">
                + Tambah Section
            </span>

            <span wire:loading wire:target="save">
                Menyimpan...
            </span>

        </button>

    </form>

    {{-- LIST SECTION --}}
    <div class="mt-6 space-y-3">

        @forelse($sections as $section)
            <div class="p-4 rounded-xl border border-gray-200 bg-white shadow-sm flex items-center justify-between">

                <!-- TITLE -->
                <div>
                    <p class="text-sm font-semibold text-gray-800">
                        {{ $section->title }}
                    </p>

                </div>

                <!-- OPTIONAL ACTION -->
                <div class="flex items-center gap-3">

                    <span class="text-xs text-gray-400">
                        Section
                    </span>

                    <button
                        wire:click="$dispatch('confirmDelete', { id: {{ $section->id }} })"
                        wire:loading.attr="disabled"
                        class="text-red-500 hover:text-red-600 text-xs font-medium"
                    >
                        <span wire:loading.remove>Hapus</span>
                        <span wire:loading>Menghapus...</span>
                    </button>

                </div>

            </div>
        @empty
            <div class="text-center text-sm text-gray-500 py-6">
                Belum ada section
            </div>
        @endforelse

    </div>

</div>