<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Vercel Web Analytics -->
        <script>
            window.va = window.va || function () { (window.vaq = window.vaq || []).push(arguments); };
        </script>
        <script defer src="/_vercel/insights/script.js"></script>
    </head>
    <body class="font-sans text-gray-900 antialiased min-h-screen flex flex-col bg-gray-100">
        <div class="flex-1 flex flex-col sm:justify-center items-center pt-6 pb-20 sm:pt-0">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>

        {{-- CENTERED MINIMALIST FULL-WIDTH FOOTER --}}
        <footer class="mt-auto w-full px-4 sm:px-6 lg:px-8 py-5 bg-[#171a21] border-t border-slate-700/80 flex flex-col items-center justify-center gap-2 text-center shadow-inner z-20 relative">
            {{-- Logo & Name (Centered) --}}
            <div class="flex items-center justify-center gap-3">
                <img src="{{ asset('images/nas/PICTD.png') }}" alt="PICT Division" class="h-6 w-auto mix-blend-screen opacity-90">
                <span class="text-xs font-semibold text-slate-300 tracking-wider">PICT DIVISION</span>
            </div>
            
            {{-- Copyright --}}
            <p class="text-[11px] text-slate-400 font-medium tracking-wide">
                &copy; {{ date('Y') }} NAS Student-Athlete Information System. All rights reserved.
            </p>
        </footer>

        <!-- Vercel Speed Insights -->
        <script>
            window.si = window.si || function () { (window.siq = window.siq || []).push(arguments); };
        </script>
        <script defer src="/_vercel/speed-insights/script.js"></script>
    </body>
</html>
