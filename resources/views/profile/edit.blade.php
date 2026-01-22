@php
    // Determine the layout based on user role
    $role = Auth::user()->role;
    $layout = 'app-layout'; // Default (Admin)

    if ($role === 'student') {
        $layout = 'student-layout';
    } elseif ($role === 'applicant') {
        $layout = 'applicant-layout';
    }

    // 👇 Check if user is a student to lock fields
    $isStudent = ($role === 'student'); 
@endphp

<x-dynamic-component :component="$layout">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile Settings') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- Success Message --}}
            @if (session('status') === 'profile-updated')
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex items-center" 
                     x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                    <i class='bx bx-check-circle text-2xl mr-2'></i>
                    <div>
                        <p class="font-bold">Success!</p>
                        <p class="text-sm">Profile details updated successfully.</p>
                    </div>
                </div>
            @endif

            {{-- SINGLE CARD CONTAINER --}}
            <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-100">
                
                {{-- Header / Banner --}}
                <div class="bg-indigo-700 p-6 sm:p-8 flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6 text-center sm:text-left">
                    <div class="flex-shrink-0">
                        <div class="h-20 w-20 rounded-full bg-white flex items-center justify-center text-indigo-700 text-3xl font-bold border-4 border-indigo-300 shadow-md mx-auto sm:mx-0">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $user->name }}</h1>
                        <div class="flex items-center justify-center sm:justify-start text-indigo-200 mt-1 text-sm sm:text-base">
                            <i class='bx bx-envelope mr-1'></i> {{ $user->email }}
                        </div>
                        <span class="inline-block mt-3 px-3 py-1 rounded-full bg-indigo-900 text-[10px] text-white font-bold uppercase tracking-widest border border-indigo-500">
                            {{ ucfirst($user->role ?? 'User') }} Account
                        </span>
                    </div>
                </div>

                {{-- Unified Form --}}
                <form method="post" action="{{ route('profile.update') }}" class="p-6 sm:p-8">
                    @csrf
                    @method('patch')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 sm:gap-10">
                        
                        {{-- LEFT COLUMN: Basic Information --}}
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-4 sm:mb-6 border-b pb-2 flex items-center">
                                <i class='bx bx-user-circle mr-2 text-indigo-600 text-xl'></i> Account Information
                            </h3>

                            <div class="space-y-4 sm:space-y-6">
                                {{-- 👇 MODIFIED: Name Field (ReadOnly if Student) --}}
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                                        {{ $isStudent ? 'readonly' : '' }}
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 
                                        {{ $isStudent ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}">
                                    
                                    @if($isStudent)
                                        <p class="text-[10px] text-gray-400 mt-1 italic"><i class='bx bx-lock-alt'></i> Contact Registrar to update name.</p>
                                    @endif
                                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- 👇 MODIFIED: Email Field (ReadOnly if Student) --}}
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                                        {{ $isStudent ? 'readonly' : '' }}
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5
                                        {{ $isStudent ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}">
                                    
                                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror

                                    {{-- Email Verification Notice --}}
                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                        <div class="mt-2 text-xs text-gray-800 bg-yellow-50 p-2 rounded border border-yellow-200">
                                            {{ __('Your email address is unverified.') }}
                                            <button form="send-verification" class="underline text-indigo-600 hover:text-indigo-900 font-bold">
                                                {{ __('Click here to re-send the verification email.') }}
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT COLUMN: Change Password --}}
                        <div class="bg-gray-50 p-5 sm:p-6 rounded-xl border border-gray-200 shadow-inner">
                            <h3 class="text-lg font-bold text-gray-800 mb-2 flex items-center">
                                <i class='bx bx-lock-alt mr-2 text-indigo-600 text-xl'></i> Security
                            </h3>
                            <p class="text-xs text-gray-500 mb-4 sm:mb-6 border-b pb-3">
                                Change Password (leave blank to keep current)
                            </p>

                            <div class="space-y-4">
                                {{-- Current Password --}}
                                <div>
                                    <label for="current_password" class="block text-xs font-bold text-gray-600 uppercase mb-1">Current Password</label>
                                    <input type="password" name="current_password" id="current_password" autocomplete="current-password"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    @error('current_password') <span class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</span> @enderror
                                </div>

                                {{-- New Password --}}
                                <div>
                                    <label for="password" class="block text-xs font-bold text-gray-600 uppercase mb-1">New Password</label>
                                    <input type="password" name="password" id="password" autocomplete="new-password"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    @error('password') <span class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</span> @enderror
                                </div>

                                {{-- Confirm Password --}}
                                <div>
                                    <label for="password_confirmation" class="block text-xs font-bold text-gray-600 uppercase mb-1">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    @error('password_confirmation') <span class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- SINGLE SAVE BUTTON --}}
                    <div class="mt-8 sm:mt-10 flex justify-end border-t border-gray-100 pt-6">
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3 bg-indigo-700 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-800 focus:bg-indigo-800 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class='bx bx-save text-lg mr-2'></i> Save All Changes
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-dynamic-component>