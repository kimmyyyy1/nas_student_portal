<x-app-layout>
    
    <x-slot name="header">
        <div class="flex justify-between items-center no-print">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Review Admission Application') }}
            </h2>
            <div class="flex gap-2">
                <button onclick="window.print()" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded inline-flex items-center shadow transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    PRINT
                </button>
                <a href="{{ route('admission.pdf', $application->id) }}" target="_blank" class="bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-4 rounded inline-flex items-center shadow transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    DOWNLOAD PDF
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        .check-box { width: 16px; height: 16px; border: 1px solid #6b7280; display: flex; align-items: center; justify-content: center; margin-right: 6px; background-color: #fff; }
        
        @media print {
            @page { margin: 0.5cm; size: auto; }
            html, body { height: 100%; margin: 0 !important; padding: 0 !important; overflow: visible !important; background: white !important; }
            nav, header, footer, .no-print, .shadow-xl, .border-t-4, x-app-layout, .min-h-screen { display: none !important; }
            body * { visibility: hidden; }
            #print-area, #print-area * { visibility: visible !important; }
            #print-area { position: absolute !important; left: 0 !important; top: 0 !important; width: 100% !important; margin: 0 !important; padding: 0 !important; background: white !important; border: none !important; box-shadow: none !important; }
            .md\:col-span-3 { width: 100% !important; display: block !important; }
            .grid { display: block !important; }
            .md\:flex-row { display: flex !important; flex-direction: row !important; } 
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            .check-box { border: 1px solid #000 !important; }
            .admin-input { display: none !important; }
            .pencil-icon { display: none !important; }
            .print-status-hide { display: none; } 
            .no-print { display: none !important; }
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm no-print">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                {{-- MAIN CONTENT --}}
                <div id="print-area" class="md:col-span-3 bg-white shadow-xl rounded-lg overflow-hidden border border-gray-300 p-8 md:p-10 relative">
                        
                    {{-- HEADER: UPDATED LOGOS & TEXT --}}
                    <div class="flex flex-col md:flex-row items-center justify-between mb-6 pb-4 border-b-2 border-black gap-4">
                        
                        {{-- Left Logo: NAS (Updated to nas-logo-sidebar.png) --}}
                        <div class="w-24 flex-shrink-0 flex justify-center">
                            <img src="{{ asset('images/nas/nas-logo-sidebar.png') }}" alt="NAS Logo" class="h-24 w-auto object-contain">
                        </div>

                        {{-- Center Text --}}
                        <div class="text-center flex-1">
                            <h1 class="text-lg sm:text-xl font-black text-gray-900 uppercase leading-tight">
                                NAS Annual Search for Competent, Exceptional, Notable, and Talented Student-Athlete Scholars
                            </h1>
                            <h2 class="text-base sm:text-lg font-bold text-gray-700 uppercase tracking-widest mt-1">
                                (NASCENT SAS)
                            </h2>
                            <p class="text-xs italic text-gray-600 mt-2">New Clark City, Capas, Tarlac</p>
                        </div>

                        {{-- Right Logo: NASCENT SAS --}}
                        <div class="w-24 flex-shrink-0 flex justify-center">
                            <img src="{{ asset('images/nas/NASCENT SAS.png') }}" alt="NASCENT SAS Logo" class="h-24 w-auto object-contain">
                        </div>

                    </div>

                    {{-- I. APPLICANT INFORMATION --}}
                    <div class="mb-6">
                        <div class="bg-gray-200 border border-gray-400 px-3 py-1 mb-4">
                            <h3 class="text-sm font-bold text-gray-800 uppercase">I. Personal Information</h3>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-8 items-start">
                            {{-- LEFT: Text Details --}}
                            <div class="flex-1 w-full space-y-4">
                                <div class="grid grid-cols-1">
                                    <div>
                                        <label class="block text-[10px] text-gray-500 uppercase font-bold">Learner Reference Number (LRN)</label>
                                        <div class="text-lg font-bold text-gray-900 font-mono border-b border-gray-300">{{ $application->lrn }}</div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1">
                                    <div>
                                        <label class="block text-[10px] text-gray-500 uppercase font-bold">Name</label>
                                        <div class="text-xl font-extrabold text-gray-900 uppercase border-b border-gray-300">
                                            {{ $application->last_name }}, {{ $application->first_name }} {{ $application->middle_name == 'N/A' ? '' : $application->middle_name }}
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-[10px] text-gray-500 uppercase font-bold">Date of Birth</label>
                                        <div class="text-sm font-bold text-gray-900 border-b border-gray-300">{{ \Carbon\Carbon::parse($application->date_of_birth)->format('M d, Y') }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-gray-500 uppercase font-bold">Age</label>
                                        <div class="text-sm font-bold text-gray-900 border-b border-gray-300">{{ $application->age }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-gray-500 uppercase font-bold">Sex</label>
                                        <div class="text-sm font-bold text-gray-900 border-b border-gray-300">{{ $application->gender }}</div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] text-gray-500 uppercase font-bold">Place of Birth</label>
                                        <div class="text-sm font-bold text-gray-900 border-b border-gray-300">{{ $application->birthplace }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-gray-500 uppercase font-bold">Religion</label>
                                        <div class="text-sm font-bold text-gray-900 border-b border-gray-300">{{ $application->religion }}</div>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] text-gray-500 uppercase font-bold">Email Address</label>
                                        <div class="text-sm font-bold text-gray-900 border-b border-gray-300">{{ $application->email_address }}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- RIGHT: 2x2 Photo --}}
                            <div class="w-40 flex-shrink-0 flex flex-col items-center">
                                <label class="block text-[10px] text-gray-500 uppercase font-bold mb-1 text-center">2x2 Photo</label>
                                <div class="w-40 h-40 bg-gray-100 border border-gray-400 flex items-center justify-center overflow-hidden relative group">
                                    @if(isset($application->uploaded_files['id_picture']))
                                        <img src="{{ $application->uploaded_files['id_picture'] }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-gray-400 text-xs font-bold">NO PHOTO</span>
                                    @endif
                                </div>
                            </div>
                        </div> 
                        <div class="mt-4 pt-2">
                            <label class="block text-[10px] text-gray-500 uppercase font-bold mb-1">Permanent Address</label>
                            <div class="text-sm font-bold text-gray-900 border-b border-gray-300 pb-1">{{ $application->street_address }}, {{ $application->barangay }}, {{ $application->municipality_city }}, {{ $application->province }}</div>
                        </div>
                    </div>

                    {{-- II. ACADEMIC & SPORTS PROFILE --}}
                    <div class="mb-6">
                        <div class="bg-gray-200 border border-gray-400 px-3 py-1 mb-4">
                            <h3 class="text-sm font-bold text-gray-800 uppercase">II. Academic & Sports Profile</h3>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm">
                            <div class="col-span-2"><label class="block text-[10px] text-gray-500 uppercase font-bold">Last School Attended</label><div class="font-bold text-gray-900 border-b border-gray-300">{{ $application->previous_school }} ({{ $application->school_type }})</div></div>
                            <div><label class="block text-[10px] text-gray-500 uppercase font-bold">Applied Grade</label><div class="font-bold text-gray-900 border-b border-gray-300">{{ $application->grade_level_applied }}</div></div>
                            <div><label class="block text-[10px] text-gray-500 uppercase font-bold">Sport</label><div class="font-bold text-gray-900 border-b border-gray-300">{{ $application->sport }}</div></div>
                        </div>
                    </div>

                    {{-- III. PARENTS & GUARDIAN --}}
                    <div class="mb-6">
                        <div class="bg-gray-200 border border-gray-400 px-3 py-1 mb-4">
                            <h3 class="text-sm font-bold text-gray-800 uppercase">III. PARENTS' & DESIGNATED GUARDIAN'S INFORMATION</h3>
                        </div>
                        <div class="grid grid-cols-2 gap-6 text-sm">
                            <div><label class="block text-[10px] text-gray-500 uppercase font-bold">Designated Guardian</label><div class="font-bold text-gray-900 border-b border-gray-300">{{ $application->guardian_name }}</div></div>
                            <div><label class="block text-[10px] text-gray-500 uppercase font-bold">Relationship</label><div class="font-bold text-gray-900 border-b border-gray-300">{{ $application->guardian_relationship }}</div></div>
                            <div><label class="block text-[10px] text-gray-500 uppercase font-bold">Contact Number</label><div class="font-bold text-gray-900 border-b border-gray-300">{{ $application->guardian_contact }}</div></div>
                            <div><label class="block text-[10px] text-gray-500 uppercase font-bold">Email Address</label><div class="font-bold text-gray-900 border-b border-gray-300">{{ $application->guardian_email }}</div></div>
                        </div>
                    </div>

                    {{-- IV. SUBMITTED DOCUMENTS (CONDITIONAL DISPLAY) --}}
                    <div>
                        <div class="bg-gray-200 border border-gray-400 px-3 py-1 mb-4">
                            <h3 class="text-sm font-bold text-gray-800 uppercase">IV. Submitted Documents & Remarks</h3>
                        </div>
                        
                        {{-- HIDE TABLE IF 1ST LEVEL ASSESSMENT --}}
                        @if(in_array($application->status, ['For 1st Level Assessment', 'With Complete Requirements & for 1st Level Assessment']))
                            
                            <div class="p-8 text-center bg-gray-50 border border-gray-200 rounded-lg flex flex-col items-center justify-center">
                                <div class="bg-blue-100 p-3 rounded-full mb-3">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <h4 class="text-sm font-bold text-gray-800 uppercase">1st Level Assessment in Progress</h4>
                                <p class="text-xs text-gray-500 mt-1 max-w-sm">The applicant is currently in the 1st Level Assessment phase (Profile Review). Documentary requirements will be submitted during the <strong>2nd Level Assessment</strong>.</p>
                            </div>

                        @else
                            {{-- SHOW TABLE IF 2ND LEVEL OR HIGHER --}}
                            
                            <form action="{{ route('admission.process', $application->id) }}" method="POST">
                                @csrf @method('PATCH')
                                
                                {{-- Preserve Main Status --}}
                                <input type="hidden" name="status" value="{{ $application->status }}">
                                <input type="hidden" name="assessment_score" value="{{ $application->assessment_score }}">

                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse min-w-[600px]">
                                        <thead>
                                            <tr class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 uppercase">
                                                <th class="px-4 py-3 font-bold w-1/3">Document Name</th>
                                                <th class="px-4 py-3 font-bold text-center">Status</th>
                                                <th class="px-4 py-3 font-bold text-center">View</th>
                                                <th class="px-4 py-3 font-bold w-1/3 no-print">Admin Remarks</th>
                                            </tr>
                                        </thead>
                                        
                                        {{-- FIXED TBODY LOGIC HERE --}}
                                        <tbody class="divide-y divide-gray-100 text-xs">
                                            @php
                                                $files = $application->uploaded_files ?? [];
                                                $remarks = $application->document_remarks ?? []; 
                                                $fileTimestamps = $application->file_timestamps ?? [];
                                                
                                                // ADMIN CHECK DATE (Convert to Timestamp for comparison)
                                                $adminCheckDate = $application->date_checked ? \Carbon\Carbon::parse($application->date_checked)->setTimezone('Asia/Manila') : null;
                                                $checkTs = $adminCheckDate ? $adminCheckDate->timestamp : 0;

                                                $docs = [
                                                    'id_picture' => '2x2 ID Picture', 
                                                    'scholarship_form' => 'Scholarship Application Form',
                                                    'student_profile' => 'Student-Athlete’s Profile Form',
                                                    'medical_clearance' => 'Preparticipation Physical Evaluation Clearance Form',
                                                    'coach_reco' => 'Coach’s Recommendation Form',
                                                    'adviser_reco' => 'Adviser’s Recommendation Form',
                                                    'birth_cert' => 'PSA Birth Certificate',
                                                    'report_card' => 'Report Card (SF9)',
                                                    'guardian_id' => 'Designated Guardian’s Valid ID',
                                                    'kukkiwon_cert' => 'Kukkiwon Certificate',
                                                    'ip_cert' => 'IP Certification',
                                                    'pwd_id' => 'PWD ID',
                                                    '4ps_id' => '4Ps ID/Certification'
                                                ];
                                            @endphp

                                            @foreach($docs as $key => $label)
                                                @php
                                                    $isUploaded = isset($files[$key]) && !empty($files[$key]);
                                                    
                                                    // File Timestamp (Convert to Timestamp)
                                                    $fileDate = isset($fileTimestamps[$key]) ? \Carbon\Carbon::parse($fileTimestamps[$key])->setTimezone('Asia/Manila') : null;
                                                    $fileTs = $fileDate ? $fileDate->timestamp : 0;
                                                    
                                                    $remark = isset($remarks[$key]) ? trim($remarks[$key]) : '';
                                                    $hasRemark = !empty($remark);

                                                    // DEFAULT STATUS
                                                    $statusText = 'MISSING';
                                                    $badgeClass = 'bg-red-100 text-red-800 border-red-200';
                                                    $showDate = false;

                                                    // --- LOGIC ---
                                                    if ($isUploaded) {
                                                        // 1. Check NEW UPDATE (Buffer +15 seconds)
                                                        $isNewUpdate = ($checkTs > 0 && $fileTs > ($checkTs + 15));

                                                        if ($isNewUpdate) {
                                                            $statusText = 'NEW UPDATE';
                                                            $badgeClass = 'bg-blue-100 text-blue-800 border-blue-200 shadow-sm animate-pulse';
                                                            $showDate = true;
                                                        } 
                                                        elseif ($hasRemark) {
                                                            $statusText = 'NEEDS UPDATE';
                                                            $badgeClass = 'bg-red-100 text-red-800 border-red-200';
                                                        } 
                                                        else {
                                                            $statusText = 'SUBMITTED';
                                                            $badgeClass = 'bg-green-100 text-green-800 border-green-200';
                                                        }
                                                    } else {
                                                        // Handle Optional
                                                        $isOptional = in_array($key, ['coach_reco', 'adviser_reco']);
                                                        if($isOptional) {
                                                            $statusText = 'OPTIONAL';
                                                            $badgeClass = 'bg-gray-100 text-gray-500 border-gray-200';
                                                        }
                                                    }

                                                    $isSpecial = in_array($key, ['kukkiwon_cert', 'ip_cert', 'pwd_id', '4ps_id']);
                                                    if ($isSpecial && !$isUploaded) { continue; }
                                                    $isOptionalDoc = in_array($key, ['coach_reco', 'adviser_reco']);
                                                @endphp

                                                <tr class="hover:bg-gray-50 transition {{ $statusText == 'NEEDS UPDATE' ? 'bg-red-50' : '' }}">
                                                    <td class="px-4 py-3 font-bold text-gray-900 align-middle">
                                                        {{ $label }}
                                                        @if($isOptionalDoc) <span class="text-gray-400 font-normal ml-1 text-[10px]">(Optional)</span> @endif
                                                    </td>
                                                    
                                                    <td class="px-4 py-3 text-center align-middle">
                                                        <div class="flex flex-col items-center">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeClass }}">
                                                                {{ $statusText }}
                                                            </span>
                                                            @if($showDate && $fileDate)
                                                                <span class="text-[9px] text-gray-500 mt-1 font-mono">{{ $fileDate->format('M d, h:i A') }}</span>
                                                            @endif
                                                        </div>
                                                    </td>

                                                    <td class="px-4 py-3 text-center align-middle">
                                                        @if($isUploaded)
                                                            <a href="{{ $files[$key] }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-bold underline no-print text-xs">VIEW</a>
                                                        @else
                                                            <span class="text-gray-400">-</span>
                                                        @endif
                                                    </td>

                                                    <td class="px-4 py-2 no-print relative group align-middle">
                                                        <div class="relative">
                                                            <input type="text" 
                                                                   name="document_remarks[{{ $key }}]" 
                                                                   value="{{ $remark }}"
                                                                   class="w-full border border-gray-300 rounded text-xs px-3 py-2 pr-8 focus:ring-indigo-500 focus:border-indigo-500 admin-input shadow-sm transition-colors group-hover:border-indigo-300 {{ $statusText == 'NEEDS UPDATE' ? 'bg-red-50 border-red-300 text-red-900' : '' }}" 
                                                                   placeholder="{{ $statusText == 'NEW UPDATE' ? 'File updated. Review & clear remark.' : 'Add remarks here...' }}">
                                                            
                                                            <svg class="w-4 h-4 text-gray-400 absolute right-2 top-2.5 pencil-icon pointer-events-none group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                            </svg>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-4 text-right no-print">
                                    <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded shadow-md transition transform hover:scale-105 text-sm flex items-center ml-auto">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                        SAVE DOCUMENT REMARKS
                                    </button>
                                    <p class="text-[10px] text-gray-500 mt-1 italic">Clicking save marks current files as "Checked". Only future uploads will show as "Updated".</p>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- SIDEBAR --}}
                <div class="md:col-span-1 no-print">
                    <div class="bg-white shadow-xl rounded-lg border-t-4 border-indigo-600 sticky top-6">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Application Status</h3>
                            <form action="{{ route('admission.process', $application->id) }}" method="POST">
                                @csrf @method('PATCH')
                                
                                @if($application->document_remarks)
                                    @foreach($application->document_remarks as $k => $v) <input type="hidden" name="document_remarks[{{ $k }}]" value="{{ $v }}"> @endforeach
                                @endif

                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status</label>
                                    <select name="status" id="status" class="w-full border-gray-300 rounded text-sm font-bold text-gray-800">
                                        <option value="With Complete Requirements & for 1st Level Assessment" {{ $application->status == 'With Complete Requirements & for 1st Level Assessment' ? 'selected' : '' }}>For Assessment (1st Level)</option>
                                        <option value="For 2nd Level Assessment" {{ $application->status == 'For 2nd Level Assessment' ? 'selected' : '' }}>For Assessment (2nd Level)</option>
                                        <option value="Qualified" {{ $application->status == 'Qualified' ? 'selected' : '' }}>Qualified</option>
                                        <option value="Waitlisted" {{ $application->status == 'Waitlisted' ? 'selected' : '' }}>Waitlisted</option>
                                        <option value="Not Qualified" {{ in_array($application->status, ['Not Qualified', 'Rejected', 'Failed']) ? 'selected' : '' }}>Not Qualified</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">General Assessment</label>
                                    <textarea name="assessment_score" rows="3" class="w-full border-gray-300 rounded text-sm">{{ $application->assessment_score }}</textarea>
                                </div>
                                <div class="mb-4 hidden" id="rejection-div">
                                    <label class="block text-xs font-bold text-red-600 uppercase mb-1">Reason for Rejection</label>
                                    <textarea name="rejection_reason" rows="2" class="w-full border-red-300 bg-red-50 rounded text-sm text-red-700">{{ $application->rejection_reason }}</textarea>
                                </div>
                                <button type="submit" class="w-full bg-indigo-700 hover:bg-indigo-800 text-white font-bold py-3 rounded shadow">UPDATE STATUS</button>
                            </form>
                        </div>
                        @if($application->status == 'Qualified')
                            <div class="bg-green-100 p-4 text-center border-t border-green-200">
                                <p class="text-xs text-green-800 mb-2">Applicant is qualified.</p>
                                <a href="{{ route('official-enrollment.show', $application->id) }}" class="block w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 rounded shadow text-sm">PROCEED TO ENROLLMENT</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const statusSelect = document.getElementById('status');
        const rejectionDiv = document.getElementById('rejection-div');
        function toggleReject() {
            const rejectedStatuses = ['Not Qualified', 'Rejected', 'Failed'];
            if(rejectedStatuses.includes(statusSelect.value)) { rejectionDiv.classList.remove('hidden'); } else { rejectionDiv.classList.add('hidden'); }
        }
        statusSelect.addEventListener('change', toggleReject);
        toggleReject(); 
    </script>
</x-app-layout>