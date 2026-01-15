<div>
    {{-- 👇 HIDDEN BUTTON: Para sa Back Button sa Header --}}
    <button id="hidden-back-btn" wire:click="goBack" style="display: none;"></button>

    {{-- SUCCESS MESSAGE --}}
    @if (session()->has('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-sm rounded relative animate-fade-in" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- 👇 VIEW 1: GRID OF CARDS (Class Selection) --}}
    @if($view == 'grid')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in items-stretch">
            @forelse($sections as $section)
                {{-- CARD CONTAINER --}}
                <div wire:click="openAttendanceSheet({{ $section->id }})" 
                     class="group bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition duration-200 overflow-hidden flex flex-col h-full cursor-pointer relative">
                    
                    <div class="p-6 flex flex-col h-full">
                        {{-- UPPER CONTENT --}}
                        <div class="flex-grow">
                            {{-- Header ng Card --}}
                            <div class="flex justify-between items-start mb-4">
                                {{-- Blue Calendar Icon --}}
                                <div class="p-3 bg-blue-50 rounded-lg text-blue-600 group-hover:bg-blue-100 transition">
                                    <i class='bx bx-calendar-check text-xl'></i>
                                </div>
                                @if($section->adviser)
                                    <div class="text-xs font-bold text-blue-600 uppercase tracking-wide bg-blue-50 px-2 py-1 rounded group-hover:bg-blue-100 transition">
                                        <i class='bx bx-user'></i> {{ $section->adviser->last_name }}
                                    </div>
                                @endif
                            </div>

                            {{-- Section Info with Hover Effect --}}
                            <h3 class="text-xl font-bold text-gray-800 mb-1 group-hover:text-blue-600 transition">
                                {{ $section->grade_level }} - {{ $section->section_name }}
                            </h3>
                            
                            <div class="text-sm text-gray-500 mb-6 space-y-1">
                                <p class="flex items-center gap-2">
                                    <i class='bx bx-user text-gray-400'></i> 
                                    <span>Students: <span class="font-medium text-gray-700">{{ $section->students_count ?? 0 }}</span></span>
                                </p>
                                <p class="flex items-center gap-2">
                                    <i class='bx bx-map text-gray-400'></i> 
                                    <span>Room: <span class="font-medium text-gray-700">{{ $section->room_number ?? 'TBA' }}</span></span>
                                </p>
                            </div>
                        </div>

                        {{-- ACTION LINK (Always at bottom) --}}
                        <div class="pt-4 border-t border-gray-100 mt-auto">
                            <div class="w-full flex justify-between items-center text-sm font-bold text-blue-600 group-hover:text-blue-800 transition">
                                CHECK ATTENDANCE
                                <i class='bx bx-right-arrow-alt text-lg transform group-hover:translate-x-1 transition'></i>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500">
                    <i class='bx bx-calendar-x text-4xl mb-2'></i>
                    <p>No classes found.</p>
                </div>
            @endforelse
        </div>

    {{-- 👇 VIEW 2: ATTENDANCE SHEET (Table View) --}}
    @elseif($view == 'sheet')
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 animate-fade-in">
            <div class="p-6">
                
                {{-- Date Picker --}}
                <div class="mb-6 flex items-center gap-4">
                    <label class="font-bold text-gray-700">Date:</label>
                    <input type="date" wire:model="date" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                {{-- Attendance Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Student Name</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-green-600 uppercase tracking-wider">Present</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-yellow-600 uppercase tracking-wider">Late</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-red-600 uppercase tracking-wider">Absent</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-blue-600 uppercase tracking-wider">Excused</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($students as $student)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ $student->last_name }}, {{ $student->first_name }}
                                    </td>
                                    
                                    {{-- Radio Buttons for Status --}}
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" wire:model="attendance.{{ $student->id }}" value="present" class="text-green-600 focus:ring-green-500 cursor-pointer h-4 w-4">
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" wire:model="attendance.{{ $student->id }}" value="late" class="text-yellow-600 focus:ring-yellow-500 cursor-pointer h-4 w-4">
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" wire:model="attendance.{{ $student->id }}" value="absent" class="text-red-600 focus:ring-red-500 cursor-pointer h-4 w-4">
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" wire:model="attendance.{{ $student->id }}" value="excused" class="text-blue-600 focus:ring-blue-500 cursor-pointer h-4 w-4">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">No students enrolled in this class.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Save Button --}}
                <div class="mt-6 flex justify-end">
                    <button wire:click="saveAttendance" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow-sm transition flex items-center gap-2">
                        <i class='bx bx-save'></i> Save Attendance
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>