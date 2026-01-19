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

                <div id="print-area" class="md:col-span-3 bg-white shadow-xl rounded-lg overflow-hidden border border-gray-300 p-8 md:p-10 relative">
                        
                    <div class="flex flex-col items-center justify-center text-center mb-6 pb-4 border-b-2 border-black">
                        <img src="{{ asset('images/nas/nas-logo-sidebar.png') }}" alt="NAS Logo" class="h-20 w-auto mb-2 object-contain">
                        <h1 class="text-2xl font-black text-gray-900 uppercase tracking-widest">National Academy of Sports</h1>
                        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Student-Athlete Admission Application Form</h2>
                        <p class="text-xs italic text-gray-600">New Clark City, Capas, Tarlac</p>
                    </div>

                    <div class="mb-6">
                        <div class="bg-gray-200 border border-gray-400 px-3 py-1 mb-4">
                            <h3 class="text-sm font-bold text-gray-800 uppercase">I. Applicant Information</h3>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-40 h-40 bg-gray-100 border border-gray-400 flex-shrink-0 flex items-center justify-center overflow-hidden">
                                {{-- 👇 FIXED IMAGE: Removed asset('storage/...') --}}
                                @if(isset($application->uploaded_files['id_picture']))
                                    <img src="{{ $application->uploaded_files['id_picture'] }}" class="w-full h-full object-cover">
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

                        <div class="mt-4 pt-2">
                            <div class="grid grid-cols-4 gap-4 mb-2">
                                <div class="col-span-1"><label class="block text-[10px] text-gray-500 uppercase font-bold">Region</label><div class="text-xs font-bold">{{ $application->region }}</div></div>
                                <div class="col-span-1"><label class="block text-[10px] text-gray-500 uppercase font-bold">Province</label><div class="text-xs font-bold">{{ $application->province }}</div></div>
                                <div class="col-span-1"><label class="block text-[10px] text-gray-500 uppercase font-bold">City/Municipality</label><div class="text-xs font-bold">{{ $application->municipality_city }}</div></div>
                                <div class="col-span-1"><label class="block text-[10px] text-gray-500 uppercase font-bold">Barangay</label><div class="text-xs font-bold">{{ $application->barangay }}</div></div>
                            </div>
                            <label class="block text-[10px] text-gray-500 uppercase font-bold">Street Address</label>
                            <div class="text-sm font-bold text-gray-900 border-b border-gray-300 mb-2">{{ $application->street_address }} (Zip: {{ $application->zip_code }})</div>
                            
                            @php
                                // Logic para sa Others (Fail-safe check)
                                $others_text = $application->others_specify 
                                            ?? $application->other_category_details 
                                            ?? $application->other_category 
                                            ?? $application->others
                                            ?? '';
                                $is_others = (bool)($application->is_others ?? false) || !empty($others_text);
                            @endphp

                            <div class="flex flex-wrap gap-4 mt-3">
                                <div class="flex items-center">
                                    <div class="check-box">@if($application->is_ip) <span class="text-black font-bold text-xs">✓</span> @endif</div>
                                    <span class="text-[10px] font-bold uppercase text-gray-700">Indigenous People (IP)</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="check-box">@if($application->is_pwd) <span class="text-black font-bold text-xs">✓</span> @endif</div>
                                    <span class="text-[10px] font-bold uppercase text-gray-700">PWD</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="check-box">@if($application->is_4ps) <span class="text-black font-bold text-xs">✓</span> @endif</div>
                                    <span class="text-[10px] font-bold uppercase text-gray-700">4Ps Beneficiary</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="check-box">@if($is_others) <span class="text-black font-bold text-xs">✓</span> @endif</div>
                                    <span class="text-[10px] font-bold uppercase text-gray-700 mr-1">Others:</span>
                                    <span class="text-[10px] font-bold uppercase text-black border-b border-gray-400 px-1 min-w-[50px]">{{ $others_text }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <div class="bg-gray-200 border border-gray-400 px-3 py-1 mb-4">
                            <h3 class="text-sm font-bold text-gray-800 uppercase">II. Academic & Sports Profile</h3>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm">
                            <div class="col-span-2"><label class="block text-[10px] text-gray-500 uppercase font-bold">Last School Attended</label><div class="font-bold text-gray-900 border-b border-gray-300">{{ $application->previous_school }} ({{ $application->school_type }})</div></div>
                            <div><label class="block text-[10px] text-gray-500 uppercase font-bold">Applied Grade</label><div class="font-bold text-gray-900 border-b border-gray-300">{{ $application->grade_level_applied }}</div></div>
                            <div><label class="block text-[10px] text-gray-500 uppercase font-bold">Sport</label><div class="font-bold text-gray-900 border-b border-gray-300">{{ $application->sport }}</div></div>
                            <div class="col-span-4"><label class="block text-[10px] text-gray-500 uppercase font-bold">Palarong Pambansa Participation</label><div class="font-bold text-gray-900 border-b border-gray-300">{{ $application->has_palaro_participation ? 'YES (Year: ' . $application->palaro_year . ')' : 'NO' }}</div></div>
                        </div>
                    </div>

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

                    <div>
                        <div class="bg-gray-200 border border-gray-400 px-3 py-1 mb-4">
                            <h3 class="text-sm font-bold text-gray-800 uppercase">IV. Submitted Documents Checklist</h3>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-2 gap-y-3 gap-x-2 text-xs">
                            @php
                                $files = $application->uploaded_files ?? [];
                                $docs = [
                                    'scholarship_form' => 'Scholarship Application Form',
                                    'student_profile' => 'Student-Athlete Profile Form',
                                    'coach_reco' => 'Coach Recommendation Form',
                                    'adviser_reco' => 'Adviser Recommendation Form',
                                    'medical_clearance' => 'Medical/Physical Clearance',
                                    'guardian_id' => 'Guardian Valid ID',
                                    'psa_birth_cert' => 'PSA Birth Certificate',
                                    'good_moral' => 'Good Moral Certificate',
                                    'sf10' => 'Form 137 (SF10)',
                                    'report_card' => 'Report Card (SF9)',
                                    'id_picture' => '2x2 ID Picture'
                                ];
                            @endphp

                            @foreach($docs as $key => $label)
                                <div class="flex items-center">
                                    <div class="check-box">
                                        @if(isset($files[$key])) <span class="text-black font-bold text-xs">✓</span> @endif
                                    </div>
                                    <span class="{{ isset($files[$key]) ? 'text-gray-900 font-bold' : 'text-gray-400' }}">
                                        {{ $label }}
                                        @if(isset($files[$key]))
                                            {{-- 👇 FIXED LINK: Removed asset('storage/...') --}}
                                            <a href="{{ $files[$key] }}" target="_blank" class="no-print ml-1 text-blue-600 hover:underline">(View)</a>
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                <div class="md:col-span-1 no-print">
                    <div class="bg-white shadow-xl rounded-lg border-t-4 border-indigo-600 sticky top-6">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Update Status</h3>
                            
                            <form method="POST" action="{{ route('admission.process', $application->id) }}">
                                @csrf
                                @method('PATCH')

                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status</label>
                                    <select name="status" id="status" class="w-full border-gray-300 rounded text-sm font-bold text-gray-800">
                                        <option value="Pending Review" {{ $application->status == 'Pending Review' ? 'selected' : '' }}>Pending Review</option>
                                        <option value="For Assessment" {{ $application->status == 'For Assessment' ? 'selected' : '' }}>For Assessment</option>
                                        <option value="Qualified" {{ $application->status == 'Qualified' ? 'selected' : '' }}>Qualified</option>
                                        <option value="Waitlisted" {{ $application->status == 'Waitlisted' ? 'selected' : '' }}>Waitlisted</option>
                                        <option value="Not Qualified" {{ $application->status == 'Not Qualified' ? 'selected' : '' }}>Not Qualified</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Remarks</label>
                                    <textarea name="assessment_score" rows="3" class="w-full border-gray-300 rounded text-sm">{{ $application->assessment_score }}</textarea>
                                </div>

                                <div class="mb-4 hidden" id="rejection-div">
                                    <label class="block text-xs font-bold text-red-600 uppercase mb-1">Reason for Rejection</label>
                                    <textarea name="rejection_reason" rows="2" class="w-full border-red-300 bg-red-50 rounded text-sm text-red-700">{{ $application->rejection_reason }}</textarea>
                                </div>

                                <button type="submit" class="w-full bg-indigo-700 hover:bg-indigo-800 text-white font-bold py-3 rounded shadow">
                                    SAVE UPDATES
                                </button>
                            </form>
                        </div>

                        @if($application->status == 'Qualified')
                            <div class="bg-green-100 p-4 text-center border-t border-green-200">
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
            if(statusSelect.value === 'Not Qualified') {
                rejectionDiv.classList.remove('hidden');
            } else {
                rejectionDiv.classList.add('hidden');
            }
        }
        statusSelect.addEventListener('change', toggleReject);
        toggleReject(); 
    </script>
</x-app-layout>