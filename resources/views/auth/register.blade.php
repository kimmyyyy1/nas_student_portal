<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | NAS SAIS</title>
        
    <link rel="icon" type="image/png" href="{{ asset('images/nas/favicon1.png') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- AlpineJS --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* 1. GLOBAL RESET */
        * { font-family: 'Poppins', sans-serif !important; }
        [x-cloak] { display: none !important; }

        /* 2. BODY SCROLL FIX */
        body {
            background-color: #111827; /* Gray 900 */
            overflow-y: auto !important; /* Allow scroll */
            height: auto !important;
            min-height: 100vh;
        }

        /* 3. HIDE BROWSER DEFAULT PASSWORD ICON */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear { display: none; }
        input[type="password"]::-webkit-contacts-auto-fill-button,
        input[type="password"]::-webkit-credentials-auto-fill-button {
            visibility: hidden; pointer-events: none; position: absolute; right: 0;
        }

        /* 4. MAIN WRAPPER */
        .main-wrapper {
            min-height: 100dvh; /* Dynamic Height for Mobile */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem 1rem; /* Breathing room */
            position: relative;
            z-index: 10;
        }
    </style>
</head>
<body class="antialiased text-gray-900">

    {{-- 1. BACKGROUND (FIXED) --}}
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/nas/IMG_20250429_105924_472.jpg') }}" class="w-full h-full object-cover opacity-50" alt="Background">
        {{-- Overlay Gradient --}}
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/90 via-gray-900/80 to-black/90 backdrop-blur-[2px]"></div>
    </div>

    {{-- 2. SCROLLABLE CONTENT WRAPPER --}}
    <div class="main-wrapper">
        
        {{-- REGISTER CARD --}}
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl border-t-[6px] border-yellow-400 overflow-hidden">
            
            <div class="px-8 py-8">
                
                {{-- HEADER / LOGO --}}
                <div class="text-center mb-6">
                    <a href="/">
                        <img src="{{ asset('images/nas/stack.png') }}" class="h-20 w-auto mx-auto mb-3 drop-shadow-md hover:scale-105 transition-transform" alt="NAS Logo">
                    </a>
                    <h2 class="text-2xl font-extrabold text-blue-900 tracking-tight">Create Account</h2>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Applicant Registration</p>
                </div>

                {{-- FORM --}}
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    {{-- First Name & Middle Name --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-[10px] text-gray-500 uppercase mb-1 ml-1">First Name *</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required autofocus 
                                   class="block w-full rounded-lg border-gray-300 bg-gray-50 p-2.5 text-sm focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition shadow-sm" 
                                   placeholder="Juan">
                        </div>
                        <div>
                            <label class="block font-bold text-[10px] text-gray-500 uppercase mb-1 ml-1">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name') }}" 
                                   class="block w-full rounded-lg border-gray-300 bg-gray-50 p-2.5 text-sm focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition shadow-sm" 
                                   placeholder="(Optional)">
                        </div>
                    </div>

                    {{-- Last Name --}}
                    <div>
                        <label class="block font-bold text-[10px] text-gray-500 uppercase mb-1 ml-1">Last Name *</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required 
                               class="block w-full rounded-lg border-gray-300 bg-gray-50 p-2.5 text-sm focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition shadow-sm" 
                               placeholder="Dela Cruz">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block font-bold text-[10px] text-gray-500 uppercase mb-1 ml-1">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                               class="block w-full rounded-lg border-gray-300 bg-gray-50 p-2.5 text-sm focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition shadow-sm" 
                               placeholder="juan@example.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-[10px] text-red-600 font-bold ml-1" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        
                        {{-- PASSWORD FIELD --}}
                        <div x-data="{ show: false }">
                            <label class="block font-bold text-[10px] text-gray-500 uppercase mb-1 ml-1 tracking-tight">Password *</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password"
                                       class="block w-full rounded-lg border-gray-300 bg-gray-50 p-2.5 pr-9 text-sm focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition shadow-sm" 
                                       placeholder="******">
                                
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 hover:text-blue-600 focus:outline-none transition" title="Show/Hide">
                                    <svg x-show="!show" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg x-show="show" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.572-2.872m2.197-2.197A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.572 2.872M9 9l3 3m0 0l3 3m-3-3a3 3 0 01-3 3m3-3a3 3 0 013-3m3-3l3 3" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" /></svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-[10px] text-red-600 font-bold ml-1" />
                        </div>

                        {{-- CONFIRM PASSWORD FIELD --}}
                        <div x-data="{ showConfirm: false }">
                            <label class="block font-bold text-[10px] text-gray-500 uppercase mb-1 ml-1 tracking-tight">Confirm *</label>
                            <div class="relative">
                                <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required 
                                       class="block w-full rounded-lg border-gray-300 bg-gray-50 p-2.5 pr-9 text-sm focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition shadow-sm" 
                                       placeholder="******">
                                
                                <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 hover:text-blue-600 focus:outline-none transition" title="Show/Hide">
                                    <svg x-show="!showConfirm" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg x-show="showConfirm" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.572-2.872m2.197-2.197A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.572 2.872M9 9l3 3m0 0l3 3m-3-3a3 3 0 01-3 3m3-3a3 3 0 013-3m3-3l3 3" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- SUBMIT BUTTON --}}
                    <button type="submit" class="w-full mt-6 py-3.5 bg-gradient-to-r from-blue-800 to-blue-900 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-lg shadow-lg transform transition hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-blue-200 uppercase tracking-widest text-xs">
                        REGISTER
                    </button>
                    
                    {{-- FOOTER LINKS --}}
                    <div class="text-center pt-5 border-t border-gray-100 mt-5">
                        <p class="text-[10px] text-gray-400 mb-2 font-bold uppercase">Already registered?</p>
                        <a href="{{ route('login') }}" class="inline-block text-xs font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 hover:text-blue-800 py-2.5 px-6 rounded-full transition uppercase tracking-wide">
                            Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        {{-- FOOTER COPYRIGHT --}}
        <div class="mt-6 text-center">
             <p class="text-gray-300 text-[10px] opacity-90 uppercase tracking-widest font-medium drop-shadow-md">
                &copy; {{ date('Y') }} National Academy of Sports
            </p>
        </div>

    </div>

</body>
</html>