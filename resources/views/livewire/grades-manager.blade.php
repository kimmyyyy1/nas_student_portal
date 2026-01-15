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
                <div wire:click="openGradingSheet({{ $section->id }})" 
                     class="group bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition duration-200 overflow-hidden flex flex-col h-full cursor-pointer relative">
                    
                    <div class="p-6 flex flex-col h-full">
                        {{-- UPPER CONTENT --}}
                        <div class="flex-grow">
                            {{-- Header ng Card --}}
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

                            {{-- Section Info --}}
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

                        {{-- ACTION LINK --}}
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
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 animate-fade-in">
            <div class="p-6">
                {{-- Grading Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Student Name</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">1st Quarter</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">2nd Quarter</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">3rd Quarter</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">4th Quarter</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider bg-gray-100">Final Grade</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($students as $student)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ $student->last_name }}, {{ $student->first_name }}
                                    </td>

                                    {{-- Q1 Input --}}
                                    <td class="px-2 py-3">
                                        <input type="number" min="60" max="100"
                                            wire:model.live.debounce.500ms="gradesData.{{ $student->id }}.q1"
                                            class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="--">
                                    </td>

                                    {{-- Q2 Input --}}
                                    <td class="px-2 py-3">
                                        <input type="number" min="60" max="100"
                                            wire:model.live.debounce.500ms="gradesData.{{ $student->id }}.q2"
                                            class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="--">
                                    </td>

                                    {{-- Q3 Input --}}
                                    <td class="px-2 py-3">
                                        <input type="number" min="60" max="100"
                                            wire:model.live.debounce.500ms="gradesData.{{ $student->id }}.q3"
                                            class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="--">
                                    </td>

                                    {{-- Q4 Input --}}
                                    <td class="px-2 py-3">
                                        <input type="number" min="60" max="100"
                                            wire:model.live.debounce.500ms="gradesData.{{ $student->id }}.q4"
                                            class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="--">
                                    </td>

                                    {{-- Final Grade (Read Only - Auto Calculated) --}}
                                    <td class="px-2 py-3 bg-gray-50">
                                        <input type="text" readonly
                                            wire:model="gradesData.{{ $student->id }}.final"
                                            class="w-full text-center bg-transparent border-none font-bold text-gray-800 text-sm focus:ring-0" placeholder="-">
                                    </td>

                                    {{-- Status Dropdown --}}
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
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">No students enrolled in this class.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Save Button --}}
                <div class="mt-6 flex justify-end">
                    <button wire:click="saveGrades" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow-sm transition flex items-center gap-2">
                        <i class='bx bx-save'></i> Save Grades
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>