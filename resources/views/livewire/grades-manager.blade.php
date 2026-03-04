<div>
    {{-- 👇 HIDDEN BUTTON: Para sa Back Button sa Header --}}
    <button id="hidden-back-btn" wire:click="goBack" style="display: none;"></button>

    {{-- SUCCESS MESSAGE --}}
    @if (session()->has('success'))
        <div class="mb-8 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-6 rounded-2xl shadow-xl flex items-center gap-4 animate-fade-in">
            <div class="h-10 w-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 shadow-sm">
                <i class='bx bx-check-circle text-2xl'></i>
            </div>
            <div>
                <p class="text-sm font-black uppercase tracking-widest">Operation Successful</p>
                <p class="text-xs font-bold opacity-80 mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- 👇 VIEW 1: GRID OF CARDS (Class Selection) --}}
    @if($view == 'grid')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 animate-fade-in items-stretch pb-10">
            @forelse($sections as $section)
                <div wire:click="openGradingSheet({{ $section->id }})" 
                     class="group bg-white/70 backdrop-blur-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 sm:rounded-[2.5rem] hover:shadow-[0_20px_50px_rgba(0,0,0,0.1)] transition-all duration-700 flex flex-col h-full cursor-pointer relative overflow-hidden">
                    
                    <!-- Background Softness -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 rounded-full -mr-16 -mt-16 blur-3xl group-hover:bg-indigo-500/10 transition-colors"></div>
                    
                    <div class="px-10 py-10 flex flex-col h-full relative z-10">
                        <div class="flex-grow">
                            <div class="flex justify-between items-start mb-8">
                                <div class="h-14 w-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 transition-all duration-500 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white shadow-sm ring-1 ring-indigo-100/50">
                                    <i class='bx bxs-edit-alt text-2xl'></i>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-auto">Load Status</span>
                                    <div class="px-3 py-1 bg-white shadow-sm border border-slate-100 rounded-lg text-emerald-600 text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5">
                                        <div class="h-1.5 w-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                                        Active
                                    </div>
                                </div>
                            </div>

                            <p class="text-[11px] font-black text-indigo-500 uppercase tracking-[0.25em] mb-2 font-poppins-override">Class Repository</p>
                            <h3 class="text-3xl font-black text-slate-900 tracking-tight leading-tight group-hover:text-indigo-600 transition-colors duration-300">
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
                            <span class="text-[11px] font-black text-indigo-600 uppercase tracking-[0.2em]">Launch Academic Grading Hub</span>
                            <div class="h-8 w-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-200">
                                <i class='bx bx-spreadsheet text-lg'></i>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-32 flex flex-col items-center opacity-30">
                    <div class="h-24 w-24 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                        <i class='bx bx-folder-open text-6xl text-slate-300'></i>
                    </div>
                    <p class="text-[14px] font-black uppercase tracking-[0.2em]">No assigned academic rosters</p>
                </div>
            @endforelse
        </div>

    {{-- 👇 VIEW 2: GRADING SHEET (Table View) --}}
    @elseif($view == 'sheet')
        <div class="grid grid-cols-1 gap-8 animate-fade-in pb-12">
            <!-- Filtering Card -->
            <div class="bg-white/70 backdrop-blur-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 sm:rounded-[2.5rem] p-10 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                    <div class="flex items-center gap-7">
                        <div class="h-20 w-20 rounded-[1.8rem] bg-indigo-600 flex items-center justify-center text-white shadow-2xl shadow-indigo-200">
                            <i class='bx bxs-book-content text-4xl'></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-black text-indigo-500 uppercase tracking-[0.25em] mb-2">Academic Assessment</p>
                            <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-none">Course Grading Sheet</h1>
                            <div class="mt-4 flex flex-wrap gap-4 font-bold text-slate-500 text-[11px] uppercase tracking-widest">
                                <div class="flex items-center gap-2"><i class='bx bx-group text-indigo-400'></i> {{ $selectedSection->grade_level }} — {{ $selectedSection->section_name }}</div>
                                <div class="flex items-center gap-2 font-poppins-override"><i class='bx bx-buildings text-indigo-400'></i> Room: <span class="text-slate-900">{{ $selectedSection->room ?? 'TBH' }}</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white px-8 py-6 rounded-[2rem] border border-slate-100 shadow-sm min-w-0 lg:w-[450px]">
                        <label class="text-[9px] font-black text-slate-600 uppercase tracking-[0.2em] mb-3 block">Academic Subject Selection</label>
                        <div class="relative">
                            <i class='bx bx-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none'></i>
                            <select wire:model.live="selectedScheduleId" 
                                    class="w-full pl-6 pr-12 py-4 bg-slate-50 border-none rounded-xl text-xs font-black uppercase tracking-widest text-slate-800 focus:ring-2 focus:ring-indigo-500 appearance-none">
                                <option value="">Select Academic Subject</option>
                                @foreach($schedules as $sched)
                                    <option value="{{ $sched->id }}">
                                        {{ $sched->subject->subject_name ?? 'Subject' }} Roster ({{ date('h:i A', strtotime($sched->time_start)) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 👇 2. ERROR MESSAGE --}}
            @if (session()->has('error'))
                <div class="p-6 bg-rose-50 border-l-4 border-rose-500 rounded-2xl flex items-center gap-4 shadow-sm animate-pulse">
                    <i class='bx bxs-error-circle text-rose-500 text-3xl'></i>
                    <p class="text-rose-700 font-black text-xs uppercase tracking-widest">{{ session('error') }}</p>
                </div>
            @endif

            {{-- 👇 3. MAIN TABLE DATA --}}
            <div class="bg-white/70 backdrop-blur-xl shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-white/60 sm:rounded-[2.5rem] overflow-hidden">
                <div class="px-10 py-8 border-b border-gray-100/50 bg-white/40 flex justify-between items-center">
                    <h3 class="text-[13px] font-black text-slate-900 uppercase tracking-[0.2em] flex items-center gap-3">
                        <i class='bx bx-spreadsheet text-indigo-500 text-xl'></i> Performance Indicators
                    </h3>
                </div>

                @if($selectedScheduleId)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-10 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Student Name</th>
                                    <th class="px-5 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Q1</th>
                                    <th class="px-5 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Q2</th>
                                    <th class="px-5 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Q3</th>
                                    <th class="px-5 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Q4</th>
                                    <th class="px-10 py-5 text-center text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em]">General Average</th>
                                    <th class="px-10 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Promotion Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100/60 font-poppins-override">
                                @forelse($students as $student)
                                    <tr class="hover:bg-indigo-50/30 transition-colors duration-200 group">
                                        <td class="px-10 py-6">
                                            <div class="flex items-center gap-4">
                                                <div class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 font-bold overflow-hidden border border-slate-200 shadow-sm">
                                                    @if($student->id_picture)
                                                        <img src="{{ fileUrl($student->id_picture) }}" class="h-full w-full object-cover">
                                                    @else
                                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                    @endif
                                                </div>
                                                <span class="text-sm font-black text-slate-800 tracking-tight uppercase">{{ $student->last_name }}, {{ $student->first_name }}</span>
                                            </div>
                                        </td>

                                        @foreach(['q1', 'q2', 'q3', 'q4'] as $q)
                                            <td class="px-5 py-6">
                                                <input type="number" min="60" max="100"
                                                    wire:model.live.debounce.500ms="gradesData.{{ $student->id }}.{{ $q }}"
                                                    class="w-16 mx-auto text-center border-none bg-slate-100/50 rounded-xl shadow-none font-bold text-slate-700 text-xs focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all py-3 px-0 placeholder-slate-300" placeholder="--">
                                            </td>
                                        @endforeach

                                        <td class="px-10 py-6 bg-indigo-50/20">
                                            <input type="text" readonly
                                                wire:model="gradesData.{{ $student->id }}.final"
                                                class="w-20 mx-auto text-center bg-white border border-indigo-100 rounded-xl font-black text-indigo-600 text-sm py-2 px-0 shadow-sm" placeholder="-">
                                        </td>

                                        <td class="px-10 py-6 min-w-[180px]">
                                            <div class="relative">
                                                <i class='bx bx-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-sm'></i>
                                                <select wire:model="studentStatus.{{ $student->id }}" 
                                                        class="w-full text-[10px] font-black uppercase tracking-widest border-none bg-white rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 py-3 pl-4 pr-10 appearance-none {{ ($studentStatus[$student->id] ?? '') === 'Promoted' ? 'text-emerald-600' : (($studentStatus[$student->id] ?? '') === 'Retained' ? 'text-rose-600' : 'text-slate-600') }}">
                                                    <option value="">Roster Status</option>
                                                    <option value="Promoted">Promoted</option>
                                                    <option value="Retained">Retained</option>
                                                    <option value="Conditional">Conditional</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-10 py-20 text-center">
                                            <div class="flex flex-col items-center opacity-30">
                                                <i class='bx bx-user-x text-6xl mb-4 text-slate-300'></i>
                                                <p class="text-[12px] font-black uppercase tracking-[0.2em]">No students in roster</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-32 opacity-60">
                        <div class="h-24 w-24 bg-indigo-50 rounded-full flex items-center justify-center mb-6 ring-2 ring-indigo-100 ring-offset-2 animate-bounce cursor-pointer">
                            <i class='bx bx-up-arrow-alt text-6xl text-indigo-500'></i>
                        </div>
                        <h4 class="text-lg font-black text-slate-600 uppercase tracking-[0.2em]">Academic Access Required</h4>
                        <p class="text-[11px] font-bold text-slate-500 mt-2 uppercase tracking-widest text-center">Select an academic subject above to initiate the grading process</p>
                    </div>
                @endif
            </div>

            @if($selectedScheduleId)
                <!-- Advanced Controls -->
                <div class="flex flex-col md:flex-row justify-between items-center gap-6 mt-4">
                    <div class="p-6 bg-white/40 border border-white/60 rounded-3xl flex items-center gap-4">
                        <div class="h-10 w-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500 shadow-sm border border-amber-100">
                            <i class='bx bx-info-circle text-2xl'></i>
                        </div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.15em] leading-relaxed max-w-[400px]">
                            Grades are automatically computed for running averages. Final averages are rounded to the nearest intelligence point upon saving.
                        </p>
                    </div>
                    
                    <div class="flex items-center gap-6">
                        <div wire:loading wire:target="saveGrades" class="flex items-center gap-3">
                            <div class="h-5 w-5 border-2 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                            <span class="text-[11px] font-black text-indigo-600 uppercase tracking-[0.2em] animate-pulse">Syncing Academic Data...</span>
                        </div>
                        <button wire:click="saveGrades" 
                                wire:loading.attr="disabled"
                                class="inline-flex items-center justify-center gap-4 px-10 py-5 bg-gradient-to-r from-slate-900 to-indigo-950 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] shadow-xl shadow-indigo-900/10 hover:shadow-indigo-900/20 hover:-translate-y-1 transition-all duration-300 disabled:opacity-50">
                            <i class='bx bx-cloud-upload text-xl'></i> Commit Academic Records
                        </button>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>