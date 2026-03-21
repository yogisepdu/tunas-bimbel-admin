<div class="p-6 max-w-7xl mx-auto">

    <h1 class="text-xl font-bold text-heading mb-4">
        Tambah Soal
    </h1>

    {{-- SUCCESS --}}
    @if (session()->has('success'))
        <div class="mb-4 p-3 text-sm text-green-700 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="save"
        class="space-y-5 bg-white p-5 rounded-xl shadow border">

        <!-- PILIH SET -->
        <div>
            <label class="text-sm text-gray-700 font-medium mb-2 block">
                Pilih Soal Set
            </label>

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

        <!-- QUESTION -->
        <div>
            <label class="text-sm text-gray-700 font-medium mb-2 block">
                Pertanyaan
            </label>

            <textarea
                wire:model.defer="question"
                rows="3"
                class="w-full px-3 py-2 border rounded-lg text-gray-800"
                placeholder="Tulis soal di sini..."></textarea>
        </div>

        <!-- OPTIONS -->
        <div class="space-y-3">

            @foreach(['A','B','C','D'] as $key)
                <div class="flex items-center gap-3">

                    <!-- RADIO -->
                    <input type="radio"
                        wire:model="correct_answer"
                        value="{{ $key }}"
                        class="accent-blue-600">

                    <!-- INPUT -->
                    <input type="text"
                        wire:model.defer="options.{{ $key }}"
                        placeholder="Opsi {{ $key }}"
                        class="w-full px-3 py-2 border rounded-lg text-gray-800">
                </div>
            @endforeach

        </div>

        <!-- BUTTON -->
        <button type="submit"
            class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg font-medium">

            + Simpan Soal
        </button>

    </form>
</div>