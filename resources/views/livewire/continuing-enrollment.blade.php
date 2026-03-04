<div class="w-full py-6 sm:py-8 font-sans">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-8 lg:mb-10 bg-white/60 backdrop-blur-xl p-6 sm:p-8 rounded-3xl shadow-sm border border-white/50">
            <img src="{{ asset('images/nas/nas-logo-sidebar.png') }}" class="h-16 sm:h-20 mx-auto mb-4 drop-shadow-sm">
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Continuing Student Enrollment</h1>
            <p class="text-indigo-600 font-black uppercase mt-2 text-sm tracking-widest">SY {{ date('Y') }}-{{ date('Y') + 1 }}</p>
        </div>

        @if ($applicant && $applicant->status === 'Renewal (Returned)')
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl shadow-sm animate-pulse">
                <div class="flex items-center gap-3">
                    <i class='bx bxs-error-circle text-red-500 text-xl'></i>
                    <div>
                        <p class="text-sm font-bold text-red-800">Application Returned for Revision</p>
                        <p class="text-xs text-red-600 mt-0.5">Please check the highlighted documents below and re-upload the correct files.</p>
                    </div>
                </div>
            </div>
        @elseif (session()->has('message'))
            <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl font-bold flex items-center justify-center gap-2 border border-emerald-200">
                <i class='bx bxs-check-circle text-emerald-500 text-lg'></i> {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6 font-bold">
                {{ session('error') }}
            </div>
        @endif

        @if ($applicant && in_array($applicant->status, ['Admitted', 'Officially Enrolled', 'Enrolled']))
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

        <form wire:submit="submitEnrollment" class="w-full">
            <div class="space-y-8">
                {{-- Student Info --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-white/40 backdrop-blur-md p-5 rounded-2xl border border-white/50 shadow-sm">
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase">Student Name</label>
                        <p class="font-black text-slate-800 text-lg uppercase">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase">Grade Level to Enroll</label>
                        <div class="mt-1 flex items-center justify-between p-3 bg-indigo-50/50 rounded-xl border border-indigo-100 shadow-inner">
                            <span class="font-black text-indigo-700 text-lg uppercase drop-shadow-sm">{{ $grade_level }}</span>
                            <span class="px-2.5 py-1 bg-indigo-100 text-indigo-600 rounded-md text-[9px] font-black uppercase tracking-widest border border-indigo-200 shadow-sm">Auto-computed</span>
                        </div>
                    </div>
                </div>

                {{-- Files --}}
                <div class="space-y-5">
                    @php $fields = [
                        ['model' => 'sa_info_form', 'key' => 'renewal_sa_info_form', 'label' => '1. Student-Athlete’s Information Form', 'desc' => 'Upload filled-out Student-Athlete Information form.'],
                        ['model' => 'basic_ed_form', 'key' => 'renewal_basic_ed_form', 'label' => '2. Basic Education Enrollment Form', 'desc' => 'Upload filled-out Basic Education Enrollment form.'],
                        ['model' => 'scholarship_agreement', 'key' => 'renewal_scholarship_agreement', 'label' => '3. Scholarship Agreement', 'desc' => 'Upload signed Scholarship Agreement.'],
                        ['model' => 'uniform_measurement', 'key' => 'renewal_uniform_measurement', 'label' => '4. Student Uniform Measurement Form', 'desc' => 'Upload filled-out Uniform Measurement form.'],
                        ['model' => 'health_assessment', 'key' => 'renewal_health_assessment', 'label' => '5. Pre-ingress Health Assessment Forms', 'desc' => 'Upload Health Assessment forms.'],
                        ['model' => 'passport', 'key' => 'renewal_passport', 'label' => '6. Passport of the Student-Athlete (if available)', 'desc' => 'Upload clear image/PDF.'],
                        ['model' => 'mother_id', 'key' => 'renewal_mother_id', 'label' => '7. Mother’s valid Government-Issued ID', 'desc' => 'With signature (not required for all).'],
                        ['model' => 'father_id', 'key' => 'renewal_father_id', 'label' => '8. Father’s valid Government-Issued ID', 'desc' => 'With signature (not required for all).'],
                        ['model' => 'guardian_id', 'key' => 'renewal_guardian_id', 'label' => '9. Designated Guardian’s valid Government-Issued ID', 'desc' => 'With signature.']
                    ]; @endphp

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
                        $remark = $remarks[$dbKey] ?? null;
                    @endphp

                    <div class="p-4 sm:p-5 rounded-2xl border transition-all shadow-sm backdrop-blur-md {{ $isDeclined ? 'border-red-400 bg-red-50/70' : 'border-white/60 bg-white/40 hover:border-indigo-300' }}">
                        <label class="block text-xs font-black text-slate-700 uppercase mb-1.5 flex items-center justify-between">
                            <span>
                                {{ $label }} 
                                @if(str_contains($label, '(Optional)'))
                                    <span class="text-[9px] font-bold text-slate-400 normal-case ml-1">(Optional)</span>
                                @else
                                    <span class="text-red-500 ml-0.5">*</span>
                                @endif
                            </span>

                            @if($isDeclined)
                                <span class="bg-red-100 text-red-600 text-[9px] px-2 py-0.5 rounded-md uppercase tracking-wider">Needs Revision</span>
                            @elseif($isPending && $applicant && $applicant->status !== 'Renewal (Returned)')
                                <span class="bg-amber-100 text-amber-600 text-[9px] px-2 py-0.5 rounded-md uppercase tracking-wider">Pending Review</span>
                            @elseif($isApproved)
                                <span class="bg-emerald-100 text-emerald-600 text-[9px] px-2 py-0.5 rounded-md uppercase tracking-wider"><i class='bx bx-check'></i> Approved</span>
                            @elseif($hasFile && !$applicant)
                                <span class="bg-indigo-100 text-indigo-600 text-[9px] px-2 py-0.5 rounded-md uppercase tracking-wider"><i class='bx bx-check'></i> Uploaded</span>
                            @endif
                        </label>

                        @if($isDeclined && isset($remarks[$dbKey]))
                            <p class="text-[10px] text-red-600 mb-3 bg-red-100/50 p-2 rounded-lg border border-red-200"><strong class="uppercase text-[9px]">Registrar Remark:</strong> {{ $remarks[$dbKey] }}</p>
                        @else
                            <p class="text-[10px] text-slate-500 mb-3">{{ $desc }}</p>
                        @endif

                        <div class="relative">
                            <input type="file" wire:model="{{ $model }}" accept=".pdf,.jpg,.jpeg,.png"
                                   class="block w-full text-xs text-slate-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-xl file:border-0
                                          file:text-xs file:font-bold
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100 transition-colors cursor-pointer">
                            
                            <!-- Loading Indicator for this specific file -->
                            <div wire:loading wire:target="{{ $model }}" class="absolute right-3 top-1/2 -translate-y-1/2">
                                <svg class="animate-spin h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                        @error($field['model']) <span class="text-red-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    @endforeach
                </div>

                <button type="submit" wire:loading.attr="disabled" class="w-full bg-slate-900 hover:bg-indigo-600 text-white font-black py-4 rounded-xl shadow-lg uppercase tracking-widest text-sm disabled:opacity-50">
                    <span wire:loading.remove wire:target="submitEnrollment">Submit Renewal Application</span>
                    <span wire:loading wire:target="submitEnrollment">Uploading files...</span>
                </button>
            </div>
        </form>
        @endif
    </div>
</div>