<x-app-layout>
<div class="w-full py-6 sm:py-8 font-sans">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-8 lg:mb-10 bg-gradient-to-br from-white/95 to-slate-50/95 backdrop-blur-xl p-6 sm:p-8 rounded-3xl shadow-lg border border-white/80 relative overflow-hidden">
            {{-- Decorative blurs --}}
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600"></div>
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-blue-100/50 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-indigo-100/50 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 flex items-center justify-center gap-5 mb-5">
                <img src="{{ asset('images/nas/NAS LOGO.png') }}" class="h-16 sm:h-20 w-auto object-contain drop-shadow-md hover:scale-105 transition-transform duration-300">
                <div class="h-12 w-px bg-slate-300"></div>
                <img src="{{ asset('images/nas/NASCENT SAS.png') }}" class="h-16 sm:h-20 w-auto object-contain drop-shadow-md hover:scale-105 transition-transform duration-300">
            </div>
            <h1 class="relative z-10 text-2xl md:text-3xl font-black text-slate-800 uppercase tracking-tight">Continuing Student Enrollment</h1>
            <p class="relative z-10 text-indigo-600 font-extrabold uppercase mt-2 text-sm tracking-widest bg-indigo-50 inline-block px-4 py-1 rounded-full border border-indigo-100/50 shadow-sm">SY {{ date('Y') }}-{{ date('Y') + 1 }}</p>
        </div>

        @if ($applicant && $applicant->status === 'Renewal (Returned)')
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl shadow-sm animate-pulse">
                <div class="flex items-center gap-3">
                    <i class='bx bxs-error-circle text-red-500 text-xl'></i>
                    <div>
                        <p class="text-sm font-bold text-red-800">Application Returned for Revision</p>
                        @php
                            $overall = null;
                            if ($applicant && $applicant->document_remarks) {
                                $rem = is_string($applicant->document_remarks) ? json_decode($applicant->document_remarks, true) : $applicant->document_remarks;
                                $overall = $rem['overall'] ?? null;
                            }
                        @endphp
                        @if($overall)
                            <div class="mt-2 text-xs text-red-700 bg-red-100/50 p-2 rounded border border-red-200">
                                <strong>Registrar's Note:</strong> {{ $overall }}
                            </div>
                        @else
                            <p class="text-xs text-red-600 mt-0.5">Please check the highlighted documents below and re-upload the correct files.</p>
                        @endif
                    </div>
                </div>
            </div>
        @elseif (session()->has('success'))
            <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl font-bold flex items-center justify-center gap-2 border border-emerald-200">
                <i class='bx bxs-check-circle text-emerald-500 text-lg'></i> {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6 font-bold flex items-center gap-2">
                <i class='bx bxs-error-circle text-red-500 text-lg'></i> {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6 font-bold flex items-start gap-2">
                <i class='bx bxs-error-circle text-red-500 text-lg mt-0.5'></i>
                <div>
                    <p>Submitting failed. Please fix the following errors:</p>
                    <ul class="list-disc ml-5 mt-1 text-sm font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @php
            $renewalRemarks = $applicant->document_remarks ?? [];
            if(is_string($renewalRemarks)) $renewalRemarks = json_decode($renewalRemarks, true);
            $hasRenewalSubmission = isset($renewalRemarks['is_renewal']) && $renewalRemarks['is_renewal'] === true;
            $isRenewalApproved = $hasRenewalSubmission && in_array($applicant->status, ['Admitted', 'Officially Enrolled', 'Enrolled']);
        @endphp

        @if ($applicant && $isRenewalApproved)
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 p-8 rounded-3xl shadow-xl border border-indigo-400/30 text-white text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mt-20 -mr-20 blur-2xl"></div>
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-4">
                        <i class='bx bxs-check-circle text-4xl text-emerald-400'></i>
                    </div>
                    <h2 class="text-2xl font-black uppercase tracking-tight mb-2">Congratulations!</h2>
                    <p class="text-indigo-100 font-medium max-w-lg mb-6">Your scholarship renewal has been verified and approved by the Registrar. You are officially enrolled for SY {{ date('Y') }}-{{ date('Y') + 1 }}.</p>
                    <a href="{{ route('student.dashboard') }}" class="bg-white text-indigo-700 px-6 py-2.5 rounded-xl font-black uppercase text-xs tracking-widest hover:bg-indigo-50 transition-colors shadow-lg">
                        Return to Dashboard
                    </a>
                </div>
            </div>
        @else

        <form action="{{ route('student.renew-enrollment.store') }}" method="POST" enctype="multipart/form-data" class="w-full" x-data="{ uploading: false }" @submit="uploading = true">
            @csrf
            <div class="space-y-8">
                {{-- Student Info --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-white/95 backdrop-blur-md p-5 rounded-2xl border border-white/80 shadow-lg">
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase">Student Name</label>
                        <p class="font-black text-slate-800 text-lg uppercase">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase">Student No.</label>
                        <p class="font-black text-slate-800 text-lg uppercase">{{ Auth::user()->student->nas_student_id ?? Auth::user()->student_id ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <input type="hidden" name="grade_level" value="{{ $grade_level }}">
                        <label class="text-[10px] font-bold text-slate-500 uppercase">Grade Level to Enroll</label>
                        <div class="mt-1 flex items-center justify-between p-3 bg-indigo-50/50 rounded-xl border border-indigo-100 shadow-inner">
                            <span class="font-black text-indigo-700 text-lg uppercase drop-shadow-sm">{{ $grade_level }}</span>
                            <span class="px-2.5 py-1 bg-indigo-100 text-indigo-600 rounded-md text-[9px] font-black uppercase tracking-widest border border-indigo-200 shadow-sm">Auto-computed</span>
                        </div>
                    </div>
                </div>

                {{-- Files --}}
                <div class="space-y-5">
                    @php 
                        $fields = [
                            ['model' => 'sa_info_form', 'key' => 'renewal_sa_info_form', 'label' => '1. Student-Athlete’s Information Form', 'desc' => 'Upload filled-out Student-Athlete Information form.'],
                            ['model' => 'basic_ed_form', 'key' => 'renewal_basic_ed_form', 'label' => '2. Basic Education Enrollment Form', 'desc' => 'Upload filled-out Basic Education Enrollment form.'],
                            ['model' => 'scholarship_agreement', 'key' => 'renewal_scholarship_agreement', 'label' => '3. Scholarship Agreement', 'desc' => 'Upload signed Scholarship Agreement.'],
                            ['model' => 'uniform_measurement', 'key' => 'renewal_uniform_measurement', 'label' => '4. Student Uniform Measurement Form', 'desc' => 'Upload filled-out Uniform Measurement form.'],
                            ['model' => 'health_assessment', 'key' => 'renewal_health_assessment', 'label' => '5. Pre-ingress Health Assessment Forms', 'desc' => 'Upload Health Assessment forms.'],
                            ['model' => 'passport', 'key' => 'renewal_passport', 'label' => '6. Passport of the Student-Athlete (if available)', 'desc' => 'Upload clear image/PDF.'],
                            ['model' => 'mother_id', 'key' => 'renewal_mother_id', 'label' => '7. Mother’s valid Government-Issued ID', 'desc' => 'With signature (Optional).'],
                            ['model' => 'father_id', 'key' => 'renewal_father_id', 'label' => '8. Father’s valid Government-Issued ID', 'desc' => 'With signature (Optional).'],
                            ['model' => 'guardian_id', 'key' => 'renewal_guardian_id', 'label' => '9. Designated Guardian’s valid Government-Issued ID', 'desc' => 'With signature.'],
                            ['model' => 'student_id_file', 'key' => 'renewal_student_id', 'label' => '10. Student’s School ID (Optional)', 'desc' => 'Upload clear image/PDF of your NAS School ID.']
                        ]; 
                    @endphp

                    @foreach($fields as $field)
                    @php
                        $model = $field['model'];
                        $label = $field['label'];
                        $desc = $field['desc'];
                        $dbKey = $field['key'];
                        
                        $isDeclined = isset($statuses[$dbKey]) && $statuses[$dbKey] === 'declined';
                        $isPending = isset($statuses[$dbKey]) && $statuses[$dbKey] === 'pending';
                        $isApproved = isset($statuses[$dbKey]) && $statuses[$dbKey] === 'approved';
                        $hasFile = isset($currentFiles[$dbKey]);
                        $remark = $remarks['documents'][$dbKey] ?? ($remarks[$dbKey] ?? null);
                    @endphp

                    <div class="p-5 sm:p-6 rounded-3xl border transition-all shadow-md backdrop-blur-xl hover:shadow-lg hover:-translate-y-0.5 duration-300 {{ $isDeclined ? 'border-red-300 bg-red-50' : 'border-white/80 bg-white/90 hover:border-indigo-300/60' }}">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-black text-slate-800 uppercase tracking-wide">
                                {{ $label }} 
                                @if(str_contains($label, '(Optional)'))
                                    <span class="text-[10px] font-bold text-slate-400 normal-case ml-2 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">Optional</span>
                                @else
                                    <span class="text-red-500 ml-0.5 text-lg leading-none">*</span>
                                @endif
                            </label>

                            @if($isDeclined)
                                <span class="bg-red-100 text-red-700 text-[10px] px-2.5 py-1 rounded-lg uppercase tracking-wider font-bold border border-red-200 shadow-sm flex items-center gap-1"><i class='bx bxs-error-circle'></i> Needs Revision</span>
                            @elseif($isPending && $applicant && $applicant->status !== 'Renewal (Returned)')
                                <span class="bg-amber-100 text-amber-700 text-[10px] px-2.5 py-1 rounded-lg uppercase tracking-wider font-bold border border-amber-200 shadow-sm flex items-center gap-1"><i class='bx bx-time-five'></i> Pending Review</span>
                            @elseif($isApproved)
                                <span class="bg-emerald-100 text-emerald-700 text-[10px] px-2.5 py-1 rounded-lg uppercase tracking-wider font-bold border border-emerald-200 shadow-sm flex items-center gap-1"><i class='bx bx-check-double'></i> Approved</span>
                            @elseif($hasFile && !$applicant)
                                <span class="bg-indigo-100 text-indigo-700 text-[10px] px-2.5 py-1 rounded-lg uppercase tracking-wider font-bold border border-indigo-200 shadow-sm flex items-center gap-1"><i class='bx bx-cloud-upload'></i> Uploaded</span>
                            @elseif($hasFile)
                                <div class="flex items-center gap-2">
                                    <span class="bg-emerald-100 text-emerald-700 text-[10px] px-2.5 py-1 rounded-lg uppercase tracking-wider font-bold border border-emerald-200 shadow-sm flex items-center gap-1"><i class='bx bx-check'></i> File Exists</span>
                                    <a href="{{ $currentFiles[$dbKey] }}" target="_blank" class="text-[9px] font-bold text-indigo-600 hover:text-indigo-800 underline uppercase tracking-tighter">View</a>
                                </div>
                            @endif
                        </div>

                        @if($isDeclined && $remark)
                            <div class="text-[11px] text-red-700 mb-4 bg-red-100/50 p-3 rounded-xl border border-red-200 flex gap-2 items-start text-left">
                                <i class='bx bxs-message-square-error text-lg mt-0.5'></i>
                                <div><strong class="uppercase text-[10px] block mb-0.5">Registrar Remark:</strong> {{ $remark }}</div>
                            </div>
                        @else
                            <p class="text-[11px] text-slate-500 mb-4 font-medium">{{ $desc }}</p>
                        @endif

                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-50 to-blue-50 opacity-0 group-hover:opacity-100 rounded-2xl transition-opacity duration-300"></div>
                            
                            <input type="file" name="{{ $model }}" accept=".pdf,.jpg,.jpeg,.png"
                                   class="relative z-10 block w-full text-xs text-slate-600 font-medium
                                          file:mr-4 file:py-2.5 file:px-5
                                          file:rounded-xl file:border-0
                                          file:text-xs file:font-bold file:uppercase file:tracking-wider
                                          file:bg-indigo-600 file:text-white file:shadow-md
                                           hover:file:bg-indigo-700 file:transition-all hover:file:-translate-y-0.5
                                          cursor-pointer border-2 border-dashed border-slate-300 hover:border-indigo-400 rounded-2xl bg-white/50 px-2 py-2 transition-all">
                        </div>
                        <p class="text-[9px] text-slate-400 mt-1.5 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Accepted: JPG, JPEG, PNG, PDF &bull; Max size: 20MB
                        </p>
                        @error($model) <span class="text-red-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    @endforeach
                </div>

                <button type="submit" :disabled="uploading" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl shadow-[0_8px_30px_rgb(79,70,229,0.3)] hover:shadow-[0_8px_30px_rgb(79,70,229,0.5)] transition-all duration-300 hover:-translate-y-1 uppercase tracking-widest text-sm disabled:opacity-50 disabled:cursor-not-allowed mt-4">
                    <span x-show="!uploading" class="flex items-center justify-center gap-2">
                        Submit Renewal Application <i class='bx bx-right-arrow-alt text-xl'></i>
                    </span>
                    <span x-show="uploading" style="display: none;" class="flex items-center justify-center gap-2">
                        <i class='bx bx-loader-alt animate-spin text-xl'></i> Uploading...
                    </span>
                </button>
            </div>
        </form>
        @endif
    </div>
</div>
</x-app-layout>
