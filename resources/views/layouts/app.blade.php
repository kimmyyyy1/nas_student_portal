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

        {{-- Scripts --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        {{-- Custom Scrollbar Style --}}
        <style>
            .custom-scrollbar::-webkit-scrollbar { width: 5px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #9ca3af; border-radius: 5px; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6b7280; }
        </style>
    </head>
    
    {{-- 👇 PAGBABAGO 1: Ginawang 'text-gray-100' (White Text) ang default color ng body --}}
    <body class="font-sans antialiased text-gray-100">
        
        {{-- GLOBAL BACKGROUND IMAGE --}}
        <div class="fixed inset-0 z-[-1]">
            <img src="{{ asset('images/nas/IMG_20250429_105924_472.jpg') }}" class="w-full h-full object-cover" alt="Background">
            {{-- Dark Blue Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80 via-blue-900/60 to-black/70 backdrop-blur-[2px]"></div>
        </div>

        {{-- MAIN CONTENT WRAPPER --}}
        <div class="min-h-screen relative">
            
            {{-- Navigation Sidebar --}}
            @include('layouts.navigation')

            {{-- Page Header --}}
            @if (isset($header))
                {{-- 👇 PAGBABAGO 2: Inalis ang 'bg-white shadow' para maging transparent ang header --}}
                <header class="relative md:ml-64"> 
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{-- Ang text dito ay magiging white na dahil sa body class --}}
                        {{ $header }}
                    </div>
                </header>
            @endif

            {{-- Page Content --}}
            <main class="md:ml-64 pt-6 px-4"> 
                {{ $slot }}
            </main>
        </div>
    </body>
</html>