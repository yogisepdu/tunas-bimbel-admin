<div>

    <x-button-add :route="route('student.create')" />

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
                    <th class="px-6 py-3 font-medium">School</th>
                    <th class="px-6 py-3 font-medium">Grade</th>
                    <th class="px-6 py-3 font-medium text-right">Action</th>
                </tr>
            </thead>

            <tbody>

                @forelse($students as $student)

                <tr class="bg-neutral-primary-soft border-b border-default hover:bg-neutral-secondary-medium">

                    <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                        {{ $student->user->name }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $student->user->email }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $student->phone }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $student->school }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $student->grade }}
                    </td>

                    <td class="px-6 py-4 text-right space-x-3">

                        <a wire:navigate href="{{ route('student.edit', $student->id) }}"
                            class="font-medium text-fg-brand hover:underline">
                                Edit
                        </a>

                        <button
                            wire:click="delete({{ $student->id }})"
                            wire:loading.attr="disabled"
                            class="font-medium text-red-500 hover:underline">

                            <span wire:loading.remove wire:target="delete({{ $student->id }})">
                            Delete
                            </span>

                            <span wire:loading wire:target="delete({{ $student->id }})">
                            Deleting...
                            </span>

                        </button>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6" class="px-6 py-6 text-center text-gray-500">
                        No students found
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>
    </div>

</div>