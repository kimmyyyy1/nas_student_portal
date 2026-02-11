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
        
        {{-- Icons --}}
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

        {{-- Scripts & Styles --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        
        {{-- AlpineJS --}}
        <script src="//unpkg.com/alpinejs" defer></script>

        <style>
            * { font-family: 'Poppins', sans-serif !important; }
            
            /* Icon Fixes */
            i.bx, i.bxs, i.bxl, .bx {
                font-family: 'boxicons' !important;
                font-weight: normal !important;
                font-style: normal !important;
                line-height: 1 !important;
                flex-shrink: 0 !important;          
                min-width: 1.25rem;
                display: inline-block;
                text-align: center;
            }

            [x-cloak] { display: none !important; }
            
            body {
                background-color: transparent !important;
                background-image: none !important;
                height: 100dvh !important;
                overflow: hidden !important;
            }

            /* Custom Scrollbar */
            .custom-scroll::-webkit-scrollbar { width: 6px; }
            .custom-scroll::-webkit-scrollbar-track { background: transparent; }
            .custom-scroll::-webkit-scrollbar-thumb { background: rgba(156, 163, 175, 0.5); border-radius: 4px; }
            .custom-scroll::-webkit-scrollbar-thumb:hover { background: rgba(107, 114, 128, 0.8); }

            /* Animation */
            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-page-enter {
                animation: fadeUp 0.3s ease-out forwards;
            }
            
            /* Floating Animation */
            @keyframes bounce-slight {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-5px); }
            }
            .animate-bounce-slight {
                animation: bounce-slight 2s infinite;
            }
        </style>
    </head>
    
    <body class="font-sans antialiased text-gray-900 bg-transparent">
        
        {{-- BACKGROUND --}}
        <div class="fixed inset-0 z-[-1]">
            <img src="{{ asset('images/nas/IMG_20250429_105924_472.jpg') }}" class="w-full h-full object-cover" alt="Background">
            <div class="absolute inset-0 bg-white/1 backdrop-blur-[2px]"></div>
        </div>

        {{-- ⚡ MAIN WRAPPER (Fixed Breakpoints to lg) ⚡ --}}
        <div class="h-full flex flex-col lg:flex-row w-full">
            
            {{-- Sidebar --}}
            <div class="shrink-0">
                @include('layouts.navigation')
            </div>

            {{-- ⚡ CONTENT AREA: Fixed lg:ml-64 and lg:pt-0 to match navigation ⚡ --}}
            <div class="flex-1 flex flex-col h-full overflow-hidden relative lg:ml-64 pt-16 lg:pt-0 transition-all duration-300 w-full">
                
                {{-- Header --}}
                @if (isset($header))
                    <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-200/50 z-20 shrink-0 w-full">
                        <div class="w-full mx-auto py-4 px-4 sm:px-6 lg:px-8 xl:px-12">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                {{-- MAIN CONTENT --}}
                {{-- ⚡ Removed max-w restrictions, added wider padding on large screens (xl:px-12) ⚡ --}}
                <main class="flex-1 overflow-y-auto custom-scroll p-4 sm:p-6 lg:p-8 xl:px-12 pb-20 lg:pb-8 animate-page-enter w-full">
                    {{ $slot }}
                </main>

            </div>
        </div>

        {{-- ================================================================= --}}
        {{-- SYSTEM ALERTS (SUCCESS / ERROR TOASTS)                            --}}
        {{-- ================================================================= --}}
        
        <div class="fixed top-20 right-4 z-[100] flex flex-col gap-2 pointer-events-none">
            
            {{-- SUCCESS ALERT --}}
            @if(session('success'))
                <div x-data="{ show: true }" 
                     x-show="show" 
                     x-init="setTimeout(() => show = false, 5000)"
                     x-transition:enter="transform ease-out duration-300 transition"
                     x-transition:enter-start="translate-x-full opacity-0"
                     x-transition:enter-end="translate-x-0 opacity-100"
                     x-transition:leave="transform transition ease-in duration-300"
                     x-transition:leave-start="translate-x-0 opacity-100"
                     x-transition:leave-end="translate-x-full opacity-0"
                     class="pointer-events-auto w-80 bg-white border-l-4 border-emerald-500 shadow-2xl rounded-r-lg p-4 flex items-start gap-3 ring-1 ring-black/5">
                    
                    <div class="flex-shrink-0 text-emerald-500">
                        <i class='bx bxs-check-circle text-2xl'></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-gray-800">Success</h3>
                        <p class="text-xs text-gray-600 mt-0.5">{!! session('success') !!}</p>
                    </div>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                        <i class='bx bx-x text-xl'></i>
                    </button>
                </div>
            @endif

            {{-- ERROR ALERT --}}
            @if(session('error'))
                <div x-data="{ show: true }" 
                     x-show="show" 
                     x-init="setTimeout(() => show = false, 5000)"
                     x-transition:enter="transform ease-out duration-300 transition"
                     x-transition:enter-start="translate-x-full opacity-0"
                     x-transition:enter-end="translate-x-0 opacity-100"
                     x-transition:leave="transform transition ease-in duration-300"
                     x-transition:leave-start="translate-x-0 opacity-100"
                     x-transition:leave-end="translate-x-full opacity-0"
                     class="pointer-events-auto w-80 bg-white border-l-4 border-red-500 shadow-2xl rounded-r-lg p-4 flex items-start gap-3 ring-1 ring-black/5">
                    
                    <div class="flex-shrink-0 text-red-500">
                        <i class='bx bxs-error-circle text-2xl'></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-gray-800">Action Failed</h3>
                        <p class="text-xs text-gray-600 mt-0.5">{!! session('error') !!}</p>
                    </div>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                        <i class='bx bx-x text-xl'></i>
                    </button>
                </div>
            @endif

        </div>

        {{-- ================================================================= --}}
        {{-- LIVEWIRE NOTIFICATIONS (Real-time Bell)                           --}}
        {{-- ================================================================= --}}
        @auth
            <livewire:notifications />
        @endauth

        @livewireScripts
    </body>
</html>