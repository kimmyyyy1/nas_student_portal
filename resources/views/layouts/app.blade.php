<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NAS SAIS') }}</title>

        <link rel="icon" type="image/jpeg" href="/images/nas/favicon.jpg"> 

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .custom-scrollbar::-webkit-scrollbar { width: 5px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 5px; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
        </style>
    </head>
    <body class="font-sans antialiased">
        
        {{-- 1. BACKGROUND IMAGE (Pure CSS Fix) --}}
        {{-- Ito ang magsisigurong nasa likod siya dahil sa z-index: -100 --}}
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -100; pointer-events: none;">
            <img src="{{ asset('images/nas/IMG_20250429_105924_472.jpg') }}" 
                 style="width: 100%; height: 100%; object-fit: cover; opacity: 0.2;" 
                 alt="NAS Background">
            {{-- White Overlay para mabasa ang text --}}
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.5);"></div>
        </div>

        {{-- 2. MAIN CONTENT WRAPPER --}}
        {{-- Ito naman ang magsisigurong nasa harap ang dashboard dahil sa z-index: 10 --}}
        <div style="position: relative; z-index: 10; min-height: 100vh;">
            
            {{-- Navigation Sidebar --}}
            @include('layouts.navigation')

            {{-- Page Header --}}
            @if (isset($header))
                <header class="bg-white/90 shadow backdrop-blur-sm relative md:ml-64"> 
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
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