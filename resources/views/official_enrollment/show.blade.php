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
        // ⚡ LOGIC PARA MALAMAN KUNG ENROLLED NA ⚡
        $stat = strtoupper($applicant->status ?? '');
        $isAdmitted = str_contains($stat, 'ADMITTED') || str_contains($stat, 'OFFICIALLY ENROLLED');
    @endphp

    <div class="py-6 lg:py-12 bg-slate-50 min-h-screen w-full block">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 xl:px-12">
            
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8 w-full">

                {{-- LEFT SIDE: ALL FORM DETAILS --}}
                <div class="w-full lg:col-span-3 space-y-6 lg:space-y-8">
                    
                    {{-- HEADER CARD: STUDENT NAME & LRN --}}
                    <div class="bg-indigo-900 rounded-2xl sm:rounded-[2rem] overflow-hidden shadow-2xl border-b-[6px] sm:border-b-8 border-yellow-400 w-full">
                        <div class="p-5 sm:p-8 lg:p-10 text-white w-full">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-5 lg:gap-6 w-full">
                                <div class="w-full">
                                    <span class="text-[9px] sm:text-[10px] block text-indigo-300 tracking-widest font-black uppercase mb-1.5 sm:mb-2">Verified Applicant Profile</span>
                                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black uppercase tracking-tighter leading-tight break-words w-full">
                                        {{ $applicant->last_name }}, {{ $applicant->first_name }} 
                                        @if($applicant->middle_name && strtoupper($applicant->middle_name) !== 'N/A') {{ $applicant->middle_name }} @endif
                                        @if($applicant->enrollmentDetail && $applicant->enrollmentDetail->extension_name && strtoupper($applicant->enrollmentDetail->extension_name) !== 'N/A') {{ $applicant->enrollmentDetail->extension_name }} @endif
                                    </h1>
                                    
                                    {{-- LRN & SPORT INFO (Wrap on mobile) --}}
                                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4 mt-4 lg:mt-6 w-full sm:w-auto">
                                        <div class="bg-black/20 px-3 sm:px-4 py-2 rounded-xl border border-white/10 flex-1 sm:flex-none">
                                            <p class="text-[8px] sm:text-[9px] text-indigo-300 font-bold uppercase tracking-widest">LRN Number</p>
                                            <p class="font-mono text-base sm:text-lg font-black tracking-widest">{{ $applicant->lrn }}</p>
                                        </div>
                                        <div class="bg-yellow-400 text-indigo-900 px-3 sm:px-4 py-2 rounded-xl flex-1 sm:flex-none">
                                            <p class="text-[8px] sm:text-[9px] font-black uppercase tracking-widest">Focus Sport</p>
                                            <p class="font-black text-base sm:text-lg uppercase">{{ $applicant->sport }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white/10 backdrop-blur-md p-4 sm:p-5 lg:p-6 rounded-2xl lg:rounded-3xl border border-white/20 text-center w-full md:w-auto min-w-[150px] lg:min-w-[180px]">
                                    <p class="text-white/50 text-[9px] sm:text-[10px] font-black uppercase tracking-widest mb-1">Current Status</p>
                                    <span class="{{ $isAdmitted ? 'text-emerald-400' : 'text-yellow-400' }} text-xs sm:text-sm font-black uppercase tracking-widest">{{ $applicant->status }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 1: STUDENT PERSONAL INFO --}}
                    <div class="bg-white p-5 sm:p-6 lg:p-8 rounded-2xl lg:rounded-[2rem] shadow-xl border border-slate-100 w-full">
                        <h3 class="text-indigo-900 font-black uppercase text-[10px] sm:text-xs tracking-widest mb-6 lg:mb-8 flex items-center">
                            <span class="bg-indigo-600 text-white w-5 h-5 sm:w-6 sm:h-6 rounded-md sm:rounded-lg flex items-center justify-center text-[9px] sm:text-[10px] mr-2.5 sm:mr-3 shrink-0">01</span> 
                            Student Personal Information
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 lg:gap-8 w-full">
                            <div>
                                <label class="block text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest">Date of Birth</label>
                                <span class="font-bold text-slate-700 block mt-1 text-sm">{{ \Carbon\Carbon::parse($applicant->date_of_birth)->format('F d, Y') }}</span>
                            </div>
                            <div>
                                <label class="block text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest">Age</label>
                                <span class="font-bold text-slate-700 block mt-1 text-sm">{{ $applicant->age }} Years Old</span>
                            </div>
                            
                            {{-- SEX FIELD --}}
                            <div>
                                <label class="block text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest">Sex</label>
                                <span class="font-bold text-slate-700 block mt-1 text-sm uppercase">
                                    @if(in_array(strtolower($applicant->gender), ['male', 'm', 'boy'])) MALE
                                    @elseif(in_array(strtolower($applicant->gender), ['female', 'f', 'girl'])) FEMALE
                                    @else {{ strtoupper($applicant->gender) }}
                                    @endif
                                </span>
                            </div>

                            <div class="sm:col-span-2 md:col-span-2 bg-indigo-50 p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-indigo-100 w-full overflow-hidden">
                                <label class="block text-[9px] sm:text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Student Email Address</label>
                                <span class="font-black text-indigo-600 text-base sm:text-lg break-words">{{ $applicant->enrollmentDetail->email ?? ($applicant->user->email ?? 'N/A') }}</span>
                            </div>
                            <div class="sm:col-span-1 md:col-span-1">
                                <label class="block text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest">Contact No.</label>
                                <span class="font-black text-slate-700 block mt-1 text-base sm:text-lg">{{ $applicant->guardian_contact }}</span>
                            </div>

                            {{-- ADDRESS SUB-SECTION --}}
                            <div class="sm:col-span-2 md:col-span-3 pt-5 lg:pt-6 border-t border-slate-100 w-full">
                                <label class="block text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 sm:mb-4">Residential Address</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs sm:text-sm w-full">
                                    <div>
                                        <p class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase">Street / House No.</p>
                                        <p class="font-bold text-slate-700 break-words">{{ $applicant->enrollmentDetail->street_house_no ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase">Barangay</p>
                                        <p class="font-bold text-slate-700 break-words">{{ $applicant->barangay }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase">Municipality / City</p>
                                        <p class="font-bold text-slate-700 break-words">{{ $applicant->municipality_city }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase">Province & Zip</p>
                                        <p class="font-bold text-slate-700 break-words">{{ $applicant->province }} ({{ $applicant->zip_code }})</p>
                                    </div>
                                </div>
                            </div>

                            {{-- SPECIAL GROUPS --}}
                            <div class="sm:col-span-2 md:col-span-3 grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 w-full mt-2">
                                <div class="p-3 sm:p-4 rounded-xl sm:rounded-2xl {{ $applicant->is_ip ? 'bg-yellow-50 border border-yellow-100' : 'bg-slate-50 border border-slate-100' }}">
                                    <p class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase">Indigenous Group (IP)</p>
                                    <p class="font-bold text-xs sm:text-sm mt-0.5 {{ $applicant->is_ip ? 'text-yellow-700' : 'text-slate-700' }}">
                                        {{ $applicant->is_ip ? 'YES ('.$applicant->ip_group_name.')' : 'NO' }}
                                    </p>
                                </div>
                                <div class="p-3 sm:p-4 rounded-xl sm:rounded-2xl {{ $applicant->is_pwd ? 'bg-yellow-50 border border-yellow-100' : 'bg-slate-50 border border-slate-100' }}">
                                    <p class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase">Person with Disability (PWD)</p>
                                    <p class="font-bold text-xs sm:text-sm mt-0.5 {{ $applicant->is_pwd ? 'text-yellow-700' : 'text-slate-700' }}">
                                        {{ $applicant->is_pwd ? 'YES ('.$applicant->pwd_disability.')' : 'NO' }}
                                    </p>
                                </div>
                                <div class="p-3 sm:p-4 rounded-xl sm:rounded-2xl {{ $applicant->is_4ps ? 'bg-yellow-50 border border-yellow-100' : 'bg-slate-50 border border-slate-100' }}">
                                    <p class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase">4Ps Beneficiary</p>
                                    <p class="font-bold text-xs sm:text-sm mt-0.5 {{ $applicant->is_4ps ? 'text-yellow-700' : 'text-slate-700' }}">
                                        {{ $applicant->is_4ps ? 'YES' : 'NO' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: PARENTS & GUARDIAN INFO --}}
                    <div class="bg-white p-5 sm:p-6 lg:p-8 rounded-2xl lg:rounded-[2rem] shadow-xl border border-slate-100 w-full">
                        <h3 class="text-indigo-900 font-black uppercase text-[10px] sm:text-xs tracking-widest mb-6 lg:mb-8 flex items-center">
                            <span class="bg-indigo-600 text-white w-5 h-5 sm:w-6 sm:h-6 rounded-md sm:rounded-lg flex items-center justify-center text-[9px] sm:text-[10px] mr-2.5 sm:mr-3 shrink-0">02</span> 
                            Parents & Guardian Information
                        </h3>
                        <div class="space-y-8 sm:space-y-10 text-xs sm:text-sm w-full">
                            
                            {{-- FATHER --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 pb-6 border-b border-slate-100 w-full">
                                <div class="md:col-span-2 flex items-center gap-2 w-full">
                                    <div class="h-px bg-slate-200 flex-1"></div>
                                    <span class="text-[9px] sm:text-[10px] font-black text-indigo-400 uppercase tracking-widest bg-slate-50 px-3 py-1 rounded-full border border-slate-200 shrink-0">Father's Information</span>
                                    <div class="h-px bg-slate-200 flex-1"></div>
                                </div>
                                <div class="w-full">
                                    <p class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase">Full Name</p>
                                    <p class="font-bold text-slate-700 uppercase text-base sm:text-lg break-words">{{ $applicant->enrollmentDetail->father_name ?? 'N/A' }}</p>
                                </div>
                                <div class="w-full">
                                    <p class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase">Contact / Email</p>
                                    <p class="font-bold text-slate-700 break-words">{{ $applicant->enrollmentDetail->father_contact ?? 'N/A' }}</p>
                                    <p class="text-[10px] sm:text-xs text-blue-600 break-words">{{ $applicant->enrollmentDetail->father_email ?? 'N/A' }}</p>
                                </div>
                                <div class="md:col-span-2 w-full">
                                    <p class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase">Father's Address</p>
                                    <p class="font-bold text-slate-700 break-words">{{ $applicant->enrollmentDetail->father_address ?? 'Same as Student' }}</p>
                                </div>
                            </div>

                            {{-- MOTHER --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 pb-6 border-b border-slate-100 w-full">
                                <div class="md:col-span-2 flex items-center gap-2 w-full">
                                    <div class="h-px bg-slate-200 flex-1"></div>
                                    <span class="text-[9px] sm:text-[10px] font-black text-indigo-400 uppercase tracking-widest bg-slate-50 px-3 py-1 rounded-full border border-slate-200 shrink-0">Mother's Information</span>
                                    <div class="h-px bg-slate-200 flex-1"></div>
                                </div>
                                <div class="w-full">
                                    <p class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase">Maiden Name</p>
                                    <p class="font-bold text-slate-700 uppercase text-base sm:text-lg break-words">{{ $applicant->enrollmentDetail->mother_maiden_name ?? 'N/A' }}</p>
                                </div>
                                <div class="w-full">
                                    <p class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase">Contact / Email</p>
                                    <p class="font-bold text-slate-700 break-words">{{ $applicant->enrollmentDetail->mother_contact ?? 'N/A' }}</p>
                                    <p class="text-[10px] sm:text-xs text-blue-600 break-words">{{ $applicant->enrollmentDetail->mother_email ?? 'N/A' }}</p>
                                </div>
                                <div class="md:col-span-2 w-full">
                                    <p class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase">Mother's Address</p>
                                    <p class="font-bold text-slate-700 break-words">{{ $applicant->enrollmentDetail->mother_address ?? 'Same as Student' }}</p>
                                </div>
                            </div>

                            {{-- GUARDIAN --}}
                            <div class="bg-indigo-900 p-5 sm:p-6 rounded-2xl lg:rounded-3xl text-white shadow-xl w-full">
                                <div class="text-[9px] sm:text-[10px] font-black text-indigo-300 uppercase tracking-widest mb-3 sm:mb-4">Designated Guardian (Emergency Contact)</div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 w-full">
                                    <div class="w-full">
                                        <p class="text-[8px] sm:text-[9px] font-black text-indigo-400 uppercase">Guardian Name & Relationship</p>
                                        <p class="font-black text-base sm:text-lg uppercase leading-tight break-words">{{ $applicant->guardian_name }} ({{ $applicant->guardian_relationship }})</p>
                                    </div>
                                    <div class="w-full">
                                        <p class="text-[8px] sm:text-[9px] font-black text-indigo-400 uppercase">Contact / Email</p>
                                        <p class="font-black text-base sm:text-lg break-words">{{ $applicant->guardian_contact }}</p>
                                        <p class="text-[10px] sm:text-xs text-indigo-200 break-words">{{ $applicant->enrollmentDetail->guardian_email ?? $applicant->guardian_email }}</p>
                                    </div>
                                    <div class="sm:col-span-2 border-t border-white/10 pt-4 w-full">
                                        <p class="text-[8px] sm:text-[9px] font-black text-indigo-400 uppercase">Guardian Home Address</p>
                                        <p class="font-bold text-xs sm:text-sm break-words">{{ $applicant->enrollmentDetail->guardian_address ?? 'Same as Student' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3: SCHOOL INFO --}}
                    <div class="bg-white p-5 sm:p-6 lg:p-8 rounded-2xl lg:rounded-[2rem] shadow-xl border border-slate-100 w-full">
                        <h3 class="text-indigo-900 font-black uppercase text-[10px] sm:text-xs tracking-widest mb-6 lg:mb-8 flex items-center">
                            <span class="bg-indigo-600 text-white w-5 h-5 sm:w-6 sm:h-6 rounded-md sm:rounded-lg flex items-center justify-center text-[9px] sm:text-[10px] mr-2.5 sm:mr-3 shrink-0">03</span> 
                            School Information (For Transferees)
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 lg:gap-8 text-xs sm:text-sm w-full">
                            <div class="sm:col-span-2 bg-slate-50 p-4 sm:p-5 rounded-xl sm:rounded-2xl border border-slate-100 w-full">
                                <p class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase">Last School Attended</p>
                                <p class="font-black text-slate-800 text-base sm:text-lg uppercase leading-tight break-words">{{ $applicant->enrollmentDetail->school_name ?? 'NOT A TRANSFEREE' }}</p>
                                <p class="text-[10px] sm:text-xs text-slate-500 mt-1 break-words">School ID: {{ $applicant->enrollmentDetail->school_id ?? 'N/A' }} | Type: {{ $applicant->enrollmentDetail->school_type ?? 'N/A' }}</p>
                            </div>
                            <div class="w-full">
                                <p class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase">Last Grade & School Year</p>
                                <p class="font-bold text-slate-700 break-words">Grade {{ $applicant->enrollmentDetail->last_grade_level ?? 'N/A' }} | S.Y. {{ $applicant->enrollmentDetail->last_school_year ?? 'N/A' }}</p>
                            </div>
                            <div class="w-full">
                                <p class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase">School Address</p>
                                <p class="font-bold text-slate-700 break-words">{{ $applicant->enrollmentDetail->school_address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 4: DOCUMENTS --}}
                    <div class="bg-white p-5 sm:p-6 lg:p-8 rounded-2xl lg:rounded-[2rem] shadow-xl border border-slate-100 w-full">
                        <h3 class="text-indigo-900 font-black uppercase text-[10px] sm:text-xs tracking-widest mb-6 lg:mb-8 flex items-center">
                            <span class="bg-indigo-600 text-white w-5 h-5 sm:w-6 sm:h-6 rounded-md sm:rounded-lg flex items-center justify-center text-[9px] sm:text-[10px] mr-2.5 sm:mr-3 shrink-0">04</span> 
                            Submitted Official Requirements
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 w-full">
                            @php
                                $files = is_string($applicant->uploaded_files) ? json_decode($applicant->uploaded_files, true) : ($applicant->uploaded_files ?? []);
                                $labels = [
                                    'sa_info_form' => 'Student-Athlete Info Form',
                                    'scholarship_app_form' => 'Scholarship Application',
                                    'sa_profile_form' => 'Athlete Profile Summary',
                                    'ppe_clearance' => 'Medical Clearance (PPE)',
                                    'psa_birth_cert' => 'PSA Birth Certificate',
                                    'report_card' => 'Previous Report Card (SF9)',
                                    'guardian_id' => 'Guardian Govt ID',
                                    'kukkiwon_cert' => 'Kukkiwon Certificate (TKD)',
                                    'ip_cert' => 'IP Certification',
                                    'pwd_id' => 'PWD ID Card',
                                    '4ps_id' => '4Ps Membership/ID',
                                    'id_picture' => 'Official 2x2 Photo'
                                ];
                            @endphp

                            @forelse($files as $key => $link)
                                <div class="flex items-center justify-between p-3 sm:p-4 border border-slate-100 rounded-xl sm:rounded-2xl bg-slate-50 hover:bg-indigo-50 transition-all group w-full">
                                    <div class="flex items-center gap-3 sm:gap-4 overflow-hidden w-full">
                                        <div class="bg-white p-2 sm:p-3 rounded-lg sm:rounded-xl text-indigo-600 shadow-sm group-hover:bg-indigo-600 group-hover:text-white shrink-0 transition-colors">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <div class="truncate min-w-0">
                                            <p class="text-[8px] sm:text-[9px] font-black text-slate-400 uppercase">Requirement</p>
                                            <p class="text-[10px] sm:text-xs font-black text-slate-800 uppercase truncate">{{ $labels[$key] ?? str_replace('_', ' ', $key) }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ $link }}" target="_blank" class="bg-slate-800 hover:bg-indigo-600 text-white p-2 sm:p-2.5 rounded-lg sm:rounded-xl transition-all shadow-lg transform hover:scale-105 shrink-0 ml-2">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                </div>
                            @empty
                                <div class="col-span-1 md:col-span-2 text-center py-6 sm:py-8 w-full">
                                    <p class="text-slate-400 italic text-xs sm:text-sm">No files uploaded.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- RIGHT SIDE: FINAL ADMISSION (STICKY SIDEBAR) --}}
                <div class="w-full lg:col-span-1 no-print">
                    <div class="sticky top-8 space-y-5 sm:space-y-6 w-full">
                        <div class="bg-white shadow-2xl rounded-[2rem] sm:rounded-[2.5rem] border border-slate-100 overflow-hidden w-full">
                            
                            @if($isAdmitted)
                                {{-- ⚡ ADMITTED STATE: NO MORE BUTTONS ⚡ --}}
                                <div class="bg-emerald-600 p-6 sm:p-8 text-center text-white relative w-full">
                                    <h3 class="font-black uppercase tracking-tighter italic text-lg sm:text-xl flex items-center justify-center gap-2">
                                        <svg class="w-6 h-6 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Officially Admitted
                                    </h3>
                                    <p class="text-[9px] sm:text-[10px] font-bold text-white/70 uppercase mt-1">Enrollment Complete</p>
                                </div>
                                <div class="p-5 sm:p-8 w-full text-center">
                                    <div class="mb-4">
                                        <label class="block text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 sm:mb-3">Official Student ID</label>
                                        <div class="bg-slate-900 p-4 sm:p-5 rounded-2xl sm:rounded-[2rem] border-2 border-slate-800 text-white w-full">
                                            <span class="font-mono text-xl sm:text-2xl font-black tracking-[0.1em] sm:tracking-[0.2em] text-emerald-400">
                                                {{ $applicant->student_id ?? date('Y').'-'.str_pad($applicant->id, 5, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-xs font-bold text-slate-400 mt-6 italic">No further actions required.</p>
                                </div>
                            
                            @else
                                {{-- ⚡ PENDING STATE: SHOW BUTTONS ⚡ --}}
                                <div class="bg-indigo-600 p-6 sm:p-8 text-center text-white relative w-full">
                                    <h3 class="font-black uppercase tracking-tighter italic text-lg sm:text-xl">Admit Student</h3>
                                    <p class="text-[9px] sm:text-[10px] font-bold text-white/70 uppercase mt-1">Final Registrar Approval</p>
                                </div>
                                <div class="p-5 sm:p-8 w-full">
                                    <form action="{{ route('official-enrollment.store', $applicant->id) }}" method="POST" class="space-y-5 sm:space-y-6 w-full">
                                        @csrf
                                        <div class="text-center w-full">
                                            <label class="block text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 sm:mb-3">Generated Student ID</label>
                                            <div class="bg-slate-900 p-4 sm:p-5 rounded-2xl sm:rounded-[2rem] border-2 border-slate-800 text-white w-full">
                                                <span class="font-mono text-xl sm:text-2xl font-black tracking-[0.1em] sm:tracking-[0.2em] text-indigo-400">
                                                    {{ date('Y') }}-{{ str_pad($applicant->id, 5, '0', STR_PAD_LEFT) }}
                                                </span>
                                            </div>
                                        </div>

                                        <button type="submit" onclick="return confirm('Officially admit this student?')" 
                                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 sm:py-5 px-4 sm:px-6 rounded-xl sm:rounded-[1.5rem] shadow-xl shadow-emerald-100 transition-all transform hover:-translate-y-1 active:scale-95 uppercase tracking-wider sm:tracking-widest text-[10px] sm:text-xs flex justify-center items-center gap-2 sm:gap-3">
                                            CONFIRM & ADMIT
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </button>
                                    </form>

                                    <div class="mt-5 sm:mt-6 pt-5 sm:pt-6 border-t border-slate-100 w-full">
                                        <form action="{{ route('official-enrollment.return', $applicant->id) }}" method="POST" class="w-full">
                                            @csrf @method('PATCH')
                                            <button type="submit" onclick="return confirm('Return for revision?')" 
                                                class="w-full bg-red-50 hover:bg-red-500 text-red-600 hover:text-white font-black py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg sm:rounded-xl transition-all uppercase tracking-wider sm:tracking-widest text-[9px] sm:text-[10px] border border-red-100">
                                                Return for Revision
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>