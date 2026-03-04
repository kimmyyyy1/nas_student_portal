<div class="py-6 lg:py-10 min-h-screen w-full block" wire:poll.2s>
    
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">



        {{-- Update Notification (Glass Effect) --}}
        @if(str_contains($application->status, 'Resubmitted') || str_contains($application->status, 'Pending'))
             <div class="mb-6 w-full bg-blue-100/80 backdrop-blur-md border-l-4 border-blue-500 text-blue-800 p-4 rounded-r-lg shadow-lg flex items-center animate-pulse">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-bold text-sm lg:text-base">Update: Applicant has resubmitted documents! Please review below.</span>
            </div>
        @endif

        {{-- MAIN GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 w-full">

            {{-- LEFT CONTENT AREA (GLASSMOPHISM: Transparent White Background) --}}
            {{-- ⚡ GINAWANG bg-white/10 para kitang-kita ang background image ⚡ --}}
            <div id="print-area" class="w-full lg:col-span-3 bg-white/10 backdrop-blur-md shadow-2xl shadow-black/5 rounded-3xl overflow-hidden border border-white/30 relative text-slate-800">
                
                <div class="h-2 bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500 w-full opacity-80"></div>

                <div class="p-5 sm:p-8 lg:p-10 space-y-8 lg:space-y-12 w-full">
                    
                    {{-- HEADER --}}
                    <div class="flex flex-col md:flex-row items-center justify-between pb-6 border-b-2 border-slate-500/20 gap-4 lg:gap-6 w-full">
                        <div class="w-full md:w-32 lg:w-40 flex justify-center md:justify-start flex-shrink-0">
                            <img src="{{ asset('images/nas/nas-logo-sidebar.png') }}" alt="NAS Logo" class="h-16 sm:h-20 lg:h-28 w-auto object-contain drop-shadow-md">
                        </div>
                        <div class="flex-1 w-full flex flex-col items-center justify-center text-center px-1 sm:px-4">
                            <h1 class="text-xs sm:text-sm lg:text-[16px] font-black text-slate-900 uppercase leading-tight tracking-tight max-w-3xl mx-auto drop-shadow-sm">
                                NAS Annual Search for Competent, Exceptional, Notable, and Talented Student-Athlete Scholars
                            </h1>
                            <div class="flex flex-col items-center justify-center w-full mt-1.5 lg:mt-2">
                                <h2 class="text-[9px] sm:text-[10px] lg:text-xs font-bold text-blue-700 uppercase tracking-[0.2em] drop-shadow-sm">
                                    (NASCENT SAS)
                                </h2>
                                <p class="text-[8px] sm:text-[9px] font-bold text-slate-600 mt-1 uppercase tracking-wider">New Clark City, Capas, Tarlac</p>
                            </div>
                        </div>
                        <div class="w-full md:w-32 lg:w-40 flex justify-center md:justify-end flex-shrink-0 hidden md:flex">
                            <img src="{{ asset('images/nas/NASCENT SAS.png') }}" alt="NASCENT SAS Logo" class="h-14 sm:h-16 lg:h-20 w-auto object-contain drop-shadow-md">
                        </div>
                    </div>

                    {{-- I. PERSONAL INFORMATION --}}
                    <section class="w-full">
                        <div class="flex items-center mb-5 lg:mb-6 w-full">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center text-white font-black mr-3 sm:mr-4 shadow-lg text-sm sm:text-lg flex-shrink-0 border border-white/20">1</div>
                            <div class="flex-1">
                                <h3 class="text-base sm:text-lg font-black text-slate-900 uppercase tracking-tighter leading-tight drop-shadow-sm">Personal Information</h3>
                                <p class="text-[10px] sm:text-xs text-slate-600 font-bold uppercase tracking-wide">Applicant Details</p>
                            </div>
                        </div>

                        {{-- Inner Box made transparent (bg-white/20) --}}
                        <div class="bg-white/40 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-5 sm:p-6 border border-white/30 shadow-inner w-full">
                            
                            {{-- ROW 1: PHOTO & NAME --}}
                            <div class="flex flex-col md:flex-row gap-6 lg:gap-8 mb-6 sm:mb-8 pb-2 w-full">
                                <div class="w-full md:w-48 lg:w-56 flex-shrink-0 flex flex-col items-center justify-center">
                                    <div class="w-32 h-32 sm:w-40 sm:h-40 lg:w-48 lg:h-48 bg-white/80 rounded-2xl lg:rounded-[2rem] overflow-hidden border-[4px] lg:border-[5px] border-white/50 shadow-xl flex items-center justify-center">
                                        @php
                                            $rawFiles = $application->uploaded_files;
                                            if (is_string($rawFiles)) { $files = json_decode($rawFiles, true); } else { $files = $rawFiles; }
                                            $files = is_array($files) ? $files : [];
                                            $photoUrl = $files['id_picture'] ?? null;
                                        @endphp
                                        @if(!empty($photoUrl))
                                            <img src="{{ $photoUrl }}" class="w-full h-full object-cover" alt="Applicant Photo">
                                        @else
                                            <div class="flex flex-col items-center justify-center h-full text-slate-400 w-full">
                                                <svg class="w-10 h-10 lg:w-16 lg:h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <span class="text-[9px] lg:text-xs font-black uppercase tracking-widest">No Photo</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mt-4 text-center">
                                        <span class="inline-block bg-white/80 backdrop-blur border border-white/40 text-slate-800 text-[10px] sm:text-[11px] font-black px-4 py-1.5 rounded-full uppercase tracking-wider shadow-sm">
                                            LRN: <span class="text-blue-700">{{ $application->lrn }}</span>
                                        </span>
                                    </div>
                                </div>

                                <div class="flex-1 flex flex-col justify-center w-full">
                                    <div class="mb-5 sm:mb-6 bg-white/60 p-4 sm:p-5 rounded-xl sm:rounded-2xl border border-white/40 shadow-sm text-center md:text-left w-full">
                                        <label class="block text-[9px] sm:text-[10px] font-black text-slate-500 uppercase mb-1.5 tracking-wider">Full Name (Last, First, Middle)</label>
                                        @php
                                            $mname = $application->middle_name;
                                            if(is_null($mname) || in_array(strtoupper(trim($mname)), ['N/A', 'NA', 'NONE', ''])) { $mname = '-'; }
                                        @endphp
                                        <p class="text-xl sm:text-2xl lg:text-3xl font-black text-slate-900 uppercase leading-tight tracking-tight break-words">
                                            {{ $application->last_name }}, {{ $application->first_name }} {{ $mname }}
                                        </p>
                                    </div>
                                    
                                    <div class="grid grid-cols-3 gap-2 sm:gap-4 w-full">
                                        <div class="bg-white/60 p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-white/40 text-center shadow-sm flex flex-col justify-center min-h-[4rem] sm:min-h-[5rem] lg:min-h-[6rem]">
                                            <label class="block text-[8px] sm:text-[10px] font-black text-slate-500 uppercase mb-1">Sex</label>
                                            <p class="font-bold text-slate-800 text-sm sm:text-base lg:text-xl">{{ $application->gender }}</p>
                                        </div>
                                        <div class="bg-white/60 p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-white/40 text-center shadow-sm flex flex-col justify-center min-h-[4rem] sm:min-h-[5rem] lg:min-h-[6rem]">
                                            <label class="block text-[8px] sm:text-[10px] font-black text-slate-500 uppercase mb-1">Age</label>
                                            <p class="font-bold text-slate-800 text-sm sm:text-base lg:text-xl">{{ $application->age }}</p>
                                        </div>
                                        <div class="bg-white/60 p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-white/40 text-center shadow-sm flex flex-col justify-center min-h-[4rem] sm:min-h-[5rem] lg:min-h-[6rem]">
                                            <label class="block text-[8px] sm:text-[10px] font-black text-slate-500 uppercase mb-1">Birthdate</label>
                                            <p class="font-bold text-slate-800 text-[10px] sm:text-sm lg:text-lg break-words leading-tight">{{ \Carbon\Carbon::parse($application->date_of_birth)->format('M d, Y') }}</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap justify-center md:justify-start gap-2 mt-5 sm:mt-6 w-full">
                                        @if($application->is_ip == 'Yes') <span class="px-2.5 py-1 sm:px-3 bg-amber-100/90 text-amber-900 text-[9px] sm:text-[10px] font-black rounded-full uppercase border border-amber-200 shadow-sm">IP: {{ $application->ip_group_name }}</span> @endif
                                        @if($application->is_pwd == 'Yes') <span class="px-2.5 py-1 sm:px-3 bg-purple-100/90 text-purple-900 text-[9px] sm:text-[10px] font-black rounded-full uppercase border border-purple-200 shadow-sm">PWD: {{ $application->pwd_disability }}</span> @endif
                                        @if($application->is_4ps == 'Yes') <span class="px-2.5 py-1 sm:px-3 bg-rose-100/90 text-rose-900 text-[9px] sm:text-[10px] font-black rounded-full uppercase border border-rose-200 shadow-sm">4Ps Beneficiary</span> @endif
                                    </div>
                                </div>
                            </div>

                            {{-- ROW 2: ADDRESS --}}
                            <div class="mb-6 sm:mb-8 pt-5 border-t border-slate-500/20 w-full">
                                <h4 class="text-[10px] sm:text-xs font-black text-blue-800 uppercase tracking-widest mb-3 sm:mb-4 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Complete Address
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 w-full">
                                    <div class="bg-white/60 p-4 sm:p-5 rounded-xl border border-white/40 shadow-sm">
                                        <div class="mb-3 sm:mb-4">
                                            <label class="block text-[8px] sm:text-[9px] font-black text-slate-500 uppercase mb-1">Street / House No.</label>
                                            <p class="font-bold text-slate-800 text-xs sm:text-sm break-words">{{ $application->street_address }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-[8px] sm:text-[9px] font-black text-slate-500 uppercase mb-1">Barangay</label>
                                            <p class="font-bold text-slate-800 text-xs sm:text-sm">{{ $application->barangay }}</p>
                                        </div>
                                    </div>
                                    <div class="bg-white/60 p-4 sm:p-5 rounded-xl border border-white/40 shadow-sm">
                                        <div class="mb-3 sm:mb-4">
                                            <label class="block text-[8px] sm:text-[9px] font-black text-slate-500 uppercase mb-1">Municipality/City</label>
                                            <p class="font-bold text-slate-800 text-xs sm:text-sm">{{ $application->municipality_city }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-[8px] sm:text-[9px] font-black text-slate-500 uppercase mb-1">Province & Region</label>
                                            <p class="font-bold text-slate-800 text-xs sm:text-sm">{{ $application->province }}</p>
                                            <p class="text-[9px] sm:text-[10px] text-slate-500 uppercase mt-0.5">{{ $application->region }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ROW 3: CONTACT INFO --}}
                            @php
                                $email = $application->email ?? $application->user->email;
                                if(!$email || in_array(strtoupper(trim($email)), ['N/A', 'NA'])) $email = '-';
                            @endphp

                            <div class="w-full">
                                <div class="bg-blue-50/80 p-3 sm:p-4 rounded-xl border border-blue-200/50 flex items-center w-full">
                                    <div class="bg-blue-200/50 p-2 rounded-lg mr-3 text-blue-800 flex-shrink-0">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div class="overflow-hidden w-full">
                                        <label class="block text-[8px] sm:text-[9px] font-black text-blue-500 uppercase mb-0.5">Email Address</label>
                                        <p class="font-bold text-blue-900 text-xs sm:text-sm break-words">{{ $email }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </section>

                    {{-- II. SPORTS & SCHOOL PROFILE --}}
                    <section class="w-full">
                        <div class="flex items-center mb-5 lg:mb-6 w-full">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center text-white font-black mr-3 sm:mr-4 shadow-lg text-sm sm:text-lg flex-shrink-0 border border-white/20">2</div>
                            <div class="flex-1">
                                <h3 class="text-base sm:text-lg font-black text-slate-900 uppercase tracking-tighter leading-tight drop-shadow-sm">Sports & School Profile</h3>
                                <p class="text-[10px] sm:text-xs text-slate-600 font-bold uppercase tracking-wide">Athletic Background</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 w-full">
                            <div class="bg-white/60 p-5 sm:p-6 rounded-xl md:rounded-2xl border-l-4 border-blue-600 border-t border-r border-b border-white/40 shadow-md">
                                <label class="block text-[9px] sm:text-[10px] font-black text-blue-500 uppercase mb-2 tracking-wider">Focus Sport</label>
                                <div class="flex items-center flex-wrap gap-2">
                                    <p class="text-xl sm:text-2xl font-black text-slate-800 uppercase tracking-tight">{{ $application->sport }}</p>
                                    @if($application->sport_specification)
                                        <span class="text-[10px] sm:text-sm font-bold text-white bg-blue-600 px-3 py-1 rounded-full uppercase shadow-sm">{{ $application->sport_specification }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="bg-white/60 p-5 sm:p-6 rounded-xl md:rounded-2xl border-l-4 border-indigo-600 border-t border-r border-b border-white/40 shadow-md">
                                <label class="block text-[9px] sm:text-[10px] font-black text-indigo-500 uppercase mb-2 tracking-wider">Current School Type</label>
                                <p class="text-xl sm:text-2xl font-black text-slate-800 uppercase tracking-tight">{{ $application->school_type }}</p>
                            </div>

                            <div class="bg-white/60 p-5 sm:p-6 rounded-xl md:rounded-2xl border-t border-r border-b border-white/40 shadow-md">
                                <label class="block text-[9px] sm:text-[10px] font-black text-slate-500 uppercase mb-2 tracking-wider">Last Grade Level</label>
                                <p class="text-lg sm:text-xl font-black text-slate-800 uppercase tracking-tight">{{ $application->school_last_grade_level ?? 'N/A' }}</p>
                            </div>

                            <div class="bg-white/60 p-5 sm:p-6 rounded-xl md:rounded-2xl border-t border-r border-b border-white/40 shadow-md flex items-center justify-between">
                                <div>
                                    <label class="block text-[9px] sm:text-[10px] font-black text-slate-500 uppercase mb-2 tracking-wider">Year Completed</label>
                                    <p class="text-lg sm:text-xl font-black text-slate-800 uppercase tracking-tight">{{ $application->school_last_year_completed ?? 'N/A' }}</p>
                                </div>
                                @if($application->school_id)
                                    <div class="text-right">
                                        <label class="block text-[8px] sm:text-[9px] font-black text-slate-400 uppercase mb-1 tracking-wider">School ID</label>
                                        <p class="font-mono font-bold text-slate-600 text-sm sm:text-base tracking-widest">{{ $application->school_id }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="p-4 sm:p-6 rounded-xl md:rounded-2xl border shadow-sm transition-colors flex justify-between items-center sm:block {{ $application->palaro_finisher == 'Yes' ? 'bg-yellow-50/80 border-yellow-400' : 'bg-white/40 border-white/40 opacity-75' }}">
                                <span class="block text-[9px] sm:text-[10px] font-black uppercase tracking-wider {{ $application->palaro_finisher == 'Yes' ? 'text-yellow-800' : 'text-slate-500' }}">Palarong Pambansa Finisher</span>
                                <span class="text-base sm:text-xl lg:text-2xl font-black uppercase mt-0 sm:mt-2 block {{ $application->palaro_finisher == 'Yes' ? 'text-yellow-700' : 'text-slate-500' }}">{{ $application->palaro_finisher }}</span>
                            </div>

                            <div class="p-4 sm:p-6 rounded-xl md:rounded-2xl border shadow-sm transition-colors flex justify-between items-center sm:block {{ $application->batang_pinoy_finisher == 'Yes' ? 'bg-yellow-50/80 border-yellow-400' : 'bg-white/40 border-white/40 opacity-75' }}">
                                <span class="block text-[9px] sm:text-[10px] font-black uppercase tracking-wider {{ $application->batang_pinoy_finisher == 'Yes' ? 'text-yellow-800' : 'text-slate-500' }}">Batang Pinoy Finisher</span>
                                <span class="text-base sm:text-xl lg:text-2xl font-black uppercase mt-0 sm:mt-2 block {{ $application->batang_pinoy_finisher == 'Yes' ? 'text-yellow-700' : 'text-slate-500' }}">{{ $application->batang_pinoy_finisher }}</span>
                            </div>
                        </div>
                    </section>

                    {{-- III. GUARDIAN INFORMATION --}}
                    <section class="w-full">
                        <div class="flex items-center mb-5 lg:mb-6 w-full">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center text-white font-black mr-3 sm:mr-4 shadow-lg text-sm sm:text-lg flex-shrink-0 border border-white/20">3</div>
                            <div class="flex-1">
                                <h3 class="text-base sm:text-lg font-black text-slate-900 uppercase tracking-tighter leading-tight drop-shadow-sm">Guardian Information</h3>
                                <p class="text-[10px] sm:text-xs text-slate-600 font-bold uppercase tracking-wide">Emergency Contact</p>
                            </div>
                        </div>

                        <div class="bg-white/40 backdrop-blur-sm rounded-2xl md:rounded-3xl p-5 sm:p-8 border border-white/30 shadow-sm relative overflow-hidden w-full">
                            <div class="absolute top-0 right-0 w-20 h-20 sm:w-24 sm:h-24 bg-white/20 rounded-bl-full -mr-3 -mt-3 sm:-mr-4 sm:-mt-4 z-0"></div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5 sm:gap-y-8 relative z-10 w-full">
                                <div>
                                    <label class="block text-[9px] sm:text-[10px] font-black text-slate-500 uppercase mb-1 tracking-wider">Guardian Name</label>
                                    <p class="font-bold text-slate-800 text-base sm:text-lg">{{ $application->guardian_name }}</p>
                                </div>
                                <div>
                                    <label class="block text-[9px] sm:text-[10px] font-black text-slate-500 uppercase mb-1 tracking-wider">Relationship</label>
                                    <p class="font-bold text-slate-800 text-base sm:text-lg">{{ $application->guardian_relationship }}</p>
                                </div>
                                
                                @php
                                    $gContact = $application->guardian_contact;
                                    $gEmail = $application->guardian_email;
                                    if(!$gContact || in_array(strtoupper(trim($gContact)), ['N/A', 'NA'])) $gContact = '-';
                                    if(!$gEmail || in_array(strtoupper(trim($gEmail)), ['N/A', 'NA'])) $gEmail = '-';
                                @endphp

                                <div>
                                    <label class="block text-[9px] sm:text-[10px] font-black text-slate-500 uppercase mb-1 tracking-wider">Contact Number</label>
                                    <p class="font-bold text-slate-800 font-mono text-base sm:text-lg tracking-wide">{{ $gContact }}</p>
                                </div>
                                <div class="overflow-hidden w-full">
                                    <label class="block text-[9px] sm:text-[10px] font-black text-slate-500 uppercase mb-1 tracking-wider">Email Address</label>
                                    <p class="font-bold text-blue-700 text-sm sm:text-base break-words">{{ $gEmail }}</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- IV. DOCUMENTS --}}
                    <section class="w-full">
                        <div class="flex items-center mb-5 lg:mb-6 w-full">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-black mr-3 sm:mr-4 shadow-lg text-sm sm:text-lg flex-shrink-0 border border-white/20">4</div>
                            <div class="flex-1">
                                <h3 class="text-base sm:text-lg font-black text-slate-900 uppercase tracking-tighter leading-tight drop-shadow-sm">Submitted Documents</h3>
                                <p class="text-[10px] sm:text-xs text-slate-600 font-bold uppercase tracking-wide">Review & Verification</p>
                            </div>
                        </div>
                        
                        <form id="remarks-form" action="{{ route('admission.process', $application->id) }}" method="POST" class="w-full" onsubmit="return handleFormAjax(event, 'Remarks saved successfully!')">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $application->status }}">

                            <div class="rounded-xl lg:rounded-2xl border border-white/30 shadow-sm overflow-hidden w-full overflow-x-auto bg-white/40 backdrop-blur-sm">
                                <table class="min-w-[600px] w-full text-left border-collapse table-fixed">
                                    <thead class="bg-white/30 text-[8px] sm:text-[10px] uppercase text-slate-600 font-black border-b border-white/20 tracking-wider">
                                        <tr>
                                            <th class="px-2 py-3 sm:px-6 sm:py-5 w-[30%] sm:w-[28%] lg:w-[25%] leading-tight">Document Name</th>
                                            <th class="px-1 py-3 sm:px-2 sm:py-5 text-center w-[16%] sm:w-[15%] lg:w-[12%]">Status</th>
                                            <th class="px-1 py-3 sm:px-2 sm:py-5 text-center w-[12%] sm:w-[10%]">File</th>
                                            <th class="px-1 py-3 sm:px-2 sm:py-5 text-center no-print w-[14%] sm:w-[15%] lg:w-[18%]">Action</th>
                                            <th class="px-2 py-3 sm:px-6 sm:py-5 w-[28%] sm:w-[32%] lg:w-[35%] no-print">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/20">
                                        @php
                                            $rawFiles = $application->uploaded_files;
                                            if (is_string($rawFiles)) { $files = json_decode($rawFiles, true); } else { $files = $rawFiles; }
                                            $files = is_array($files) ? $files : [];
                                            $remarks = is_string($application->document_remarks) ? json_decode($application->document_remarks, true) : ($application->document_remarks ?? []);
                                            $statuses = is_string($application->document_statuses) ? json_decode($application->document_statuses, true) : ($application->document_statuses ?? []);
                                            $isRenewal = $application->status === 'Pending Renewal' || ($remarks['is_renewal'] ?? false);
                                            
                                            if ($isRenewal) {
                                                $docs = [
                                                    'renewal_sa_info_form' => '1. Scholarship Application Form',
                                                    'renewal_basic_ed_form' => '2. Student-Athlete\'s Profile Form',
                                                    'renewal_scholarship_agreement' => '3. Preparticipation Physical Evaluation Clearance Form',
                                                    'renewal_uniform_measurement' => '4. PSA Birth Certificate',
                                                    'renewal_health_assessment' => '5. Grade 5 and 6 Report Card (for incoming Grade 7) or Grade 6 and 7 Report Card (for incoming Grade 8)',
                                                    'renewal_passport' => '6. Passport of the Student-Athlete',
                                                    'renewal_mother_id' => '7. Mother\'s valid Government-Issued ID',
                                                    'renewal_father_id' => '8. Father\'s valid Government-Issued ID',
                                                    'renewal_guardian_id' => '9. Designated Guardian\'s valid Government-Issued ID with signature'
                                                ];
                                            } else {
                                                $docs = [
                                                    'scholarship_form' => 'Scholarship Application Form',
                                                    'student_profile' => 'Student-Athlete\'s Profile Form',
                                                    'medical_clearance' => 'Preparticipation Physical Evaluation Clearance Form',
                                                    'psa_birth_cert' => 'PSA Birth Certificate',
                                                    'report_card' => 'Grade 5 and 6 Report Card (for incoming Grade 7) or Grade 6 and 7 Report Card (for incoming Grade 8)',
                                                    'guardian_id' => 'Designated Guardian\'s valid Government-Issued ID with signature',
                                                    'kukkiwon_cert' => 'Kukkiwon Certificate (If taekwondo) (not required for all)',
                                                    'ip_cert' => 'IP Certification (If member of an indigenous group) (not required for all)',
                                                    'pwd_id' => 'PWD ID (If person with disability) (not required for all)',
                                                    '4ps_id' => '4Ps ID or Certification (If beneficiary of the 4Ps) (not required for all)'
                                                ];
                                            }
                                        @endphp


                                        @foreach($docs as $key => $label)
                                            @php
                                                $isUploaded = isset($files[$key]) && !empty($files[$key]);
                                                if(in_array($key, ['ip_cert', 'pwd_id', '4ps_id', 'passport', 'mother_id', 'father_id', 'renewal_passport', 'renewal_mother_id', 'renewal_father_id']) && !$isUploaded) continue;
                                                $status = $statuses[$key] ?? 'Pending';
                                                $badgeClass = match($status) {
                                                    'Accepted' => 'bg-emerald-100/90 text-emerald-800 border-emerald-200',
                                                    'Needs resubmission' => 'bg-red-100/90 text-red-800 border-red-200',
                                                    default => 'bg-amber-100/90 text-amber-800 border-amber-200',
                                                };
                                            @endphp
                                            <tr class="hover:bg-white/20 transition group align-top">
                                                <td class="px-2 py-3 lg:px-6 lg:py-5 font-bold text-slate-800 leading-tight break-words text-[9px] md:text-xs lg:text-sm">{{ $label }}</td>
                                                <td class="px-1 py-3 lg:px-2 lg:py-5 text-center align-middle">
                                                    @if($isUploaded)
                                                        <span class="inline-flex px-1.5 py-0.5 sm:px-2 sm:py-1 lg:px-3 lg:py-1 text-[7px] sm:text-[9px] font-black uppercase rounded sm:rounded-full border shadow-sm {{ $badgeClass }} leading-none">{{ $status }}</span>
                                                    @else
                                                        <span class="text-slate-400 text-[8px] lg:text-[10px] italic font-bold">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="px-1 py-3 lg:px-2 lg:py-5 text-center align-middle">
                                                    @if($isUploaded)
                                                        <a href="{{ $files[$key] }}" target="_blank" class="text-indigo-700 hover:text-indigo-900 font-black text-[8px] lg:text-[10px] flex flex-col items-center justify-center gap-0.5 lg:gap-1 group/link">
                                                            <div class="p-1 md:p-1.5 lg:p-2 bg-indigo-50/80 rounded-md lg:rounded-lg group-hover/link:bg-indigo-100 transition">
                                                                <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                            </div>
                                                            VIEW
                                                        </a>
                                                    @else - @endif
                                                </td>
                                                <td class="px-1 py-3 lg:px-2 lg:py-5 text-center no-print align-middle">
                                                    @if($isUploaded)
                                                        <div class="flex flex-col md:flex-row justify-center items-center gap-1 md:gap-2 lg:gap-3">
                                                            <button type="button" {{ $application->is_locked ? 'disabled' : '' }} onclick="docAction(this, '{{ route('admission.approve_document', ['id' => $application->id, 'doc_key' => $key]) }}')" class="p-1.5 lg:p-2.5 rounded-md lg:rounded-xl {{ $application->is_locked ? 'bg-slate-200 text-slate-400 cursor-not-allowed border-slate-300' : 'bg-emerald-50/80 text-emerald-600 hover:bg-emerald-500 hover:text-white transition shadow-sm border border-emerald-200' }}" title="Accept">
                                                                <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                            </button>
                                                            <button type="button" {{ $application->is_locked ? 'disabled' : '' }} onclick="docAction(this, '{{ route('admission.decline_document', ['id' => $application->id, 'doc_key' => $key]) }}')" class="p-1.5 lg:p-2.5 rounded-md lg:rounded-xl {{ $application->is_locked ? 'bg-slate-200 text-slate-400 cursor-not-allowed border-slate-300' : 'bg-red-50/80 text-red-600 hover:bg-red-500 hover:text-white transition shadow-sm border border-red-200' }}" title="Needs resubmission">
                                                                <svg class="w-3 h-3 lg:w-4 lg:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-2 py-3 lg:px-6 lg:py-4 no-print align-middle">
                                                    <textarea name="document_remarks[{{ $key }}]" rows="2" class="w-full text-[9px] lg:text-xs border-white/40 bg-white/50 focus:bg-white rounded-md lg:rounded-xl focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition placeholder-slate-400 p-2 lg:p-3 leading-tight lg:leading-relaxed resize-none" placeholder="Remarks...">{{ $remarks[$key] ?? '' }}</textarea>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4 md:mt-5 lg:mt-6 text-right no-print">
                                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 px-6 lg:py-3 lg:px-8 rounded-lg lg:rounded-xl text-[10px] lg:text-xs uppercase tracking-widest shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition w-full sm:w-auto">
                                    Save Remarks Only
                                </button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            {{-- SIDEBAR ACTIONS (GLASSMOPHISM) --}}
            <div class="w-full lg:col-span-1 no-print">
                {{-- ⚡ GINAWANG bg-white/10 + backdrop-blur para transparent din ang sidebar ⚡ --}}
                <div class="bg-white/10 backdrop-blur-md shadow-xl shadow-indigo-100/30 rounded-3xl border border-white/30 p-5 sm:p-6 lg:sticky lg:top-6 w-full text-slate-800">
                    <div class="flex items-center justify-between border-b border-slate-500/20 pb-3 sm:pb-4 mb-4 sm:mb-6">
                        <h3 class="text-[10px] sm:text-xs font-black text-slate-900 uppercase tracking-widest">Application Decision</h3>
                        @if(in_array($application->status, ['Officially Enrolled', 'Enrolled']))
                            <button wire:click="toggleLock" class="text-[9px] sm:text-[10px] font-black uppercase tracking-wider px-2 py-1 rounded {{ $application->is_locked ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' }} transition">
                                <span wire:loading.remove wire:target="toggleLock">
                                    {{ $application->is_locked ? '🔒 Unlock' : '🔓 Lock' }}
                                </span>
                                <span wire:loading wire:target="toggleLock" class="animate-pulse">Loading...</span>
                            </button>
                        @endif
                    </div>
                    
                    @if($application->is_locked)
                        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-3 rounded text-red-700 text-xs">
                            <span class="font-bold">Record Locked.</span> You cannot modify the status or documents of a finalized enrollment record. Unlock it first to make changes.
                        </div>
                    @endif

                    <form id="status-form" action="{{ route('admission.process', $application->id) }}" method="POST" class="w-full" onsubmit="return handleFormAjax(event, 'Application status updated!')">
                        @csrf @method('PATCH')
                        
                        @if(isset($remarks) && is_array($remarks))
                            @foreach($remarks as $k => $v) <input type="hidden" name="document_remarks[{{ $k }}]" value="{{ $v }}"> @endforeach
                        @endif

                        <div class="mb-5 sm:mb-6 w-full">
                            <label class="block text-[9px] sm:text-[10px] font-black text-slate-500 uppercase mb-1.5 sm:mb-2 tracking-wider">Set Status</label>
                            
                            <div class="w-full">
                                <select name="status" id="status" {{ $application->is_locked ? 'disabled' : '' }} class="w-full border-white/40 rounded-lg sm:rounded-xl text-xs sm:text-sm font-bold text-slate-800 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm py-2.5 px-3 sm:py-3 sm:px-3 bg-white/60 cursor-pointer disabled:bg-slate-100 disabled:text-slate-400">
                                    <optgroup label="Phase 1: Registration">
                                        <option value="With Pending Requirements" {{ $application->status == 'With Pending Requirements' ? 'selected' : '' }}>With Pending Requirements</option>
                                        <option value="With Complete Requirements &amp; for 1st Level Assessment" {{ $application->status == 'With Complete Requirements &amp; for 1st Level Assessment' ? 'selected' : '' }}>For 1st Level Assessment</option>
                                        <option value="For 2nd Level Assessment" {{ $application->status == 'For 2nd Level Assessment' ? 'selected' : '' }}>Passed 1st Level (Move to Phase 2)</option>
                                    </optgroup>
                                    <optgroup label="Phase 2: Documents">
                                        <option value="Waitlisted" {{ $application->status == 'Waitlisted' ? 'selected' : '' }}>Waitlisted</option>
                                        <option value="Qualified" {{ $application->status == 'Qualified' ? 'selected' : '' }}>Qualified (Final)</option>
                                    </optgroup>
                                    <optgroup label="Declined">
                                        <option value="Not Qualified" {{ in_array($application->status, ['Not Qualified', 'Rejected', 'Failed']) ? 'selected' : '' }}>Not Qualified</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <div class="mb-5 sm:mb-6 hidden w-full" id="rejection-div">
                            <label class="block text-[9px] sm:text-[10px] font-black text-red-400 uppercase mb-1.5 sm:mb-2 tracking-wider">Reason for Rejection</label>
                            <textarea name="rejection_reason" {{ $application->is_locked ? 'disabled' : '' }} rows="3" class="w-full border-red-200 bg-red-50/80 rounded-lg sm:rounded-xl text-xs sm:text-sm text-red-700 focus:border-red-500 focus:ring-red-500 p-2.5 sm:p-3 placeholder-red-300 disabled:bg-slate-100 disabled:text-slate-400" placeholder="State reason...">{{ $application->rejection_reason }}</textarea>
                        </div>

                        <button type="submit" {{ $application->is_locked ? 'disabled' : '' }} class="{{ $application->is_locked ? 'bg-slate-300 text-slate-500 cursor-not-allowed' : 'bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white shadow-lg hover:shadow-indigo-300/50' }} w-full font-black py-3 sm:py-4 rounded-lg sm:rounded-xl transition transform hover:-translate-y-0.5 text-[10px] sm:text-xs uppercase tracking-[0.2em] flex items-center justify-center gap-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Update Status
                        </button>
                    </form>
                </div>

                {{-- AUDIT TRAIL LOG --}}
                <div class="mt-6 bg-white/60 p-5 sm:p-6 lg:p-8 rounded-2xl sm:rounded-3xl border border-white/40 shadow-xl w-full">
                    <h2 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center mb-4">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Audit Trail
                    </h2>
                    
                    <div class="space-y-4 max-h-64 overflow-y-auto pr-2 custom-scrollbar">
                        @forelse($auditLogs as $log)
                            @php
                                $details = json_decode($log->details, true);
                            @endphp
                            <div class="border-l-2 border-indigo-200 pl-3 py-1 relative">
                                <span class="absolute -left-[5px] top-1.5 w-2 h-2 rounded-full bg-indigo-500"></span>
                                <div class="text-[10px] sm:text-xs">
                                    <span class="font-bold text-slate-800">{{ $log->user->name ?? 'System' }}</span>
                                    <span class="text-slate-500">{{ $log->action }}</span>
                                </div>
                                <div class="text-[9px] text-slate-400 mt-1 uppercase tracking-wider">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y h:i A') }}
                                </div>
                                @if(isset($details['status']))
                                    <div class="mt-1 text-[10px] text-slate-600 bg-white/50 px-2 py-1 rounded inline-block">
                                        Status set to: <span class="font-bold text-indigo-600">{{ $details['status'] }}</span>
                                    </div>
                                @endif
                                @if(isset($details['document']))
                                    <div class="mt-1 text-[10px] text-slate-600 bg-white/50 px-2 py-1 rounded inline-block uppercase font-bold">
                                        {{ str_replace('_', ' ', $details['document']) }}
                                    </div>
                                @endif
                                @if(isset($details['remarks']))
                                    <div class="mt-1 text-[9px] text-slate-500 italic">
                                        "{{ $details['remarks'] }}"
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-xs text-slate-400 text-center italic py-4">
                                No audit logs yet.
                            </div>
                        @endforelse
                    </div>
                </div>
                
            </div>

        </div>
    <script>
        function handleFormAjax(e, successMsg) {
            e.preventDefault();
            var form = e.target;
            var btn = form.querySelector('button[type="submit"]');
            var origText = btn.innerHTML;
            
            // UI Feedback: Disable button and show saving state
            btn.disabled = true;
            btn.style.opacity = '0.7';
            btn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> SAVING...';
            
            var formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest', 
                    'Accept': 'application/json' 
                },
                body: formData,
                credentials: 'same-origin'
            })
            .then(function(response) {
                var isSuccess = response.ok || response.redirected;
                var msg = isSuccess ? successMsg : 'Something went wrong. Please try again.';
                var color = isSuccess ? '#10b981' : '#ef4444'; // emerald-500 or red-500
                var bgColor = isSuccess ? '#ecfdf5' : '#fef2f2';
                var icon = isSuccess 
                    ? '<svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                    : '<svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                
                // Create custom robust toast
                var toast = document.createElement('div');
                toast.className = 'fixed top-24 right-6 z-[9999999] pointer-events-none transform transition-all duration-500 translate-x-full opacity-0';
                toast.innerHTML = `
                    <div class="pointer-events-auto bg-white border-l-4 rounded-r-xl shadow-2xl p-4 flex items-start gap-3 w-80 ring-1 ring-black/5" style="border-left-color: ${color};">
                        <div class="flex-shrink-0 mt-0.5">${icon}</div>
                        <div class="flex-1">
                            <h3 class="text-sm font-bold text-gray-800">${isSuccess ? 'Success' : 'Error'}</h3>
                            <p class="text-xs text-gray-600 mt-1 font-medium">${msg}</p>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(toast);
                
                // Animate In
                setTimeout(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0');
                    toast.classList.add('translate-x-0', 'opacity-100');
                }, 50);
                
                // Animate Out & Remove
                setTimeout(() => {
                    toast.classList.remove('translate-x-0', 'opacity-100');
                    toast.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => { if(toast.parentNode) toast.remove(); }, 500);
                }, 5000);

                // Restore Button State
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.innerHTML = origText;
                
                // Delay Livewire refresh so toast has time to be seen!
                setTimeout(() => {
                    try { Livewire.all()[0].$refresh(); } catch(e) {}
                }, 1500);
            })
            .catch(function(error) {
                console.error("AJAX Error:", error);
                // Restore Button State on network error
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.innerHTML = origText;
                alert("Network error occurred while saving.");
            });
            
            return false;
        }
    </script>
</div>