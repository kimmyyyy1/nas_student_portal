<div>
    {{-- 👇 HIDDEN BUTTON: Para sa Back Button sa Header --}}
    <button id="hidden-back-btn" wire:click="goBack" style="display: none;"></button>

    {{-- SUCCESS MESSAGE --}}
    @if (session()->has('success'))
        <div class="mb-8 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-6 rounded-2xl shadow-xl flex items-center gap-4 animate-fade-in font-poppins-override">
            <div class="h-10 w-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 shadow-sm">
                <i class='bx bx-check-double text-2xl'></i>
            </div>
            <div>
                <p class="text-sm font-black uppercase tracking-widest">Attendance Recorded</p>
                <p class="text-xs font-bold opacity-80 mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- 👇 VIEW 1: GRID OF CARDS (Class Selection) --}}
    @if($view == 'grid')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 animate-fade-in items-stretch pb-10">
            @forelse($sections as $section)
                <div wire:click="openAttendanceSheet({{ $section->id }})" 
                     class="group bg-white/70 backdrop-blur-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 sm:rounded-[2.5rem] hover:shadow-[0_20px_50px_rgba(0,0,0,0.1)] transition-all duration-700 flex flex-col h-full cursor-pointer relative overflow-hidden">
                    
                    <!-- Background Softness -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-full -mr-16 -mt-16 blur-3xl group-hover:bg-blue-500/10 transition-colors"></div>
                    
                    <div class="px-10 py-10 flex flex-col h-full relative z-10 font-poppins-override">
                        <div class="flex-grow">
                            <div class="flex justify-between items-start mb-8">
                                <div class="h-14 w-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 transition-all duration-500 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white shadow-sm ring-1 ring-blue-100/50">
                                    <i class='bx bx-calendar-check text-2xl'></i>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-auto">Log Status</span>
                                    <div class="px-3 py-1 bg-white shadow-sm border border-slate-100 rounded-lg text-blue-600 text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5">
                                        <div class="h-1.5 w-1.5 bg-blue-500 rounded-full animate-pulse"></div>
                                        Active Log
                                    </div>
                                </div>
                            </div>

                            <p class="text-[11px] font-black text-blue-500 uppercase tracking-[0.25em] mb-2 font-poppins-override">Attendance Management</p>
                            <h3 class="text-3xl font-black text-slate-900 tracking-tight leading-tight group-hover:text-blue-600 transition-colors duration-300">
                                {{ $section->grade_level }} — {{ $section->section_name }}
                            </h3>
                            
                            <div class="mt-8 grid grid-cols-2 gap-4">
                                <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-100/50 group-hover:bg-white transition-colors duration-300">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Students</p>
                                    <p class="text-xl font-black text-slate-800 tracking-tight">{{ sprintf('%02d', $section->students_count ?? 0) }}</p>
                                </div>
                                <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-100/50 group-hover:bg-white transition-colors duration-300">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Adviser</p>
                                    <p class="text-xs font-black text-slate-800 truncate uppercase tracking-tight">{{ $section->adviser->last_name ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 pt-6 border-t border-slate-100/60 flex items-center justify-between group-hover:translate-x-1 transition-transform duration-300">
                            <span class="text-[11px] font-black text-blue-500 uppercase tracking-[0.2em]">Check Class Attendance</span>
                            <i class='bx bx-right-arrow-alt text-2xl text-blue-500'></i>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-32 flex flex-col items-center opacity-30">
                    <div class="h-24 w-24 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                        <i class='bx bx-calendar-x text-6xl text-slate-300'></i>
                    </div>
                    <p class="text-[14px] font-black uppercase tracking-[0.2em]">No assigned attendance logs</p>
                </div>
            @endforelse
        </div>

    {{-- 👇 VIEW 2: ATTENDANCE SHEET (Table View) --}}
    @elseif($view == 'sheet')
        <div class="grid grid-cols-1 gap-8 animate-fade-in pb-12 font-poppins-override">
            <!-- Filtering Card -->
            <div class="bg-white/70 backdrop-blur-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 sm:rounded-[2.5rem] p-10 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                    <div class="flex items-center gap-7">
                        <div class="h-20 w-20 rounded-[1.8rem] bg-indigo-600 flex items-center justify-center text-white shadow-2xl shadow-indigo-200">
                            <i class='bx bx-calendar-event text-4xl'></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-black text-blue-600 uppercase tracking-[0.25em] mb-2 font-poppins-override">Attendance Registry Hub</p>
                            <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-none">Class Attendance Log</h1>
                            <div class="mt-4 flex flex-wrap gap-4 font-bold text-slate-500 text-[11px] uppercase tracking-widest">
                                <div class="flex items-center gap-2 font-poppins-override"><i class='bx bx-group text-blue-400'></i> {{ $selectedSection->grade_level }} — {{ $selectedSection->section_name }}</div>
                                <div class="flex items-center gap-2 font-poppins-override"><i class='bx bx-buildings text-blue-400'></i> Room: <span class="text-slate-900">{{ $selectedSection->room ?? 'TBH' }}</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white px-8 py-6 rounded-[2rem] border border-slate-100 shadow-sm min-w-0 lg:w-[350px]">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 block">Reporting Timeline</label>
                        <div class="relative">
                            <i class='bx bx-calendar absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none'></i>
                            <input type="date" wire:model.live="date" 
                                   class="w-full pl-6 pr-12 py-4 bg-slate-50 border-none rounded-xl text-xs font-black uppercase tracking-widest text-slate-800 focus:ring-2 focus:ring-blue-500 appearance-none">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Attendance Table --}}
            <div class="bg-white/70 backdrop-blur-xl shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-white/60 sm:rounded-[2.5rem] overflow-hidden">
                <div class="px-10 py-8 border-b border-gray-100/50 bg-white/40 flex justify-between items-center">
                    <h3 class="text-[13px] font-black text-slate-900 uppercase tracking-[0.2em] flex items-center gap-3">
                        <i class='bx bx-list-check text-blue-500 text-xl'></i> Log Verification
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-10 py-5 text-left text-[10px] font-black text-slate-900 uppercase tracking-[0.2em]">Student Name</th>
                                <th class="px-5 py-5 text-center text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] border-b-2 border-emerald-500/20">Present</th>
                                <th class="px-5 py-5 text-center text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] border-b-2 border-amber-500/20">Late</th>
                                <th class="px-5 py-5 text-center text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] border-b-2 border-rose-500/20">Absent</th>
                                <th class="px-5 py-5 text-center text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] border-b-2 border-blue-500/20">Excused</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/60 font-poppins-override">
                            @forelse($students as $student)
                                <tr class="hover:bg-blue-50/20 transition-colors duration-200 group {{ (isset($attendance[$student->id]) && $attendance[$student->id] === 'excused') ? 'bg-blue-50/30' : '' }}">
                                    <td class="px-10 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 font-bold overflow-hidden border border-slate-200 shadow-sm">
                                                @if($student->id_picture)
                                                    <img src="{{ asset($student->id_picture) }}" class="h-full w-full object-cover">
                                                @else
                                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                @endif
                                            </div>
                                            <span class="text-sm font-black text-slate-800 tracking-tight uppercase">{{ $student->last_name }}, {{ $student->first_name }}</span>
                                        </div>
                                    </td>
                                    
                                    <td class="px-5 py-6 text-center">
                                        <input type="radio" wire:model.live="attendance.{{ $student->id }}" value="present" class="h-5 w-5 border-2 border-slate-200 text-emerald-600 focus:ring-emerald-500 transition-all cursor-pointer">
                                    </td>
                                    <td class="px-5 py-6 text-center">
                                        <input type="radio" wire:model.live="attendance.{{ $student->id }}" value="late" class="h-5 w-5 border-2 border-slate-200 text-amber-500 focus:ring-amber-500 transition-all cursor-pointer">
                                    </td>
                                    <td class="px-5 py-6 text-center">
                                        <input type="radio" wire:model.live="attendance.{{ $student->id }}" value="absent" class="h-5 w-5 border-2 border-slate-200 text-rose-500 focus:ring-rose-500 transition-all cursor-pointer">
                                    </td>
                                    <td class="px-5 py-6 text-center">
                                        <input type="radio" wire:model.live="attendance.{{ $student->id }}" value="excused" class="h-5 w-5 border-2 border-slate-200 text-blue-600 focus:ring-blue-500 transition-all cursor-pointer">
                                    </td>
                                </tr>

                                @if(isset($attendance[$student->id]) && $attendance[$student->id] === 'excused')
                                    <tr class="bg-blue-50/40 animate-fade-in shadow-inner">
                                        <td colspan="5" class="px-10 py-4 border-b border-indigo-100/50">
                                            <div class="flex items-center gap-6 pl-10">
                                                <span class="text-[10px] font-black text-blue-500 uppercase tracking-widest flex items-center gap-2">
                                                    <i class='bx bx-note text-lg'></i> Attendance Remarks:
                                                </span>
                                                <input type="text" 
                                                       wire:model="remarks.{{ $student->id }}"
                                                       class="w-full md:w-2/3 bg-white border-slate-200 rounded-xl text-xs font-bold py-3 px-6 shadow-sm focus:ring-2 focus:ring-blue-500 placeholder-slate-300"
                                                       placeholder="Enter justification for absence...">
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="5" class="px-10 py-20 text-center">
                                        <div class="flex flex-col items-center opacity-100">
                                            <i class='bx bx-user-x text-6xl mb-4 text-slate-900'></i>
                                            <p class="text-[12px] font-black uppercase tracking-[0.2em] text-slate-900">Attendance Sheet Empty</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Save Control Bar -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 mt-4">
                <div class="p-6 bg-white/40 border border-white/60 rounded-3xl flex items-center gap-4">
                    <div class="h-10 w-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500 shadow-sm border border-blue-100">
                        <i class='bx bx-info-circle text-2xl'></i>
                    </div>
                    <p class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.15em] leading-relaxed max-w-[400px]">
                        Attendance records are updated in real-time. Please verify all entries carefully before committing to the system.
                    </p>
                </div>
                
                <div class="flex items-center gap-6">
                    <div wire:loading wire:target="saveAttendance" class="flex items-center gap-3">
                        <div class="h-5 w-5 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                        <span class="text-[11px] font-black text-blue-600 uppercase tracking-[0.2em] animate-pulse">Syncing Log...</span>
                    </div>
                    <button wire:click="saveAttendance" 
                            wire:loading.attr="disabled"
                            class="inline-flex items-center justify-center gap-4 px-10 py-5 bg-slate-900 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] shadow-xl shadow-slate-200 hover:bg-slate-800 hover:-translate-y-1 transition-all duration-300 disabled:opacity-50">
                        <i class='bx bx-cloud-upload text-xl'></i> Commit Attendance Log
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>