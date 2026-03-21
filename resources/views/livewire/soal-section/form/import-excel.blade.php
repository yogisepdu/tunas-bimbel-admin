<div class="p-6 max-w-7xl mx-auto space-y-6">

    <h1 class="text-xl font-bold text-gray-800 bg-white p-4 rounded-lg shadow border">
        Import Soal dari Excel
    </h1>

    @if (session()->has('success'))
        <div class="p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-3 bg-red-100 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="import" class="space-y-5 bg-white p-5 rounded-xl shadow border">

        {{-- SELECT SET --}}
        <div>
            <label class="text-sm font-medium text-gray-700">Pilih Soal Set</label>

            <select wire:model="soal_set_id"
                class="w-full px-3 py-2 border rounded-lg text-gray-800">

                <option value="">-- Pilih Set --</option>

                @foreach($sets as $set)
                    <option value="{{ $set->id }}">
                        {{ $set->title }} ({{ $set->section?->title }})
                    </option>
                @endforeach

            </select>
        </div>

        {{-- FILE --}}
        <div>
            <label class="text-sm font-medium text-gray-700">Upload File Excel</label>

            <input type="file"
                wire:model="file"
                class="w-full mt-2 text-sm" />
        </div>

        {{-- BUTTON --}}
        <button
            type="submit"
            class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg">
            Import Excel
        </button>

    </form>

</div>