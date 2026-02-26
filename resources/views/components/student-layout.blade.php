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
            
            /* Pwersahin na walang kulay ang html at body para lumitaw ang BG */
            html, body {
                background-color: transparent !important;
                background: none !important;
                height: 100%;
            }

            /* Custom Scrollbar */
            .custom-scroll::-webkit-scrollbar { width: 6px; }
            .custom-scroll::-webkit-scrollbar-track { background: transparent; }
            .custom-scroll::-webkit-scrollbar-thumb { background: rgba(156, 163, 175, 0.5); border-radius: 4px; }
            .custom-scroll::-webkit-scrollbar-thumb:hover { background: rgba(107, 114, 128, 0.8); }
        </style>
    </head>
    
    <body class="font-sans antialiased text-gray-900">
        
        {{-- =============================================== --}}
        {{-- FIXED BACKGROUND IMAGE LAYER (Absolute, Z-0)    --}}
        {{-- =============================================== --}}
        <div class="fixed inset-0 z-0">
            <img src="{{ asset('images/nas/IMG_20250429_105924_472.jpg') }}" 
                 class="w-full h-full object-cover" 
                 alt="Background">
            
            {{-- Lighter overlay so BG image is clearly visible --}}
            <div class="absolute inset-0 bg-black/20 backdrop-blur-[1px]"></div>
        </div>

        {{-- =============================================== --}}
        {{-- MAIN CONTENT WRAPPER (Relative, Z-10)           --}}
        {{-- =============================================== --}}
        <div class="relative z-10 min-h-screen flex flex-col md:flex-row">

            {{-- SIDEBAR --}}
            @include('layouts.navigation')

            {{-- MAIN CONTENT AREA --}}
            <div class="flex-1 flex flex-col min-h-screen md:ml-64 transition-all duration-300">
                
                @if (isset($header))
                    {{-- Header na may konting transparency --}}
                    <header class="bg-white/70 backdrop-blur-xl shadow-sm border-b border-white/30 sticky top-0 z-20">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main class="flex-1 p-4 sm:p-6">
                    {{ $slot }}
                </main>

                <footer class="bg-white/60 backdrop-blur-xl border-t border-white/30 mt-auto py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-gray-500 font-medium uppercase tracking-wider">
                        &copy; {{ date('Y') }} National Academy of Sports. Student Portal.
                    </div>
                </footer>
            </div>

        </div>

        @livewireScripts
    </body>
</html>