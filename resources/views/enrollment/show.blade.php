<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight no-print">
            {{ __('Application Assessment') }}
        </h2>
    </x-slot>

    <style>
        @media print {
            body * { visibility: hidden; }
            #printable-area, #printable-area * { visibility: visible; }
            #printable-area { position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 0; border: none; box-shadow: none; }
            .no-print, nav, aside, header, footer { display: none !important; }
            .page-break { page-break-before: always; }
            .print-bg-black { background-color: black !important; color: white !important; -webkit-print-color-adjust: exact; }
        }
        @keyframes fadeOut {
            0% { opacity: 1; }
            80% { opacity: 1; }
            100% { opacity: 0; display: none; }
        }
        .alert-fade { animation: fadeOut 5s forwards; }
    </style>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- DATABASE INSPECTOR (X-RAY) - Start --}}
            {{-- Ito ay temporary tool para makita natin ang tamang column name --}}
            <div class="bg-red-100 border-4 border-red-500 text-red-900 p-4 mb-6 rounded shadow-lg relative no-print">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-full">
                        <h3 class="text-lg leading-6 font-bold text-red-900">⚠️ DATABASE INSPECTOR</h3>
                        <div class="mt-2 text-sm text-red-800">
                            <p class="mb-2">Hanapin sa listahan sa ibaba kung saan nakasulat ang "Others" details ng student na ito:</p>
                            <div class="h-64 overflow-y-auto bg-white p-2 border border-red-300 font-mono text-xs text-black w-full">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-gray-200 sticky top-0">
                                            <th class="p-2 border">Column Name</th>
                                            <th class="p-2 border">Value (Laman)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($application->getAttributes() as $key => $value)
                                        <tr class="border-b hover:bg-yellow-50">
                                            <td class="p-2 border font-bold text-blue-700 select-all">{{ $key }}</td>
                                            <td class="p-2 border break-all select-all">{{ is_array($value) ? json_encode($value) : $value }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- DATABASE INSPECTOR - End --}}

            <div class="bg-white p-4 rounded-lg shadow-md mb-6 flex flex-col md:flex-row justify-between items-center gap-4 no-print border-l-4 border-blue-600">
                <div class="flex items-center">
                    <a href="{{ route('admission.index') }}" class="text-gray-600 hover:text-gray-900 font-bold flex items-center transition">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to List
                    </a>
                </div>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <button onclick="window.print()" class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded shadow flex justify-center items-center transition transform hover:scale-105">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        PRINT APPLICATION FORM
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div id="printable-area" class="lg:col-span-2 bg-white shadow-lg sm:rounded-lg overflow-hidden border border-gray-200 p-8 text-black">
                    
                    <div class="text-center border-b-2 border-black pb-4 mb-6">
                        <img src="{{ asset('images/nas/nas-logo-spotlight.jpg') }}" class="h-20 mx-auto mb-2" alt="NAS Logo">
                        <h1 class="text-2xl font-bold uppercase tracking-wide">National Academy of Sports</h1>
                        <p class="text-sm font-bold uppercase">Student-Athlete Admission Application Form</p>
                        <p class="text-xs italic mt-1">New Clark City, Capas, Tarlac</p>
                    </div>

                    <div class="mb-6">
                        <h3 class="bg-gray-200 text-gray-800 font-bold px-2 py-1 mb-3 uppercase text-sm border border-gray-400 font-bold uppercase">1. Applicant Information</h3>
                        
                        <div class="flex gap-6 mb-4">
                            <div class="w-32 h-32 border border-gray-300 flex-shrink-0 bg-gray-100 flex items-center justify-center overflow-hidden">
                                @if(isset($application->uploaded_files['id_picture']))
                                    <img src="{{ asset('storage/' . $application->uploaded_files['id_picture']) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-xs text-gray-400 italic">No Photo</span>
                                @endif
                            </div>

                            <div class="flex-1 flex flex-col justify-between text-sm">
                                <div class="flex gap-4 w-full">
                                    <div class="flex-grow">
                                        <span class="block text-xs text-gray-500 uppercase font-bold">Full Name</span>
                                        <div class="font-bold text-lg uppercase border-b border-gray-400 w-full h-8 flex items-end">
                                            {{ $application->last_name }}, {{ $application->first_name }} {{ $application->middle_name }}
                                        </div>
                                    </div>
                                    <div class="w-1/3">
                                        <span class="block text-xs text-gray-500 uppercase font-bold">LRN</span>
                                        <div class="font-mono font-bold text-lg border-b border-gray-400 w-full h-8 flex items-end">
                                            {{ $application->lrn }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex gap-4 w-full mt-2">
                                    <div class="flex-grow">
                                        <span class="block text-xs text-gray-500 uppercase font-bold">Email Address</span>
                                        <div class="border-b border-gray-400 w-full h-6 flex items-end">
                                            {{ $application->email_address }}
                                        </div>
                                    </div>
                                    <div class="w-1/3">
                                        <span class="block text-xs text-gray-500 uppercase font-bold">Date of Birth</span>
                                        <div class="border-b border-gray-400 w-full h-6 flex items-end">
                                            {{ \Carbon\Carbon::parse($application->date_of_birth)->format('F d, Y') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 mt-2 w-full">
                                    <div>
                                        <span class="block text-xs text-gray-500 uppercase font-bold">Sex / Age</span>
                                        <div class="border-b border-gray-400 w-full h-6 flex items-end">
                                            {{ $application->gender }} / {{ \Carbon\Carbon::parse($application->date_of_birth)->age }}
                                        </div>
                                    </div>
                                    <div>
                                        <span class="block text-xs text-gray-500 uppercase font-bold">Place of Birth</span>
                                        <div class="border-b border-gray-400 w-full h-6 flex items-end overflow-hidden whitespace-nowrap text-ellipsis italic">
                                            {{ $application->birthplace }}
                                        </div>
                                    </div>
                                    <div>
                                        <span class="block text-xs text-gray-500 uppercase font-bold">Religion</span>
                                        <div class="border-b border-gray-400 w-full h-6 flex items-end">
                                            {{ $application->religion ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-4 gap-4 mb-4 text-[11px]">
                            <div>
                                <span class="block text-gray-500 uppercase font-bold">Region</span>
                                <div class="border-b border-gray-400 h-6 flex items-end font-bold uppercase">{{ $application->region }}</div>
                            </div>
                            <div>
                                <span class="block text-gray-500 uppercase font-bold">Province</span>
                                <div class="border-b border-gray-400 h-6 flex items-end font-bold uppercase">{{ $application->province }}</div>
                            </div>
                            <div>
                                <span class="block text-gray-500 uppercase font-bold">City/Municipality</span>
                                <div class="border-b border-gray-400 h-6 flex items-end font-bold uppercase">{{ $application->municipality_city }}</div>
                            </div>
                            <div>
                                <span class="block text-gray-500 uppercase font-bold">Barangay</span>
                                <div class="border-b border-gray-400 h-6 flex items-end font-bold uppercase">{{ $application->barangay }}</div>
                            </div>
                        </div>

                        <div class="mb-4 text-sm">
                            <span class="block text-xs text-gray-500 uppercase font-bold">Street Address</span>
                            <div class="border-b border-gray-400 h-6 flex items-end font-bold uppercase">
                                {{ $application->street_address }} (Zip Code: {{ $application->zip_code }})
                            </div>
                        </div>

                        @php
                            $is_ip = (bool)($application->is_ip ?? false);
                            $is_pwd = (bool)($application->is_pwd ?? false);
                            $is_4ps = (bool)($application->is_4ps ?? false);
                            
                            // Check multiple possible column names for the text
                            // TINGNAN MO YUNG PULANG BOX PARA SA TAMANG NAME
                            $others_text = $application->others_specify 
                                        ?? $application->other_category_details 
                                        ?? $application->other_category 
                                        ?? $application->others
                                        ?? '';

                            // Auto-check the box if there is text, OR if the boolean is true
                            $is_others = (bool)($application->is_others ?? false) || !empty($others_text);
                        @endphp

                        <div class="grid grid-cols-4 gap-2 text-[10px] mt-2 border p-3 rounded bg-white">
                            <div class="flex items-center">
                                <div class="w-4 h-4 border border-black flex items-center justify-center mr-2 {{ $is_ip ? 'bg-black text-white print-bg-black' : 'bg-white' }}">
                                    @if($is_ip) ✓ @endif
                                </div>
                                <span class="{{ $is_ip ? 'font-bold text-black' : 'text-gray-400' }}">INDIGENOUS PEOPLE (IP)</span>
                            </div>

                            <div class="flex items-center">
                                <div class="w-4 h-4 border border-black flex items-center justify-center mr-2 {{ $is_pwd ? 'bg-black text-white print-bg-black' : 'bg-white' }}">
                                    @if($is_pwd) ✓ @endif
                                </div>
                                <span class="{{ $is_pwd ? 'font-bold text-black' : 'text-gray-400' }}">PWD</span>
                            </div>

                            <div class="flex items-center">
                                <div class="w-4 h-4 border border-black flex items-center justify-center mr-2 {{ $is_4ps ? 'bg-black text-white print-bg-black' : 'bg-white' }}">
                                    @if($is_4ps) ✓ @endif
                                </div>
                                <span class="{{ $is_4ps ? 'font-bold text-black' : 'text-gray-400' }}">4PS BENEFICIARY</span>
                            </div>

                            <div class="flex items-center">
                                <div class="w-4 h-4 border border-black flex items-center justify-center mr-2 {{ $is_others ? 'bg-black text-white print-bg-black' : 'bg-white' }}">
                                    @if($is_others) ✓ @endif
                                </div>
                                <span class="{{ $is_others ? 'font-bold text-black' : 'text-gray-400' }}">OTHERS:</span>
                                <span class="ml-1 border-b border-gray-400 flex-1 min-w-[50px] text-black font-bold uppercase">
                                    {{ $others_text }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="bg-gray-200 text-gray-800 font-bold px-2 py-1 mb-3 uppercase text-sm border border-gray-400">II. Academic & Sports Profile</h3>
                        <div class="grid grid-cols-3 gap-6 text-sm">
                            <div class="col-span-1">
                                <span class="block text-xs text-gray-500 uppercase font-bold">Last School Attended</span>
                                <div class="border-b border-gray-400 font-bold h-6 flex items-end uppercase">{{ $application->previous_school }} ({{ $application->school_type }})</div>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 uppercase font-bold">Applied Grade</span>
                                <div class="border-b border-gray-400 font-bold h-6 flex items-end uppercase">{{ $application->grade_level_applied }}</div>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 uppercase font-bold">Sport</span>
                                <div class="border-b border-gray-400 font-bold h-6 flex items-end uppercase">{{ $application->sport }}</div>
                            </div>
                        </div>
                        <div class="mt-4 text-sm">
                            <span class="block text-xs text-gray-500 uppercase font-bold">Palarong Pambansa Participation</span>
                            <div class="font-bold border-b border-gray-400 inline-block min-w-[150px]">
                                {{ $application->has_palaro_participation ? 'YES (Year: ' . $application->palaro_year . ')' : 'NO' }}
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="bg-gray-200 text-gray-800 font-bold px-2 py-1 mb-3 uppercase text-sm border border-gray-400">III. Guardian Information</h3>
                        <div class="grid grid-cols-2 gap-6 text-sm">
                            <div>
                                <span class="block text-xs text-gray-500 uppercase font-bold">Guardian Name</span>
                                <div class="border-b border-gray-400 font-bold h-6 flex items-end uppercase">{{ $application->guardian_name }}</div>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 uppercase font-bold">Relationship</span>
                                <div class="border-b border-gray-400 h-6 flex items-end uppercase">{{ $application->guardian_relationship }}</div>
                            </div>
                        </div>
                    </div>

                    @if($application->assessment_score || $application->status != 'Submitted (with Pending)')
                    <div class="border-t-2 border-black pt-4 mt-12">
                        <div class="grid grid-cols-2 gap-6 text-sm">
                            <div>
                                <span class="block text-xs text-gray-500 uppercase font-bold">Assessment Score</span>
                                <div class="font-bold text-xl border-b border-black inline-block min-w-[100px]">
                                    {{ $application->assessment_score ?? 'N/A' }}
                                </div>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 uppercase font-bold">Final Status</span>
                                <div class="font-bold text-xl uppercase border-b border-black inline-block min-w-[150px]">
                                    {{ $application->status }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-16 flex justify-end">
                            <div class="text-center">
                                <div class="border-b border-black w-48 mb-1"></div>
                                <span class="text-xs uppercase font-bold italic">Admission Officer Signature</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div> 

                <div class="no-print lg:col-span-1">
                    <div class="bg-white shadow-lg sm:rounded-lg border-t-4 border-indigo-600 p-6 sticky top-6">
                        @if(session('success'))
                            <div class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded alert-fade">
                                <p class="font-bold text-sm">Success</p>
                                <p class="text-xs">{{ session('success') }}</p>
                            </div>
                        @endif

                        <h3 class="text-lg font-bold text-gray-800 mb-4">Update Status</h3>
                        <form method="POST" action="{{ route('admission.process', $application->id) }}">
                            @csrf
                            @method('PATCH')
                            
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status</label>
                                <select name="status" class="w-full rounded-md border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500" onchange="toggleFields(this.value)">
                                    <option value="Submitted (with Pending)" {{ $application->status == 'Submitted (with Pending)' ? 'selected' : '' }}>Pending Review</option>
                                    <option value="For Assessment" {{ $application->status == 'For Assessment' ? 'selected' : '' }}>For Assessment</option>
                                    <option value="Qualified" {{ $application->status == 'Qualified' ? 'selected' : '' }}>Qualified</option>
                                    <option value="Waitlisted" {{ $application->status == 'Waitlisted' ? 'selected' : '' }}>Waitlisted</option>
                                    <option value="Not Qualified" {{ $application->status == 'Not Qualified' ? 'selected' : '' }}>Not Qualified</option>
                                </select>
                            </div>

                            <div id="score-field" class="mb-4 hidden">
                                <label class="block text-xs font-bold text-blue-600 uppercase mb-1">Assessment Score (0-100)</label>
                                <input type="number" step="0.01" name="assessment_score" value="{{ $application->assessment_score }}" class="w-full rounded-md border-blue-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm font-bold">
                            </div>

                            <button type="submit" class="w-full bg-indigo-700 hover:bg-indigo-800 text-white font-bold py-3 px-4 rounded shadow transition transform hover:-translate-y-0.5">
                                SAVE UPDATES
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function toggleFields(status) {
            const scoreField = document.getElementById('score-field');
            if (status === 'For Assessment' || status === 'Qualified') {
                scoreField.classList.remove('hidden');
            } else {
                scoreField.classList.add('hidden');
            }
        }
        // Initial check on page load
        toggleFields("{{ $application->status }}");
    </script>
</x-app-layout>