<x-student-layout>
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
                    {{ __('Student Portal Dashboard') }}
                    <span class="ml-3 px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                        <span class="w-2 h-2 bg-red-600 rounded-full mr-1"></span> LIVE
                    </span>
                </h2>
            </div>
            
        </div>
    </x-slot>

    @php
        // 1. FIX GENDER
        $gender = strtoupper($student->gender ?? $student->sex);
        if(in_array($gender, ['BOY', 'M', 'MALE'])) $gender = 'MALE';
        if(in_array($gender, ['GIRL', 'F', 'FEMALE'])) $gender = 'FEMALE';

        // 2. FORCE FETCH DATA (Smart Fallback Logic)
        $details = \App\Models\EnrollmentDetail::where('email', $student->email_address)
                    ->orWhere('lrn', $student->lrn)
                    ->latest() 
                    ->first();

        $applicantFallback = \App\Models\Applicant::where('lrn', $student->lrn)->first();

        // 3. VARIABLE MAPPING (Priority: Details -> Applicant -> Student Record -> 'N/A')
        function getData($d, $a, $s, $fieldD, $fieldA, $fieldS) {
            return $d->$fieldD ?? ($a->$fieldA ?? ($s->$fieldS ?? 'N/A'));
        }

        // Address
        $street = getData($details, $applicantFallback, $student, 'street_house_no', 'street_address', 'street_address');
        $brgy = getData($details, $applicantFallback, $student, 'barangay', 'barangay', 'barangay');
        $city = getData($details, $applicantFallback, $student, 'municipality_city', 'municipality_city', 'municipality_city');
        $prov = getData($details, $applicantFallback, $student, 'province', 'province', 'province');
        $zip  = $details->zip_code ?? ($applicantFallback->zip_code ?? ($student->zip_code ?? ''));

        // Guardian
        $g_name = getData($details, $applicantFallback, $student, 'guardian_name', 'guardian_name', 'guardian_name');
        $g_rel = getData($details, $applicantFallback, $student, 'guardian_relationship', 'guardian_relationship', 'guardian_relationship');
        $g_contact = getData($details, $applicantFallback, $student, 'guardian_contact', 'guardian_contact', 'guardian_contact');
        
        // ⚡ ROBUST FLAG CHECKING (Handles 'Yes', 'yes', 1, true, etc.) ⚡
        $raw_ip = $details->is_ip ?? ($applicantFallback->is_ip ?? ($student->is_ip ?? 'No'));
        $is_ip = in_array(strtolower(trim($raw_ip)), ['yes', '1', 'true', 'y']);
        $ip_grp = $details->ip_group_name ?? ($applicantFallback->ip_group_name ?? ($student->ip_group_name ?? ''));
        
        $raw_pwd = $details->is_pwd ?? ($applicantFallback->is_pwd ?? ($student->is_pwd ?? 'No'));
        $is_pwd = in_array(strtolower(trim($raw_pwd)), ['yes', '1', 'true', 'y']);
        $pwd_id = $details->pwd_disability ?? ($applicantFallback->pwd_disability ?? ($student->pwd_disability ?? ''));
        
        $raw_4ps = $details->is_4ps ?? ($applicantFallback->is_4ps ?? ($student->is_4ps ?? 'No'));
        $is_4ps = in_array(strtolower(trim($raw_4ps)), ['yes', '1', 'true', 'y']);
        
        // Other Remarks
        $otherRemarks = $details->other_remarks ?? ($applicantFallback->other_remarks ?? ($student->other_remarks ?? ''));
    @endphp

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            
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

            {{-- ⚡ RENEWAL / CONTINUING ENROLLMENT BANNER ⚡ --}}
            @if(in_array($student->promotion_status, ['Promoted', 'Conditional']) || str_contains($student->promotion_status, 'Honors') || $student->status === 'Continuing')
                <div class="bg-gradient-to-r from-indigo-600 to-blue-700 rounded-2xl shadow-xl overflow-hidden mb-6 sm:mb-8 border border-indigo-400 relative">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 relative z-10">
                        <div class="text-white text-center sm:text-left">
                            <h3 class="text-lg sm:text-xl font-black uppercase tracking-widest mb-1 flex items-center justify-center sm:justify-start gap-2">
                                <i class='bx bxs-graduation text-2xl text-yellow-400'></i> 
                                Ready for the Next School Year?
                            </h3>
                            <p class="text-indigo-100 text-xs sm:text-sm font-medium">Please submit your updated documents to renew your NASCENT SAS scholarship and enroll.</p>
                        </div>
                        <a href="{{ route('student.renew-enrollment') }}" wire:navigate class="w-full sm:w-auto bg-white hover:bg-slate-50 text-indigo-700 font-black py-3 sm:py-4 px-6 sm:px-8 rounded-xl shadow-lg transition-transform transform hover:scale-105 active:scale-95 uppercase tracking-widest text-xs flex justify-center items-center gap-2 shrink-0 border-b-4 border-indigo-200">
                            Renew Enrollment <i class='bx bx-right-arrow-alt text-lg'></i>
                        </a>
                    </div>
                </div>
            @endif

            {{-- 1. PROFILE CARD SECTION --}}
            <div class="bg-white/95 backdrop-blur-md rounded-lg shadow-lg overflow-hidden mb-6 sm:mb-8 border-l-8 border-indigo-700 ring-1 ring-black/5">
                <div class="p-6 md:flex items-start justify-between">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start mb-4 md:mb-0 text-center sm:text-left">
                        
                        {{-- Profile Picture --}}
                        <div class="h-20 w-20 sm:h-24 sm:w-24 rounded-full bg-gray-200 border-4 border-indigo-500 shadow-sm overflow-hidden mb-3 sm:mb-0 sm:mr-6 flex-shrink-0 relative group mx-auto sm:mx-0">
                            @if($student->id_picture)
                                <img src="{{ $student->id_picture }}" alt="Profile" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center bg-indigo-100 text-indigo-700 text-xl sm:text-2xl font-bold">
                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                </div>
                            @endif
                        </div>

                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">{{ $student->last_name }}, {{ $student->first_name }}</h1>
                            <p class="text-xs sm:text-sm text-gray-500 font-semibold">NAS ID: <span class="text-indigo-600">{{ $student->nas_student_id }}</span></p>
                            <p class="text-xs sm:text-sm text-gray-500">LRN: {{ $student->lrn ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    {{-- Status Badges --}}
                    <div class="text-center md:text-right space-y-2 sm:space-y-1 w-full md:w-auto mt-4 md:mt-0 flex flex-col items-center md:items-end">
                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full w-auto">
                            {{ $student->grade_level }} - {{ $student->section->section_name ?? 'Unassigned' }}
                        </span>
                        
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full w-auto">
                            {{ $student->team->team_name ?? $student->sport ?? 'No Sport' }}
                        </span>
                        <div class="text-xs text-gray-400 mt-1 font-bold">Status: <span class="text-slate-600">{{ $student->status }}</span></div>
                    </div>
                </div>
                
                {{-- Expandable Details --}}
                <div x-data="{ showInfo: false }" class="border-t border-gray-100">
                    <button @click="showInfo = !showInfo" class="w-full text-center py-3 sm:py-2 text-xs text-gray-500 hover:bg-gray-50 flex justify-center items-center bg-gray-50/50 transition active:bg-gray-200">
                        <span x-show="!showInfo">View Full Profile Details</span>
                        <span x-show="showInfo">Hide Profile Details</span>
                        <svg class="w-4 h-4 ml-1 transform transition-transform" :class="{'rotate-180': showInfo}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div x-show="showInfo" x-transition style="display: none;" class="p-4 sm:p-6 bg-white/95 grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 text-sm border-t">
                        <div>
                            <h4 class="font-bold text-indigo-700 mb-2 border-b pb-1">Personal Data</h4>
                            <p class="mb-1"><span class="text-gray-500">Birthdate:</span> {{ $student->birthdate ? date('M d, Y', strtotime($student->birthdate)) : 'N/A' }}</p>
                            <p class="mb-1"><span class="text-gray-500">Age:</span> {{ $student->birthdate ? \Carbon\Carbon::parse($student->birthdate)->age : 'N/A' }}</p>
                            <p class="mb-1"><span class="text-gray-500">Sex:</span> {{ $gender }}</p>
                            
                            {{-- Specific Groups Badges --}}
                            @if($is_ip || $is_pwd || $is_4ps || !empty($otherRemarks))
                                <div class="mt-2 flex flex-wrap gap-1">
                                    @if($is_ip) <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-[9px] font-bold rounded uppercase">IP: {{ $ip_grp ?: 'Yes' }}</span> @endif
                                    @if($is_pwd) <span class="px-2 py-0.5 bg-purple-100 text-purple-800 text-[9px] font-bold rounded uppercase">PWD: {{ $pwd_id ?: 'Yes' }}</span> @endif
                                    @if($is_4ps) <span class="px-2 py-0.5 bg-rose-100 text-rose-800 text-[9px] font-bold rounded uppercase">4Ps</span> @endif
                                    @if(!empty($otherRemarks)) <span class="px-2 py-0.5 bg-gray-200 text-gray-800 text-[9px] font-bold rounded uppercase">{{ $otherRemarks }}</span> @endif
                                </div>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-bold text-indigo-700 mb-2 border-b pb-1">Contact Info</h4>
                            <p class="mb-1"><span class="text-gray-500">Email:</span> <span class="break-all">{{ $student->email_address }}</span></p>
                            <p class="mb-1"><span class="text-gray-500">Address:</span> {{ $brgy }}, {{ $city }}, {{ $prov }} {{ $zip ? '('.$zip.')' : '' }}</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-indigo-700 mb-2 border-b pb-1">Guardian</h4>
                            <p class="mb-1"><span class="text-gray-500">Name:</span> {{ $g_name }} <span class="italic text-xs text-gray-400">({{ $g_rel }})</span></p>
                            <p class="mb-1"><span class="text-gray-500">Contact:</span> {{ $g_contact }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- 2. PROMOTION STATUS --}}
            @if(isset($student->promotion_status) && $student->promotion_status)
                @php
                    $borderClass = 'border-red-500';
                    $textClass = 'text-red-600';
                    if($student->promotion_status == 'Promoted' || str_contains($student->promotion_status, 'Honors')) {
                        $borderClass = 'border-green-500';
                        $textClass = 'text-green-600';
                    } elseif($student->promotion_status == 'Conditional') {
                        $borderClass = 'border-yellow-500';
                        $textClass = 'text-yellow-600';
                    }
                @endphp
                <div class="mb-6 sm:mb-8 bg-white/95 backdrop-blur-md shadow-md rounded-lg border-l-8 {{ $borderClass }} p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-center text-center sm:text-left ring-1 ring-black/5">
                    <div class="mb-2 sm:mb-0">
                        <h3 class="text-lg font-bold text-gray-800">End of School Year Status</h3>
                        <p class="text-xs sm:text-sm text-gray-600">Based on academic performance.</p>
                    </div>
                    <span class="text-lg sm:text-xl md:text-2xl font-extrabold uppercase tracking-wider {{ $textClass }}">
                        {{ $student->promotion_status }}
                    </span>
                </div>
            @endif

            {{-- 3. CLASS SCHEDULE --}}
            <div x-data="{ showSchedule: false }" class="bg-white/95 backdrop-blur-md shadow-md rounded-lg overflow-hidden mb-6 sm:mb-8 border border-gray-200">
                <button @click="showSchedule = !showSchedule" class="w-full p-4 bg-blue-50/80 border-b border-gray-200 flex justify-between items-center hover:bg-blue-100/80 transition active:bg-blue-200">
                    <div class="flex items-center text-blue-800">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 00-2-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <h3 class="text-base sm:text-lg font-bold">Class Schedule</h3>
                    </div>
                    <div class="text-xs font-bold text-blue-600 uppercase flex items-center">
                        <span x-show="!showSchedule">Show</span>
                        <span x-show="showSchedule" style="display: none;">Hide</span>
                        <svg class="w-4 h-4 ml-1 transform transition-transform" :class="{'rotate-180': showSchedule}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </button>
                <div x-show="showSchedule" style="display: none;" class="p-0 sm:p-4 bg-white/95 transition-all duration-300">
                    @if($student->section && $student->section->schedules && $student->section->schedules->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase">
                                        <th class="p-3 w-1/3">Time</th>
                                        <th class="p-3">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->section->schedules as $sched)
                                    <tr class="border-b last:border-0 hover:bg-gray-50/50">
                                        <td class="p-3 font-mono text-xs text-blue-600 font-bold whitespace-nowrap align-top">
                                            {{ date('h:i A', strtotime($sched->time_start)) }} <br> 
                                            <span class="text-gray-400 font-normal">to</span> <br>
                                            {{ date('h:i A', strtotime($sched->time_end)) }}
                                        </td>
                                        <td class="p-3 align-top">
                                            <span class="font-bold text-gray-800 block text-sm">{{ $sched->subject->subject_name }}</span>
                                            <span class="inline-block bg-gray-100 rounded px-2 py-0.5 text-[10px] text-gray-500 font-bold mt-1 uppercase">{{ $sched->day }}</span>
                                            <span class="text-gray-500 text-xs block mt-1">Room: {{ $sched->room ?? 'TBA' }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-gray-500 italic p-4 text-sm">No class schedule available yet.</p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- 4. ACADEMIC RECORDS --}}
                <div class="bg-white/95 backdrop-blur-md overflow-hidden shadow-md sm:rounded-lg border border-gray-200">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-indigo-50/80">
                        <h3 class="text-base sm:text-lg font-bold text-indigo-800">Academic Records</h3>
                    </div>
                    <div class="p-0">
                        @if($student->grades && $student->grades->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-xs sm:text-sm">
                                    <thead class="bg-gray-50 text-gray-600 font-bold uppercase text-[10px] sm:text-xs">
                                        <tr>
                                            <th class="p-3 pl-4 text-left">Subject</th>
                                            <th class="p-2 text-center w-10">Q1</th>
                                            <th class="p-2 text-center w-10">Q2</th>
                                            <th class="p-2 text-center w-10">Q3</th>
                                            <th class="p-2 text-center w-10">Q4</th>
                                            <th class="p-2 text-center text-indigo-700 bg-indigo-50 w-12">Final</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($student->grades as $grade)
                                            <tr class="hover:bg-gray-50/50 transition">
                                                <td class="p-3 pl-4 font-medium text-gray-800 whitespace-nowrap">
                                                    {{ $grade->schedule->subject->subject_name ?? 'Subject' }}
                                                </td>
                                                <td class="p-2 text-center text-gray-600">{{ $grade->first_quarter ?? '-' }}</td>
                                                <td class="p-2 text-center text-gray-600">{{ $grade->second_quarter ?? '-' }}</td>
                                                <td class="p-2 text-center text-gray-600">{{ $grade->third_quarter ?? '-' }}</td>
                                                <td class="p-2 text-center text-gray-600">{{ $grade->fourth_quarter ?? '-' }}</td>
                                                <td class="p-2 text-center font-bold bg-indigo-50/50 
                                                    {{ ($grade->final_grade && $grade->final_grade < 75) ? 'text-red-600' : 'text-green-600' }}">
                                                    {{ $grade->final_grade ?? '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-8 text-center text-gray-400 text-sm">No grades posted yet.</div>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- 5. AWARDS --}}
                    <div class="bg-white/95 backdrop-blur-md overflow-hidden shadow-md sm:rounded-lg border border-gray-200">
                        <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-yellow-50/80">
                            <h3 class="text-base sm:text-lg font-bold text-yellow-800">Awards & Recognition</h3>
                        </div>
                        <div class="p-4">
                             @if($student->awards && $student->awards->count() > 0)
                                <ul class="space-y-2">
                                    @foreach($student->awards as $award)
                                        <li class="flex items-start sm:items-center p-3 bg-white border border-yellow-100 rounded text-sm shadow-sm">
                                            <svg class="w-5 h-5 text-yellow-500 mr-2 flex-shrink-0 mt-0.5 sm:mt-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            <span class="font-bold text-gray-800 leading-tight">{{ $award->award_name }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else <p class="text-gray-400 text-center italic text-sm">No awards yet.</p> @endif
                        </div>
                    </div>

                    {{-- 6. ATTENDANCE LOG --}}
                    <div class="bg-white/95 backdrop-blur-md overflow-hidden shadow-md sm:rounded-lg border border-gray-200">
                        <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-green-50/80">
                            <h3 class="text-base sm:text-lg font-bold text-green-800">Attendance Log</h3>
                        </div>
                        <div class="p-4">
                             @if($student->attendances && $student->attendances->count() > 0)
                                <div class="space-y-2">
                                    @foreach($student->attendances->sortByDesc('date')->take(5) as $att)
                                        @php
                                            $statusColor = match(strtolower($att->status)) {
                                                'present' => 'bg-green-100 text-green-700',
                                                'late'    => 'bg-yellow-100 text-yellow-700',
                                                'absent'  => 'bg-red-100 text-red-700',
                                                'excused' => 'bg-blue-100 text-blue-700',
                                                default   => 'bg-gray-100 text-gray-700',
                                            };
                                        @endphp
                                        <div class="p-3 rounded border border-gray-100 text-sm bg-white shadow-sm">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <span class="text-gray-700 font-bold mr-2 text-sm">{{ date('M d', strtotime($att->date)) }}</span>
                                                    <span class="text-xs text-gray-400 uppercase tracking-wide">({{ date('D', strtotime($att->date)) }})</span>
                                                </div>
                                                <span class="font-bold text-[10px] px-2 py-1 rounded uppercase {{ $statusColor }}">
                                                    {{ $att->status }}
                                                </span>
                                            </div>
                                            @if(strtolower($att->status) == 'excused' && !empty($att->remarks))
                                                <div class="mt-2 text-right border-t border-dashed border-gray-200 pt-1">
                                                    <span class="text-[10px] italic text-blue-500">Note: {{ $att->remarks }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else <p class="text-gray-400 text-center italic text-sm">No attendance records.</p> @endif
                        </div>
                    </div>

                    {{-- 7. EXIT CLEARANCE --}}
                    <div class="bg-white/95 backdrop-blur-md overflow-hidden shadow-md sm:rounded-lg border border-gray-200">
                        <div class="p-4 flex items-center justify-between">
                            <div>
                                <h3 class="text-sm sm:text-md font-bold text-gray-800">Exit Clearance</h3>
                                <p class="text-[10px] sm:text-xs text-gray-500">Transfer/Graduation Request</p>
                            </div>
                            <button type="button" onclick="alert('Request sent.')" class="bg-gray-800 hover:bg-gray-700 text-white text-xs font-bold py-2 px-4 rounded shadow transition active:scale-95">Request</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-student-layout>