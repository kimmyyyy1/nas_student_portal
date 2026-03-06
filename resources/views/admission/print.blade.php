<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form - {{ $application->lrn }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        
        body { 
            background: #e5e7eb; 
            font-family: 'Inter', sans-serif; 
            -webkit-print-color-adjust: exact; 
            print-color-adjust: exact; 
        }
        
        /* A4 Settings */
        .paper {
            width: 210mm;  /* A4 Width */
            min-height: 296mm; /* A4 Height */
            background: white;
            margin: 20px auto;
            padding: 10mm 15mm; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            /* 👇 IMPORTANT: Flex Column para gumana ang mt-auto sa footer */
            display: flex;
            flex-direction: column;
        }

        /* Compact Header styling */
        .section-title {
            background-color: #e5e7eb; 
            color: #1f2937; 
            padding: 4px 8px; 
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.75rem; 
            border: 1px solid #d1d5db;
            margin-bottom: 0.5rem;
            margin-top: 0.75rem;
        }

        .label {
            font-size: 0.6rem; 
            color: #6b7280; 
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 0;
            display: block;
            line-height: 1;
        }

        .value {
            font-size: 0.75rem; 
            color: #111827; 
            font-weight: 600;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 1px;
            display: block;
            min-height: 1rem;
            line-height: 1.2;
        }

        /* Checkbox styling */
        .cb-box {
            width: 12px; height: 12px; border: 1px solid black; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; margin-right: 4px;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: white; margin: 0; }
            /* A4 Size Override */
            @page { 
                size: A4 portrait; 
                margin: 0; /* Zero margin sa page, handle sa .paper padding */
            }
            .paper { 
                box-shadow: none; 
                margin: 0; 
                width: 100%; 
                height: 297mm; /* Force full height */
                padding: 10mm 15mm; 
            }
        }
    </style>
</head>
<body>

    {{-- Controls --}}
    <div class="no-print fixed top-0 left-0 w-full bg-indigo-900 text-white p-3 shadow-md flex justify-between items-center z-50">
        <div class="font-bold text-sm flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print Preview (A4)
        </div>
        <div class="flex gap-2">
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded text-xs font-bold uppercase shadow transition">Print / Save PDF</button>
            <button onclick="window.close()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-1 rounded text-xs font-bold uppercase shadow transition">Close</button>
        </div>
    </div>

    {{-- DOCUMENT --}}
    <div class="paper mt-16 print:mt-0">
        
        {{-- REFERENCE NUMBER & DATE --}}
        <div class="text-right mb-2">
            <p class="text-[10px] text-gray-500">Ref No: <span class="font-mono font-bold text-black text-sm">{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</span></p>
            <p class="text-[10px] text-gray-500">Date: {{ $application->created_at->format('M d, Y') }}</p>
        </div>

        {{-- HEADER --}}
        <div class="flex items-center justify-between gap-4 mb-4 pb-4 border-b-2 border-black">
            <div class="w-24 flex-shrink-0 flex justify-center">
                <img src="{{ asset('images/nas/nas-logo-spotlight.jpg') }}" alt="NAS Logo" class="h-24 w-auto object-contain">
            </div>
            <div class="flex-1 text-center px-4">
                <h1 class="text-base font-black text-gray-900 uppercase leading-tight">NAS Annual Search for Competent, Exceptional, Notable, and Talented Student-Athlete Scholars</h1>
                <h2 class="text-sm font-bold text-gray-700 uppercase mt-1">(NASCENT SAS)</h2>
                <p class="text-xs italic text-gray-600 mt-1">New Clark City, Capas, Tarlac</p>
            </div>
            <div class="w-24 flex-shrink-0 flex justify-center">
                <img src="{{ asset('images/nas/NASCENT SAS ICON.png') }}" alt="NASCENT SAS ICON" class="h-24 w-auto object-contain">
            </div>
        </div>

        {{-- I. APPLICANT INFORMATION --}}
        <div class="section-title mt-0">I. Personal Information</div>
        
        <div class="flex gap-4 mb-2">
            {{-- RIGHT: 2x2 Photo --}}
            <div class="w-24 h-24 flex-shrink-0 border border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden">
                @if(isset($application->uploaded_files['id_picture']))
                    <img src="{{ fileUrl($application->uploaded_files['id_picture']) }}" class="w-full h-full object-cover">
                @else
                    <span class="text-[10px] text-gray-400 font-bold uppercase text-center p-1">2x2 Photo</span>
                @endif
            </div>

            {{-- LEFT: Text Details --}}
            <div class="flex-1 w-full space-y-2">
                <div>
                    <span class="label">Learner Reference Number (LRN)</span>
                    <span class="value font-mono">{{ $application->lrn }}</span>
                </div>

                <div class="col-span-3">
                    <span class="label">Full Name</span>
                    @php
                        $middleName = $application->middle_name;
                        $middleNameDisplay = (empty($middleName) || strtolower($middleName) === 'n/a') ? '-' : $middleName;
                    @endphp
                    <span class="value uppercase flex items-baseline gap-x-2">
                        <span>{{ $application->last_name }},</span>
                        <span>{{ $application->first_name }}</span>
                        <span class="font-medium">{{ $middleNameDisplay }}</span>
                    </span>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <span class="label">Date of Birth</span>
                        <span class="value">{{ \Carbon\Carbon::parse($application->date_of_birth)->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="label">Age</span>
                        <span class="value">{{ $application->age }}</span>
                    </div>
                    <div>
                        <span class="label">Sex</span>
                        <span class="value">{{ $application->gender }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="label">Place of Birth</span>
                        <span class="value truncate">{{ $application->birthplace }}</span>
                    </div>
                    <div>
                        <span class="label">Religion</span>
                        <span class="value truncate">{{ $application->religion }}</span>
                    </div>
                </div>
                <div>
                    <span class="label">Email Address</span>
                    <span class="value lowercase">{{ $application->email_address }}</span>
                </div>
            </div>
        </div>

        <div class="mt-2 pt-2">
            <label class="block text-[10px] text-gray-500 uppercase font-bold mb-1">Permanent Address</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-2">
                <div class="md:col-span-4">
                    <span class="label">Street Address / House No.</span>
                    <span class="value">{{ $application->street_address }}</span>
                </div>
                <div>
                    <span class="label">Barangay</span>
                    <span class="value">{{ $application->barangay }}</span>
                </div>
                <div>
                    <span class="label">Municipality / City</span>
                    <span class="value">{{ $application->municipality_city }}</span>
                </div>
                <div>
                    <span class="label">Province</span>
                    <span class="value">{{ $application->province }}</span>
                </div>
                <div>
                    <span class="label">Region</span>
                    <span class="value">{{ $application->region }}</span>
                </div>
                <div>
                    <span class="label">Zip Code</span>
                    <span class="value">{{ $application->zip_code }}</span>
                </div>
            </div>
        </div>

        <div class="mt-2 pt-2 border-t border-gray-200">
            <label class="block text-[10px] text-gray-500 uppercase font-bold mb-1">Background & Special Categories</label>
            <div class="grid grid-cols-3 gap-4">
                <div class="flex items-center">
                    <div class="cb-box">@if($application->is_ip) <span class="text-black font-bold text-xs">✓</span> @endif</div>
                    <span class="text-[10px] font-bold uppercase text-gray-700">IP Member @if($application->is_ip) ({{ $application->ip_group_name }}) @endif</span>
                </div>
                <div class="flex items-center">
                    <div class="cb-box">@if($application->is_pwd) <span class="text-black font-bold text-xs">✓</span> @endif</div>
                    <span class="text-[10px] font-bold uppercase text-gray-700">PWD @if($application->is_pwd) ({{ $application->pwd_disability }}) @endif</span>
                </div>
                <div class="flex items-center">
                    <div class="cb-box">@if($application->is_4ps) <span class="text-black font-bold text-xs">✓</span> @endif</div>
                    <span class="text-[10px] font-bold uppercase text-gray-700">4Ps Beneficiary</span>
                </div>
            </div>
        </div>

        {{-- II. ACADEMIC & SPORTS PROFILE --}}
        <div class="section-title">II. Academic & Sports Profile</div>
        <div class="grid grid-cols-4 gap-4 text-sm mb-2">
            <div class="col-span-2">
                <span class="label">Last School Attended</span>
                <span class="value">{{ $application->previous_school }} ({{ $application->school_type }})</span>
            </div>
            <div>
                <span class="label">Applied Grade</span>
                <span class="value">{{ $application->grade_level_applied }}</span>
            </div>
            <div>
                <span class="label">Sport</span>
                <span class="value">{{ $application->sport }} @if($application->sport_specification) ({{ $application->sport_specification }}) @endif</span>
            </div>
            <div class="col-span-2">
                <span class="label">Palarong Pambansa Finisher</span>
                <span class="value">{{ $application->has_palaro_participation ? 'YES' : 'NO' }}</span>
            </div>
            <div class="col-span-2">
                <span class="label">Batang Pinoy Finisher</span>
                <span class="value">{{ $application->batang_pinoy_finisher == 'Yes' ? 'YES' : 'NO' }}</span>
            </div>
        </div>

        {{-- III. PARENTS & GUARDIAN --}}
        <div class="section-title">III. PARENTS' & DESIGNATED GUARDIAN'S INFORMATION</div>
        <div class="grid grid-cols-4 gap-3 mb-2">
            <div class="col-span-2"><span class="label">Designated Guardian</span><span class="value">{{ $application->guardian_name }}</span></div>
            <div class="col-span-2"><span class="label">Relationship to the Applicant</span><span class="value">{{ $application->guardian_relationship }}</span></div>
            <div class="col-span-2"><span class="label">Contact Number</span><span class="value">{{ $application->guardian_contact }}</span></div>
            <div class="col-span-2"><span class="label">Email Address</span><span class="value lowercase">{{ $application->guardian_email }}</span></div>
        </div>

        {{-- IV. CHECKLIST --}}
        <div class="section-title">IV. Submitted Documents Checklist</div>
        <div class="grid grid-cols-2 gap-x-6 gap-y-1 text-[10px] mb-6">
            @php
                $files = $application->uploaded_files ?? [];
                $docs = [
                    'id_picture' => '2x2 ID Picture', 
                    'scholarship_form' => 'Scholarship Application Form',
                    'student_profile' => 'Student-Athlete’s Profile Form',
                    'medical_clearance' => 'Preparticipation Physical Evaluation Clearance Form',
                    'coach_reco' => 'Coach’s Recommendation Form',
                    'adviser_reco' => 'Adviser’s Recommendation Form',
                    'birth_cert' => 'PSA Birth Certificate',
                    'report_card' => 'Report Card (SF9)',
                    'guardian_id' => 'Designated Guardian’s Valid ID',
                    'kukkiwon_cert' => 'Kukkiwon Certificate',
                    'ip_cert' => 'IP Certification',
                    'pwd_id' => 'PWD ID',
                    '4ps_id' => '4Ps ID/Certification'
                ];
            @endphp

            @foreach($docs as $key => $label)
                @php
                    $isUploaded = isset($files[$key]) && !empty($files[$key]);
                    $isSpecial = in_array($key, ['kukkiwon_cert', 'ip_cert', 'pwd_id', '4ps_id']);
                    if ($isSpecial && !$isUploaded) { continue; }
                @endphp
                <div class="flex justify-between items-center border-b border-dashed border-gray-300 py-0.5">
                    <span class="uppercase text-gray-700 font-medium">{{ $label }}</span>
                    @if($isUploaded)
                        <span class="font-bold text-black text-[9px] bg-gray-200 px-1 rounded">SUBMITTED</span>
                    @else
                        <span class="text-gray-300 text-[9px]">---</span>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- SIGNATURES --}}
        <div class="flex justify-between gap-10 mt-8 mb-4"> {{-- Added mb-4 para safe --}}
            <div class="w-1/2 text-center">
                <div class="border-b border-black mb-1"></div>
                <p class="font-bold uppercase text-xs">{{ $application->first_name }} {{ $application->last_name }}</p>
                <p class="text-[9px] text-gray-500 uppercase font-bold tracking-wide">Applicant Signature</p>
            </div>
            <div class="w-1/2 text-center">
                <div class="border-b border-black mb-1"></div>
                <p class="font-bold uppercase text-xs">{{ $application->guardian_name }}</p>
                <p class="text-[9px] text-gray-500 uppercase font-bold tracking-wide">Guardian Signature</p>
            </div>
        </div>

        {{-- FOOTER (Ngayon ay nasa ilalim na talaga dahil sa mt-auto at flex container) --}}
        <div class="mt-auto pt-2 text-center text-[9px] text-gray-400 border-t border-gray-200">
            <p>System Generated Form | Date Printed: {{ now()->format('Y-m-d H:i') }} | NASCENT SAS</p>
        </div>

    </div>

</body>
</html>