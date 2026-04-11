<div>

    {{-- BUTTON --}}
    <x-button-add :route="route('packages.create')" />

    {{-- ALERT --}}
    @if (session()->has('success'))
        <div class="mb-4 p-3 text-green-700 bg-green-100 rounded">
            {{ session('success') }}
        </div>
    @endif


    {{-- LOOP PACKAGES --}}
    @forelse($packages as $package)

    <div class="mb-10 border border-default rounded-lg overflow-hidden bg-neutral-primary-soft">

        <!-- HEADER PACKAGE -->
        <div class="px-6 py-4 bg-green-900/40 border-b border-default flex justify-between items-center">

            <div class="flex items-center gap-4">

                {{-- IMAGE --}}
                <div class="w-14 h-14 rounded-lg overflow-hidden bg-gray-700 flex-shrink-0">
                    @if($package->image)
                        <img 
                            src="{{ asset('storage/' . $package->image) }}"
                            class="w-full h-full object-cover"
                        >
                    @else
                        {{-- fallback --}}
                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">
                            No Img
                        </div>
                    @endif
                </div>

                {{-- TEXT --}}
                <div>
                    <h2 class="text-xl font-semibold text-white">
                        {{ $package->name }}
                    </h2>

                    <p class="text-xs text-gray-400">
                        Paket
                    </p>
                </div>

            </div>

            <div class="flex items-center gap-3">

                {{-- INFO --}}
                <span class="text-xs bg-green-500/20 text-green-400 px-3 py-1 rounded-full">
                    {{ $package->classes->count() }} Kelas
                </span>

                <span class="text-xs bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full">
                    Rp {{ number_format($package->price) }}
                </span>

                {{-- ACTION --}}
                <div class="flex items-center gap-2 ml-4">

                    {{-- EDIT --}}
                    <a 
                        wire:navigate
                        href="{{ route('packages.edit', $package->id) }}"
                        class="text-blue-400 hover:underline text-xs">
                        Edit
                    </a>

                    {{-- DELETE --}}
                    <button
                        wire:click="$dispatch('confirmDelete', { id: {{ $package->id }} })"
                        class="text-red-400 hover:underline text-xs">
                        Delete
                    </button>

                </div>

            </div>

        </div>


        {{-- LOOP CLASSES --}}
        @foreach($package->classes as $class)

        <div class="border-b border-default-medium">

            <!-- HEADER CLASS -->
            <div class="px-8 py-3 bg-neutral-secondary-medium flex justify-between items-center">

                <div>
                    <h4 class="font-medium text-heading">
                        {{ $class->name }}
                    </h4>

                    <p class="text-xs text-gray-400">
                        Kelas
                    </p>
                </div>

                <span class="text-xs bg-blue-500/20 text-blue-400 px-2 py-1 rounded">
                    {{ $class->chapters->count() }} Chapter
                </span>

            </div>
        </div>

        @endforeach

    </div>

    @empty

        <div class="p-6 text-center text-gray-500">
            Belum ada paket
        </div>

    @endforelse

</div>