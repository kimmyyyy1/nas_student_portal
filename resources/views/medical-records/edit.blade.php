<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Medical Record for {{ $medicalRecord->student->last_name ?? 'N/A' }}, {{ $medicalRecord->student->first_name ?? 'N/A' }}
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

                    <form method="POST" action="{{ route('medical-records.update', $medicalRecord->id) }}">
                        @csrf
                        @method('PATCH') 

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="student_name" class="block text-sm font-medium text-gray-700">Student</label>
                                <input type="text" name="student_name" value="{{ $medicalRecord->student->last_name ?? 'N/A' }}, {{ $medicalRecord->student->first_name ?? 'N/A' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" disabled>
                                <input type="hidden" name="student_id" value="{{ $medicalRecord->student_id }}">
                            </div>

                            <div>
                                <label for="record_type" class="block text-sm font-medium text-gray-700">Record Type</label>
                                <select name="record_type" id="record_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="Medical Exam" {{ old('record_type', $medicalRecord->record_type) == 'Medical Exam' ? 'selected' : '' }}>Medical Exam</option>
                                    <option value="Dental Clearance" {{ old('record_type', $medicalRecord->record_type) == 'Dental Clearance' ? 'selected' : '' }}>Dental Clearance</option>
                                    <option value="Physical Clearance" {{ old('record_type', $medicalRecord->record_type) == 'Physical Clearance' ? 'selected' : '' }}>Physical Clearance</option>
                                    <option value="Emergency Contact" {{ old('record_type', $medicalRecord->record_type) == 'Emergency Contact' ? 'selected' : '' }}>Emergency Contact</option>
                                </select>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">-- Select Status --</option>
                                    <option value="Cleared" {{ old('status', $medicalRecord->status) == 'Cleared' ? 'selected' : '' }}>Cleared</option>
                                    <option value="Pending" {{ old('status', $medicalRecord->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Restricted" {{ old('status', $medicalRecord->status) == 'Restricted' ? 'selected' : '' }}>Restricted (Not Cleared)</option>
                                </select>
                            </div>

                            <div>
                                <label for="date_cleared" class="block text-sm font-medium text-gray-700">Date Cleared (Optional)</label>
                                <input type="date" name="date_cleared" id="date_cleared" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('date_cleared', $medicalRecord->date_cleared) }}">
                            </div>

                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes / Details (Optional)</label>
                                <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('notes', $medicalRecord->notes) }}</textarea>
                            </div>

                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                                Update Medical Record
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>