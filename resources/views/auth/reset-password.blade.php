<x-guest-layout>
    {{-- HEADER SECTION --}}
    <div class="text-center mb-8">
        {{-- Logo --}}
        <img src="{{ asset('images/nas/logo-transparent.png') }}" class="h-24 mx-auto mb-4 drop-shadow-md object-contain" alt="NAS Logo">
        
        {{-- Titles --}}
        <h1 class="text-2xl font-extrabold text-blue-900 tracking-tight">NAS SAIS</h1>
        <h2 class="text-lg font-bold text-gray-600 uppercase tracking-widest mt-1">Reset Password</h2>
        
        <p class="text-sm text-gray-500 mt-3">
            Please create a strong password for your account to ensure security.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-5">
            <label for="email" class="block text-xs font-bold text-gray-600 uppercase mb-2">Email Address</label>
            {{-- Added 'readonly' kasi galing na ito sa email link, para hindi magkamali ang user --}}
            <input id="email" 
                   type="email" 
                   name="email" 
                   class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-300 text-gray-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all cursor-not-allowed" 
                   value="{{ old('email', $request->email) }}" 
                   required 
                   readonly>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mb-5">
            <label for="password" class="block text-xs font-bold text-gray-600 uppercase mb-2">New Password</label>
            <input id="password" 
                   type="password" 
                   name="password" 
                   class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all" 
                   required 
                   autocomplete="new-password"
                   placeholder="Enter new password">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mb-8">
            <label for="password_confirmation" class="block text-xs font-bold text-gray-600 uppercase mb-2">Confirm Password</label>
            <input id="password_confirmation" 
                   type="password" 
                   name="password_confirmation" 
                   class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all" 
                   required 
                   autocomplete="new-password"
                   placeholder="Retype new password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end">
            <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 rounded-lg shadow-md transition transform hover:-translate-y-0.5 flex justify-center items-center uppercase tracking-wider text-sm">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
</x-guest-layout>