<div>

    <x-button-add :route="route('course.create')" />

    @if (session()->has('success'))
        <div class="mb-4 p-3 text-green-700 bg-green-100 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">

        <table class="w-full text-sm text-left text-body">

            <thead class="text-sm text-body bg-neutral-secondary-medium border-b border-default-medium">
                <tr>
                    <th class="px-6 py-3 font-medium">Nama Kelas</th>
                    <th class="px-6 py-3 font-medium">Deskripsi</th>
                    <th class="px-6 py-3 font-medium text-right">Action</th>
                </tr>
            </thead>

            <tbody>

                @forelse($classes as $class)

                <tr class="bg-neutral-primary-soft border-b border-default hover:bg-neutral-secondary-medium">

                    <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                        {{ $class->name }}
                    </td>

                    <td class="px-6 py-4">
                        <div class="max-w-md whitespace-normal break-words">
                            {{ $class->description }}
                        </div>
                    </td>

                    <td class="px-6 py-4 text-right space-x-3">

                        <a 
                            wire:navigate 
                            href="{{ route('course.edit', $class->id) }}"
                            class="font-medium text-fg-brand hover:underline">
                            Edit
                        </a>

                        <button
                            wire:click="$dispatch('confirmDelete', { id: {{ $class->id }} })"
                            class="font-medium text-red-500 hover:underline">
                            Delete
                        </button>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="3" class="px-6 py-6 text-center text-gray-500">
                        Belum ada data kelas
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>