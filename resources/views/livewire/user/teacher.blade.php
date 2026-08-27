<div>
    {{-- TOMBOL TAMBAH --}}
    <div class="mb-6 flex flex-wrap items-center gap-3">
        <a class="rounded-base bg-brand hover:bg-brand-strong px-4 py-2.5 text-sm font-medium text-white"
            href="{{ route('admin.create') }}" wire:navigate>
            + Tambah User
        </a>
    </div>

    {{-- NOTIFIKASI --}}
    @if (session()->has('success'))
        <div class="mb-6 rounded-lg bg-green-100 p-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 rounded-lg bg-red-100 p-3 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- ===================================================== --}}
    {{-- TABEL AKUN ADMIN --}}
    {{-- ===================================================== --}}
    <section class="mb-10">
        <div class="mb-4">
            <h2 class="text-heading text-xl font-semibold">
                Akun Admin
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Daftar akun admin yang dapat membantu mengelola sistem.
            </p>
        </div>

        <div class="rounded-base border-default bg-neutral-primary-soft shadow-xs relative overflow-x-auto border">
            <table class="text-body w-full text-left text-sm">
                <thead class="border-default-medium bg-neutral-secondary-medium text-body border-b text-sm">
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

                        <th class="px-6 py-3 font-medium">
                            Status Email
                        </th>

                        <th class="px-6 py-3 font-medium">
                            Dibuat
                        </th>

                        <th class="px-6 py-3 text-right font-medium">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($admins as $admin)
                        <tr class="border-default bg-neutral-primary-soft hover:bg-neutral-secondary-medium border-b"
                            wire:key="admin-user-{{ $admin->id }}">
                            <td class="text-heading whitespace-nowrap px-6 py-4 font-medium">
                                {{ $admin->name }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $admin->email }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                                    Admin
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                @if ($admin->email_verified_at)
                                    <span class="text-green-600">
                                        Terverifikasi
                                    </span>
                                @else
                                    <span class="text-yellow-600">
                                        Belum terverifikasi
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                {{ $admin->created_at?->format('d-m-Y H:i') }}
                            </td>

                            <td class="space-x-3 whitespace-nowrap px-6 py-4 text-right">
                                <a class="text-fg-brand font-medium hover:underline"
                                    href="{{ route('admin.edit', [
                                        'id' => $admin->id,
                                    ]) }}"
                                    wire:navigate>
                                    Edit
                                </a>

                                <button class="font-medium text-red-500 hover:underline disabled:opacity-50"
                                    type="button" wire:click="deleteAdmin({{ $admin->id }})"
                                    wire:confirm="Apakah Anda yakin ingin menghapus akun admin ini?"
                                    wire:loading.attr="disabled" wire:target="deleteAdmin({{ $admin->id }})">
                                    <span wire:loading.remove wire:target="deleteAdmin({{ $admin->id }})">
                                        Delete
                                    </span>

                                    <span wire:loading wire:target="deleteAdmin({{ $admin->id }})">
                                        Deleting...
                                    </span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-8 text-center text-gray-500" colspan="6">
                                Tidak ada akun admin.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- ===================================================== --}}
    {{-- TABEL AKUN TEACHER --}}
    {{-- ===================================================== --}}
    <section>
        <div class="mb-4">
            <h2 class="text-heading text-xl font-semibold">
                Akun Teacher
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Daftar akun teacher beserta informasi profilnya.
            </p>
        </div>

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

                            <td class="space-x-3 whitespace-nowrap px-6 py-4 text-right">
                                <a class="text-fg-brand font-medium hover:underline"
                                    href="{{ route('teacher.edit', [
                                        'userId' => $user->id,
                                    ]) }}"
                                    wire:navigate>
                                    Edit
                                </a>

                                <button class="font-medium text-red-500 hover:underline disabled:opacity-50"
                                    type="button" wire:click="deleteTeacher({{ $user->id }})"
                                    wire:confirm="Apakah Anda yakin ingin menghapus akun teacher ini?"
                                    wire:loading.attr="disabled" wire:target="deleteTeacher({{ $user->id }})">
                                    <span wire:loading.remove wire:target="deleteTeacher({{ $user->id }})">
                                        Delete
                                    </span>

                                    <span wire:loading wire:target="deleteTeacher({{ $user->id }})">
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
    </section>
</div>
