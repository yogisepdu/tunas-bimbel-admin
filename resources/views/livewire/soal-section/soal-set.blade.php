<div class="p-6 max-w-7xl mx-auto">

    <h1 class="text-xl font-bold mb-4">Soal Set</h1>

    {{-- SUCCESS --}}
    @if (session()->has('success'))
        <div class="mb-4 p-3 text-sm text-green-700 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- ================= FORM ================= --}}
    <form wire:submit.prevent="save"
        class="space-y-5 bg-white p-5 rounded-xl shadow-sm border">

        <!-- SECTION -->
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">
                Pilih Section
            </label>

            <select wire:model="soal_section_id"
                class="w-full px-3 py-2.5 border rounded-lg text-sm bg-white text-gray-800 focus:ring-2 focus:ring-blue-500">

                <option value="" class="text-gray-500">-- Pilih Section --</option>

                @foreach($sections as $section)
                    <option value="{{ $section->id }}">
                        {{ $section->title }}
                    </option>
                @endforeach

            </select>

            @error('soal_section_id')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- TITLE -->
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">
                Judul Soal
            </label>

            <input type="text"
                wire:model.defer="title"
                placeholder="Contoh: Verbal Logic Reasoning"
                class="w-full px-3 py-2.5 border rounded-lg text-sm bg-white text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500" />

            @error('title')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- GRID -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- DURASI -->
            <div>
                <label class="text-sm text-gray-600 mb-1 block">
                    Durasi (Menit)
                </label>

                <input type="number"
                    wire:model.defer="duration"
                    placeholder="30"
                    class="w-full px-3 py-2 border rounded-lg text-sm bg-white text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- POINT -->
            <div>
                <label class="text-sm text-gray-600 mb-1 block">
                    Poin
                </label>

                <input type="number"
                    wire:model.defer="points"
                    placeholder="15"
                    class="w-full px-3 py-2 border rounded-lg text-sm bg-white text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- TOTAL SOAL -->
            <div>
                <label class="text-sm text-gray-600 mb-1 block">
                    Total Soal
                </label>

                <input type="number"
                    wire:model.defer="total_questions"
                    placeholder="15"
                    class="w-full px-3 py-2 border rounded-lg text-sm bg-white text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- BADGE -->
            <div>
                <label class="text-sm text-gray-600 mb-1 block">
                    Badge
                </label>

                <select
                    wire:model="badge"
                    class="w-full px-3 py-2 border rounded-lg text-sm bg-white text-gray-800 focus:ring-2 focus:ring-blue-500"
                >
                    <option value="" class="text-gray-500">
                        -- Pilih Badge --
                    </option>

                    @foreach($badges as $b)
                        <option value="{{ $b['value'] }}">
                            {{ $b['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <!-- BUTTON -->
        <button type="submit"
            wire:loading.attr="disabled"
            class="w-full bg-blue-600 hover:bg-blue-700 transition text-white py-2.5 rounded-lg text-sm font-medium">

            <span wire:loading.remove>+ Tambah Soal Set</span>
            <span wire:loading>Menyimpan...</span>
        </button>

    </form>

    {{-- ================= LIST ================= --}}
    <div class="mt-8 space-y-3">

        @forelse($sets as $set)
            <div class="p-4 bg-white border rounded-xl shadow-sm flex justify-between items-center hover:shadow-md transition">

                <!-- INFO -->
                <div>
                    <p class="font-semibold text-gray-800">
                        {{ $set->title }}
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        {{ $set->section?->title ?? 'Tanpa Section' }}
                        • {{ $set->duration }} menit
                        • {{ $set->points }} poin
                        • {{ $set->total_questions ?? 0 }} soal
                    </p>
                </div>

                <!-- BADGE -->
                @php
                    $badgeMap = [
                        'hots' => ['🔥 HOTS', 'bg-red-100 text-red-600'],
                        'easy' => ['🧠 EASY', 'bg-green-100 text-green-600'],
                        'medium' => ['⚡ MEDIUM', 'bg-yellow-100 text-yellow-700'],
                        'hard' => ['💀 HARD', 'bg-gray-200 text-gray-700'],
                    ];
                @endphp

                @if($set->badge && isset($badgeMap[$set->badge]))
                    <span class="px-3 py-1 text-xs rounded-full {{ $badgeMap[$set->badge][1] }}">
                        {{ $badgeMap[$set->badge][0] }}
                    </span>
                @endif

            </div>
        @empty
            <div class="text-center text-gray-500 text-sm py-6">
                Belum ada soal set
            </div>
        @endforelse

    </div>

</div>