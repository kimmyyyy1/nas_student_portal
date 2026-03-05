<div wire:poll.10s>
<div x-data="{
    selectAll: false,
    selectedStudents: [],
    bulkStatus: '',
    get hasSelection() { return this.selectedStudents.length > 0; },
    toggleAll() {
        if (this.selectAll) {
            let boxes = document.querySelectorAll('.student-checkbox:not(:disabled)');
            this.selectedStudents = Array.from(boxes).map(b => b.value);
        } else {
            this.selectedStudents = [];
        }
    },
    updateSelection() {
        let boxes = document.querySelectorAll('.student-checkbox:not(:disabled)');
        this.selectAll = boxes.length > 0 && this.selectedStudents.length === boxes.length;
    },
    submitUpdate(e) {
        if (!this.hasSelection || !this.bulkStatus) {
            e.preventDefault();
            return;
        }
        if(!confirm('Are you sure you want to update the status of the selected student(s)?')) {
            e.preventDefault();
            return;
        }
        this.$refs.bulkForm.action = '{{ route("students.bulk-update-status") }}';
    },
    submitFinalize() {
        if (!this.hasSelection) return;
        if (!confirm('Finalize ' + this.selectedStudents.length + ' selected student record(s)? They will be locked from editing.')) return;
        this.$refs.bulkForm.action = '{{ route("students.bulk-finalize") }}';
        this.$refs.bulkForm.submit();
    },
    submitUnfinalize() {
        if (!this.hasSelection) return;
        if (!confirm('Unfinalize ' + this.selectedStudents.length + ' selected student record(s)? They will become editable again.')) return;
        this.$refs.bulkForm.action = '{{ route("students.bulk-unfinalize") }}';
        this.$refs.bulkForm.submit();
    }
}" wire:ignore.self>
    {{-- FILTER BAR --}}
    <div class="mb-6 bg-white/60 backdrop-blur-xl p-4 sm:rounded-2xl shadow-sm border border-white/60">
        <div class="flex flex-col lg:flex-row gap-3 lg:items-end">
            
            {{-- 1. SEARCH --}}
            <div class="w-full lg:flex-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Search Student</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class='bx bx-search text-gray-400'></i>
                    </div>
                    <input type="text" wire:model.live.debounce.500ms="search" 
                        class="pl-9 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-1.5 text-gray-900" 
                        placeholder="Name, LRN, or ID...">
                </div>
            </div>

            {{-- 2. GRADE --}}
            <div class="w-full lg:w-32">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Grade</label>
                <select wire:model.live="grade_level" class="block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-1.5 text-gray-900 cursor-pointer">
                    <option value="">All Grades</option>
                    @foreach(['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'] as $gl)
                        <option value="{{ $gl }}">{{ $gl }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 3. SECTION --}}
            <div class="w-full lg:w-40">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Section</label>
                <select wire:model.live="section_id" class="block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-1.5 text-gray-900 cursor-pointer">
                    <option value="">All Sections</option>
                    @foreach($sections->groupBy('grade_level') as $grade => $gradeSections)
                        <optgroup label="{{ $grade }}">
                            @foreach($gradeSections as $sec)
                                <option value="{{ $sec->id }}">{{ $sec->section_name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            {{-- 4. SPORT --}}
            <div class="w-full lg:w-40">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Sport</label>
                <select wire:model.live="sport" class="block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-1.5 text-gray-900 cursor-pointer">
                    <option value="">All Sports</option>
                    @foreach(['Aquatics', 'Athletics', 'Badminton', 'Gymnastics', 'Judo', 'Table Tennis', 'Taekwondo', 'Weightlifting'] as $spt)
                        <option value="{{ $spt }}">{{ $spt }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 5. STATUS --}}
            <div class="w-full lg:w-32">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Status</label>
                <select wire:model.live="status" class="block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-1.5 text-gray-900 cursor-pointer">
                    <option value="">All Statuses</option>
                    @foreach(['New', 'Continuing', 'Enrolled', 'Transfer out', 'Graduate'] as $stat)
                        <option value="{{ $stat }}">{{ $stat }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 6. RESET BUTTON --}}
            <div class="flex gap-2 w-full lg:w-auto">
                <button type="button" wire:click="resetFilters" class="bg-white hover:bg-rose-50 border border-slate-200 hover:border-rose-200 text-slate-500 hover:text-rose-600 px-3 py-2 rounded-xl text-sm font-bold shadow-sm transition-all h-[38px] flex items-center justify-center lg:flex-none {{ $search || $grade_level || $section_id || $status || $sport ? '' : 'hidden' }}">
                    <i class='bx bx-x text-lg'></i>
                </button>
            </div>
        </div>
    </div>

    {{-- TABLE SECTION --}}
    <div class="premium-table-container">
        <form id="bulkUpdateForm" action="{{ route('students.bulk-update-status') }}" method="POST" x-ref="bulkForm">
            @csrf
            <input type="hidden" name="search" value="{{ $search }}">
            <input type="hidden" name="grade_level" value="{{ $grade_level }}">
            <input type="hidden" name="section_id" value="{{ $section_id }}">
            <input type="hidden" name="status" value="{{ $status }}">
            <input type="hidden" name="sport" value="{{ $sport }}">
            <input type="hidden" name="select_all_matching" id="selectAllMatchingInput" x-bind:value="selectAll ? '1' : '0'">
            
            {{-- BULK ACTION TAB --}}
            <div class="px-5 py-3 border-b border-gray-200 bg-white/60 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="selectAllCheckbox" x-model="selectAll" x-on:change="toggleAll" class="rounded border-gray-300 text-blue-600 shadow-sm cursor-pointer w-4 h-4">
                    <label for="selectAllCheckbox" class="text-[10px] md:text-sm font-bold text-slate-600 uppercase tracking-widest cursor-pointer select-none">Select All</label>
                </div>
                <!-- Live indicator added to the right corner -->
                <div class="hidden md:flex items-center space-x-2">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                    <span class="text-[10px] uppercase font-bold text-emerald-600 tracking-wider">Live Sync</span>
                </div>
            </div>
            
            <div class="px-5 py-3 border-b border-gray-200 bg-white shadow-sm flex items-center gap-2 sticky top-0 z-20">
                    <select name="bulk_status" id="bulkStatusSelect" x-model="bulkStatus" class="block w-32 md:w-48 rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xs py-1.5 md:py-2 text-gray-900 cursor-pointer" required>
                        <option value="">Change Status To...</option>
                        <option value="New">New</option>
                        <option value="Continuing">Continuing</option>
                        <option value="Enrolled">Enrolled</option>
                        <option value="Transfer out">Transfer Out</option>
                        <option value="Graduate">Graduate (Alumni)</option>
                    </select>
                    <button type="submit" x-on:click="submitUpdate" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 md:px-5 md:py-2 rounded-xl text-xs font-bold shadow-sm transition disabled:opacity-50 disabled:cursor-not-allowed uppercase tracking-wider" x-bind:disabled="!hasSelection || !bulkStatus">
                        Update
                    </button>
                    <button type="button" x-on:click="submitFinalize" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 md:px-5 md:py-2 rounded-xl text-xs font-bold shadow-sm transition disabled:opacity-50 disabled:cursor-not-allowed uppercase tracking-wider flex items-center gap-1.5" x-bind:disabled="!hasSelection">
                        <i class='bx bx-check-shield text-sm'></i> Finalize
                    </button>
                    @if(auth()->user()->role === 'admin')
                    <button type="button" x-on:click="submitUnfinalize" class="bg-amber-600 hover:bg-amber-700 text-white px-3 py-1.5 md:px-5 md:py-2 rounded-xl text-xs font-bold shadow-sm transition disabled:opacity-50 disabled:cursor-not-allowed uppercase tracking-wider flex items-center gap-1.5" x-bind:disabled="!hasSelection">
                        <i class='bx bx-lock-open text-sm'></i> Unfinalize
                    </button>
                    @endif
            </div>

            <div class="w-full overflow-x-auto custom-scroll relative"> 
                
                {{-- Add a tiny inline loading indicator for filter changes, not an overlay --}}
                <div wire:loading wire:target="search, grade_level, section_id, status, sport" class="w-full text-center py-2 hidden">
                    <span class="text-xs font-bold text-indigo-600 flex items-center justify-center gap-1">
                        <i class='bx bx-loader-alt bx-spin'></i> Filtering...
                    </span>
                </div>

                <table class="min-w-full divide-y divide-gray-100/50 whitespace-nowrap bg-transparent table-fixed">
                    <thead class="premium-table-header">
                        <tr>
                            <th style="width:40px" class="px-4"></th>
                            <th style="width:16%">Student ID</th>
                            <th style="width:auto">Name</th>
                            <th style="width:12%">Grade & Sec</th>
                            <th style="width:12%" class="!text-center">Sport</th>
                            <th style="width:10%" class="!text-center">Status</th>
                            <th style="width:10%" class="!text-right">Action</th>
                        </tr>
                    </thead>
                    
                    <tbody id="student-list" class="divide-y divide-gray-50/50 relative">
                        @forelse($students as $student)
                            <tr class="premium-table-row" wire:key="student-{{ $student->id }}">
                                <td class="px-4 whitespace-nowrap text-center align-middle">
                                    <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" x-model="selectedStudents" x-on:change="updateSelection" class="student-checkbox rounded border-gray-300 text-blue-600 shadow-sm disabled:opacity-50 disabled:bg-gray-100 cursor-pointer w-4 h-4" {{ $student->is_locked && auth()->user()->role !== 'admin' ? 'disabled' : '' }}>
                                </td>
                                
                                <td class="premium-table-cell">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 mr-2 hidden sm:block">
                                            @if($student->id_picture)
                                                <img class="h-10 w-10 rounded-full object-cover ring-2 ring-white" src="{{ $student->id_picture ?? asset('images/default-avatar.svg') }}" alt="{{ $student->full_name }}">
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
                                    <div class="flex items-center gap-1.5">
                                        <div class="text-sm font-bold text-gray-900 uppercase leading-tight">
                                            {{ $student->last_name }}, {{ $student->first_name }}
                                        </div>
                                        @if($student->is_locked)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-wider bg-amber-100 text-amber-700 border border-amber-200" title="Finalized">
                                                🔒
                                            </span>
                                        @endif
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
                                        <div class="text-sm text-gray-900 font-medium">
                                            Grade {{ trim(str_ireplace('grade', '', strtolower($student->grade_level))) }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $student->section->section_name ?? 'Unassigned' }}
                                        </div>
                                    @endif
                                </td>

                                <td class="premium-table-cell !text-center">
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

                                <td class="premium-table-cell !text-center">
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

                                <td class="premium-table-cell !text-right text-[13px] font-bold">
                                    <a href="{{ route('students.show', ['student' => $student->id] + request()->query()) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900 font-bold mr-3 transition">
                                        View
                                    </a>
                                    @if(!$student->is_locked)
                                        <a href="{{ route('students.edit', ['student' => $student->id] + request()->query()) }}" wire:navigate class="text-blue-600 hover:text-blue-900 font-bold transition">
                                            Edit
                                        </a>
                                    @endif
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
                {{ $students->links() }}
            </div>
        </form>
    </div>
</div>
</div>
