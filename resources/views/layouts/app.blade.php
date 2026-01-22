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
        
        {{-- Icons (Unpkg) --}}
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

        {{-- Scripts & Styles --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <style>
            * { font-family: 'Poppins', sans-serif !important; }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    
    {{-- 👇 FIX: 'bg-transparent' para lumabas ang image, 'min-h-screen' para walang flicker --}}
    <body class="font-sans antialiased text-gray-900 bg-transparent min-h-screen">
        
        {{-- 👇 BACKGROUND IMAGE (Fixed Position) --}}
        <div class="fixed inset-0 z-[-1]">
            {{-- Image --}}
            <img src="{{ asset('images/nas/IMG_20250429_105924_472.jpg') }}" class="w-full h-full object-cover" alt="Background">
            
            {{-- 👇 OVERLAY FIX: 
                 'bg-white/60' -> Saktong puti para mabasa ang text pero kita ang image.
                 'backdrop-blur-[3px]' -> Hindi gaanong blurred, sakto lang. --}}
            <div class="absolute inset-0 bg-white/60 backdrop-blur-[3px]"></div>
        </div>

        <div class="flex min-h-screen flex-col md:flex-row">
            
            {{-- NAVIGATION --}}
            @include('layouts.navigation')

            {{-- MAIN CONTENT --}}
            {{-- Walang transition-all dito para hindi tumalon-talon --}}
            <div class="flex-1 flex flex-col w-full md:ml-64">
                
                {{-- PAGE HEADER (Sticky) --}}
                @if (isset($header))
                    <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-200/50 sticky top-0 z-20">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                {{-- CONTENT SLOT --}}
                <main class="flex-1 p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>

            </div>
            
        </div>

        @livewireScripts
    </body>
</html>