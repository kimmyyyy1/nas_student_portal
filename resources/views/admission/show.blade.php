<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center no-print">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <a href="{{ route('admission.index') }}" class="text-gray-400 hover:text-indigo-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <span class="border-l-2 border-gray-300 pl-3 ml-1">{{ __('Review Admission Application') }}</span>
            </h2>
            <div class="flex gap-3">
                <button onclick="window.print()" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-2 px-4 rounded-lg inline-flex items-center shadow-sm transition text-xs uppercase tracking-wider">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print
                </button>
                <a href="{{ route('admission.pdf', $application->id) }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center shadow-md transition text-xs uppercase tracking-wider">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download PDF
                </a>
            </div>
        </div>
    </x-slot>

    <style>
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
            .admin-input { display: none !important; }
        }
    </style>

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alerts --}}
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-r-lg shadow-sm no-print flex items-center animate-fade-in-down">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                {{-- MAIN CONTENT AREA --}}
                <div id="print-area" class="md:col-span-3 bg-white shadow-xl shadow-indigo-100/50 rounded-2xl overflow-hidden border border-slate-200 relative">
                    
                    <div class="p-8 md:p-10 space-y-10">
                        {{-- HEADER --}}
                        <div class="flex flex-col md:flex-row items-center justify-between pb-6 border-b-2 border-slate-100 gap-6">
                            <img src="{{ asset('images/nas/nas-logo-sidebar.png') }}" alt="NAS Logo" class="h-24 w-auto object-contain">
                            <div class="text-center flex-1">
                                <h1 class="text-xl font-black text-slate-800 uppercase leading-tight tracking-tight">
                                    NAS Annual Search for Competent, Exceptional, Notable, and Talented Student-Athlete Scholars
                                </h1>
                                <h2 class="text-sm font-bold text-indigo-600 uppercase tracking-widest mt-2 bg-indigo-50 inline-block px-3 py-1 rounded">
                                    (NASCENT SAS)
                                </h2>
                                <p class="text-xs font-medium text-slate-400 mt-2">New Clark City, Capas, Tarlac</p>
                            </div>
                            <img src="{{ asset('images/nas/NASCENT SAS.png') }}" alt="NASCENT SAS Logo" class="h-24 w-auto object-contain">
                        </div>

                        {{-- I. PERSONAL INFORMATION --}}
                        <section>
                            <div class="flex items-center mb-5">
                                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-black mr-3 shadow-lg shadow-indigo-200">1</div>
                                <h3 class="text-base font-black text-slate-800 uppercase tracking-wide">Personal Information</h3>
                            </div>
                            
                            <div class="flex flex-col md:flex-row gap-8 items-start bg-slate-50/50 p-6 rounded-2xl border border-slate-200">
                                {{-- Text Details --}}
                                <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-3 gap-y-5 gap-x-6">
                                    
                                    {{-- Row 1 --}}
                                    <div class="md:col-span-3">
                                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Learner Reference Number (LRN)</label>
                                        <div class="text-xl font-mono font-bold text-slate-800 tracking-wider">{{ $application->lrn }}</div>
                                    </div>

                                    {{-- Row 2 --}}
                                    <div>
                                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Last Name</label>
                                        <div class="text-sm font-bold text-slate-800 border-b border-slate-200 pb-1 uppercase">{{ $application->last_name }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">First Name</label>
                                        <div class="text-sm font-bold text-slate-800 border-b border-slate-200 pb-1 uppercase">{{ $application->first_name }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Middle Name</label>
                                        <div class="text-sm font-bold text-slate-800 border-b border-slate-200 pb-1 uppercase">{{ $application->middle_name ?? 'N/A' }}</div>
                                    </div>

                                    {{-- Row 3 --}}
                                    <div>
                                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Date of Birth</label>
                                        <div class="text-sm font-bold text-slate-800 border-b border-slate-200 pb-1">{{ \Carbon\Carbon::parse($application->date_of_birth)->format('F d, Y') }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Age</label>
                                        <div class="text-sm font-bold text-slate-800 border-b border-slate-200 pb-1">{{ $application->age }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Sex</label>
                                        <div class="text-sm font-bold text-slate-800 border-b border-slate-200 pb-1">{{ $application->gender }}</div>
                                    </div>

                                    {{-- Row 4: Address --}}
                                    <div class="md:col-span-3 border-t border-slate-200 pt-4 mt-2">
                                        <label class="block text-xs font-black text-indigo-900 uppercase mb-3">Permanent Address</label>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                            <div>
                                                <span class="block text-[9px] text-slate-400 uppercase">Region</span>
                                                <span class="text-xs font-bold text-slate-700">{{ $application->region }}</span>
                                            </div>
                                            <div>
                                                <span class="block text-[9px] text-slate-400 uppercase">Province</span>
                                                <span class="text-xs font-bold text-slate-700">{{ $application->province }}</span>
                                            </div>
                                            <div>
                                                <span class="block text-[9px] text-slate-400 uppercase">Municipality/City</span>
                                                <span class="text-xs font-bold text-slate-700">{{ $application->municipality_city }}</span>
                                            </div>
                                            <div>
                                                <span class="block text-[9px] text-slate-400 uppercase">Barangay</span>
                                                <span class="text-xs font-bold text-slate-700">{{ $application->barangay }}</span>
                                            </div>
                                            <div class="md:col-span-2">
                                                <span class="block text-[9px] text-slate-400 uppercase">Street / House No.</span>
                                                <span class="text-xs font-bold text-slate-700">{{ $application->street_address ?? 'N/A' }}</span>
                                            </div>
                                            <div>
                                                <span class="block text-[9px] text-slate-400 uppercase">Zip Code</span>
                                                <span class="text-xs font-bold text-slate-700 font-mono">{{ $application->zip_code }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Background Info --}}
                                    <div class="md:col-span-3 border-t border-slate-200 pt-4 mt-2 grid grid-cols-2 gap-4 text-xs">
                                        <div>
                                            <label class="block text-[9px] text-slate-400 uppercase">Referred By?</label>
                                            <span class="font-bold text-slate-700">{{ $application->referrer_name ?? 'N/A' }} ({{ $application->learn_about_nas }})</span>
                                        </div>
                                        <div>
                                            <label class="block text-[9px] text-slate-400 uppercase">Attended Campaign?</label>
                                            <span class="font-bold text-slate-700">{{ $application->attended_campaign }}</span>
                                        </div>
                                    </div>

                                    <div class="md:col-span-3 grid grid-cols-3 gap-4 text-xs">
                                        <div>
                                            <label class="block text-[9px] text-slate-400 uppercase">Indigenous Group?</label>
                                            <span class="font-bold {{ $application->is_ip ? 'text-indigo-600' : 'text-slate-600' }}">
                                                {{ $application->is_ip ? 'YES (' . $application->ip_group_name . ')' : 'NO' }}
                                            </span>
                                        </div>
                                        <div>
                                            <label class="block text-[9px] text-slate-400 uppercase">PWD?</label>
                                            <span class="font-bold {{ $application->is_pwd ? 'text-indigo-600' : 'text-slate-600' }}">
                                                {{ $application->is_pwd ? 'YES (' . $application->pwd_disability . ')' : 'NO' }}
                                            </span>
                                        </div>
                                        <div>
                                            <label class="block text-[9px] text-slate-400 uppercase">4Ps Beneficiary?</label>
                                            <span class="font-bold {{ $application->is_4ps ? 'text-indigo-600' : 'text-slate-600' }}">{{ $application->is_4ps ? 'YES' : 'NO' }}</span>
                                        </div>
                                    </div>

                                </div>

                                {{-- Photo --}}
                                <div class="w-32 flex-shrink-0 flex flex-col items-center">
                                    <div class="w-32 h-32 bg-white border-2 border-slate-200 rounded-xl flex items-center justify-center overflow-hidden shadow-sm">
                                        @if(isset($application->uploaded_files['id_picture']))
                                            <img src="{{ $application->uploaded_files['id_picture'] }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-slate-300 text-[10px] font-bold text-center px-2">NO PHOTO</span>
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase">2x2 Photo</p>
                                </div>
                            </div>
                        </section>

                        {{-- II. SPORTS & SCHOOL PROFILE --}}
                        <section>
                            <div class="flex items-center mb-5">
                                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-black mr-3 shadow-lg shadow-indigo-200">2</div>
                                <h3 class="text-base font-black text-slate-800 uppercase tracking-wide">Sports & School Profile</h3>
                            </div>
                            <div class="bg-slate-50/50 p-6 rounded-2xl border border-slate-200 grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                {{-- Left: Focus Sport --}}
                                <div>
                                    <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Focus Sport</label>
                                    <div class="text-lg font-black text-indigo-700">{{ $application->sport }}</div>
                                    @if($application->sport_specification)
                                        <div class="text-xs font-bold text-indigo-500 uppercase mt-1">({{ $application->sport_specification }})</div>
                                    @endif
                                </div>

                                {{-- Right: Achievements & School --}}
                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Palarong Pambansa</label>
                                        <span class="text-xs font-bold px-3 py-1 rounded {{ $application->palaro_finisher == 'Yes' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-500' }}">
                                            {{ $application->palaro_finisher ?? 'NO' }}
                                        </span>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Batang Pinoy</label>
                                        <span class="text-xs font-bold px-3 py-1 rounded {{ $application->batang_pinoy_finisher == 'Yes' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-500' }}">
                                            {{ $application->batang_pinoy_finisher ?? 'NO' }}
                                        </span>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Current School Type</label>
                                        <div class="text-sm font-bold text-slate-800">{{ $application->school_type ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- III. GUARDIAN --}}
                        <section>
                            <div class="flex items-center mb-5">
                                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-black mr-3 shadow-lg shadow-indigo-200">3</div>
                                <h3 class="text-base font-black text-slate-800 uppercase tracking-wide">Guardian Information</h3>
                            </div>
                            <div class="bg-slate-50/50 p-6 rounded-2xl border border-slate-200 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                                <div>
                                    <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Guardian Name</label>
                                    <div class="font-bold text-slate-800">{{ $application->guardian_name }}</div>
                                </div>
                                <div>
                                    <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Relationship</label>
                                    <div class="font-bold text-slate-800">{{ $application->guardian_relationship }}</div>
                                </div>
                                <div>
                                    <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Contact Number</label>
                                    <div class="font-bold text-slate-800 font-mono">{{ $application->guardian_contact }}</div>
                                </div>
                                <div>
                                    <label class="block text-[10px] text-slate-400 uppercase font-bold mb-1">Email Address</label>
                                    <div class="font-bold text-slate-800">{{ $application->guardian_email }}</div>
                                </div>
                            </div>
                        </section>

                        {{-- IV. DOCUMENTS --}}
                        <section>
                            <div class="flex items-center mb-5">
                                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-black mr-3 shadow-lg shadow-indigo-200">4</div>
                                <h3 class="text-base font-black text-slate-800 uppercase tracking-wide">Submitted Documents (Phase 2)</h3>
                            </div>

                            <form action="{{ route('admission.process', $application->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="{{ $application->status }}">
                                
                                <div class="overflow-hidden rounded-xl border border-slate-200 shadow-sm">
                                    <table class="w-full text-left border-collapse bg-white text-sm table-fixed">
                                        <thead class="bg-slate-50 text-[10px] uppercase text-slate-500 font-bold border-b border-slate-200">
                                            <tr>
                                                <th class="px-4 py-4 w-[30%]">Document Name</th>
                                                <th class="px-2 py-4 text-center w-[10%]">Status</th>
                                                <th class="px-2 py-4 text-center w-[10%]">File</th>
                                                <th class="px-2 py-4 text-center no-print w-[10%]">Action</th>
                                                <th class="px-4 py-4 w-[40%] no-print">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @php
                                                $files = is_string($application->uploaded_files) ? json_decode($application->uploaded_files, true) : ($application->uploaded_files ?? []);
                                                $remarks = is_string($application->document_remarks) ? json_decode($application->document_remarks, true) : ($application->document_remarks ?? []);
                                                $statuses = is_string($application->document_statuses) ? json_decode($application->document_statuses, true) : ($application->document_statuses ?? []);

                                                // UPDATED PROFESSIONAL NAMES based on user list
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
                                                        default => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                    };
                                                @endphp
                                                <tr class="hover:bg-slate-50 transition group align-top">
                                                    
                                                    {{-- Document Name --}}
                                                    <td class="px-4 py-4 font-bold text-slate-700 leading-snug break-words pr-4">
                                                        {{ $label }}
                                                    </td>

                                                    {{-- Status --}}
                                                    <td class="px-2 py-4 text-center align-middle">
                                                        @if($isUploaded)
                                                            <span class="inline-flex px-2 py-1 text-[9px] font-bold uppercase rounded-full border {{ $badgeClass }}">
                                                                {{ $status }}
                                                            </span>
                                                        @else
                                                            <span class="text-slate-300 text-[10px] italic font-medium">Pending</span>
                                                        @endif
                                                    </td>

                                                    {{-- File Link --}}
                                                    <td class="px-2 py-4 text-center align-middle">
                                                        @if($isUploaded)
                                                            <a href="{{ $files[$key] }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-bold text-[10px] flex flex-col items-center justify-center gap-1">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                                VIEW
                                                            </a>
                                                        @else - @endif
                                                    </td>

                                                    {{-- Action Buttons --}}
                                                    <td class="px-2 py-4 text-center no-print align-middle">
                                                        @if($isUploaded)
                                                            <div class="flex flex-col md:flex-row justify-center gap-2">
                                                                <a href="{{ route('admission.approve_document', ['id' => $application->id, 'doc_key' => $key]) }}" class="p-2 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white transition shadow-sm border border-emerald-200" title="Approve">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                                </a>
                                                                <a href="{{ route('admission.decline_document', ['id' => $application->id, 'doc_key' => $key]) }}" class="p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition shadow-sm border border-red-200" title="Decline">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </td>

                                                    {{-- Remarks Field (TEXTAREA) --}}
                                                    <td class="px-4 py-3 no-print">
                                                        <textarea 
                                                            name="document_remarks[{{ $key }}]" 
                                                            rows="3" 
                                                            class="w-full text-xs border-slate-300 bg-slate-50 focus:bg-white rounded-lg focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition placeholder-slate-400 p-3 leading-relaxed" 
                                                            placeholder="Type remarks here..."
                                                        >{{ $remarks[$key] ?? '' }}</textarea>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-4 text-right no-print">
                                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-3 px-8 rounded-xl text-xs uppercase tracking-widest shadow-md hover:shadow-lg transition">
                                        Save Remarks Only
                                    </button>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>

                {{-- SIDEBAR ACTIONS --}}
                <div class="md:col-span-1 no-print">
                    <div class="bg-white shadow-xl shadow-indigo-100 rounded-2xl border border-slate-200 p-6 sticky top-6">
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-wide border-b border-slate-100 pb-3 mb-4">Application Decision</h3>
                        
                        <form action="{{ route('admission.process', $application->id) }}" method="POST">
                            @csrf @method('PATCH')
                            
                            {{-- IMPORTANT: Pass existing remarks so they don't get wiped out when changing status --}}
                            @if(isset($remarks) && is_array($remarks))
                                @foreach($remarks as $k => $v) <input type="hidden" name="document_remarks[{{ $k }}]" value="{{ $v }}"> @endforeach
                            @endif

                            <div class="mb-4">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Set Status</label>
                                <select name="status" id="status" class="w-full border-slate-200 rounded-lg text-sm font-bold text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                    
                                    <optgroup label="Phase 1: Registration">
                                        <option value="Submitted for 1st Level Assessment" {{ $application->status == 'Submitted for 1st Level Assessment' ? 'selected' : '' }}>For 1st Level Assessment</option>
                                        <option value="For 2nd Level Assessment" {{ $application->status == 'For 2nd Level Assessment' ? 'selected' : '' }}>Passed 1st Level (Move to Phase 2)</option>
                                    </optgroup>

                                    <optgroup label="Phase 2: Documents">
                                        <option value="Requirements Submitted & For Review" {{ $application->status == 'Requirements Submitted & For Review' ? 'selected' : '' }}>Requirements Submitted</option>
                                        
                                        {{-- THE NEW OPTION FOR RE-UPLOAD --}}
                                        <option value="Requirements Returned for Re-upload" class="text-red-600 font-bold" {{ $application->status == 'Requirements Returned for Re-upload' ? 'selected' : '' }}>RETURN for Re-upload</option>
                                        
                                        <option value="Qualified" {{ $application->status == 'Qualified' ? 'selected' : '' }}>Qualified (Final)</option>
                                        <option value="Waitlisted" {{ $application->status == 'Waitlisted' ? 'selected' : '' }}>Waitlisted</option>
                                    </optgroup>

                                    <optgroup label="Declined">
                                        <option value="Not Qualified" {{ in_array($application->status, ['Not Qualified', 'Rejected', 'Failed']) ? 'selected' : '' }}>Not Qualified</option>
                                    </optgroup>
                                </select>
                            </div>

                            <div class="mb-4 hidden" id="rejection-div">
                                <label class="block text-[10px] font-bold text-red-500 uppercase mb-1">Reason for Rejection</label>
                                <textarea name="rejection_reason" rows="3" class="w-full border-red-200 bg-red-50 rounded-lg text-sm text-red-700 focus:border-red-500 focus:ring-red-500">{{ $application->rejection_reason }}</textarea>
                            </div>

                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-indigo-200 hover:shadow-indigo-300 transition transform hover:-translate-y-0.5 text-xs uppercase tracking-widest flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Update Status
                            </button>
                        </form>
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
            if(rejectedStatuses.includes(statusSelect.value)) { rejectionDiv.classList.remove('hidden'); } 
            else { rejectionDiv.classList.add('hidden'); }
        }
        statusSelect.addEventListener('change', toggleReject);
        toggleReject(); 
    </script>
</x-app-layout>