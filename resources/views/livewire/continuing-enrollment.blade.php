<div class="min-h-screen bg-slate-50 py-8 lg:py-12 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-8 lg:mb-10">
            <img src="{{ asset('images/nas/nas-logo-sidebar.png') }}" class="h-20 mx-auto mb-4">
            <h1 class="text-2xl font-black text-slate-800 uppercase">Continuing Student Enrollment</h1>
            <p class="text-indigo-600 font-bold uppercase mt-2 text-sm">SY {{ date('Y') }}-{{ date('Y') + 1 }}</p>
        </div>

        @if (session()->has('message'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 font-bold flex items-center">
                <i class='bx bxs-check-circle mr-2 text-xl'></i> {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6 font-bold">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit="submitEnrollment" class="bg-white shadow-xl rounded-[2rem] overflow-hidden border border-slate-100">
            <div class="h-2 bg-gradient-to-r from-blue-600 to-indigo-600 w-full"></div>
            <div class="p-6 sm:p-10 space-y-8">
                {{-- Student Info --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-5 rounded-2xl border">
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase">Student Name</label>
                        <p class="font-black text-slate-800 text-lg uppercase">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase">Grade Level to Enroll</label>
                        <select wire:model.live="grade_level" class="mt-1 block w-full rounded-xl border-slate-300 text-sm font-bold">
                            <option value="">Select Grade Level...</option>
                            @foreach(['Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'] as $level)
                                <option value="{{ $level }}">{{ $level }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Files --}}
                <div class="space-y-5">
                    @php $fields = [
                        ['model' => 'sa_info_form', 'key' => 'renewal_sa_info_form', 'label' => '1. Student-Athlete’s Information Form', 'desc' => 'Upload filled-out Student-Athlete Information form.'],
                        ['model' => 'report_card', 'key' => 'renewal_report_card', 'label' => '2. Report Card (SF9)', 'desc' => 'Upload clear image/PDF of SF9.'],
                        ['model' => 'medical_clearance', 'key' => 'renewal_medical_clearance', 'label' => '3. Medical Clearance', 'desc' => 'Valid PPE for the new SY.']
                    ]; @endphp

                    @foreach($fields as $field)
                    @php
                        $status = $statuses[$field['key']] ?? 'pending';
                        $isUploaded = isset($currentFiles[$field['key']]) && !empty($currentFiles[$field['key']]);
                        $remark = $remarks[$field['key']] ?? null;

                        $statusClasses = match($status) {
                            'approved' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            'declined' => 'bg-red-100 text-red-800 border-red-200',
                            default => 'bg-amber-100 text-amber-800 border-amber-200',
                        };
                    @endphp
                    <div class="border-2 {{ $status === 'declined' ? 'border-red-300 bg-red-50/30' : 'border-slate-200 bg-slate-50/50' }} rounded-2xl p-5 transition-all">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <label class="block text-sm font-black text-slate-700 uppercase">{{ $field['label'] }}</label>
                                <p class="text-xs text-slate-500">{{ $field['desc'] }}</p>
                            </div>
                            @if($isUploaded)
                                <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full border shadow-sm {{ $statusClasses }}">
                                    {{ $status }}
                                </span>
                            @endif
                        </div>

                        @if($status === 'approved')
                            <div class="flex items-center text-emerald-600 text-xs font-bold py-2">
                                <i class='bx bxs-check-circle mr-1.5 text-lg'></i> Document verified and approved.
                            </div>
                        @else
                            <input type="file" wire:model="{{ $field['model'] }}" accept="image/*,.pdf" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-slate-900 file:text-white hover:file:bg-indigo-600 transition cursor-pointer">
                            <div wire:loading wire:target="{{ $field['model'] }}" class="text-[10px] text-blue-500 mt-2 italic font-bold">Uploading to temporary storage...</div>
                        @endif

                        @if($status === 'declined' && $remark)
                            <div class="mt-4 p-3 bg-red-100/50 border border-red-200 rounded-xl">
                                <label class="block text-[10px] font-black text-red-600 uppercase mb-1">Registrar Remarks:</label>
                                <p class="text-xs text-red-800 font-bold italic">"{{ $remark }}"</p>
                                <p class="text-[10px] text-red-500 mt-2 font-medium underline uppercase tracking-wider">Please upload a corrected document above.</p>
                            </div>
                        @endif

                        @error($field['model']) <span class="text-red-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    @endforeach
                </div>

                <button type="submit" wire:loading.attr="disabled" class="w-full bg-slate-900 hover:bg-indigo-600 text-white font-black py-4 rounded-xl shadow-lg uppercase tracking-widest text-sm disabled:opacity-50">
                    <span wire:loading.remove wire:target="submitEnrollment">Submit Renewal Application</span>
                    <span wire:loading wire:target="submitEnrollment">Processing to Cloudinary...</span>
                </button>
            </div>
        </form>
    </div>
</div>