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
        * { font-family: 'Poppins', sans-serif !important; box-sizing: border-box; }
        [x-cloak] { display: none !important; }

        /* 2. BODY SETTINGS */
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background-color: #f3f4f6;
            overflow-x: hidden;
            overflow-y: auto;
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
            width: 100%; height: 100%; object-fit: cover;
        }
        .bg-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.4); /* Dark tint */
        }

        /* 5. MAIN WRAPPER */
        .main-wrapper {
            min-height: 100vh;
            min-height: 100dvh;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem 1rem;
        }

        /* 6. CARD DESIGN - GINAWANG WIDER (640px) */
        .login-card {
            background-color: white;
            width: 100%;
            max-width: 640px; /* Tamang lapad lang, hindi sagad sa screen */
            border-radius: 0.75rem; 
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.3);
            border-top: 5px solid #facc15; 
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        @media (min-width: 768px) {
            .login-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            }
        }

        .card-content { padding: 2.5rem; }

        /* 7. FORM ELEMENTS */
        .input-group { margin-bottom: 1.25rem; }
        .input-label {
            display: block; font-size: 0.75rem; font-weight: 700; color: #4b5563;
            text-transform: uppercase; margin-bottom: 0.35rem; letter-spacing: 0.05em;
        }
        .form-input {
            width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem;
            font-size: 0.9rem; border-radius: 0.375rem;
            border: 1px solid #9ca3af; background-color: #fff;
            transition: all 0.2s;
        }
        .form-input:focus {
            outline: none; border-color: #2563eb; 
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        /* 8. RESPONSIVE GRID (Para tumabi ang mga fields sa Desktop) */
        .grid-responsive {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        @media (min-width: 640px) {
            .grid-responsive {
                grid-template-columns: 1fr 1fr;
                gap: 1.25rem;
            }
        }
        .grid-responsive .input-group { margin-bottom: 0; }

        /* 9. BUTTONS */
        .btn-login {
            width: 100%; padding: 0.85rem;
            background-color: #1e40af; color: white;
            font-weight: 700; border-radius: 0.375rem; border: none;
            cursor: pointer; text-transform: uppercase; letter-spacing: 0.05em;
            transition: background-color 0.2s; margin-top: 1rem;
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
        .icon { position: absolute; top: 50%; left: 0.85rem; transform: translateY(-50%); width: 1.25rem; height: 1.25rem; color: #6b7280; pointer-events: none; }
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

        {{-- REGISTER CARD --}}
        <div class="login-card">
            <div class="card-content">
                
                {{-- Logo Section --}}
                <div style="text-align: center; margin-bottom: 2rem;">
                    <a href="/">
                        {{-- Pinaliit ng konti ang logo para makatipid sa vertical space --}}
                        <img src="{{ asset('images/nas/stack.png') }}" 
                             style="height: 6.5rem; width: auto; margin: 0 auto; display: block; filter: drop-shadow(0 4px 3px rgba(0,0,0,0.07));" 
                             alt="NAS Logo">
                    </a>
                    <h2 style="font-size: 1.25rem; font-weight: 800; color: #1e3a8a; margin-top: 1rem; text-transform: uppercase; letter-spacing: 0.05em;">Create Account</h2>
                    <p style="font-size: 0.65rem; color: #6b7280; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em;">Applicant Registration</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf 

                    {{-- ROW 1: First Name & Middle Name --}}
                    <div class="grid-responsive mb-5">
                        <div class="input-group">
                            <label class="input-label">First Name *</label>
                            <div style="position: relative;">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" required autofocus
                                       class="form-input" 
                                       placeholder="Juan">
                            </div>
                            <x-input-error :messages="$errors->get('first_name')" class="mt-1 text-xs text-red-600" />
                        </div>

                        <div class="input-group">
                            <label class="input-label">Middle Name</label>
                            <div style="position: relative;">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                                       class="form-input" 
                                       placeholder="(Optional)">
                            </div>
                        </div>
                    </div>

                    {{-- ROW 2: Last Name & Email Address --}}
                    <div class="grid-responsive mb-5">
                        <div class="input-group">
                            <label class="input-label">Last Name *</label>
                            <div style="position: relative;">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                       class="form-input" 
                                       placeholder="Dela Cruz">
                            </div>
                            <x-input-error :messages="$errors->get('last_name')" class="mt-1 text-xs text-red-600" />
                        </div>
                        
                        <div class="input-group">
                            <label class="input-label">Email Address *</label>
                            <div style="position: relative;">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                                <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                                       class="form-input" 
                                       placeholder="juan@example.com">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" style="color: #ef4444;" />
                        </div>
                    </div>

                    {{-- ROW 3: Password & Confirm Password --}}
                    <div class="grid-responsive mb-2">
                        <div class="input-group" x-data="{ show: false }">
                            <label class="input-label">Password *</label>
                            <div style="position: relative;">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                
                                <input :type="show ? 'text' : 'password'" 
                                       name="password" 
                                       required 
                                       autocomplete="new-password"
                                       class="form-input" 
                                       style="padding-right: 2.5rem;" 
                                       placeholder="Create a password">

                                <button type="button" @click="show = !show" style="position: absolute; top: 0; bottom: 0; right: 0; padding-right: 0.85rem; display: flex; align-items: center; background: none; border: none; cursor: pointer; color: #9ca3af; z-index: 10;">
                                    <svg x-show="!show" style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg x-show="show" x-cloak style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.572-2.872m2.197-2.197A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.572 2.872M9 9l3 3m0 0l3 3m-3-3a3 3 0 01-3 3m3-3a3 3 0 013-3m3-3l3 3" /></svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" style="color: #ef4444;" />
                        </div>

                        <div class="input-group" x-data="{ showConfirm: false }">
                            <label class="input-label">Confirm Password *</label>
                            <div style="position: relative;">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                
                                <input :type="showConfirm ? 'text' : 'password'" 
                                       name="password_confirmation" 
                                       required 
                                       class="form-input" 
                                       style="padding-right: 2.5rem;" 
                                       placeholder="Repeat password">

                                <button type="button" @click="showConfirm = !showConfirm" style="position: absolute; top: 0; bottom: 0; right: 0; padding-right: 0.85rem; display: flex; align-items: center; background: none; border: none; cursor: pointer; color: #9ca3af; z-index: 10;">
                                    <svg x-show="!showConfirm" style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg x-show="showConfirm" x-cloak style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.572-2.872m2.197-2.197A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.572 2.872M9 9l3 3m0 0l3 3m-3-3a3 3 0 01-3 3m3-3a3 3 0 013-3m3-3l3 3" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-login">
                        Register
                    </button>
                </form>

                {{-- Login Link --}}
                <div style="margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid #f3f4f6; text-align: center;">
                    <p style="font-size: 0.7rem; color: #9ca3af; font-weight: 600; text-transform: uppercase; margin-bottom: 0.75rem;">Already have an account?</p>
                    
                    <a href="{{ route('login') }}" class="btn-register">
                        Back to Login
                    </a>
                </div>

            </div>
        </div>
        
        {{-- Footer --}}
        <div style="margin-top: 2rem; text-align: center;">
             <p style="color: #e5e7eb; font-size: 0.7rem; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600; text-shadow: 0 2px 4px rgba(0,0,0,0.8);">
                &copy; {{ date('Y') }} National Academy of Sports
            </p>
        </div>

    </div>

</body>
</html>