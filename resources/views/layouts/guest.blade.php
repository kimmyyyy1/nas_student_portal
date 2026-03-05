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

        {{-- FOOTER --}}
        <footer class="mt-auto w-full z-20 relative">
            <div class="px-4 sm:px-6 lg:px-8 py-6 bg-gradient-to-b from-[#171a21] to-[#0f1115] border-t border-slate-700/60 shadow-[0_-4px_20px_rgba(0,0,0,0.15)]">
                {{-- Accent line --}}
                <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-blue-500/60 to-transparent"></div>
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 max-w-7xl mx-auto">
                    {{-- Logo & Brand --}}
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/nas/PICTD.png') }}" alt="PICT Division" class="h-11 w-auto object-contain opacity-95 drop-shadow-sm">
                        <div class="border-l border-slate-500/50 pl-3">
                            <span class="text-sm font-semibold text-slate-200 tracking-wide">National Academy of Sports</span>
                        </div>
                    </div>
                    {{-- Copyright --}}
                    <p class="text-sm text-slate-200 font-medium text-center sm:text-right">
                        &copy; {{ date('Y') }} Student-Athlete Information System. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>

        <!-- Vercel Speed Insights -->
        <script>
            window.si = window.si || function () { (window.siq = window.siq || []).push(arguments); };
        </script>
        <script defer src="/_vercel/speed-insights/script.js"></script>
    </body>
</html>
