<x-app-layout>
    {{-- 👇 1. DIRECT INJECTION: Force Poppins on this page --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Force Poppins on everything in this view */
        * { font-family: 'Poppins', sans-serif !important; }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Medical Record') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('medical-records.store') }}">
                        @csrf 

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Student --}}
                            <div>
                                <label for="student_id" class="block text-sm font-bold text-gray-700 mb-1">Student</label>
                                <select name="student_id" id="student_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">-- Select Student --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->last_name }}, {{ $student->first_name }} ({{ $student->student_number }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Record Type --}}
                            <div>
                                <label for="record_type" class="block text-sm font-bold text-gray-700 mb-1">Record Type</label>
                                <select name="record_type" id="record_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="Medical Exam" {{ old('record_type') == 'Medical Exam' ? 'selected' : '' }}>Medical Exam</option>
                                    <option value="Dental Clearance" {{ old('record_type') == 'Dental Clearance' ? 'selected' : '' }}>Dental Clearance</option>
                                    <option value="Physical Clearance" {{ old('record_type') == 'Physical Clearance' ? 'selected' : '' }}>Physical Clearance</option>
                                    <option value="Emergency Contact" {{ old('record_type') == 'Emergency Contact' ? 'selected' : '' }}>Emergency Contact</option>
                                </select>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label for="status" class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">-- Select Status --</option>
                                    <option value="Cleared" {{ old('status') == 'Cleared' ? 'selected' : '' }}>Cleared</option>
                                    <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Restricted" {{ old('status') == 'Restricted' ? 'selected' : '' }}>Restricted (Not Cleared)</option>
                                </select>
                            </div>

                            {{-- Date Cleared --}}
                            <div>
                                <label for="date_cleared" class="block text-sm font-bold text-gray-700 mb-1">Date Cleared (Optional)</label>
                                <input type="date" name="date_cleared" id="date_cleared" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('date_cleared') }}">
                            </div>

                            {{-- Notes --}}
                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-bold text-gray-700 mb-1">Notes / Details (Optional)</label>
                                <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                            </div>

                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-6 flex justify-end gap-2">
                            <a href="{{ route('medical-records.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-md shadow-sm transition duration-150 ease-in-out">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-sm transition duration-150 ease-in-out">
                                Save Medical Record
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>