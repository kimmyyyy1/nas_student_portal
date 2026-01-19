<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NAS SAIS') }}</title>

        {{-- Favicon --}}
        <link rel="icon" type="image/png" href="{{ asset('images/nas/favicon1.png') }}">

        {{-- Google Fonts: Poppins --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
        {{-- Icons --}}
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

        {{-- ❌ TINANGGAL NA NATIN ANG MANUAL VERCEL SCRIPT DITO --}}
        {{-- Dahil nasa app.js na ang injection --}}

        {{-- Scripts (Vite) --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        {{-- Livewire Styles --}}
        @livewireStyles

        {{-- Custom Styles --}}
        <style>
            * {
                font-family: 'Poppins', sans-serif !important;
            }

            /* Custom Scrollbar Style */
            .custom-scrollbar::-webkit-scrollbar { width: 5px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 5px; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
        </style>

        <!-- Vercel Web Analytics -->
        <script>
            window.va = window.va || function () { (window.vaq = window.vaq || []).push(arguments); };
        </script>
        <script defer src="/_vercel/insights/script.js"></script>
    </head>
    
    <body class="font-sans antialiased text-gray-900">
        
        {{-- BACKGROUND IMAGE --}}
        <div class="fixed inset-0 z-[-1]">
            <img src="{{ asset('images/nas/IMG_20250429_105924_472.jpg') }}" class="w-full h-full object-cover" alt="Background">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80 via-blue-900/60 to-black/70 backdrop-blur-[2px]"></div>
        </div>

        {{-- MAIN CONTENT WRAPPER --}}
        <div class="min-h-screen relative">
            
            {{-- NAVIGATION --}}
            @include('layouts.navigation')

            {{-- PAGE HEADER --}}
            @if (isset($header))
                <header class="bg-white shadow relative md:ml-64 transition-all duration-300"> 
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            {{-- PAGE CONTENT --}}
            <main class="md:ml-64 pt-6 px-4 transition-all duration-300"> 
                {{ $slot }}
            </main>
        </div>

        {{-- Livewire Scripts --}}
        @livewireScripts
    </body>
</html>