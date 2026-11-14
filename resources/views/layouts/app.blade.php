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

    {{ $slot }}

    @livewireScripts
    @fluxScripts

</body>

</html>
