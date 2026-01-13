<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NAS SAIS') }}</title>

        <link rel="icon" type="image/png" href="{{ asset('images/nas/favicon1.png') }}">

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .custom-scrollbar::-webkit-scrollbar { width: 5px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; } /* Transparent track */
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 5px; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-900">
        
        {{-- ================================================================= --}}
        {{-- GLOBAL BACKGROUND IMAGE (Fixed for entire app)                    --}}
        {{-- ================================================================= --}}
        <div class="fixed inset-0 z-[-1]">
            {{-- Image --}}
            <img src="{{ asset('images/nas/IMG_20250429_105924_472.jpg') }}" class="w-full h-full object-cover" alt="Background">
            
            {{-- Overlay: Blue Gradient na medyo dark para lumitaw ang white texts/cards --}}
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80 via-indigo-900/80 to-slate-900/90 backdrop-blur-[2px]"></div>
        </div>

        <div class="min-h-screen relative">
            
            {{-- Navigation Sidebar --}}
            @include('layouts.navigation')

            {{-- Main Content --}}
            <main class="md:ml-64 transition-all duration-300"> 
                
                {{-- Page Header (Optional: Kung gusto mong ibalik, uncomment lang. Pero mas maganda kung wala para clean) --}}
                {{-- 
                @if (isset($header))
                    <header class="bg-white/80 backdrop-blur-md shadow-sm relative"> 
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif 
                --}}

                <div class="py-6 px-4">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>