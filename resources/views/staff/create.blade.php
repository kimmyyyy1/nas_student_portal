<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add New Staff Member</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-8">
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded text-sm">
                        <strong class="font-bold">Please fix the following errors:</strong>
                        <ul class="list-disc list-inside mt-1">
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
                            <label class="block text-sm font-bold text-gray-700">Employee ID <span class="text-red-500">*</span></label>
                            <input type="text" name="employee_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('employee_id') }}" placeholder="e.g. T-2025-001">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">System Role <span class="text-red-500">*</span></label>
                            <select name="role" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="" disabled selected>-- Select Role --</option>
                                <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher / Adviser</option>
                                <option value="coach" {{ old('role') == 'coach' ? 'selected' : '' }}>Coach</option>
                                <option value="sass" {{ old('role') == 'sass' ? 'selected' : '' }}>SASS (Medical/Welfare)</option>
                                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff / Support</option> <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('first_name') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('last_name') }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700">Email Address (For Login) <span class="text-red-500">*</span></label>
                        <input type="email" name="email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('email') }}" placeholder="staff@nas.edu">
                        <p class="text-xs text-gray-500 mt-1">This email will be used as their username to log in. Default password is 'password'.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Contact Number</label>
                            <input type="text" name="contact_number" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('contact_number') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Department</label>
                            <input type="text" name="department" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('department') }}" placeholder="e.g. Science">
                        </div>
                         <div>
                            <label class="block text-sm font-bold text-gray-700">Position</label>
                            <input type="text" name="position" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('position') }}" placeholder="e.g. Head Teacher">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 border-t pt-6">
                        <a href="{{ route('staff.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md font-semibold transition">Cancel</a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md font-bold shadow transition">Save Staff & Create Account</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>