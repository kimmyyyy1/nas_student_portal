<x-guest-layout>
    {{-- HEADER SECTION (Inside Card) --}}
    <div class="text-center mb-8">
        {{-- Logo: Siguraduhin na tama ang path ng logo mo --}}
        <a href="/" class="inline-block">
            <img src="{{ asset('images/nas/logo-transparent.png') }}" class="h-20 w-auto mx-auto mb-4 drop-shadow-md object-contain" alt="NAS Logo">
        </a>
        
        {{-- Titles --}}
        <h1 class="text-3xl font-extrabold text-blue-900 tracking-tight">NAS SAIS</h1>
        <h2 class="text-sm font-bold text-gray-500 uppercase tracking-widest mt-1">Reset Password</h2>
        
        <p class="text-xs text-gray-400 mt-3 px-4">
            Create a new, strong password for your account.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-5">
            <label for="email" class="block text-xs font-bold text-gray-600 uppercase mb-2">Email Address</label>
            {{-- Added 'readonly' at gray background para hindi na mapalitan ng user (iwas error) --}}
            <input id="email" 
                   type="email" 
                   name="email" 
                   class="block w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-100 text-gray-500 cursor-not-allowed focus:border-indigo-500 focus:ring-indigo-500 shadow-sm sm:text-sm" 
                   value="{{ old('email', $request->email) }}" 
                   required 
                   readonly 
                   tabindex="-1" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mb-5">
            <label for="password" class="block text-xs font-bold text-gray-600 uppercase mb-2">New Password</label>
            <input id="password" 
                   type="password" 
                   name="password" 
                   class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm sm:text-sm transition-colors" 
                   required 
                   autofocus
                   autocomplete="new-password" 
                   placeholder="Enter your new password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mb-8">
            <label for="password_confirmation" class="block text-xs font-bold text-gray-600 uppercase mb-2">Confirm Password</label>
            <input id="password_confirmation" 
                   type="password" 
                   name="password_confirmation" 
                   class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm sm:text-sm transition-colors" 
                   required 
                   autocomplete="new-password" 
                   placeholder="Retype your new password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end">
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-800 hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:-translate-y-0.5 uppercase tracking-widest">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
</x-guest-layout>