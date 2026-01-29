<x-applicant-layout>
    <div id="dashboard-content" class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
        
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <div class="mb-4 md:mb-0 text-center md:text-left w-full md:w-auto">
                <img src="{{ asset('images/nas/horizontal.png') }}" class="h-10 sm:h-12 md:h-14 object-contain mb-2 mx-auto md:mx-0" alt="NAS Logo">
                <h1 class="text-base sm:text-lg font-bold text-gray-700 tracking-tight mt-1">
                    Welcome, <span class="text-indigo-700">{{ Auth::user()->first_name }}</span>!
                </h1>
            </div>
            <div class="text-center md:text-right w-full md:w-auto mt-2 md:mt-0">
                <span class="text-[10px] sm:text-xs text-gray-400 uppercase font-bold tracking-wider block">Current Date</span>
                <p class="text-sm sm:text-base font-bold text-indigo-700">{{ now()->format('F d, Y') }}</p>
            </div>
        </div>

        {{-- SUCCESS ALERT --}}
        @if(session('success'))
            <div id="success-alert" class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md flex items-center transition-all duration-500">
                <svg class="h-6 w-6 mr-3 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div><p class="font-bold text-sm">Success!</p><p class="text-xs">{{ session('success') }}</p></div>
            </div>
        @endif

        @if($application)
            {{-- STATUS CARD (Full Width) --}}
            <div id="status-section" class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-200 mb-6">
                <div class="p-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-5">
                        <div class="w-full md:w-auto mb-3 md:mb-0">
                            <h2 class="text-base font-bold text-gray-800 uppercase tracking-wide">Application Status</h2>
                            <p class="text-xs text-gray-500 mt-1">Reference No: <span class="font-mono text-indigo-600 font-bold bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</span></p>
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
                        <span id="live-status-badge" class="px-3 py-1.5 rounded-full text-xs font-bold border uppercase tracking-wide shadow-sm {{ $statusColor }} self-start md:self-center">{{ $application->status }}</span>
                    </div>

                    {{-- PROGRESS BAR --}}
                    <div class="relative pt-1">
                        <div class="flex mb-2 items-center justify-between">
                            <div class="text-[10px] font-semibold inline-block py-1 px-2 uppercase rounded-full text-indigo-600 bg-indigo-200">Progress</div>
                            <div class="text-right"><span class="text-[10px] font-semibold inline-block text-indigo-600">{{ $application->status == 'Enrolled' ? '100%' : ($application->status == 'Qualified' ? '90%' : '50%') }}</span></div>
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

                    {{-- GENERAL REMARKS --}}
                    @if($application->assessment_score || $application->rejection_reason)
                        <div class="mt-4 p-3 rounded-lg border text-xs {{ $application->status == 'Not Qualified' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-yellow-50 border-yellow-200 text-yellow-800' }}">
                            <h4 class="font-bold uppercase mb-1">Registrar Remarks:</h4>
                            <p>{{ $application->rejection_reason ?? $application->assessment_score }}</p>
                        </div>
                    @endif

                    <div class="mt-5 flex justify-end">
                        @if(!in_array($application->status, ['Enrolled', 'Qualified', 'Not Qualified']))
                            <a href="{{ route('applicant.edit') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit Application
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            @if($application->status == 'Qualified')
                {{-- QUALIFIED UPLOAD SECTION --}}
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 shadow-sm animate-fade-in-up mb-6">
                    <div class="flex flex-col md:flex-row items-start">
                        <div class="flex-shrink-0 mb-4 md:mb-0"><svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg></div>
                        <div class="ml-0 md:ml-4 w-full">
                            <h3 class="text-lg font-bold text-blue-900">Next Step: Submit Enrollment Requirements</h3>
                            <p class="text-xs text-blue-700 mt-1 mb-4">Congratulations! You are qualified. Please upload the remaining digital copies to finalize your enrollment.</p>
                            
                            {{-- FORM START --}}
                            <form id="uploadForm" action="{{ route('applicant.submit_requirements') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded-xl border border-blue-100 shadow-sm">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @php
                                        $uploaded = $application->uploaded_files;
                                        if (is_string($uploaded)) { $uploaded = json_decode($uploaded, true); }
                                        $uploaded = $uploaded ?? [];
                                        $remarks = $application->document_remarks ?? [];
                                        $requiredFields = ['sf10', 'good_moral', 'psa_birth_cert', 'medical_cert', 'coach_reco'];
                                        $allFilesUploaded = true;
                                    @endphp

                                    @foreach($requiredFields as $field)
                                        @php
                                            $isUploaded = isset($uploaded[$field]) && !empty($uploaded[$field]);
                                            $hasRemark = isset($remarks[$field]) && !empty($remarks[$field]);
                                            $isOptional = in_array($field, ['coach_reco']); 
                                            if ((!$isUploaded && !$isOptional) || $hasRemark) { $allFilesUploaded = false; }
                                            $niceLabels = [ 'sf10' => 'Report Card (SF10)', 'good_moral' => 'Good Moral Certificate', 'psa_birth_cert' => 'PSA Birth Certificate', 'medical_cert' => 'Medical Certificate', 'coach_reco' => 'Coach Recommendation' ];
                                            $label = $niceLabels[$field] ?? strtoupper(str_replace('_', ' ', $field));
                                        @endphp
                                        
                                        <div class="border rounded-lg p-3 transition-all {{ $hasRemark ? 'bg-red-50 border-red-300' : ($isUploaded ? 'bg-green-50 border-green-400' : 'bg-red-50 border-red-300 shadow-sm') }}">
                                            <div class="flex justify-between items-start mb-2">
                                                <label class="block text-xs font-bold {{ $hasRemark ? 'text-red-800' : ($isUploaded ? 'text-green-800' : 'text-red-800') }}">{{ $label }} @if(!$isOptional) <span class="text-red-600">*</span> @endif</label>
                                                @if($hasRemark) <span class="text-[9px] font-bold text-red-700 bg-red-100 px-2 py-0.5 rounded border border-red-200">NEEDS UPDATE</span>
                                                @elseif($isUploaded) <span class="text-[9px] font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded border border-green-200">SUBMITTED</span>
                                                @else 
                                                    @if($isOptional) <span class="text-[9px] font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded border border-gray-200">OPTIONAL</span>
                                                    @else <span class="text-[9px] font-bold text-red-700 bg-red-100 px-2 py-0.5 rounded border border-red-200">MISSING</span>
                                                    @endif
                                                @endif
                                            </div>
                                            @if(!$isUploaded || $hasRemark)
                                                <input type="file" name="{{ $field }}" class="block w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-bold file:bg-red-100 file:text-red-700 hover:file:bg-red-200 cursor-pointer">
                                            @else
                                                <span class="text-[10px] text-green-700 italic">File uploaded.</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4 text-right">
                                    @if(!$allFilesUploaded)
                                        <button type="submit" id="submitBtn" class="bg-indigo-700 hover:bg-indigo-800 text-white px-4 py-2 rounded-lg font-bold shadow-md text-xs">Upload Files</button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if($application->status != 'Qualified')
                
                {{-- INFO GRID: 3 Columns Row --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    
                    {{-- 1. STUDENT PROFILE --}}
                    <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden h-full">
                        <div class="bg-indigo-900 px-5 py-3 border-b border-indigo-800">
                            <h3 class="text-white font-bold text-sm flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Student Profile
                            </h3>
                        </div>
                        <div class="p-5 text-center">
                            <div class="inline-block relative">
                                @php
                                    $files = $application->uploaded_files;
                                    if (is_string($files)) { $files = json_decode($files, true); }
                                    $idPic = $files['id_picture'] ?? ($files->id_picture ?? null);
                                @endphp

                                @if($idPic)
                                    <img src="{{ $idPic }}" class="h-24 w-24 rounded-full object-cover border-4 border-indigo-100 shadow-md mx-auto" alt="Student Photo"
                                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($application->first_name) }}&background=6366f1&color=fff&size=128';">
                                @else
                                    <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center border-4 border-gray-100 mx-auto">
                                        <span class="text-gray-400 text-2xl font-bold">{{ substr($application->first_name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <h2 class="mt-3 text-lg font-bold text-gray-900">{{ $application->last_name }}, {{ $application->first_name }}</h2>
                            
                            @php
                                $middleName = $application->middle_name;
                                if (strtolower($middleName) === 'n/a') { $middleNameDisplay = 'N/A'; } else { $middleNameDisplay = $middleName; }
                            @endphp
                            <p class="text-indigo-600 font-medium text-sm">{{ $middleNameDisplay }}</p>

                            <div class="mt-4 border-t border-gray-100 pt-3 text-left">
                                <div class="mb-2"><span class="text-[10px] text-gray-500 uppercase font-bold tracking-wider block">Learner Ref No. (LRN)</span><p class="text-gray-900 font-mono font-bold text-sm">{{ $application->lrn }}</p></div>
                                <div class="mb-2"><span class="text-[10px] text-gray-500 uppercase font-bold tracking-wider block">Email Address</span><p class="text-gray-900 text-sm truncate">{{ $application->email_address }}</p></div>
                                <div><span class="text-[10px] text-gray-500 uppercase font-bold tracking-wider block">Region / Province</span><p class="text-gray-900 text-sm truncate">{{ $application->region }}</p><p class="text-gray-700 text-xs">{{ $application->province }}</p></div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. ACADEMIC & SPORTS INFO --}}
                    <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden h-full">
                        <div class="bg-gray-50 px-5 py-3 border-b border-gray-200 flex justify-between items-center"><h3 class="text-gray-800 font-bold text-sm flex items-center"><svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>Academic & Sports</h3></div>
                        <div class="p-5">
                            <div class="space-y-3">
                                <div class="bg-indigo-50 p-2.5 rounded border border-indigo-100"><label class="text-[9px] text-indigo-500 uppercase font-bold block">Grade Level Applied</label><p class="text-xs font-bold text-indigo-900">{{ $application->grade_level_applied }}</p></div>
                                <div class="bg-orange-50 p-2.5 rounded border border-orange-100"><label class="text-[9px] text-orange-500 uppercase font-bold block">Sport / Discipline</label><p class="text-xs font-bold text-orange-900">{{ $application->sport }}</p></div>
                                <div><label class="text-[9px] text-gray-400 uppercase font-bold block">Previous School</label><p class="text-xs font-semibold text-gray-700">{{ $application->previous_school }}</p></div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. BACKGROUND --}}
                    <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden h-full">
                        <div class="bg-gray-50 px-5 py-3 border-b border-gray-200 flex justify-between items-center"><h3 class="text-gray-800 font-bold text-sm flex items-center"><svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Background</h3></div>
                        <div class="p-5">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center border-b border-gray-100 pb-2"><span class="text-xs font-medium text-gray-600">IP Member</span><div class="text-right"><span class="text-xs font-bold {{ $application->is_ip ? 'text-green-600' : 'text-gray-400' }}">{{ $application->is_ip ? 'Yes' : 'No' }}</span>@if($application->is_ip && $application->ip_group_name)<div class="text-[9px] text-gray-500">{{ $application->ip_group_name }}</div>@endif</div></div>
                                <div class="flex justify-between items-center border-b border-gray-100 pb-2"><span class="text-xs font-medium text-gray-600">PWD</span><div class="text-right"><span class="text-xs font-bold {{ $application->is_pwd ? 'text-green-600' : 'text-gray-400' }}">{{ $application->is_pwd ? 'Yes' : 'No' }}</span>@if($application->is_pwd && $application->pwd_disability)<div class="text-[9px] text-gray-500">{{ $application->pwd_disability }}</div>@endif</div></div>
                                <div class="flex justify-between items-center"><span class="text-xs font-medium text-gray-600">4Ps Member</span><span class="text-xs font-bold {{ $application->is_4ps ? 'text-green-600' : 'text-gray-400' }}">{{ $application->is_4ps ? 'Yes' : 'No' }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SUBMITTED REQUIREMENTS (Full Width Below) --}}
                <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden mb-8">
                    <div class="bg-gray-50 px-5 py-3 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-gray-800 font-bold text-sm flex items-center">
                            <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Submitted Requirements
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[600px]">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-[10px] text-gray-500 uppercase">
                                    <th class="px-5 py-2 font-bold w-1/3">Document Name</th>
                                    <th class="px-5 py-2 font-bold text-center">Status</th>
                                    <th class="px-5 py-2 font-bold text-center">Action</th>
                                    <th class="px-5 py-2 font-bold text-left w-1/3">Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @php
                                    $reqKeys = [
                                        'id_picture' => '2x2 ID Picture', 
                                        'scholarship_form' => 'Scholarship Application Form',
                                        'student_profile' => 'Student-Athlete’s Profile Form',
                                        'medical_clearance' => 'Preparticipation Physical Evaluation Clearance Form',
                                        'coach_reco' => 'Coach’s Recommendation Form',
                                        'adviser_reco' => 'Adviser’s Recommendation Form',
                                        'birth_cert' => 'PSA Birth Certificate',
                                        'report_card' => 'Report Card (SF9)',
                                        'guardian_id' => 'Guardian’s Valid ID',
                                        'kukkiwon_cert' => 'Kukkiwon Certificate',
                                        'ip_cert' => 'IP Certification',
                                        'pwd_id' => 'PWD ID',
                                        '4ps_id' => '4Ps ID/Certification'
                                    ];
                                    
                                    $uploadedList = $application->uploaded_files;
                                    if (is_string($uploadedList)) { $uploadedList = json_decode($uploadedList, true); }
                                    $remarksList = $application->document_remarks ?? [];
                                    $statusesList = $application->document_statuses ?? [];
                                @endphp

                                @foreach($reqKeys as $key => $label)
                                    @php
                                        $path = $uploadedList[$key] ?? null;
                                        $hasRemark = isset($remarksList[$key]) && !empty($remarksList[$key]);
                                        $status = $statusesList[$key] ?? null;
                                        $isUploaded = !empty($path);
                                        $isSpecial = in_array($key, ['kukkiwon_cert', 'ip_cert', 'pwd_id', '4ps_id']);
                                        
                                        if ($isSpecial && !$isUploaded) { continue; }

                                        $isOptional = in_array($key, ['coach_reco', 'adviser_reco']);
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition {{ $hasRemark || $status == 'needs_update' ? 'bg-red-50' : '' }}">
                                        <td class="px-5 py-3 text-xs font-medium text-gray-900">
                                            {{ $label }}
                                            @if($isOptional) <span class="text-gray-400 text-[10px] ml-1">(Optional)</span> @endif
                                        </td>
                                        
                                        <td class="px-5 py-3 text-center">
                                            @if($status == 'pending_review')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">PENDING REVIEW</span>
                                            @elseif($hasRemark || $status == 'needs_update')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-red-100 text-red-700 border border-red-200">NEEDS UPDATE</span>
                                            @elseif($isUploaded)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-green-100 text-green-800 border border-green-200">✓ SUBMITTED</span>
                                            @else
                                                @if($isOptional)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-gray-100 text-gray-500 border border-gray-200">Optional - N/A</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-red-100 text-red-700 border border-red-200">MISSING</span>
                                                @endif
                                            @endif
                                        </td>
                                        
                                        <td class="px-5 py-3 text-center">
                                            @if($isUploaded)
                                                @php $viewUrl = route('applicant.view_file', ['id' => $application->id, 'type' => $key]); @endphp
                                                @if(Str::endsWith(strtolower($path), '.pdf'))
                                                    <a href="{{ $viewUrl }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-[10px] font-bold uppercase hover:underline">PDF</a>
                                                @else
                                                    <a href="{{ $viewUrl }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-[10px] font-bold uppercase hover:underline">IMAGE</a>
                                                @endif
                                            @else
                                                <span class="text-gray-400 text-xs">-</span>
                                            @endif
                                        </td>

                                        <td class="px-5 py-3 text-xs">
                                            @if($hasRemark)
                                                <div class="text-red-700 font-bold flex items-start">
                                                    <svg class="w-3 h-3 mr-1 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                    {{ $remarksList[$key] }}
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic text-[10px]">Good</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        @else
            {{-- CTA for No Application --}}
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-200 text-center p-12 sm:p-20">
                <div class="mb-6">
                    <img src="{{ asset('images/nas/horizontal.png') }}" class="h-20 sm:h-24 mx-auto object-contain opacity-80" alt="NAS Logo">
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-4">Start Your NAS Journey Today!</h2>
                <p class="text-gray-600 mb-8 max-w-lg mx-auto text-sm sm:text-base">
                    Join the National Academy of Sports and become part of the next generation of Filipino student-athletes. Submit your application now.
                </p>
                <a href="{{ route('applicant.create') }}" class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-base sm:text-lg font-bold rounded-full text-white bg-indigo-600 hover:bg-indigo-700 md:text-xl md:px-10 shadow-lg transform transition hover:scale-105">
                    Apply Now
                    <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </a>
            </div>
        @endif
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('uploadForm');

            // Only run auto-update if the upload form is NOT present.
            if (!form) {
                setInterval(function() { updateDashboard(); }, 5000);
            }

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
                if(document.activeElement.tagName !== "INPUT" && document.activeElement.tagName !== "SELECT") {
                     if(currentContent.innerHTML !== newContent) { currentContent.innerHTML = newContent; }
                }
            }).catch(error => console.error('Error updating dashboard:', error));
        }
    </script>
</x-applicant-layout>