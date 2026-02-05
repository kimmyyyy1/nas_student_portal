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
                                'Qualified', 'Endorsed for Enrollment' => 'bg-green-100 text-green-800 border-green-200',
                                'Not Qualified' => 'bg-red-100 text-red-800 border-red-200',
                                'Waitlisted' => 'bg-orange-100 text-orange-800 border-orange-200',
                                'For 2nd Level Assessment' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                                'With Complete Requirements & for 1st Level Assessment', 'For 1st Level Assessment' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'With Pending Requirements' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                default => 'bg-gray-100 text-gray-800 border-gray-200'
                            };
                        @endphp
                        <span id="live-status-badge" class="px-3 py-1.5 rounded-full text-xs font-bold border uppercase tracking-wide shadow-sm {{ $statusColor }} self-start md:self-center">{{ $application->displayStatus }}</span>
                    </div>

                    {{-- PROGRESS BAR --}}
                    <div class="relative pt-1">
                        <div class="flex mb-2 items-center justify-between">
                            <div class="text-[10px] font-semibold inline-block py-1 px-2 uppercase rounded-full text-indigo-600 bg-indigo-200">Progress</div>
                            @php
                                $progressPercent = match($application->status) {
                                    'Endorsed for Enrollment' => 100,
                                    'Qualified' => 75,
                                    'For 2nd Level Assessment' => 50,
                                    'With Complete Requirements & for 1st Level Assessment', 'For 1st Level Assessment' => 25,
                                    'With Pending Requirements' => 40,
                                    'Waitlisted' => 50,
                                    'Not Qualified' => 100,
                                    default => 0,
                                };
                            @endphp
                            <div class="text-right"><span class="text-[10px] font-semibold inline-block text-indigo-600">{{ $progressPercent }}%</span></div>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-indigo-200">
                            @php
                                $barColor = $application->status == 'Not Qualified' ? 'bg-red-500' : 'bg-indigo-500';
                            @endphp
                            <div style="width:{{ $progressPercent }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $barColor }} transition-all duration-1000 ease-in-out"></div>
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
                                Edit Profile
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- INFO GRID --}}
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

            {{-- 4. REQUIREMENTS UPLOAD (CONDITIONAL: LEVEL 2 ONLY) --}}
            @if(in_array($application->status, ['For 2nd Level Assessment', 'With Pending Requirements']))
                <div class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden mb-8">
                    <div class="bg-indigo-900 px-5 py-4 border-b border-indigo-800 flex justify-between items-center">
                        <div>
                            <h3 class="text-white font-bold text-lg flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                2nd Level Assessment: Requirements Submission
                            </h3>
                            <p class="text-indigo-200 text-xs mt-1">Please upload the required documents below. Clear scans or photos are required.</p>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        @if ($errors->has('msg'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                                <span class="block sm:inline">{{ $errors->first('msg') }}</span>
                            </div>
                        @endif

                        <form id="uploadForm" action="{{ route('applicant.submit-requirements') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @php
                                    $requirements = [
                                        'birth_cert' => 'PSA Birth Certificate',
                                        'report_card' => 'Report Card (SF9)',
                                        'medical_clearance' => 'Medical Clearance',
                                        'guardian_id' => 'Guardian\'s Valid ID',
                                        'scholarship_form' => 'Scholarship App Form',
                                        'student_profile' => 'Student-Athlete Profile Form',
                                        'coach_reco' => 'Coach Recommendation (Optional)',
                                        'adviser_reco' => 'Adviser Recommendation (Optional)',
                                        'kukkiwon_cert' => 'Kukkiwon Cert (If Taekwondo)',
                                        'ip_cert' => 'IP Certification (If IP)',
                                        'pwd_id' => 'PWD ID (If PWD)',
                                        '4ps_id' => '4Ps ID (If 4Ps)'
                                    ];

                                    $uploadedFiles = $application->uploaded_files ?? [];
                                    $statuses = $application->document_statuses ?? [];
                                    $remarks = $application->document_remarks ?? [];
                                @endphp

                                @foreach($requirements as $key => $label)
                                    @php
                                        // Skip irrelevant requirements
                                        if ($key == 'kukkiwon_cert' && $application->sport !== 'Taekwondo') continue;
                                        if ($key == 'ip_cert' && !$application->is_ip) continue;
                                        if ($key == 'pwd_id' && !$application->is_pwd) continue;
                                        if ($key == '4ps_id' && !$application->is_4ps) continue;

                                        $isUploaded = isset($uploadedFiles[$key]) && !empty($uploadedFiles[$key]);
                                        $status = $statuses[$key] ?? 'pending';
                                        $remark = $remarks[$key] ?? null;
                                    @endphp

                                    <div class="border rounded-lg p-4 {{ $remark ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">
                                        <label class="block text-sm font-bold text-gray-700 mb-2 truncate" title="{{ $label }}">
                                            {{ $label }}
                                        </label>

                                        {{-- STATUS INDICATOR --}}
                                        <div class="mb-3 flex items-center justify-between">
                                            @if($isUploaded)
                                                <span class="text-xs font-bold text-green-600 flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    Submitted
                                                </span>
                                                <a href="{{ route('applicant.view-file', ['id' => $application->id, 'type' => $key]) }}" target="_blank" class="text-xs text-indigo-600 underline hover:text-indigo-800">View</a>
                                            @else
                                                <span class="text-xs font-bold text-gray-400">Missing</span>
                                            @endif
                                        </div>

                                        {{-- REMARKS (IF ANY) --}}
                                        @if($remark)
                                            <div class="text-xs text-red-600 bg-red-100 p-2 rounded mb-2">
                                                <strong>Admin Remark:</strong> {{ $remark }}
                                            </div>
                                        @endif

                                        {{-- FILE INPUT --}}
                                        <input type="file" name="files[{{ $key }}]" 
                                               class="block w-full text-xs text-slate-500
                                                      file:mr-2 file:py-2 file:px-4
                                                      file:rounded-full file:border-0
                                                      file:text-xs file:font-semibold
                                                      file:bg-indigo-50 file:text-indigo-700
                                                      hover:file:bg-indigo-100 mb-1"
                                               accept=".jpg,.jpeg,.png,.pdf">
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6 border-t pt-4 flex justify-end">
                                <button type="submit" id="submitBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg shadow transition flex items-center">
                                    <svg id="btnSpinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span id="btnText">Submit Documents</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            @elseif(in_array($application->status, ['With Complete Requirements & for 1st Level Assessment', 'For 1st Level Assessment']))
                {{-- MESSAGE IF LEVEL 1 (UNDER REVIEW) --}}
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-8 text-center mb-8">
                    <svg class="w-12 h-12 text-blue-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-lg font-bold text-blue-900 uppercase tracking-wide">Application Under Review (Level 1)</h3>
                    <p class="text-sm text-blue-700 mt-2 max-w-2xl mx-auto">
                        Your Student Profile is currently being reviewed by the Admissions Officer. <br>
                        Once your profile is approved, the status will change to <strong>"For 2nd Level Assessment"</strong>, and the <strong>requirements upload section</strong> will appear here automatically.
                    </p>
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

            if (!form) {
                // Auto-refresh if waiting for review (Level 1)
                setInterval(function() { updateDashboard(); }, 10000); // 10 seconds check
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