<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta content="{{ csrf_token() }}" name="csrf-token">

    <title>{{ config('app.name') }}</title>
    {{-- Favicon Tunas Bimbel --}}
    <link href="{{ asset('assets/tunas-favicon.png') }}" rel="icon" type="image/png">
    <link href="{{ asset('assets/tunas-favicon.png') }}" rel="shortcut icon" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    @fluxAppearance

</head>

<body class="min-h-screen bg-zinc-100 dark:bg-zinc-900">

    <div class="flex h-screen">

        <!-- SIDEBAR -->
        <flux:sidebar class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900"
            collapsible="mobile" sticky>

            <flux:sidebar.header>
                <flux:sidebar.brand href="#" logo:dark="https://fluxui.dev/img/demo/dark-mode-logo.png"
                    logo="https://fluxui.dev/img/demo/logo.png" name="Acme Inc." />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.search placeholder="Search..." />
            <x-sidebar />
        </flux:sidebar>

        <!-- CONTENT AREA -->
        <div class="flex flex-1 flex-col">

            <!-- HEADER -->
            <flux:header class="border-b border-zinc-200 bg-white px-6 dark:border-zinc-700 dark:bg-zinc-900">

                <div class="flex items-center gap-3">
                    <flux:sidebar.toggle class="text-zinc-700 lg:hidden dark:text-white" />

                    <flux:button icon="arrow-left"
                        onclick="window.history.length > 1 ? history.back() : window.location='{{ route('dashboard') }}'"
                        variant="ghost" wire:navigate />

                    <flux:heading size="lg">
                        {{ $title ?? 'Dashboard' }}
                    </flux:heading>

                </div>

            </flux:header>

            <!-- MAIN CONTENT -->
            <flux:main class="flex-1 overflow-auto p-6">

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
                        Livewire.dispatch('deleteClass', {
                            id: data.id
                        })
                    }

                });

            });

        });
    </script>

</body>

</html>
