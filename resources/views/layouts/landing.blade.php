<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="{{ csrf_token() }}" name="csrf-token">

    <title>{{ config('app.name', 'Tunas Bimbel') }}</title>
    <meta
        content="Tunas Bimbel - platform pembelajaran online dengan materi, video, PDF, quiz, tryout, dan evaluasi belajar."
        name="description">

    <link href="{{ asset('assets/images/favicon.png') }}" rel="shortcut icon" type="image/x-icon">

    {{-- Font --}}
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- Theme / Plugin CSS --}}
    <link href="{{ asset('assets/plugins/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/themify-icons/themify-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/tunas-home.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/tunas-checkout.css') }}" rel="stylesheet">

    @livewireStyles
    @fluxAppearance

    <style>
        html,
        body {
            margin: 0;
            min-height: 100%;
            background: #ffffff;
        }

        body {
            font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        [wire\:loading][wire\:loading] {
            display: none;
        }

        button:disabled {
            cursor: not-allowed;
            opacity: .7;
        }
    </style>
</head>

<body>
    {{ $slot }}

    <div class="scroll-top-to">
        <i class="ti-angle-up"></i>
    </div>

    @livewireScripts
    @fluxScripts

    {{-- JavaScript --}}
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.AOS) {
                AOS.init({
                    duration: 700,
                    once: true,
                    offset: 40
                });
            }
        });

        document.addEventListener('livewire:navigated', function() {
            if (window.AOS) {
                AOS.refreshHard();
            }
        });
    </script>
</body>

</html>
