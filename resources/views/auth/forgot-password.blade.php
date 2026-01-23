<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password | NAS SAIS</title>
    
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

        /* 3. MAIN WRAPPER */
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
        <img src="{{ asset('images/nas/IMG_20250429_105924_472.jpg') }}" class="w-full h-full object-cover opacity-60" alt="Background">
        {{-- Overlay Gradient --}}
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/90 via-gray-900/80 to-black/90 backdrop-blur-[2px]"></div>
    </div>

    {{-- 2. SCROLLABLE CONTENT WRAPPER --}}
    <div class="main-wrapper">
        
        {{-- CARD --}}
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl border-t-[6px] border-yellow-400 overflow-hidden">
            
            <div class="px-8 py-8">
                
                {{-- HEADER / LOGO --}}
                <div class="text-center mb-6">
                    <a href="/">
                        <img src="{{ asset('images/nas/stack.png') }}" class="h-20 w-auto mx-auto mb-3 drop-shadow-md hover:scale-105 transition-transform" alt="NAS Logo">
                    </a>
                    <h2 class="text-2xl font-extrabold text-blue-900 tracking-tight">NAS SAIS</h2>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Reset Password</p>
                </div>

                {{-- MESSAGE --}}
                <div class="mb-6 text-xs text-gray-600 text-center leading-relaxed bg-blue-50 p-4 rounded-lg border border-blue-100 shadow-sm">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
                </div>

                {{-- SESSION STATUS --}}
                <x-auth-session-status class="mb-4" :status="session('status')" />

                {{-- FORM --}}
                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    {{-- Email Field --}}
                    <div>
                        <label class="block font-bold text-[10px] text-gray-500 uppercase mb-1 ml-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                {{-- Envelope Icon --}}
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="block w-full pl-10 pr-3 py-2.5 rounded-lg border-gray-300 bg-gray-50 text-sm focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition shadow-sm" 
                                   placeholder="Enter your registered email">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-[10px] text-red-600 font-bold ml-1" />
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-blue-800 to-blue-900 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-lg shadow-lg transform transition hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-blue-200 uppercase tracking-widest text-xs">
                        Email Password Reset Link
                    </button>
                </form>

                {{-- Back to Login --}}
                <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center text-xs font-bold text-gray-500 hover:text-blue-700 transition uppercase tracking-wide group">
                        <svg class="w-4 h-4 mr-1 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Login
                    </a>
                </div>

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