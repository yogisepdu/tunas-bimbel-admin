<div>

    <x-button-add :route="route('sub-course.create')" />

    @if (session()->has('success'))
        <div class="mb-4 p-3 text-green-700 bg-green-100 rounded">
            {{ session('success') }}
        </div>
    @endif


    @forelse($classes as $class)

    <div class="mb-8 bg-neutral-primary-soft shadow-xs rounded-base border border-default">

        <!-- HEADER KELAS -->
        <div class="px-6 py-4 bg-neutral-secondary-medium border-b border-default-medium flex justify-between items-center">

            <h3 class="text-lg font-semibold text-heading">
                {{ $class->name }}
            </h3>

            <span class="text-xs bg-blue-500/10 text-blue-400 px-2 py-1 rounded">
                {{ $class->chapters->count() }} Chapter
            </span>

        </div>


        <!-- TABLE CHAPTER -->
        <div class="overflow-x-auto">

            <table class="w-full text-sm text-left text-body">

                <thead class="border-b border-default-medium">
                    <tr>
                        <th class="px-6 py-3 font-medium">Judul Chapter</th>
                        <th class="px-6 py-3 font-medium">Deskripsi</th>
                        <th class="px-6 py-3 font-medium text-right">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($class->chapters as $chapter)

                    <tr class="border-b border-default hover:bg-neutral-secondary-medium">

                        <td class="px-6 py-4 font-medium text-heading">
                            {{ $chapter->title }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="max-w-md whitespace-normal break-words">
                                {{ $chapter->description }}
                            </div>
                        </td>

                        <td class="px-6 py-4 text-right space-x-3">

                            <a 
                                wire:navigate
                                href="{{ route('sub-course.edit', $chapter->id) }}"
                                class="text-fg-brand hover:underline">
                                Edit
                            </a>

                            <button
                                wire:click="$dispatch('confirmDelete', { id: {{ $chapter->id }} })"
                                class="font-medium text-red-500 hover:underline">
                                Delete
                            </button>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="3" class="px-6 py-6 text-center text-gray-500">
                            Belum ada chapter
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    @empty

        <div class="p-6 text-center text-gray-500">
            Belum ada data kelas
        </div>

    @endforelse

</div>