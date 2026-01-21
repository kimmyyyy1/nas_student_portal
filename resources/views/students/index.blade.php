<x-app-layout>
    <x-slot name="header">
        
        {{-- ============================================================= --}}
        {{-- 📱 MOBILE HEADER: Compact Badge & Live Indicator              --}}
        {{-- ============================================================= --}}
        <div class="flex md:hidden items-center justify-between w-full py-1">
            
            {{-- Badge --}}
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700 uppercase shadow-sm border border-green-200">
                <i class='bx bxs-user-detail mr-1.5 text-sm'></i> Directory
            </span>

            {{-- Live Indicator --}}
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mr-1"></span> LIVE
            </span>

        </div>

        {{-- ============================================================= --}}
        {{-- 💻 DESKTOP HEADER: Standard View                              --}}
        {{-- ============================================================= --}}
        <div class="hidden md:flex flex-col md:flex-row justify-between items-start md:items-center gap-4 py-2">
            
            {{-- TITLE --}}
            <div class="flex items-center justify-between w-full md:w-auto">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                    {{ __('Student Directory') }}
                    <span class="ml-3 px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                        <span class="w-2 h-2 bg-red-600 rounded-full mr-1"></span> LIVE
                    </span>
                </h2>
            </div>
            
            {{-- HEADER BUTTONS --}}
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <a href="{{ route('students.bulk-upload') }}" wire:navigate class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm flex items-center justify-center shadow transition w-full sm:w-auto">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Bulk Upload
                </a>

                <a href="{{ route('students.create') }}" wire:navigate class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm flex items-center justify-center shadow transition w-full sm:w-auto">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Student
                </a>
            </div>
        </div>
    </x-slot>

    {{-- 👇 FIX: 'py-2' sa mobile, 'md:py-12' sa desktop --}}
    <div class="py-2 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- ❌ REMOVED: Wala na ang Back Button dito --}}
            
            {{-- ALERTS --}}
            @if(session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded shadow relative text-sm">
                    <button onclick="this.parentElement.style.display='none'" class="absolute top-2 right-2 text-green-600 hover:text-green-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>{!! session('success') !!}</span>
                    </div>
                </div>
            @endif

            {{-- FILTER BAR --}}
            <div class="mb-4 bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                <form method="GET" action="{{ route('students.index') }}">
                    <div class="flex flex-col lg:flex-row gap-3 lg:items-end">
                        
                        {{-- 1. SEARCH --}}
                        <div class="w-full lg:flex-1">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Search Student</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class='bx bx-search text-gray-400'></i>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    class="pl-9 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-1.5 text-gray-900" 
                                    placeholder="Name, LRN, or ID...">
                            </div>
                        </div>

                        {{-- 2. GRADE --}}
                        <div class="w-full lg:w-32">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Grade</label>
                            <select name="grade_level" class="block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-1.5 text-gray-900 cursor-pointer">
                                <option value="">All Grades</option>
                                @foreach(['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'] as $gl)
                                    <option value="{{ $gl }}" {{ request('grade_level') == $gl ? 'selected' : '' }}>{{ $gl }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 3. SECTION --}}
                        <div class="w-full lg:w-40">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Section</label>
                            <select name="section_id" class="block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-1.5 text-gray-900 cursor-pointer">
                                <option value="">All Sections</option>
                                @foreach($sections->groupBy('grade_level') as $grade => $gradeSections)
                                    <optgroup label="{{ $grade }}">
                                        @foreach($gradeSections as $sec)
                                            <option value="{{ $sec->id }}" {{ request('section_id') == $sec->id ? 'selected' : '' }}>
                                                {{ $sec->section_name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        {{-- 4. SPORT (UPDATED BASED ON SCREENSHOT) --}}
                        <div class="w-full lg:w-40">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Sport</label>
                            <select name="sport" class="block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-1.5 text-gray-900 cursor-pointer">
                                <option value="">All Sports</option>
                                {{-- 👇 List from your screenshot --}}
                                @foreach(['Taekwondo', 'Table Tennis', 'Judo', 'Gymnastics', 'Badminton', 'Athletics', 'Aquatics'] as $sport)
                                    <option value="{{ $sport }}" {{ request('sport') == $sport ? 'selected' : '' }}>{{ $sport }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 5. STATUS --}}
                        <div class="w-full lg:w-32">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Status</label>
                            <select name="status" class="block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-1.5 text-gray-900 cursor-pointer">
                                <option value="">All Statuses</option>
                                @foreach(['New', 'Continuing', 'Enrolled', 'Transfer out', 'Graduate'] as $stat)
                                    <option value="{{ $stat }}" {{ request('status') == $stat ? 'selected' : '' }}>{{ $stat }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 6. BUTTONS --}}
                        <div class="flex gap-2 w-full lg:w-auto">
                            <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-1.5 rounded text-sm font-bold shadow transition h-[34px] flex items-center justify-center flex-1 lg:flex-none">
                                <i class='bx bx-filter-alt mr-1'></i> Filter
                            </button>
                            
                            @if(request()->hasAny(['search', 'grade_level', 'section_id', 'status', 'sport']))
                                <a href="{{ route('students.index') }}" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-3 py-1.5 rounded text-sm font-bold shadow transition h-[34px] flex items-center justify-center">
                                    <i class='bx bx-x text-lg'></i>
                                </a>
                            @endif
                        </div>

                    </div>
                </form>
            </div>

            {{-- TABLE SECTION --}}
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 overflow-hidden">
                <div class="w-full overflow-x-auto"> 
                    <table class="min-w-full divide-y divide-gray-200 whitespace-nowrap">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Student ID</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Grade & Sec</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Sport</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        
                        <tbody id="student-list" class="bg-white divide-y divide-gray-200">
                            @forelse($students as $student)
                                <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                    
                                    {{-- 1. STUDENT ID --}}
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 mr-2 hidden sm:block">
                                                @if($student->id_picture)
                                                    <img class="h-8 w-8 rounded-full object-cover border border-gray-300" src="{{ $student->id_picture }}" alt="">
                                                @else
                                                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500 font-bold text-xs border border-indigo-200">
                                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-sm font-mono text-blue-600 font-bold">
                                                {{ $student->nas_student_id }}
                                            </div>
                                        </div>
                                    </td>

                                    {{-- 2. NAME --}}
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-bold text-gray-900 uppercase leading-tight">
                                            {{ $student->last_name }}, {{ $student->first_name }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $student->email_address }}</div>
                                    </td>

                                    {{-- 3. GRADE & SECTION --}}
                                    <td class="px-4 py-3">
                                        @if($student->status === 'Graduate')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                                <i class='bx bxs-graduation mr-1'></i> Alumni
                                            </span>
                                        @elseif($student->status === 'Transfer out')
                                            <span class="text-xs text-red-500 italic font-medium">Transferred Out</span>
                                        @else
                                            <div class="text-sm text-gray-900 font-medium">{{ $student->grade_level }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ $student->section->section_name ?? 'Unassigned' }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- 4. SPORT --}}
                                    <td class="px-4 py-3">
                                        @if($student->team)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                                {{ $student->team->sport ?? $student->team->sport_type ?? $student->team->team_name }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">None</span>
                                        @endif
                                    </td>

                                    {{-- 5. STATUS --}}
                                    <td class="px-4 py-3 text-center">
                                        @php
                                            $statusColor = match($student->status) {
                                                'New' => 'bg-green-100 text-green-800 border-green-200',
                                                'Continuing' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                'Enrolled' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                                'Graduate' => 'bg-gray-600 text-white border-gray-600', 
                                                'Transfer out' => 'bg-red-100 text-red-800 border-red-200',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $statusColor }}">
                                            {{ $student->status }}
                                        </span>
                                    </td>

                                    {{-- 6. ACTION --}}
                                    <td class="px-4 py-3 text-right text-sm font-medium">
                                        <a href="{{ route('students.show', ['student' => $student->id] + request()->query()) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900 font-bold mr-3 transition">
                                            View
                                        </a>
                                        <a href="{{ route('students.edit', ['student' => $student->id] + request()->query()) }}" wire:navigate class="text-blue-600 hover:text-blue-900 font-bold transition">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                        No enrolled students found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- LIVE UPDATE SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setInterval(function() {
                const urlParams = new URLSearchParams(window.location.search);
                if (!urlParams.has('search') && !urlParams.has('page') && !urlParams.has('sport')) {
                    updateTable();
                }
            }, 10000); 
        });

        function updateTable() {
            const url = window.location.href;
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newBody = doc.getElementById('student-list').innerHTML;
                    if(document.getElementById('student-list')) {
                        document.getElementById('student-list').innerHTML = newBody;
                    }
                })
                .catch(error => console.error('Error updating table:', error));
        }
    </script>
</x-app-layout>