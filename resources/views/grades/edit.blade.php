<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title flex border-none">
            {{ __('Edit Grade') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="premium-card !p-0 overflow-hidden">
                <div class="p-6 md:p-8 text-slate-800 border-t border-white/40">
                    
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('grades.update', $grade->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="student_id" class="block text-sm font-medium text-gray-700">Student</label>
                                <select name="student_id" id="student_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">-- Select Student --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id', $grade->student_id) == $student->id ? 'selected' : '' }}>
                                            {{ $student->last_name }}, {{ $student->first_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="schedule_id" class="block text-sm font-medium text-gray-700">Class / Schedule</label>
                                <select name="schedule_id" id="schedule_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">-- Select Class --</option>
                                    @foreach($schedules as $schedule)
                                        <option value="{{ $schedule->id }}" {{ old('schedule_id', $grade->schedule_id) == $schedule->id ? 'selected' : '' }}>
                                            {{ $schedule->subject->subject_code ?? 'N/A' }} ({{ $schedule->section->section_name ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="grading_period" class="block text-sm font-medium text-gray-700">Grading Period</label>
                                <select name="grading_period" id="grading_period" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">-- Select Period --</option>
                                    <option value="1st Quarter" {{ old('grading_period', $grade->grading_period) == '1st Quarter' ? 'selected' : '' }}>1st Quarter</option>
                                    <option value="2nd Quarter" {{ old('grading_period', $grade->grading_period) == '2nd Quarter' ? 'selected' : '' }}>2nd Quarter</option>
                                    <option value="3rd Quarter" {{ old('grading_period', $grade->grading_period) == '3rd Quarter' ? 'selected' : '' }}>3rd Quarter</option>
                                    <option value="4th Quarter" {{ old('grading_period', $grade->grading_period) == '4th Quarter' ? 'selected' : '' }}>4th Quarter</option>
                                    </select>
                            </div>

                            <div>
                                <label for="mark" class="block text-sm font-medium text-gray-700">Mark (e.g., 95.50)</label>
                                <input type="number" step="0.01" name="mark" id="mark" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('mark', $grade->mark) }}" required>
                            </div>

                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                                Update Grade
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>