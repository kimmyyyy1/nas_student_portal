<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Medical Record') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('medical-records.store') }}">
                        @csrf 

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="student_id" class="block text-sm font-medium text-gray-700">Student</label>
                                <select name="student_id" id="student_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">-- Select Student --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->last_name }}, {{ $student->first_name }} ({{ $student->student_number }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="record_type" class="block text-sm font-medium text-gray-700">Record Type</label>
                                <select name="record_type" id="record_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="Medical Exam" {{ old('record_type') == 'Medical Exam' ? 'selected' : '' }}>Medical Exam</option>
                                    <option value="Dental Clearance" {{ old('record_type') == 'Dental Clearance' ? 'selected' : '' }}>Dental Clearance</option>
                                    <option value="Physical Clearance" {{ old('record_type') == 'Physical Clearance' ? 'selected' : '' }}>Physical Clearance</option>
                                    <option value="Emergency Contact" {{ old('record_type') == 'Emergency Contact' ? 'selected' : '' }}>Emergency Contact</option>
                                </select>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">-- Select Status --</option>
                                    <option value="Cleared" {{ old('status') == 'Cleared' ? 'selected' : '' }}>Cleared</option>
                                    <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Restricted" {{ old('status') == 'Restricted' ? 'selected' : '' }}>Restricted (Not Cleared)</option>
                                </select>
                            </div>

                            <div>
                                <label for="date_cleared" class="block text-sm font-medium text-gray-700">Date Cleared (Optional)</label>
                                <input type="date" name="date_cleared" id="date_cleared" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('date_cleared') }}">
                            </div>

                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes / Details (Optional)</label>
                                <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('notes') }}</textarea>
                            </div>

                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                                Save Medical Record
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>