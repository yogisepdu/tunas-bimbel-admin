<div>

    @php
        $canManageClass = in_array(auth()->user()->role, ['administrator', 'admin'], true);
    @endphp

    @if ($canManageClass)
        <x-button-add :route="route('course.create')" />
    @endif

    @if (session()->has('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-neutral-primary-soft shadow-xs rounded-base border-default relative overflow-x-auto border">

        <table class="text-body w-full text-left text-sm">

            <thead class="text-body bg-neutral-secondary-medium border-default-medium border-b text-sm">
                <tr>
                    <th class="px-6 py-3 font-medium">Nama Kelas</th>
                    <th class="px-6 py-3 font-medium">Deskripsi</th>
                    <th class="px-6 py-3 text-right font-medium">Action</th>
                </tr>
            </thead>

            <tbody>

                @forelse($classes as $class)
                    <tr class="bg-neutral-primary-soft border-default hover:bg-neutral-secondary-medium border-b">

                        <td class="text-heading whitespace-nowrap px-6 py-4 font-medium">
                            {{ $class->name }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="max-w-md whitespace-normal break-words">
                                {{ $class->description }}
                            </div>
                        </td>

                        <td class="space-x-3 px-6 py-4 text-right">

                            @if ($canManageClass)
                                <a class="text-fg-brand font-medium hover:underline"
                                    href="{{ route('course.edit', $class->id) }}" wire:navigate>
                                    Edit
                                </a>

                                <button class="font-medium text-red-500 hover:underline"
                                    wire:click="$dispatch('confirmDelete', {
                                        id: {{ $class->id }}
                                    })">
                                    Delete
                                </button>
                            @else
                                <span class="text-xs text-gray-500">
                                    Kelas ditugaskan
                                </span>
                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td class="px-6 py-6 text-center text-gray-500" colspan="3">
                            Belum ada data kelas
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>

</div>
