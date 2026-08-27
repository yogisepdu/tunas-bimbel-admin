@props([
    'role' => 'teacher',
])

<form wire:submit.prevent="update">
    {{-- JENIS AKUN --}}
    <div class="mb-6">
        <label class="text-heading mb-2.5 block text-sm font-medium">
            Jenis Akun
        </label>

        <input
            class="rounded-base border-default-medium shadow-xs block w-full border bg-gray-100 px-3 py-2.5 text-sm text-gray-500 md:w-1/2"
            disabled type="text" value="{{ $role === 'teacher' ? 'Teacher' : 'Admin' }}">

        <p class="mt-2 text-sm text-gray-500">
            Jenis akun tidak dapat diubah setelah akun dibuat.
        </p>
    </div>

    {{-- INFORMASI AKUN --}}
    <div class="mb-6 grid gap-6 md:grid-cols-2">
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
                type="email" wire:model.defer="email">

            @error('email')
                <span class="mt-1 block text-sm text-red-500">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>

    {{-- DATA KHUSUS TEACHER --}}
    @if ($role === 'teacher')
        <div class="rounded-base border-default bg-neutral-primary-soft mb-6 border p-5">
            <h2 class="text-heading mb-5 text-lg font-semibold">
                Profil Teacher
            </h2>

            <div class="grid gap-6 md:grid-cols-2">
                {{-- PHONE --}}
                <div>
                    <label class="text-heading mb-2.5 block text-sm font-medium">
                        Phone
                    </label>

                    <input
                        class="rounded-base border-default-medium bg-neutral-secondary-medium text-heading shadow-xs block w-full border px-3 py-2.5 text-sm"
                        type="text" wire:model.defer="phone">

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
                        class="rounded-base border-default-medium bg-neutral-secondary-medium text-heading shadow-xs block w-full border px-3 py-2.5 text-sm"
                        type="text" wire:model.defer="company">

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
                        class="rounded-base border-default-medium bg-neutral-secondary-medium text-heading shadow-xs block w-full border px-3 py-2.5 text-sm"
                        type="text" wire:model.defer="specialization">

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
                        class="rounded-base border-default-medium bg-neutral-secondary-medium text-heading shadow-xs block w-full border px-3 py-2.5 text-sm"
                        max="100" min="0" type="number" wire:model.defer="experience_years">

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
                    class="rounded-base border-default-medium bg-neutral-secondary-medium text-heading shadow-xs block w-full border px-3 py-2.5 text-sm"
                    rows="4" wire:model.defer="bio"></textarea>

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
                Password Baru
            </label>

            <input
                class="rounded-base border-default-medium bg-neutral-secondary-medium text-heading shadow-xs block w-full border px-3 py-2.5 text-sm"
                placeholder="Kosongkan jika tidak diganti" type="password" wire:model.defer="password">

            @error('password')
                <span class="mt-1 block text-sm text-red-500">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div>
            <label class="text-heading mb-2.5 block text-sm font-medium">
                Confirm Password Baru
            </label>

            <input
                class="rounded-base border-default-medium bg-neutral-secondary-medium text-heading shadow-xs block w-full border px-3 py-2.5 text-sm"
                placeholder="Ulangi password baru" type="password" wire:model.defer="password_confirmation">
        </div>
    </div>

    {{-- ACTION --}}
    <div class="flex items-center gap-3">
        <button
            class="rounded-base bg-brand shadow-xs hover:bg-brand-strong px-4 py-2.5 text-sm font-medium text-white disabled:opacity-50"
            type="submit" wire:loading.attr="disabled" wire:target="update">
            <span wire:loading.remove wire:target="update">
                {{ $role === 'teacher' ? 'Update Teacher' : 'Update Admin' }}
            </span>

            <span wire:loading wire:target="update">
                Updating...
            </span>
        </button>

        <a class="rounded-base border-default hover:bg-neutral-secondary-medium border px-4 py-2.5 text-sm font-medium"
            href="{{ route('teacher.index') }}" wire:navigate>
            Batal
        </a>
    </div>
</form>
