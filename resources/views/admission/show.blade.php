<x-app-layout>
    
    <x-slot name="header">
        <div class="flex justify-between items-center no-print">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Review Admission Application') }}
            </h2>
            <a href="{{ route('admission.pdf', $application->id) }}" target="_blank" class="bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-4 rounded inline-flex items-center shadow transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                DOWNLOAD PDF
            </a>
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
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            .check-box { border: 1px solid #000 !important; }
            .admin-input { display: none !important; }
            .pencil-icon { display: none !important; }
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

                {{-- MAIN CONTENT (PRINTABLE) --}}
                <div id="print-area" class="md:col-span-3 bg-white shadow-xl rounded-lg overflow-hidden border border-gray-300 p-8 md:p-10 relative">
                        
                    <div class="flex flex-col items-center justify-center text-center mb-6 pb-4 border-b-2 border-black">
                        <img src="{{ asset('images/nas/nas-logo-sidebar.png') }}" alt="NAS Logo" class="h-20 w-auto mb-2 object-contain">
                        <h1 class="text-2xl font-black text-gray-900 uppercase tracking-widest">National Academy of Sports</h1>
                        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Student-Athlete Admission Application Form</h2>
                        <p class="text-xs italic text-gray-600">New Clark City, Capas, Tarlac</p>
                    </div>

                    {{-- I. APPLICANT INFORMATION --}}
                    <div class="mb-6">
                        <div class="bg-gray-200 border border-gray-400 px-3 py-1 mb-4">
                            <h3 class="text-sm font-bold text-gray-800 uppercase">I. Applicant Information</h3>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-6">
                            {{-- ID Picture Preview (Top Section) --}}
                            <div class="w-40 h-40 bg-gray-100 border border-gray-400 flex-shrink-0 flex items-center justify-center overflow-hidden relative group">
                                @if(isset($application->uploaded_files['id_picture']))
                                    <img src="{{ $application->uploaded_files['id_picture'] }}" class="w-full h-full object-cover">
                                    
                                    {{-- CHECK SPECIFIC TIMESTAMP FOR ID PICTURE (Top Badge) --}}
                                    @php
                                        $fileTimestamps = $application->file_timestamps ?? [];
                                        $idPicTime = isset($fileTimestamps['id_picture']) ? \Carbon\Carbon::parse($fileTimestamps['id_picture']) : null;
                                        $lastCheck = $application->date_checked;
                                        $isIdUpdated = $idPicTime && $lastCheck && $idPicTime->gt($lastCheck);
                                    @endphp

                                    @if($isIdUpdated)
                                        <div class="absolute bottom-0 left-0 w-full bg-blue-600 text-white text-[10px] text-center py-1 font-bold no-print">
                                            UPDATED
                                        </div>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-xs font-bold">2x2 PHOTO</span>
                                @endif
                            </div>

                            <div class="flex-grow space-y-3">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] text-gray-500 uppercase font-bold">Name</label>
                                        <div class="text-xl font-extrabold text-gray-900 uppercase border-b border-gray-300">
                                            {{ $application->last_name }}, {{ $application->first_name }} {{ $application->middle_name }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-gray-500 uppercase font-bold">LRN</label>
                                        <div class="text-lg font-bold text-gray-900 font-mono border-b border-gray-300">
                                            {{ $application->lrn }}
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div><label class="block text-[10px] text-gray-500 uppercase font-bold">Birthdate</label><div class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($application->date_of_birth)->format('M d, Y') }}</div></div>
                                    <div><label class="block text-[10px] text-gray-500 uppercase font-bold">Age / Sex</label><div class="text-sm font-bold text-gray-900">{{ $application->age }} / {{ $application->gender }}</div></div>
                                    <div><label class="block text-[10px] text-gray-500 uppercase font-bold">Religion</label><div class="text-sm font-bold text-gray-900">{{ $application->religion }}</div></div>
                                    <div><label class="block text-[10px] text-gray-500 uppercase font-bold">Birthplace</label><div class="text-sm font-bold text-gray-900">{{ $application->birthplace }}</div></div>
                                </div>

                                <div><label class="block text-[10px] text-gray-500 uppercase font-bold">Email Address</label><div class="text-sm font-bold text-gray-900 border-b border-gray-300">{{ $application->email_address }}</div></div>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="mt-4 pt-2">
                            <label class="block text-[10px] text-gray-500 uppercase font-bold mb-1">Permanent Address</label>
                            <div class="grid grid-cols-4 gap-4 mb-2 text-xs border-b border-gray-300 pb-1">
                                <div><span class="text-gray-500">Region:</span> <br><strong>{{ $application->region }}</strong></div>
                                <div><span class="text-gray-500">Province:</span> <br><strong>{{ $application->province }}</strong></div>
                                <div><span class="text-gray-500">City/Municipal:</span> <br><strong>{{ $application->municipality_city }}</strong></div>
                                <div><span class="text-gray-500">Barangay:</span> <br><strong>{{ $application->barangay }}</strong></div>
                            </div>
                            <div class="text-sm font-bold text-gray-900">
                                {{ $application->street_address }} (Zip Code: {{ $application->zip_code }})
                            </div>
                        </div>

                        {{-- Special Categories --}}
                        <div class="mt-4 pt-2 border-t border-gray-200">
                            <label class="block text-[10px] text-gray-500 uppercase font-bold mb-2">Background & Special Categories</label>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="flex items-center">
                                    <div class="check-box">@if($application->is_ip) <span class="text-black font-bold text-xs">✓</span> @endif</div>
                                    <div>
                                        <span class="text-[10px] font-bold uppercase text-gray-700">IP Member</span>
                                        @if($application->is_ip) <div class="text-[10px] text-black">({{ $application->ip_group_name }})</div> @endif
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="check-box">@if($application->is_pwd) <span class="text-black font-bold text-xs">✓</span> @endif</div>
                                    <div>
                                        <span class="text-[10px] font-bold uppercase text-gray-700">PWD</span>
                                        @if($application->is_pwd) <div class="text-[10px] text-black">({{ $application->pwd_disability }})</div> @endif
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="check-box">@if($application->is_4ps) <span class="text-black font-bold text-xs">✓</span> @endif</div>
                                    <span class="text-[10px] font-bold uppercase text-gray-700">4Ps Beneficiary</span>
                                </div>
                            </div>
                            
                            {{-- Referral Info --}}
                            <div class="mt-3 text-xs">
                                <span class="text-gray-500 font-bold">Learned NAS via:</span> {{ $application->learn_about_nas }}
                                @if($application->referrer_name)
                                    | <span class="text-gray-500 font-bold">Referred by:</span> {{ $application->referrer_name }}
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- II. ACADEMIC & SPORTS PROFILE --}}
                    <div class="mb-6">
                        <div class="bg-gray-200 border border-gray-400 px-3 py-1 mb-4">
                            <h3 class="text-sm font-bold text-gray-800 uppercase">II. Academic & Sports Profile</h3>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm">
                            <div class="col-span-2">
                                <label class="block text-[10px] text-gray-500 uppercase font-bold">Last School Attended</label>
                                <div class="font-bold text-gray-900 border-b border-gray-300">{{ $application->previous_school }} ({{ $application->school_type }})</div>
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-500 uppercase font-bold">Applied Grade</label>
                                <div class="font-bold text-gray-900 border-b border-gray-300">{{ $application->grade_level_applied }}</div>
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-500 uppercase font-bold">Sport</label>
                                <div class="font-bold text-gray-900 border-b border-gray-300">
                                    {{ $application->sport }} 
                                    @if($application->sport_specification) 
                                        <span class="text-xs font-normal">({{ $application->sport_specification }})</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-[10px] text-gray-500 uppercase font-bold">Palarong Pambansa Finisher</label>
                                <div class="font-bold text-gray-900 border-b border-gray-300">
                                    {{ $application->has_palaro_participation ? 'YES' : 'NO' }}
                                </div>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-[10px] text-gray-500 uppercase font-bold">Batang Pinoy Finisher</label>
                                <div class="font-bold text-gray-900 border-b border-gray-300">
                                    {{ $application->batang_pinoy_finisher == 'Yes' ? 'YES' : 'NO' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- III. GUARDIAN INFORMATION --}}
                    <div class="mb-6">
                        <div class="bg-gray-200 border border-gray-400 px-3 py-1 mb-4">
                            <h3 class="text-sm font-bold text-gray-800 uppercase">III. Guardian Information</h3>
                        </div>
                        <div class="grid grid-cols-2 gap-6 text-sm">
                            <div><label class="block text-[10px] text-gray-500 uppercase font-bold">Guardian Name</label><div class="font-bold text-gray-900 border-b border-gray-300">{{ $application->guardian_name }}</div></div>
                            <div><label class="block text-[10px] text-gray-500 uppercase font-bold">Relationship</label><div class="font-bold text-gray-900 border-b border-gray-300">{{ $application->guardian_relationship }}</div></div>
                            <div><label class="block text-[10px] text-gray-500 uppercase font-bold">Contact Number</label><div class="font-bold text-gray-900 border-b border-gray-300">{{ $application->guardian_contact }}</div></div>
                            <div><label class="block text-[10px] text-gray-500 uppercase font-bold">Email Address</label><div class="font-bold text-gray-900 border-b border-gray-300">{{ $application->guardian_email }}</div></div>
                        </div>
                    </div>

                    {{-- IV. SUBMITTED DOCUMENTS --}}
                    <div>
                        <div class="bg-gray-200 border border-gray-400 px-3 py-1 mb-4">
                            <h3 class="text-sm font-bold text-gray-800 uppercase">IV. Submitted Documents & Remarks</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[600px]">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 uppercase">
                                        <th class="px-4 py-3 font-bold w-1/3">Document Name</th>
                                        <th class="px-4 py-3 font-bold text-center">Status</th>
                                        <th class="px-4 py-3 font-bold text-center">View</th>
                                        <th class="px-4 py-3 font-bold w-1/3 no-print">Admin Remarks (Editable)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-xs">
                                    @php
                                        $files = $application->uploaded_files ?? [];
                                        $remarks = $application->document_remarks ?? []; 
                                        $fileTimestamps = $application->file_timestamps ?? [];
                                        $lastAdminCheck = $application->date_checked;
                                        
                                        $docs = [
                                            // 👇 Added ID Picture here so you can add remarks/check status
                                            'id_picture' => '2x2 ID Picture', 
                                            'scholarship_form' => 'Scholarship Application Form',
                                            'student_profile' => 'Student-Athlete Profile Form',
                                            'medical_clearance' => 'Medical/Physical Clearance',
                                            'coach_reco' => 'Coach Recommendation Form',
                                            'adviser_reco' => 'Adviser Recommendation Form',
                                            'birth_cert' => 'PSA Birth Certificate',
                                            'report_card' => 'Report Card (SF9)',
                                            'guardian_id' => 'Guardian Valid ID'
                                        ];
                                    @endphp

                                    @foreach($docs as $key => $label)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-3 font-bold text-gray-900">
                                                {{ $label }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @php
                                                    $isUploaded = isset($files[$key]) && !empty($files[$key]);
                                                    $fileUpdatedTime = isset($fileTimestamps[$key]) ? \Carbon\Carbon::parse($fileTimestamps[$key]) : null;
                                                    
                                                    // Modified Logic: Specific file timestamp > Last admin check date
                                                    $isModified = $isUploaded && $fileUpdatedTime && $lastAdminCheck && $fileUpdatedTime->gt($lastAdminCheck);
                                                @endphp

                                                @if($isUploaded)
                                                    @if($isModified) 
                                                        <div class="flex flex-col items-center">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200 shadow-sm animate-pulse">
                                                                UPDATED
                                                            </span>
                                                            <span class="text-[9px] text-gray-500 mt-1 font-mono">{{ $fileUpdatedTime->format('M d, h:i A') }}</span>
                                                        </div>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 border border-green-200">
                                                            UPLOADED
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800 border border-red-200">
                                                        MISSING
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if(isset($files[$key]) && !empty($files[$key]))
                                                    <a href="{{ $files[$key] }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-bold underline no-print">VIEW</a>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 no-print relative group">
                                                <div class="relative">
                                                    <input type="text" 
                                                           name="document_remarks[{{ $key }}]" 
                                                           form="update-status-form" 
                                                           value="{{ $remarks[$key] ?? '' }}"
                                                           class="w-full border border-gray-300 rounded text-xs px-3 py-2 pr-8 focus:ring-indigo-500 focus:border-indigo-500 admin-input shadow-sm transition-colors group-hover:border-indigo-300" 
                                                           placeholder="Type remarks here...">
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
                    </div>

                </div>

                {{-- SIDEBAR: ADMIN ACTIONS (No Print) --}}
                <div class="md:col-span-1 no-print">
                    <div class="bg-white shadow-xl rounded-lg border-t-4 border-indigo-600 sticky top-6">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Update Status</h3>
                            
                            <form id="update-status-form" method="POST" action="{{ route('admission.process', $application->id) }}">
                                @csrf
                                @method('PATCH')

                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status</label>
                                    <select name="status" id="status" class="w-full border-gray-300 rounded text-sm font-bold text-gray-800">
                                        <option value="Pending" {{ in_array($application->status, ['Pending', 'Pending Review']) ? 'selected' : '' }}>Pending Review</option>
                                        <option value="For Assessment" {{ $application->status == 'For Assessment' ? 'selected' : '' }}>For Assessment</option>
                                        <option value="Qualified" {{ $application->status == 'Qualified' ? 'selected' : '' }}>Qualified</option>
                                        <option value="Waitlisted" {{ $application->status == 'Waitlisted' ? 'selected' : '' }}>Waitlisted</option>
                                        <option value="Not Qualified" {{ in_array($application->status, ['Not Qualified', 'Rejected', 'Failed']) ? 'selected' : '' }}>Not Qualified</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">General Remarks / Assessment</label>
                                    <textarea name="assessment_score" rows="3" class="w-full border-gray-300 rounded text-sm" placeholder="Internal notes or general feedback...">{{ $application->assessment_score }}</textarea>
                                </div>

                                <div class="mb-4 hidden" id="rejection-div">
                                    <label class="block text-xs font-bold text-red-600 uppercase mb-1">Reason for Rejection (Visible to Student)</label>
                                    <textarea name="rejection_reason" rows="2" class="w-full border-red-300 bg-red-50 rounded text-sm text-red-700" placeholder="State reason...">{{ $application->rejection_reason }}</textarea>
                                </div>

                                <button type="submit" class="w-full bg-indigo-700 hover:bg-indigo-800 text-white font-bold py-3 rounded shadow transition transform hover:scale-105">
                                    SAVE UPDATES
                                </button>
                            </form>
                        </div>

                        @if($application->status == 'Qualified')
                            <div class="bg-green-100 p-4 text-center border-t border-green-200">
                                <p class="text-xs text-green-800 mb-2">Applicant is qualified.</p>
                                <a href="{{ route('official-enrollment.show', $application->id) }}" class="block w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 rounded shadow text-sm">
                                    PROCEED TO ENROLLMENT
                                </a>
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
            // Check for various "Not Qualified" statuses
            const rejectedStatuses = ['Not Qualified', 'Rejected', 'Failed'];
            if(rejectedStatuses.includes(statusSelect.value)) {
                rejectionDiv.classList.remove('hidden');
            } else {
                rejectionDiv.classList.add('hidden');
            }
        }
        
        statusSelect.addEventListener('change', toggleReject);
        toggleReject(); 
    </script>
</x-app-layout>