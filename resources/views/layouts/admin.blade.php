<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    @livewireStyles
    @fluxAppearance

</head>

<body class="min-h-screen bg-zinc-100 dark:bg-zinc-900">

    <div class="flex h-screen">

        <!-- SIDEBAR -->
        <flux:sidebar sticky collapsible="mobile"
            class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">

            <flux:sidebar.header>
                <flux:sidebar.brand
                    href="#"
                    logo="https://fluxui.dev/img/demo/logo.png"
                    logo:dark="https://fluxui.dev/img/demo/dark-mode-logo.png"
                    name="Acme Inc."
                />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.search placeholder="Search..." />
            <x-sidebar />
        </flux:sidebar>

        <!-- CONTENT AREA -->
        <div class="flex-1 flex flex-col">

            <!-- HEADER -->
            <flux:header class="bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700 px-6">

                <div class="flex items-center gap-3">
                    <flux:sidebar.toggle class="lg:hidden text-zinc-700 dark:text-white" />

                    <flux:button
                        variant="ghost"
                        icon="arrow-left"
                        onclick="window.history.length > 1 ? history.back() : window.location='{{ route('dashboard') }}'"
                        wire:navigate
                    />

                    <flux:heading size="lg">
                        {{ $title ?? 'Dashboard' }}
                    </flux:heading>

                </div>

            </flux:header>

            <!-- MAIN CONTENT -->
            <flux:main class="p-6 flex-1 overflow-auto">

                {{ $slot }}

            </flux:main>

        </div>

    </div>

    @livewireScripts
    @fluxScripts

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('livewire:init', () => {

            Livewire.on('confirmDelete', (data) => {

                Swal.fire({
                    title: 'Yakin, hapus Data ini?',
                    text: "Semua chapter dan materi akan ikut terhapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {

                    if (result.isConfirmed) {
                        Livewire.dispatch('deleteClass', { id: data.id })
                    }

                });

            });

        });
    </script>

</body>
</html>