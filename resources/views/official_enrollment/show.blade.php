<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 no-print w-full">
            <h2 class="font-black text-lg sm:text-xl text-slate-800 leading-tight uppercase tracking-tighter">
                {{ __('Official Enrollment Verification') }}
            </h2>
            <div class="flex w-full sm:w-auto gap-2">
                <a href="{{ route('official-enrollment.index') }}" class="w-full sm:w-auto justify-center bg-slate-800 hover:bg-indigo-600 text-white font-bold py-2.5 px-6 rounded-xl text-xs uppercase transition-all flex items-center gap-2 shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    @php
        // 1. STATUS & GENDER
        $stat = strtoupper($applicant->status ?? '');
        
        // ⚡ FIX: Binago natin ang logic. Dahil "OFFICIALLY ENROLLED" ang initial status
        // galing sa applicant, hindi na natin isasama ang 'ENROLLED' sa check para hindi 
        // mawala ang Admit button. Dapat may "ADMITTED" talaga para maging true ito.
        $isAdmitted = str_contains($stat, 'OFFICIALLY ENROLLED') || str_contains($stat, 'ADMITTED'); 

        $gender = strtoupper($applicant->gender);
        if(in_array($gender, ['BOY', 'M', 'MALE'])) $gender = 'MALE';
        if(in_array($gender, ['GIRL', 'F', 'FEMALE'])) $gender = 'FEMALE';

        // 2. FORCE FETCH ENROLLMENT DETAILS
        $details = $applicant->enrollmentDetail; 
        if (!$details) {
            $details = \App\Models\EnrollmentDetail::where('email', $applicant->email ?? $applicant->user->email)
                        ->orWhere('lrn', $applicant->lrn)
                        ->latest()
                        ->first();
        }

        // 3. DATA MAPPING (Priority: Details -> Applicant -> N/A)
        // Helper function
        function getVal($d, $a, $fieldD, $fieldA) {
            return $d->$fieldD ?? ($a->$fieldA ?? 'N/A');
        }

        // Address
        $street = getVal($details, $applicant, 'street_house_no', 'street_address');
        $brgy   = getVal($details, $applicant, 'barangay', 'barangay');
        $city   = getVal($details, $applicant, 'municipality_city', 'municipality_city');
        $prov   = getVal($details, $applicant, 'province', 'province');
        $region = getVal($details, $applicant, 'region', 'region');
        $zip    = $details->zip_code ?? ($applicant->zip_code ?? '');

        // New fields
        $birthplace = $details->birthplace ?? ($applicant->birthplace ?? 'N/A');
        $religion   = $details->religion ?? ($applicant->religion ?? 'N/A');

        // Father
        $f_name    = getVal($details, $applicant, 'father_name', 'father_name');
        $f_contact = getVal($details, $applicant, 'father_contact', 'father_contact');
        $f_email   = getVal($details, $applicant, 'father_email', 'father_email');
        $f_addr    = getVal($details, $applicant, 'father_address', 'father_address');

        // Mother
        $m_name    = $details->mother_maiden_name ?? ($applicant->mother_name ?? 'N/A');
        $m_contact = getVal($details, $applicant, 'mother_contact', 'mother_contact');
        $m_email   = getVal($details, $applicant, 'mother_email', 'mother_email');
        $m_addr    = getVal($details, $applicant, 'mother_address', 'mother_address');

        // Guardian
        $g_name    = getVal($details, $applicant, 'guardian_name', 'guardian_name');
        $g_rel     = getVal($details, $applicant, 'guardian_relationship', 'guardian_relationship');
        $g_contact = getVal($details, $applicant, 'guardian_contact', 'guardian_contact');
        $g_email   = $details->guardian_email ?? 'N/A';
        $g_addr    = getVal($details, $applicant, 'guardian_address', 'guardian_address');

        // ⚡ SPECIAL GROUPS LOGIC (FIXED) ⚡
        // Logic: Check raw value if it exists in expected "truthy" values
        
        // IP Logic
        $raw_ip = $details->is_ip ?? ($applicant->is_ip ?? 'No');
        // Convert to boolean for easier checking
        $is_ip = (in_array(strtolower($raw_ip), ['yes', '1', 'true', 'on'])); 
        $ip_grp = $details->ip_group_name ?? ($applicant->ip_group_name ?? 'N/A');

        // PWD Logic
        $raw_pwd = $details->is_pwd ?? ($applicant->is_pwd ?? 'No');
        $is_pwd = (in_array(strtolower($raw_pwd), ['yes', '1', 'true', 'on']));
        $pwd_id = $details->pwd_disability ?? ($applicant->pwd_disability ?? 'N/A');

        // 4Ps Logic
        $raw_4ps = $details->is_4ps ?? ($applicant->is_4ps ?? 'No');
        $is_4ps = (in_array(strtolower($raw_4ps), ['yes', '1', 'true', 'on']));
    @endphp

    <div class="py-6 lg:py-12 w-full block"> 
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 xl:px-12">
            
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8 w-full">

                {{-- LEFT SIDE: ALL FORM DETAILS --}}
                <div class="w-full lg:col-span-3 space-y-6 lg:space-y-8">
                    
                    {{-- HEADER CARD: GLASS EFFECT --}}
                    <div class="bg-indigo-900/90 backdrop-blur-md rounded-2xl sm:rounded-[2rem] overflow-hidden shadow-2xl border-b-[6px] sm:border-b-8 border-yellow-400 w-full border border-white/10">
                        <div class="p-5 sm:p-8 lg:p-10 text-white w-full">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-5 lg:gap-6 w-full">
                                <div class="w-full">
                                    <span class="text-[9px] sm:text-[10px] block text-indigo-200 tracking-widest font-black uppercase mb-1.5 sm:mb-2">Verified Applicant Profile</span>
                                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black uppercase tracking-tighter leading-tight break-words w-full text-white">
                                        {{ $applicant->last_name }}, {{ $applicant->first_name }} 
                                        @if(empty(trim($applicant->middle_name)) || in_array(trim(strtoupper($applicant->middle_name)), ['N/A', 'NA', 'NONE', '-'])) - @else {{ $applicant->middle_name }} @endif
                                        @if(!empty($details->extension_name) && !in_array(trim(strtoupper($details->extension_name)), ['N/A', 'NA', 'NONE', '-'])) {{ $details->extension_name }} @endif
                                    </h1>
                                    
                                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4 mt-4 lg:mt-6 w-full sm:w-auto">
                                        <div class="bg-black/30 px-3 sm:px-4 py-2 rounded-xl border border-white/20 flex-1 sm:flex-none">
                                            <p class="text-[8px] sm:text-[9px] text-indigo-200 font-bold uppercase tracking-widest">LRN Number</p>
                                            <p class="font-mono text-base sm:text-lg font-black tracking-widest text-white">{{ $applicant->lrn }}</p>
                                        </div>
                                        <div class="bg-yellow-400 text-indigo-900 px-3 sm:px-4 py-2 rounded-xl flex-1 sm:flex-none">
                                            <p class="text-[8px] sm:text-[9px] font-black uppercase tracking-widest">Focus Sport</p>
                                            <p class="font-black text-base sm:text-lg uppercase">{{ $applicant->sport }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white/10 backdrop-blur-md p-4 sm:p-5 lg:p-6 rounded-2xl lg:rounded-3xl border border-white/20 text-center w-full md:w-auto min-w-[150px] lg:min-w-[180px]">
                                    <p class="text-white/70 text-[9px] sm:text-[10px] font-black uppercase tracking-widest mb-1">Current Status</p>
                                    <span class="{{ $isAdmitted ? 'text-emerald-400' : 'text-yellow-400' }} text-xs sm:text-sm font-black uppercase tracking-widest">{{ $applicant->status }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 1: STUDENT PERSONAL INFO (GLASS EFFECT) --}}
                    <div class="bg-white/90 backdrop-blur-md p-5 sm:p-6 lg:p-8 rounded-2xl lg:rounded-[2rem] shadow-xl border border-white/40 w-full">
                        <h3 class="text-indigo-900 font-black uppercase text-[10px] sm:text-xs tracking-widest mb-6 lg:mb-8 flex items-center">
                            <span class="bg-indigo-600 text-white w-5 h-5 sm:w-6 sm:h-6 rounded-md sm:rounded-lg flex items-center justify-center text-[9px] sm:text-[10px] mr-2.5 sm:mr-3 shrink-0">01</span> 
                            Student Personal Information
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 lg:gap-8 w-full">
                            <div>
                                <label class="block text-[9px] sm:text-[10px] font-black text-slate-500 uppercase tracking-widest">Date of Birth</label>
                                <span class="font-bold text-slate-800 block mt-1 text-sm">{{ \Carbon\Carbon::parse($applicant->date_of_birth)->format('F d, Y') }}</span>
                            </div>
                            <div>
                                <label class="block text-[9px] sm:text-[10px] font-black text-slate-500 uppercase tracking-widest">Age</label>
                                <span class="font-bold text-slate-800 block mt-1 text-sm">{{ $applicant->age }} Years Old</span>
                            </div>
                            
                            <div>
                                <label class="block text-[9px] sm:text-[10px] font-black text-slate-500 uppercase tracking-widest">Sex</label>
                                <span class="font-bold text-slate-800 block mt-1 text-sm uppercase">{{ $gender }}</span>
                            </div>
                            <div>
                                <label class="block text-[9px] sm:text-[10px] font-black text-slate-500 uppercase tracking-widest">Birthplace</label>
                                <span class="font-bold text-slate-800 block mt-1 text-sm">{{ $birthplace }}</span>
                            </div>
                            <div>
                                <label class="block text-[9px] sm:text-[10px] font-black text-slate-500 uppercase tracking-widest">Religion</label>
                                <span class="font-bold text-slate-800 block mt-1 text-sm">{{ $religion }}</span>
                            </div>

                            <div class="sm:col-span-2 md:col-span-3 bg-indigo-50/50 p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-indigo-100 w-full overflow-hidden">
                                <label class="block text-[9px] sm:text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Student Email Address</label>
                                <span class="font-black text-indigo-600 text-base sm:text-lg break-words">{{ $applicant->email ?? $applicant->user->email ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="sm:col-span-2 md:col-span-3 pt-5 lg:pt-6 border-t border-slate-200 w-full">
                                <label class="block text-[9px] sm:text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 sm:mb-4">Residential Address</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 text-xs sm:text-sm w-full">
                                    <div>
                                        <p class="text-[8px] sm:text-[9px] font-black text-slate-500 uppercase">Region</p>
                                        <p class="font-bold text-slate-800 break-words">{{ $region }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[8px] sm:text-[9px] font-black text-slate-500 uppercase">Province</p>
                                        <p class="font-bold text-slate-800 break-words">{{ $prov }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[8px] sm:text-[9px] font-black text-slate-500 uppercase">Municipality / City</p>
                                        <p class="font-bold text-slate-800 break-words">{{ $city }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[8px] sm:text-[9px] font-black text-slate-500 uppercase">Barangay</p>
                                        <p class="font-bold text-slate-800 break-words">{{ $brgy }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[8px] sm:text-[9px] font-black text-slate-500 uppercase">Street / House No.</p>
                                        <p class="font-bold text-slate-800 break-words">{{ $street }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- ⚡ SPECIAL GROUPS (FIXED) ⚡ --}}
                            <div class="sm:col-span-2 md:col-span-3 grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 w-full mt-2">
                                
                                {{-- IP --}}
                                <div class="p-3 sm:p-4 rounded-xl sm:rounded-2xl {{ $is_ip ? 'bg-yellow-50/80 border border-yellow-200' : 'bg-slate-50/50 border border-slate-200' }}">
                                    <p class="text-[8px] sm:text-[9px] font-black text-slate-500 uppercase">Indigenous Group (IP)</p>
                                    <p class="font-bold text-xs sm:text-sm mt-0.5 {{ $is_ip ? 'text-yellow-700' : 'text-slate-600' }}">
                                        {{ $is_ip ? 'YES ('.$ip_grp.')' : 'NO' }}
                                    </p>
                                </div>

                                {{-- PWD --}}
                                <div class="p-3 sm:p-4 rounded-xl sm:rounded-2xl {{ $is_pwd ? 'bg-yellow-50/80 border border-yellow-200' : 'bg-slate-50/50 border border-slate-200' }}">
                                    <p class="text-[8px] sm:text-[9px] font-black text-slate-500 uppercase">Person with Disability (PWD)</p>
                                    <p class="font-bold text-xs sm:text-sm mt-0.5 {{ $is_pwd ? 'text-yellow-700' : 'text-slate-600' }}">
                                        {{ $is_pwd ? 'YES ('.$pwd_id.')' : 'NO' }}
                                    </p>
                                </div>

                                {{-- 4Ps --}}
                                <div class="p-3 sm:p-4 rounded-xl sm:rounded-2xl {{ $is_4ps ? 'bg-yellow-50/80 border border-yellow-200' : 'bg-slate-50/50 border border-slate-200' }}">
                                    <p class="text-[8px] sm:text-[9px] font-black text-slate-500 uppercase">4Ps Beneficiary</p>
                                    <p class="font-bold text-xs sm:text-sm mt-0.5 {{ $is_4ps ? 'text-yellow-700' : 'text-slate-600' }}">
                                        {{ $is_4ps ? 'YES' : 'NO' }}
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: PARENTS & GUARDIAN INFO (GLASS EFFECT) --}}
                    <div class="bg-white/90 backdrop-blur-md p-5 sm:p-6 lg:p-8 rounded-2xl lg:rounded-[2rem] shadow-xl border border-white/40 w-full">
                        <h3 class="text-indigo-900 font-black uppercase text-[10px] sm:text-xs tracking-widest mb-6 lg:mb-8 flex items-center">
                            <span class="bg-indigo-600 text-white w-5 h-5 sm:w-6 sm:h-6 rounded-md sm:rounded-lg flex items-center justify-center text-[9px] sm:text-[10px] mr-2.5 sm:mr-3 shrink-0">02</span> 
                            Parents & Guardian Information
                        </h3>
                        <div class="space-y-8 sm:space-y-10 text-xs sm:text-sm w-full">
                            
                            {{-- FATHER --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 pb-6 border-b border-slate-200 w-full">
                                <div class="md:col-span-2 flex items-center gap-2 w-full">
                                    <div class="h-px bg-slate-200 flex-1"></div>
                                    <span class="text-[9px] sm:text-[10px] font-black text-indigo-400 uppercase tracking-widest bg-slate-50/50 px-3 py-1 rounded-full border border-slate-200 shrink-0">Father's Information</span>
                                    <div class="h-px bg-slate-200 flex-1"></div>
                                </div>
                                <div class="w-full">
                                    <p class="text-[8px] sm:text-[9px] font-black text-slate-500 uppercase">Full Name</p>
                                    <p class="font-bold text-slate-800 uppercase text-base sm:text-lg break-words">{{ $f_name }}</p>
                                </div>
                                <div class="w-full">
                                    <p class="text-[8px] sm:text-[9px] font-black text-slate-500 uppercase">Contact / Email</p>
                                    <p class="font-bold text-slate-800 break-words">{{ $f_contact }}</p>
                                    <p class="text-[10px] sm:text-xs text-blue-600 break-words">{{ $f_email }}</p>
                                </div>
                                <div class="md:col-span-2 w-full">
                                    <p class="text-[8px] sm:text-[9px] font-black text-slate-500 uppercase">Father's Address</p>
                                    <p class="font-bold text-slate-800 break-words">{{ $f_addr }}</p>
                                </div>
                            </div>

                            {{-- MOTHER --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 pb-6 border-b border-slate-200 w-full">
                                <div class="md:col-span-2 flex items-center gap-2 w-full">
                                    <div class="h-px bg-slate-200 flex-1"></div>
                                    <span class="text-[9px] sm:text-[10px] font-black text-indigo-400 uppercase tracking-widest bg-slate-50/50 px-3 py-1 rounded-full border border-slate-200 shrink-0">Mother's Information</span>
                                    <div class="h-px bg-slate-200 flex-1"></div>
                                </div>
                                <div class="w-full">
                                    <p class="text-[8px] sm:text-[9px] font-black text-slate-500 uppercase">Maiden Name</p>
                                    <p class="font-bold text-slate-800 uppercase text-base sm:text-lg break-words">{{ $m_name }}</p>
                                </div>
                                <div class="w-full">
                                    <p class="text-[8px] sm:text-[9px] font-black text-slate-500 uppercase">Contact / Email</p>
                                    <p class="font-bold text-slate-800 break-words">{{ $m_contact }}</p>
                                    <p class="text-[10px] sm:text-xs text-blue-600 break-words">{{ $m_email }}</p>
                                </div>
                                <div class="md:col-span-2 w-full">
                                    <p class="text-[8px] sm:text-[9px] font-black text-slate-500 uppercase">Mother's Address</p>
                                    <p class="font-bold text-slate-800 break-words">{{ $m_addr }}</p>
                                </div>
                            </div>

                            {{-- GUARDIAN --}}
                            <div class="bg-indigo-900/90 backdrop-blur-md p-5 sm:p-6 rounded-2xl lg:rounded-3xl text-white shadow-xl w-full border border-white/10">
                                <div class="text-[9px] sm:text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-3 sm:mb-4">Designated Guardian (Emergency Contact)</div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 w-full">
                                    <div class="w-full">
                                        <p class="text-[8px] sm:text-[9px] font-black text-indigo-300 uppercase">Guardian Name & Relationship</p>
                                        <p class="font-black text-base sm:text-lg uppercase leading-tight break-words">{{ $g_name }} ({{ $g_rel }})</p>
                                    </div>
                                    <div class="w-full">
                                        <p class="text-[8px] sm:text-[9px] font-black text-indigo-300 uppercase">Contact / Email</p>
                                        <p class="font-black text-base sm:text-lg break-words">{{ $g_contact }}</p>
                                        <p class="text-[10px] sm:text-xs text-indigo-200 break-words">{{ $g_email }}</p>
                                    </div>
                                    <div class="sm:col-span-2 border-t border-white/10 pt-4 w-full">
                                        <p class="text-[8px] sm:text-[9px] font-black text-indigo-300 uppercase">Guardian Home Address</p>
                                        <p class="font-bold text-xs sm:text-sm break-words">{{ $g_addr }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3: SCHOOL INFO (GLASS EFFECT) --}}
                    <div class="bg-white/90 backdrop-blur-md p-5 sm:p-6 lg:p-8 rounded-2xl lg:rounded-[2rem] shadow-xl border border-white/40 w-full">
                        <h3 class="text-indigo-900 font-black uppercase text-[10px] sm:text-xs tracking-widest mb-6 lg:mb-8 flex items-center">
                            <span class="bg-indigo-600 text-white w-5 h-5 sm:w-6 sm:h-6 rounded-md sm:rounded-lg flex items-center justify-center text-[9px] sm:text-[10px] mr-2.5 sm:mr-3 shrink-0">03</span> 
                            School Information (For Transferees)
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 lg:gap-8 text-xs sm:text-sm w-full">
                            <div class="sm:col-span-2 bg-slate-50/50 p-4 sm:p-5 rounded-xl sm:rounded-2xl border border-slate-200 w-full">
                                <p class="text-[8px] sm:text-[9px] font-black text-slate-500 uppercase">Last School Attended</p>
                                <p class="font-black text-slate-800 text-base sm:text-lg uppercase leading-tight break-words">{{ $details->school_name ?? 'NOT A TRANSFEREE' }}</p>
                                <p class="text-[10px] sm:text-xs text-slate-500 mt-1 break-words">School ID: {{ $details->school_id ?? 'N/A' }} | Type: {{ $details->school_type ?? 'N/A' }}</p>
                            </div>
                            <div class="w-full">
                                <p class="text-[8px] sm:text-[9px] font-black text-slate-500 uppercase">Last Grade & School Year</p>
                                <p class="font-bold text-slate-800 break-words">Grade {{ $details->last_grade_level ?? 'N/A' }} | S.Y. {{ $details->last_school_year ?? 'N/A' }}</p>
                            </div>
                            <div class="w-full">
                                <p class="text-[8px] sm:text-[9px] font-black text-slate-500 uppercase">School Address</p>
                                <p class="font-bold text-slate-800 break-words">{{ $details->school_address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 4: DOCUMENTS (GLASS EFFECT) --}}
                    <div class="bg-white/90 backdrop-blur-md p-5 sm:p-6 lg:p-8 rounded-2xl lg:rounded-[2rem] shadow-xl border border-white/40 w-full">
                        <form id="verificationForm" action="{{ route('official-enrollment.return', $applicant->id) }}" method="POST">
                            @csrf @method('PATCH')
                            
                            <h3 class="text-indigo-900 font-black uppercase text-[10px] sm:text-xs tracking-widest mb-6 lg:mb-8 flex items-center">
                                <span class="bg-indigo-600 text-white w-5 h-5 sm:w-6 sm:h-6 rounded-md sm:rounded-lg flex items-center justify-center text-[9px] sm:text-[10px] mr-2.5 sm:mr-3 shrink-0">04</span> 
                                Submitted Official Requirements
                            </h3>

                        @php
                            $remarks = is_string($applicant->document_remarks) ? json_decode($applicant->document_remarks, true) : ($applicant->document_remarks ?? []);
                            $isRenewal = $applicant->status === 'Pending Renewal' || ($remarks['is_renewal'] ?? false);
                            $files = is_string($applicant->uploaded_files) ? json_decode($applicant->uploaded_files, true) : ($applicant->uploaded_files ?? []);
                            
                            $renewalLabels = [
                                'renewal_sa_info_form' => 'Student-Athlete Info Form',
                                'renewal_basic_ed_form' => 'Basic Ed. Enrollment Form',
                                'renewal_scholarship_agreement' => 'Scholarship Agreement',
                                'renewal_uniform_measurement' => 'Uniform Measurement',
                                'renewal_health_assessment' => 'Health Assessment Forms',
                                'renewal_passport' => 'Passport (Optional)',
                                'renewal_mother_id' => 'Mother ID (Optional)',
                                'renewal_father_id' => 'Father ID (Optional)',
                                'renewal_guardian_id' => 'Guardian ID'
                            ];

                            $initialLabels = [
                                'sa_info_form' => 'Student-Athlete’s Information Form',
                                'basic_ed_form' => 'Basic Education Enrollment Form',
                                'scholarship_agreement' => 'Scholarship Agreement',
                                'uniform_measurement' => 'Student Uniform Measurement Form',
                                'ppe_clearance' => 'Pre-ingress Health Assessment Forms',
                                'report_card' => 'Grade 6 or Grade 7 Report Card',
                                'psa_birth_cert' => 'PSA Birth Certificate',
                                'passport' => 'Passport of the Student-Athlete (if available)',
                                'mother_id' => 'Mother’s valid Government-Issued ID with signature (not required for all)',
                                'father_id' => 'Father’s valid Government-Issued ID with signature (not required for all)',
                                'guardian_id' => 'Designated Guardian’s valid Government-Issued ID with signature',
                                'ip_cert' => 'IP Certification (If member of an indigenous group) (not required for all)',
                                'pwd_id' => 'PWD ID (If person with disability) (not required for all)',
                                '4ps_id' => '4Ps ID or Certification (If beneficiary of the 4Ps) (not required for all)'
                            ];
                        @endphp

                        @if($isRenewal)
                            {{-- ⚡ RENEWAL DOCUMENTS SUBSECTION ⚡ --}}
                            <div class="mb-8 w-full">
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="px-3 py-1 bg-orange-100 text-orange-700 text-[10px] font-black uppercase rounded-full border border-orange-200 shadow-sm {{ !$isAdmitted ? 'animate-pulse' : '' }}">
                                        {{ $isAdmitted ? 'Scholarship Documents' : 'Scholarship Renewal Documents' }} 
                                        @php
                                            $currentSettingsSy = \App\Models\SystemSetting::where('setting_key', 'current_school_year')->value('setting_value') ?? (date('Y').'-'.(date('Y')+1));
                                        @endphp
                                        (S.Y. {{ $currentSettingsSy }})
                                    </span>
                                    <div class="h-px bg-orange-100 flex-1"></div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 w-full">
                                    @php $hasRenewal = false; @endphp
                                    @foreach($renewalLabels as $key => $label)
                                        @if(isset($files[$key]) && !empty($files[$key]))
                                            @php $hasRenewal = true; @endphp
                                                <div class="flex flex-col w-full">
                                                    <div class="flex items-center justify-between p-3 sm:p-4 border border-orange-200 rounded-xl sm:rounded-2xl bg-orange-50/50 hover:bg-orange-100/50 transition-all group w-full">
                                                        <div class="flex items-center gap-3 sm:gap-4 overflow-hidden w-full">
                                                            <div class="bg-white p-2 sm:p-3 rounded-lg sm:rounded-xl text-orange-600 shadow-sm group-hover:bg-orange-600 group-hover:text-white shrink-0 transition-colors">
                                                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                            </div>
                                                            <div class="truncate min-w-0">
                                                                <p class="text-[8px] sm:text-[9px] font-black text-orange-500 uppercase">{{ $isAdmitted ? 'Requirement' : 'Renewal Requirement' }}</p>
                                                                <p class="text-[10px] sm:text-xs font-black text-slate-800 uppercase truncate">{{ $label }}</p>
                                                            </div>
                                                        </div>
                                                        <a href="{{ $files[$key] }}" target="_blank" class="bg-orange-600 hover:bg-orange-700 text-white p-2 sm:p-2.5 rounded-lg sm:rounded-xl transition-all shadow-lg transform hover:scale-105 shrink-0 ml-2">
                                                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                        </a>
                                                    </div>
                                                    @if(!$isAdmitted)
                                                        <input type="text" name="document_remarks[{{ $key }}]" placeholder="Remarks for this file (optional)" 
                                                               value="{{ $remarks['documents'][$key] ?? '' }}"
                                                               class="mt-2 w-full text-[10px] border-slate-200 rounded-lg px-3 py-2 bg-white/50 focus:ring-orange-500 focus:border-orange-500">
                                                    @endif
                                                </div>
                                        @endif
                                    @endforeach
                                    @if(!$hasRenewal)
                                        <div class="col-span-1 md:col-span-2 text-center py-4 w-full">
                                            <p class="text-orange-400 italic text-xs">No specific renewal files found in this submission.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2 mb-4 mt-6">
                                <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-black uppercase rounded-full border border-slate-200">
                                    Initial Application Files 
                                    @if($applicant->created_at)
                                        @php
                                            $date = \Carbon\Carbon::parse($applicant->created_at);
                                            $y = $date->format('Y');
                                            $m = $date->format('m');
                                            $syInitial = $m >= 6 ? "$y-".($y+1) : ($y-1)."-$y";
                                        @endphp
                                        (S.Y. {{ $syInitial }})
                                    @endif
                                </span>
                                <div class="h-px bg-slate-100 flex-1"></div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 w-full">
                            @forelse($initialLabels as $key => $label)
                                @if(isset($files[$key]) && !empty($files[$key]))
                                        <div class="flex flex-col w-full">
                                            <div class="flex items-center justify-between p-3 sm:p-4 border border-slate-200 rounded-xl sm:rounded-2xl bg-slate-50/50 hover:bg-indigo-50/50 transition-all group w-full">
                                                <div class="flex items-center gap-3 sm:gap-4 overflow-hidden w-full">
                                                    <div class="bg-white p-2 sm:p-3 rounded-lg sm:rounded-xl text-indigo-600 shadow-sm group-hover:bg-indigo-600 group-hover:text-white shrink-0 transition-colors">
                                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    </div>
                                                    <div class="truncate min-w-0">
                                                        <p class="text-[8px] sm:text-[9px] font-black text-slate-500 uppercase">Requirement</p>
                                                        <p class="text-[10px] sm:text-xs font-black text-slate-800 uppercase truncate">{{ $label }}</p>
                                                    </div>
                                                </div>
                                                <a href="{{ $files[$key] }}" target="_blank" class="bg-slate-800 hover:bg-indigo-600 text-white p-2 sm:p-2.5 rounded-lg sm:rounded-xl transition-all shadow-lg transform hover:scale-105 shrink-0 ml-2">
                                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                </a>
                                            </div>
                                            @if(!$isRenewal && !$isAdmitted)
                                                <input type="text" name="document_remarks[{{ $key }}]" placeholder="Remarks for this file (optional)" 
                                                       value="{{ $remarks['documents'][$key] ?? '' }}"
                                                       class="mt-2 w-full text-[10px] border-slate-200 rounded-lg px-3 py-2 bg-white/50 focus:ring-indigo-500 focus:border-indigo-500">
                                            @endif
                                        </div>
                                @endif
                            @empty
                                <div class="col-span-1 md:col-span-2 text-center py-6 sm:py-8 w-full">
                                    <p class="text-slate-400 italic text-xs sm:text-sm">No initial files found.</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- 📚 DOCUMENT HISTORY (PER S.Y.) 📚 --}}
                        @php
                            $history = is_string($applicant->historical_files) ? json_decode($applicant->historical_files, true) : ($applicant->historical_files ?? []);
                        @endphp
                        
                        @if(!empty($history))
                            @foreach($history as $sy => $syFiles)
                                <div class="flex items-center gap-2 mb-4 mt-8">
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-[10px] font-black uppercase rounded-full border border-indigo-200 shadow-sm">S.Y. {{ $sy }} Files</span>
                                    <div class="h-px bg-indigo-100 flex-1"></div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 w-full">
                                    @foreach($syFiles as $key => $url)
                                        @php
                                            $label = $initialLabels[$key] ?? ($renewalLabels[$key] ?? ucwords(str_replace('_', ' ', $key)));
                                        @endphp
                                        <div class="flex flex-col w-full">
                                            <div class="flex items-center justify-between p-3 sm:p-4 border border-slate-200 rounded-xl sm:rounded-2xl bg-white hover:bg-slate-50 transition-all group w-full">
                                                <div class="flex items-center gap-3 sm:gap-4 overflow-hidden w-full">
                                                    <div class="bg-indigo-50 p-2 sm:p-3 rounded-lg sm:rounded-xl text-indigo-600 shadow-inner shrink-0">
                                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                                    </div>
                                                    <div class="truncate min-w-0">
                                                        <p class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase">Archived File</p>
                                                        <p class="text-[10px] sm:text-xs font-black text-slate-700 uppercase truncate">{{ $label }}</p>
                                                    </div>
                                                </div>
                                                <a href="{{ $url }}" target="_blank" class="bg-slate-200 hover:bg-indigo-500 hover:text-white text-slate-700 p-2 sm:p-2.5 rounded-lg sm:rounded-xl transition-all shadow-sm shrink-0 ml-2">
                                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @endif

                    </form>
                </div>
            </div>

                {{-- RIGHT SIDE: FINAL ADMISSION (STICKY SIDEBAR) --}}
                <div class="w-full lg:col-span-1 no-print">
                    <div class="sticky top-8 space-y-5 sm:space-y-6 w-full">
                        {{-- ⚡ GLASS EFFECT FOR SIDEBAR ⚡ --}}
                        <div class="bg-white/90 backdrop-blur-md shadow-2xl rounded-[2rem] sm:rounded-[2.5rem] border border-white/40 overflow-hidden w-full">
                            
                            @if($isAdmitted)
                                {{-- ADMITTED STATE: NO MORE BUTTONS --}}
                                <div class="bg-emerald-600/90 backdrop-blur-md p-6 sm:p-8 text-center text-white relative w-full">
                                    <h3 class="font-black uppercase tracking-tighter italic text-lg sm:text-xl flex items-center justify-center gap-2">
                                        <svg class="w-6 h-6 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Officially Admitted
                                    </h3>
                                    <p class="text-[9px] sm:text-[10px] font-bold text-white/70 uppercase mt-1">Enrollment Complete</p>
                                </div>
                                <div class="p-5 sm:p-8 w-full text-center">
                                    <div class="mb-4">
                                        <label class="block text-[9px] sm:text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 sm:mb-3">Official Student No.</label>
                                        <div class="bg-slate-900 p-4 sm:p-5 rounded-2xl sm:rounded-[2rem] border-2 border-slate-800 text-white w-full">
                                            <span class="font-mono text-xl sm:text-2xl font-black tracking-[0.1em] sm:tracking-[0.2em] text-emerald-400">
                                                @php
                                                    $displayId = $applicant->student_id;
                                                    if (!$displayId) {
                                                        $std = \App\Models\Student::where('lrn', $applicant->lrn)->first();
                                                        $displayId = $std ? $std->nas_student_id : date('Y').'-'.str_pad($applicant->id, 5, '0', STR_PAD_LEFT);
                                                    }
                                                @endphp
                                                {{ $displayId }}
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-xs font-bold text-slate-400 mt-6 italic">No further actions required.</p>
                                </div>
                            
                            @else
                                {{-- PENDING STATE: SHOW BUTTONS --}}
                                <div class="bg-indigo-600/90 backdrop-blur-md p-6 sm:p-8 text-center text-white relative w-full">
                                    <h3 class="font-black uppercase tracking-tighter italic text-lg sm:text-xl">{{ $isRenewal ? 'Confirm Renewal' : 'Admit Student' }}</h3>
                                    <p class="text-[9px] sm:text-[10px] font-bold text-white/70 uppercase mt-1">Final Registrar {{ $isRenewal ? 'Verification' : 'Approval' }}</p>
                                </div>
                                <div class="p-5 sm:p-8 w-full">
                                    <form action="{{ route('official-enrollment.store', $applicant->id) }}" method="POST" class="space-y-5 sm:space-y-6 w-full">
                                        @csrf
                                        @if($isRenewal)
                                            <div class="text-center w-full">
                                                <label class="block text-[9px] sm:text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-2 sm:mb-3">Existing Student No.</label>
                                                <div class="bg-emerald-700/80 p-4 sm:p-5 rounded-2xl sm:rounded-[2rem] border-[3px] border-emerald-500 text-white w-full shadow-inner">
                                                    @php
                                                        $std = \App\Models\Student::where('lrn', $applicant->lrn)->first();
                                                        $existingId = $std ? $std->nas_student_id : $applicant->student_id;
                                                    @endphp
                                                    <span class="font-mono text-xl sm:text-2xl font-black tracking-normal sm:tracking-wide text-white drop-shadow-md">
                                                        {{ $existingId }}
                                                    </span>
                                                    <input type="hidden" name="student_id" value="{{ $existingId }}">
                                                </div>
                                                <p class="text-[8px] text-emerald-400/70 mt-2 flex justify-center items-center gap-1 italic"><i class='bx bxs-check-circle'></i> Continuing student detected</p>
                                            </div>
                                        @else
                                            <div class="text-center w-full">
                                                <label for="student_id" class="block text-[9px] sm:text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 sm:mb-3">Enter Student No. (Registrar)</label>
                                                <div class="bg-slate-900 p-4 sm:p-5 rounded-2xl sm:rounded-[2rem] border-2 border-slate-800 w-full">
                                                    <input type="text" 
                                                           name="student_id" 
                                                           id="student_id"
                                                           value="{{ old('student_id', $applicant->student_id ?? '') }}" 
                                                           required 
                                                           placeholder="e.g. 2026-00001" 
                                                           class="w-full bg-transparent text-center font-mono text-xl sm:text-2xl font-black tracking-[0.1em] sm:tracking-[0.2em] text-indigo-400 border-0 border-b-2 border-indigo-500/50 focus:border-indigo-400 focus:ring-0 placeholder-slate-600 uppercase">
                                                </div>
                                                <p class="text-[8px] text-slate-400 mt-2 italic">This will be the official Student Number assigned by the Registrar</p>
                                            </div>
                                        @endif

                                        <button type="submit" onclick="return confirm('Officially admit this student?')" 
                                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 sm:py-5 px-4 sm:px-6 rounded-xl sm:rounded-[1.5rem] shadow-xl shadow-emerald-100 transition-all transform hover:-translate-y-1 active:scale-95 uppercase tracking-wider sm:tracking-widest text-[10px] sm:text-xs flex justify-center items-center gap-2 sm:gap-3">
                                            {{ $isRenewal ? 'CONFIRM RENEWAL' : 'CONFIRM & ADMIT' }}
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        @if(!$isAdmitted)
                            {{-- ⚡ SEPARATE CARD FOR RETURN FLOW ⚡ --}}
                            <div class="bg-white/90 backdrop-blur-md shadow-2xl rounded-[2rem] sm:rounded-[2.5rem] border border-white/40 overflow-hidden w-full p-6 sm:p-8 animate-fade-in no-print">
                                <div class="mb-4">
                                    <label for="overall_remarks" class="block text-[10px] sm:text-xs font-black text-red-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        Overall Return Instructions
                                    </label>
                                    <textarea name="overall_remarks" id="overall_remarks" form="verificationForm" rows="3" 
                                              class="w-full text-xs border-red-100 rounded-2xl bg-red-50/20 focus:ring-red-500 focus:border-red-500 placeholder-red-300 resize-none shadow-inner"
                                              placeholder="Provide general instructions for the student...">{{ $remarks['overall'] ?? '' }}</textarea>
                                </div>
                                <button type="submit" form="verificationForm" onclick="return confirm('Return for revision?')" 
                                    class="w-full bg-red-50 hover:bg-red-500 text-red-600 hover:text-white font-black py-4 px-6 rounded-2xl transition-all uppercase tracking-widest text-[10px] border border-red-100 shadow-xl shadow-red-100/50 transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 mt-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                    Return for Revision
                                </button>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>