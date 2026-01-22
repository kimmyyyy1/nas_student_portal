<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NAS SAIS') }} - Student Portal</title>

        <link rel="icon" type="image/png" href="{{ asset('images/nas/favicon1.png') }}">

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
        {{-- Icons --}}
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <style>
            * { font-family: 'Poppins', sans-serif !important; }
            [x-cloak] { display: none !important; }
            
            body {
                background-color: transparent !important; 
                background-image: none !important;
                min-height: 100vh;
                overflow-y: auto; 
            }

            .custom-scroll::-webkit-scrollbar { width: 6px; }
            .custom-scroll::-webkit-scrollbar-track { background: transparent; }
            .custom-scroll::-webkit-scrollbar-thumb { background: rgba(156, 163, 175, 0.5); border-radius: 4px; }
            .custom-scroll::-webkit-scrollbar-thumb:hover { background: rgba(107, 114, 128, 0.8); }
        </style>
    </head>
    
    <body class="font-sans antialiased text-gray-900 bg-transparent">
        
        {{-- 👇 FIXED BACKGROUND IMAGE --}}
        <div class="fixed inset-0 z-[-1]">
            {{-- Image --}}
            <img src="{{ asset('images/nas/IMG_20250429_105924_472.jpg') }}" class="w-full h-full object-cover" alt="Background">
            
            {{-- 👇 FIX: Ginawa kong 50% lang ang opacity (bg-gray-50/50) para makita ang picture --}}
            <div class="absolute inset-0 bg-gray-50/50 backdrop-blur-[2px]"></div>
        </div>

        <div class="min-h-screen flex flex-col relative">
            
            @include('layouts.navigation')

            @if (isset($header))
                <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-200/50 sticky top-0 z-10">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="flex-1">
                {{ $slot }}
            </main>

            <footer class="bg-white/80 backdrop-blur-sm border-t border-gray-200 mt-auto py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-gray-500 font-medium uppercase tracking-wider">
                    &copy; {{ date('Y') }} National Academy of Sports. Student Portal.
                </div>
            </footer>

        </div>

        @livewireScripts
    </body>
</html>