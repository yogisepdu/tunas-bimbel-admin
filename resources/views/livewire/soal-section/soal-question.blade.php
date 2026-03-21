<div class="p-6 max-w-7xl mx-auto space-y-6">

    {{-- ADD BUTTON --}}
    <div class="flex flex-wrap gap-3">

        {{-- ➕ TAMBAH SOAL --}}
        <a 
            wire:navigate
            href="{{ route('soal-question.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 5v14M5 12h14"/>
            </svg>

            Tambah Soal
        </a>

        {{-- 📊 IMPORT EXCEL --}}
        <a 
            wire:navigate
            href="{{ route('soal.import') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 16v-8M8 12l4-4 4 4"/>
                <path d="M4 20h16"/>
            </svg>

            Import Excel
        </a>

        <a 
            href="{{ route('soal.template') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 16v-8M8 12l4-4 4 4"/>
                <path d="M4 20h16"/>
            </svg>

            Download Template
        </a>

    </div>

    {{-- SUCCESS --}}
    @if (session()->has('success'))
        <div class="p-3 text-sm text-green-700 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- ================= SECTION LOOP ================= --}}
    @forelse($sections as $section)

        <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">

            {{-- HEADER SECTION --}}
            <div class="px-6 py-4 bg-gray-50 border-b flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">
                    {{ $section->title }}
                </h2>

                <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded-full">
                    {{ $section->sets->count() }} Set
                </span>
            </div>

            {{-- ================= SET LOOP ================= --}}
            <div class="divide-y">

                @forelse($section->sets as $set)

                    <div class="p-5 space-y-4">

                        {{-- SET INFO --}}
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-semibold text-gray-800">
                                    {{ $set->title }}
                                </h3>

                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $set->duration }} menit • 
                                    {{ $set->points }} poin • 
                                    {{ $set->total_questions }} soal
                                </p>
                            </div>

                            {{-- BADGE --}}
                            @if($set->badge)
                                <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">
                                    {{ strtoupper($set->badge) }}
                                </span>
                            @endif
                        </div>

                        {{-- ================= QUESTIONS ================= --}}
                        @if($set->questions->count())

                            <div class="space-y-3">

                                @foreach($set->questions as $index => $q)

                                    <div class="border rounded-xl p-4 bg-gray-50">

                                        {{-- QUESTION HEADER --}}
                                        <div class="flex justify-between items-start">

                                            <p class="font-medium text-gray-800">
                                                {{ $index + 1 }}. {{ $q->question }}
                                            </p>

                                            <button
                                                wire:click="delete({{ $q->id }})"
                                                class="text-xs text-red-500 hover:text-red-700">
                                                Hapus
                                            </button>

                                        </div>

                                        {{-- OPTIONS --}}
                                        <div class="mt-3 space-y-1">

                                            @foreach($q->options as $opt)
                                                <div class="px-3 py-2 rounded-lg flex justify-between items-center
                                                    {{ $opt->key === $q->correct_answer 
                                                        ? 'bg-green-100 border border-green-300' 
                                                        : 'bg-white border' }}">

                                                    <span class="text-sm text-gray-800">
                                                        {{ $opt->key }}. {{ $opt->text }}
                                                    </span>

                                                    @if($opt->key === $q->correct_answer)
                                                        <span class="text-xs text-green-600 font-semibold">
                                                            ✔ Benar
                                                        </span>
                                                    @endif

                                                </div>
                                            @endforeach

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @else
                            <div class="text-sm text-gray-400">
                                Belum ada soal
                            </div>
                        @endif

                    </div>

                @empty
                    <div class="p-4 text-sm text-gray-400">
                        Belum ada soal set
                    </div>
                @endforelse

            </div>

        </div>

    @empty
        <div class="text-center text-gray-500 py-10">
            Belum ada data soal
        </div>
    @endforelse

</div>