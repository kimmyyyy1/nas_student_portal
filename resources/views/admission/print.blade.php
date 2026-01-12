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
            padding: 10mm 15mm; /* Mas maliit na padding */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Compact Header styling */
        .section-title {
            background-color: #e5e7eb; 
            color: #1f2937; 
            padding: 4px 8px; /* Compact padding */
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.75rem; /* Mas maliit na font */
            border: 1px solid #d1d5db;
            margin-bottom: 0.5rem;
            margin-top: 0.75rem;
        }

        .label {
            font-size: 0.6rem; /* 10px approx */
            color: #6b7280; 
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 0;
            display: block;
            line-height: 1;
        }

        .value {
            font-size: 0.75rem; /* 12px approx */
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
                margin: 5mm; /* Sobrang liit na margin para di urong */
            }
            .paper { 
                box-shadow: none; 
                margin: 0; 
                width: 100%; 
                padding: 5mm 10mm; /* Adjusted print padding */
                min-height: auto;
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
        
        {{-- HEADER --}}
        <div class="text-center mb-4 relative">
            <img src="{{ asset('images/nas/nas-logo-sidebar.png') }}" class="h-16 mx-auto mb-1" onerror="this.style.display='none'">
            <h1 class="text-xl font-extrabold text-gray-900 uppercase leading-tight">National Academy of Sports</h1>
            <p class="text-xs font-bold text-gray-600 uppercase tracking-wider">Student-Athlete Admission Application Form</p>
            <p class="text-[10px] text-gray-500 italic">New Clark City, Capas, Tarlac</p>
            
            {{-- Reference Info (Right Side Overlay) --}}
            <div class="absolute top-0 right-0 text-right hidden sm:block print:block">
                <p class="text-[10px] text-gray-500">Ref No: <span class="font-mono font-bold text-black text-sm">{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</span></p>
                <p class="text-[10px] text-gray-500">Date: {{ $application->created_at->format('M d, Y') }}</p>
            </div>
            <div class="border-b-2 border-gray-800 mt-2 w-full"></div>
        </div>

        {{-- I. APPLICANT INFORMATION --}}
        <div class="section-title mt-0">I. Applicant Information</div>
        
        <div class="flex gap-4 mb-2">
            {{-- PHOTO (Smaller) --}}
            <div class="w-24 h-24 flex-shrink-0 border border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden">
                @if(isset($application->uploaded_files['id_picture']))
                    <img src="{{ $application->uploaded_files['id_picture'] }}" class="w-full h-full object-cover">
                @else
                    <span class="text-[10px] text-gray-400 font-bold uppercase text-center p-1">2x2 Photo</span>
                @endif
            </div>

            {{-- BASIC DETAILS --}}
            <div class="flex-grow grid grid-cols-4 gap-x-4 gap-y-2">
                <div class="col-span-2">
                    <span class="label">Full Name</span>
                    <span class="value uppercase">{{ $application->last_name }}, {{ $application->first_name }} {{ $application->middle_name }}</span>
                </div>
                <div class="col-span-1">
                    <span class="label">LRN</span>
                    <span class="value font-mono">{{ $application->lrn }}</span>
                </div>
                <div class="col-span-1">
                    <span class="label">Birthdate</span>
                    <span class="value">{{ \Carbon\Carbon::parse($application->date_of_birth)->format('M d, Y') }}</span>
                </div>

                <div class="col-span-1">
                    <span class="label">Gender</span>
                    <span class="value">{{ $application->gender }}</span>
                </div>
                <div class="col-span-1">
                    <span class="label">Age</span>
                    <span class="value">{{ $application->age }}</span>
                </div>
                <div class="col-span-1">
                    <span class="label">Religion</span>
                    <span class="value truncate">{{ $application->religion }}</span>
                </div>
                <div class="col-span-1">
                    <span class="label">Birthplace</span>
                    <span class="value truncate">{{ $application->birthplace }}</span>
                </div>

                <div class="col-span-2">
                    <span class="label">Email Address</span>
                    <span class="value lowercase">{{ $application->email_address }}</span>
                </div>
                <div class="col-span-2">
                    <span class="label">Contact No.</span>
                    <span class="value">{{ $application->contact_number ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        {{-- ADDRESS --}}
        <div class="grid grid-cols-4 gap-3 mb-2">
            <div class="col-span-4">
                <span class="label">Home Address</span>
                <span class="value">{{ $application->street_address }}, {{ $application->barangay }}, {{ $application->municipality_city }}, {{ $application->province }} {{ $application->region }} {{ $application->zip_code }}</span>
            </div>
        </div>

        {{-- II. ACADEMIC & SPORTS --}}
        <div class="section-title">II. Academic & Sports Profile</div>
        <div class="grid grid-cols-4 gap-3 mb-2">
            <div class="col-span-1">
                <span class="label">Applying For Grade</span>
                <span class="value">{{ $application->grade_level_applied }}</span>
            </div>
            <div class="col-span-1">
                <span class="label">Priority Sport</span>
                <span class="value font-bold uppercase text-indigo-900">{{ $application->sport }}</span>
            </div>
            <div class="col-span-2">
                <span class="label">Last School Attended</span>
                <span class="value truncate">{{ $application->previous_school }} ({{ $application->school_type }})</span>
            </div>
            <div class="col-span-4">
                <span class="label">Palarong Pambansa Participation</span>
                <span class="value">{{ $application->has_palaro_participation ? 'YES (Year: ' . $application->palaro_year . ')' : 'NO' }}</span>
            </div>
        </div>

        {{-- SPECIAL CATEGORIES (COMPACT) --}}
        @php
            $categoriesData = [];
            $otherDetails = '';
            if ($application->special_categories) {
                $categoriesData = array_map('trim', explode(',', $application->special_categories));
            }
            $is_others = false;
            foreach($categoriesData as $cat) {
                if (\Illuminate\Support\Str::startsWith(strtolower($cat), 'others')) {
                    $is_others = true;
                    if (str_contains($cat, ':')) {
                        $parts = explode(':', $cat, 2);
                        $otherDetails = trim($parts[1]);
                    } elseif (!empty($application->other_category_details)) {
                        $otherDetails = $application->other_category_details;
                    }
                    break;
                }
            }
        @endphp
        <div class="mb-2 border border-gray-300 p-2 text-xs flex items-center bg-gray-50 rounded">
            <span class="font-bold text-gray-500 mr-3 uppercase text-[10px]">Special Categories:</span>
            <div class="flex gap-4">
                <div class="flex items-center"><span class="cb-box">{{ $application->is_ip ? '✓' : '' }}</span> IP</div>
                <div class="flex items-center"><span class="cb-box">{{ $application->is_pwd ? '✓' : '' }}</span> PWD</div>
                <div class="flex items-center"><span class="cb-box">{{ $application->is_4ps ? '✓' : '' }}</span> 4Ps</div>
                <div class="flex items-center"><span class="cb-box">{{ $is_others ? '✓' : '' }}</span> Others: <span class="border-b border-gray-400 min-w-[50px] ml-1 px-1 font-bold">{{ $otherDetails }}</span></div>
            </div>
        </div>

        {{-- III. GUARDIAN INFO --}}
        <div class="section-title">III. Guardian Information</div>
        <div class="grid grid-cols-4 gap-3 mb-2">
            <div class="col-span-2"><span class="label">Guardian Name</span><span class="value">{{ $application->guardian_name }}</span></div>
            <div class="col-span-2"><span class="label">Relationship</span><span class="value">{{ $application->guardian_relationship }}</span></div>
            <div class="col-span-2"><span class="label">Contact Number</span><span class="value">{{ $application->guardian_contact }}</span></div>
            <div class="col-span-2"><span class="label">Email Address</span><span class="value lowercase">{{ $application->guardian_email }}</span></div>
        </div>

        {{-- IV. CHECKLIST --}}
        <div class="section-title">IV. Requirements Checklist</div>
        <div class="grid grid-cols-2 gap-x-6 gap-y-1 text-[10px] mb-6">
            @php
                $docs = [
                    'scholarship_form' => 'Scholarship Application Form',
                    'student_profile' => 'Student-Athlete Profile Form',
                    'birth_cert' => 'PSA Birth Certificate',
                    'report_card' => 'Report Card (SF9/SF10)',
                    'good_moral' => 'Good Moral Certificate',
                    'medical_cert' => 'Medical Certificate',
                    'medical_clearance' => 'Medical/Physical Clearance',
                    'coach_reco' => 'Coach Recommendation',
                    'adviser_reco' => 'Adviser Recommendation',
                    'guardian_id' => 'Guardian Valid ID'
                ];
                $files = $application->uploaded_files ?? [];
            @endphp

            @foreach($docs as $key => $label)
                <div class="flex justify-between items-center border-b border-dashed border-gray-300 py-0.5">
                    <span class="uppercase text-gray-700 font-medium">{{ $label }}</span>
                    @if(isset($files[$key]))
                        <span class="font-bold text-black text-[9px] bg-gray-200 px-1 rounded">SUBMITTED</span>
                    @else
                        <span class="text-gray-300 text-[9px]">---</span>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- SIGNATURES --}}
        <div class="flex justify-between gap-10 mt-8">
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

        {{-- FOOTER --}}
        <div class="mt-auto pt-4 text-center text-[9px] text-gray-400 border-t border-gray-200">
            <p>System Generated Form | Date Printed: {{ now()->format('Y-m-d H:i') }} | NAS Admission System</p>
        </div>

    </div>

</body>
</html>