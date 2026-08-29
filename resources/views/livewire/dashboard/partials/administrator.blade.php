<div class="space-y-6">
    {{-- HERO --}}
    <section
        class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-zinc-950 via-indigo-950 to-violet-950 p-6 text-white shadow-xl md:p-8">
        <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-indigo-400/20 blur-3xl"></div>
        <div class="absolute -bottom-20 left-1/3 h-52 w-52 rounded-full bg-violet-400/10 blur-3xl"></div>

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
                    Pantau akun pengguna dan seluruh aktivitas operasional
                    Tunas Bimbel melalui satu dashboard.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a class="rounded-xl bg-white px-4 py-2.5 text-sm font-medium text-zinc-900 shadow-lg hover:bg-zinc-100"
                    href="{{ route('teacher.create') }}" wire:navigate>
                    + Tambah Pengguna
                </a>

                <a class="rounded-xl border border-white/15 bg-white/10 px-4 py-2.5 text-sm font-medium text-white hover:bg-white/20"
                    href="{{ route('course.create') }}" wire:navigate>
                    + Tambah Kelas
                </a>
            </div>
        </div>
    </section>

    {{-- STATISTIK AKUN --}}
    <section>
        <div class="mb-4">
            <h2 class="text-heading text-lg font-semibold">
                Ringkasan Akun
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Informasi akun hanya ditampilkan kepada administrator.
            </p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($accountStats as $stat)
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

    {{-- DATA OPERASIONAL --}}
    <section class="border-default bg-neutral-primary-soft shadow-xs rounded-2xl border p-6">
        <div class="mb-5">
            <h2 class="text-heading text-lg font-semibold">
                Data Operasional
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Ringkasan seluruh konten dan aktivitas pembelajaran.
            </p>
        </div>

        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($administratorSystemStats as $stat)
                <div class="border-default rounded-xl border p-4">
                    <div class="flex items-center gap-3">
                        <span class="h-2.5 w-2.5 rounded-full" style="background: {{ $stat['color'] }};"></span>

                        <p class="text-sm text-gray-500">
                            {{ $stat['label'] }}
                        </p>
                    </div>

                    <p class="text-heading mt-3 text-2xl font-semibold">
                        {{ number_format($stat['value']) }}
                    </p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- CHARTS --}}
    <section class="grid gap-6 xl:grid-cols-3">
        {{-- LINE CHART --}}
        <article class="border-default bg-neutral-primary-soft shadow-xs rounded-2xl border p-6 xl:col-span-2">
            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                <div>
                    <h2 class="text-heading text-lg font-semibold">
                        Pertumbuhan Akun
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Pendaftaran pengguna selama enam bulan terakhir.
                    </p>
                </div>

                <div class="rounded-xl bg-indigo-50 px-4 py-2 text-right">
                    <p class="text-xs text-indigo-500">
                        Bulan ini
                    </p>

                    <p class="font-semibold text-indigo-700">
                        {{ $newUsersThisMonth }} akun baru
                    </p>

                    <p class="{{ $userGrowth >= 0 ? 'text-emerald-600' : 'text-red-600' }} text-xs">
                        {{ $userGrowth >= 0 ? '+' : '' }}{{ $userGrowth }}%
                    </p>
                </div>
            </div>

            <div class="relative mt-8 h-60">
                <div class="absolute inset-0 flex flex-col justify-between">
                    @for ($line = 0; $line < 5; $line++)
                        <div class="border-t border-dashed border-zinc-200"></div>
                    @endfor
                </div>

                <svg class="absolute inset-0 h-full w-full overflow-visible" preserveAspectRatio="none"
                    viewBox="0 0 100 100">
                    <defs>
                        <linearGradient id="accountArea" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="#6366f1" stop-opacity="0.30" />
                            <stop offset="100%" stop-color="#6366f1" stop-opacity="0" />
                        </linearGradient>
                    </defs>

                    <polygon fill="url(#accountArea)"
                        points="0,100
                            @foreach ($monthlyUsers as $month)
                                {{ $month['x'] }},{{ $month['y'] }} @endforeach
                            100,100" />

                    <polyline fill="none"
                        points="
                            @foreach ($monthlyUsers as $month)
                                {{ $month['x'] }},{{ $month['y'] }} @endforeach
                        "
                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="#6366f1"
                        vector-effect="non-scaling-stroke" />

                    @foreach ($monthlyUsers as $month)
                        <circle cx="{{ $month['x'] }}" cy="{{ $month['y'] }}" fill="#ffffff" r="2"
                            stroke-width="1.5" stroke="#6366f1" vector-effect="non-scaling-stroke" />
                    @endforeach
                </svg>
            </div>

            <div class="mt-4 grid grid-cols-6 gap-2">
                @foreach ($monthlyUsers as $month)
                    <div class="text-center">
                        <p class="text-heading text-sm font-semibold">
                            {{ $month['total'] }}
                        </p>

                        <p class="text-xs text-gray-500">
                            {{ $month['label'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </article>

        {{-- DONUT --}}
        <article class="border-default bg-neutral-primary-soft shadow-xs rounded-2xl border p-6">
            <h2 class="text-heading text-lg font-semibold">
                Distribusi Role
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Komposisi akun berdasarkan hak akses.
            </p>

            <div class="my-7 flex justify-center">
                <div class="flex h-44 w-44 items-center justify-center rounded-full"
                    style="background: {{ $roleChartGradient }};">
                    <div
                        class="bg-neutral-primary-soft flex h-28 w-28 flex-col items-center justify-center rounded-full">
                        <span class="text-heading text-2xl font-semibold">
                            {{ number_format($roleDistribution->sum('total')) }}
                        </span>

                        <span class="text-xs text-gray-500">
                            Akun
                        </span>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                @foreach ($roleDistribution as $item)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="h-2.5 w-2.5 rounded-full" style="background: {{ $item['color'] }};"></span>

                            <span class="text-sm text-gray-600">
                                {{ $item['label'] }}
                            </span>
                        </div>

                        <div class="text-right">
                            <span class="text-heading font-semibold">
                                {{ $item['total'] }}
                            </span>

                            <span class="ml-1 text-xs text-gray-400">
                                {{ $item['percentage'] }}%
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </article>
    </section>

    {{-- AKUN TERBARU --}}
    <section class="border-default bg-neutral-primary-soft shadow-xs overflow-hidden rounded-2xl border">
        <div class="border-default flex items-center justify-between border-b px-6 py-5">
            <div>
                <h2 class="text-heading text-lg font-semibold">
                    Akun Terbaru
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Pengguna yang baru ditambahkan.
                </p>
            </div>

            <a class="text-sm font-medium text-indigo-600 hover:underline" href="{{ route('teacher.index') }}"
                wire:navigate>
                Kelola akun
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-neutral-secondary-medium text-gray-500">
                    <tr>
                        <th class="px-6 py-3 font-medium">
                            Nama
                        </th>

                        <th class="px-6 py-3 font-medium">
                            Email
                        </th>

                        <th class="px-6 py-3 font-medium">
                            Role
                        </th>

                        <th class="px-6 py-3 text-right font-medium">
                            Ditambahkan
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($recentUsers as $recentUser)
                        <tr class="border-default border-t">
                            <td class="text-heading px-6 py-4 font-medium">
                                {{ $recentUser->name }}
                            </td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ $recentUser->email }}
                            </td>

                            <td class="px-6 py-4">
                                {{ ucfirst($recentUser->role) }}
                            </td>

                            <td class="px-6 py-4 text-right text-gray-500">
                                {{ $recentUser->created_at ? $recentUser->created_at->locale('id')->diffForHumans() : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-10 text-center text-gray-500" colspan="4">
                                Belum ada akun.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
