<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NAS SAIS') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* Custom Scrollbar */
            .custom-scrollbar::-webkit-scrollbar { width: 5px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 5px; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
        </style>
    </head>
    <body class="font-sans antialiased">
        
        {{-- 1. GLOBAL BACKGROUND IMAGE (Pinaka-likod) --}}
        <div class="fixed inset-0 -z-50 w-full h-full pointer-events-none">
            <img src="{{ asset('images/nas/IMG_20250429_105924_472.jpg') }}" class="w-full h-full object-cover opacity-80" alt="NAS Background">
            <div class="absolute inset-0 bg-white/30"></div>
        </div>

        {{-- 2. SIDEBAR WHITE BACKGROUND FIX (Para hindi transparent ang sidebar area) --}}
        <div class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200 z-0 hidden md:block"></div>

        {{-- 3. MAIN CONTENT WRAPPER --}}
        <div class="min-h-screen bg-transparent relative z-10">
            
            {{-- Navigation Sidebar --}}
            @include('layouts.navigation')

            @if (isset($header))
                <header class="bg-white/90 shadow backdrop-blur-sm relative z-30 md:ml-64"> 
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="md:ml-64 pt-6 px-4"> 
                {{ $slot }}
            </main>
        </div>
    </body>
</html>