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
        // ⚡ EXACT ZIP CODE FETCH ⚡
        $zip  = $details->zip_code ?? ($applicantFallback->zip_code ?? ($student->zip_code ?? ''));

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
        // Subukang kunin ang category sa team, kung wala, sa applicant's sport specification
        $displayCategory = $student->team->sport_type ?? ($applicantFallback->sport_specification ?? '-');
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
                            <img src="{{ $student->id_picture ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->first_name . ' ' . $student->last_name) . '&background=random&size=256' }}" 
                                 class="w-32 h-32 md:w-44 md:h-44 rounded-full border-4 border-white shadow-xl object-cover bg-white" 
                                 alt="Profile">
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
                        <div class="mt-4 md:mt-0 md:ml-6 text-center md:text-left w-full md:w-auto z-10 flex-1 md:pb-2">
                            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 leading-tight break-words px-2 md:px-0">
                                {{ $student->last_name }}, {{ $student->first_name }} 
                                <span class="text-gray-500 font-normal text-lg md:text-xl block md:inline">{{ $student->middle_name }}</span>
                            </h1>
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mt-2 text-sm text-gray-600">
                                <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-mono font-bold border border-blue-100 flex items-center shadow-sm">
                                    <i class='bx bx-id-card mr-1'></i> {{ $student->nas_student_id }}
                                </span>
                                <span class="flex items-center text-gray-500 font-medium">
                                    <i class='bx bx-barcode mr-1'></i> LRN: {{ $student->lrn }}
                                </span>
                            </div>
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
                            {{-- ACADEMIC INFO --}}
                            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition">
                                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2 flex items-center">
                                    <i class='bx bx-book-reader mr-2 text-indigo-500 text-lg'></i> Academic Info
                                </h3>
                                <div class="space-y-4">
                                    <div class="flex justify-between"><span class="text-sm text-gray-500">Grade Level</span><span class="font-bold text-gray-800">{{ $student->grade_level }}</span></div>
                                    <div class="flex justify-between"><span class="text-sm text-gray-500">Section</span><span class="font-bold text-gray-800">{{ $student->section->section_name ?? 'Unassigned' }}</span></div>
                                    <div class="flex justify-between items-center"><span class="text-sm text-gray-500">Adviser</span><span class="text-sm font-medium text-indigo-600 text-right">@if($student->section && $student->section->adviser) {{ $student->section->adviser->first_name }} {{ $student->section->adviser->last_name }} @else N/A @endif</span></div>
                                    <div class="flex justify-between items-center pt-2"><span class="text-sm text-gray-500">Status</span><span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">{{ $student->status }}</span></div>
                                </div>
                            </div>

                            {{-- SPORTS INFO --}}
                            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition">
                                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2 flex items-center">
                                    <i class='bx bx-trophy mr-2 text-yellow-500 text-lg'></i> Sports Info
                                </h3>
                                <div class="space-y-4">
                                    <div><p class="text-xs text-gray-500 mb-1">Focus Sport</p><p class="font-bold text-gray-800 text-lg uppercase">{{ $displaySport }}</p></div>
                                    <div><p class="text-xs text-gray-500 mb-1">Category / Type</p><p class="font-medium text-gray-700">{{ $displayCategory }}</p></div>
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT COLUMN: PERSONAL & FAMILY --}}
                        <div class="md:col-span-2 space-y-6">
                            
                            {{-- PERSONAL INFO --}}
                            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center pb-2 border-b border-gray-100"><span class="bg-indigo-100 p-2 rounded-lg mr-3 text-indigo-600"><i class='bx bx-user'></i></span> Personal Information</h3>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                                    <div><p class="text-xs uppercase tracking-wide text-gray-500 font-bold mb-1">Sex / Gender</p><p class="font-medium text-gray-900">{{ $gender }}</p></div>
                                    <div><p class="text-xs uppercase tracking-wide text-gray-500 font-bold mb-1">Birthdate (Age)</p><p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($student->birthdate)->format('F d, Y') }} <span class="text-gray-500 ml-1">({{ \Carbon\Carbon::parse($student->birthdate)->age }} yrs)</span></p></div>
                                    <div class="sm:col-span-2"><p class="text-xs uppercase tracking-wide text-indigo-400 font-bold mb-1">Email Address</p><span class="font-medium text-indigo-600 break-words">{{ $student->email_address }}</span></div>
                                    <div class="sm:col-span-2 pt-4 border-t border-gray-100">
                                        <p class="text-xs uppercase tracking-wide text-gray-500 font-bold mb-3 flex items-center"><i class='bx bx-map mr-1'></i> Residential Address</p>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div><p class="text-[10px] font-bold text-gray-400 uppercase">Street / House No.</p><p class="text-sm font-medium text-gray-800">{{ $street }}</p></div>
                                            <div><p class="text-[10px] font-bold text-gray-400 uppercase">Barangay</p><p class="text-sm font-medium text-gray-800">{{ $brgy }}</p></div>
                                            <div><p class="text-[10px] font-bold text-gray-400 uppercase">Municipality / City</p><p class="text-sm font-medium text-gray-800">{{ $city }}</p></div>
                                            {{-- ⚡ FIXED ZIP CODE DISPLAY ⚡ --}}
                                            <div>
                                                <p class="text-[10px] font-bold text-gray-400 uppercase">Province</p>
                                                <p class="text-sm font-medium text-gray-800">
                                                    {{ $prov }} 
                                                    @if(!empty($zip) && $zip != 'N/A') <span class="text-gray-500">({{ $zip }})</span> @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    @if($is_ip || $is_pwd || $is_4ps || !empty($otherRemarks))
                                        <div class="sm:col-span-2 mt-2 pt-4 border-t border-gray-100 border-dashed">
                                            <div class="flex flex-wrap gap-3">
                                                @if($is_ip) 
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                        IP: {{ $ip_grp ?: 'Yes' }}
                                                    </span> 
                                                @endif
                                                @if($is_pwd) 
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                                        PWD: {{ $pwd_id ?: 'Yes' }}
                                                    </span> 
                                                @endif
                                                @if($is_4ps) 
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                                        4Ps Beneficiary
                                                    </span> 
                                                @endif
                                                @if(!empty($otherRemarks))
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800 border border-gray-200">
                                                        Others: {{ $otherRemarks }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center pb-2 border-b border-gray-100"><span class="bg-blue-100 p-2 rounded-lg mr-3 text-blue-600"><i class='bx bxs-group'></i></span> Family & Guardian Information</h3>
                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-4 border-b border-gray-50">
                                        <div class="sm:col-span-2"><p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Father's Name</p><p class="font-bold text-gray-800 text-base uppercase">{{ $f_name }}</p></div>
                                        <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Contact No.</p><p class="text-sm font-medium text-gray-700">{{ $f_contact }}</p></div>
                                        <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Email</p><p class="text-sm font-medium text-blue-600">{{ $f_email }}</p></div>
                                        <div class="sm:col-span-2"><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Address</p><p class="text-sm font-medium text-gray-700">{{ $f_addr }}</p></div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-4 border-b border-gray-50">
                                        <div class="sm:col-span-2"><p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Mother's Name</p><p class="font-bold text-gray-800 text-base uppercase">{{ $m_name }}</p></div>
                                        <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Contact No.</p><p class="text-sm font-medium text-gray-700">{{ $m_contact }}</p></div>
                                        <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Email</p><p class="text-sm font-medium text-blue-600">{{ $m_email }}</p></div>
                                        <div class="sm:col-span-2"><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Address</p><p class="text-sm font-medium text-gray-700">{{ $m_addr }}</p></div>
                                    </div>
                                    <div class="bg-orange-50 border border-orange-100 rounded-xl p-5">
                                        <div class="flex items-center mb-3"><i class='bx bxs-shield-alt-2 text-orange-500 mr-2 text-lg'></i><span class="text-xs font-black text-orange-700 uppercase tracking-widest">Emergency Contact / Guardian</span></div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div><p class="text-[10px] font-bold text-orange-400 uppercase mb-1">Guardian Name</p><p class="font-bold text-gray-900 text-base">{{ $g_name }}</p><p class="text-xs text-gray-500 italic">{{ $g_rel }}</p></div>
                                            <div><p class="text-[10px] font-bold text-orange-400 uppercase mb-1">Contact Number</p><a href="tel:{{ $g_contact }}" class="inline-flex items-center text-lg font-mono font-bold text-gray-800 hover:text-orange-600 transition">{{ $g_contact }}</a></div>
                                            <div class="sm:col-span-2 mt-2 pt-2 border-t border-orange-200/50"><p class="text-[10px] font-bold text-orange-400 uppercase mb-1">Guardian Address</p><p class="text-sm text-gray-700">{{ $g_addr }}</p></div>
                                            @if($g_email != 'N/A')
                                                <div class="sm:col-span-2 mt-1"><p class="text-[10px] font-bold text-orange-400 uppercase mb-1">Email</p><p class="text-sm text-gray-700">{{ $g_email }}</p></div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>