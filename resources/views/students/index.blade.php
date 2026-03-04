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
                <h2 class="font-black text-2xl md:text-3xl text-slate-800 tracking-tight flex items-center">
                    {{ __('Student Directory') }}
                    <span class="ml-4 px-2.5 py-1 rounded-md text-[10px] font-black tracking-widest bg-rose-100 text-rose-600 animate-pulse flex items-center shadow-sm border border-rose-200">
                        <span class="w-1.5 h-1.5 bg-rose-600 rounded-full mr-1.5"></span> LIVE
                    </span>
                </h2>
            </div>
            
            {{-- HEADER BUTTONS --}}
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <a href="{{ route('students.bulk-upload') }}" wire:navigate class="premium-btn-secondary w-full sm:w-auto">
                    <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Bulk Upload
                </a>
                {{-- Tinanggal na ang "New Student" button dito --}}
            </div>
        </div>
    </x-slot>

    <div class="py-2 md:py-8">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8 px-4">
            
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
            <div class="mb-6 bg-white/60 backdrop-blur-xl p-4 sm:rounded-2xl shadow-sm border border-white/60">
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

                        {{-- 4. SPORT --}}
                        <div class="w-full lg:w-40">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Sport</label>
                            <select name="sport" class="block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-1.5 text-gray-900 cursor-pointer">
                                <option value="">All Sports</option>
                                {{-- ⚡ INALIS NA ANG MGA NASA LOOB NG PARENTHESIS ⚡ --}}
                                @foreach(['Aquatics', 'Athletics', 'Badminton', 'Gymnastics', 'Judo', 'Table Tennis', 'Taekwondo', 'Weightlifting'] as $sport)
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
                            <button type="submit" class="bg-gradient-to-r from-slate-700 to-slate-800 hover:from-slate-800 hover:to-slate-900 text-white px-5 py-2 rounded-xl text-xs font-bold shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all h-[38px] flex items-center justify-center flex-1 lg:flex-none">
                                <i class='bx bx-filter-alt mr-1.5'></i> Filter
                            </button>
                            
                            @if(request()->hasAny(['search', 'grade_level', 'section_id', 'status', 'sport']))
                                <a href="{{ route('students.index') }}" class="bg-white hover:bg-rose-50 border border-slate-200 hover:border-rose-200 text-slate-500 hover:text-rose-600 px-3 py-2 rounded-xl text-sm font-bold shadow-sm transition-all h-[38px] flex items-center justify-center">
                                    <i class='bx bx-x text-lg'></i>
                                </a>
                            @endif
                        </div>

                    </div>
                </form>
            </div>

            {{-- TABLE SECTION --}}
            <div class="premium-table-container">
                <form id="bulkUpdateForm" action="{{ route('students.bulk-update-status') }}" method="POST">
                    @csrf
                    
                    {{-- BULK ACTION TAB --}}
                    <div class="px-5 py-3 border-b border-gray-200 bg-white/60 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="selectAllCheckbox" class="rounded border-gray-300 text-blue-600 shadow-sm cursor-pointer w-4 h-4">
                            <label for="selectAllCheckbox" class="text-[10px] md:text-sm font-bold text-slate-600 uppercase tracking-widest cursor-pointer select-none">Select All</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <select name="bulk_status" id="bulkStatusSelect" class="block w-32 md:w-48 rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xs py-1.5 md:py-2 text-gray-900 cursor-pointer" required>
                                <option value="">Change Status To...</option>
                                <option value="New">New</option>
                                <option value="Continuing">Continuing</option>
                                <option value="Enrolled">Enrolled</option>
                                <option value="Transfer out">Transfer Out</option>
                                <option value="Graduate">Graduate (Alumni)</option>
                            </select>
                            <button type="submit" onclick="return confirm('Are you sure you want to update the status of the selected students?')" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 md:px-5 md:py-2 rounded-xl text-xs font-bold shadow-sm transition disabled:opacity-50 disabled:cursor-not-allowed uppercase tracking-wider" id="bulkSubmitBtn" disabled>
                                Update
                            </button>
                        </div>
                    </div>

                    <div class="w-full overflow-x-auto custom-scroll"> 
                        <table class="min-w-full divide-y divide-gray-100/50 whitespace-nowrap bg-transparent">
                            <thead class="premium-table-header">
                                <tr>
                                    <th class="w-10 px-4"></th>
                                    <th>Student ID</th>
                                <th>Name</th>
                                <th>Grade & Sec</th>
                                <th>Sport</th>
                                <th class="text-center">Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        
                        <tbody id="student-list" class="divide-y divide-gray-50/50">
                            @forelse($students as $student)
                                <tr class="premium-table-row">
                                    <td class="px-4 whitespace-nowrap text-center align-middle">
                                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="student-checkbox rounded border-gray-300 text-blue-600 shadow-sm cursor-pointer w-4 h-4">
                                    </td>
                                    
                                    <td class="premium-table-cell">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 mr-2 hidden sm:block">
                                                @if($student->id_picture)
                                                    <img class="h-8 w-8 rounded-full object-cover border border-gray-300" src="{{ fileUrl($student->id_picture) }}" alt="">
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

                                    <td class="premium-table-cell">
                                        <div class="text-sm font-bold text-gray-900 uppercase leading-tight">
                                            {{ $student->last_name }}, {{ $student->first_name }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $student->email_address }}</div>
                                    </td>

                                    <td class="premium-table-cell">
                                        @if($student->status === 'Graduate')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                                <i class='bx bxs-graduation mr-1'></i> Alumni
                                            </span>
                                        @elseif($student->status === 'Transfer out')
                                            <span class="text-xs text-red-500 italic font-medium">Transferred Out</span>
                                        @else
                                            {{-- ⚡ FIXED GRADE LEVEL DISPLAY ⚡ --}}
                                            <div class="text-sm text-gray-900 font-medium">
                                                Grade {{ trim(str_ireplace('grade', '', strtolower($student->grade_level))) }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $student->section->section_name ?? 'Unassigned' }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- 4. SPORT (ULTIMATE ROBUST LOGIC) --}}
                                    <td class="premium-table-cell">
                                        @php
                                            $details = $student->enrollmentDetail; 
                                            if (!$details && $student->user) {
                                                $details = $student->user->enrollmentDetail;
                                            }
                                            if (!$details && (!empty($student->lrn) || !empty($student->email_address))) {
                                                $details = \App\Models\EnrollmentDetail::where(function($query) use ($student) {
                                                    if(!empty($student->lrn)) $query->where('lrn', $student->lrn);
                                                    if(!empty($student->email_address)) $query->orWhere('email', $student->email_address);
                                                })->latest()->first();
                                            }

                                            $applicantFallback = null;
                                            if (!empty($student->lrn)) {
                                                $applicantFallback = \App\Models\Applicant::where('lrn', $student->lrn)->first();
                                            }

                                            $displaySport = $student->sport 
                                                            ?? ($details->sport ?? null) 
                                                            ?? ($applicantFallback->sport ?? null) 
                                                            ?? ($student->team->sport ?? $student->team->sport_type ?? $student->team->team_name ?? null);

                                            if (!empty($displaySport) && $displaySport !== 'N/A' && $displaySport !== 'None') {
                                                $sportLower = strtolower($displaySport);
                                                
                                                // ⚡ CLEAN DISPLAY NAMES ⚡
                                                if (str_contains($sportLower, 'aquatic') || str_contains($sportLower, 'swim')) {
                                                    $displaySport = 'Aquatics';
                                                } elseif (str_contains($sportLower, 'athletic') || str_contains($sportLower, 'track')) {
                                                    $displaySport = 'Athletics';
                                                } elseif (str_contains($sportLower, 'taekwondo')) {
                                                    $displaySport = 'Taekwondo';
                                                } elseif (str_contains($sportLower, 'gymnastic')) {
                                                    $displaySport = 'Gymnastics';
                                                } elseif (str_contains($sportLower, 'badminton')) {
                                                    $displaySport = 'Badminton';
                                                } elseif (str_contains($sportLower, 'judo')) {
                                                    $displaySport = 'Judo';
                                                } elseif (str_contains($sportLower, 'table tennis') || str_contains($sportLower, 'tabletennis')) {
                                                    $displaySport = 'Table Tennis';
                                                } elseif (str_contains($sportLower, 'weightlifting')) {
                                                    $displaySport = 'Weightlifting';
                                                } else {
                                                    $displaySport = ucwords(strtolower($displaySport));
                                                }
                                            } else {
                                                $displaySport = null;
                                            }
                                        @endphp

                                        @if($displaySport)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-100 shadow-sm">
                                                {{ $displaySport }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">None</span>
                                        @endif
                                    </td>

                                    <td class="premium-table-cell text-center">
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

                                    <td class="premium-table-cell text-right text-[13px] font-bold">
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
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-medium">
                                        No enrolled students found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="px-5 py-4 border-t border-white/60 bg-white/40">
                    {{ $students->appends(request()->query())->links() }}
                </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setInterval(function() {
                const urlParams = new URLSearchParams(window.location.search);
                if (!urlParams.has('search') && !urlParams.has('page') && !urlParams.has('sport') && !urlParams.has('grade_level')) {
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
                        rebindCheckboxes(); // re-bind events after DOM replacement
                    }
                })
                .catch(error => console.error('Error updating table:', error));
        }

        // Bulk Selection Logic
        function rebindCheckboxes() {
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            const bulkSubmitBtn = document.getElementById('bulkSubmitBtn');
            const bulkStatusSelect = document.getElementById('bulkStatusSelect');

            if(!selectAllCheckbox || !bulkSubmitBtn) return;

            function updateSubmitButton() {
                const checkedCount = document.querySelectorAll('.student-checkbox:checked').length;
                bulkSubmitBtn.disabled = checkedCount === 0 || bulkStatusSelect.value === '';
            }

            selectAllCheckbox.addEventListener('change', function() {
                studentCheckboxes.forEach(cb => cb.checked = selectAllCheckbox.checked);
                updateSubmitButton();
            });

            studentCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const allChecked = document.querySelectorAll('.student-checkbox:checked').length === studentCheckboxes.length;
                    selectAllCheckbox.checked = allChecked && studentCheckboxes.length > 0;
                    updateSubmitButton();
                });
            });

            bulkStatusSelect.addEventListener('change', updateSubmitButton);
        }

        // Initialize immediately
        document.addEventListener('DOMContentLoaded', rebindCheckboxes);
    </script>
</x-app-layout>