<div class="min-h-screen bg-slate-50 py-8 lg:py-12 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-3xl mx-auto">
        
        {{-- HEADER --}}
        <div class="text-center mb-8 lg:mb-10 animate-fade-in-down">
            <img src="{{ asset('images/nas/nas-logo-sidebar.png') }}" class="h-20 mx-auto mb-4" alt="NAS Logo">
            <h1 class="text-2xl sm:text-3xl font-black text-slate-800 uppercase tracking-tight">
                Continuing Student Enrollment
            </h1>
            <p class="text-indigo-600 font-bold tracking-widest uppercase mt-2 text-xs sm:text-sm">
                School Year {{ date('Y') }}-{{ date('Y') + 1 }}
            </p>
        </div>

        @if (session()->has('message'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 shadow-sm flex items-center">
                <i class='bx bxs-check-circle text-xl mr-2 text-emerald-500'></i>
                <span class="text-sm font-bold">{{ session('message') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="submitEnrollment" class="bg-white shadow-xl shadow-indigo-100/50 rounded-[2rem] overflow-hidden border border-slate-100">
            
            <div class="h-2 bg-gradient-to-r from-blue-600 to-indigo-600 w-full"></div>
            
            <div class="p-6 sm:p-10 space-y-8">
                
                {{-- 1. STUDENT DETAILS (Read-Only) --}}
                <div>
                    <h3 class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-4 flex items-center border-b border-slate-100 pb-2">
                        <i class='bx bxs-user-badge mr-2 text-lg'></i> Student Information
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-200">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase">Student Name</label>
                            <p class="font-black text-slate-800 text-lg uppercase">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase">Grade Level to Enroll</label>
                            <select wire:model="grade_level" required class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold text-slate-700 bg-white cursor-pointer">
                                <option value="">Select Grade Level...</option>
                                <option value="Grade 8">Grade 8</option>
                                <option value="Grade 9">Grade 9</option>
                                <option value="Grade 10">Grade 10</option>
                                <option value="Grade 11">Grade 11</option>
                                <option value="Grade 12">Grade 12</option>
                            </select>
                            @error('grade_level') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- 2. UPLOAD REQUIREMENTS --}}
                <div>
                    <h3 class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-4 flex items-center border-b border-slate-100 pb-2">
                        <i class='bx bxs-file-archive mr-2 text-lg'></i> Renewal Requirements
                    </h3>
                    
                    <div class="space-y-5">
                        
                        {{-- ⚡ BAGO: Student-Athlete's Information Form ⚡ --}}
                        <div class="border-2 border-dashed border-slate-200 rounded-2xl p-5 hover:border-blue-400 transition-colors bg-slate-50/50">
                            <label class="block text-sm font-black text-slate-700 uppercase mb-1">1. Student-Athlete’s Information Form</label>
                            <p class="text-xs text-slate-500 mb-3">Please upload the filled-out Student-Athlete's Information Form (Required for Old & New Students).</p>
                            <input type="file" wire:model="sa_info_form" accept="image/*,.pdf" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            <div wire:loading wire:target="sa_info_form" class="text-xs text-blue-500 mt-2 italic font-bold">Uploading...</div>
                            @error('sa_info_form') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Report Card --}}
                        <div class="border-2 border-dashed border-slate-200 rounded-2xl p-5 hover:border-indigo-400 transition-colors bg-slate-50/50">
                            <label class="block text-sm font-black text-slate-700 uppercase mb-1">2. Previous Report Card (SF9)</label>
                            <p class="text-xs text-slate-500 mb-3">Please upload a clear picture or PDF of your final report card from the previous grade.</p>
                            <input type="file" wire:model="report_card" accept="image/*,.pdf" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                            <div wire:loading wire:target="report_card" class="text-xs text-indigo-500 mt-2 italic font-bold">Uploading...</div>
                            @error('report_card') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Medical Clearance --}}
                        <div class="border-2 border-dashed border-slate-200 rounded-2xl p-5 hover:border-emerald-400 transition-colors bg-slate-50/50">
                            <label class="block text-sm font-black text-slate-700 uppercase mb-1">3. Updated Medical Clearance</label>
                            <p class="text-xs text-slate-500 mb-3">Pre-participation Physical Evaluation (PPE) valid for the new school year.</p>
                            <input type="file" wire:model="medical_clearance" accept="image/*,.pdf" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                            <div wire:loading wire:target="medical_clearance" class="text-xs text-emerald-500 mt-2 italic font-bold">Uploading...</div>
                            @error('medical_clearance') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- SUBMIT BUTTON --}}
                <div class="pt-4 border-t border-slate-100">
                    <button type="submit" class="w-full bg-slate-900 hover:bg-indigo-600 text-white font-black py-4 rounded-xl transition-all transform hover:-translate-y-1 shadow-lg shadow-slate-200 uppercase tracking-widest text-sm flex items-center justify-center">
                        <span wire:loading.remove wire:target="submitEnrollment">Submit Renewal Application</span>
                        <span wire:loading wire:target="submitEnrollment">Processing...</span>
                        <i class='bx bx-right-arrow-alt ml-2 text-lg' wire:loading.remove wire:target="submitEnrollment"></i>
                    </button>
                    <p class="text-center text-[10px] text-slate-400 mt-3 uppercase tracking-wider">Ensure all documents are clear before submitting.</p>
                </div>

            </div>
        </form>
    </div>
</div>