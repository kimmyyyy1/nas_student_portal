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
        body { font-family: 'Poppins', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased text-gray-800 bg-gray-100 min-h-screen relative overflow-x-hidden">

    {{-- Background Image & Overlay --}}
    <div class="fixed inset-0 z-0 pointer-events-none">
        <img src="{{ asset('images/nas/IMG_20250429_105924_472.jpg') }}" class="w-full h-full object-cover" alt="NAS Campus"> 
        <div class="absolute inset-0 bg-black/40"></div>
    </div>
    
    <div class="relative z-10 min-h-screen flex flex-col justify-center items-center p-4 sm:p-6">

        <div class="w-full max-w-[420px] bg-white rounded-lg shadow-xl hover:shadow-2xl border-t-[5px] border-yellow-400 transition-all duration-300 md:hover:-translate-y-1">
            <div class="p-8 sm:p-10">
                
                {{-- Logo Section --}}
                <div class="text-center mb-8">
                    <a href="/" class="inline-block">
                        <img src="{{ asset('images/nas/stack.png') }}" class="h-28 w-auto mx-auto drop-shadow-sm" alt="NAS Logo">
                    </a>
                    <h2 class="text-xl font-extrabold text-blue-900 mt-4 uppercase tracking-wide">Reset Password</h2>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">Account Recovery</p>
                </div>

                {{-- Informational Message --}}
                <div class="bg-blue-50 border border-blue-200 text-gray-600 text-xs p-4 rounded-md mb-6 leading-relaxed text-center font-medium">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
                </div>

                {{-- Session Status --}}
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf 
                    
                    {{-- Email Input --}}
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-1.5">Email Address *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                   class="w-full pl-10 pr-4 py-3 rounded-md border-gray-300 focus:border-blue-600 focus:ring focus:ring-blue-600/20 text-sm transition-shadow" 
                                   placeholder="Enter your registered email">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-500" />
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-blue-800 hover:bg-blue-900 text-white font-bold rounded-md uppercase tracking-wider text-sm transition-colors">
                        Email Reset Link
                    </button>
                </form>

                {{-- Back to Login Link --}}
                <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-4">Remembered your password?</p>
                    <a href="{{ route('login') }}" class="inline-block px-6 py-2.5 bg-gray-100 hover:bg-white border border-transparent hover:border-gray-300 text-gray-700 text-xs font-bold uppercase tracking-wider rounded-full transition-all">
                        Back to Login
                    </a>
                </div>

            </div>
        </div>
        
        <div class="mt-8 text-center">
             <p class="text-gray-200 text-xs font-medium uppercase tracking-widest drop-shadow-md">
                &copy; {{ date('Y') }} National Academy of Sports
            </p>
        </div>

    </div>

</body>
</html>