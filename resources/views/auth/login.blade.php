<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | NAS SAIS</title>
    
    <link rel="icon" href="/images/nas/nas-logo-spotlight.jpg">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { display: none; }
        .custom-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-900 h-screen w-full overflow-hidden relative flex items-center justify-center">

    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/nas/IMG_20250429_105924_472.jpg') }}" class="w-full h-full object-cover opacity-60" alt="Background"
             onerror="this.style.display='none';"> 
    </div>
    
    <div class="absolute inset-0 z-0 bg-gradient-to-br from-blue-900/80 via-blue-900/60 to-black/70 backdrop-blur-[1px]"></div>

    <div class="relative z-10 w-full h-full flex flex-col justify-center px-4 sm:px-0">
        
        <div class="w-full max-w-sm mx-auto bg-white rounded-2xl shadow-2xl border-t-[6px] border-yellow-400 overflow-hidden flex flex-col max-h-[90vh]">
            
            <div class="overflow-y-auto px-6 py-6 custom-scrollbar">
                
                <div class="text-center mb-6">
                    <img src="{{ asset('images/nas/nas-logo-sidebar.png') }}" class="h-16 w-auto mx-auto mb-3 drop-shadow-md" alt="NAS Logo">
                    <h1 class="text-2xl font-extrabold text-blue-900 tracking-tight">NAS SAIS</h1>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Student-Athlete Information System</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 p-2 rounded text-xs">
                        <strong>Login Failed.</strong> Please check your credentials.
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf 
                    <div>
                        <label class="block font-bold text-[10px] text-gray-500 uppercase mb-1 ml-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                   class="block w-full pl-9 pr-3 py-2 rounded-lg border-gray-300 bg-gray-50 text-sm focus:ring-blue-500 focus:border-blue-500 transition" 
                                   placeholder="Enter email">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                    </div>

                    <div x-data="{ show: false }">
                        <label class="block font-bold text-[10px] text-gray-500 uppercase mb-1 ml-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            
                            <input :type="show ? 'text' : 'password'" 
                                   name="password" 
                                   required 
                                   autocomplete="current-password"
                                   class="block w-full pl-9 pr-10 py-2 rounded-lg border-gray-300 bg-gray-50 text-sm focus:ring-blue-500 focus:border-blue-500 transition" 
                                   placeholder="Enter password">

                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 focus:outline-none">
                                <svg x-show="!show" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg x-show="show" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.572-2.872m2.197-2.197A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.572 2.872M9 9l3 3m0 0l3 3m-3-3a3 3 0 01-3 3m3-3a3 3 0 013-3m3-3l3 3" /></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                    </div>

                    <div class="flex items-center justify-between text-xs">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-gray-600 font-medium">Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-blue-600 hover:underline font-bold" href="{{ route('password.request') }}">Forgot?</a>
                        @endif
                    </div>

                    <button type="submit" class="w-full py-3 bg-blue-800 hover:bg-blue-900 text-white font-bold rounded-lg shadow-md transition transform hover:scale-[1.02] text-sm uppercase tracking-wider">
                        LOG IN
                    </button>
                </form>

                <div class="mt-5 pt-4 border-t border-gray-100 text-center">
                    <p class="text-[10px] text-gray-400 font-bold uppercase mb-2">No account yet?</p>
                    <a href="{{ route('register') }}" class="inline-block text-xs font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 py-2 px-6 rounded-full transition uppercase tracking-wide">
                        Register as Applicant
                    </a>
                </div>

            </div>
        </div>
        
        <div class="mt-4 text-center pb-2">
             <p class="text-gray-300 text-[10px] opacity-80 uppercase tracking-widest font-medium drop-shadow-md">
                &copy; {{ date('Y') }} National Academy of Sports
            </p>
        </div>

    </div>
</body>
</html>