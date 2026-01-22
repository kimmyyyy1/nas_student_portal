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
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
        {{-- Icons (Unpkg para sure na gumana) --}}
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

        {{-- Scripts & Styles --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <style>
            * { font-family: 'Poppins', sans-serif !important; }
            [x-cloak] { display: none !important; }
            
            /* Custom Scrollbar for the Content Area */
            .custom-scroll::-webkit-scrollbar { width: 6px; }
            .custom-scroll::-webkit-scrollbar-track { background: transparent; }
            .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
            .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        </style>
    </head>
    
    {{-- 👇 FIX: 'h-screen overflow-hidden' para hindi gumalaw ang body --}}
    <body class="font-sans antialiased text-gray-900 bg-gray-50 h-screen overflow-hidden">
        
        {{-- BACKGROUND IMAGE --}}
        <div class="fixed inset-0 z-[-1]">
            <img src="{{ asset('images/nas/IMG_20250429_105924_472.jpg') }}" class="w-full h-full object-cover opacity-100" alt="Background">
            {{-- White Overlay para mabasa ang text --}}
            <div class="absolute inset-0 bg-white/40 backdrop-blur-[2px]"></div>
        </div>

        <div class="flex h-screen overflow-hidden">
            
            {{-- NAVIGATION --}}
            @include('layouts.navigation')

            {{-- MAIN CONTENT WRAPPER --}}
            {{-- 👇 FIX: Dito natin ilalagay ang scrollbar (overflow-y-auto) --}}
            <div class="flex-1 flex flex-col md:ml-64 h-full relative z-10">
                
                {{-- PAGE HEADER --}}
                @if (isset($header))
                    <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-200/50 sticky top-0 z-20 flex-none">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                {{-- SCROLLABLE CONTENT AREA --}}
                {{-- Ang 'custom-scroll' ay nasa style tag sa taas --}}
                <main class="flex-1 overflow-y-auto custom-scroll p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>

            </div>
            
        </div>

        @livewireScripts
    </body>
</html>