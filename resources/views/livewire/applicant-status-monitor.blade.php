<div class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8 font-sans" wire:poll.5s>
    
    {{-- HEADER SECTION --}}
    <div class="max-w-7xl mx-auto mb-10 flex flex-col md:flex-row justify-between items-center gap-6 animate-fade-in-down">
        <div class="flex items-center gap-6">
            <div class="relative">
                <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full blur opacity-30"></div>
                {{-- User Avatar / Placeholder --}}
                @if(isset($application) && $application->uploaded_files && isset($application->uploaded_files['id_picture']))
                    <img src="{{ $application->uploaded_files['id_picture'] }}" class="relative h-20 w-20 rounded-full object-cover border-4 border-white shadow-md">
                @else
                    <div class="relative h-20 w-20 rounded-full bg-indigo-100 flex items-center justify-center border-4 border-white shadow-md text-indigo-600 text-2xl font-black">
                        {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}
                    </div>
                @endif
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">
                    Hello, {{ Auth::user()->first_name }}!
                </h1>
                <p class="text-slate-500 font-medium">Welcome to your NASCENT SAS Portal</p>
            </div>
        </div>
        
        {{-- Date Widget --}}
        <div class="hidden md:block text-right">
            <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest mb-1">Today is</p>
            <p class="text-xl font-bold text-slate-700">{{ $currentDate ?? date('l, F j, Y') }}</p>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="max-w-7xl mx-auto space-y-8">

        {{-- ALERT MESSAGES --}}
        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm flex items-center animate-fade-in-up">
                <svg class="h-6 w-6 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <p class="font-bold text-emerald-800">Success!</p>
                    <p class="text-sm text-emerald-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm flex items-center animate-fade-in-up">
                <svg class="h-6 w-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <p class="font-bold text-red-800">Notice</p>
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- SCENARIO A: APPLICATION SUBMITTED (SHOW STATUS) --}}
        @if($application)
            
            @php
                // ⚡ CENTRALIZED STATUS LOGIC ⚡
                $currentStatus = strtoupper($application->status);

                // Check for ADMITTED status to trigger the final redirect
                $isRedirectReady = str_contains($currentStatus, 'ADMITTED') || str_contains($currentStatus, 'ENROLLED AND ADMITTED');
                
                // Other statuses
                $isSubmitted   = str_contains($currentStatus, 'OFFICIALLY ENROLLED') && !$isRedirectReady;
                $isQualified   = str_contains($currentStatus, 'QUALIFIED') && !str_contains($currentStatus, 'NOT');
                $isPhase2      = str_contains($currentStatus, '2ND LEVEL') || str_contains($currentStatus, 'REQUIREMENTS');
                $isRejected    = str_contains($currentStatus, 'NOT QUALIFIED') || str_contains($currentStatus, 'REJECTED') || str_contains($currentStatus, 'FAILED');
                $isReturned    = str_contains($currentStatus, 'RETURNED');
                
                // Progress Bar Width
                $displayProgress = 25; 
                if($isRedirectReady) $displayProgress = 100;
                elseif($isSubmitted) $displayProgress = 90;
                elseif($isQualified) $displayProgress = 85;
                elseif($isPhase2) $displayProgress = 50;

                // Status Badge Color
                $statusColor = 'bg-yellow-100 text-yellow-700 border-yellow-200';
                $dotColor = 'bg-yellow-500';
                
                if($isRedirectReady) {
                    $statusColor = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                    $dotColor = 'bg-emerald-500';
                } elseif($isSubmitted) {
                     $statusColor = 'bg-blue-100 text-blue-700 border-blue-200';
                     $dotColor = 'bg-blue-500';
                } elseif($isQualified) {
                    $statusColor = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                    $dotColor = 'bg-emerald-500';
                } elseif($isRejected || $isReturned) {
                    $statusColor = 'bg-red-100 text-red-700 border-red-200';
                    $dotColor = 'bg-red-500';
                }
            @endphp

            {{-- ⚡ AUTO-REDIRECT SCRIPT (ONLY IF ADMITTED) ⚡ --}}
            @if($isRedirectReady)
                <div 
                    x-data="{ isRedirecting: false }" 
                    x-init="if(!isRedirecting) { 
                        isRedirecting = true; 
                        setTimeout(() => { window.location.href = '{{ route('student.dashboard') }}' }, 3000); 
                    }"
                    class="fixed inset-0 bg-white/95 backdrop-blur-md z-[9999] flex flex-col items-center justify-center text-center animate-fade-in"
                >
                    <div class="mb-6 relative">
                        <div class="h-20 w-20 rounded-full border-4 border-indigo-200 border-t-indigo-600 animate-spin"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class='bx bx-check text-3xl text-indigo-600 font-bold'></i>
                        </div>
                    </div>
                    
                    <h2 class="text-3xl font-black text-gray-800 mb-2 tracking-tight">Congratulations, Scholar!</h2>
                    <p class="text-gray-500 font-medium mb-1">Your admission is confirmed.</p>
                    <p class="text-indigo-600 text-sm animate-pulse font-bold mt-4">Redirecting to your Student Portal...</p>
                </div>
            @endif

            {{-- STATUS TRACKER CARD --}}
            <div class="bg-white rounded-[2rem] shadow-xl shadow-indigo-100/50 border border-slate-100 overflow-hidden relative">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
                
                <div class="p-8 md:p-10">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                        <div>
                            <h2 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Current Application Status</h2>
                            <div class="flex items-center gap-3">
                                
                                <div class="px-4 py-2 rounded-full border {{ $statusColor }} flex items-center shadow-sm">
                                    <span class="relative flex h-3 w-3 mr-3">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $dotColor }} opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-3 w-3 {{ $dotColor }}"></span>
                                    </span>
                                    <span class="font-bold text-sm uppercase tracking-tight">{{ $application->status }}</span>
                                </div>
                                <span class="text-xs text-slate-400 font-mono">LRN: {{ $application->lrn }}</span>
                            </div>
                        </div>
                        
                        {{-- Phase Indicator --}}
                        <div class="mt-4 md:mt-0 text-right">
                            <span class="block text-4xl font-black text-slate-200">
                                @if($isRedirectReady) FINAL
                                @elseif($isSubmitted) PENDING
                                @elseif($isQualified) PHASE 3
                                @elseif($isPhase2) PHASE 2
                                @else PHASE 1 @endif
                            </span>
                            <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">
                                @if($isRedirectReady) Officially Admitted
                                @elseif($isSubmitted) For Admin Verification
                                @elseif($isQualified) Proceed to Enrollment
                                @else Assessment Ongoing @endif
                            </span>
                        </div>
                    </div>

                    {{-- PROGRESS BAR VISUALIZER --}}
                    <div class="relative">
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-slate-100">
                            <div style="width:{{ $displayProgress }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-indigo-500 to-purple-600 transition-all duration-1000 ease-out"></div>
                        </div>
                        <div class="flex justify-between text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            <span class="{{ $displayProgress >= 25 ? 'text-indigo-600' : '' }}">Registration</span>
                            <span class="{{ $displayProgress >= 50 ? 'text-indigo-600' : '' }}">1st Assessment</span>
                            <span class="{{ $displayProgress >= 85 ? 'text-indigo-600' : '' }}">2nd Assessment</span>
                            <span class="{{ $displayProgress >= 90 ? 'text-emerald-600' : '' }}">Final Result</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DETAILS GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                {{-- CARD 1: PROFILE SUMMARY --}}
                <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-lg shadow-slate-100/50 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Applicant Profile
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase">Full Name</label>
                            <p class="text-sm font-bold text-slate-700">{{ $application->first_name }} {{ $application->last_name }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase">Region & Province</label>
                            <p class="text-sm font-bold text-slate-700">{{ $application->region }}, {{ $application->province }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase">Age & Gender</label>
                            <p class="text-sm font-bold text-slate-700">{{ $application->age }} Years Old / {{ $application->gender }}</p>
                        </div>
                    </div>
                </div>

                {{-- CARD 2: SPORTS INFO --}}
                <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-lg shadow-slate-100/50 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Sports Information
                    </h3>
                    <div class="space-y-4">
                        <div class="bg-indigo-50 p-4 rounded-2xl border border-indigo-100">
                            <label class="block text-[10px] font-bold text-indigo-400 uppercase">Focus Sport</label>
                            <p class="text-lg font-black text-indigo-700">{{ $application->sport }}</p>
                            @if($application->sport_specification)
                                <p class="text-xs font-bold text-indigo-500 mt-1">({{ $application->sport_specification }})</p>
                            @endif
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-500">Palarong Pambansa Finisher?</span>
                            <span class="px-3 py-1 rounded text-[10px] font-bold uppercase {{ $application->palaro_finisher == 'Yes' ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                {{ $application->palaro_finisher }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-500">Batang Pinoy Finisher?</span>
                            <span class="px-3 py-1 rounded text-[10px] font-bold uppercase {{ $application->batang_pinoy_finisher == 'Yes' ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                {{ $application->batang_pinoy_finisher }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- CARD 3: NEXT STEPS (DYNAMIC) --}}
                <div class="bg-gradient-to-br from-indigo-600 to-purple-700 p-8 rounded-3xl shadow-xl shadow-indigo-200 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
                    
                    <h3 class="text-lg font-black mb-4 relative z-10">Next Steps</h3>
                    
                    {{-- ⚡ SCENARIO 0: NOT QUALIFIED / REJECTED ⚡ --}}
                    @if($isRejected)
                        <div class="relative z-10 mb-6">
                            <div class="bg-red-500/20 border border-red-400/30 p-4 rounded-2xl mb-2">
                                <p class="text-red-100 text-xs font-bold uppercase tracking-widest mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Application Unsuccessful
                                </p>
                                <p class="text-white text-sm leading-relaxed mb-3">We regret to inform you that your application did not meet the required criteria for this scholarship batch.</p>
                                
                                @if(!empty($application->rejection_reason))
                                    <div class="mt-4 p-3 bg-red-900/40 rounded-xl border border-red-500/30">
                                        <p class="text-[9px] text-red-200 uppercase font-black tracking-widest mb-1">Remarks / Reason:</p>
                                        <p class="text-sm font-medium text-white italic">"{{ $application->rejection_reason }}"</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                    {{-- ⚡ SCENARIO: SUBMITTED (WAITING FOR ADMIN) ⚡ --}}
                    @elseif($isSubmitted)
                        <div class="relative z-10 mb-6">
                            <div class="bg-blue-500/30 border border-blue-400/40 p-4 rounded-2xl mb-2">
                                <p class="text-blue-50 text-xs font-bold uppercase tracking-wide mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Enrollment Form Submitted
                                </p>
                                <p class="text-white text-sm leading-relaxed">Your official enrollment form and requirements have been submitted. Please wait for the Registrar to verify your documents and officially ADMIT you to the system.</p>
                            </div>
                        </div>
                        <button disabled class="w-full bg-blue-600 border border-blue-500 text-white font-black py-3.5 rounded-xl cursor-not-allowed relative z-10 text-xs uppercase tracking-widest shadow-lg flex justify-center items-center opacity-80">
                            <svg class="w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Waiting for Admission
                        </button>

                    {{-- ⚡ SCENARIO: ADMITTED (READY TO REDIRECT) ⚡ --}}
                    @elseif($isRedirectReady)
                        <div class="relative z-10 mb-6">
                            <div class="bg-emerald-500/30 border border-emerald-400/40 p-4 rounded-2xl mb-2">
                                <p class="text-emerald-50 text-xs font-bold uppercase tracking-wide mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Enrollment Confirmed
                                </p>
                                <p class="text-white text-sm leading-relaxed">Welcome to NAS! You are now officially admitted as a NASCENT SAS Scholar.</p>
                            </div>
                        </div>
                        <button disabled class="w-full bg-emerald-600 border border-emerald-500 text-white font-black py-3.5 rounded-xl cursor-not-allowed relative z-10 text-xs uppercase tracking-widest shadow-lg flex justify-center items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Officially Admitted
                        </button>

                    {{-- SCENARIO 1: RE-UPLOAD NEEDED (Priority Check) --}}
                    @elseif($isReturned)
                        <div class="relative z-10 mb-6">
                            <div class="bg-red-500/20 border border-red-400/30 p-3 rounded-xl mb-2">
                                <p class="text-red-100 text-xs font-bold uppercase tracking-wide mb-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Action Required
                                </p>
                                <p class="text-white text-sm">Please check the remarks and re-upload the corrected files.</p>
                            </div>
                        </div>

                        @if($isQualified)
                             <a href="{{ route('applicant.enrollment.show') }}" class="inline-flex w-full justify-center items-center bg-white text-red-600 font-bold py-3 px-4 rounded-xl shadow-lg hover:bg-red-50 transition transform hover:-translate-y-1 relative z-10 text-xs uppercase tracking-widest animate-pulse">
                                Fix Enrollment Docs
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                            </a>
                        @else
                            <a href="{{ route('applicant.requirements') }}" class="inline-flex w-full justify-center items-center bg-white text-red-600 font-bold py-3 px-4 rounded-xl shadow-lg hover:bg-red-50 transition transform hover:-translate-y-1 relative z-10 text-xs uppercase tracking-widest animate-pulse">
                                Fix Admission Docs
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </a>
                        @endif

                    {{-- SCENARIO 2: REQUIREMENTS SUBMITTED (Wait for Review) --}}
                    @elseif(str_contains($currentStatus, 'REQUIREMENTS SUBMITTED'))
                        <p class="text-indigo-100 text-sm mb-6 relative z-10">Your documents have been submitted and are currently under review by the Secretariat.</p>
                        <button disabled class="w-full bg-indigo-800/50 text-indigo-300 font-bold py-3 rounded-xl cursor-not-allowed relative z-10 text-xs uppercase tracking-widest border border-indigo-500/30">
                            Application Under Review
                        </button>

                    {{-- SCENARIO 3: QUALIFIED -> PROCEED TO ENROLLMENT --}}
                    @elseif($isQualified)
                        <p class="text-indigo-100 text-sm mb-6 relative z-10">Congratulations! You have qualified. Please proceed to the Enrollment Phase to finalize your slot.</p>
                        <a href="{{ route('applicant.enrollment.show') }}" class="inline-block w-full text-center bg-white text-indigo-700 font-bold py-3 rounded-xl shadow-lg hover:bg-indigo-50 transition relative z-10 text-xs uppercase tracking-widest animate-bounce-slow">
                            Proceed to Enrollment
                        </a>

                    {{-- SCENARIO 5: PASSED 1ST LEVEL (Upload Requirements) --}}
                    @elseif($isPhase2)
                        <p class="text-indigo-100 text-sm mb-6 relative z-10">Congratulations! You passed the 1st Level Assessment. Please upload your mandatory documents now.</p>
                        <a href="{{ route('applicant.requirements') }}" class="inline-flex w-full justify-center items-center bg-white text-indigo-700 font-bold py-3 px-4 rounded-xl shadow-lg hover:bg-indigo-50 transition transform hover:-translate-y-1 relative z-10 text-xs uppercase tracking-widest animate-pulse">
                            Upload Requirements
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </a>

                    {{-- SCENARIO 6: DEFAULT/PENDING --}}
                    @else
                        <p class="text-indigo-100 text-sm mb-6 relative z-10 leading-relaxed">
                            Your application is currently being reviewed by the Secretariat. Please check back regularly for updates.
                        </p>
                        <div class="flex items-center gap-2 text-xs font-bold text-indigo-200 bg-white/10 p-3 rounded-lg backdrop-blur-sm relative z-10">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Awaiting Results...
                        </div>
                    @endif
                </div>

            </div>

        {{-- SCENARIO B: NO APPLICATION YET (SHOW APPLY BUTTON) --}}
        @else
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="bg-white p-12 rounded-[2.5rem] shadow-2xl shadow-indigo-100 border border-slate-100 max-w-3xl w-full relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
                    
                    <img src="{{ asset('images/nas/nas-logo-sidebar.png') }}" class="h-24 mx-auto mb-8 animate-bounce-slow">
                    
                    <h2 class="text-3xl font-black text-slate-800 mb-4">Start Your Journey</h2>
                    <p class="text-slate-500 mb-10 max-w-lg mx-auto text-lg">
                        Become a NASCENT SAS Scholar. Apply now to unlock world-class sports training and education.
                    </p>
                    
                    <a href="{{ route('applicant.create') }}" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 px-12 rounded-2xl text-lg shadow-xl shadow-indigo-200 transition-all transform hover:-translate-y-1">
                        Apply Now
                        <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>