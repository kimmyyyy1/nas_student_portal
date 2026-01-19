<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password | NAS SAIS</title>
        
    <link rel="icon" type="image/png" href="{{ asset('images/nas/favicon1.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- ALPINE.JS --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Poppins', sans-serif !important; }
        
        /* Hide default browser password toggle */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear { display: none; }
        input[type="password"]::-webkit-contacts-auto-fill-button,
        input[type="password"]::-webkit-credentials-auto-fill-button {
            visibility: hidden; pointer-events: none; position: absolute; right: 0;
        }

        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { display: none; }
        .custom-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-900 h-screen w-full overflow-hidden relative flex flex-col items-center justify-center">

    {{-- BACKGROUND IMAGE & OVERLAY --}}
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/nas/IMG_20250429_105924_472.jpg') }}" class="w-full h-full object-cover opacity-60" alt="Background">
    </div>
    <div class="absolute inset-0 z-0 bg-gradient-to-br from-blue-900/80 via-blue-900/60 to-black/70 backdrop-blur-[1px]"></div>

    <div class="relative z-10 w-full h-full flex flex-col justify-center items-center px-4 py-4">
        
        {{-- MAIN CARD --}}
        <div class="w-full max-w-md bg-white rounded-xl shadow-2xl border-t-[6px] border-yellow-400 overflow-hidden flex flex-col max-h-[90vh]">
            
            <div class="overflow-y-auto px-8 py-8 custom-scrollbar">
                
                {{-- HEADER --}}
                <div class="text-center mb-8">
                    <img src="{{ asset('images/nas/stack.png') }}" class="h-24 w-auto mx-auto mb-3 drop-shadow-md hover:scale-105 transition-transform" alt="NAS Logo">
                    
                    <h2 class="text-2xl font-extrabold text-blue-900 tracking-tight">Reset Password</h2>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Create a new secure password</p>
                </div>

                {{-- FORM --}}
                <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    {{-- EMAIL ADDRESS (READONLY) --}}
                    <div>
                        <label class="block font-bold text-[10px] text-gray-500 uppercase mb-1 ml-1">Email Address</label>
                        <input type="email" name="email" 
                               value="{{ old('email', $request->email) }}" 
                               required readonly tabindex="-1"
                               class="block w-full rounded-lg border-gray-300 bg-gray-100 text-gray-500 cursor-not-allowed p-3 text-sm focus:ring-blue-500 focus:border-blue-500 transition shadow-inner">
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                    </div>

                    {{-- NEW PASSWORD --}}
                    <div x-data="{ show: false }">
                        <label class="block font-bold text-[10px] text-gray-500 uppercase mb-1 ml-1 tracking-tight">New Password *</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" required autofocus autocomplete="new-password"
                                   class="block w-full rounded-lg border-gray-300 bg-gray-50 p-3 pr-10 text-sm focus:ring-blue-500 focus:border-blue-500 transition" 
                                   placeholder="Enter new password">
                            
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 focus:outline-none transition" title="Show/Hide">
                                <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg x-show="show" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.572-2.872m2.197-2.197A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.572 2.872M9 9l3 3m0 0l3 3m-3-3a3 3 0 01-3 3m3-3a3 3 0 013-3m3-3l3 3" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" /></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                    </div>

                    {{-- CONFIRM PASSWORD --}}
                    <div x-data="{ showConfirm: false }">
                        <label class="block font-bold text-[10px] text-gray-500 uppercase mb-1 ml-1 tracking-tight">Confirm Password *</label>
                        <div class="relative">
                            <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                                   class="block w-full rounded-lg border-gray-300 bg-gray-50 p-3 pr-10 text-sm focus:ring-blue-500 focus:border-blue-500 transition" 
                                   placeholder="Retype new password">
                            
                            <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 focus:outline-none transition" title="Show/Hide">
                                <svg x-show="!showConfirm" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg x-show="showConfirm" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.572-2.872m2.197-2.197A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.572 2.872M9 9l3 3m0 0l3 3m-3-3a3 3 0 01-3 3m3-3a3 3 0 013-3m3-3l3 3" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" /></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs" />
                    </div>

                    {{-- SUBMIT BUTTON --}}
                    <button type="submit" class="w-full mt-2 py-3 bg-gradient-to-r from-blue-800 to-blue-900 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-lg shadow-lg transform transition hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-blue-200 uppercase tracking-widest text-sm">
                        RESET PASSWORD
                    </button>
                    
                    {{-- BACK TO LOGIN --}}
                    <div class="text-center pt-2 border-t border-gray-100 mt-4">
                        <a href="{{ route('login') }}" class="inline-block text-xs font-bold text-gray-500 hover:text-blue-700 transition uppercase tracking-wide">
                            <span class="mr-1">&larr;</span> Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        {{-- FOOTER --}}
        <div class="mt-4 text-center pb-2">
             <p class="text-gray-300 text-[10px] opacity-80 uppercase tracking-widest font-medium drop-shadow-md">
                &copy; {{ date('Y') }} National Academy of Sports
            </p>
        </div>
    </div>
</body>
</html>