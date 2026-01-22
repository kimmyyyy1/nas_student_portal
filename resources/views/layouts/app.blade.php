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
        
        {{-- 👇 FIX: Using CDNJS for Boxicons (More Stable & Faster) --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css" integrity="sha512-cn16Qw8mzTBKpu504Cwnkqk4En8t1j5+5v4rQ5w5h5+5+5+5+5+5+5+5" crossorigin="anonymous" referrerpolicy="no-referrer" />

        {{-- Scripts & Styles --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        {{-- Custom Styles --}}
        <style>
            * { font-family: 'Poppins', sans-serif !important; }
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none;  scrollbar-width: none; }
            @media (min-width: 768px) {
                ::-webkit-scrollbar { width: 6px; }
                ::-webkit-scrollbar-track { background: #f1f1f1; }
                ::-webkit-scrollbar-thumb { background: #c7c7c7; border-radius: 10px; }
                ::-webkit-scrollbar-thumb:hover { background: #888; }
            }
        </style>
    </head>
    
    <body class="font-sans antialiased text-gray-900 overflow-x-hidden">
        
        {{-- BACKGROUND IMAGE --}}
        <div class="fixed inset-0 z-[-1]">
            <img src="{{ asset('images/nas/IMG_20250429_105924_472.jpg') }}" class="w-full h-full object-cover" alt="Background">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/90 via-slate-900/80 to-black/80 backdrop-blur-[1px]"></div>
        </div>

        {{-- MAIN WRAPPER --}}
        <div class="min-h-screen flex flex-col">
            
            {{-- NAVIGATION --}}
            @include('layouts.navigation')

            {{-- PAGE HEADER --}}
            @if (isset($header))
                {{-- 👇 FIX: 'pt-16' (mobile) and 'md:pt-0' (desktop) --}}
                <header class="bg-white/95 backdrop-blur-sm shadow-sm relative md:ml-64 transition-all duration-300 z-10 border-b border-gray-100 pt-16 md:pt-0">
                    <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            {{-- PAGE CONTENT --}}
            <main class="flex-grow md:ml-64 px-0 md:px-4 transition-all duration-300">
                {{ $slot }}
            </main>
            
        </div>

        @livewireScripts
    </body>
</html>