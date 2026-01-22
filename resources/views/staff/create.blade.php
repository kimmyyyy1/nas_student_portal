<x-app-layout>
    <x-slot name="header">
        
        {{-- ============================================================= --}}
        {{-- 📱 MOBILE HEADER: Compact Badge & Live Indicator              --}}
        {{-- ============================================================= --}}
        <div class="flex md:hidden items-center justify-between w-full py-1">
            
            {{-- Badge --}}
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700 uppercase shadow-sm border border-indigo-200">
                <i class='bx bxs-user-plus mr-1.5 text-sm'></i> Add Staff
            </span>

            {{-- Live Indicator --}}
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mr-1"></span> LIVE
            </span>

        </div>

        {{-- ============================================================= --}}
        {{-- 💻 DESKTOP HEADER: Standard View                              --}}
        {{-- ============================================================= --}}
        <div class="hidden md:flex items-center justify-between w-full py-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                {{ __('Add New Staff Member') }}
                <span class="ml-3 px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                    <span class="w-2 h-2 bg-red-600 rounded-full mr-1"></span> LIVE
                </span>
            </h2>
        </div>

    </x-slot>

    {{-- 👇 FIX: 'py-2' mobile, 'md:py-12' desktop --}}
    <div class="py-2 md:py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- 🟢 BACK BUTTON REMOVED (Cancel button below serves as back) --}}

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm text-sm">
                            <div class="flex items-center mb-2">
                                <i class='bx bx-error-circle mr-2 text-xl'></i>
                                <span class="font-bold">Please fix the following errors:</span>
                            </div>
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error) 
                                    <li>{{ $error }}</li> 
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('staff.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Employee ID <span class="text-red-500">*</span></label>
                                <input type="text" name="employee_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required value="{{ old('employee_id') }}" placeholder="e.g. T-2025-001">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">System Role <span class="text-red-500">*</span></label>
                                <select name="role" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm cursor-pointer" required>
                                    <option value="" disabled selected>-- Select Role --</option>
                                    <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher / Adviser</option>
                                    <option value="coach" {{ old('role') == 'coach' ? 'selected' : '' }}>Coach</option>
                                    <option value="sass" {{ old('role') == 'sass' ? 'selected' : '' }}>SASS (Medical/Welfare)</option>
                                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff / Support</option> 
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required value="{{ old('first_name') }}">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required value="{{ old('last_name') }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Email Address (For Login) <span class="text-red-500">*</span></label>
                            <input type="email" name="email" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required value="{{ old('email') }}" placeholder="staff@nas.edu">
                            <p class="text-[10px] text-gray-500 mt-1 italic">This email will be used as their username. Default password is 'password'.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Contact Number</label>
                                <input type="text" name="contact_number" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" value="{{ old('contact_number') }}">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Department</label>
                                <input type="text" name="department" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" value="{{ old('department') }}" placeholder="e.g. Science">
                            </div>
                             <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Position</label>
                                <input type="text" name="position" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" value="{{ old('position') }}" placeholder="e.g. Head Teacher">
                            </div>
                        </div>

                        {{-- 👇 UPDATED BUTTON LAYOUT: Flex Column on Mobile, Row on Desktop --}}
                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('staff.index') }}" wire:navigate class="w-full sm:w-auto text-center bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 font-bold py-2 px-4 rounded text-sm shadow-sm transition">
                                Cancel
                            </a>
                            <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded text-sm shadow-md transition transform hover:-translate-y-0.5">
                                Save Staff & Create Account
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 