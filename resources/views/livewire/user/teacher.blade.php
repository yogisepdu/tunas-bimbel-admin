<div>

    <x-button-add :route="route('teacher.create')" />

    @if (session()->has('success'))
        <div class="mb-4 p-3 text-green-700 bg-green-100 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">

        <table class="w-full text-sm text-left text-body">

            <thead class="text-sm text-body bg-neutral-secondary-medium border-b border-default-medium">
                <tr>
                    <th class="px-6 py-3 font-medium">Name</th>
                    <th class="px-6 py-3 font-medium">Email</th>
                    <th class="px-6 py-3 font-medium">Phone</th>
                    <th class="px-6 py-3 font-medium">Company</th>
                    <th class="px-6 py-3 font-medium">Specialization</th>
                    <th class="px-6 py-3 font-medium">Experience Years</th>
                    <th class="px-6 py-3 font-medium">Bio</th>
                    <th class="px-6 py-3 font-medium text-right">Action</th>
                </tr>
            </thead>

            <tbody>

                @forelse($teachers as $teacher)

                    <tr class="bg-neutral-primary-soft border-b border-default hover:bg-neutral-secondary-medium">

                        <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                            {{ $teacher->user->name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $teacher->user->email }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $teacher->phone }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $teacher->company }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $teacher->specialization }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $teacher->experience_years }} years
                        </td>

                        <td class="px-6 py-4">
                            {{ Str::limit($teacher->bio, 40) }}
                        </td>

                        <td class="px-6 py-4 text-right space-x-3">

                            <a wire:navigate href="{{ route('teacher.edit', $teacher->id) }}"
                                class="font-medium text-fg-brand hover:underline">
                                Edit
                            </a>

                            <button
                                wire:click="delete({{ $teacher->id }})"
                                wire:loading.attr="disabled"
                                class="font-medium text-red-500 hover:underline">

                                <span wire:loading.remove wire:target="delete({{ $teacher->id }})">
                                    Delete
                                </span>

                                <span wire:loading wire:target="delete({{ $teacher->id }})">
                                    Deleting...
                                </span>

                            </button>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="8" class="px-6 py-6 text-center text-gray-500">
                            No teachers found
                        </td>
                    </tr>

                    @endforelse

            </tbody>

        </table>
    </div>

</div>