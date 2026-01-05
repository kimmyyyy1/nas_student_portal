<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Schedule') }}
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

                    <form method="POST" action="{{ route('schedules.store') }}">
                        @csrf 

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject</label>
                                <select name="subject_id" id="subject_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">-- Select Subject --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->subject_code }} - {{ $subject->subject_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="section_id" class="block text-sm font-medium text-gray-700">Section</label>
                                <select name="section_id" id="section_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">-- Select Section --</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                            {{ $section->grade_level }} - {{ $section->section_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="staff_id" class="block text-sm font-medium text-gray-700">Teacher / Staff</label>
                                <select name="staff_id" id="staff_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">-- Select Staff --</option>
                                    @foreach($staff as $person)
                                        <option value="{{ $person->id }}" {{ old('staff_id') == $person->id ? 'selected' : '' }}>
                                            {{ $person->first_name }} {{ $person->last_name }} ({{ $person->role }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="day" class="block text-sm font-medium text-gray-700">Day(s)</label>
                                <input type="text" name="day" id="day" placeholder="e.g., MWF or TTh" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('day') }}" required>
                            </div>

                            <div>
                                <label for="time_start" class="block text-sm font-medium text-gray-700">Time Start</label>
                                <input type="time" name="time_start" id="time_start" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('time_start') }}" required>
                            </div>

                            <div>
                                <label for="time_end" class="block text-sm font-medium text-gray-700">Time End</label>
                                <input type="time" name="time_end" id="time_end" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('time_end') }}" required>
                            </div>

                            <div class="md:col-span-3">
                                <label for="room" class="block text-sm font-medium text-gray-700">Room (Optional)</label>
                                <input type="text" name="room" id="room" placeholder="e.g., Room 101" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('room') }}">
                            </div>

                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                                Save Schedule
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>