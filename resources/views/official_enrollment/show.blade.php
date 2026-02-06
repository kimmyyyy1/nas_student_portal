<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center no-print">
            <h2 class="font-black text-xl text-slate-800 leading-tight uppercase tracking-tighter">
                {{ __('Official Enrollment Verification') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('official-enrollment.index') }}" class="bg-slate-800 hover:bg-indigo-600 text-white font-bold py-2.5 px-6 rounded-xl text-xs uppercase transition-all flex items-center gap-2 shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                {{-- LEFT SIDE: ALL FORM DETAILS --}}
                <div class="lg:col-span-3 space-y-8">
                    
                    {{-- HEADER CARD: STUDENT NAME & LRN --}}
                    <div class="bg-indigo-900 rounded-[2.5rem] overflow-hidden shadow-2xl border-b-8 border-yellow-400">
                        <div class="p-10 text-white">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                                <div>
                                    <span class="text-[10px] block text-indigo-300 tracking-widest font-black uppercase mb-2">Verified Applicant Profile</span>
                                    <h1 class="text-3xl md:text-4xl font-black uppercase tracking-tighter leading-none">
                                        {{ $applicant->last_name }}, {{ $applicant->first_name }} 
                                        @if($applicant->middle_name && strtoupper($applicant->middle_name) !== 'N/A') {{ $applicant->middle_name }} @endif
                                        @if($applicant->enrollmentDetail && $applicant->enrollmentDetail->extension_name && strtoupper($applicant->enrollmentDetail->extension_name) !== 'N/A') {{ $applicant->enrollmentDetail->extension_name }} @endif
                                    </h1>
                                    <div class="flex items-center gap-4 mt-6">
                                        <div class="bg-black/20 px-4 py-2 rounded-xl border border-white/10">
                                            <p class="text-[9px] text-indigo-300 font-bold uppercase tracking-widest">LRN Number</p>
                                            <p class="font-mono text-lg font-black tracking-widest">{{ $applicant->lrn }}</p>
                                        </div>
                                        <div class="bg-yellow-400 text-indigo-900 px-4 py-2 rounded-xl">
                                            <p class="text-[9px] font-black uppercase tracking-widest">Focus Sport</p>
                                            <p class="font-black text-lg uppercase">{{ $applicant->sport }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white/10 backdrop-blur-md p-6 rounded-3xl border border-white/20 text-center min-w-[180px]">
                                    <p class="text-white/50 text-[10px] font-black uppercase tracking-widest mb-1">Current Status</p>
                                    <span class="text-yellow-400 text-sm font-black uppercase tracking-widest">{{ $applicant->status }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 1: STUDENT PERSONAL INFO --}}
                    <div class="bg-white p-8 rounded-[2rem] shadow-xl border border-slate-100">
                        <h3 class="text-indigo-900 font-black uppercase text-xs tracking-widest mb-8 flex items-center">
                            <span class="bg-indigo-600 text-white w-6 h-6 rounded-lg flex items-center justify-center text-[10px] mr-3">01</span> 
                            Student Personal Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Date of Birth</label>
                                <span class="font-bold text-slate-700 block mt-1">{{ \Carbon\Carbon::parse($applicant->date_of_birth)->format('F d, Y') }}</span>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Age</label>
                                <span class="font-bold text-slate-700 block mt-1">{{ $applicant->age }} Years Old</span>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Sex</label>
                                <span class="font-bold text-slate-700 block mt-1 uppercase">{{ $applicant->gender }}</span>
                            </div>
                            <div class="md:col-span-2 bg-indigo-50 p-4 rounded-2xl border border-indigo-100">
                                <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Student Email Address</label>
                                <span class="font-black text-indigo-600 text-lg">{{ $applicant->enrollmentDetail->email ?? ($applicant->user->email ?? 'N/A') }}</span>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Contact No.</label>
                                <span class="font-black text-slate-700 block mt-1 text-lg">{{ $applicant->guardian_contact }}</span>
                            </div>

                            {{-- ADDRESS SUB-SECTION --}}
                            <div class="md:col-span-3 pt-6 border-t border-slate-100">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Residential Address</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase">Street / House No.</p>
                                        <p class="font-bold text-slate-700">{{ $applicant->enrollmentDetail->street_house_no ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase">Barangay</p>
                                        <p class="font-bold text-slate-700">{{ $applicant->barangay }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase">Municipality / City</p>
                                        <p class="font-bold text-slate-700">{{ $applicant->municipality_city }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase">Province & Zip</p>
                                        <p class="font-bold text-slate-700">{{ $applicant->province }} ({{ $applicant->zip_code }})</p>
                                    </div>
                                </div>
                            </div>

                            {{-- SPECIAL GROUPS --}}
                            <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="p-4 rounded-2xl {{ $applicant->is_ip ? 'bg-yellow-50 border border-yellow-100' : 'bg-slate-50 border border-slate-100' }}">
                                    <p class="text-[9px] font-black text-slate-400 uppercase">Indigenous Group (IP)</p>
                                    <p class="font-bold {{ $applicant->is_ip ? 'text-yellow-700' : 'text-slate-700' }}">
                                        {{ $applicant->is_ip ? 'YES ('.$applicant->ip_group_name.')' : 'NO' }}
                                    </p>
                                </div>
                                <div class="p-4 rounded-2xl {{ $applicant->is_pwd ? 'bg-yellow-50 border border-yellow-100' : 'bg-slate-50 border border-slate-100' }}">
                                    <p class="text-[9px] font-black text-slate-400 uppercase">Person with Disability (PWD)</p>
                                    <p class="font-bold {{ $applicant->is_pwd ? 'text-yellow-700' : 'text-slate-700' }}">
                                        {{ $applicant->is_pwd ? 'YES ('.$applicant->pwd_disability.')' : 'NO' }}
                                    </p>
                                </div>
                                <div class="p-4 rounded-2xl {{ $applicant->is_4ps ? 'bg-yellow-50 border border-yellow-100' : 'bg-slate-50 border border-slate-100' }}">
                                    <p class="text-[9px] font-black text-slate-400 uppercase">4Ps Beneficiary</p>
                                    <p class="font-bold {{ $applicant->is_4ps ? 'text-yellow-700' : 'text-slate-700' }}">
                                        {{ $applicant->is_4ps ? 'YES' : 'NO' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: PARENTS & GUARDIAN INFO --}}
                    <div class="bg-white p-8 rounded-[2rem] shadow-xl border border-slate-100">
                        <h3 class="text-indigo-900 font-black uppercase text-xs tracking-widest mb-8 flex items-center">
                            <span class="bg-indigo-600 text-white w-6 h-6 rounded-lg flex items-center justify-center text-[10px] mr-3">02</span> 
                            Parents & Guardian Information
                        </h3>
                        <div class="space-y-8 text-sm">
                            {{-- FATHER --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-slate-100">
                                <div class="md:col-span-2 text-[10px] font-black text-indigo-400 uppercase tracking-widest">Father's Information</div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase">Full Name</p>
                                    <p class="font-bold text-slate-700 uppercase">{{ $applicant->enrollmentDetail->father_name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase">Contact / Email</p>
                                    <p class="font-bold text-slate-700">{{ $applicant->enrollmentDetail->father_contact ?? 'N/A' }} / {{ $applicant->enrollmentDetail->father_email ?? 'N/A' }}</p>
                                </div>
                            </div>
                            {{-- MOTHER --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-slate-100">
                                <div class="md:col-span-2 text-[10px] font-black text-indigo-400 uppercase tracking-widest">Mother's Information</div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase">Maiden Name</p>
                                    <p class="font-bold text-slate-700 uppercase">{{ $applicant->enrollmentDetail->mother_maiden_name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase">Contact / Email</p>
                                    <p class="font-bold text-slate-700">{{ $applicant->enrollmentDetail->mother_contact ?? 'N/A' }} / {{ $applicant->enrollmentDetail->mother_email ?? 'N/A' }}</p>
                                </div>
                            </div>
                            {{-- GUARDIAN --}}
                            <div class="bg-indigo-900 p-6 rounded-3xl text-white shadow-xl">
                                <div class="text-[10px] font-black text-indigo-300 uppercase tracking-widest mb-4">Designated Guardian (Emergency Contact)</div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-[9px] font-black text-indigo-400 uppercase">Guardian Name & Relationship</p>
                                        <p class="font-black text-lg uppercase leading-tight">{{ $applicant->guardian_name }} ({{ $applicant->guardian_relationship }})</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-indigo-400 uppercase">Contact / Email</p>
                                        <p class="font-black text-lg">{{ $applicant->guardian_contact }}</p>
                                        <p class="text-xs text-indigo-200">{{ $applicant->enrollmentDetail->guardian_email ?? $applicant->guardian_email }}</p>
                                    </div>
                                    <div class="md:col-span-2 border-t border-white/10 pt-4">
                                        <p class="text-[9px] font-black text-indigo-400 uppercase">Guardian Home Address</p>
                                        <p class="font-bold text-sm">{{ $applicant->enrollmentDetail->guardian_address ?? 'Same as Student' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3: SCHOOL INFO (TRANSFEREES) --}}
                    <div class="bg-white p-8 rounded-[2rem] shadow-xl border border-slate-100">
                        <h3 class="text-indigo-900 font-black uppercase text-xs tracking-widest mb-8 flex items-center">
                            <span class="bg-indigo-600 text-white w-6 h-6 rounded-lg flex items-center justify-center text-[10px] mr-3">03</span> 
                            School Information (For Transferees)
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                            <div class="md:col-span-2 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <p class="text-[9px] font-black text-slate-400 uppercase">Last School Attended</p>
                                <p class="font-black text-slate-800 text-lg uppercase leading-tight">{{ $applicant->enrollmentDetail->school_name ?? 'NOT A TRANSFEREE' }}</p>
                                <p class="text-xs text-slate-500 mt-1">School ID: {{ $applicant->enrollmentDetail->school_id ?? 'N/A' }} | Type: {{ $applicant->enrollmentDetail->school_type ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase">Last Grade & School Year</p>
                                <p class="font-bold text-slate-700">Grade {{ $applicant->enrollmentDetail->last_grade_level ?? 'N/A' }} | S.Y. {{ $applicant->enrollmentDetail->last_school_year ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase">School Address</p>
                                <p class="font-bold text-slate-700">{{ $applicant->enrollmentDetail->school_address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 4: DOCUMENTS --}}
                    <div class="bg-white p-8 rounded-[2rem] shadow-xl border border-slate-100">
                        <h3 class="text-indigo-900 font-black uppercase text-xs tracking-widest mb-8 flex items-center">
                            <span class="bg-indigo-600 text-white w-6 h-6 rounded-lg flex items-center justify-center text-[10px] mr-3">04</span> 
                            Submitted Official Requirements
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                <div class="flex items-center justify-between p-4 border border-slate-100 rounded-2xl bg-slate-50 hover:bg-indigo-50 transition-all group">
                                    <div class="flex items-center gap-4 overflow-hidden">
                                        <div class="bg-white p-3 rounded-xl text-indigo-600 shadow-sm group-hover:bg-indigo-600 group-hover:text-white">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-[9px] font-black text-slate-400 uppercase">Requirement</p>
                                            <p class="text-xs font-black text-slate-800 uppercase truncate">{{ $labels[$key] ?? str_replace('_', ' ', $key) }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ $link }}" target="_blank" class="bg-slate-800 hover:bg-indigo-600 text-white p-2.5 rounded-xl transition-all shadow-lg transform hover:scale-105">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                </div>
                            @empty
                                <div class="col-span-2 text-center py-8">
                                    <p class="text-slate-400 italic text-sm">No files uploaded.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- RIGHT SIDE: FINAL ADMISSION --}}
                <div class="lg:col-span-1 no-print">
                    <div class="sticky top-8 space-y-6">
                        <div class="bg-white shadow-2xl rounded-[2.5rem] border border-slate-100 overflow-hidden">
                            <div class="bg-emerald-600 p-8 text-center text-white relative">
                                <h3 class="font-black uppercase tracking-tighter italic text-xl">Admit Student</h3>
                                <p class="text-[10px] font-bold text-white/70 uppercase mt-1">Final Registrar Approval</p>
                            </div>
                            <div class="p-8">
                                <form action="{{ route('official-enrollment.store', $applicant->id) }}" method="POST" class="space-y-6">
                                    @csrf
                                    <div class="text-center">
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Generated Student ID</label>
                                        <div class="bg-slate-900 p-5 rounded-[2rem] border-2 border-slate-800 text-white">
                                            <span class="font-mono text-2xl font-black tracking-[0.2em] text-emerald-400">
                                                {{ date('Y') }}-{{ str_pad($applicant->id, 5, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </div>
                                    </div>

                                    <button type="submit" onclick="return confirm('Officially admit this student?')" 
                                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-5 px-6 rounded-[1.5rem] shadow-xl shadow-emerald-100 transition-all transform hover:-translate-y-1 active:scale-95 uppercase tracking-widest text-xs flex justify-center items-center gap-3">
                                        CONFIRM & ADMIT
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </button>
                                </form>

                                <div class="mt-6 pt-6 border-t border-slate-100">
                                    <form action="{{ route('official-enrollment.return', $applicant->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" onclick="return confirm('Return for revision?')" 
                                            class="w-full bg-red-50 hover:bg-red-500 text-red-600 hover:text-white font-black py-3 px-6 rounded-xl transition-all uppercase tracking-widest text-[10px] border border-red-100">
                                            Return for Revision
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>