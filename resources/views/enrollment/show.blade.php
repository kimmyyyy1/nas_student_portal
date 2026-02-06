<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center no-print">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Enrollment Verification & Processing') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('official-enrollment.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-xs uppercase tracking-widest transition">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- STATUS ALERT --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex items-center no-print">
                    <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                {{-- LEFT COLUMN: ENROLLMENT FORM DATA (Read-Only View) --}}
                <div class="lg:col-span-3 bg-white shadow-xl rounded-2xl overflow-hidden border border-slate-200">
                    
                    {{-- HEADER --}}
                    <div class="bg-indigo-900 text-white p-6 border-b-4 border-yellow-400 flex justify-between items-center">
                        <div>
                            <h1 class="text-xl font-black uppercase tracking-widest">Official Enrollment Data</h1>
                            <p class="text-xs text-slate-400 font-mono mt-1">APP ID: {{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="text-right">
                            <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full uppercase border border-green-200">
                                {{ $application->status }}
                            </span>
                        </div>
                    </div>

                    <div class="p-8">
                        {{-- 1. STUDENT INFORMATION --}}
                        <div class="mb-10">
                            <h3 class="text-indigo-900 font-bold uppercase text-sm border-b border-gray-200 pb-2 mb-4 flex items-center">
                                <span class="bg-indigo-600 text-white w-6 h-6 rounded flex items-center justify-center text-xs mr-2">1</span> 
                                Student Information
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                    <span class="block text-[10px] font-bold text-gray-500 uppercase">LRN</span>
                                    <span class="block text-lg font-mono font-bold text-gray-800">{{ $application->lrn }}</span>
                                </div>
                                <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                    <span class="block text-[10px] font-bold text-gray-500 uppercase">Email</span>
                                    <span class="block text-lg font-bold text-gray-800">{{ $application->email_address }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                <div><label class="text-[10px] text-gray-500 font-bold uppercase">Last Name</label><div class="font-bold border-b border-gray-200 text-sm">{{ $application->last_name }}</div></div>
                                <div><label class="text-[10px] text-gray-500 font-bold uppercase">First Name</label><div class="font-bold border-b border-gray-200 text-sm">{{ $application->first_name }}</div></div>
                                <div><label class="text-[10px] text-gray-500 font-bold uppercase">Middle Name</label><div class="font-bold border-b border-gray-200 text-sm">{{ $application->middle_name }}</div></div>
                                <div><label class="text-[10px] text-gray-500 font-bold uppercase">Ext.</label><div class="font-bold border-b border-gray-200 text-sm">{{ $application->extension_name ?? 'N/A' }}</div></div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div><label class="text-[10px] text-gray-500 font-bold uppercase">Date of Birth</label><div class="font-bold border-b border-gray-200 text-sm">{{ \Carbon\Carbon::parse($application->date_of_birth)->format('F d, Y') }}</div></div>
                                <div><label class="text-[10px] text-gray-500 font-bold uppercase">Age</label><div class="font-bold border-b border-gray-200 text-sm">{{ $application->age }}</div></div>
                                <div><label class="text-[10px] text-gray-500 font-bold uppercase">Sex</label><div class="font-bold border-b border-gray-200 text-sm">{{ $application->gender }}</div></div>
                            </div>

                            <div class="bg-slate-50 p-4 rounded border border-gray-200">
                                <label class="text-[10px] text-gray-500 font-bold uppercase block mb-1">Permanent Address</label>
                                <p class="text-sm font-bold text-gray-800">{{ $application->street_address }}, {{ $application->barangay }}</p>
                                <p class="text-xs text-gray-600">{{ $application->municipality_city }}, {{ $application->province }}</p>
                                <p class="text-xs text-gray-600">{{ $application->region }} (Zip: {{ $application->zip_code }})</p>
                            </div>

                            <div class="grid grid-cols-3 gap-4 mt-4 text-xs font-bold">
                                <div class="flex items-center"><span class="w-3 h-3 border border-gray-400 mr-2 flex items-center justify-center">{{ $application->is_ip ? '✓' : '' }}</span> IP Member</div>
                                <div class="flex items-center"><span class="w-3 h-3 border border-gray-400 mr-2 flex items-center justify-center">{{ $application->is_pwd ? '✓' : '' }}</span> PWD</div>
                                <div class="flex items-center"><span class="w-3 h-3 border border-gray-400 mr-2 flex items-center justify-center">{{ $application->is_4ps ? '✓' : '' }}</span> 4Ps</div>
                            </div>
                        </div>

                        {{-- 2. SPORTS --}}
                        <div class="mb-10">
                            <h3 class="text-indigo-900 font-bold uppercase text-sm border-b border-gray-200 pb-2 mb-4 flex items-center">
                                <span class="bg-indigo-600 text-white w-6 h-6 rounded flex items-center justify-center text-xs mr-2">2</span> 
                                Sports Discipline
                            </h3>
                            <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4">
                                <span class="block text-xs font-bold text-indigo-400 uppercase">Selected Main Sport</span>
                                <span class="block text-xl font-black text-indigo-900 uppercase">{{ $application->sport }}</span>
                            </div>
                        </div>

                        {{-- 3. FAMILY BACKGROUND (Data from Enrollment Form) --}}
                        <div class="mb-10">
                            <h3 class="text-indigo-900 font-bold uppercase text-sm border-b border-gray-200 pb-2 mb-4 flex items-center">
                                <span class="bg-indigo-600 text-white w-6 h-6 rounded flex items-center justify-center text-xs mr-2">3</span> 
                                Family Background
                            </h3>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                {{-- Father --}}
                                <div class="border border-gray-200 rounded p-4">
                                    <span class="text-xs font-bold text-gray-400 uppercase block mb-2 border-b pb-1">Father's Info</span>
                                    <div class="mb-2"><span class="text-[10px] text-gray-500 block">NAME</span><span class="font-bold text-sm">{{ $application->father_name ?? 'N/A' }}</span></div>
                                    <div class="mb-2"><span class="text-[10px] text-gray-500 block">CONTACT</span><span class="font-bold text-sm">{{ $application->father_contact ?? 'N/A' }}</span></div>
                                    <div class="mb-2"><span class="text-[10px] text-gray-500 block">EMAIL</span><span class="font-bold text-sm">{{ $application->father_email ?? 'N/A' }}</span></div>
                                </div>

                                {{-- Mother --}}
                                <div class="border border-gray-200 rounded p-4">
                                    <span class="text-xs font-bold text-gray-400 uppercase block mb-2 border-b pb-1">Mother's Info</span>
                                    <div class="mb-2"><span class="text-[10px] text-gray-500 block">NAME</span><span class="font-bold text-sm">{{ $application->mother_name ?? 'N/A' }}</span></div>
                                    <div class="mb-2"><span class="text-[10px] text-gray-500 block">CONTACT</span><span class="font-bold text-sm">{{ $application->mother_contact ?? 'N/A' }}</span></div>
                                    <div class="mb-2"><span class="text-[10px] text-gray-500 block">EMAIL</span><span class="font-bold text-sm">{{ $application->mother_email ?? 'N/A' }}</span></div>
                                </div>

                                {{-- Guardian --}}
                                <div class="lg:col-span-2 bg-slate-50 border border-slate-200 rounded p-4">
                                    <span class="text-xs font-bold text-indigo-500 uppercase block mb-2 border-b border-indigo-200 pb-1">Designated Guardian</span>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div><span class="text-[10px] text-gray-500 block">NAME</span><span class="font-bold text-sm">{{ $application->guardian_name }}</span></div>
                                        <div><span class="text-[10px] text-gray-500 block">RELATIONSHIP</span><span class="font-bold text-sm">{{ $application->guardian_relationship }}</span></div>
                                        <div><span class="text-[10px] text-gray-500 block">CONTACT</span><span class="font-bold text-sm">{{ $application->guardian_contact }}</span></div>
                                        <div class="md:col-span-3"><span class="text-[10px] text-gray-500 block">ADDRESS</span><span class="font-bold text-sm">{{ $application->guardian_address }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 4. SCHOOL INFORMATION (Data from Enrollment Form) --}}
                        <div class="mb-10">
                            <h3 class="text-indigo-900 font-bold uppercase text-sm border-b border-gray-200 pb-2 mb-4 flex items-center">
                                <span class="bg-indigo-600 text-white w-6 h-6 rounded flex items-center justify-center text-xs mr-2">4</span> 
                                Previous School Information
                            </h3>
                            <div class="bg-white border border-gray-200 rounded p-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div><span class="block text-[10px] font-bold text-gray-400">SCHOOL NAME</span><div class="font-bold">{{ $application->previous_school }}</div></div>
                                <div><span class="block text-[10px] font-bold text-gray-400">SCHOOL ID</span><div class="font-bold">{{ $application->school_id }}</div></div>
                                <div><span class="block text-[10px] font-bold text-gray-400">SCHOOL ADDRESS</span><div class="font-bold">{{ $application->school_address }}</div></div>
                                <div><span class="block text-[10px] font-bold text-gray-400">SCHOOL TYPE</span><div class="font-bold">{{ $application->school_type }}</div></div>
                                <div><span class="block text-[10px] font-bold text-gray-400">LAST GRADE COMPLETED</span><div class="font-bold">{{ $application->last_grade_level }}</div></div>
                                <div><span class="block text-[10px] font-bold text-gray-400">LAST SCHOOL YEAR</span><div class="font-bold">{{ $application->last_school_year }}</div></div>
                            </div>
                        </div>

                        {{-- 5. ENROLLMENT REQUIREMENTS CHECKLIST --}}
                        <div class="mb-6">
                            <h3 class="text-indigo-900 font-bold uppercase text-sm border-b border-gray-200 pb-2 mb-4 flex items-center">
                                <span class="bg-indigo-600 text-white w-6 h-6 rounded flex items-center justify-center text-xs mr-2">5</span> 
                                Submitted Enrollment Documents
                            </h3>

                            <form action="{{ route('official-enrollment.return', $application->id) }}" method="POST">
                                @csrf @method('PATCH')

                                <div class="grid grid-cols-1 gap-3">
                                    @php
                                        // Include ALL enrollment requirements here
                                        $docs = [
                                            'sf10' => 'Original Form 137 / SF10',
                                            'good_moral' => 'Good Moral Certificate',
                                            'psa_birth_cert' => 'PSA Birth Certificate (Original)',
                                            'medical_cert' => 'Medical Certificate',
                                            'id_picture' => '2x2 ID Picture',
                                            'student_info_form' => 'Student-Athlete Info Form',
                                            'scholarship_form' => 'Scholarship Application Form',
                                            'student_profile' => 'Student-Athlete Profile Form',
                                            'medical_clearance' => 'Medical Clearance',
                                            'guardian_id' => 'Guardian Valid ID'
                                        ];
                                        // Safe Decode
                                        $files = is_string($application->uploaded_files) ? json_decode($application->uploaded_files, true) : ($application->uploaded_files ?? []);
                                        $remarks = is_string($application->document_remarks) ? json_decode($application->document_remarks, true) : ($application->document_remarks ?? []);
                                    @endphp

                                    @foreach($docs as $key => $label)
                                        <div class="flex items-center justify-between p-3 border rounded-lg bg-gray-50 hover:bg-white transition hover:shadow-sm">
                                            {{-- Name --}}
                                            <div class="w-1/3">
                                                <span class="text-xs font-bold uppercase text-slate-700 block">{{ $label }}</span>
                                                @if(isset($files[$key]))
                                                    <span class="text-[10px] font-bold text-green-600 flex items-center"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Uploaded</span>
                                                @else
                                                    <span class="text-[10px] font-bold text-red-500 flex items-center"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg> Missing</span>
                                                @endif
                                            </div>

                                            {{-- View Button --}}
                                            <div class="w-1/4 text-center">
                                                @if(isset($files[$key]))
                                                    <a href="{{ route('applicant.view_file', ['id' => $application->id, 'type' => $key]) }}" target="_blank" class="bg-indigo-100 text-indigo-700 hover:bg-indigo-200 px-3 py-1 rounded text-xs font-bold transition">
                                                        VIEW FILE
                                                    </a>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">-</span>
                                                @endif
                                            </div>

                                            {{-- Remark Input --}}
                                            <div class="w-1/3">
                                                <input type="text" name="remarks[{{ $key }}]" value="{{ $remarks[$key] ?? '' }}" class="w-full text-xs border-gray-300 rounded px-2 py-1 focus:ring-red-500 focus:border-red-500" placeholder="Type correction request here...">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-4 text-right">
                                    <button type="submit" class="bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 font-bold py-2 px-4 rounded text-xs uppercase transition">
                                        Return to Student for Corrections
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

                {{-- RIGHT COLUMN: ACTION PANEL --}}
                <div class="lg:col-span-1 no-print">
                    <div class="bg-white shadow-xl rounded-xl border border-indigo-100 sticky top-6 overflow-hidden">
                        
                        <div class="bg-indigo-600 px-6 py-4">
                            <h3 class="text-lg font-bold text-white flex items-center">
                                Finalize Enrollment
                            </h3>
                        </div>

                        <div class="p-6">
                            <p class="text-xs text-slate-600 mb-6 bg-indigo-50 p-3 rounded border border-indigo-100">
                                Verify that all information is correct and documents are valid.
                            </p>

                            <form action="{{ route('official-enrollment.store', $application->id) }}" method="POST">
                                @csrf
                                
                                <div class="mb-5">
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Generated Student ID</label>
                                    <div class="flex items-center bg-slate-100 p-3 rounded border border-slate-300">
                                        <span class="font-mono font-bold text-slate-800 text-lg">
                                            {{ date('Y') }}-{{ str_pad($application->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Assign Section <span class="text-red-500">*</span></label>
                                    <select name="section_id" required class="block w-full text-sm border-slate-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                                        <option value="" disabled selected>-- Choose Section --</option>
                                        @forelse($sections as $section)
                                            <option value="{{ $section->id }}">
                                                {{ $section->section_name }} ({{ $section->adviser_name ?? 'No Adviser' }})
                                            </option>
                                        @empty
                                            <option value="" disabled>No sections available</option>
                                        @endforelse
                                    </select>
                                </div>

                                <button type="submit" onclick="return confirm('WARNING: Are you sure? This will officially enroll the student.')" 
                                    class="w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-md transition transform hover:-translate-y-0.5 flex justify-center items-center group">
                                    <span>CONFIRM & ENROLL</span>
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>