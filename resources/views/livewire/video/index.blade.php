<div>

    {{-- =========================================================
        FLASH MESSAGE
    ========================================================== --}}
    @if (session()->has('success'))
        <div
            class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/30 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif


    {{-- =========================================================
        CLASSES
    ========================================================== --}}
    @forelse ($classes as $class)

        <div class="border-default bg-neutral-primary-soft shadow-xs mb-10 overflow-hidden rounded-2xl border">

            {{-- =================================================
                CLASS HEADER
            ================================================== --}}
            <div
                class="border-default-medium bg-neutral-secondary-medium flex flex-col gap-4 border-b px-6 py-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <h3 class="text-heading text-lg font-semibold">
                        {{ $class->name }}
                    </h3>

                    <p class="text-body mt-1 text-xs">
                        Daftar video pembelajaran berdasarkan sub materi.
                    </p>
                </div>


                <div class="flex items-center gap-3">

                    <span class="rounded-full bg-blue-500/10 px-3 py-1 text-xs font-semibold text-blue-500">
                        {{ $class->chapters->count() }}
                        Chapter
                    </span>

                    <x-button-add :route="route('video.create')" />

                </div>

            </div>


            {{-- =================================================
                CHAPTERS
            ================================================== --}}
            <div class="space-y-10 p-6">

                @forelse ($class->chapters as $chapter)
                    <section>

                        {{-- CHAPTER HEADER --}}
                        <div class="mb-5 flex items-center justify-between gap-4">

                            <div>
                                <h4 class="text-heading text-base font-semibold">
                                    {{ $chapter->title }}
                                </h4>

                                <p class="text-body mt-1 text-xs">
                                    {{ $chapter->videos->count() }}
                                    video tersedia
                                </p>
                            </div>

                        </div>


                        {{-- =====================================
                            VIDEO LIST
                        ====================================== --}}
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">

                            @forelse ($chapter->videos as $video)
                                @php
                                    /*
                                    |--------------------------------------------------------------------------
                                    | SOURCE TYPE FALLBACK
                                    |--------------------------------------------------------------------------
                                    |
                                    | Untuk data lama yang belum mempunyai source_type:
                                    | - kalau youtube_id ada => youtube
                                    | - kalau video_path ada => private_file
                                    |
                                    */

                                    $sourceType =
                                        $video->source_type ?:
                                        (!empty($video->youtube_id)
                                            ? 'youtube'
                                            : 'private_file');

                                    $isYoutube = $sourceType === 'youtube';

                                    $isPrivate = $sourceType === 'private_file';

                                    /*
                                    |--------------------------------------------------------------------------
                                    | FILE SIZE
                                    |--------------------------------------------------------------------------
                                    */

                                    $videoSize = null;

                                    if ($video->video_size && $video->video_size > 0) {
                                        $sizeInMb = $video->video_size / 1024 / 1024;

                                        $videoSize = number_format($sizeInMb, 2, ',', '.') . ' MB';
                                    }
                                @endphp


                                <article
                                    class="border-default-medium bg-neutral-secondary-medium shadow-xs group overflow-hidden rounded-2xl border transition hover:-translate-y-0.5 hover:shadow-md"
                                    wire:key="video-{{ $video->id }}">

                                    {{-- =================================
                                        VIDEO PLAYER
                                    ================================== --}}
                                    <div class="relative aspect-video overflow-hidden bg-black">

                                        {{-- =============================
                                            YOUTUBE
                                        ============================== --}}
                                        @if ($isYoutube)
                                            @if (!empty($video->youtube_id))
                                                <iframe
                                                    allow="
                                                        accelerometer;
                                                        autoplay;
                                                        clipboard-write;
                                                        encrypted-media;
                                                        gyroscope;
                                                        picture-in-picture;
                                                        web-share
                                                    "
                                                    allowfullscreen class="h-full w-full" frameborder="0" loading="lazy"
                                                    referrerpolicy="strict-origin-when-cross-origin"
                                                    src="https://www.youtube.com/embed/{{ $video->youtube_id }}"
                                                    title="{{ $video->title }}"></iframe>
                                            @else
                                                <div
                                                    class="flex h-full w-full flex-col items-center justify-center gap-2 bg-zinc-900 text-zinc-400">
                                                    <span class="text-sm font-semibold">
                                                        YouTube ID tidak tersedia
                                                    </span>
                                                </div>
                                            @endif


                                            {{-- =============================
                                            PRIVATE FILE
                                        ============================== --}}
                                        @elseif ($isPrivate)
                                            @if (!empty($video->video_path))
                                                <video class="h-full w-full bg-black object-contain" controls
                                                    controlsList="
                                                        nodownload
                                                        noremoteplayback
                                                    "
                                                    disablePictureInPicture preload="metadata">
                                                    <source
                                                        src="{{ route('video.preview', [
                                                            'video' => $video->id,
                                                        ]) }}"
                                                        type="{{ $video->video_mime_type ?: 'video/mp4' }}">

                                                    Browser Anda tidak mendukung
                                                    pemutar video HTML5.
                                                </video>
                                            @else
                                                <div
                                                    class="flex h-full w-full flex-col items-center justify-center gap-2 bg-zinc-900 text-zinc-400">

                                                    <svg class="h-8 w-8" fill="none" stroke-width="1.5"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="
                                                                m15.75 10.5
                                                                4.72-4.72
                                                                a.75.75 0 0 1
                                                                1.28.53v11.38
                                                                a.75.75 0 0 1
                                                                -1.28.53l-4.72-4.72
                                                                M4.5 18.75h9
                                                                a2.25 2.25 0 0 0
                                                                2.25-2.25v-9
                                                                A2.25 2.25 0 0 0
                                                                13.5 5.25h-9
                                                                A2.25 2.25 0 0 0
                                                                2.25 7.5v9
                                                                A2.25 2.25 0 0 0
                                                                4.5 18.75Z
                                                            " stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>

                                                    <span class="text-sm font-semibold">
                                                        File video tidak tersedia
                                                    </span>

                                                </div>
                                            @endif


                                            {{-- =============================
                                            UNKNOWN SOURCE
                                        ============================== --}}
                                        @else
                                            <div
                                                class="flex h-full w-full items-center justify-center bg-zinc-900 px-5 text-center text-sm font-medium text-zinc-400">
                                                Source video tidak dikenali
                                            </div>
                                        @endif


                                        {{-- =================================
                                            SOURCE BADGE
                                        ================================== --}}
                                        <div class="absolute left-3 top-3 z-10">

                                            @if ($isYoutube)
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full bg-red-600 px-2.5 py-1 text-[10px] font-bold text-white shadow">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-white"></span>

                                                    YOUTUBE
                                                </span>
                                            @elseif ($isPrivate)
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full bg-violet-600 px-2.5 py-1 text-[10px] font-bold text-white shadow">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-white"></span>

                                                    PRIVATE
                                                </span>
                                            @endif

                                        </div>

                                    </div>


                                    {{-- =================================
                                        VIDEO INFORMATION
                                    ================================== --}}
                                    <div class="p-5">

                                        {{-- TITLE --}}
                                        <h5 class="text-heading line-clamp-2 font-semibold">
                                            {{ $video->title }}
                                        </h5>


                                        {{-- SUBTITLE --}}
                                        @if ($video->subtitle)
                                            <p class="text-body mt-1 line-clamp-2 text-sm">
                                                {{ $video->subtitle }}
                                            </p>
                                        @endif


                                        {{-- =================================
                                            META
                                        ================================== --}}
                                        <div class="mt-4 flex flex-wrap items-center gap-2">

                                            {{-- SOURCE TYPE --}}
                                            @if ($isYoutube)
                                                <span
                                                    class="rounded-lg bg-red-500/10 px-2 py-1 text-[11px] font-semibold text-red-500">
                                                    Source: YouTube
                                                </span>
                                            @elseif ($isPrivate)
                                                <span
                                                    class="rounded-lg bg-violet-500/10 px-2 py-1 text-[11px] font-semibold text-violet-500">
                                                    Source: Private File
                                                </span>
                                            @endif


                                            {{-- FILE SIZE --}}
                                            @if ($isPrivate && $videoSize)
                                                <span
                                                    class="rounded-lg bg-zinc-500/10 px-2 py-1 text-[11px] font-medium text-zinc-500">
                                                    {{ $videoSize }}
                                                </span>
                                            @endif


                                            {{-- MIME --}}
                                            @if ($isPrivate && $video->video_mime_type)
                                                <span
                                                    class="rounded-lg bg-zinc-500/10 px-2 py-1 text-[11px] font-medium text-zinc-500">
                                                    {{ $video->video_mime_type }}
                                                </span>
                                            @endif

                                        </div>


                                        {{-- =================================
                                            SOURCE DETAIL
                                        ================================== --}}
                                        <div
                                            class="border-default-medium bg-neutral-primary-soft mt-4 rounded-xl border p-3">

                                            @if ($isYoutube)
                                                <div class="text-body text-[11px]">
                                                    <span class="text-heading font-semibold">
                                                        YouTube ID:
                                                    </span>

                                                    <span class="ml-1 break-all">
                                                        {{ $video->youtube_id ?: '-' }}
                                                    </span>
                                                </div>
                                            @elseif ($isPrivate)
                                                <div class="text-body text-[11px]">
                                                    <span class="text-heading font-semibold">
                                                        Private Path:
                                                    </span>

                                                    <span class="ml-1 break-all">
                                                        {{ $video->video_path ?: '-' }}
                                                    </span>
                                                </div>
                                            @endif

                                        </div>


                                        {{-- =================================
                                            ACTION
                                        ================================== --}}
                                        <div
                                            class="border-default-medium mt-5 flex items-center justify-between gap-3 border-t pt-4">

                                            {{-- STATUS --}}
                                            <div class="text-body text-xs">
                                                ID:
                                                <span class="text-heading font-semibold">
                                                    #{{ $video->id }}
                                                </span>
                                            </div>


                                            <div class="flex items-center gap-3 text-sm">

                                                <a class="text-fg-brand font-medium hover:underline"
                                                    href="{{ route('video.edit', $video->id) }}"
                                                    wire:navigate>
                                                    Edit
                                                </a>


                                                <button class="font-medium text-red-500 hover:underline" type="button"
                                                    wire:click="
                                                        $dispatch(
                                                            'confirmDelete',
                                                            {
                                                                id:
                                                                {{ $video->id }}
                                                            }
                                                        )
                                                    ">
                                                    Delete
                                                </button>

                                            </div>

                                        </div>

                                    </div>

                                </article>

                            @empty

                                <div
                                    class="border-default-medium col-span-full rounded-xl border border-dashed px-6 py-10 text-center">
                                    <p class="text-sm text-gray-500">
                                        Belum ada video pada chapter ini.
                                    </p>
                                </div>
                            @endforelse

                        </div>

                    </section>

                @empty

                    <div
                        class="border-default-medium rounded-xl border border-dashed p-8 text-center text-sm text-gray-500">
                        Belum ada chapter pada kelas ini.
                    </div>
                @endforelse

            </div>

        </div>

    @empty

        <div class="border-default-medium rounded-2xl border border-dashed p-10 text-center text-gray-500">
            Belum ada data kelas.
        </div>

    @endforelse

</div>
