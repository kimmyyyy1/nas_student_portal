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
                     class="group premium-card !rounded-2xl border border-white/40 hover:border-indigo-300/50 transition duration-300 overflow-hidden flex flex-col h-full cursor-pointer relative shadow-sm hover:shadow-lg">
                    
                    <div class="px-6 py-8 flex flex-col h-full">
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
        <div class="premium-card !p-0 overflow-hidden animate-fade-in">
            <div class="p-6 md:p-8 border-b border-white/40">
                
                {{-- Date Picker --}}
                <div class="mb-6 flex items-center gap-4">
                    <label class="font-bold text-gray-700">Date:</label>
                    <input type="date" wire:model.live="date" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                {{-- Attendance Table --}}
                <div class="premium-table-container !rounded-none !border-x-0 !border-b-0 mt-4">
                    <table class="min-w-full divide-y divide-gray-100/50 bg-transparent">
                        <thead class="premium-table-header">
                            <tr>
                                <th>Student Name</th>
                                <th class="text-center text-emerald-600 font-black">Present</th>
                                <th class="text-center text-amber-500 font-black">Late</th>
                                <th class="text-center text-rose-500 font-black">Absent</th>
                                <th class="text-center text-blue-500 font-black">Excused</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/50">
                            @forelse($students as $student)
                                {{-- Main Row --}}
                                <tr class="premium-table-row group {{ (isset($attendance[$student->id]) && $attendance[$student->id] === 'excused') ? 'bg-blue-50/30' : '' }}">
                                    <td class="premium-table-cell font-bold text-slate-800 uppercase tracking-wide">
                                        {{ $student->last_name }}, {{ $student->first_name }}
                                    </td>
                                    
                                    {{-- Radio Buttons for Status --}}
                                    <td class="premium-table-cell text-center">
                                        <input type="radio" wire:model.live="attendance.{{ $student->id }}" value="present" class="text-green-600 focus:ring-green-500 cursor-pointer h-4 w-4">
                                    </td>
                                    <td class="premium-table-cell text-center">
                                        <input type="radio" wire:model.live="attendance.{{ $student->id }}" value="late" class="text-yellow-600 focus:ring-yellow-500 cursor-pointer h-4 w-4">
                                    </td>
                                    <td class="premium-table-cell text-center">
                                        <input type="radio" wire:model.live="attendance.{{ $student->id }}" value="absent" class="text-red-600 focus:ring-red-500 cursor-pointer h-4 w-4">
                                    </td>
                                    <td class="premium-table-cell text-center">
                                        <input type="radio" wire:model.live="attendance.{{ $student->id }}" value="excused" class="text-blue-600 focus:ring-blue-500 cursor-pointer h-4 w-4">
                                    </td>
                                </tr>

                                {{-- 👇 CONDITIONAL ROW: Remarks (Reason) --}}
                                {{-- Lumalabas lang kung 'Excused' ang napili --}}
                                @if(isset($attendance[$student->id]) && $attendance[$student->id] === 'excused')
                                    <tr class="bg-blue-50 animate-fade-in-down">
                                        <td colspan="5" class="px-6 py-2 pb-4 border-b border-gray-200">
                                            <div class="flex items-center gap-3 pl-4">
                                                <span class="text-xs font-bold text-blue-700 uppercase tracking-wide">
                                                    <i class='bx bx-info-circle'></i> Reason:
                                                </span>
                                                <input type="text" 
                                                       wire:model="remarks.{{ $student->id }}"
                                                       class="w-full md:w-1/2 text-sm border-blue-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-blue-300"
                                                       placeholder="Enter reason for excuse (Optional)...">
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">No students enrolled in this class.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Save Button --}}
                <div class="mt-8 flex justify-end">
                    <button wire:click="saveAttendance" class="premium-btn-primary !py-3 !px-8 text-[13px]">
                        <i class='bx bx-save text-lg'></i> Save Attendance
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>