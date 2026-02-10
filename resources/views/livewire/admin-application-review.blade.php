<div class="py-10 bg-slate-50 min-h-screen" wire:poll.5s> {{-- ⚡ AUTO-REFRESH --}}
    
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Alerts (Flash Messages from Livewire) --}}
        @if (session()->has('message'))
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-r-lg shadow-sm flex items-center animate-fade-in-down">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium">{{ session('message') }}</span>
            </div>
        @endif

        {{-- NEW UPDATE ALERT (Real-time Notification) --}}
        @if(str_contains($application->status, 'Resubmitted') || str_contains($application->status, 'Pending'))
             <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded-r-lg shadow-sm flex items-center animate-pulse">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-bold">Update: Applicant has resubmitted documents! Please review below.</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            {{-- MAIN CONTENT AREA (Personal Info + Documents) --}}
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

                    {{-- I. PERSONAL INFORMATION (Static Display) --}}
                    {{-- ... (Kopyahin mo yung Section I mula sa show.blade.php) ... --}}
                    
                    {{-- II. SPORTS & SCHOOL PROFILE --}}
                    {{-- ... (Kopyahin mo yung Section II mula sa show.blade.php) ... --}}

                    {{-- III. GUARDIAN --}}
                    {{-- ... (Kopyahin mo yung Section III mula sa show.blade.php) ... --}}

                    {{-- IV. DOCUMENTS (The Critical Part) --}}
                    <section>
                        <div class="flex items-center mb-5">
                            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-black mr-3 shadow-lg shadow-indigo-200">4</div>
                            <h3 class="text-base font-black text-slate-800 uppercase tracking-wide">Submitted Documents (Phase 2)</h3>
                        </div>

                        {{-- NOTE: Tinanggal natin ang <form> wrapper dito dahil Livewire ang gagamitin natin sa buttons --}}
                        
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

                                            {{-- Action Buttons (STILL USING STANDARD LINKS FOR SAFETY) --}}
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
                                            {{-- NOTE: Remarks saving is still manual via "Save Remarks" button below --}}
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
                    </section>
                </div>
            </div>

            {{-- SIDEBAR ACTIONS --}}
            <div class="md:col-span-1 no-print">
                <div class="bg-white shadow-xl shadow-indigo-100 rounded-2xl border border-slate-200 p-6 sticky top-6">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-wide border-b border-slate-100 pb-3 mb-4">Application Decision</h3>
                    
                    {{-- FORM FOR STATUS UPDATE --}}
                    <form action="{{ route('admission.process', $application->id) }}" method="POST">
                        @csrf @method('PATCH')
                        
                        {{-- Keep remarks when updating status --}}
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
                                    
                                    {{-- IMPORTANT: The RETURN option --}}
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