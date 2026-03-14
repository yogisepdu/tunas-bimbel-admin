<div class="p-6 max-w-7xl mx-auto">

    {{-- FORM CREATE EVENT --}}
    <livewire:kalender.form.index />

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">

        <button wire:click="previousYear"
            class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-700 hover:bg-gray-600 transition">
            ←
        </button>

        <h2 class="text-3xl font-bold tracking-wide">
            Kalender {{ $currentYear }}
        </h2>

        <button wire:click="nextYear"
            class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-700 hover:bg-gray-600 transition">
            →
        </button>

    </div>


    {{-- LEGEND --}}
    <div class="flex gap-6 mb-8 text-sm">

        <div class="flex items-center gap-2">
            <span class="w-3 h-3 bg-blue-500 rounded"></span>
            <span>Agenda Sistem</span>
        </div>

        <div class="flex items-center gap-2">
            <span class="w-3 h-3 bg-red-500 rounded"></span>
            <span>Hari Libur Nasional</span>
        </div>

        <div class="flex items-center gap-2">
            <span class="w-3 h-3 bg-yellow-400 rounded"></span>
            <span>Hari Ini</span>
        </div>

    </div>


    {{-- GRID 12 BULAN --}}
    <div class="grid grid-cols-3 lg:grid-cols-4 gap-8">

        @for ($month = 1; $month <= 12; $month++)

            @php
                $startOfMonth = \Carbon\Carbon::create($currentYear, $month, 1);
                $daysInMonth = $startOfMonth->daysInMonth;
                $startDay = $startOfMonth->dayOfWeek;
            @endphp

            <div class="border border-gray-700 rounded-xl p-4 bg-[#1a1a1a]">

                {{-- NAMA BULAN --}}
                <h3 class="text-center font-semibold mb-3">
                    {{ $startOfMonth->format('F') }}
                </h3>


                {{-- HEADER HARI --}}
                <div class="grid grid-cols-7 text-[10px] text-center text-gray-400 mb-2">
                    <div>M</div>
                    <div>S</div>
                    <div>S</div>
                    <div>R</div>
                    <div>K</div>
                    <div>J</div>
                    <div>S</div>
                </div>


                {{-- GRID TANGGAL --}}
                <div class="grid grid-cols-7 gap-1 text-xs">

                    {{-- EMPTY --}}
                    @for ($i = 0; $i < $startDay; $i++)
                        <div></div>
                    @endfor


                    {{-- DAYS --}}
                    @for ($day = 1; $day <= $daysInMonth; $day++)

                        @php
                            $date = $startOfMonth->copy()->day($day)->format('Y-m-d');
                            $today = now()->format('Y-m-d');

                            $isHoliday = isset($holidays[$date]);
                            $isEvent = isset($events[$date]);
                        @endphp


                        {{-- JIKA ADA EVENT --}}
                        @if($isEvent)

                        <div class="relative group">

                            <div
                                title="{{ $events[$date][0]['title'] }}"
                                class="text-center p-1 rounded bg-blue-500/30 text-blue-300 cursor-pointer">

                                {{ $day }}

                            </div>

                            {{-- DELETE BUTTON --}}
                            <button
                                wire:click="deleteEvent({{ $events[$date][0]['id'] }})"
                                class="hidden group-hover:block absolute -top-2 -right-2 bg-red-600 text-white text-[10px] px-1 rounded">
                                ✕
                            </button>

                        </div>

                        {{-- JIKA TIDAK ADA EVENT --}}
                        @else

                        <div
                            title="{{ $isHoliday ? $holidays[$date] : '' }}"
                            class="text-center p-1 rounded cursor-pointer

                            @if($date == $today)
                                border border-yellow-400 text-yellow-300
                            @elseif($isHoliday)
                                bg-red-500/30 text-red-400 font-semibold
                            @else
                                text-gray-300
                            @endif
                            ">

                            {{ $day }}

                        </div>

                        @endif

                    @endfor

                </div>

            </div>

        @endfor

    </div>

</div>