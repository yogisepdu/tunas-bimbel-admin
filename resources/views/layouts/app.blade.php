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

        {{ $slot }}

        @livewireScripts
        @fluxScripts

    </body>
</html>