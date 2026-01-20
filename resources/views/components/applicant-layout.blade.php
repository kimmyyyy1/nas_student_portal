<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>NAS Applicant Portal</title>

        {{-- 1. FAVICON --}}
        <link rel="icon" type="image/png" href="{{ asset('images/nas/favicon1.png') }}">

        {{-- 2. GOOGLE FONTS: POPPINS (Updated for Consistency) --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        {{-- 3. SCRIPTS & STYLES --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        {{-- 4. CUSTOM STYLES (Scrollbar & Font Force) --}}
        <style>
            * {
                font-family: 'Poppins', sans-serif !important;
            }
            
            /* Custom Scrollbar */
            ::-webkit-scrollbar { width: 6px; }
            ::-webkit-scrollbar-track { background: #f1f1f1; }
            ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900">
        <div class="min-h-screen flex flex-col">
            
            {{-- NAVIGATION BAR --}}
            <nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        
                        {{-- LOGO --}}
                        <div class="flex">
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('applicant.dashboard') }}" class="flex items-center gap-2">
                                    <img src="{{ asset('images/nas/favicon1.png') }}" class="block h-10 w-auto" alt="NAS Logo" />
                                    <span class="font-bold text-gray-700 text-lg hidden md:block tracking-tight">Applicant Portal</span>
                                </a>
                            </div>
                        </div>

                        {{-- DESKTOP DROPDOWN (RIGHT SIDE) --}}
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <div class="relative">
                                {{-- Trigger Button --}}
                                <button id="user-menu-button" type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-indigo-600 hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150">
                                    <div class="font-bold">{{ Auth::user()->name }}</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>

                                {{-- Dropdown Content --}}
                                <div id="user-menu-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 z-50 origin-top-right">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-xs text-gray-500">Signed in as</p>
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                                    </div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-800 transition w-full text-left font-medium cursor-pointer">
                                            Log Out
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        {{-- MOBILE HAMBURGER --}}
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button id="mobile-menu-button" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path id="hamburger-icon" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path id="close-icon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                {{-- MOBILE MENU --}}
                <div id="mobile-menu" class="hidden sm:hidden border-t border-gray-200 bg-gray-50">
                    <div class="pt-4 pb-4 border-t border-gray-200">
                        <div class="px-4 flex items-center">
                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-lg">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <div class="font-bold text-base text-gray-800">{{ Auth::user()->name }}</div>
                                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <div class="mt-4 space-y-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block w-full ps-3 pe-4 py-3 border-l-4 border-transparent text-start text-base font-medium text-red-600 hover:text-red-800 hover:bg-red-50 hover:border-red-300 transition cursor-pointer">
                                    Log Out
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            {{-- PAGE CONTENT --}}
            <main class="flex-grow">
                {{ $slot }}
            </main>
            
            {{-- FOOTER --}}
            <footer class="bg-white border-t mt-auto py-6">
                <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-500">
                    <p class="font-semibold text-gray-600 mb-1">National Academy of Sports</p>
                    <p>&copy; {{ date('Y') }} All rights reserved.</p>
                </div>
            </footer>
        </div>

        {{-- Livewire Scripts --}}
        @livewireScripts

        {{-- VANILLA JS DROPDOWN LOGIC --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                
                // 1. DESKTOP DROPDOWN LOGIC
                const userButton = document.getElementById('user-menu-button');
                const userDropdown = document.getElementById('user-menu-dropdown');

                if(userButton && userDropdown) {
                    // Toggle Click
                    userButton.addEventListener('click', function(e) {
                        e.stopPropagation();
                        userDropdown.classList.toggle('hidden');
                    });

                    // Close when clicking outside
                    document.addEventListener('click', function(e) {
                        if (!userButton.contains(e.target) && !userDropdown.contains(e.target)) {
                            userDropdown.classList.add('hidden');
                        }
                    });
                }

                // 2. MOBILE MENU LOGIC
                const mobileButton = document.getElementById('mobile-menu-button');
                const mobileMenu = document.getElementById('mobile-menu');
                const hamburgerIcon = document.getElementById('hamburger-icon');
                const closeIcon = document.getElementById('close-icon');

                if(mobileButton && mobileMenu) {
                    mobileButton.addEventListener('click', function() {
                        mobileMenu.classList.toggle('hidden');
                        
                        // Toggle Icons
                        if(mobileMenu.classList.contains('hidden')) {
                            hamburgerIcon.classList.remove('hidden');
                            hamburgerIcon.classList.add('inline-flex');
                            closeIcon.classList.add('hidden');
                        } else {
                            hamburgerIcon.classList.add('hidden');
                            hamburgerIcon.classList.remove('inline-flex');
                            closeIcon.classList.remove('hidden');
                        }
                    });
                }
            });
        </script>
    </body>
</html>