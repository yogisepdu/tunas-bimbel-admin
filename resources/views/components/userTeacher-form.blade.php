@props([
    'role' => 'teacher',
])

<form wire:submit.prevent="save">
    {{-- PILIH JENIS AKUN --}}
    <div class="mb-6">
        <label class="text-heading mb-3 block text-sm font-medium">
            Jenis Akun
        </label>

        <div class="grid gap-4 md:grid-cols-2">
            {{-- ADMIN --}}
            <label
                class="rounded-base border-default-medium hover:bg-neutral-secondary-medium flex cursor-pointer items-start gap-3 border p-4">
                <input class="mt-1 h-4 w-4" type="radio" value="admin" wire:model.live="role">

                <div>
                    <p class="text-heading font-medium">
                        Admin
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Dapat mengelola kelas, materi, paket,
                        kalender, tautan, dan pengumuman.
                    </p>
                </div>
            </label>

            {{-- TEACHER --}}
            <label
                class="rounded-base border-default-medium hover:bg-neutral-secondary-medium flex cursor-pointer items-start gap-3 border p-4">
                <input class="mt-1 h-4 w-4" type="radio" value="teacher" wire:model.live="role">

                <div>
                    <p class="text-heading font-medium">
                        Teacher
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Dapat mengelola materi pembelajaran
                        pada kelas yang ditugaskan.
                    </p>
                </div>
            </label>
        </div>

        @error('role')
            <span class="mt-2 block text-sm text-red-500">
                {{ $message }}
            </span>
        @enderror
    </div>

    {{-- DATA AKUN --}}
    <div class="mb-6">
        <h2 class="text-heading mb-4 text-lg font-semibold">
            Informasi Akun
        </h2>

        <div class="grid gap-6 md:grid-cols-2">
            {{-- NAME --}}
            <div>
                <label class="text-heading mb-2.5 block text-sm font-medium">
                    Full Name
                </label>

                <input
                    class="rounded-base border-default-medium bg-neutral-secondary-medium text-heading shadow-xs focus:border-brand focus:ring-brand block w-full border px-3 py-2.5 text-sm"
                    placeholder="John Doe" type="text" wire:model.defer="name">

                @error('name')
                    <span class="mt-1 block text-sm text-red-500">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="text-heading mb-2.5 block text-sm font-medium">
                    Email
                </label>

                <input
                    class="rounded-base border-default-medium bg-neutral-secondary-medium text-heading shadow-xs focus:border-brand focus:ring-brand block w-full border px-3 py-2.5 text-sm"
                    placeholder="{{ $role === 'teacher' ? 'teacher@email.com' : 'admin@email.com' }}"
                    type="email" wire:model.defer="email">

                @error('email')
                    <span class="mt-1 block text-sm text-red-500">
                        {{ $message }}
                    </span>
                @enderror
            </div>
        </div>
    </div>

    {{-- DATA KHUSUS TEACHER --}}
    @if ($role === 'teacher')
        <div class="rounded-base border-default bg-neutral-primary-soft mb-6 border p-5"
            wire:key="teacher-profile-fields">
            <div class="mb-5">
                <h2 class="text-heading text-lg font-semibold">
                    Profil Teacher
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Informasi berikut hanya diperlukan untuk akun teacher.
                </p>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                {{-- PHONE --}}
                <div>
                    <label class="text-heading mb-2.5 block text-sm font-medium">
                        Phone
                    </label>

                    <input
                        class="rounded-base border-default-medium bg-neutral-secondary-medium text-heading shadow-xs focus:border-brand focus:ring-brand block w-full border px-3 py-2.5 text-sm"
                        placeholder="08123456789" type="text" wire:model.defer="phone">

                    @error('phone')
                        <span class="mt-1 block text-sm text-red-500">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- COMPANY --}}
                <div>
                    <label class="text-heading mb-2.5 block text-sm font-medium">
                        Company / Institution
                    </label>

                    <input
                        class="rounded-base border-default-medium bg-neutral-secondary-medium text-heading shadow-xs focus:border-brand focus:ring-brand block w-full border px-3 py-2.5 text-sm"
                        placeholder="Universitas / Sekolah / Freelance" type="text" wire:model.defer="company">

                    @error('company')
                        <span class="mt-1 block text-sm text-red-500">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- SPECIALIZATION --}}
                <div>
                    <label class="text-heading mb-2.5 block text-sm font-medium">
                        Specialization
                    </label>

                    <input
                        class="rounded-base border-default-medium bg-neutral-secondary-medium text-heading shadow-xs focus:border-brand focus:ring-brand block w-full border px-3 py-2.5 text-sm"
                        placeholder="Mathematics, Physics, English" type="text" wire:model.defer="specialization">

                    @error('specialization')
                        <span class="mt-1 block text-sm text-red-500">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- EXPERIENCE --}}
                <div>
                    <label class="text-heading mb-2.5 block text-sm font-medium">
                        Experience (Years)
                    </label>

                    <input
                        class="rounded-base border-default-medium bg-neutral-secondary-medium text-heading shadow-xs focus:border-brand focus:ring-brand block w-full border px-3 py-2.5 text-sm"
                        max="100" min="0" placeholder="5" type="number"
                        wire:model.defer="experience_years">

                    @error('experience_years')
                        <span class="mt-1 block text-sm text-red-500">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>

            {{-- BIO --}}
            <div class="mt-6">
                <label class="text-heading mb-2.5 block text-sm font-medium">
                    Bio
                </label>

                <textarea
                    class="rounded-base border-default-medium bg-neutral-secondary-medium text-heading shadow-xs focus:border-brand focus:ring-brand block w-full border px-3 py-2.5 text-sm"
                    placeholder="Short biography about teacher" rows="4" wire:model.defer="bio"></textarea>

                @error('bio')
                    <span class="mt-1 block text-sm text-red-500">
                        {{ $message }}
                    </span>
                @enderror
            </div>
        </div>
    @endif

    {{-- PASSWORD --}}
    <div class="mb-6 grid gap-6 md:grid-cols-2">
        <div>
            <label class="text-heading mb-2.5 block text-sm font-medium">
                Password
            </label>

            <input
                class="rounded-base border-default-medium bg-neutral-secondary-medium text-heading shadow-xs focus:border-brand focus:ring-brand block w-full border px-3 py-2.5 text-sm"
                placeholder="••••••••" type="password" wire:model.defer="password">

            @error('password')
                <span class="mt-1 block text-sm text-red-500">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div>
            <label class="text-heading mb-2.5 block text-sm font-medium">
                Confirm Password
            </label>

            <input
                class="rounded-base border-default-medium bg-neutral-secondary-medium text-heading shadow-xs focus:border-brand focus:ring-brand block w-full border px-3 py-2.5 text-sm"
                placeholder="••••••••" type="password" wire:model.defer="password_confirmation">
        </div>
    </div>

    {{-- ACTION --}}
    <div class="flex items-center gap-3">
        <button
            class="rounded-base bg-brand shadow-xs hover:bg-brand-strong px-4 py-2.5 text-sm font-medium text-white disabled:opacity-50"
            type="submit" wire:loading.attr="disabled" wire:target="save">
            <span wire:loading.remove wire:target="save">
                {{ $role === 'teacher' ? 'Register Teacher' : 'Register Admin' }}
            </span>

            <span wire:loading wire:target="save">
                Saving...
            </span>
        </button>

        <a class="rounded-base border-default hover:bg-neutral-secondary-medium border px-4 py-2.5 text-sm font-medium"
            href="{{ route('teacher.index') }}" wire:navigate>
            Batal
        </a>
    </div>
</form>
