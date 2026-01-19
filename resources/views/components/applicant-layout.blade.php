<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>NAS Applicant Portal</title>

        {{-- 1. FAVICON --}}
        <link rel="icon" type="image/png" href="{{ asset('images/nas/favicon1.png') }}">

        {{-- FONTS --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- SCRIPTS (VITE) --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900">
        <div class="min-h-screen flex flex-col">
            
            {{-- NAVIGATION BAR --}}
            <nav x-data="{ open: false, dropdownOpen: false }" class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
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
                                <button @click="dropdownOpen = !dropdownOpen" @click.outside="dropdownOpen = false" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-indigo-600 hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150">
                                    <div class="font-bold">{{ Auth::user()->name }}</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4 transform transition-transform duration-200" :class="{'rotate-180': dropdownOpen}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>

                                {{-- Dropdown Content --}}
                                <div x-show="dropdownOpen" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 z-50 origin-top-right" 
                                     style="display: none;">
                                    
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-xs text-gray-500">Signed in as</p>
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                                    </div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-800 transition w-full text-left font-medium">
                                            Log Out
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        {{-- MOBILE HAMBURGER --}}
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                {{-- MOBILE MENU --}}
                <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-200 bg-gray-50">
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
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block w-full ps-3 pe-4 py-3 border-l-4 border-transparent text-start text-base font-medium text-red-600 hover:text-red-800 hover:bg-red-50 hover:border-red-300 transition">
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
    </body>
</html>