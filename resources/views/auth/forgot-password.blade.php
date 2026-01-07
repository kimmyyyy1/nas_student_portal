<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password | NAS SAIS</title>
    
    <link rel="icon" type="image/jpeg" href="/images/nas/favicon.jpg">

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
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Reset Password</p>
                </div>

                <div class="mb-4 text-xs text-gray-600 text-center leading-relaxed bg-blue-50 p-3 rounded-lg border border-blue-100">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block font-bold text-[10px] text-gray-500 uppercase mb-1 ml-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="block w-full pl-9 pr-3 py-2 rounded-lg border-gray-300 bg-gray-50 text-sm focus:ring-blue-500 focus:border-blue-500 transition" 
                                   placeholder="Enter your registered email">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                    </div>

                    <button type="submit" class="w-full py-3 bg-blue-800 hover:bg-blue-900 text-white font-bold rounded-lg shadow-md transition transform hover:scale-[1.02] text-sm uppercase tracking-wider">
                        Email Password Reset Link
                    </button>
                </form>

                <div class="mt-5 pt-4 border-t border-gray-100 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center text-xs font-bold text-gray-500 hover:text-blue-700 transition uppercase tracking-wide group">
                        <svg class="w-4 h-4 mr-1 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Login
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