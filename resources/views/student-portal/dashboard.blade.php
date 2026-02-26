<x-student-layout>
    <x-slot name="header">
        
        {{-- ============================================================= --}}
        {{-- 📱 MOBILE HEADER: Compact Badge & Live Indicator               --}}
        {{-- ============================================================= --}}
        <div class="flex md:hidden items-center justify-between w-full py-1">
            
            {{-- Badge --}}
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700 uppercase shadow-sm border border-indigo-200">
                <i class='bx bxs-dashboard mr-1.5 text-sm'></i> Dashboard
            </span>

            {{-- Live Indicator --}}
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-600 animate-pulse flex items-center shadow-sm border border-emerald-200">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1"></span> LIVE
            </span>

        </div>

        {{-- ============================================================= --}}
        {{-- 💻 DESKTOP HEADER: Standard View                               --}}
        {{-- ============================================================= --}}
        <div class="hidden md:flex flex-col md:flex-row justify-between items-start md:items-center gap-4 py-2">
            
            {{-- TITLE --}}
            <div class="flex items-center justify-between w-full md:w-auto">
                <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-3">
                    {{ __('Student Portal Dashboard') }}
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-600 animate-pulse flex items-center shadow-sm border border-emerald-200">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span> LIVE
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

        // 3. VARIABLE MAPPING (Clean Display Formatting)
        function formatData($value) {
            $val = trim((string) $value);
            return (empty($val) || strcasecmp($val, 'n/a') === 0 || strcasecmp($val, 'none') === 0) ? '-' : $val;
        }

        function getData($d, $a, $s, $fieldD, $fieldA, $fieldS) {
            $val = $d->$fieldD ?? ($a->$fieldA ?? ($s->$fieldS ?? null));
            return formatData($val);
        }

        // ⚡ FETCH EXTENSION NAME ⚡
        $raw_ext = $details->extension_name ?? ($applicantFallback->extension_name ?? '');
        $ext_name = formatData($raw_ext) === '-' ? '' : formatData($raw_ext);

        // Address
        $street = formatData(getData($details, $applicantFallback, $student, 'street_house_no', 'street_address', 'street_address'));
        $brgy = formatData(getData($details, $applicantFallback, $student, 'barangay', 'barangay', 'barangay'));
        $city = formatData(getData($details, $applicantFallback, $student, 'municipality_city', 'municipality_city', 'municipality_city'));
        $prov = formatData(getData($details, $applicantFallback, $student, 'province', 'province', 'province'));
        $zip  = formatData($details->zip_code ?? ($applicantFallback->zip_code ?? ($student->zip_code ?? '')));

        // Guardian
        $g_name = formatData(getData($details, $applicantFallback, $student, 'guardian_name', 'guardian_name', 'guardian_name'));
        $g_rel = formatData(getData($details, $applicantFallback, $student, 'guardian_relationship', 'guardian_relationship', 'guardian_relationship'));
        $g_contact = formatData(getData($details, $applicantFallback, $student, 'guardian_contact', 'guardian_contact', 'guardian_contact'));
        
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

        // ⚡ SPORT LOGIC ⚡
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
            $displaySport = 'No Sport'; // Fallback text
        }

        // ==========================================
        // ⚙️ ENROLLMENT PERIOD CONFIGURATION (DYNAMIC) ⚙️
        // ==========================================
        $currentDate = date('Y-m-d');

        $enrollmentStartDate = \App\Models\SystemSetting::where('setting_key', 'enrollment_start_date')->value('setting_value') ?? date('Y') . '-06-01';
        $enrollmentEndDate   = \App\Models\SystemSetting::where('setting_key', 'enrollment_end_date')->value('setting_value') ?? date('Y') . '-08-31';
        
        $isEnrollmentOpen = ($currentDate >= $enrollmentStartDate && $currentDate <= $enrollmentEndDate);
        
        $displayStartDate = date('F j, Y', strtotime($enrollmentStartDate));
        $displayEndDate   = date('F j, Y', strtotime($enrollmentEndDate));
    @endphp

    <div class="relative py-6 md:py-10">
        <div class="relative z-10 max-w-7xl mx-auto sm:px-6 lg:px-8 px-4 space-y-6">
            
            {{-- ALERTS --}}
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl shadow-sm relative text-sm flex items-center gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                        <i class='bx bx-check text-lg text-emerald-600'></i>
                    </div>
                    <span class="flex-1 font-medium">{!! session('success') !!}</span>
                    <button onclick="this.parentElement.style.display='none'" class="text-emerald-400 hover:text-emerald-600 transition">
                        <i class='bx bx-x text-xl'></i>
                    </button>
                </div>
            @endif

            {{-- ⚡ RENEWAL / CONTINUING ENROLLMENT BANNER ⚡ --}}
            @if($student->status === 'Pending Renewal')
                <div class="relative bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg overflow-hidden border border-emerald-400/30">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mt-20 -mr-20 blur-2xl"></div>
                    <div class="p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 relative z-10">
                        <div class="text-white text-center sm:text-left">
                            <h3 class="text-lg sm:text-xl font-extrabold mb-1 flex items-center justify-center sm:justify-start gap-2">
                                <i class='bx bxs-check-circle text-2xl text-yellow-300'></i> 
                                Renewal Application Submitted!
                            </h3>
                            <p class="text-emerald-100 text-xs sm:text-sm font-medium text-balance">Your application is currently being reviewed by the Registrar's Office. Please wait for further updates.</p>
                        </div>
                        <div class="flex-shrink-0 bg-white/15 backdrop-blur-sm text-white font-bold py-3 px-6 rounded-xl border border-white/20 text-xs flex items-center gap-2">
                            <i class='bx bx-time text-lg'></i> Pending Review
                        </div>
                    </div>
                </div>
            @elseif((in_array($student->promotion_status, ['Promoted', 'Conditional']) || ($student->promotion_status && str_contains($student->promotion_status, 'Honors')) || $student->status === 'Continuing') && $student->status !== 'Enrolled')
                <div class="relative bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl shadow-lg overflow-hidden border border-indigo-400/30">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mt-20 -mr-20 blur-2xl"></div>
                    <div class="p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 relative z-10">
                        <div class="text-white text-center sm:text-left">
                            <h3 class="text-lg sm:text-xl font-extrabold mb-1 flex items-center justify-center sm:justify-start gap-2">
                                <i class='bx bxs-graduation text-2xl text-yellow-300'></i> 
                                Ready for the Next School Year?
                            </h3>
                            
                            @if($isEnrollmentOpen)
                                <p class="text-indigo-100 text-xs sm:text-sm font-medium">Please submit your updated documents to renew your NASCENT SAS scholarship and enroll.</p>
                            @else
                                <p class="text-indigo-200 text-xs sm:text-sm font-medium">
                                    <i class='bx bx-time-five'></i> Enrollment period is currently closed. It will open from <strong class="text-white">{{ $displayStartDate }}</strong> to <strong class="text-white">{{ $displayEndDate }}</strong>.
                                </p>
                            @endif
                        </div>
                        
                        @if($isEnrollmentOpen)
                            <a href="{{ route('student.renew-enrollment') }}" wire:navigate class="flex-shrink-0 bg-white hover:bg-indigo-50 text-indigo-700 font-bold py-3 px-8 rounded-xl shadow-lg transition-all transform hover:scale-105 active:scale-95 text-sm flex items-center gap-2">
                                Renew Enrollment <i class='bx bx-right-arrow-alt text-lg'></i>
                            </a>
                        @else
                            <button disabled class="flex-shrink-0 cursor-not-allowed bg-white/10 backdrop-blur-sm text-indigo-200 font-bold py-3 px-8 rounded-xl border border-white/20 text-sm flex items-center gap-2">
                                <i class='bx bxs-lock-alt text-lg'></i> Enrollment Closed
                            </button>
                        @endif
                    </div>
                </div>
            @endif

            {{-- ============================================================= --}}
            {{-- 1. PROFILE CARD SECTION (Hero Style)                           --}}
            {{-- ============================================================= --}}
            <div class="bg-white/80 backdrop-blur-3xl rounded-3xl shadow-lg border border-white/60 overflow-hidden">
                <div class="relative bg-gradient-to-br from-indigo-600/90 via-indigo-700/90 to-blue-800/90 px-6 sm:px-8 pt-8 pb-16 sm:pb-20">
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'30\' height=\'30\' viewBox=\'0 0 30 30\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M0 15h30M15 0v30\' stroke=\'%23ffffff\' stroke-opacity=\'0.03\' stroke-width=\'1\'/%3E%3C/svg%3E')]"></div>
                    <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start gap-4 text-center sm:text-left">
                        <div class="text-white">
                            <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight">
                                {{ $student->last_name }}@if(!empty($ext_name)) {{ $ext_name }}@endif, 
                                {{ $student->first_name }} 
                                <span class="font-normal text-indigo-200 text-lg sm:text-xl">{{ formatData($student->middle_name) }}</span>
                            </h1>
                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-x-4 gap-y-1 mt-2 text-indigo-200 text-xs sm:text-sm">
                                <span class="font-semibold">NAS ID: <span class="text-white">{{ $student->nas_student_id }}</span></span>
                                <span class="hidden sm:inline text-indigo-400">•</span>
                                <span>LRN: <span class="text-white">{{ $student->lrn ?? '-' }}</span></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Profile Picture + Badges (Overlapping Hero) --}}
                <div class="relative px-6 sm:px-8 -mt-12 sm:-mt-14 pb-6 flex flex-col sm:flex-row items-center sm:items-end justify-between gap-4">
                    <div class="flex items-end gap-4">
                        <div class="h-24 w-24 sm:h-28 sm:w-28 rounded-2xl bg-white/90 backdrop-blur-md border-4 border-white/80 shadow-xl overflow-hidden flex-shrink-0">
                            @if($student->id_picture)
                                <img src="{{ $student->id_picture }}" alt="Profile" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center bg-gradient-to-br from-indigo-100 to-blue-100 text-indigo-600 text-2xl sm:text-3xl font-bold">
                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="hidden sm:block pb-1">
                            <span class="text-sm font-bold text-slate-700">{{ $student->status }}</span>
                        </div>
                    </div>
                    
                    {{-- Status Badges --}}
                    <div class="flex flex-wrap items-center justify-center sm:justify-end gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-xl border border-indigo-100">
                            <i class='bx bxs-school text-sm'></i>
                            Grade {{ trim(str_ireplace('grade', '', strtolower($student->grade_level))) }} - {{ $student->section->section_name ?? 'Unassigned' }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-xl border border-emerald-100">
                            <i class='bx bx-run text-sm'></i>
                            {{ $displaySport }}
                        </span>
                        <span class="sm:hidden inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-xl border border-slate-200">
                            {{ $student->status }}
                        </span>
                    </div>
                </div>
                
                {{-- Expandable Details --}}
                <div x-data="{ showInfo: false }" class="border-t border-slate-100">
                    <button @click="showInfo = !showInfo" class="w-full text-center py-3 text-xs text-slate-500 hover:text-indigo-600 hover:bg-indigo-50/50 flex justify-center items-center gap-1 transition-colors font-semibold">
                        <span x-show="!showInfo">View Full Profile Details</span>
                        <span x-show="showInfo">Hide Profile Details</span>
                        <svg class="w-4 h-4 transform transition-transform" :class="{'rotate-180': showInfo}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div x-show="showInfo" x-transition style="display: none;" class="px-6 sm:px-8 pb-6 pt-2 grid grid-cols-1 md:grid-cols-3 gap-6 text-sm border-t border-slate-100">
                        <div>
                            <h4 class="font-bold text-indigo-600 mb-3 text-xs uppercase tracking-wider flex items-center gap-1.5">
                                <i class='bx bx-user text-base'></i> Personal Data
                            </h4>
                            <div class="space-y-2 text-slate-600">
                                <p><span class="text-slate-400 text-xs">Birthdate:</span> <span class="font-medium text-slate-700">{{ $student->birthdate ? date('M d, Y', strtotime($student->birthdate)) : '-' }}</span></p>
                                <p><span class="text-slate-400 text-xs">Age:</span> <span class="font-medium text-slate-700">{{ $student->birthdate ? \Carbon\Carbon::parse($student->birthdate)->age : '-' }}</span></p>
                                <p><span class="text-slate-400 text-xs">Sex:</span> <span class="font-medium text-slate-700">{{ $gender }}</span></p>
                            </div>
                            
                            {{-- Specific Groups Badges --}}
                            @if($is_ip || $is_pwd || $is_4ps || !empty($otherRemarks))
                                <div class="mt-3 flex flex-wrap gap-1.5">
                                    @if($is_ip) <span class="px-2 py-0.5 bg-amber-50 text-amber-700 text-[10px] font-bold rounded-lg border border-amber-200">IP: {{ $ip_grp ?: 'Yes' }}</span> @endif
                                    @if($is_pwd) <span class="px-2 py-0.5 bg-purple-50 text-purple-700 text-[10px] font-bold rounded-lg border border-purple-200">PWD: {{ $pwd_id ?: 'Yes' }}</span> @endif
                                    @if($is_4ps) <span class="px-2 py-0.5 bg-rose-50 text-rose-700 text-[10px] font-bold rounded-lg border border-rose-200">4Ps</span> @endif
                                    @if(!empty($otherRemarks)) <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg border border-slate-200">{{ $otherRemarks }}</span> @endif
                                </div>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-bold text-indigo-600 mb-3 text-xs uppercase tracking-wider flex items-center gap-1.5">
                                <i class='bx bx-envelope text-base'></i> Contact Info
                            </h4>
                            <div class="space-y-2 text-slate-600">
                                <p><span class="text-slate-400 text-xs">Email:</span> <span class="break-all font-medium text-slate-700">{{ $student->email_address }}</span></p>
                                <p><span class="text-slate-400 text-xs">Address:</span> <span class="font-medium text-slate-700">{{ $brgy }}, {{ $city }}, {{ $prov }} {{ $zip ? '('.$zip.')' : '' }}</span></p>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-bold text-indigo-600 mb-3 text-xs uppercase tracking-wider flex items-center gap-1.5">
                                <i class='bx bx-group text-base'></i> Guardian
                            </h4>
                            <div class="space-y-2 text-slate-600">
                                <p><span class="text-slate-400 text-xs">Name:</span> <span class="font-medium text-slate-700">{{ $g_name }}</span> <span class="italic text-xs text-slate-400">({{ $g_rel }})</span></p>
                                <p><span class="text-slate-400 text-xs">Contact:</span> <span class="font-medium text-slate-700">{{ $g_contact }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- ============================================================= --}}
            {{-- 2. PROMOTION STATUS                                            --}}
            {{-- ============================================================= --}}
            @if(isset($student->promotion_status) && $student->promotion_status)
                @php
                    $promoIcon = 'bx-x-circle';
                    $promoBg = 'from-red-500 to-rose-600';
                    $promoBorder = 'border-red-400/30';
                    if($student->promotion_status == 'Promoted' || str_contains($student->promotion_status, 'Honors')) {
                        $promoIcon = 'bx-trophy';
                        $promoBg = 'from-emerald-500 to-green-600';
                        $promoBorder = 'border-emerald-400/30';
                    } elseif($student->promotion_status == 'Conditional') {
                        $promoIcon = 'bx-error-circle';
                        $promoBg = 'from-amber-500 to-yellow-600';
                        $promoBorder = 'border-amber-400/30';
                    }
                @endphp
                <div class="relative bg-gradient-to-r {{ $promoBg }} rounded-2xl shadow-sm overflow-hidden border {{ $promoBorder }} p-5 sm:p-6 flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-3">
                    <div class="absolute inset-0 bg-white/5"></div>
                    <div class="relative z-10 flex items-center gap-3 text-white">
                        <div class="w-12 h-12 rounded-xl bg-white/15 backdrop-blur-sm flex items-center justify-center flex-shrink-0">
                            <i class='bx {{ $promoIcon }} text-2xl'></i>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-extrabold">End of School Year Status</h3>
                            <p class="text-xs text-white/70 font-medium">Based on academic performance</p>
                        </div>
                    </div>
                    <span class="relative z-10 text-xl sm:text-2xl font-black uppercase tracking-wider text-white drop-shadow">
                        {{ $student->promotion_status }}
                    </span>
                </div>
            @endif

            {{-- ============================================================= --}}
            {{-- 3. CLASS SCHEDULE                                              --}}
            {{-- ============================================================= --}}
            <div x-data="{ showSchedule: false }" class="bg-white/60 backdrop-blur-2xl rounded-3xl shadow-lg overflow-hidden border border-white/50">
                <button @click="showSchedule = !showSchedule" class="w-full p-5 flex justify-between items-center hover:bg-white/40 transition-colors group">
                    <div class="flex items-center gap-3 text-slate-700">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-100 transition-colors">
                            <i class='bx bx-calendar text-xl text-blue-500'></i>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold">Class Schedule</h3>
                    </div>
                    <div class="text-xs font-bold text-blue-500 uppercase flex items-center gap-1">
                        <span x-show="!showSchedule">Show</span>
                        <span x-show="showSchedule" style="display: none;">Hide</span>
                        <svg class="w-4 h-4 transform transition-transform" :class="{'rotate-180': showSchedule}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </button>
                <div x-show="showSchedule" style="display: none;" x-transition class="border-t border-slate-100">
                    @if($student->section && $student->section->schedules && $student->section->schedules->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="bg-slate-50 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                        <th class="px-6 py-3 w-1/3">Time</th>
                                        <th class="px-6 py-3">Details</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($student->section->schedules as $sched)
                                    <tr class="hover:bg-blue-50/30 transition-colors">
                                        <td class="px-6 py-4 font-mono text-xs text-blue-600 font-bold whitespace-nowrap align-top">
                                            {{ date('h:i A', strtotime($sched->time_start)) }} <br> 
                                            <span class="text-slate-300 font-normal">to</span> <br>
                                            {{ date('h:i A', strtotime($sched->time_end)) }}
                                        </td>
                                        <td class="px-6 py-4 align-top">
                                            <span class="font-bold text-slate-800 block text-sm">{{ $sched->subject->subject_name }}</span>
                                            <div class="flex flex-wrap gap-1.5 mt-1.5">
                                                <span class="inline-block bg-indigo-50 text-indigo-600 rounded-lg px-2 py-0.5 text-[10px] font-bold border border-indigo-100">{{ $sched->day }}</span>
                                                <span class="inline-block bg-slate-50 text-slate-500 rounded-lg px-2 py-0.5 text-[10px] font-medium border border-slate-100">Room: {{ $sched->room ?? 'TBA' }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-10 text-center">
                            <div class="w-12 h-12 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mx-auto mb-3">
                                <i class='bx bx-calendar-x text-xl text-slate-300'></i>
                            </div>
                            <p class="text-slate-400 text-sm font-medium">No class schedule available yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ============================================================= --}}
            {{-- GRID: Academic Records & Right Column                          --}}
            {{-- ============================================================= --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- 4. ACADEMIC RECORDS --}}
                <div class="bg-white/60 backdrop-blur-2xl rounded-3xl shadow-lg border border-white/50 overflow-hidden">
                    <div class="p-5 border-b border-white/40 bg-white/30 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center flex-shrink-0">
                            <i class='bx bx-book-open text-xl text-indigo-500'></i>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-800">Academic Records</h3>
                    </div>
                    <div class="p-0">
                        @if($student->grades && $student->grades->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-xs sm:text-sm">
                                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] sm:text-[11px] tracking-wider">
                                        <tr>
                                            <th class="px-5 py-3 text-left">Subject</th>
                                            <th class="px-2 py-3 text-center w-10">Q1</th>
                                            <th class="px-2 py-3 text-center w-10">Q2</th>
                                            <th class="px-2 py-3 text-center w-10">Q3</th>
                                            <th class="px-2 py-3 text-center w-10">Q4</th>
                                            <th class="px-2 py-3 text-center text-indigo-600 bg-indigo-50/50 w-14 font-extrabold">Final</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        @foreach($student->grades as $grade)
                                            <tr class="hover:bg-slate-50/50 transition-colors">
                                                <td class="px-5 py-3 font-medium text-slate-800 whitespace-nowrap">
                                                    {{ $grade->schedule->subject->subject_name ?? 'Subject' }}
                                                </td>
                                                <td class="px-2 py-3 text-center text-slate-500">{{ $grade->first_quarter ?? '-' }}</td>
                                                <td class="px-2 py-3 text-center text-slate-500">{{ $grade->second_quarter ?? '-' }}</td>
                                                <td class="px-2 py-3 text-center text-slate-500">{{ $grade->third_quarter ?? '-' }}</td>
                                                <td class="px-2 py-3 text-center text-slate-500">{{ $grade->fourth_quarter ?? '-' }}</td>
                                                <td class="px-2 py-3 text-center font-extrabold bg-indigo-50/30
                                                    {{ ($grade->final_grade && $grade->final_grade < 75) ? 'text-red-500' : 'text-emerald-600' }}">
                                                    {{ $grade->final_grade ?? '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-10 text-center">
                                <div class="w-12 h-12 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mx-auto mb-3">
                                    <i class='bx bx-book text-xl text-slate-300'></i>
                                </div>
                                <p class="text-slate-400 text-sm font-medium">No grades posted yet.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- 5. AWARDS --}}
                    <div class="bg-white/60 backdrop-blur-2xl rounded-3xl shadow-lg border border-white/50 overflow-hidden">
                        <div class="p-5 border-b border-white/40 bg-white/30 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center flex-shrink-0">
                                <i class='bx bx-trophy text-xl text-amber-500'></i>
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-800">Awards & Recognition</h3>
                        </div>
                        <div class="p-5">
                             @if($student->awards && $student->awards->count() > 0)
                                <ul class="space-y-2.5">
                                    @foreach($student->awards as $award)
                                        <li class="flex items-center p-3.5 bg-amber-50/50 border border-amber-100 rounded-xl text-sm">
                                            <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center mr-3 flex-shrink-0">
                                                <i class='bx bxs-star text-amber-500'></i>
                                            </div>
                                            <span class="font-bold text-slate-700 leading-tight">{{ $award->award_name }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="py-6 text-center">
                                    <div class="w-12 h-12 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mx-auto mb-3">
                                        <i class='bx bx-trophy text-xl text-slate-300'></i>
                                    </div>
                                    <p class="text-slate-400 text-sm font-medium">No awards yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 6. ATTENDANCE LOG --}}
                    <div class="bg-white/60 backdrop-blur-2xl rounded-3xl shadow-lg border border-white/50 overflow-hidden">
                        <div class="p-5 border-b border-white/40 bg-white/30 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center flex-shrink-0">
                                <i class='bx bx-calendar-check text-xl text-emerald-500'></i>
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-800">Attendance Log</h3>
                        </div>
                        <div class="p-5">
                             @if($student->attendances && $student->attendances->count() > 0)
                                <div class="space-y-2.5">
                                    @foreach($student->attendances->sortByDesc('date')->take(5) as $att)
                                        @php
                                            $statusColor = match(strtolower($att->status)) {
                                                'present' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                'late'    => 'bg-amber-50 text-amber-700 border-amber-100',
                                                'absent'  => 'bg-red-50 text-red-700 border-red-100',
                                                'excused' => 'bg-blue-50 text-blue-700 border-blue-100',
                                                default   => 'bg-slate-50 text-slate-700 border-slate-100',
                                            };
                                        @endphp
                                        <div class="p-3.5 rounded-xl border border-slate-100 text-sm bg-white hover:bg-slate-50/50 transition-colors">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-slate-700 font-bold text-sm">{{ date('M d', strtotime($att->date)) }}</span>
                                                    <span class="text-[10px] text-slate-400 font-semibold">({{ date('D', strtotime($att->date)) }})</span>
                                                </div>
                                                <span class="font-bold text-[10px] px-2.5 py-1 rounded-lg uppercase border {{ $statusColor }}">
                                                    {{ $att->status }}
                                                </span>
                                            </div>
                                            @if(strtolower($att->status) == 'excused' && !empty($att->remarks))
                                                <div class="mt-2 text-right border-t border-dashed border-slate-100 pt-2">
                                                    <span class="text-[10px] italic text-blue-500 font-medium">Note: {{ $att->remarks }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="py-6 text-center">
                                    <div class="w-12 h-12 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mx-auto mb-3">
                                        <i class='bx bx-calendar-x text-xl text-slate-300'></i>
                                    </div>
                                    <p class="text-slate-400 text-sm font-medium">No attendance records.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 7. EXIT CLEARANCE --}}
                    <div class="bg-white/60 backdrop-blur-2xl rounded-3xl shadow-lg border border-white/50 overflow-hidden">
                        <div class="p-5 flex items-center justify-between bg-white/30">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center flex-shrink-0">
                                    <i class='bx bx-log-out text-xl text-slate-500'></i>
                                </div>
                                <div>
                                    <h3 class="text-sm sm:text-base font-bold text-slate-800">Exit Clearance</h3>
                                    <p class="text-[11px] text-slate-400 font-medium">Transfer/Graduation Request</p>
                                </div>
                            </div>
                            <button type="button" onclick="alert('Request sent.')" class="bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold py-2.5 px-5 rounded-xl shadow-sm transition-all hover:shadow active:scale-95">
                                Request
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-student-layout>