<div>
    {{-- 👇 HIDDEN BUTTON: Para sa Back Button sa Header --}}
    <button id="hidden-back-btn" wire:click="goBack" style="display: none;"></button>

    {{-- 👇 VIEW 1: GRID OF CARDS (Class Selection) --}}
    @if($view == 'grid')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in items-stretch">
            @forelse($sections as $section)
                {{-- CARD CONTAINER: Added 'group' for hover effects --}}
                <div class="group bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition duration-200 overflow-hidden flex flex-col h-full">
                    
                    <div class="p-6 flex flex-col h-full">
                        {{-- UPPER CONTENT --}}
                        <div class="flex-grow">
                            {{-- Header ng Card --}}
                            <div class="flex justify-between items-start mb-4">
                                <div class="p-3 bg-indigo-50 rounded-lg text-indigo-600 group-hover:bg-indigo-100 transition">
                                    <i class='bx bx-save text-xl'></i>
                                </div>
                                @if($section->adviser)
                                    <div class="text-xs font-bold text-indigo-600 uppercase tracking-wide bg-indigo-50 px-2 py-1 rounded group-hover:bg-indigo-100 transition">
                                        <i class='bx bx-user'></i> {{ $section->adviser->last_name }}
                                    </div>
                                @endif
                            </div>

                            {{-- Section Info with Hover Effect --}}
                            {{-- 👇 Dito ko nilagay ang 'group-hover:text-indigo-600' --}}
                            <h3 class="text-xl font-bold text-gray-800 mb-1 group-hover:text-indigo-600 transition">
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
                            <button wire:click="openGradingSheet({{ $section->id }})" 
                                    class="w-full flex justify-between items-center text-sm font-bold text-indigo-600 group-hover:text-indigo-800 transition">
                                OPEN GRADING SHEET
                                <i class='bx bx-right-arrow-alt text-lg transform group-hover:translate-x-1 transition'></i>
                            </button>
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
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">1st Quarter</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">2nd Quarter</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">3rd Quarter</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">4th Quarter</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Final Grade</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($students as $student)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ $student->last_name }}, {{ $student->first_name }}
                                    </td>
                                    {{-- Sample Input Fields --}}
                                    <td class="px-4 py-3"><input type="number" class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></td>
                                    <td class="px-4 py-3"><input type="number" class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></td>
                                    <td class="px-4 py-3"><input type="number" class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></td>
                                    <td class="px-4 py-3"><input type="number" class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></td>
                                    <td class="px-4 py-3 text-center font-bold text-gray-700">-</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">No students enrolled in this class.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Save Button --}}
                <div class="mt-6 flex justify-end">
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow-sm transition">
                        Save Grades
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>