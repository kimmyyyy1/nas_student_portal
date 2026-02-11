<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NAS SAIS</title>
        
    <link rel="icon" type="image/png" href="{{ asset('images/nas/favicon1.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Poppins', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Hide default edge/ie password reveal button */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear { display: none; }
    </style>
</head>
<body class="antialiased text-gray-800 bg-gray-100 min-h-screen relative overflow-x-hidden">

    {{-- Background Image & Overlay --}}
    <div class="fixed inset-0 z-0 pointer-events-none">
        <img src="{{ asset('images/nas/IMG_20250429_105924_472.jpg') }}" class="w-full h-full object-cover" alt="NAS Campus"> 
        <div class="absolute inset-0 bg-black/40"></div>
    </div>
    
    <div class="relative z-10 min-h-screen flex flex-col justify-center items-center p-4 sm:p-6">

        <div class="w-full max-w-[420px] bg-white/80 rounded-lg shadow-xl hover:shadow-2xl border-t-[5px] border-yellow-400 transition-all duration-300 md:hover:-translate-y-1">
            <div class="p-8 sm:p-10">
                
                <div class="text-center mb-8">
                    <a href="/" class="inline-block">
                        <img src="{{ asset('images/nas/stack.png') }}" class="h-32 w-auto mx-auto drop-shadow-sm" alt="NAS Logo">
                    </a>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded-md text-sm mb-6">
                        <strong>Login Failed.</strong> Please check your credentials.
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf 
                    
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-1.5">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                   class="w-full pl-10 pr-4 py-3 rounded-md border-gray-300 focus:border-blue-600 focus:ring focus:ring-blue-600/20 text-sm transition-shadow" 
                                   placeholder="Enter your email">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-500" />
                    </div>

                    <div class="mb-4" x-data="{ show: false }">
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-1.5">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            
                            <input :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                                   class="w-full pl-10 pr-10 py-3 rounded-md border-gray-300 focus:border-blue-600 focus:ring focus:ring-blue-600/20 text-sm transition-shadow" 
                                   placeholder="Enter your password">

                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg x-show="show" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.572-2.872m2.197-2.197A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.572 2.872M9 9l3 3m0 0l3 3m-3-3a3 3 0 01-3 3m3-3a3 3 0 013-3m3-3l3 3" /></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-500" />
                    </div>

                    <div class="flex items-center justify-between mb-6 mt-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-800 shadow-sm focus:ring-blue-500" {{ old('remember') ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-600 font-medium">Remember me</span>
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-blue-700 hover:text-blue-900 hover:underline font-semibold transition-colors">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-blue-800 hover:bg-blue-900 text-white font-bold rounded-md uppercase tracking-wider text-sm transition-colors">
                        Log In
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-4">No account yet?</p>
                    <a href="{{ route('register') }}" class="inline-block px-6 py-2.5 bg-gray-100 hover:bg-white border border-transparent hover:border-gray-300 text-gray-700 text-xs font-bold uppercase tracking-wider rounded-full transition-all">
                        Register as Applicant
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