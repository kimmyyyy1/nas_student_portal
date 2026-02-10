<div class="py-6 md:py-10 bg-slate-50 min-h-screen" wire:poll.5s> {{-- ⚡ AUTO-REFRESH --}}
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Alerts --}}
        @if (session()->has('message'))
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-r-lg shadow-sm flex items-center animate-fade-in-down">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium text-sm md:text-base">{{ session('message') }}</span>
            </div>
        @endif

        {{-- Update Notification --}}
        @if(str_contains($application->status, 'Resubmitted') || str_contains($application->status, 'Pending'))
             <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded-r-lg shadow-sm flex items-center animate-pulse">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-bold text-sm md:text-base">Update: Applicant has resubmitted documents! Please review below.</span>
            </div>
        @endif

        {{-- MAIN GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            {{-- LEFT CONTENT AREA (Personal Info + Documents) --}}
            <div id="print-area" class="lg:col-span-3 bg-white shadow-xl shadow-indigo-100/50 rounded-3xl overflow-hidden border border-slate-200 relative order-2 lg:order-1">
                
                <div class="h-2 bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500 w-full"></div>

                <div class="p-6 md:p-10 space-y-10 md:space-y-12">
                    
                    {{-- HEADER --}}
                    <div class="flex flex-col md:flex-row items-center justify-between pb-6 border-b-2 border-slate-100 gap-4 md:gap-6 text-center md:text-left">
                        <img src="{{ asset('images/nas/nas-logo-sidebar.png') }}" alt="NAS Logo" class="h-16 md:h-20 w-auto object-contain drop-shadow-lg">
                        
                        {{-- Center Text --}}
                        <div class="flex-1 flex flex-col items-center justify-center text-center">
                            <h1 class="text-lg md:text-xl font-black text-slate-800 uppercase leading-tight tracking-tight">
                                NAS Annual Search for Competent, Exceptional, Notable, and Talented Student-Athlete Scholars
                            </h1>
                            <div class="flex flex-col items-center justify-center">
                                <h2 class="text-[10px] md:text-xs font-bold text-blue-600 uppercase tracking-[0.2em] mt-2">
                                    (NASCENT SAS)
                                </h2>
                                <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-wider">New Clark City, Capas, Tarlac</p>
                            </div>
                        </div>

                        <img src="{{ asset('images/nas/NASCENT SAS.png') }}" alt="NASCENT SAS Logo" class="h-16 md:h-20 w-auto object-contain drop-shadow-lg hidden md:block">
                    </div>

                    {{-- I. PERSONAL INFORMATION --}}
                    <section>
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center text-white font-black mr-3 md:mr-4 shadow-lg shadow-blue-200 text-base md:text-lg">1</div>
                            <div>
                                <h3 class="text-base md:text-lg font-black text-slate-800 uppercase tracking-tighter">Personal Information</h3>
                                <p class="text-[10px] md:text-xs text-slate-400 font-bold uppercase tracking-wide">Applicant Details</p>
                            </div>
                        </div>

                        <div class="bg-slate-50/50 rounded-3xl p-4 md:p-6 border border-slate-100 shadow-inner">
                            
                            {{-- ROW 1: PHOTO & NAME --}}
                            <div class="flex flex-col md:flex-row gap-6 md:gap-8 mb-8 pb-2">
                                
                                {{-- PHOTO SECTION --}}
                                <div class="w-full md:w-56 flex-shrink-0 flex flex-col items-center justify-center">
                                    <div class="w-40 h-40 md:w-48 md:h-48 bg-white rounded-[1.5rem] md:rounded-[2rem] overflow-hidden border-[4px] md:border-[5px] border-white shadow-xl flex items-center justify-center">
                                        @php
                                            $rawFiles = $application->uploaded_files;
                                            if (is_string($rawFiles)) { $files = json_decode($rawFiles, true); } else { $files = $rawFiles; }
                                            $files = is_array($files) ? $files : [];
                                            $photoUrl = $files['id_picture'] ?? null;
                                        @endphp

                                        @if(!empty($photoUrl))
                                            <img src="{{ (str_starts_with($photoUrl, 'http')) ? $photoUrl : asset($photoUrl) }}" 
                                                 class="w-full h-full object-cover" 
                                                 alt="Applicant Photo">
                                        @else
                                            <div class="flex flex-col items-center justify-center h-full text-slate-300 bg-slate-50 w-full">
                                                <svg class="w-12 h-12 md:w-16 md:h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <span class="text-[10px] md:text-xs font-black uppercase tracking-widest">No Photo</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mt-4 md:mt-5 text-center">
                                        <span class="inline-block bg-white border border-slate-200 text-slate-600 text-[10px] md:text-[11px] font-black px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">
                                            LRN: <span class="text-blue-600">{{ $application->lrn }}</span>
                                        </span>
                                    </div>
                                </div>

                                {{-- NAME & VITAL STATS --}}
                                <div class="flex-1 flex flex-col justify-center">
                                    
                                    {{-- FULL NAME --}}
                                    <div class="mb-4 md:mb-6 bg-white p-4 md:p-5 rounded-2xl border border-slate-200 shadow-sm">
                                        <label class="block text-[9px] md:text-[10px] font-black text-slate-400 uppercase mb-1 tracking-wider">Full Name (Last, First, Middle)</label>
                                        
                                        {{-- N/A REPLACEMENT --}}
                                        @php
                                            $mname = $application->middle_name;
                                            if(is_null($mname) || in_array(strtoupper(trim($mname)), ['N/A', 'NA', 'NONE', ''])) {
                                                $mname = '-';
                                            }
                                        @endphp

                                        <p class="text-xl md:text-3xl font-black text-slate-800 uppercase leading-tight tracking-tight break-words">
                                            {{ $application->last_name }}, {{ $application->first_name }} {{ $mname }}
                                        </p>
                                    </div>
                                    
                                    {{-- STATS GRID --}}
                                    <div class="grid grid-cols-3 gap-2 md:gap-4">
                                        <div class="bg-white p-2 md:p-4 rounded-xl md:rounded-2xl border border-slate-200 text-center shadow-sm flex flex-col justify-center min-h-[4rem] md:min-h-[6rem]">
                                            <label class="block text-[8px] md:text-[10px] font-black text-slate-400 uppercase mb-0.5 md:mb-1">Sex</label>
                                            <p class="font-bold text-slate-700 text-sm md:text-xl">{{ $application->gender }}</p>
                                        </div>
                                        <div class="bg-white p-2 md:p-4 rounded-xl md:rounded-2xl border border-slate-200 text-center shadow-sm flex flex-col justify-center min-h-[4rem] md:min-h-[6rem]">
                                            <label class="block text-[8px] md:text-[10px] font-black text-slate-400 uppercase mb-0.5 md:mb-1">Age</label>
                                            <p class="font-bold text-slate-700 text-sm md:text-xl">{{ $application->age }}</p>
                                        </div>
                                        <div class="bg-white p-2 md:p-4 rounded-xl md:rounded-2xl border border-slate-200 text-center shadow-sm flex flex-col justify-center min-h-[4rem] md:min-h-[6rem]">
                                            <label class="block text-[8px] md:text-[10px] font-black text-slate-400 uppercase mb-0.5 md:mb-1">Birthdate</label>
                                            <p class="font-bold text-slate-700 text-xs md:text-lg break-words leading-tight">{{ \Carbon\Carbon::parse($application->date_of_birth)->format('M d, Y') }}</p>
                                        </div>
                                    </div>

                                    {{-- Group Tags --}}
                                    <div class="flex flex-wrap gap-2 mt-4 md:mt-6">
                                        @if($application->is_ip == 'Yes')
                                            <span class="px-2 py-1 md:px-3 bg-amber-100 text-amber-800 text-[9px] md:text-[10px] font-black rounded-full uppercase border border-amber-200 shadow-sm">IP: {{ $application->ip_group_name }}</span>
                                        @endif
                                        @if($application->is_pwd == 'Yes')
                                            <span class="px-2 py-1 md:px-3 bg-purple-100 text-purple-800 text-[9px] md:text-[10px] font-black rounded-full uppercase border border-purple-200 shadow-sm">PWD: {{ $application->pwd_disability }}</span>
                                        @endif
                                        @if($application->is_4ps == 'Yes')
                                            <span class="px-2 py-1 md:px-3 bg-rose-100 text-rose-800 text-[9px] md:text-[10px] font-black rounded-full uppercase border border-rose-200 shadow-sm">4Ps Beneficiary</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- ROW 2: ADDRESS --}}
                            <div class="mb-8 pt-4 border-t border-slate-200">
                                <h4 class="text-[10px] md:text-xs font-black text-blue-900 uppercase tracking-widest mb-4 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Complete Address
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                                    {{-- BOX 1 --}}
                                    <div class="bg-white p-3 md:p-4 rounded-xl border border-slate-200 shadow-sm">
                                        <div class="mb-2 md:mb-3">
                                            <label class="block text-[8px] md:text-[9px] font-black text-slate-400 uppercase mb-1">Street / House No.</label>
                                            <p class="font-bold text-slate-700 text-sm break-words">{{ $application->street_address }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-[8px] md:text-[9px] font-black text-slate-400 uppercase mb-1">Barangay</label>
                                            <p class="font-bold text-slate-700 text-sm">{{ $application->barangay }}</p>
                                        </div>
                                    </div>

                                    {{-- BOX 2 --}}
                                    <div class="bg-white p-3 md:p-4 rounded-xl border border-slate-200 shadow-sm">
                                        <div class="mb-2 md:mb-3">
                                            <label class="block text-[8px] md:text-[9px] font-black text-slate-400 uppercase mb-1">Municipality/City</label>
                                            <p class="font-bold text-slate-700 text-sm">{{ $application->municipality_city }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-[8px] md:text-[9px] font-black text-slate-400 uppercase mb-1">Province & Region</label>
                                            <p class="font-bold text-slate-700 text-sm">{{ $application->province }}</p>
                                            <p class="text-[9px] md:text-[10px] text-slate-400 uppercase mt-0.5">{{ $application->region }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ROW 3: CONTACT INFO --}}
                            @php
                                $email = $application->email ?? $application->user->email;
                                $contact = $application->guardian_contact;
                                
                                if(!$email || in_array(strtoupper(trim($email)), ['N/A', 'NA'])) $email = '-';
                                if(!$contact || in_array(strtoupper(trim($contact)), ['N/A', 'NA'])) $contact = '-';
                            @endphp

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                                <div class="bg-blue-50 p-3 md:p-4 rounded-xl border border-blue-100 flex items-center">
                                    <div class="bg-blue-200 p-2 rounded-lg mr-3 text-blue-700 flex-shrink-0">
                                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div class="overflow-hidden w-full">
                                        <label class="block text-[8px] md:text-[9px] font-black text-blue-400 uppercase mb-0.5">Email Address</label>
                                        <p class="font-bold text-blue-900 text-xs md:text-sm break-all">{{ $email }}</p>
                                    </div>
                                </div>

                                <div class="bg-emerald-50 p-3 md:p-4 rounded-xl border border-emerald-100 flex items-center">
                                    <div class="bg-emerald-200 p-2 rounded-lg mr-3 text-emerald-700 flex-shrink-0">
                                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    </div>
                                    <div>
                                        <label class="block text-[8px] md:text-[9px] font-black text-emerald-400 uppercase mb-0.5">Contact Number</label>
                                        <p class="font-bold text-emerald-900 text-xs md:text-sm font-mono tracking-wide">{{ $contact }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </section>

                    {{-- II. SPORTS & SCHOOL PROFILE --}}
                    <section>
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center text-white font-black mr-3 md:mr-4 shadow-lg shadow-blue-200 text-base md:text-lg">2</div>
                            <div>
                                <h3 class="text-base md:text-lg font-black text-slate-800 uppercase tracking-tighter">Sports & School Profile</h3>
                                <p class="text-[10px] md:text-xs text-slate-400 font-bold uppercase tracking-wide">Athletic Background</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
                            {{-- Focus Sport Box --}}
                            <div class="bg-white p-4 md:p-6 rounded-2xl border-l-4 border-blue-600 shadow-md">
                                <label class="block text-[9px] md:text-[10px] font-black text-blue-400 uppercase mb-2 tracking-wider">Focus Sport</label>
                                <div class="flex items-center flex-wrap gap-2">
                                    <p class="text-xl md:text-2xl font-black text-slate-800 uppercase tracking-tight">{{ $application->sport }}</p>
                                    @if($application->sport_specification)
                                        <span class="text-xs md:text-sm font-bold text-white bg-blue-600 px-3 py-1 rounded-full uppercase shadow-sm">{{ $application->sport_specification }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- School Type Box --}}
                            <div class="bg-white p-4 md:p-6 rounded-2xl border-l-4 border-indigo-600 shadow-md">
                                <label class="block text-[9px] md:text-[10px] font-black text-indigo-400 uppercase mb-2 tracking-wider">Current School Type</label>
                                <p class="text-xl md:text-2xl font-black text-slate-800 uppercase tracking-tight">{{ $application->school_type }}</p>
                            </div>

                            {{-- Palaro Box --}}
                            <div class="p-4 md:p-6 rounded-2xl border shadow-sm transition-colors {{ $application->palaro_finisher == 'Yes' ? 'bg-yellow-50 border-yellow-400' : 'bg-slate-50 border-slate-200 opacity-75' }}">
                                <span class="block text-[9px] md:text-[10px] font-black uppercase tracking-wider {{ $application->palaro_finisher == 'Yes' ? 'text-yellow-700' : 'text-slate-400' }}">Palarong Pambansa Finisher</span>
                                <span class="text-xl md:text-2xl font-black uppercase mt-1 block {{ $application->palaro_finisher == 'Yes' ? 'text-yellow-600' : 'text-slate-400' }}">{{ $application->palaro_finisher }}</span>
                            </div>

                            {{-- Batang Pinoy Box --}}
                            <div class="p-4 md:p-6 rounded-2xl border shadow-sm transition-colors {{ $application->batang_pinoy_finisher == 'Yes' ? 'bg-yellow-50 border-yellow-400' : 'bg-slate-50 border-slate-200 opacity-75' }}">
                                <span class="block text-[9px] md:text-[10px] font-black uppercase tracking-wider {{ $application->batang_pinoy_finisher == 'Yes' ? 'text-yellow-700' : 'text-slate-400' }}">Batang Pinoy Finisher</span>
                                <span class="text-xl md:text-2xl font-black uppercase mt-1 block {{ $application->batang_pinoy_finisher == 'Yes' ? 'text-yellow-600' : 'text-slate-400' }}">{{ $application->batang_pinoy_finisher }}</span>
                            </div>
                        </div>
                    </section>

                    {{-- III. GUARDIAN INFORMATION --}}
                    <section>
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center text-white font-black mr-3 md:mr-4 shadow-lg shadow-blue-200 text-base md:text-lg">3</div>
                            <div>
                                <h3 class="text-base md:text-lg font-black text-slate-800 uppercase tracking-tighter">Guardian Information</h3>
                                <p class="text-[10px] md:text-xs text-slate-400 font-bold uppercase tracking-wide">Emergency Contact</p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100 shadow-sm relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-24 h-24 bg-slate-50 rounded-bl-full -mr-4 -mt-4 z-0"></div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 md:gap-y-8 relative z-10">
                                <div>
                                    <label class="block text-[9px] md:text-[10px] font-black text-slate-400 uppercase mb-1 tracking-wider">Guardian Name</label>
                                    <p class="font-bold text-slate-800 text-base md:text-lg">{{ $application->guardian_name }}</p>
                                </div>
                                <div>
                                    <label class="block text-[9px] md:text-[10px] font-black text-slate-400 uppercase mb-1 tracking-wider">Relationship</label>
                                    <p class="font-bold text-slate-800 text-base md:text-lg">{{ $application->guardian_relationship }}</p>
                                </div>
                                
                                {{-- ⚡ GUARDIAN CONTACT LOGIC ⚡ --}}
                                @php
                                    $gContact = $application->guardian_contact;
                                    $gEmail = $application->guardian_email;

                                    if(!$gContact || in_array(strtoupper(trim($gContact)), ['N/A', 'NA'])) $gContact = '-';
                                    if(!$gEmail || in_array(strtoupper(trim($gEmail)), ['N/A', 'NA'])) $gEmail = '-';
                                @endphp

                                <div>
                                    <label class="block text-[9px] md:text-[10px] font-black text-slate-400 uppercase mb-1 tracking-wider">Contact Number</label>
                                    <p class="font-bold text-slate-800 font-mono text-base md:text-lg tracking-wide">{{ $gContact }}</p>
                                </div>
                                <div>
                                    <label class="block text-[9px] md:text-[10px] font-black text-slate-400 uppercase mb-1 tracking-wider">Email Address</label>
                                    <p class="font-bold text-blue-600 text-sm md:text-base break-all">{{ $gEmail }}</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- IV. DOCUMENTS (WITH FORM WRAPPER FIX) --}}
                    <section>
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-black mr-3 md:mr-4 shadow-lg shadow-indigo-200 text-base md:text-lg">4</div>
                            <div>
                                <h3 class="text-base md:text-lg font-black text-slate-800 uppercase tracking-tighter">Submitted Documents (Phase 2)</h3>
                                <p class="text-[10px] md:text-xs text-slate-400 font-bold uppercase tracking-wide">Review & Verification</p>
                            </div>
                        </div>
                        
                        {{-- ⚡ FORM WRAPPER FOR SAVING REMARKS ⚡ --}}
                        <form action="{{ route('admission.process', $application->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            {{-- Preserve current status --}}
                            <input type="hidden" name="status" value="{{ $application->status }}">

                            {{-- FIXED TABLE WRAPPER (No Scroll - Tight on Mobile) --}}
                            <div class="rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                                <table class="w-full text-left border-collapse bg-white text-sm table-fixed">
                                    <thead class="bg-slate-50 text-[8px] md:text-[10px] uppercase text-slate-500 font-black border-b border-slate-200 tracking-wider">
                                        <tr>
                                            <th class="px-2 py-3 md:px-6 md:py-5 w-[30%] md:w-[25%]">Document Name</th>
                                            <th class="px-1 py-3 md:px-2 md:py-5 text-center w-[15%] md:w-[12%]">Status</th>
                                            <th class="px-1 py-3 md:px-2 md:py-5 text-center w-[10%]">File</th>
                                            <th class="px-1 py-3 md:px-2 md:py-5 text-center no-print w-[15%] md:w-[18%]">Action</th>
                                            <th class="px-2 py-3 md:px-6 md:py-5 w-[30%] md:w-[35%] no-print">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @php
                                            // Doc Logic
                                            $rawFiles = $application->uploaded_files;
                                            if (is_string($rawFiles)) { $files = json_decode($rawFiles, true); } else { $files = $rawFiles; }
                                            $files = is_array($files) ? $files : [];

                                            $remarks = is_string($application->document_remarks) ? json_decode($application->document_remarks, true) : ($application->document_remarks ?? []);
                                            $statuses = is_string($application->document_statuses) ? json_decode($application->document_statuses, true) : ($application->document_statuses ?? []);

                                            $docs = [
                                                'scholarship_form' => 'Scholarship Application Form',
                                                'student_profile' => 'Student-Athlete’s Profile Form',
                                                'medical_clearance' => 'Preparticipation Physical Evaluation Clearance Form', 
                                                'psa_birth_cert' => 'PSA Birth Certificate',
                                                'report_card' => 'Grade 5/6 or 6/7 Report Card',
                                                'guardian_id' => 'Designated Guardian’s Valid Gov’t ID with Signature',
                                                'kukkiwon_cert' => 'Kukkiwon Certificate',
                                                'ip_cert' => 'IP Certification',
                                                'pwd_id' => 'PWD ID',
                                                '4ps_id' => '4Ps ID or Certification'
                                            ];
                                        @endphp

                                        @foreach($docs as $key => $label)
                                            @php
                                                $isUploaded = isset($files[$key]) && !empty($files[$key]);
                                                if(in_array($key, ['kukkiwon_cert', 'ip_cert', 'pwd_id', '4ps_id']) && !$isUploaded) continue;

                                                $status = $statuses[$key] ?? 'pending';
                                                $badgeClass = match($status) {
                                                    'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                    'declined' => 'bg-red-100 text-red-700 border-red-200',
                                                    default => 'bg-amber-100 text-amber-700 border-amber-200',
                                                };
                                            @endphp
                                            <tr class="hover:bg-slate-50 transition group align-top">
                                                <td class="px-2 py-3 md:px-6 md:py-5 font-bold text-slate-700 leading-snug break-words text-[10px] md:text-sm">{{ $label }}</td>
                                                <td class="px-1 py-3 md:px-2 md:py-5 text-center align-middle">
                                                    @if($isUploaded)
                                                        <span class="inline-flex px-1.5 py-0.5 md:px-3 md:py-1 text-[8px] md:text-[9px] font-black uppercase rounded-full border shadow-sm {{ $badgeClass }}">{{ $status }}</span>
                                                    @else
                                                        <span class="text-slate-300 text-[8px] md:text-[10px] italic font-bold">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="px-1 py-3 md:px-2 md:py-5 text-center align-middle">
                                                    @if($isUploaded)
                                                        <a href="{{ $files[$key] }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-black text-[8px] md:text-[10px] flex flex-col items-center justify-center gap-0.5 md:gap-1 group/link">
                                                            <div class="p-1 md:p-2 bg-indigo-50 rounded-lg group-hover/link:bg-indigo-100 transition">
                                                                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                            </div>
                                                            VIEW
                                                        </a>
                                                    @else - @endif
                                                </td>
                                                
                                                {{-- FIXED ACTION BUTTONS --}}
                                                <td class="px-1 py-3 md:px-2 md:py-5 text-center no-print align-middle">
                                                    @if($isUploaded)
                                                        <div class="flex flex-row justify-center gap-1 md:gap-3">
                                                            <a href="{{ route('admission.approve_document', ['id' => $application->id, 'doc_key' => $key]) }}" class="p-1.5 md:p-2.5 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white transition shadow-sm border border-emerald-200" title="Approve">
                                                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                            </a>
                                                            <a href="{{ route('admission.decline_document', ['id' => $application->id, 'doc_key' => $key]) }}" class="p-1.5 md:p-2.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition shadow-sm border border-red-200" title="Decline">
                                                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                            </a>
                                                        </div>
                                                    @endif
                                                </td>

                                                <td class="px-2 py-3 md:px-6 md:py-4 no-print align-middle">
                                                    <textarea name="document_remarks[{{ $key }}]" rows="2" class="w-full text-[10px] md:text-xs border-slate-200 bg-slate-50 focus:bg-white rounded-xl focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition placeholder-slate-400 p-2 md:p-3 leading-relaxed resize-none" placeholder="Type remarks...">{{ $remarks[$key] ?? '' }}</textarea>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-6 text-right no-print">
                                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-3 px-8 rounded-xl text-xs uppercase tracking-widest shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition">
                                    Save Remarks Only
                                </button>
                            </div>
                        </form> {{-- ⚡ END FORM WRAPPER ⚡ --}}
                    </section>
                </div>
            </div>

            {{-- SIDEBAR ACTIONS --}}
            <div class="lg:col-span-1 no-print order-1 lg:order-2">
                <div class="bg-white shadow-xl shadow-indigo-100 rounded-3xl border border-slate-200 p-6 sticky top-6">
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-4 mb-6">Application Decision</h3>
                    
                    <form action="{{ route('admission.process', $application->id) }}" method="POST">
                        @csrf @method('PATCH')
                        
                        @if(isset($remarks) && is_array($remarks))
                            @foreach($remarks as $k => $v) <input type="hidden" name="document_remarks[{{ $k }}]" value="{{ $v }}"> @endforeach
                        @endif

                        <div class="mb-6">
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-wider">Set Status</label>
                            
                            {{-- ⚡ FIXED SIDEBAR DROPDOWN (NO CUSTOM SVG - REMOVED appearance-none) ⚡ --}}
                            <div>
                                <select name="status" id="status" class="w-full border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm py-3 px-3 bg-slate-50 cursor-pointer">
                                    <optgroup label="Phase 1: Registration">
                                        <option value="Submitted for 1st Level Assessment" {{ $application->status == 'Submitted for 1st Level Assessment' ? 'selected' : '' }}>For 1st Level Assessment</option>
                                        <option value="For 2nd Level Assessment" {{ $application->status == 'For 2nd Level Assessment' ? 'selected' : '' }}>Passed 1st Level (Move to Phase 2)</option>
                                    </optgroup>
                                    <optgroup label="Phase 2: Documents">
                                        <option value="Requirements Submitted & For Review" {{ $application->status == 'Requirements Submitted & For Review' ? 'selected' : '' }}>Requirements Submitted</option>
                                        <option value="Requirements Returned for Re-upload" class="text-red-600 font-bold" {{ $application->status == 'Requirements Returned for Re-upload' ? 'selected' : '' }}>RETURN for Re-upload</option>
                                        <option value="Qualified" {{ $application->status == 'Qualified' ? 'selected' : '' }}>Qualified (Final)</option>
                                        <option value="Waitlisted" {{ $application->status == 'Waitlisted' ? 'selected' : '' }}>Waitlisted</option>
                                    </optgroup>
                                    <optgroup label="Declined">
                                        <option value="Not Qualified" {{ in_array($application->status, ['Not Qualified', 'Rejected', 'Failed']) ? 'selected' : '' }}>Not Qualified</option>
                                    </optgroup>
                                </select>
                            </div>
                            {{-- ⚡ END FIXED SIDEBAR DROPDOWN ⚡ --}}

                        </div>

                        <div class="mb-6 hidden" id="rejection-div">
                            <label class="block text-[10px] font-black text-red-400 uppercase mb-2 tracking-wider">Reason for Rejection</label>
                            <textarea name="rejection_reason" rows="3" class="w-full border-red-200 bg-red-50 rounded-xl text-sm text-red-700 focus:border-red-500 focus:ring-red-500 p-3 placeholder-red-300" placeholder="State reason...">{{ $application->rejection_reason }}</textarea>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-black py-4 rounded-xl shadow-lg shadow-indigo-200 hover:shadow-indigo-300 transition transform hover:-translate-y-0.5 text-xs uppercase tracking-[0.2em] flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Update Status
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>