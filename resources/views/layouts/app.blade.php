<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NAS SAIS') }}</title>

        {{-- Favicon --}}
        <link rel="icon" type="image/png" href="{{ asset('images/nas/favicon1.png') }}">

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        {{-- Icons --}}
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

        {{-- Scripts (Vite) --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        {{-- 👇 1. LIVEWIRE STYLES (Ibinalik natin para sigurado) --}}
        @livewireStyles

        {{-- Custom Scrollbar Style --}}
        <style>
            .custom-scrollbar::-webkit-scrollbar { width: 5px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 5px; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
        </style>
    </head>
    
    <body class="font-sans antialiased text-gray-900">
        
        {{-- BACKGROUND IMAGE --}}
        <div class="fixed inset-0 z-[-1]">
            <img src="{{ asset('images/nas/IMG_20250429_105924_472.jpg') }}" class="w-full h-full object-cover" alt="Background">
            {{-- Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80 via-blue-900/60 to-black/70 backdrop-blur-[2px]"></div>
        </div>

        {{-- MAIN CONTENT WRAPPER --}}
        <div class="min-h-screen relative">
            
            {{-- 👇 2. SIDEBAR WITH PERSIST (Ito ang pipigil sa flickering) --}}
            @persist('sidebar')
                @include('layouts.navigation')
            @endpersist

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

        {{-- 👇 3. LIVEWIRE SCRIPTS (Ibinalik natin para gumana ang wire:navigate) --}}
        @livewireScripts
    </body>
</html>