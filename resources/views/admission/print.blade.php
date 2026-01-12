<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form - {{ $application->lrn }}</title>
    {{-- Tailwind CSS for quick styling --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Print-specific styles */
        @media print {
            .no-print { display: none !important; }
            body { 
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact; 
                background-color: white;
            }
            @page { 
                margin: 0.5in; 
                size: letter portrait; 
            }
            .paper {
                box-shadow: none;
                margin: 0;
                padding: 0;
                width: 100%;
            }
        }

        /* Screen styles to mimic paper */
        body { 
            background-color: #e5e7eb; 
            font-family: 'Arial', sans-serif; /* Use a standard font for reliability */
        }
        .paper {
            width: 8.5in;
            min-height: 11in;
            background: white;
            margin: 2rem auto;
            padding: 0.5in;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* Custom Table Styles */
        table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; }
        th, td { border: 1px solid #d1d5db; padding: 0.5rem; text-align: left; vertical-align: top; font-size: 0.875rem; }
        th { background-color: #f3f4f6; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; }
        
        .header-section { border-bottom: 2px solid #1f2937; margin-bottom: 1.5rem; padding-bottom: 1rem; text-align: center; }
        .section-title { 
            background-color: #1f2937; 
            color: white; 
            padding: 0.25rem 0.5rem; 
            font-weight: bold; 
            text-transform: uppercase; 
            font-size: 0.875rem; 
            margin-bottom: 0.5rem; 
        }
    </style>
</head>
<body>

    {{-- Controls (Hidden when printing) --}}
    <div class="no-print fixed top-0 left-0 w-full bg-white shadow-md p-4 z-50 flex justify-between items-center border-b border-gray-200">
        <h1 class="font-bold text-gray-800 text-lg">Print Preview</h1>
        <div class="flex gap-3">
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print / Save as PDF
            </button>
            <button onclick="window.close()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition">
                Close
            </button>
        </div>
    </div>

    {{-- Paper Content --}}
    <div class="paper mt-20 print:mt-0">
        
        {{-- Header --}}
        <div class="header-section">
            <img src="{{ asset('images/nas/nas-logo-sidebar.png') }}" alt="NAS Logo" class="h-20 mx-auto mb-2 object-contain">
            <h1 class="text-2xl font-bold text-gray-900 uppercase">National Academy of Sports</h1>
            <p class="text-sm text-gray-600 font-semibold uppercase tracking-wider">Student-Athlete Admission Application Form</p>
            <p class="text-xs text-gray-500">New Clark City, Capas, Tarlac</p>
        </div>

        {{-- Meta Info --}}
        <div class="flex justify-between items-end mb-6 text-sm">
            <div>
                <p><span class="font-bold text-gray-600 uppercase text-xs">Reference No:</span> <span class="font-mono font-bold text-lg text-black">{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</span></p>
                <p><span class="font-bold text-gray-600 uppercase text-xs">Date Filed:</span> {{ $application->created_at->format('F d, Y') }}</p>
            </div>
            <div class="text-right">
                <div class="border border-gray-300 w-32 h-32 flex items-center justify-center bg-gray-50 overflow-hidden relative">
                    @if(isset($application->uploaded_files['id_picture']))
                        <img src="{{ $application->uploaded_files['id_picture'] }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-xs text-gray-400 font-bold uppercase text-center p-2">2x2 ID<br>Picture</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- I. Personal Information --}}
        <div class="section-title">I. Applicant Information</div>
        <table class="mb-6">
            <tr>
                <th width="20%">Full Name</th>
                <td colspan="3" class="uppercase font-bold">{{ $application->last_name }}, {{ $application->first_name }} {{ $application->middle_name }}</td>
            </tr>
            <tr>
                <th>LRN</th>
                <td>{{ $application->lrn }}</td>
                <th width="15%">Birthdate</th>
                <td>{{ \Carbon\Carbon::parse($application->date_of_birth)->format('M d, Y') }} ({{ $application->age }} yrs)</td>
            </tr>
            <tr>
                <th>Gender</th>
                <td>{{ $application->gender }}</td>
                <th>Religion</th>
                <td>{{ $application->religion }}</td>
            </tr>
            <tr>
                <th>Birthplace</th>
                <td colspan="3">{{ $application->birthplace }}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td colspan="3">
                    {{ $application->street_address }}, {{ $application->barangay }}, {{ $application->municipality_city }}, {{ $application->province }} {{ $application->region }} {{ $application->zip_code }}
                </td>
            </tr>
            <tr>
                <th>Email</th>
                <td colspan="3">{{ $application->email_address }}</td>
            </tr>
        </table>

        {{-- II. Academic & Sports --}}
        <div class="section-title">II. Academic & Sports Profile</div>
        <table class="mb-6">
            <tr>
                <th width="25%">Applying For Grade</th>
                <td>{{ $application->grade_level_applied }}</td>
                <th width="25%">Priority Sport</th>
                <td class="font-bold uppercase">{{ $application->sport }}</td>
            </tr>
            <tr>
                <th>Last School</th>
                <td colspan="3">{{ $application->previous_school }} ({{ $application->school_type }})</td>
            </tr>
            <tr>
                <th>Palaro Participation</th>
                <td colspan="3">
                    @if($application->has_palaro_participation)
                        <span class="font-bold text-green-700">YES</span> (Year: {{ $application->palaro_year }})
                    @else
                        NO
                    @endif
                </td>
            </tr>
        </table>

        {{-- Special Categories --}}
        @php
            // Logic to check for "Others" category
            $categoriesData = [];
            $otherDetails = '';
            
            if ($application->special_categories) {
                $categoriesData = array_map('trim', explode(',', $application->special_categories));
            }

            $is_others = false;
            foreach($categoriesData as $cat) {
                if (\Illuminate\Support\Str::startsWith(strtolower($cat), 'others')) {
                    $is_others = true;
                    // Extract details if stored in the string "Others: details" or separate column
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

        <div class="mb-6 border border-gray-300 p-2 text-sm bg-gray-50">
            <span class="font-bold uppercase text-xs text-gray-500 mr-2">Special Categories:</span>
            <span class="inline-flex items-center mr-4">
                <span class="w-4 h-4 border border-black flex items-center justify-center mr-1 text-xs font-bold">{{ $application->is_ip ? '✓' : '' }}</span> Indigenous People
            </span>
            <span class="inline-flex items-center mr-4">
                <span class="w-4 h-4 border border-black flex items-center justify-center mr-1 text-xs font-bold">{{ $application->is_pwd ? '✓' : '' }}</span> PWD
            </span>
            <span class="inline-flex items-center mr-4">
                <span class="w-4 h-4 border border-black flex items-center justify-center mr-1 text-xs font-bold">{{ $application->is_4ps ? '✓' : '' }}</span> 4Ps
            </span>
            
            {{-- Added OTHERS Checkbox --}}
            <span class="inline-flex items-center">
                <span class="w-4 h-4 border border-black flex items-center justify-center mr-1 text-xs font-bold">{{ $is_others ? '✓' : '' }}</span> 
                Others {{ $is_others && $otherDetails ? ': ' . $otherDetails : '' }}
            </span>
        </div>

        {{-- III. Guardian --}}
        <div class="section-title">III. Guardian Information</div>
        <table class="mb-8">
            <tr>
                <th width="20%">Name</th>
                <td>{{ $application->guardian_name }}</td>
                <th width="20%">Relationship</th>
                <td>{{ $application->guardian_relationship }}</td>
            </tr>
            <tr>
                <th>Contact No.</th>
                <td>{{ $application->guardian_contact }}</td>
                <th>Email</th>
                <td>{{ $application->guardian_email }}</td>
            </tr>
        </table>

        {{-- IV. Document Checklist --}}
        <div class="section-title">IV. Submitted Documents</div>
        <div class="grid grid-cols-2 gap-x-8 text-xs mb-8">
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
                <div class="flex justify-between items-center border-b border-gray-200 py-1">
                    <span class="uppercase text-gray-700">{{ $label }}</span>
                    @if(isset($files[$key]))
                        <span class="font-bold text-black">[ / ] SUBMITTED</span>
                    @else
                        <span class="text-gray-400">[   ] ---</span>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Signatures --}}
        <div class="mt-12 flex justify-between gap-10">
            <div class="w-1/2 text-center">
                <div class="border-b border-black mb-2"></div>
                <p class="font-bold uppercase text-sm">{{ $application->first_name }} {{ $application->last_name }}</p>
                <p class="text-xs text-gray-500 uppercase">Applicant Signature</p>
            </div>
            <div class="w-1/2 text-center">
                <div class="border-b border-black mb-2"></div>
                <p class="font-bold uppercase text-sm">{{ $application->guardian_name }}</p>
                <p class="text-xs text-gray-500 uppercase">Guardian Signature</p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="absolute bottom-4 left-0 w-full text-center text-[10px] text-gray-400 px-8">
            <div class="border-t border-gray-300 pt-2">
                <p>System Generated Form | Date Printed: {{ now()->format('Y-m-d H:i:s') }}</p>
                <p class="italic">National Academy of Sports Admission System</p>
            </div>
        </div>

    </div>

</body>
</html>