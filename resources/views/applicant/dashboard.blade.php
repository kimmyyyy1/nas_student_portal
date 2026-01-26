<x-applicant-layout>
    <div id="dashboard-content" class="max-w-7xl mx-auto py-6 sm:py-10 px-4 sm:px-6 lg:px-8">
        
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 sm:mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="mb-4 md:mb-0 text-center md:text-left w-full md:w-auto">
                <img src="{{ asset('images/nas/horizontal.png') }}" class="h-10 sm:h-12 md:h-16 object-contain mb-2 mx-auto md:mx-0" alt="NAS Logo">
                <h1 class="text-base sm:text-lg font-bold text-gray-700 tracking-tight mt-2">
                    Welcome, <span class="text-indigo-700">{{ Auth::user()->first_name }}</span>!
                </h1>
            </div>
            <div class="text-center md:text-right w-full md:w-auto mt-2 md:mt-0">
                <span class="text-[10px] sm:text-xs text-gray-400 uppercase font-bold tracking-wider block">Current Date</span>
                <p class="text-base sm:text-lg font-bold text-indigo-700">{{ now()->format('F d, Y') }}</p>
            </div>
        </div>

        {{-- SUCCESS ALERT --}}
        @if(session('success'))
            <div id="success-alert" class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md flex items-center transition-all duration-500">
                <svg class="h-6 w-6 mr-3 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div><p class="font-bold text-sm sm:text-base">Success!</p><p class="text-xs sm:text-sm">{{ session('success') }}</p></div>
            </div>
        @endif

        @if($application)
            {{-- STATUS CARD --}}
            <div id="status-section" class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-200 mb-6 sm:mb-8">
                <div class="p-6 md:p-8 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                        <div class="w-full md:w-auto mb-4 md:mb-0">
                            <h2 class="text-base sm:text-lg font-bold text-gray-800 uppercase tracking-wide">Application Status</h2>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">Reference No: <span class="font-mono text-indigo-600 font-bold bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</span></p>
                        </div>
                        @php
                            $statusColor = match($application->status) {
                                'Qualified', 'Enrolled' => 'bg-green-100 text-green-800 border-green-200',
                                'Not Qualified' => 'bg-red-100 text-red-800 border-red-200',
                                'Waitlisted' => 'bg-orange-100 text-orange-800 border-orange-200',
                                'For Assessment', 'Pending', 'Submitted (with Pending)' => 'bg-blue-100 text-blue-800 border-blue-200',
                                default => 'bg-gray-100 text-gray-800 border-gray-200'
                            };
                        @endphp
                        <span id="live-status-badge" class="px-4 py-2 rounded-full text-xs sm:text-sm font-bold border uppercase tracking-wide shadow-sm {{ $statusColor }} self-start md:self-center">{{ $application->status }}</span>
                    </div>

                    {{-- PROGRESS BAR --}}
                    <div class="relative pt-1">
                        <div class="flex mb-2 items-center justify-between">
                            <div class="text-[10px] sm:text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-indigo-600 bg-indigo-200">Progress</div>
                            <div class="text-right"><span class="text-[10px] sm:text-xs font-semibold inline-block text-indigo-600">{{ $application->status == 'Enrolled' ? '100%' : ($application->status == 'Qualified' ? '90%' : '50%') }}</span></div>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-indigo-200">
                            @php
                                $progress = match($application->status) { 
                                    'Enrolled' => 100, 
                                    'Qualified' => 90, 
                                    'For Assessment', 'Waitlisted', 'Pending', 'Submitted (with Pending)' => 50, 
                                    'Not Qualified' => 100, 
                                    default => 25 
                                };
                                $barColor = $application->status == 'Not Qualified' ? 'bg-red-500' : 'bg-indigo-500';
                            @endphp
                            <div style="width:{{ $progress }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $barColor }} transition-all duration-1000 ease-in-out"></div>
                        </div>
                    </div>

                    {{-- REMARKS --}}
                    @if($application->assessment_score || $application->rejection_reason)
                        <div class="mt-4 p-4 rounded-lg border text-xs sm:text-sm {{ $application->status == 'Not Qualified' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-yellow-50 border-yellow-200 text-yellow-800' }}">
                            <h4 class="font-bold uppercase mb-1">Registrar Remarks:</h4>
                            <p>{{ $application->rejection_reason ?? $application->assessment_score }}</p>
                        </div>
                    @endif

                    <div class="mt-6 flex justify-end">
                        @if(!in_array($application->status, ['Enrolled', 'Qualified', 'Not Qualified']))
                            <a href="{{ route('applicant.edit') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit Application
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 3-COLUMN GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 mb-8">
                
                {{-- LEFT COLUMN: PROFILE & BACKGROUND INFO (MOVED HERE TO FILL SPACE) --}}
                <div class="lg:col-span-1 space-y-6 sm:space-y-8">
                    
                    {{-- 1. PROFILE CARD --}}
                    <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden">
                        <div class="bg-indigo-900 px-6 py-4 border-b border-indigo-800">
                            <h3 class="text-white font-bold text-base sm:text-lg flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Student Profile
                            </h3>
                        </div>
                        <div class="p-6 text-center">
                            <div class="inline-block relative">
                                {{-- SAFE CHECK FOR ID PICTURE --}}
                                @if(isset($application->uploaded_files['id_picture']))
                                    <img src="{{ $application->uploaded_files['id_picture'] }}" class="h-24 w-24 sm:h-32 sm:w-32 rounded-full object-cover border-4 border-indigo-100 shadow-md mx-auto" alt="Student Photo">
                                @else
                                    <div class="h-24 w-24 sm:h-32 sm:w-32 rounded-full bg-gray-200 flex items-center justify-center border-4 border-gray-100 mx-auto">
                                        <span class="text-gray-400 text-2xl sm:text-3xl font-bold">{{ substr($application->first_name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <h2 class="mt-4 text-lg sm:text-xl font-bold text-gray-900">{{ $application->last_name }}, {{ $application->first_name }}</h2>
                            <p class="text-indigo-600 font-medium text-sm sm:text-base">{{ $application->middle_name }}</p>
                            <div class="mt-6 border-t border-gray-100 pt-4 text-left">
                                <div class="mb-3"><span class="text-[10px] sm:text-xs text-gray-500 uppercase font-bold tracking-wider block">Learner Ref No. (LRN)</span><p class="text-gray-900 font-mono font-bold text-sm sm:text-base break-words">{{ $application->lrn }}</p></div>
                                <div class="mb-3"><span class="text-[10px] sm:text-xs text-gray-500 uppercase font-bold tracking-wider block">Email Address</span><p class="text-gray-900 text-sm truncate">{{ $application->email_address }}</p></div>
                                <div><span class="text-[10px] sm:text-xs text-gray-500 uppercase font-bold tracking-wider block">Region / Province</span><p class="text-gray-900 text-sm truncate">{{ $application->region }}</p><p class="text-gray-700 text-xs">{{ $application->province }}</p></div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. BACKGROUND & SPECIAL CATEGORIES (MOVED FROM RIGHT TO LEFT) --}}
                    <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center"><h3 class="text-gray-800 font-bold text-base sm:text-lg flex items-center"><svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Background</h3></div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center border-b border-gray-100 pb-2"><span class="text-xs sm:text-sm font-medium text-gray-600">IP Member</span><div class="text-right"><span class="text-xs sm:text-sm font-bold {{ $application->is_ip ? 'text-green-600' : 'text-gray-400' }}">{{ $application->is_ip ? 'Yes' : 'No' }}</span>@if($application->is_ip && $application->ip_group_name)<div class="text-[10px] text-gray-500">{{ $application->ip_group_name }}</div>@endif</div></div>
                                <div class="flex justify-between items-center border-b border-gray-100 pb-2"><span class="text-xs sm:text-sm font-medium text-gray-600">PWD</span><div class="text-right"><span class="text-xs sm:text-sm font-bold {{ $application->is_pwd ? 'text-green-600' : 'text-gray-400' }}">{{ $application->is_pwd ? 'Yes' : 'No' }}</span>@if($application->is_pwd && $application->pwd_disability)<div class="text-[10px] text-gray-500">{{ $application->pwd_disability }}</div>@endif</div></div>
                                <div class="flex justify-between items-center"><span class="text-xs sm:text-sm font-medium text-gray-600">4Ps Member</span><span class="text-xs sm:text-sm font-bold {{ $application->is_4ps ? 'text-green-600' : 'text-gray-400' }}">{{ $application->is_4ps ? 'Yes' : 'No' }}</span></div>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg text-sm space-y-2 mt-4">
                                <div>
                                    <label class="text-[10px] text-gray-400 uppercase font-bold block">Learned NAS via</label>
                                    <p class="font-semibold text-gray-700 text-xs sm:text-sm">{{ $application->learn_about_nas ?? 'N/A' }}</p>
                                    @if($application->referrer_name)
                                        <p class="text-[10px] text-gray-500 mt-1 pt-1 border-t border-gray-200">
                                            <span class="font-bold text-gray-600">Referred by:</span> {{ $application->referrer_name }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- RIGHT COLUMN --}}
                <div class="lg:col-span-2 space-y-6 sm:space-y-8">
                    
                    {{-- 1. ACADEMIC & SPORTS INFO --}}
                    <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center"><h3 class="text-gray-800 font-bold text-base sm:text-lg flex items-center"><svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>Academic & Sports Information</h3></div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100"><label class="text-[10px] sm:text-xs text-indigo-500 uppercase font-bold block">Grade Level Applied</label><p class="text-base sm:text-lg font-bold text-indigo-900">{{ $application->grade_level_applied }}</p></div>
                                <div class="bg-orange-50 p-4 rounded-lg border border-orange-100"><label class="text-[10px] sm:text-xs text-orange-500 uppercase font-bold block">Sport / Discipline</label><p class="text-base sm:text-lg font-bold text-orange-900">{{ $application->sport }} @if($application->sport_specification) <span class="block text-xs font-normal text-orange-700">({{ $application->sport_specification }})</span> @endif</p></div>
                                <div><label class="text-[10px] sm:text-xs text-gray-400 uppercase font-bold block">Previous School</label><p class="text-sm font-semibold text-gray-700">{{ $application->previous_school }}</p><span class="text-[10px] sm:text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">{{ $application->school_type }}</span></div>
                                
                                <div class="space-y-2">
                                    {{-- Palarong Pambansa --}}
                                    <div>
                                        <label class="text-[10px] sm:text-xs text-gray-400 uppercase font-bold block">Palarong Pambansa Finisher</label>
                                        <p class="text-sm font-semibold text-gray-700">
                                            @if($application->has_palaro_participation) 
                                                <span class="text-green-600">Yes</span> 
                                            @else 
                                                <span class="text-gray-400">No</span> 
                                            @endif
                                        </p>
                                    </div>

                                    {{-- Batang Pinoy --}}
                                    <div>
                                        <label class="text-[10px] sm:text-xs text-gray-400 uppercase font-bold block">Batang Pinoy Finisher</label>
                                        <p class="text-sm font-semibold text-gray-700">
                                            @if($application->batang_pinoy_finisher == 'Yes') 
                                                <span class="text-green-600">Yes</span> 
                                            @else 
                                                <span class="text-gray-400">No</span> 
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. SUBMITTED FILES --}}
                    @php
                        $reqKeys = ['scholarship_form', 'student_profile', 'medical_clearance', 'coach_reco', 'adviser_reco', 'birth_cert', 'report_card', 'guardian_id'];
                        $uploadedCount = 0;
                        if(isset($application->uploaded_files)) {
                            foreach($reqKeys as $key) {
                                if(isset($application->uploaded_files[$key]) && !empty($application->uploaded_files[$key])) {
                                    $uploadedCount++;
                                }
                            }
                        }
                        $reqStatus = ($uploadedCount == count($reqKeys)) ? 'Complete' : 'Incomplete';
                        $reqColor = ($reqStatus == 'Complete') ? 'bg-green-100 text-green-700 border-green-200' : 'bg-orange-100 text-orange-700 border-orange-200';
                    @endphp

                    <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden">
                        {{-- FLEX HEADER WITH STATUS BADGE --}}
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-gray-800 font-bold text-base sm:text-lg flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Submitted Requirements
                            </h3>
                            <span class="text-[10px] sm:text-xs font-bold px-3 py-1 rounded-full border {{ $reqColor }} uppercase tracking-wide">
                                {{ $reqStatus }} ({{ $uploadedCount }}/{{ count($reqKeys) }})
                            </span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[600px]">
                                <thead><tr class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 uppercase"><th class="px-6 py-3 font-bold">Document Name</th><th class="px-6 py-3 font-bold text-center">Status</th><th class="px-6 py-3 font-bold text-center">Action</th></tr></thead>
                                <tbody class="divide-y divide-gray-100">
                                    @php
                                        // Unified naming consistent with Edit form
                                        $summaryDisplayName = [
                                            'scholarship_form' => 'Scholarship Application Form',
                                            'student_profile' => 'Student-Athlete’s Profile Form',
                                            'medical_clearance' => 'Preparticipation Physical Evaluation Clearance Form',
                                            'coach_reco' => 'Coach’s Recommendation Form',
                                            'adviser_reco' => 'Adviser’s Recommendation Form',
                                            'birth_cert' => 'PSA Birth Certificate',
                                            'report_card' => 'Report Card (SF9)',
                                            'guardian_id' => 'Guardian’s Valid ID'
                                        ];
                                    @endphp
                                    @if(isset($application->uploaded_files))
                                        @foreach($application->uploaded_files as $key => $path)
                                            @if($key !== 'id_picture') {{-- Skip ID picture in this table --}}
                                                <tr class="hover:bg-gray-50 transition">
                                                    <td class="px-6 py-4 text-xs sm:text-sm font-medium text-gray-900">{{ $summaryDisplayName[$key] ?? ucfirst(str_replace('_', ' ', $key)) }}</td>
                                                    <td class="px-6 py-4 text-center"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-green-100 text-green-800"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>Uploaded</span></td>
                                                    <td class="px-6 py-4 text-center flex flex-col gap-1 items-center justify-center">
                                                        @if(Str::endsWith(strtolower($path), '.pdf'))
                                                            <a href="https://docs.google.com/viewer?url={{ urlencode($path) }}&embedded=true" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-[10px] sm:text-xs font-bold uppercase hover:underline flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>View PDF</a>
                                                            <a href="{{ $path }}" download class="text-gray-400 hover:text-gray-600 text-[10px] font-medium hover:underline">(Download)</a>
                                                        @else
                                                            <a href="{{ $path }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-[10px] sm:text-xs font-bold uppercase hover:underline flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>View Image</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @else
                                        <tr><td colspan="3" class="px-6 py-4 text-center text-gray-500 italic text-xs sm:text-sm">No files uploaded yet.</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. QUALIFIED / SUBMIT REQUIREMENTS SECTION --}}
            @if($application->status == 'Qualified')
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 sm:p-8 shadow-sm">
                    <div class="flex flex-col md:flex-row items-start">
                        <div class="flex-shrink-0 mb-4 md:mb-0"><svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg></div>
                        <div class="ml-0 md:ml-4 w-full">
                            <h3 class="text-lg sm:text-xl font-bold text-blue-900">Next Step: Submit Enrollment Requirements</h3>
                            <p class="text-xs sm:text-sm text-blue-700 mt-1 mb-6">Congratulations! Please upload the remaining digital copies to finalize your enrollment.</p>
                            
                            <form id="uploadForm" action="{{ route('applicant.submit_requirements') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 sm:p-6 rounded-xl border border-blue-100 shadow-sm">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @php
                                        $uploaded = $application->uploaded_files ?? [];
                                        // Match keys with Create/Edit forms
                                        $requiredFields = ['sf10', 'good_moral', 'psa_birth_cert', 'medical_cert', 'coach_reco'];
                                        $allFilesUploaded = true;
                                    @endphp
                                    @foreach($requiredFields as $field)
                                        @php
                                            $isUploaded = isset($uploaded[$field]) && !empty($uploaded[$field]);
                                            if (!$isUploaded) { $allFilesUploaded = false; }
                                            $label = $summaryDisplayName[$field] ?? strtoupper(str_replace('_', ' ', $field));
                                            $currentPath = $uploaded[$field] ?? null;
                                        @endphp
                                        <div class="border-2 rounded-lg p-4 transition-all {{ $isUploaded ? 'bg-green-50 border-green-400' : 'bg-red-50 border-red-300 shadow-md' }}">
                                            <div class="flex justify-between items-start mb-3">
                                                <label class="block text-xs sm:text-sm font-extrabold {{ $isUploaded ? 'text-green-800' : 'text-red-800' }}">{{ $label }}</label>
                                                @if($isUploaded)<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-200 text-green-900 border border-green-300">✓ SUBMITTED</span>@else<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-200 text-red-900 border border-red-300 animate-pulse">PENDING</span>@endif
                                            </div>
                                            @if($isUploaded)
                                                <div class="flex items-center justify-between bg-white p-3 rounded border border-green-200 shadow-sm">
                                                    <span class="text-[10px] sm:text-xs text-green-700 font-bold italic">File has been uploaded.</span>
                                                    @if(Str::endsWith(strtolower($currentPath), '.pdf')) <a href="https://docs.google.com/viewer?url={{ urlencode($currentPath) }}&embedded=true" target="_blank" class="text-[10px] sm:text-xs bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded font-bold transition flex items-center">VIEW PDF</a> @else <a href="{{ $currentPath }}" target="_blank" class="text-[10px] sm:text-xs bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded font-bold transition flex items-center">VIEW</a> @endif
                                                </div>
                                            @else
                                                <div class="bg-white p-2 rounded border border-red-200"><p class="text-[10px] text-red-500 font-bold mb-1 uppercase tracking-wide">Select file to upload:</p><input type="file" name="{{ $field }}" required class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-red-100 file:text-red-700 hover:file:bg-red-200 cursor-pointer"></div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-8">
                                    @if($allFilesUploaded)
                                        <div class="bg-green-50 border-2 border-green-400 rounded-xl p-6 text-center shadow-md">
                                            <h3 class="text-lg sm:text-xl font-extrabold text-green-800 uppercase tracking-wide mb-2">Digital Submission Complete!</h3>
                                            <p class="text-green-700 font-medium mb-6 text-sm sm:text-base">You have successfully uploaded all the required documents.</p>
                                        </div>
                                    @else
                                        <div class="flex justify-end"><button type="submit" id="submitBtn" class="bg-indigo-700 hover:bg-indigo-800 text-white px-6 sm:px-8 py-3 rounded-lg font-bold shadow-md transition transform hover:-translate-y-0.5 flex items-center text-sm sm:text-base"><span id="btnText">Upload Selected Files</span><svg id="btnSpinner" class="animate-spin ml-2 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></button></div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @else
            {{-- NO APPLICATION STATE --}}
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100 text-center p-10 sm:p-16">
                <img src="{{ asset('images/nas/stack.png') }}" class="h-20 sm:h-24 w-auto mx-auto mb-6 opacity-90 drop-shadow-sm hover:scale-105 transition-transform" alt="NAS Logo">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">No Application Found</h2>
                <p class="text-gray-500 mb-8 max-w-md mx-auto text-sm sm:text-base">It looks like you haven't started your admission process yet.</p>
                <a href="{{ route('applicant.create') }}" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 border border-transparent text-base sm:text-lg font-bold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg transform transition hover:-translate-y-1">START APPLICATION</a>
            </div>
        @endif
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-refresh dashboard every 5 seconds (Only content changes)
            setInterval(function() { updateDashboard(); }, 5000);

            const form = document.getElementById('uploadForm');
            if (form) {
                form.addEventListener('submit', function() {
                    var btn = document.getElementById('submitBtn');
                    var btnText = document.getElementById('btnText');
                    var spinner = document.getElementById('btnSpinner');
                    if (btn && btnText && spinner) {
                        btn.disabled = true;
                        btn.classList.add('opacity-75', 'cursor-not-allowed');
                        btnText.innerText = 'Uploading... Please Wait';
                        spinner.classList.remove('hidden');
                    }
                });
            }

            const alert = document.getElementById('success-alert');
            if (alert) { setTimeout(function() { alert.style.opacity = '0'; setTimeout(function() { alert.remove(); }, 500); }, 5000); }
        });

        function updateDashboard() {
            const url = window.location.href;
            fetch(url).then(response => response.text()).then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.getElementById('dashboard-content').innerHTML;
                const currentContent = document.getElementById('dashboard-content');
                
                // Prevent update if user is typing in an input (e.g. choosing file)
                if(document.activeElement.tagName !== "INPUT" && document.activeElement.tagName !== "SELECT") {
                     if(currentContent.innerHTML !== newContent) { currentContent.innerHTML = newContent; }
                }
            }).catch(error => console.error('Error updating dashboard:', error));
        }
    </script>
</x-applicant-layout>