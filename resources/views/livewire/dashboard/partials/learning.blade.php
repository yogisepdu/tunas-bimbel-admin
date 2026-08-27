<div class="space-y-6">
    {{-- HERO --}}
    <section
        class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-zinc-950 via-slate-900 to-emerald-950 p-6 text-white shadow-xl md:p-8">
        <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-emerald-400/20 blur-3xl"></div>

        <div class="relative z-10 flex flex-col justify-between gap-6 lg:flex-row lg:items-center">
            <div>
                <div
                    class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1.5 text-xs text-zinc-200">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    {{ $currentDate }}
                </div>

                <h1 class="text-3xl font-semibold tracking-tight md:text-4xl">
                    {{ $greeting }}, {{ $displayName }}
                </h1>

                <p class="mt-3 max-w-2xl text-sm leading-6 text-zinc-300">
                    @if ($isTeacher)
                        Berikut ringkasan kelas dan materi pembelajaran
                        yang ditugaskan kepada Anda.
                    @else
                        Berikut ringkasan seluruh aktivitas pembelajaran
                        dan peserta Tunas Bimbel.
                    @endif
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                @if ($isAdmin)
                    <a class="rounded-xl bg-white px-4 py-2.5 text-sm font-medium text-zinc-900 hover:bg-zinc-100"
                        href="{{ route('course.create') }}" wire:navigate>
                        + Tambah Kelas
                    </a>
                @endif

                <a class="rounded-xl border border-white/15 bg-white/10 px-4 py-2.5 text-sm font-medium text-white hover:bg-white/20"
                    href="{{ route('sub-course.create') }}" wire:navigate>
                    + Tambah Materi
                </a>

                <a class="rounded-xl border border-white/15 bg-white/10 px-4 py-2.5 text-sm font-medium text-white hover:bg-white/20"
                    href="{{ route('quiz.create') }}" wire:navigate>
                    + Tambah Quiz
                </a>
            </div>
        </div>
    </section>

    {{-- STATISTIK PEMBELAJARAN --}}
    <section>
        <div class="mb-4">
            <h2 class="text-heading text-lg font-semibold">
                Ringkasan Pembelajaran
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                @if ($isTeacher)
                    Hanya menampilkan data dari kelas yang ditugaskan.
                @else
                    Menampilkan data dari seluruh kelas.
                @endif
            </p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($learningStats as $stat)
                <article
                    class="border-default bg-neutral-primary-soft shadow-xs rounded-2xl border p-5 transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                {{ $stat['label'] }}
                            </p>

                            <p class="text-heading mt-3 text-3xl font-semibold">
                                {{ number_format($stat['value']) }}
                            </p>
                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl"
                            style="
                                color: {{ $stat['color'] }};
                                background: {{ $stat['background'] }};
                            ">
                            <span class="h-3 w-3 rounded-full bg-current"></span>
                        </div>
                    </div>

                    <p class="mt-4 text-xs text-gray-500">
                        {{ $stat['description'] }}
                    </p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-2">
        {{-- HORIZONTAL CHART --}}
        <article class="border-default bg-neutral-primary-soft shadow-xs rounded-2xl border p-6">
            <div class="mb-6">
                <h2 class="text-heading text-lg font-semibold">
                    Perbandingan Konten
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Perbandingan jumlah konten pembelajaran.
                </p>
            </div>

            <div class="space-y-5">
                @foreach ($contentChartData as $item)
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <span class="text-sm text-gray-600">
                                {{ $item['label'] }}
                            </span>

                            <span class="text-heading text-sm font-semibold">
                                {{ number_format($item['total']) }}
                            </span>
                        </div>

                        <div class="h-2.5 overflow-hidden rounded-full bg-zinc-100">
                            <div class="h-full rounded-full transition-all duration-500"
                                style="
                                    width: {{ $item['percentage'] }}%;
                                    background: {{ $item['color'] }};
                                ">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </article>

        {{-- KELAS TERBARU --}}
        <article class="border-default bg-neutral-primary-soft shadow-xs rounded-2xl border p-6">
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <h2 class="text-heading text-lg font-semibold">
                        {{ $isTeacher ? 'Kelas Ditugaskan' : 'Kelas Terbaru' }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Daftar kelas yang dapat Anda akses.
                    </p>
                </div>

                <a class="text-sm font-medium text-indigo-600 hover:underline" href="{{ route('course.index') }}"
                    wire:navigate>
                    Lihat semua
                </a>
            </div>

            <div class="space-y-3">
                @forelse ($latestClasses as $class)
                    <a class="border-default hover:bg-neutral-secondary-medium block rounded-xl border p-4 transition"
                        href="{{ route('course.index') }}" wire:navigate>
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="text-heading truncate font-medium">
                                    {{ $class->name }}
                                </p>

                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $class->description ? \Illuminate\Support\Str::limit($class->description, 80) : 'Belum ada deskripsi kelas.' }}
                                </p>
                            </div>

                            <span class="shrink-0 text-xs text-gray-400">
                                {{ $class->created_at ? $class->created_at->format('d/m/Y') : '-' }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="border-default rounded-xl border border-dashed px-5 py-10 text-center">
                        <p class="text-heading text-sm font-medium">
                            Belum ada kelas
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            @if ($isTeacher)
                                Anda belum ditugaskan pada kelas mana pun.
                            @else
                                Tambahkan kelas pertama untuk memulai.
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>
        </article>
    </section>
</div>
