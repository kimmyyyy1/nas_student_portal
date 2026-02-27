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
                <div wire:click="openGradingSheet({{ $section->id }})" 
                     class="group premium-card !rounded-2xl border border-white/40 hover:border-indigo-300/50 transition duration-300 overflow-hidden flex flex-col h-full cursor-pointer relative shadow-sm hover:shadow-lg">
                    <div class="px-6 py-8 flex flex-col h-full">
                        <div class="flex-grow">
                            <div class="flex justify-between items-start mb-4">
                                <div class="p-3 bg-indigo-50 rounded-lg text-indigo-600 group-hover:bg-indigo-100 transition">
                                    <i class='bx bx-edit text-xl'></i>
                                </div>
                                @if($section->adviser)
                                    <div class="text-xs font-bold text-indigo-600 uppercase tracking-wide bg-indigo-50 px-2 py-1 rounded group-hover:bg-indigo-100 transition">
                                        <i class='bx bx-user'></i> {{ $section->adviser->last_name }}
                                    </div>
                                @endif
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-1 group-hover:text-indigo-600 transition">
                                {{ $section->grade_level }} - {{ $section->section_name }}
                            </h3>
                            <div class="text-sm text-gray-500 mb-6 space-y-1">
                                <p class="flex items-center gap-2">
                                    <i class='bx bx-user text-gray-400'></i> 
                                    <span>Students: <span class="font-medium text-gray-700">{{ $section->students_count ?? 0 }}</span></span>
                                </p>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-100 mt-auto">
                            <div class="w-full flex justify-between items-center text-sm font-bold text-indigo-600 group-hover:text-indigo-800 transition">
                                OPEN GRADING SHEET
                                <i class='bx bx-right-arrow-alt text-lg transform group-hover:translate-x-1 transition'></i>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500">
                    <i class='bx bx-folder-open text-4xl mb-2'></i>
                    <p>No classes found.</p>
                </div>
            @endforelse
        </div>

    {{-- 👇 VIEW 2: GRADING SHEET (Table View) --}}
    @elseif($view == 'sheet')
        <div class="premium-card !p-0 overflow-hidden animate-fade-in">
            <div class="p-6 md:p-8 border-b border-white/40">
                
                {{-- 👇 1. SUBJECT SELECTION DROPDOWN (ETO ANG KULANG MO KANINA) --}}
                <div class="mb-6 p-4 bg-indigo-50 rounded-lg border border-indigo-100 flex flex-col md:flex-row md:items-center gap-4">
                    <label class="font-bold text-gray-700 whitespace-nowrap">
                        <i class='bx bx-book-bookmark'></i> Select Subject to Grade:
                    </label>
                    <select wire:model.live="selectedScheduleId" 
                            class="w-full md:w-1/2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Choose a Subject --</option>
                        @foreach($schedules as $sched)
                            <option value="{{ $sched->id }}">
                                {{ $sched->subject->subject_name ?? 'Subject' }} ({{ date('h:i A', strtotime($sched->time_start)) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 👇 2. ERROR MESSAGE (Kung nakalimutan pumili ng subject) --}}
                @if (session()->has('error'))
                    <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded animate-pulse">
                        <i class='bx bx-error-circle'></i> {{ session('error') }}
                    </div>
                @endif

                {{-- 👇 3. TABLE (Lumalabas lang pag may napili nang subject) --}}
                @if($selectedScheduleId)
                    <div class="premium-table-container !rounded-none !border-x-0 !border-b-0 animate-fade-in">
                        <table class="min-w-full divide-y divide-gray-100/50 bg-transparent">
                            <thead class="premium-table-header">
                                <tr>
                                    <th>Student Name</th>
                                    <th class="text-center">1st</th>
                                    <th class="text-center">2nd</th>
                                    <th class="text-center">3rd</th>
                                    <th class="text-center">4th</th>
                                    <th class="text-center">Final</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100/50">
                                @forelse($students as $student)
                                    <tr class="premium-table-row group">
                                        <td class="premium-table-cell font-bold text-slate-800 uppercase tracking-wide">
                                            {{ $student->last_name }}, {{ $student->first_name }}
                                        </td>

                                        {{-- Q1 --}}
                                        <td class="px-2 py-3">
                                            <input type="number" min="60" max="100"
                                                wire:model.live.debounce.500ms="gradesData.{{ $student->id }}.q1"
                                                class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="--">
                                        </td>
                                        {{-- Q2 --}}
                                        <td class="px-2 py-3">
                                            <input type="number" min="60" max="100"
                                                wire:model.live.debounce.500ms="gradesData.{{ $student->id }}.q2"
                                                class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="--">
                                        </td>
                                        {{-- Q3 --}}
                                        <td class="px-2 py-3">
                                            <input type="number" min="60" max="100"
                                                wire:model.live.debounce.500ms="gradesData.{{ $student->id }}.q3"
                                                class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="--">
                                        </td>
                                        {{-- Q4 --}}
                                        <td class="px-2 py-3">
                                            <input type="number" min="60" max="100"
                                                wire:model.live.debounce.500ms="gradesData.{{ $student->id }}.q4"
                                                class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="--">
                                        </td>

                                        {{-- Final Grade --}}
                                        <td class="px-2 py-3 bg-indigo-50">
                                            <input type="text" readonly
                                                wire:model="gradesData.{{ $student->id }}.final"
                                                class="w-full text-center bg-transparent border-none font-bold text-indigo-700 text-sm focus:ring-0" placeholder="-">
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-2 py-3">
                                            <select wire:model="studentStatus.{{ $student->id }}" 
                                                    class="w-full text-xs border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">-- Select --</option>
                                                <option value="Promoted" class="text-green-600 font-bold">Promoted</option>
                                                <option value="Retained" class="text-red-600 font-bold">Retained</option>
                                                <option value="Conditional" class="text-yellow-600 font-bold">Conditional</option>
                                            </select>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">No students enrolled.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Save Button --}}
                    <div class="mt-8 flex justify-end items-center gap-4">
                        <span wire:loading wire:target="saveGrades" class="text-sm text-indigo-500 font-semibold animate-pulse tracking-widest uppercase">
                            Saving grades...
                        </span>
                        <button wire:click="saveGrades" 
                                wire:loading.attr="disabled"
                                class="premium-btn-primary disabled:opacity-50 !py-3 !px-8 text-[13px]">
                            <i class='bx bx-save text-lg'></i> Save Grades
                        </button>
                    </div>
                @else
                    {{-- EMPTY STATE: Kapag wala pang pinipiling subject --}}
                    <div class="text-center py-16 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <i class='bx bx-up-arrow-circle text-4xl text-indigo-300 mb-2 animate-bounce'></i>
                        <h3 class="text-lg font-bold text-gray-700">No Subject Selected</h3>
                        <p class="text-gray-500">Please select a subject from the dropdown above to start grading.</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>