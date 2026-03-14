<div class="bg-[#1a1a1a] border border-gray-700 rounded-xl p-6 mb-6">

    <h2 class="text-lg font-semibold mb-4">
        Tambah Event Kalender
    </h2>

    <form wire:submit="save" class="space-y-4">

        {{-- TITLE --}}
        <div>
            <label class="text-sm text-gray-400">Judul Event</label>
            <input
                type="text"
                wire:model="title"
                class="w-full mt-1 bg-gray-800 border border-gray-600 rounded-lg p-2"
            >

            @error('title')
                <span class="text-red-400 text-xs">{{ $message }}</span>
            @enderror
        </div>

        {{-- DESCRIPTION --}}
        <div>
            <label class="text-sm text-gray-400">Deskripsi</label>
            <textarea
                wire:model="description"
                class="w-full mt-1 bg-gray-800 border border-gray-600 rounded-lg p-2"
            ></textarea>
        </div>

        {{-- DATE --}}
        <div>
            <label class="text-sm text-gray-400">Tanggal Event</label>
            <input
                type="date"
                wire:model="event_date"
                class="w-full mt-1 bg-gray-800 border border-gray-600 rounded-lg p-2"
            >

            @error('event_date')
                <span class="text-red-400 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded-lg text-white"
        >
            Simpan Event
        </button>

    </form>

</div>