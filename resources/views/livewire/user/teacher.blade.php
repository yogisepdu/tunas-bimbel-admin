<div>

    <x-button-add :route="route('teacher.create')" />

    @if (session()->has('success'))
        <div class="mb-4 rounded-lg bg-green-100 p-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-base border-default bg-neutral-primary-soft shadow-xs relative overflow-x-auto border">
        <table class="text-body w-full text-left text-sm">

            <thead class="border-default-medium bg-neutral-secondary-medium text-body border-b text-sm">
                <tr>
                    <th class="px-6 py-3 font-medium">
                        Name
                    </th>

                    <th class="px-6 py-3 font-medium">
                        Email
                    </th>

                    <th class="px-6 py-3 font-medium">
                        Phone
                    </th>

                    <th class="px-6 py-3 font-medium">
                        Company
                    </th>

                    <th class="px-6 py-3 font-medium">
                        Specialization
                    </th>

                    <th class="px-6 py-3 font-medium">
                        Experience Years
                    </th>

                    <th class="px-6 py-3 font-medium">
                        Bio
                    </th>

                    <th class="px-6 py-3 text-right font-medium">
                        Action
                    </th>
                </tr>
            </thead>

            <tbody>

                @forelse ($teachers as $user)
                    @php
                        $teacherProfile = $user->teacher;
                    @endphp

                    <tr class="border-default bg-neutral-primary-soft hover:bg-neutral-secondary-medium border-b"
                        wire:key="teacher-user-{{ $user->id }}">

                        <td class="text-heading whitespace-nowrap px-6 py-4 font-medium">
                            {{ $user->name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $user->email }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $teacherProfile?->phone ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $teacherProfile?->company ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $teacherProfile?->specialization ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            @if ($teacherProfile?->experience_years !== null)
                                {{ $teacherProfile->experience_years }} tahun
                            @else
                                -
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            {{ $teacherProfile?->bio ? \Illuminate\Support\Str::limit($teacherProfile->bio, 40) : '-' }}
                        </td>

                        <td class="space-x-3 px-6 py-4 text-right">

                            <a class="text-fg-brand font-medium hover:underline"
                                href="{{ route('teacher.edit', [
                                    'userId' => $user->id,
                                ]) }}"
                                wire:navigate>
                                Edit
                            </a>

                            <button class="font-medium text-red-500 hover:underline disabled:opacity-50" type="button"
                                wire:click="delete({{ $user->id }})"
                                wire:confirm="Apakah Anda yakin ingin menghapus akun teacher ini?"
                                wire:loading.attr="disabled" wire:target="delete({{ $user->id }})">
                                <span wire:loading.remove wire:target="delete({{ $user->id }})">
                                    Delete
                                </span>

                                <span wire:loading wire:target="delete({{ $user->id }})">
                                    Deleting...
                                </span>
                            </button>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td class="px-6 py-8 text-center text-gray-500" colspan="8">
                            Tidak ada akun teacher.
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>
    </div>

</div>
