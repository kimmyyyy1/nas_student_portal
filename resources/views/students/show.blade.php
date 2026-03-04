<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Student Profile') }}
            </h2>
            <a href="{{ route('students.index', $queryParams ?? []) }}" wire:navigate class="hidden md:flex text-sm text-gray-500 hover:text-gray-700 items-center">
                <i class='bx bx-arrow-back mr-1'></i> Back to Directory
            </a>
        </div>
    </x-slot>

    @php
        // 1. FIX GENDER
        $gender = strtoupper($student->gender ?? $student->sex);
        if(in_array($gender, ['BOY', 'M', 'MALE'])) $gender = 'MALE';
        if(in_array($gender, ['GIRL', 'F', 'FEMALE'])) $gender = 'FEMALE';

        // 2. FORCE FETCH DATA
        $details = \App\Models\EnrollmentDetail::where('email', $student->email_address)
                    ->orWhere('lrn', $student->lrn)
                    ->latest() 
                    ->first();

        $applicantFallback = \App\Models\Applicant::where('lrn', $student->lrn)->first();

        // 3. VARIABLE MAPPING (Priority: Details -> Applicant -> Student Record -> 'N/A')
        function getData($d, $a, $s, $fieldD, $fieldA, $fieldS) {
            return $d->$fieldD ?? ($a->$fieldA ?? ($s->$fieldS ?? 'N/A'));
        }

        // Address
        $street = getData($details, $applicantFallback, $student, 'street_house_no', 'street_address', 'street_address');
        $brgy = getData($details, $applicantFallback, $student, 'barangay', 'barangay', 'barangay');
        $city = getData($details, $applicantFallback, $student, 'municipality_city', 'municipality_city', 'municipality_city');
        $prov = getData($details, $applicantFallback, $student, 'province', 'province', 'province');
        $region = getData($details, $applicantFallback, $student, 'region', 'region', 'region');
        // ⚡ EXACT ZIP CODE FETCH ⚡
        $zip  = $details->zip_code ?? ($applicantFallback->zip_code ?? ($student->zip_code ?? ''));

        // New fields
        $birthplace = $details->birthplace ?? ($applicantFallback->birthplace ?? ($student->birthplace ?? 'N/A'));
        $religion   = $details->religion ?? ($applicantFallback->religion ?? ($student->religion ?? 'N/A'));
        $extensionName = $details->extension_name ?? ($applicantFallback->extension_name ?? ($student->extension_name ?? ''));
        $studentNo = $student->nas_student_id ?? ($applicantFallback->student_id ?? 'N/A');

        // Father
        $f_name = getData($details, $applicantFallback, $student, 'father_name', 'father_name', 'father_name');
        $f_contact = getData($details, $applicantFallback, $student, 'father_contact', 'father_contact', 'father_contact');
        $f_email = getData($details, $applicantFallback, $student, 'father_email', 'father_email', 'father_email');
        $f_addr = getData($details, $applicantFallback, $student, 'father_address', 'father_address', 'father_address');

        // Mother
        $m_name = $details->mother_maiden_name ?? ($applicantFallback->mother_name ?? ($student->mother_name ?? 'N/A'));
        $m_contact = getData($details, $applicantFallback, $student, 'mother_contact', 'mother_contact', 'mother_contact');
        $m_email = getData($details, $applicantFallback, $student, 'mother_email', 'mother_email', 'mother_email');
        $m_addr = getData($details, $applicantFallback, $student, 'mother_address', 'mother_address', 'mother_address');

        // Guardian
        $g_name = getData($details, $applicantFallback, $student, 'guardian_name', 'guardian_name', 'guardian_name');
        $g_rel = getData($details, $applicantFallback, $student, 'guardian_relationship', 'guardian_relationship', 'guardian_relationship');
        $g_contact = getData($details, $applicantFallback, $student, 'guardian_contact', 'guardian_contact', 'guardian_contact');
        $g_email = $details->guardian_email ?? 'N/A';
        $g_addr = getData($details, $applicantFallback, $student, 'guardian_address', 'guardian_address', 'guardian_address');
        
        // ROBUST FLAG CHECKING
        $raw_ip = $details->is_ip ?? ($applicantFallback->is_ip ?? ($student->is_ip ?? 'No'));
        $is_ip = in_array(strtolower(trim($raw_ip)), ['yes', '1', 'true', 'y']);
        $ip_grp = $details->ip_group_name ?? ($applicantFallback->ip_group_name ?? ($student->ip_group_name ?? ''));
        
        $raw_pwd = $details->is_pwd ?? ($applicantFallback->is_pwd ?? ($student->is_pwd ?? 'No'));
        $is_pwd = in_array(strtolower(trim($raw_pwd)), ['yes', '1', 'true', 'y']);
        $pwd_id = $details->pwd_disability ?? ($applicantFallback->pwd_disability ?? ($student->pwd_disability ?? ''));
        
        $raw_4ps = $details->is_4ps ?? ($applicantFallback->is_4ps ?? ($student->is_4ps ?? 'No'));
        $is_4ps = in_array(strtolower(trim($raw_4ps)), ['yes', '1', 'true', 'y']);
        
        // Other Remarks
        $otherRemarks = $details->other_remarks ?? ($applicantFallback->other_remarks ?? ($student->other_remarks ?? ''));
        
        // ⚡ SPORT & CATEGORY ⚡
        $displaySport = $student->sport ?? ($applicantFallback->sport ?? ($details->sport ?? 'N/A'));
        $displayCategory = $student->sport_specification ?? ($applicantFallback->sport_specification ?? ($student->team->sport_type ?? '-'));

        // ⚡ SCHOOL INFO ⚡
        $last_grade = $student->last_grade_level ?? ($applicantFallback->school_last_grade_level ?? ($details->last_grade_level ?? 'N/A'));
        $last_yr = $student->last_school_year ?? ($applicantFallback->school_last_year_completed ?? ($details->last_school_year ?? 'N/A'));
        $sch_name = $student->school_name ?? ($applicantFallback->school_name ?? ($details->school_name ?? 'N/A'));
        $sch_id = $student->school_id ?? ($applicantFallback->school_id ?? ($details->school_id ?? 'N/A'));
        $sch_type = $student->school_type ?? ($applicantFallback->school_type ?? ($details->school_type ?? 'N/A'));
        $sch_addr = $student->school_address ?? ($applicantFallback->school_address ?? ($details->school_address ?? 'N/A'));
    @endphp

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- MOBILE BACK BUTTON --}}
            <div class="block md:hidden mb-4">
                <a href="{{ route('students.index', $queryParams ?? []) }}" wire:navigate 
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-full shadow-sm text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
                    <i class='bx bx-arrow-back mr-2'></i> Back
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-200 relative pb-10">
                
                {{-- COVER IMAGE --}}
                <div class="bg-gradient-to-r from-blue-900 to-indigo-800 h-32 md:h-48 relative z-0">
                    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                </div>
                
                <div class="px-4 md:px-8 relative z-10">
                    
                    {{-- EDIT BUTTON --}}
                    <div class="hidden md:block absolute top-6 right-8 z-50">
                        <a href="{{ route('students.edit', ['student' => $student->id] + ($queryParams ?? [])) }}" wire:navigate
                           class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md cursor-pointer">
                            <i class='bx bx-edit text-lg mr-2'></i> Edit Profile
                        </a>
                    </div>

                    {{-- PROFILE HEADER --}}
                    <div class="relative flex flex-col md:flex-row items-center md:items-end -mt-16 md:-mt-20 mb-8">
                        <div class="relative group z-20">
                            <img src="{{ $student->id_picture ?? asset('images/default-avatar.svg') }}" 
                                 class="w-32 h-32 md:w-44 md:h-44 rounded-full border-4 border-white shadow-xl object-cover bg-white" 
                                 alt="{{ $student->full_name }}'s profile photo">
                            @php
                                $statusColor = match($student->status) {
                                    'Enrolled' => 'bg-green-500',
                                    'Pending Renewal' => 'bg-yellow-500 animate-pulse',
                                    'Transfer out' => 'bg-red-500',
                                    'Graduate' => 'bg-blue-500',
                                    default => 'bg-gray-400'
                                };
                            @endphp
                            <div class="absolute bottom-2 right-2 w-6 h-6 rounded-full border-2 border-white {{ $statusColor }}" title="{{ $student->status }}"></div>
                        </div>
                    </div>

                    {{-- MOBILE EDIT BUTTON --}}
                    <div class="block md:hidden w-full mb-8">
                        <a href="{{ route('students.edit', ['student' => $student->id] + ($queryParams ?? [])) }}" wire:navigate
                           class="flex items-center justify-center w-full px-4 py-3 bg-indigo-600 border border-transparent rounded-lg font-bold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none shadow-md cursor-pointer transition">
                            <i class='bx bx-edit text-xl mr-2'></i> Edit Profile
                        </a>
                    </div>

                    {{-- DETAILS GRID --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 border-t border-gray-100 pt-8 relative z-0">
                        
                        {{-- LEFT COLUMN: INFO CARDS --}}
                        <div class="space-y-6">
                            
                            {{-- ⚡ FIXED ACADEMIC INFO ⚡ --}}
                            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition">
                                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2 flex items-center">
                                    <i class='bx bx-book-reader mr-2 text-indigo-500 text-lg'></i> Academic Info
                                </h3>
                                <div class="space-y-4">
                                    {{-- FORMATTED GRADE LEVEL --}}
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-500">Grade Level</span>
                                        <span class="font-black text-indigo-700 bg-indigo-50 border border-indigo-100 px-3 py-1 rounded-lg shadow-sm">
                                            Grade {{ trim(str_ireplace('grade', '', strtolower($student->grade_level))) }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-500">Section</span>
                                        <span class="font-bold text-gray-800">{{ $student->section->section_name ?? 'Unassigned' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-500">Adviser</span>
                                        <span class="text-sm font-medium text-indigo-600 text-right">
                                            @if($student->section && $student->section->adviser) 
                                                {{ $student->section->adviser->first_name }} {{ $student->section->adviser->last_name }} 
                                            @else 
                                                N/A 
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center pt-3 border-t border-gray-50 mt-2">
                                        <span class="text-sm text-gray-500">Status</span>
                                        <span class="px-3 py-1 text-xs font-bold rounded-full border {{ $student->status == 'Enrolled' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-gray-100 text-gray-700 border-gray-200' }}">
                                            {{ $student->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT COLUMN: PERSONAL & FAMILY --}}
                        <div class="md:col-span-2 space-y-6">
                            
                            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center pb-2 border-b border-gray-100"><span class="bg-indigo-100 p-2 rounded-lg mr-3 text-indigo-600"><i class='bx bx-user'></i></span> Student-Athlete's Information</h3>
                                
                                {{-- Identification --}}
                                <div class="mb-6 pb-4 border-b border-gray-100">
                                    <p class="text-xs font-black text-indigo-500 uppercase tracking-widest mb-3">Identification</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                                        <div><p class="text-[10px] uppercase tracking-wide text-gray-400 font-bold mb-1">LRN</p><p class="font-mono font-bold text-gray-900">{{ $student->lrn }}</p></div>
                                        <div><p class="text-[10px] uppercase tracking-wide text-gray-400 font-bold mb-1">Student No.</p><p class="font-mono font-bold text-indigo-700">{{ $studentNo }}</p></div>
                                    </div>
                                </div>

                                {{-- Personal Details --}}
                                <div class="mb-6 pb-4 border-b border-gray-100">
                                    <p class="text-xs font-black text-indigo-500 uppercase tracking-widest mb-3">Personal Details</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                                        <div><p class="text-[10px] uppercase tracking-wide text-gray-400 font-bold mb-1">Last Name</p><p class="font-bold text-gray-900 uppercase">{{ $student->last_name }}</p></div>
                                        <div><p class="text-[10px] uppercase tracking-wide text-gray-400 font-bold mb-1">First Name</p><p class="font-bold text-gray-900 uppercase">{{ $student->first_name }}</p></div>
                                        <div><p class="text-[10px] uppercase tracking-wide text-gray-400 font-bold mb-1">Middle Name</p><p class="font-bold text-gray-900 uppercase">{{ $student->middle_name ?? 'N/A' }}</p></div>
                                        <div><p class="text-[10px] uppercase tracking-wide text-gray-400 font-bold mb-1">Extension Name</p><p class="font-bold text-gray-900 uppercase">{{ $extensionName ?: 'N/A' }}</p></div>
                                        <div class="sm:col-span-2"><p class="text-[10px] uppercase tracking-wide text-indigo-400 font-bold mb-1">Email Address</p><span class="font-medium text-indigo-600 break-words">{{ $student->email_address }}</span></div>
                                        <div><p class="text-[10px] uppercase tracking-wide text-gray-400 font-bold mb-1">Sex</p><p class="font-bold text-gray-900 uppercase">{{ $gender }}</p></div>
                                        <div><p class="text-[10px] uppercase tracking-wide text-gray-400 font-bold mb-1">Birthdate</p><p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($student->birthdate)->format('F d, Y') }}</p></div>
                                        <div><p class="text-[10px] uppercase tracking-wide text-gray-400 font-bold mb-1">Birthplace</p><p class="font-medium text-gray-900">{{ $birthplace }}</p></div>
                                        <div><p class="text-[10px] uppercase tracking-wide text-gray-400 font-bold mb-1">Age</p><p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($student->birthdate)->age }} years old</p></div>
                                        <div><p class="text-[10px] uppercase tracking-wide text-gray-400 font-bold mb-1">Religion</p><p class="font-medium text-gray-900">{{ $religion }}</p></div>
                                        <div><p class="text-[10px] uppercase tracking-wide text-gray-400 font-bold mb-1">Sport</p><p class="font-black text-gray-900 text-lg uppercase">{{ $displaySport }}</p></div>
                                        <div><p class="text-[10px] uppercase tracking-wide text-gray-400 font-bold mb-1">Specification / Event</p><p class="font-bold text-gray-900 uppercase">{{ $displayCategory }}</p></div>
                                    </div>
                                </div>

                                {{-- Special Groups --}}
                                <div class="mb-6 pb-4 border-b border-gray-100">
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div class="p-3 rounded-xl {{ $is_ip ? 'bg-amber-50 border border-amber-200' : 'bg-gray-50 border border-gray-200' }}">
                                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Indigenous People (IP)</p>
                                            <p class="font-bold text-sm {{ $is_ip ? 'text-amber-700' : 'text-gray-600' }}">{{ $is_ip ? 'Yes' . ($ip_grp ? ' — ' . $ip_grp : '') : 'No' }}</p>
                                        </div>
                                        <div class="p-3 rounded-xl {{ $is_pwd ? 'bg-purple-50 border border-purple-200' : 'bg-gray-50 border border-gray-200' }}">
                                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Person with Disability (PWD)</p>
                                            <p class="font-bold text-sm {{ $is_pwd ? 'text-purple-700' : 'text-gray-600' }}">{{ $is_pwd ? 'Yes' . ($pwd_id ? ' — ' . $pwd_id : '') : 'No' }}</p>
                                        </div>
                                        <div class="p-3 rounded-xl {{ $is_4ps ? 'bg-rose-50 border border-rose-200' : 'bg-gray-50 border border-gray-200' }}">
                                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">4Ps Beneficiary</p>
                                            <p class="font-bold text-sm {{ $is_4ps ? 'text-rose-700' : 'text-gray-600' }}">{{ $is_4ps ? 'Yes' : 'No' }}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Address --}}
                                <div>
                                    <p class="text-xs font-black text-indigo-500 uppercase tracking-widest mb-3 flex items-center"><i class='bx bx-map mr-1'></i> Residential Address</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div><p class="text-[10px] font-bold text-gray-400 uppercase">Region</p><p class="text-sm font-medium text-gray-800">{{ $region }}</p></div>
                                        <div><p class="text-[10px] font-bold text-gray-400 uppercase">Province</p><p class="text-sm font-medium text-gray-800">{{ $prov }}</p></div>
                                        <div><p class="text-[10px] font-bold text-gray-400 uppercase">Municipality / City</p><p class="text-sm font-medium text-gray-800">{{ $city }}</p></div>
                                        <div><p class="text-[10px] font-bold text-gray-400 uppercase">Barangay</p><p class="text-sm font-medium text-gray-800">{{ $brgy }}</p></div>
                                        <div><p class="text-[10px] font-bold text-gray-400 uppercase">Street / House No.</p><p class="text-sm font-medium text-gray-800">{{ $street }}</p></div>
                                        <div><p class="text-[10px] font-bold text-gray-400 uppercase">Zip Code</p><p class="text-sm font-medium text-gray-800">{{ $zip ?: 'N/A' }}</p></div>
                                    </div>
                                </div>
                            </div>

                            {{-- PARENTS' & GUARDIAN'S INFORMATION --}}
                            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center pb-2 border-b border-gray-100"><span class="bg-blue-100 p-2 rounded-lg mr-3 text-blue-600"><i class='bx bxs-group'></i></span> Parents' & Designated Guardian's Information</h3>
                                <div class="space-y-6">
                                    {{-- Father --}}
                                    <div class="pb-5 border-b border-gray-100">
                                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-3">Father's Information</p>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div class="sm:col-span-2"><p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Name (Last Name, First Name, Middle Name)</p><p class="font-bold text-gray-800 text-base uppercase">{{ $f_name }}</p></div>
                                            <div class="sm:col-span-2"><p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Address</p><p class="text-sm font-medium text-gray-700">{{ $f_addr }}</p></div>
                                            <div><p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Contact No.</p><p class="text-sm font-medium text-gray-700">{{ $f_contact }}</p></div>
                                            <div><p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Email</p><p class="text-sm font-medium text-blue-600">{{ $f_email }}</p></div>
                                        </div>
                                    </div>
                                    {{-- Mother --}}
                                    <div class="pb-5 border-b border-gray-100">
                                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-3">Mother's Information</p>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div class="sm:col-span-2"><p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Name (Last Name, First Name, Middle Name)</p><p class="font-bold text-gray-800 text-base uppercase">{{ $m_name }}</p></div>
                                            <div class="sm:col-span-2"><p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Address</p><p class="text-sm font-medium text-gray-700">{{ $m_addr }}</p></div>
                                            <div><p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Contact No.</p><p class="text-sm font-medium text-gray-700">{{ $m_contact }}</p></div>
                                            <div><p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Email</p><p class="text-sm font-medium text-blue-600">{{ $m_email }}</p></div>
                                        </div>
                                    </div>
                                    {{-- Guardian --}}
                                    <div class="bg-orange-50 border border-orange-100 rounded-xl p-5">
                                        <div class="flex items-center mb-3"><i class='bx bxs-shield-alt-2 text-orange-500 mr-2 text-lg'></i><span class="text-xs font-black text-orange-700 uppercase tracking-widest">Guardian's Information (If not Parent)</span></div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div><p class="text-[10px] font-bold text-orange-400 uppercase mb-1">Name (Last Name, First Name, Middle Name)</p><p class="font-bold text-gray-900 text-base uppercase">{{ $g_name }}</p></div>
                                            <div><p class="text-[10px] font-bold text-orange-400 uppercase mb-1">Relationship</p><p class="font-bold text-gray-900">{{ $g_rel }}</p></div>
                                            <div class="sm:col-span-2"><p class="text-[10px] font-bold text-orange-400 uppercase mb-1">Address</p><p class="text-sm text-gray-700">{{ $g_addr }}</p></div>
                                            <div><p class="text-[10px] font-bold text-orange-400 uppercase mb-1">Contact No.</p><a href="tel:{{ $g_contact }}" class="inline-flex items-center font-mono font-bold text-gray-800 hover:text-orange-600 transition">{{ $g_contact }}</a></div>
                                            <div><p class="text-[10px] font-bold text-orange-400 uppercase mb-1">Email</p><p class="text-sm text-gray-700">{{ $g_email }}</p></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SCHOOL INFORMATION --}}
                            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center pb-2 border-b border-gray-100"><span class="bg-emerald-100 p-2 rounded-lg mr-3 text-emerald-600"><i class='bx bx-building'></i></span> Previous School Information</h3>
                                @if($sch_name && $sch_name !== 'N/A')
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                                        <div><p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Last Grade Level Completed</p><p class="font-medium text-gray-900">{{ $last_grade }}</p></div>
                                        <div><p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Last School Year Completed</p><p class="font-medium text-gray-900">{{ $last_yr }}</p></div>
                                        <div class="sm:col-span-2"><p class="text-[10px] font-bold text-gray-400 uppercase mb-1">School Name</p><p class="font-bold text-gray-900 text-base uppercase">{{ $sch_name }}</p></div>
                                        <div><p class="text-[10px] font-bold text-gray-400 uppercase mb-1">School ID</p><p class="font-medium text-gray-900">{{ $sch_id }}</p></div>
                                        <div><p class="text-[10px] font-bold text-gray-400 uppercase mb-1">School Type</p><p class="font-medium text-gray-900">{{ $sch_type }}</p></div>
                                        <div class="sm:col-span-2"><p class="text-[10px] font-bold text-gray-400 uppercase mb-1">School Address</p><p class="font-medium text-gray-900">{{ $sch_addr }}</p></div>
                                    </div>
                                @else
                                    <div class="text-center py-6 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                                        <p class="text-sm text-gray-400 italic">No previous school information on file.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>