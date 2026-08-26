<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />
        
        <!-- PLUGINS CSS STYLE -->
        <link rel="stylesheet" href="assets/plugins/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="assets/plugins/themify-icons/themify-icons.css">
        <link rel="stylesheet" href="assets/plugins/slick/slick.css">
        <link rel="stylesheet" href="assets/plugins/slick/slick-theme.css">
        <link rel="stylesheet" href="assets/plugins/fancybox/jquery.fancybox.min.css">
        <link rel="stylesheet" href="assets/plugins/aos/aos.css">

        {{-- Swiper --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

        <!-- CUSTOM CSS -->
        <link href="assets/css/style.css" rel="stylesheet">

        <title>{{ config('app.name') }}</title>

        @livewireStyles
        @fluxAppearance

    </head>

    <body class="min-h-screen bg-zinc-100 dark:bg-zinc-900">

        {{ $slot }}

        @livewireScripts
        @fluxScripts

        <!-- To Top -->
    <div class="scroll-top-to">
        <i class="ti-angle-up"></i>
    </div>
    
    <!-- JAVASCRIPTS -->
    <script src="assets/plugins/jquery/jquery.min.js"></script>

    {{-- Swiper --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="assets/plugins/slick/slick.min.js"></script>
    <script src="assets/plugins/fancybox/jquery.fancybox.min.js"></script>
    <script src="assets/plugins/syotimer/jquery.syotimer.min.js"></script>
    <script src="assets/plugins/aos/aos.js"></script>
    <!-- google map -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgeuuDfRlweIs7D6uo4wdIHVvJ0LonQ6g"></script>
    <script src="assets/plugins/google-map/gmap.js"></script>
    
    <script src="assets/js/script.js"></script>
    </body>
</html>