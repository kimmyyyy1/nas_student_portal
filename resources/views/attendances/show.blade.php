<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('attendances.index') }}" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-arrow-back text-2xl'></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Attendance Sheet') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <form action="{{ route('attendances.bulk_store') }}" method="POST">
                @csrf
                {{-- Hidden input para sa Section ID --}}
                <input type="hidden" name="section_id" value="{{ $section->id }}">

                {{-- HEADER & CONTROLS --}}
                <div class="bg-white shadow-sm sm:rounded-t-lg border-b border-gray-200 p-6 flex flex-col md:flex-row justify-between items-center gap-4">
                    
                    {{-- Section Info --}}
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-800">{{ $section->grade_level }} - {{ $section->section_name }}</h1>
                        <p class="text-sm text-gray-500">Adviser: <span class="uppercase font-bold">{{ Auth::user()->name }}</span></p>
                    </div>

                    {{-- DATE PICKER (Auto-load record pag pinalitan ang date) --}}
                    <div class="flex items-center gap-2 bg-gray-50 p-2 rounded border border-gray-200">
                        <label class="font-bold text-gray-700 text-sm">Date:</label>
                        <input type="date" name="date" id="attendanceDate" 
                               value="{{ $date }}" 
                               class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                               onchange="window.location.href='{{ route('attendances.show', $section->id) }}?date=' + this.value">
                    </div>
                    
                    {{-- SAVE BUTTON --}}
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow-lg flex items-center gap-2 transition transform hover:scale-105">
                        <i class='bx bx-save text-xl'></i> SAVE ATTENDANCE
                    </button>
                </div>

                {{-- TABLE --}}
                <div class="bg-white shadow-sm sm:rounded-b-lg overflow-x-auto">
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4 text-sm font-bold">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="min-w-full divide-y divide-gray-300 text-sm">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-4 py-3 text-left w-10">#</th>
                                <th class="px-4 py-3 text-left">LEARNER NAME</th>
                                <th class="px-4 py-3 text-center">STATUS</th>
                                <th class="px-4 py-3 text-left">REMARKS (Optional)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($section->students as $index => $student)
                                @php
                                    // Check kung may record na for selected date
                                    $record = $attendanceRecords[$student->id] ?? null;
                                    // Kung wala pa, Default ay 'Present'. Kung meron, kunin ang saved status.
                                    $status = $record ? $record->status : 'Present'; 
                                @endphp
                                <tr class="hover:bg-gray-50 transition border-b border-gray-100">
                                    <td class="px-4 py-3 text-center text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-bold text-gray-800">
                                        {{ $student->last_name }}, {{ $student->first_name }}
                                    </td>
                                    
                                    {{-- STATUS RADIO BUTTONS --}}
                                    <td class="px-4 py-3">
                                        <div class="flex justify-center gap-6">
                                            
                                            {{-- PRESENT --}}
                                            <label class="inline-flex items-center cursor-pointer group">
                                                <input type="radio" name="attendance[{{ $student->id }}][status]" value="Present" class="text-green-600 focus:ring-green-500 w-4 h-4" {{ $status == 'Present' ? 'checked' : '' }}>
                                                <span class="ml-2 text-gray-700 group-hover:text-green-600 font-medium">Present</span>
                                            </label>
                                            
                                            {{-- LATE --}}
                                            <label class="inline-flex items-center cursor-pointer group">
                                                <input type="radio" name="attendance[{{ $student->id }}][status]" value="Late" class="text-yellow-600 focus:ring-yellow-500 w-4 h-4" {{ $status == 'Late' ? 'checked' : '' }}>
                                                <span class="ml-2 text-gray-700 group-hover:text-yellow-600 font-medium">Late</span>
                                            </label>
                                            
                                            {{-- ABSENT --}}
                                            <label class="inline-flex items-center cursor-pointer group">
                                                <input type="radio" name="attendance[{{ $student->id }}][status]" value="Absent" class="text-red-600 focus:ring-red-500 w-4 h-4" {{ $status == 'Absent' ? 'checked' : '' }}>
                                                <span class="ml-2 text-gray-700 group-hover:text-red-600 font-medium">Absent</span>
                                            </label>
                                            
                                            {{-- EXCUSED --}}
                                            <label class="inline-flex items-center cursor-pointer group">
                                                <input type="radio" name="attendance[{{ $student->id }}][status]" value="Excused" class="text-blue-600 focus:ring-blue-500 w-4 h-4" {{ $status == 'Excused' ? 'checked' : '' }}>
                                                <span class="ml-2 text-gray-700 group-hover:text-blue-600 font-medium">Excused</span>
                                            </label>

                                        </div>
                                    </td>

                                    {{-- REMARKS INPUT --}}
                                    <td class="px-4 py-3">
                                        <input type="text" name="attendance[{{ $student->id }}][remarks]" value="{{ $record->remarks ?? '' }}" class="w-full border-gray-300 rounded text-xs focus:ring-blue-500 focus:border-blue-500 placeholder-gray-300" placeholder="Notes (e.g. Sick)">
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-6 text-gray-400 italic">No students found in this class.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>