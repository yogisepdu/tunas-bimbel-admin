<div>

    {{-- <x-button-add :route="route('video.create')" /> --}}

    @if (session()->has('success'))
        <div class="mb-4 p-3 text-green-700 bg-green-100 rounded">
            {{ session('success') }}
        </div>
    @endif


@forelse($classes as $class)

    <div class="mb-10 bg-neutral-primary-soft shadow-xs rounded-base border border-default">

        <!-- HEADER CLASS -->
        <div class="px-6 py-4 bg-neutral-secondary-medium border-b border-default-medium flex justify-between items-center">

        <h3 class="text-lg font-semibold text-heading">
            {{ $class->name }}
        </h3>

        <div class="flex items-center gap-3">

            <span class="text-xs bg-blue-500/10 text-blue-400 px-2 py-1 rounded">
                {{ $class->chapters->count() }} Chapter
            </span>

            <x-button-add :route="route('video.create')" />

        </div>

    </div>


    <div class="p-6 space-y-8">

    @forelse($class->chapters as $chapter)

        <!-- CHAPTER -->
        <div>

            <h4 class="text-md font-semibold text-heading mb-4">
                {{ $chapter->title }}
            </h4>


            <!-- VIDEO LIST -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @forelse($chapter->videos as $video)

                <div class="bg-neutral-secondary-medium border border-default-medium rounded-base shadow-xs overflow-hidden">

                    <!-- VIDEO PLAYER -->
                    <div class="aspect-video">

                        <iframe
                            class="w-full h-full"
                            src="https://www.youtube.com/embed/{{ $video->youtube_id }}"
                            frameborder="0"
                            allowfullscreen>
                        </iframe>

                    </div>

                    <!-- VIDEO INFO -->
                    <div class="p-4">

                        <h5 class="font-semibold text-heading">
                            {{ $video->title }}
                        </h5>

                        <p class="text-sm text-body mt-1">
                            {{ $video->subtitle }}
                        </p>

                        <!-- ACTION -->
                        <div class="mt-4 flex justify-end gap-3 text-sm">

                            <a 
                                wire:navigate
                                href="{{ route('video.edit', $video->id) }}"
                                class="text-fg-brand hover:underline">
                                Edit
                            </a>

                            <button
                                wire:click="$dispatch('confirmDelete', { id: {{ $video->id }} })"
                                class="text-red-500 hover:underline">
                                Delete
                            </button>

                        </div>

                    </div>

                </div>

                @empty

                    <div class="text-gray-500 text-sm">
                        Belum ada video
                    </div>

                @endforelse

            </div>

        </div>

    @empty

        <div class="text-gray-500 text-sm">
            Belum ada chapter
        </div>

    @endforelse

    </div>

</div>

@empty

<div class="p-6 text-center text-gray-500">
    Belum ada data kelas
</div>

@endforelse

</div>