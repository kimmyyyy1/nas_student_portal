<x-applicant-layout>
    <div class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8 font-sans bg-pattern">
        
        {{-- HEADER --}}
        <div class="max-w-5xl mx-auto flex flex-col items-center justify-center mb-12 text-center animate-fade-in-down">
            <h1 class="text-3xl md:text-5xl font-black text-slate-900 uppercase tracking-tighter leading-none bg-clip-text text-transparent bg-gradient-to-r from-blue-900 via-slate-800 to-blue-900 drop-shadow-sm font-heading">
                Phase 2: Requirements Submission
            </h1>
            <p class="mt-4 text-slate-500 font-medium text-sm md:text-base max-w-2xl">
                Congratulations on passing the 1st Level Assessment! Please upload the required documents based on your profile to proceed.
            </p>
        </div>

        <div class="max-w-5xl mx-auto bg-white shadow-2xl shadow-blue-100/70 rounded-[2.5rem] overflow-hidden border border-slate-100 relative">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>

            <form action="{{ route('applicant.store_requirements') }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-12">
                @csrf

                {{-- SERVER SIDE ALERTS --}}
                @if ($errors->any())
                    <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm animate-pulse">
                        <div class="flex items-start">
                            <div class="flex-shrink-0"><svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg></div>
                            <div class="ml-3">
                                <h3 class="text-sm font-bold text-red-800">Submission Failed</h3>
                                <ul class="mt-1 list-disc list-inside text-xs text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- CLIENT SIDE JS ALERT --}}
                <div id="file-error-alert" class="hidden mb-8 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm transition-all duration-300 fixed top-4 right-4 z-50 max-w-sm">
                    <div class="flex">
                        <div class="flex-shrink-0"><svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg></div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-red-800">File Error</p>
                            <p id="file-error-msg" class="text-xs text-red-700 mt-1"></p>
                        </div>
                        <button type="button" onclick="document.getElementById('file-error-alert').classList.add('hidden')" class="ml-auto pl-3">
                            <svg class="h-4 w-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>

                {{-- NOTICE --}}
                <div class="mb-10 bg-yellow-50 border border-yellow-200 p-6 rounded-2xl flex gap-4 items-start shadow-sm">
                    <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h4 class="font-black text-yellow-800 uppercase tracking-wide text-xs mb-1">Important Note</h4>
                        <p class="text-xs text-yellow-700 leading-relaxed font-medium">
                            Coach’s & Adviser’s Recommendation Forms are <strong>NOT required</strong> to be uploaded here. They should be submitted directly via email by the coach/adviser to the Secretariat.
                            <br><span class="mt-2 block text-yellow-800 font-bold">Allowed Formats: PDF, JPG, PNG, HEIC/HEIF (iOS) - Max 10MB per file</span>
                        </p>
                    </div>
                </div>

                @php
                    $statuses = is_string($applicant->document_statuses) ? json_decode($applicant->document_statuses, true) : ($applicant->document_statuses ?? []);
                    $remarks = is_string($applicant->document_remarks) ? json_decode($applicant->document_remarks, true) : ($applicant->document_remarks ?? []);
                    $files = is_string($applicant->uploaded_files) ? json_decode($applicant->uploaded_files, true) : ($applicant->uploaded_files ?? []);
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    
                    {{-- ================= MANDATORY FILES ================= --}}
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="h-8 w-1 bg-blue-600 rounded-full"></div>
                            <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Mandatory Files</h3>
                        </div>

                        @foreach([
                            'scholarship_form' => 'Scholarship Application Form', 
                            'student_profile' => 'Student-Athlete’s Profile Form', 
                            'medical_clearance' => 'Preparticipation Physical Evaluation Clearance Form', 
                            'psa_birth_cert' => 'PSA Birth Certificate', 
                            'report_card' => 'Grade 5 and 6 Report Card (for incoming Grade 7) or Grade 6 and 7 Report Card (for incoming Grade 8)', 
                            'guardian_id' => 'Designated Guardian’s valid Government-Issued ID with signature'
                        ] as $key => $label)
                            
                            @php
                                $status = $statuses[$key] ?? 'pending';
                                $remark = $remarks[$key] ?? null;
                                $hasFile = isset($files[$key]);
                                $isDeclined = ($status === 'declined');
                                $isApproved = ($status === 'approved');

                                $borderClass = 'border-slate-100';
                                $bgClass = 'bg-white';
                                if($isDeclined) { $borderClass = 'border-red-300'; $bgClass = 'bg-red-50'; }
                                if($isApproved) { $borderClass = 'border-emerald-300'; $bgClass = 'bg-emerald-50'; }
                            @endphp

                            <div class="group relative border-2 {{ $borderClass }} {{ $bgClass }} rounded-2xl p-5 transition-all duration-300 hover:shadow-md">
                                <div class="flex justify-between items-start mb-2">
                                    <label class="block text-[10px] font-black text-slate-600 uppercase tracking-wider">
                                        {{ $label }} *
                                    </label>
                                    
                                    @if($isApproved)
                                        <span class="bg-emerald-200 text-emerald-700 text-[9px] font-bold px-2 py-0.5 rounded uppercase flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Approved
                                        </span>
                                    @elseif($isDeclined)
                                        <span class="bg-red-200 text-red-700 text-[9px] font-bold px-2 py-0.5 rounded uppercase flex items-center animate-pulse">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            Re-upload Needed
                                        </span>
                                    @elseif($hasFile)
                                        <span class="bg-yellow-200 text-yellow-800 text-[9px] font-bold px-2 py-0.5 rounded uppercase">Submitted</span>
                                    @endif
                                </div>

                                @if($isDeclined && $remark)
                                    <div class="mb-3 p-2 bg-white/60 border border-red-200 rounded-lg">
                                        <p class="text-[9px] font-bold text-red-600 uppercase">Admin Remark:</p>
                                        <p class="text-xs text-red-800 italic font-medium">"{{ $remark }}"</p>
                                    </div>
                                @endif

                                @if($isApproved)
                                    <div class="flex items-center justify-between mt-2 opacity-75">
                                        <span class="text-xs text-emerald-600 font-bold flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            File Accepted
                                        </span>
                                        <a href="{{ $files[$key] }}" target="_blank" class="text-[10px] font-bold text-slate-400 hover:text-indigo-600 hover:underline">VIEW FILE</a>
                                    </div>
                                @else
                                    <input type="file" 
                                           name="files[{{ $key }}]" 
                                           accept=".pdf,.jpg,.jpeg,.png,.heic,.heif"
                                           onchange="validateFile(this)"
                                           {{ (!$hasFile || $isDeclined) ? 'required' : '' }}
                                           class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase {{ $isDeclined ? 'file:bg-red-100 file:text-red-700 hover:file:bg-red-200' : 'file:bg-slate-100 file:text-slate-600 hover:file:bg-blue-600 hover:file:text-white' }} cursor-pointer transition-all mt-2">
                                    
                                    @if($hasFile && !$isDeclined)
                                        <div class="flex justify-between mt-1 px-1">
                                            <p class="text-[9px] text-slate-400 italic">Current file uploaded. Upload to replace.</p>
                                            <a href="{{ $files[$key] }}" target="_blank" class="text-[9px] font-bold text-indigo-500 hover:underline">VIEW</a>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- ================= CONDITIONAL FILES ================= --}}
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="h-8 w-1 bg-slate-300 rounded-full"></div>
                            <h3 class="text-lg font-black text-slate-500 uppercase tracking-tight">Conditional Requirements</h3>
                        </div>

                        @php
                            $conditionals = [];
                            if($applicant->sport === 'Taekwondo') $conditionals['kukkiwon_cert'] = 'Kukkiwon Certificate (If taekwondo)';
                            if($applicant->is_ip) $conditionals['ip_cert'] = 'IP Certification (If member of an indigenous group)';
                            if($applicant->is_pwd) $conditionals['pwd_id'] = 'PWD ID (If person with disability)';
                            if($applicant->is_4ps) $conditionals['4ps_id'] = '4Ps ID or Certification (If beneficiary of the 4Ps)';
                        @endphp

                        @if(empty($conditionals))
                            <div class="p-8 text-center border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                                <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-xs font-bold text-slate-400 uppercase">No additional documents required based on your profile.</p>
                            </div>
                        @else
                            @foreach($conditionals as $key => $label)
                                @php
                                    $status = $statuses[$key] ?? 'pending';
                                    $remark = $remarks[$key] ?? null;
                                    $hasFile = isset($files[$key]);
                                    $isDeclined = ($status === 'declined');
                                    $isApproved = ($status === 'approved');

                                    $borderClass = 'border-dashed border-slate-200';
                                    $bgClass = 'bg-slate-50';
                                    if($isDeclined) { $borderClass = 'border-solid border-red-300'; $bgClass = 'bg-red-50'; }
                                    if($isApproved) { $borderClass = 'border-solid border-emerald-300'; $bgClass = 'bg-emerald-50'; }
                                @endphp

                                <div class="group relative border-2 {{ $borderClass }} {{ $bgClass }} rounded-2xl p-5 transition-all duration-300">
                                    <div class="flex justify-between items-start mb-2">
                                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-wider">
                                            {{ $label }} *
                                        </label>
                                        
                                        @if($isApproved)
                                            <span class="bg-emerald-200 text-emerald-700 text-[9px] font-bold px-2 py-0.5 rounded uppercase text-center">Approved</span>
                                        @elseif($isDeclined)
                                            <span class="bg-red-200 text-red-700 text-[9px] font-bold px-2 py-0.5 rounded uppercase animate-pulse text-center">Re-upload</span>
                                        @elseif($hasFile)
                                            <span class="bg-yellow-200 text-yellow-800 text-[9px] font-bold px-2 py-0.5 rounded uppercase text-center">Submitted</span>
                                        @endif
                                    </div>

                                    @if($isDeclined && $remark)
                                        <div class="mb-3 p-2 bg-white/60 border border-red-200 rounded-lg">
                                            <p class="text-[9px] font-bold text-red-600 uppercase">Admin Issue:</p>
                                            <p class="text-xs text-red-800 italic">"{{ $remark }}"</p>
                                        </div>
                                    @endif

                                    @if($isApproved)
                                        <div class="flex items-center gap-2 mt-2 opacity-75">
                                            <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            <span class="text-xs font-bold text-emerald-600">Accepted</span>
                                            <a href="{{ $files[$key] }}" target="_blank" class="ml-auto text-[10px] font-bold text-slate-400 hover:text-indigo-600 underline">VIEW</a>
                                        </div>
                                    @else
                                        <input type="file" 
                                               name="files[{{ $key }}]" 
                                               accept=".pdf,.jpg,.jpeg,.png,.heic,.heif"
                                               onchange="validateFile(this)"
                                               {{ (!$hasFile || $isDeclined) ? 'required' : '' }}
                                               class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase {{ $isDeclined ? 'file:bg-red-100 file:text-red-700' : 'file:bg-white file:text-indigo-600 hover:file:bg-indigo-600 hover:file:text-white' }} cursor-pointer transition-all mt-2 shadow-sm">
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- SUBMIT BUTTON --}}
                <div class="mt-12 pt-8 border-t border-slate-100 flex justify-center">
                    <button type="submit" id="submitBtn" class="group relative w-full md:w-auto bg-slate-900 hover:bg-blue-600 text-white font-black py-5 px-16 rounded-2xl shadow-xl shadow-slate-300 hover:shadow-blue-300 transition-all duration-300 transform hover:-translate-y-1">
                        <span class="relative z-10 uppercase tracking-widest text-xs flex items-center justify-center">
                            Submit Documents
                            <svg class="w-4 h-4 ml-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function validateFile(input) {
            const file = input.files[0];
            const alertBox = document.getElementById('file-error-alert');
            const msgBox = document.getElementById('file-error-msg');

            if (file) {
                const maxSize = 10 * 1024 * 1024; 
                if (file.size > maxSize) {
                    msgBox.innerText = `The file "${file.name}" is too large! Maximum allowed size is 10MB.`;
                    alertBox.classList.remove('hidden');
                    input.value = ""; 
                    return;
                }
                const allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'heic', 'heif'];
                const fileExt = file.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(fileExt)) {
                    msgBox.innerText = `The file type ".${fileExt}" is not allowed. Please upload PDF or Images only.`;
                    alertBox.classList.remove('hidden');
                    input.value = ""; 
                    return;
                }
                alertBox.classList.add('hidden');
            }
        }
    </script>

    <style>
        .bg-pattern { background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 24px 24px; }
    </style>
</x-applicant-layout>