<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center no-print">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Official Enrollment Verification') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('official-enrollment.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-xs uppercase transition">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex items-center no-print">
                    <svg class="h-6 w-6 mr-3 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                {{-- LEFT: STUDENT DATA --}}
                <div class="lg:col-span-3 bg-white shadow-xl rounded-3xl overflow-hidden border border-slate-200">
                    <div class="bg-indigo-900 text-white p-8 border-b-4 border-yellow-400">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                            <div>
                                <h1 class="text-2xl font-black uppercase tracking-widest">Enrollment Application</h1>
                                <p class="text-sm text-indigo-300 font-mono mt-1">Applicant ID: {{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div class="mt-4 md:mt-0">
                                <span class="bg-yellow-400 text-indigo-900 text-xs font-black px-4 py-2 rounded-full uppercase">
                                    {{ $application->status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 space-y-12">
                        {{-- 1. BASIC INFO --}}
                        <section>
                            <h3 class="text-indigo-900 font-black uppercase text-sm border-b-2 border-slate-100 pb-2 mb-6 flex items-center">
                                <span class="bg-indigo-600 text-white w-6 h-6 rounded-md flex items-center justify-center text-xs mr-3">1</span> 
                                Student Profile Summary
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Full Name</span>
                                    <span class="block font-bold text-slate-800 uppercase">{{ $application->first_name }} {{ $application->last_name }}</span>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase">LRN</span>
                                    <span class="block font-mono font-bold text-slate-800">{{ $application->lrn }}</span>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Sport Discipline</span>
                                    <span class="block font-bold text-indigo-700 uppercase">{{ $application->sport }}</span>
                                </div>
                            </div>
                        </section>

                        {{-- 2. ENROLLMENT DOCUMENTS (FROM NEW TABLE) --}}
                        <section>
                            <h3 class="text-indigo-900 font-black uppercase text-sm border-b-2 border-slate-100 pb-2 mb-6 flex items-center">
                                <span class="bg-indigo-600 text-white w-6 h-6 rounded-md flex items-center justify-center text-xs mr-3">2</span> 
                                Submitted Enrollment Records
                            </h3>

                            <form action="{{ route('official-enrollment.return', $application->id) }}" method="POST">
                                @csrf @method('PATCH')

                                <div class="space-y-3">
                                    @php
                                        $enrollment = $application->enrollmentRecord;
                                        
                                        // Requirements mapping: 'db_column' => 'Label'
                                        $requirements = [
                                            'scholarship_form' => 'Scholarship Application Form',
                                            'student_athlete_profile_form' => 'Student-Athlete Profile Form',
                                            'ppe_clearance_form' => 'PPE Clearance Form (Medical)',
                                            'psa_birth_certificate' => 'PSA Birth Certificate (Original)',
                                            'report_card' => 'Official Report Card (SF9)',
                                            'guardian_valid_id' => 'Guardian Valid Gov-Issued ID',
                                            'kukkiwon_certificate' => 'Kukkiwon Certificate',
                                            'ip_certification' => 'IP Certification',
                                            'pwd_id' => 'PWD ID',
                                            'four_ps_certification' => '4Ps Certification/ID'
                                        ];

                                        $remarks = $enrollment->document_remarks ?? [];
                                    @endphp

                                    @foreach($requirements as $column => $label)
                                        @php
                                            // Check visibility logic
                                            $show = true;
                                            if ($column == 'kukkiwon_certificate' && !str_contains($application->sport, 'Taekwondo')) $show = false;
                                            if ($column == 'ip_certification' && !$application->is_ip) $show = false;
                                            if ($column == 'pwd_id' && !$application->is_pwd) $show = false;
                                            if ($column == 'four_ps_certification' && !$application->is_4ps) $show = false;
                                        @endphp

                                        @if($show)
                                            <div class="flex flex-col md:flex-row md:items-center justify-between p-5 border border-slate-200 rounded-2xl bg-white hover:shadow-md transition">
                                                <div class="w-full md:w-1/3 mb-3 md:mb-0">
                                                    <p class="text-xs font-black text-slate-700 uppercase tracking-tight">{{ $label }}</p>
                                                    @if($enrollment && $enrollment->$column)
                                                        <span class="text-[10px] font-bold text-green-600 flex items-center mt-1">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                                            File Attached
                                                        </span>
                                                    @else
                                                        <span class="text-[10px] font-bold text-red-400 flex items-center mt-1">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                                            Missing Document
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="flex-1 flex gap-4 items-center">
                                                    <div class="w-24 text-center">
                                                        @if($enrollment && $enrollment->$column)
                                                            <a href="{{ $enrollment->$column }}" target="_blank" class="text-[10px] font-black bg-indigo-50 text-indigo-700 px-3 py-1.5 rounded-lg hover:bg-indigo-600 hover:text-white transition">VIEW FILE</a>
                                                        @else
                                                            <span class="text-[10px] font-bold text-slate-300 italic">None</span>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1">
                                                        <input type="text" name="remarks[{{ $column }}]" value="{{ $remarks[$column] ?? '' }}" 
                                                            class="w-full text-xs border-slate-200 rounded-xl focus:ring-red-400 focus:border-red-400" 
                                                            placeholder="Add correction notes here if needed...">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                <div class="mt-8 flex justify-end">
                                    <button type="submit" onclick="return confirm('Confirm return to student?')" class="bg-red-50 text-red-600 border border-red-100 hover:bg-red-600 hover:text-white font-black py-3 px-6 rounded-2xl text-xs uppercase tracking-widest transition">
                                        Return for Corrections
                                    </button>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>

                {{-- RIGHT: ACTION PANEL --}}
                <div class="lg:col-span-1 space-y-6 no-print">
                    <div class="bg-white shadow-xl rounded-3xl border border-indigo-50 overflow-hidden sticky top-8">
                        <div class="bg-indigo-600 p-6">
                            <h3 class="text-white font-bold text-center uppercase tracking-tighter italic">Process Enrollment</h3>
                        </div>
                        <div class="p-6">
                            <p class="text-[10px] text-slate-500 mb-6 text-center leading-relaxed">Ensure all forms are verified before final approval. This action is permanent.</p>

                            <form action="{{ route('official-enrollment.store', $application->id) }}" method="POST">
                                @csrf
                                
                                <div class="mb-6">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Student ID Assignment</label>
                                    <div class="bg-slate-100 p-4 rounded-2xl border-2 border-slate-200 text-center">
                                        <span class="font-mono text-xl font-black text-slate-800">
                                            {{ date('Y') }}-{{ str_pad($application->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-8">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Assign to Section <span class="text-red-500">*</span></label>
                                    <select name="section_id" required class="w-full text-sm border-slate-200 rounded-2xl py-3 focus:ring-indigo-500">
                                        <option value="" disabled selected>-- Select Section --</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->section_name }} ({{ $section->adviser_name }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" onclick="return confirm('Confirm official enrollment?')" 
                                    class="w-full bg-green-500 hover:bg-green-600 text-white font-black py-4 px-6 rounded-2xl shadow-lg shadow-green-100 transition transform hover:-translate-y-1 uppercase tracking-widest text-xs flex justify-center items-center">
                                    <span>Confirm & Enroll</span>
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>