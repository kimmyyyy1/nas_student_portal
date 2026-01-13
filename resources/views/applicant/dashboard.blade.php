<x-applicant-layout>
    {{-- WRAPPER ID FOR AJAX UPDATES --}}
    <div id="dashboard-content" class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">
                    Welcome, {{ Auth::user()->first_name }}!
                </h1>
                <p class="text-gray-500 mt-1">Manage your admission application status and requirements.</p>
            </div>
            <div class="mt-4 md:mt-0 text-right">
                <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Current Date</span>
                <p class="text-lg font-bold text-indigo-700">{{ now()->format('F d, Y') }}</p>
            </div>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div id="success-alert" class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md flex items-center transition-all duration-500">
                <svg class="h-6 w-6 mr-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="font-bold">Success!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- Error Message --}}
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($application)
            
            {{-- Status Card (ID added for targeted updates) --}}
            <div id="status-section" class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-200 mb-8">
                <div class="p-6 md:p-8 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                        <div>
                            <h2 class="text-lg font-bold text-gray-800 uppercase tracking-wide">Application Status</h2>
                            <p class="text-sm text-gray-500">Reference No: <span class="font-mono text-indigo-600 font-bold bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</span></p>
                        </div>
                        
                        @php
                            $statusColor = match($application->status) {
                                'Qualified', 'Enrolled' => 'bg-green-100 text-green-800 border-green-200',
                                'Not Qualified' => 'bg-red-100 text-red-800 border-red-200',
                                'Waitlisted' => 'bg-orange-100 text-orange-800 border-orange-200',
                                'For Assessment', 'Pending' => 'bg-blue-100 text-blue-800 border-blue-200',
                                default => 'bg-gray-100 text-gray-800 border-gray-200'
                            };
                        @endphp
                        
                        {{-- LIVE STATUS BADGE --}}
                        <span id="live-status-badge" class="mt-4 md:mt-0 px-6 py-2 rounded-full text-sm font-bold border uppercase tracking-wide shadow-sm {{ $statusColor }}">
                            {{ $application->status }}
                        </span>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="relative pt-1">
                        <div class="flex mb-2 items-center justify-between">
                            <div class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-indigo-600 bg-indigo-200">
                                Progress
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-semibold inline-block text-indigo-600">
                                    {{ $application->status == 'Enrolled' ? '100%' : ($application->status == 'Qualified' ? '90%' : '50%') }}
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-indigo-200">
                            @php
                                $progress = match($application->status) {
                                    'Enrolled' => 100,
                                    'Qualified' => 90,
                                    'For Assessment', 'Waitlisted', 'Pending' => 50,
                                    'Not Qualified' => 100,
                                    default => 25
                                };
                                $barColor = $application->status == 'Not Qualified' ? 'bg-red-500' : 'bg-indigo-500';
                            @endphp
                            <div style="width:{{ $progress }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $barColor }} transition-all duration-1000 ease-in-out"></div>
                        </div>
                    </div>

                    {{-- Remarks Section --}}
                    @if($application->assessment_score || $application->rejection_reason)
                        <div class="mt-4 p-4 rounded-lg border text-sm {{ $application->status == 'Not Qualified' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-yellow-50 border-yellow-200 text-yellow-800' }}">
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                
                {{-- Left Column: Profile Card --}}
                <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden">
                    <div class="bg-indigo-900 px-6 py-4 border-b border-indigo-800">
                        <h3 class="text-white font-bold text-lg flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Student Profile
                        </h3>
                    </div>
                    <div class="p-6 text-center">
                        <div class="inline-block relative">
                            @if(isset($application->uploaded_files['id_picture']))
                                <img src="{{ $application->uploaded_files['id_picture'] }}" class="h-32 w-32 rounded-full object-cover border-4 border-indigo-100 shadow-md mx-auto" alt="Student Photo">
                            @else
                                <div class="h-32 w-32 rounded-full bg-gray-200 flex items-center justify-center border-4 border-gray-100 mx-auto">
                                    <span class="text-gray-400 text-3xl font-bold">{{ substr($application->first_name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <h2 class="mt-4 text-xl font-bold text-gray-900">{{ $application->last_name }}, {{ $application->first_name }}</h2>
                        <p class="text-indigo-600 font-medium">{{ $application->middle_name }}</p>
                        
                        <div class="mt-6 border-t border-gray-100 pt-4 text-left">
                            <div class="mb-3">
                                <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Learner Ref No. (LRN)</span>
                                <p class="text-gray-900 font-mono font-bold">{{ $application->lrn }}</p>
                            </div>
                            <div class="mb-3">
                                <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Email Address</span>
                                <p class="text-gray-900 text-sm truncate">{{ $application->email_address }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Age / Gender</span>
                                <p class="text-gray-900 text-sm">{{ $application->age }} yrs old / {{ $application->gender }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- Academic Info --}}
                    <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-gray-800 font-bold text-lg flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                Academic & Sports Information
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                                    <label class="text-xs text-indigo-500 uppercase font-bold">Grade Level Applied</label>
                                    <p class="text-lg font-bold text-indigo-900">{{ $application->grade_level_applied }}</p>
                                </div>
                                <div class="bg-orange-50 p-4 rounded-lg border border-orange-100">
                                    <label class="text-xs text-orange-500 uppercase font-bold">Sport / Discipline</label>
                                    <p class="text-lg font-bold text-orange-900">{{ $application->sport }}</p>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-400 uppercase font-bold">Previous School</label>
                                    <p class="text-sm font-semibold text-gray-700">{{ $application->previous_school }}</p>
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">{{ $application->school_type }}</span>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-400 uppercase font-bold">Palarong Pambansa</label>
                                    <p class="text-sm font-semibold text-gray-700">
                                        {{ $application->has_palaro_participation ? 'Yes (' . $application->palaro_year . ')' : 'None' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submitted Files Table --}}
                    <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h3 class="text-gray-800 font-bold text-lg flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Submitted Requirements
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 uppercase">
                                        <th class="px-6 py-3 font-bold">Document Name</th>
                                        <th class="px-6 py-3 font-bold text-center">Status</th>
                                        <th class="px-6 py-3 font-bold text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @php
                                        // Define Display Names
                                        $summaryDisplayName = [
                                            'scholarship_form' => 'Scholarship Application Form',
                                            'student_profile' => 'Student-Athlete Profile Form',
                                            'id_picture' => '2x2 ID Picture',
                                            'sf10' => 'SF10 / Form 137',
                                            'report_card' => 'Report Card (SF9)',
                                            'adviser_reco' => 'Adviser Recommendation',
                                            'guardian_id' => 'Guardian Valid ID',
                                            'good_moral' => 'Good Moral Certificate',
                                            'psa_birth_cert' => 'PSA Birth Certificate', 
                                            'birth_cert' => 'PSA Birth Certificate', 
                                            'medical_cert' => 'Medical Certificate',
                                            'medical_clearance' => 'Medical Clearance Form',
                                            'coach_reco' => 'Coach Recommendation',
                                        ];
                                    @endphp

                                    @if(isset($application->uploaded_files))
                                        @foreach($application->uploaded_files as $key => $path)
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                                    {{ $summaryDisplayName[$key] ?? ucfirst(str_replace('_', ' ', $key)) }}
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                                        Uploaded
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <a href="{{ $path }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-xs font-bold uppercase hover:underline">
                                                        View File
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-center text-gray-500 italic">No files uploaded yet.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            @if($application->status == 'Qualified')
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-8 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4 w-full">
                            <h3 class="text-xl font-bold text-blue-900">Next Step: Submit Enrollment Requirements</h3>
                            <p class="text-sm text-blue-700 mt-1 mb-6">
                                Congratulations! Please upload the remaining digital copies to finalize your enrollment.
                            </p>

                            {{-- UPDATED REQUIREMENTS FORM --}}
                            <form id="uploadForm" action="{{ route('applicant.submit_requirements') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl border border-blue-100 shadow-sm">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @php
                                        // Uploaded Files List
                                        $uploaded = $application->uploaded_files ?? [];
                                        
                                         // Required Fields Keys
                                        $requiredFields = ['sf10', 'good_moral', 'psa_birth_cert', 'medical_cert', 'coach_reco'];
                                        $allFilesUploaded = true; // Checker flag for completion
                                    @endphp

                                    @foreach($requiredFields as $field)
                                        @php
                                            // CHECK IF UPLOADED
                                            $isUploaded = isset($uploaded[$field]) && !empty($uploaded[$field]);
                                            if (!$isUploaded) { $allFilesUploaded = false; } // If one is missing, flag becomes false
                                            
                                            // Get Label
                                            $label = $summaryDisplayName[$field] ?? strtoupper(str_replace('_', ' ', $field));
                                            
                                            // Get File Path
                                            $currentPath = $uploaded[$field] ?? null;
                                        @endphp

                                        <div class="border-2 rounded-lg p-4 transition-all {{ $isUploaded ? 'bg-green-50 border-green-400' : 'bg-red-50 border-red-300 shadow-md' }}">
                                            
                                            <div class="flex justify-between items-start mb-3">
                                                <label class="block text-sm font-extrabold {{ $isUploaded ? 'text-green-800' : 'text-red-800' }}">
                                                    {{ $label }}
                                                </label>
                                                
                                                @if($isUploaded)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-200 text-green-900 border border-green-300">
                                                        ✓ SUBMITTED
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-200 text-red-900 border border-red-300 animate-pulse">
                                                        PENDING
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            @if($isUploaded)
                                                <div class="flex items-center justify-between bg-white p-3 rounded border border-green-200 shadow-sm">
                                                    <span class="text-xs text-green-700 font-bold italic">
                                                        File has been uploaded.
                                                    </span>
                                                    <a href="{{ $currentPath }}" target="_blank" class="text-xs bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded font-bold transition flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                        VIEW
                                                    </a>
                                                </div>
                                            @else
                                                <div class="bg-white p-2 rounded border border-red-200">
                                                    <p class="text-[10px] text-red-500 font-bold mb-1 uppercase tracking-wide">Select file to upload:</p>
                                                    <input type="file" name="{{ $field }}" required
                                                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-red-100 file:text-red-700 hover:file:bg-red-200 cursor-pointer">
                                                </div>
                                                @error($field)
                                                    <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                                                @enderror
                                            @endif

                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-8">
                                    @if($allFilesUploaded)
                                        {{-- SUCCESS & NEXT STEPS CARD (Kung tapos na lahat) --}}
                                        <div class="bg-green-50 border-2 border-green-400 rounded-xl p-6 text-center shadow-md">
                                            
                                            <div class="flex justify-center mb-4">
                                                <div class="h-16 w-16 bg-green-100 rounded-full flex items-center justify-center">
                                                    <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                            </div>

                                            <h3 class="text-xl font-extrabold text-green-800 uppercase tracking-wide mb-2">
                                                Digital Submission Complete!
                                            </h3>
                                            
                                            <p class="text-green-700 font-medium mb-6">
                                                You have successfully uploaded all the required documents.
                                            </p>

                                            <div class="bg-white rounded-lg border border-green-200 p-5 text-left shadow-sm max-w-lg mx-auto">
                                                <h4 class="text-sm font-bold text-gray-800 uppercase mb-3 border-b pb-2 flex items-center">
                                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    Final Step: Official Enrollment
                                                </h4>
                                                <ul class="space-y-3 text-sm text-gray-600">
                                                    <li class="flex items-start">
                                                        <span class="mr-2 text-green-600 font-bold">1.</span>
                                                        <span>Please proceed to the <strong>School Registrar's Office</strong>.</span>
                                                    </li>
                                                    <li class="flex items-start">
                                                        <span class="mr-2 text-green-600 font-bold">2.</span>
                                                        <span>Bring the <strong class="text-red-600 underline">ORIGINAL HARD COPIES</strong> of all the uploaded documents for verification.</span>
                                                    </li>
                                                    <li class="flex items-start">
                                                        <span class="mr-2 text-green-600 font-bold">3.</span>
                                                        <span>Submit your documents to the <strong>Office of the Registrar</strong> to finalize your enrollment.</span>
                                                    </li>
                                                </ul>
                                            </div>

                                        </div>
                                    @else
                                        {{-- Kung may kulang pa, ipakita ang submit button --}}
                                        <div class="flex justify-end">
                                            <button type="submit" id="submitBtn" class="bg-indigo-700 hover:bg-indigo-800 text-white px-8 py-3 rounded-lg font-bold shadow-md transition transform hover:-translate-y-0.5 flex items-center">
                                                <span id="btnText">Upload Selected Files</span>
                                                <svg id="btnSpinner" class="animate-spin ml-2 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

        @else
            {{-- No Application Found State --}}
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100 text-center p-16">
                <img src="{{ asset('images/nas/nas-logo-spotlight.jpg') }}" class="h-20 w-20 mx-auto mb-6 opacity-80 grayscale hover:grayscale-0 transition">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">No Application Found</h2>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">It looks like you haven't started your admission process yet. Click the button below to begin.</p>
                <a href="{{ route('applicant.create') }}" class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-bold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg transform transition hover:-translate-y-1">
                    START APPLICATION
                </a>
            </div>
        @endif

    </div>

    {{-- 👇 LIVE UPDATE SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. LIVE UPDATE (POLLING)
            setInterval(function() {
                updateDashboard();
            }, 5000); // 5 seconds interval

            // 2. Loading Spinner Logic
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

            // 3. Auto-hide success alert
            const alert = document.getElementById('success-alert');
            if (alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    setTimeout(function() { alert.remove(); }, 500);
                }, 5000);
            }
        });

        // Fetch new content
        function updateDashboard() {
            const url = window.location.href;

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Replace main content if changed
                    const newContent = doc.getElementById('dashboard-content').innerHTML;
                    const currentContent = document.getElementById('dashboard-content');
                    
                    // Simple check to avoid replacing if user is interacting (optional improvement: check active element)
                    // For now, we update the dashboard-content directly.
                    // To prevent flicker, we could target specific parts, but replacing the wrapper ensures all conditional logic (if status changes to Qualified) renders correctly.
                    
                    if(document.activeElement.tagName !== "INPUT" && document.activeElement.tagName !== "SELECT") {
                         // Only replace if content is different to avoid cursor jump issues if inputting
                         // Since this page is mostly read-only except file upload, this is safe.
                         if(currentContent.innerHTML !== newContent) {
                             currentContent.innerHTML = newContent;
                         }
                    }
                })
                .catch(error => console.error('Error updating dashboard:', error));
        }
    </script>
</x-applicant-layout>