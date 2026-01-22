<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NAS SAIS - Login</title>
        
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
        * { font-family: 'Poppins', sans-serif !important; box-sizing: border-box; }
        [x-cloak] { display: none !important; }

        /* 2. BODY SETTINGS */
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background-color: #111827;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }

        /* 3. INPUT FIELD FIXES */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear { display: none; }
        input[type="password"]::-webkit-contacts-auto-fill-button,
        input[type="password"]::-webkit-credentials-auto-fill-button {
            visibility: hidden; pointer-events: none; position: absolute; right: 0;
        }

        /* 4. BACKGROUND LAYERS */
        .bg-container {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;
        }
        .bg-image {
            width: 100%; height: 100%; object-fit: cover; opacity: 0.6;
        }
        .bg-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(to bottom right, rgba(30, 58, 138, 0.8), rgba(17, 24, 39, 0.8));
            backdrop-filter: blur(3px);
        }

        /* 5. MAIN WRAPPER (CENTERING MAGIC) */
        .main-wrapper {
            min-height: 100vh;      /* Full viewport height */
            min-height: 100dvh;     /* Mobile dynamic height */
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center; /* Center Vertically on Desktop */
            align-items: center;     /* Center Horizontally */
            padding: 2rem 1rem;      /* Breathing room */
        }

        /* 6. LOGIN CARD DESIGN */
        .login-card {
            background-color: white;
            width: 100%;
            max-width: 420px;       /* Optimal width for Desktop */
            border-radius: 1rem;    /* Rounded corners */
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border-top: 5px solid #facc15; /* Yellow Accent */
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        /* Slight hover effect on Desktop */
        @media (min-width: 768px) {
            .login-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.6);
            }
        }

        .card-content { padding: 2.5rem 2rem; }

        /* 7. FORM ELEMENTS */
        .input-group { margin-bottom: 1.25rem; }
        .input-label {
            display: block; font-size: 0.75rem; font-weight: 700; color: #4b5563;
            text-transform: uppercase; margin-bottom: 0.35rem; letter-spacing: 0.05em;
        }
        .form-input {
            width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem;
            font-size: 0.9rem; border-radius: 0.5rem;
            border: 1px solid #d1d5db; background-color: #f9fafb;
            transition: all 0.2s;
        }
        .form-input:focus {
            outline: none; border-color: #2563eb; background-color: #fff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Buttons */
        .btn-login {
            width: 100%; padding: 0.85rem;
            background-color: #1e40af; color: white;
            font-weight: 700; border-radius: 0.5rem; border: none;
            cursor: pointer; text-transform: uppercase; letter-spacing: 0.05em;
            transition: background-color 0.2s; margin-top: 0.5rem;
        }
        .btn-login:hover { background-color: #1e3a8a; }

        .btn-register {
            display: inline-block; font-size: 0.75rem; font-weight: 600;
            color: #4b5563; background-color: #f3f4f6;
            padding: 0.6rem 1.5rem; border-radius: 9999px;
            text-decoration: none; text-transform: uppercase; letter-spacing: 0.05em;
            transition: all 0.2s; border: 1px solid transparent;
        }
        .btn-register:hover {
            background-color: #fff; border-color: #d1d5db; color: #1f2937;
        }

        /* Utilities */
        .icon { position: absolute; top: 50%; left: 0.85rem; transform: translateY(-50%); width: 1.25rem; height: 1.25rem; color: #9ca3af; }
        .flex-between { display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; }
    </style>
</head>
<body>

    {{-- 1. BACKGROUND --}}
    <div class="bg-container">
        <img src="{{ asset('images/nas/IMG_20250429_105924_472.jpg') }}" class="bg-image" alt="Background"> 
        <div class="bg-overlay"></div>
    </div>
    
    {{-- 2. MAIN LAYOUT WRAPPER --}}
    <div class="main-wrapper">

        {{-- LOGIN CARD --}}
        <div class="login-card">
            <div class="card-content">
                
                {{-- Logo Section --}}
                <div style="text-align: center; margin-bottom: 2rem;">
                    <a href="/">
                        <img src="{{ asset('images/nas/stack.png') }}" 
                             style="height: 10rem; width: auto; margin: 0 auto; display: block; filter: drop-shadow(0 4px 3px rgba(0,0,0,0.07));" 
                             alt="NAS Logo">
                    </a>
                </div>

                {{-- Status & Errors --}}
                <x-auth-session-status class="mb-4" :status="session('status')" />

                @if ($errors->any())
                    <div style="background-color: #fef2f2; border-left: 4px solid #ef4444; color: #b91c1c; padding: 0.75rem; border-radius: 0.375rem; font-size: 0.85rem; margin-bottom: 1.5rem;">
                        <strong>Login Failed.</strong> Please check your credentials.
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf 
                    
                    {{-- Email Input --}}
                    <div class="input-group">
                        <label class="input-label">Email Address</label>
                        <div style="position: relative;">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                   class="form-input" 
                                   placeholder="Enter your email">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem;" />
                    </div>

                    {{-- Password Input --}}
                    <div class="input-group" x-data="{ show: false }">
                        <label class="input-label">Password</label>
                        <div style="position: relative;">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            
                            <input :type="show ? 'text' : 'password'" 
                                   name="password" 
                                   required 
                                   autocomplete="current-password"
                                   class="form-input" 
                                   style="padding-right: 2.5rem;" 
                                   placeholder="Enter your password">

                            <button type="button" @click="show = !show" style="position: absolute; top: 0; bottom: 0; right: 0; padding-right: 0.85rem; display: flex; align-items: center; background: none; border: none; cursor: pointer; color: #9ca3af; z-index: 10;">
                                <svg x-show="!show" style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg x-show="show" x-cloak style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.572-2.872m2.197-2.197A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.572 2.872M9 9l3 3m0 0l3 3m-3-3a3 3 0 01-3 3m3-3a3 3 0 013-3m3-3l3 3" /></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem;" />
                    </div>

                    {{-- Remember & Forgot --}}
                    <div class="flex-between">
                        <label style="display: inline-flex; align-items: center; cursor: pointer;">
                            <input type="checkbox" name="remember" style="border-radius: 0.25rem; color: #2563eb; width: 1rem; height: 1rem;">
                            <span style="margin-left: 0.5rem; color: #4b5563; font-weight: 500;">Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" style="color: #2563eb; font-weight: 600; text-decoration: none; font-size: 0.8rem;">Forgot?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn-login">
                        Log In
                    </button>
                </form>

                {{-- Register Link --}}
                <div style="margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid #f3f4f6; text-align: center;">
                    <p style="font-size: 0.7rem; color: #9ca3af; font-weight: 600; text-transform: uppercase; margin-bottom: 0.75rem;">No account yet?</p>
                    
                    {{-- 👇 FIXED ROUTE: Using 'register' to prevent RouteNotFoundException --}}
                    <a href="{{ route('register') }}" class="btn-register">
                        Register as Applicant
                    </a>
                </div>

            </div>
        </div>
        
        {{-- Footer (Responsive Positioning) --}}
        <div style="margin-top: 2rem; text-align: center;">
             <p style="color: #d1d5db; font-size: 0.7rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 500; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">
                &copy; {{ date('Y') }} National Academy of Sports
            </p>
        </div>

    </div>

</body>
</html>