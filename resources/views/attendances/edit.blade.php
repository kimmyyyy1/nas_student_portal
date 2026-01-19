    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Attendance Record') }}
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

                        <form method="POST" action="{{ route('attendances.update', $attendance->id) }}">
                            @csrf
                            @method('PATCH')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                                    <input type="date" name="date" id="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('date', $attendance->date) }}" required>
                                </div>

                                <div>
                                    <label for="student_id" class="block text-sm font-medium text-gray-700">Student</label>
                                    <select name="student_id" id="student_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" required disabled>
                                        <option value="{{ $attendance->student_id }}">{{ $attendance->student->last_name ?? 'N/A' }}, {{ $attendance->student->first_name ?? 'N/A' }}</option>
                                    </select>
                                    <input type="hidden" name="student_id" value="{{ $attendance->student_id }}">
                                </div>

                                <div>
                                    <label for="schedule_id" class="block text-sm font-medium text-gray-700">Class / Schedule</label>
                                    <select name="schedule_id" id="schedule_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                        <option value="">-- Select Class --</option>
                                        @foreach($schedules as $schedule)
                                            <option value="{{ $schedule->id }}" {{ old('schedule_id', $attendance->schedule_id) == $schedule->id ? 'selected' : '' }}>
                                                {{ $schedule->subject->subject_code ?? 'N/A' }} ({{ $schedule->section->section_name ?? 'N/A' }}) - {{ $schedule->staff->last_name ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                        <option value="">-- Select Status --</option>
                                        <option value="Present" {{ old('status', $attendance->status) == 'Present' ? 'selected' : '' }}>Present</option>
                                        <option value="Absent" {{ old('status', $attendance->status) == 'Absent' ? 'selected' : '' }}>Absent</option>
                                        <option value="Late" {{ old('status', $attendance->status) == 'Late' ? 'selected' : '' }}>Late</option>
                                        <option value="Excused" {{ old('status', $attendance->status) == 'Excused' ? 'selected' : '' }}>Excused</option>
                                    </select>
                                </div>

                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                                    Update Attendance Record
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>