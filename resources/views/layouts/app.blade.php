<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NAS SAIS') }}</title>

        {{-- 👇 DITO ANG PAGBABAGO: Updated favicon path --}}
        <link rel="icon" type="image/png" href="{{ asset('images/nas/favicon1.png') }}">

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
    <body class="font-sans antialiased bg-gray-100">
        
        {{-- BACKGROUND IMAGE REMOVED FOR TESTING --}}

        {{-- MAIN CONTENT WRAPPER --}}
        <div style="position: relative; min-height: 100vh;">
            
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