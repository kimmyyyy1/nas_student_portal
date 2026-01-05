<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Staff Member') }}
        </h2>
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
                
                <form method="POST" action="{{ route('staff.update', $staff->id) }}">
                    @csrf
                    @method('PUT') 

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label for="employee_id" class="block text-sm font-bold text-gray-700">Employee ID</label>
                            <input type="text" 
                                   name="employee_id" 
                                   id="employee_id" 
                                   value="{{ old('employee_id', $staff->employee_id) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed text-gray-500 focus:border-indigo-500 focus:ring-indigo-500" 
                                   readonly required> 
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-bold text-gray-700">System Role <span class="text-red-500">*</span></label>
                            <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Select Role</option>
                                <option value="teacher" {{ old('role', $staff->role) == 'teacher' ? 'selected' : '' }}>Teacher / Adviser</option>
                                <option value="coach" {{ old('role', $staff->role) == 'coach' ? 'selected' : '' }}>Coach</option>
                                <option value="sass" {{ old('role', $staff->role) == 'sass' ? 'selected' : '' }}>SASS (Medical/Welfare)</option>
                                <option value="staff" {{ old('role', $staff->role) == 'staff' ? 'selected' : '' }}>Staff / Support</option>
                                <option value="admin" {{ old('role', $staff->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label for="first_name" class="block text-sm font-bold text-gray-700">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $staff->first_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-bold text-gray-700">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $staff->last_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-bold text-gray-700">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $staff->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <p class="text-xs text-gray-500 mt-1">Changing this will also update their login username.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label for="contact_number" class="block text-sm font-bold text-gray-700">Contact Number</label>
                            <input type="text" name="contact_number" id="contact_number" value="{{ old('contact_number', $staff->contact_number) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="department" class="block text-sm font-bold text-gray-700">Department</label>
                            <input type="text" name="department" id="department" value="{{ old('department', $staff->department) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                         <div>
                            <label for="position" class="block text-sm font-bold text-gray-700">Position</label>
                            <input type="text" name="position" id="position" value="{{ old('position', $staff->position) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 border-t pt-6">
                        <a href="{{ route('staff.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md font-semibold transition">Cancel</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-bold shadow transition">Update Staff Details</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>