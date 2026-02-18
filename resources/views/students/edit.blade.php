<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Student Record') }}
        </h2>
    </x-slot>

    @php
        // SMART FETCH ENROLLMENT DETAILS (For Fallbacks)
        $details = $student->enrollmentDetail; 
        if (!$details && $student->user) {
            $details = $student->user->enrollmentDetail;
        }
        if (!$details) {
            $details = \App\Models\EnrollmentDetail::where('lrn', $student->lrn)
                        ->orWhere('email', $student->email_address)
                        ->latest()
                        ->first();
        }
        $applicantFallback = \App\Models\Applicant::where('lrn', $student->lrn)->first();

        // Helper function for fallbacks
        function getEditVal($d, $a, $s, $fieldD, $fieldA, $fieldS) {
            return old($fieldS, $s->$fieldS ?? ($d->$fieldD ?? ($a->$fieldA ?? '')));
        }
    @endphp

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">

                    {{-- ERROR DISPLAY --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                            <ul class="list-disc ml-8 text-sm">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('students.update', $student->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- PHOTO UPLOAD LOGIC --}}
                        <div class="mb-10 flex flex-col items-center justify-center border-b border-gray-100 pb-8">
                            <label class="block text-gray-700 font-bold mb-3 text-lg">Student 2x2 ID Picture</label>
                            
                            @php 
                                $avatarUrl = $student->id_picture ?? $student->photo ?? null;
                                $hasAvatar = !empty($avatarUrl); 
                            @endphp

                            <div class="relative group w-48 h-48"> 
                                <div id="placeholder-icon" class="w-full h-full border-4 border-gray-300 border-dashed rounded-lg bg-gray-50 flex flex-col items-center justify-center text-gray-400 hover:bg-gray-100 transition cursor-pointer {{ $hasAvatar ? 'hidden' : '' }}" onclick="document.getElementById('photo-input').click()">
                                    <i class='bx bxs-user-rectangle text-7xl mb-2'></i>
                                    <span class="text-xs font-semibold uppercase tracking-wider">Upload Photo</span>
                                </div>
                                <img id="photo-preview" src="{{ $hasAvatar ? $avatarUrl : '' }}" class="absolute inset-0 w-full h-full object-cover rounded-lg border-4 border-gray-300 shadow-md {{ $hasAvatar ? '' : 'hidden' }}" alt="ID Preview">
                                <label for="photo-input" class="absolute inset-0 bg-black bg-opacity-50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer rounded-lg">
                                    <i class='bx bxs-camera text-white text-4xl mb-1'></i>
                                    <span class="text-white text-xs font-bold uppercase tracking-wide">Change Photo</span>
                                </label>
                            </div>
                            <input type="file" id="photo-input" name="photo" accept="image/png, image/jpeg, image/jpg" class="hidden" onchange="previewImage(event)">
                            <p class="text-xs text-gray-400 mt-3 text-center">Allowed formats: JPG, PNG <br> Max size: 5MB</p>
                        </div>

                        {{-- FORM FIELDS --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            {{-- IDENTIFICATION --}}
                            <div class="md:col-span-2 bg-blue-50 p-6 rounded-lg border border-blue-100 shadow-sm">
                                <h3 class="font-bold text-blue-800 mb-4 flex items-center text-lg"><i class='bx bx-id-card mr-2 text-xl'></i> Identification</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Student ID</label><input type="text" name="nas_student_id" value="{{ old('nas_student_id', $student->nas_student_id) }}" class="w-full border-gray-300 rounded-md shadow-sm" required></div>
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">LRN</label><input type="text" name="lrn" value="{{ old('lrn', $student->lrn) }}" class="w-full border-gray-300 rounded-md shadow-sm" required></div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Status</label>
                                        <select name="status" class="w-full border-gray-300 rounded-md shadow-sm">
                                            @foreach(['New', 'Continuing', 'Enrolled', 'Transfer out', 'Graduate'] as $stat)
                                                <option value="{{ $stat }}" {{ old('status', $student->status) == $stat ? 'selected' : '' }}>{{ $stat }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- PERSONAL INFO --}}
                            <div class="md:col-span-2">
                                <h3 class="font-bold text-gray-700 mb-4 border-b pb-2 flex items-center"><i class='bx bx-user mr-2'></i> Personal Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Last Name</label><input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" class="w-full border-gray-300 rounded-md shadow-sm" required></div>
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">First Name</label><input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" class="w-full border-gray-300 rounded-md shadow-sm" required></div>
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Middle Name</label><input type="text" name="middle_name" value="{{ old('middle_name', $student->middle_name) }}" class="w-full border-gray-300 rounded-md shadow-sm"></div>
                                    
                                    {{-- EMAIL ONLY --}}
                                    <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Email Address</label><input type="email" name="email_address" value="{{ getEditVal($details, $applicantFallback, $student, 'email', 'email', 'email_address') }}" class="w-full border-gray-300 rounded-md shadow-sm" required></div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Sex</label>
                                        <select name="sex" class="w-full border-gray-300 rounded-md shadow-sm">
                                            @php $currentGender = strtoupper($student->gender ?? $student->sex); @endphp
                                            <option value="Male" {{ old('sex', in_array($currentGender, ['BOY', 'M', 'MALE']) ? 'Male' : '') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('sex', in_array($currentGender, ['GIRL', 'F', 'FEMALE']) ? 'Female' : '') == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                    
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Birthdate</label><input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate', $student->birthdate ? \Carbon\Carbon::parse($student->birthdate)->format('Y-m-d') : '') }}" class="w-full border-gray-300 rounded-md shadow-sm" required></div>
                                    
                                    <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Birthplace</label><input type="text" name="birthplace" value="{{ old('birthplace', $student->birthplace) }}" class="w-full border-gray-300 rounded-md shadow-sm" required></div>
                                    
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Religion</label><input type="text" name="religion" value="{{ old('religion', $student->religion) }}" class="w-full border-gray-300 rounded-md shadow-sm"></div>
                                    
                                    {{-- CHECKBOXES & OTHERS --}}
                                    <div class="md:col-span-3 mt-4 pt-4 border-t border-gray-100 border-dashed">
                                        <div class="flex flex-col md:flex-row md:items-center gap-4">
                                            <div class="flex flex-wrap gap-4">
                                                <label class="flex items-center space-x-2 cursor-pointer">
                                                    @php
                                                        $rawIp = getEditVal($details, $applicantFallback, $student, 'is_ip', 'is_ip', 'is_ip');
                                                        $isCheckedIp = in_array(strtolower(trim($rawIp)), ['yes', '1', 'true', 'y']);
                                                    @endphp
                                                    <input type="checkbox" name="is_ip" value="Yes" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-4 w-4" {{ old('is_ip', $isCheckedIp ? 'Yes' : '') == 'Yes' ? 'checked' : '' }}> 
                                                    <span class="text-xs font-bold text-gray-600 uppercase">Indigenous People (IP)</span>
                                                </label>
                                                <label class="flex items-center space-x-2 cursor-pointer">
                                                    @php
                                                        $rawPwd = getEditVal($details, $applicantFallback, $student, 'is_pwd', 'is_pwd', 'is_pwd');
                                                        $isCheckedPwd = in_array(strtolower(trim($rawPwd)), ['yes', '1', 'true', 'y']);
                                                    @endphp
                                                    <input type="checkbox" name="is_pwd" value="Yes" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-4 w-4" {{ old('is_pwd', $isCheckedPwd ? 'Yes' : '') == 'Yes' ? 'checked' : '' }}> 
                                                    <span class="text-xs font-bold text-gray-600 uppercase">PWD</span>
                                                </label>
                                                <label class="flex items-center space-x-2 cursor-pointer">
                                                    @php
                                                        $raw4ps = getEditVal($details, $applicantFallback, $student, 'is_4ps', 'is_4ps', 'is_4ps');
                                                        $isChecked4ps = in_array(strtolower(trim($raw4ps)), ['yes', '1', 'true', 'y']);
                                                    @endphp
                                                    <input type="checkbox" name="is_4ps" value="Yes" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-4 w-4" {{ old('is_4ps', $isChecked4ps ? 'Yes' : '') == 'Yes' ? 'checked' : '' }}> 
                                                    <span class="text-xs font-bold text-gray-600 uppercase">4Ps Beneficiary</span>
                                                </label>
                                            </div>

                                            <div class="flex items-center flex-1 w-full md:w-auto">
                                                <label for="other_remarks" class="text-xs font-bold text-gray-600 uppercase mr-2 whitespace-nowrap">Others Details:</label>
                                                @php
                                                    $ipGrp = getEditVal($details, $applicantFallback, $student, 'ip_group_name', 'ip_group_name', 'ip_group_name');
                                                    $pwdId = getEditVal($details, $applicantFallback, $student, 'pwd_disability', 'pwd_disability', 'pwd_disability');
                                                    $othersVal = array_filter([$ipGrp, $pwdId]);
                                                    $othersValStr = implode(', ', $othersVal);
                                                @endphp
                                                <input type="text" id="other_remarks" name="other_remarks" value="{{ old('other_remarks', $student->other_remarks ?? $othersValStr) }}" 
                                                       class="border-0 border-b border-gray-300 focus:border-indigo-500 focus:ring-0 text-sm w-full bg-transparent" 
                                                       placeholder="Specific details (e.g. Manobo, Visual Impairment)">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ADDRESS --}}
                            <div class="md:col-span-2">
                                <h3 class="font-bold text-gray-700 mb-4 border-b pb-2 flex items-center"><i class='bx bx-map mr-2'></i> Complete Address</h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Region</label><input type="text" name="region" value="{{ getEditVal($details, $applicantFallback, $student, 'region', 'region', 'region') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required></div>
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Province</label><input type="text" name="province" value="{{ getEditVal($details, $applicantFallback, $student, 'province', 'province', 'province') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required></div>
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">City/Municipality</label><input type="text" name="municipality_city" value="{{ getEditVal($details, $applicantFallback, $student, 'municipality_city', 'municipality_city', 'municipality_city') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required></div>
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Barangay</label><input type="text" name="barangay" value="{{ getEditVal($details, $applicantFallback, $student, 'barangay', 'barangay', 'barangay') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required></div>
                                    
                                    <div class="mt-3 md:col-span-4"><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Street / House No.</label><input type="text" name="street_address" value="{{ getEditVal($details, $applicantFallback, $student, 'street_house_no', 'street_address', 'street_address') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm"></div>
                                </div>
                            </div>

                            {{-- FAMILY & GUARDIAN INFO --}}
                            <div class="md:col-span-2">
                                <h3 class="font-bold text-gray-700 mb-4 border-b pb-2 flex items-center"><i class='bx bxs-group mr-2'></i> Family & Guardian Information</h3>
                                
                                {{-- Father --}}
                                <div class="mb-4 bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-bold text-sm text-gray-700 mb-3">Father's Information</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Name</label><input type="text" name="father_name" value="{{ getEditVal($details, $applicantFallback, $student, 'father_name', 'father_name', 'father_name') }}" class="w-full border-gray-300 rounded-md shadow-sm"></div>
                                        <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Contact</label><input type="text" name="father_contact" value="{{ getEditVal($details, $applicantFallback, $student, 'father_contact', 'father_contact', 'father_contact') }}" class="w-full border-gray-300 rounded-md shadow-sm"></div>
                                    </div>
                                </div>

                                {{-- Mother --}}
                                <div class="mb-4 bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-bold text-sm text-gray-700 mb-3">Mother's Information</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Maiden Name</label><input type="text" name="mother_name" value="{{ old('mother_name', $details->mother_maiden_name ?? ($applicantFallback->mother_name ?? ($student->mother_name ?? ''))) }}" class="w-full border-gray-300 rounded-md shadow-sm"></div>
                                        <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Contact</label><input type="text" name="mother_contact" value="{{ getEditVal($details, $applicantFallback, $student, 'mother_contact', 'mother_contact', 'mother_contact') }}" class="w-full border-gray-300 rounded-md shadow-sm"></div>
                                    </div>
                                </div>

                                {{-- Guardian --}}
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-bold text-sm text-gray-700 mb-3">Guardian Information</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Full Name</label><input type="text" name="guardian_name" value="{{ getEditVal($details, $applicantFallback, $student, 'guardian_name', 'guardian_name', 'guardian_name') }}" class="w-full border-gray-300 rounded-md shadow-sm" required></div>
                                        <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Relationship</label><input type="text" name="guardian_relationship" value="{{ getEditVal($details, $applicantFallback, $student, 'guardian_relationship', 'guardian_relationship', 'guardian_relationship') }}" class="w-full border-gray-300 rounded-md shadow-sm" required></div>
                                        <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Contact Number</label><input type="text" name="guardian_contact" value="{{ getEditVal($details, $applicantFallback, $student, 'guardian_contact', 'guardian_contact', 'guardian_contact') }}" class="w-full border-gray-300 rounded-md shadow-sm" required></div>
                                        <div class="md:col-span-3"><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Address</label><input type="text" name="guardian_address" value="{{ getEditVal($details, $applicantFallback, $student, 'guardian_address', 'guardian_address', 'guardian_address') }}" class="w-full border-gray-300 rounded-md shadow-sm"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- ACADEMIC & SPORTS --}}
                            <div class="md:col-span-2 bg-gray-50 p-6 rounded-lg border border-gray-200">
                                <h3 class="font-bold text-gray-800 mb-4 flex items-center"><i class='bx bx-trophy mr-2 text-yellow-600'></i> Academic & Sports</h3>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                    
                                    {{-- 1. GRADE LEVEL --}}
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Grade Level</label>
                                        <select id="grade_level" name="grade_level" class="w-full border-gray-300 rounded-md shadow-sm">
                                            @foreach(['Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'] as $gl)
                                                <option value="{{ $gl }}" {{ old('grade_level', $student->grade_level) == $gl ? 'selected' : '' }}>{{ $gl }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- 2. SECTION ASSIGNMENT --}}
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Section Assignment</label>
                                        <select id="section_id" name="section_id" class="w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="">-- No Section Yet --</option>
                                        </select>
                                    </div>

                                    {{-- ⚡ FOCUS SPORT ⚡ --}}
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Focus Sport</label>
                                        <select id="sport" name="sport" class="w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="">-- Select Sport --</option>
                                            @php
                                                $currentSport = old('sport', $student->sport ?? ($applicantFallback->sport ?? ($details->sport ?? '')));
                                                $sportsList = ['Aquatics (Swimming)', 'Athletics (Track and Field)', 'Badminton', 'Gymnastics', 'Judo', 'Table Tennis', 'Taekwondo', 'Weightlifting'];
                                            @endphp
                                            @foreach($sportsList as $sportOption)
                                                <option value="{{ $sportOption }}" {{ $currentSport == $sportOption ? 'selected' : '' }}>
                                                    {{ $sportOption }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- ⚡ DYNAMIC CATEGORY / TYPE (Dropdown or Text Input) ⚡ --}}
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Category / Type</label>
                                        @php
                                            $currentCategory = old('sport_specification', $student->sport_specification ?? ($applicantFallback->sport_specification ?? 'None'));
                                        @endphp
                                        
                                        <div id="category_container">
                                            <select class="w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500">
                                                <option value="None">None</option>
                                            </select>
                                        </div>
                                        
                                        {{-- Hidden input holds the actual value --}}
                                        <input type="hidden" id="current_category" value="{{ $currentCategory }}">
                                    </div>

                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Entry Year</label><input type="number" name="entry_year" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ old('entry_year', $student->entry_year) }}"></div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Promotion Status</label>
                                        <select name="promotion_status" class="w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="">-- Select --</option>
                                            @foreach(['Promoted', 'Conditional', 'Retained'] as $status)
                                                 <option value="{{ $status }}" {{ old('promotion_status', $student->promotion_status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- ENROLLMENT LOGIC --}}
                            <div class="md:col-span-2 bg-blue-50 p-4 rounded border border-blue-100">
                                <h3 class="text-lg font-bold text-blue-800 mb-4">Enrollment System Fields</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                                    <div><label class="block text-sm font-medium text-gray-700">Enrollment Date</label><input type="date" name="enrollment_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('enrollment_date', $student->enrollment_date ? \Carbon\Carbon::parse($student->enrollment_date)->format('Y-m-d') : '') }}"></div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">LIS Status</label>
                                        <select name="lis_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="">-- Select --</option>
                                            @foreach(['Enrolled', 'Pending', 'For Follow-up'] as $lis)
                                                <option value="{{ $lis }}" {{ old('lis_status', $student->lis_status) == $lis ? 'selected' : '' }}>{{ $lis }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div><label class="block text-sm font-medium text-gray-700">Remarks</label><textarea name="enrollment_remarks" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('enrollment_remarks', $student->enrollment_remarks) }}</textarea></div>
                            </div>

                        </div> 
                        
                        {{-- 🟢 UPDATED ACTION BUTTONS (Mobile Friendly) --}}
                        <div class="mt-10 flex flex-col-reverse sm:flex-row justify-end gap-3 border-t border-gray-100 pt-6">
                            <a href="{{ route('students.index', $queryParams ?? []) }}" class="w-full sm:w-auto text-center px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200 transition">
                                Cancel
                            </a>
                            <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5 flex items-center justify-center">
                                <i class='bx bx-save mr-2'></i> Update Record
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS (PHOTO PREVIEW & DYNAMIC DROPDOWNS) --}}
    <script>
        // Photo Preview Logic
        function previewImage(event) {
            const reader = new FileReader();
            const output = document.getElementById('photo-preview');
            const placeholder = document.getElementById('placeholder-icon');
            reader.onload = function(){
                output.src = reader.result;
                output.classList.remove('hidden'); 
                placeholder.classList.add('hidden');
            };
            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }

        // DEPENDENT DROPDOWN LOGIC WRAPPER
        function initStudentForm() {
            // --- SECTION DROPDOWN LOGIC ---
            const allSections = @json($sections ?? []); 
            const currentSectionId = @json(old('section_id', $student->section_id ?? ''));
            const gradeSelect = document.getElementById('grade_level');
            const sectionSelect = document.getElementById('section_id');

            function filterSections() {
                if (!gradeSelect || !sectionSelect) return;
                const selectedGradeText = gradeSelect.value;
                const gradeNumber = selectedGradeText.replace(/[^0-9]/g, '');

                sectionSelect.innerHTML = ''; 

                if (gradeNumber) {
                    const filteredSections = allSections.filter(section => {
                        if(!section.grade_level) return false;
                        const sectionGradeNumber = section.grade_level.toString().replace(/[^0-9]/g, '');
                        return sectionGradeNumber === gradeNumber;
                    });

                    if (filteredSections.length > 0) {
                        const defaultOption = document.createElement('option');
                        defaultOption.value = "";
                        defaultOption.text = "-- Select Section --";
                        sectionSelect.appendChild(defaultOption);
                        
                        filteredSections.forEach(section => {
                            const option = document.createElement('option');
                            option.value = section.id;
                            option.text = section.section_name;
                            if (currentSectionId && currentSectionId == section.id) option.selected = true;
                            sectionSelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.value = "";
                        option.text = "-- No Section Found --";
                        sectionSelect.appendChild(option);
                    }
                } else {
                    const option = document.createElement('option');
                    option.value = "";
                    option.text = "-- Select Grade First --";
                    sectionSelect.appendChild(option);
                }
            }

            if (gradeSelect) {
                gradeSelect.removeEventListener('change', filterSections);
                gradeSelect.addEventListener('change', filterSections);
                if(gradeSelect.value) filterSections();
            }

            // --- SPORT CATEGORY DROPDOWN LOGIC ---
            // ⚡ ONLY Taekwondo and Gymnastics get predefined dropdowns ⚡
            const sportDropdowns = {
                'Taekwondo': ['Kyorugi', 'Poomsae'],
                'Gymnastics': ['Artistic', 'Rhythmic']
            };

            // ⚡ Aquatics and Athletics get text input to "Please specify" ⚡
            const specifySports = ['Aquatics (Swimming)', 'Athletics (Track and Field)'];

            const sportSelect = document.getElementById('sport');
            const categoryContainer = document.getElementById('category_container');
            const currentCategoryInput = document.getElementById('current_category');

            function updateCategories() {
                if (!sportSelect || !categoryContainer) return;

                const selectedSport = sportSelect.value;
                const currentCategory = currentCategoryInput ? currentCategoryInput.value : '';
                
                // Clear container
                categoryContainer.innerHTML = '';

                if (sportDropdowns[selectedSport]) {
                    // 1. Create Dropdown for Taekwondo & Gymnastics
                    const select = document.createElement('select');
                    select.name = 'sport_specification';
                    select.className = 'w-full border-gray-300 rounded-md shadow-sm';
                    
                    const defaultOpt = document.createElement('option');
                    defaultOpt.value = "";
                    defaultOpt.text = "-- Select Category --";
                    select.appendChild(defaultOpt);

                    sportDropdowns[selectedSport].forEach(cat => {
                        const opt = document.createElement('option');
                        opt.value = cat;
                        opt.text = cat;
                        if (cat === currentCategory) opt.selected = true;
                        select.appendChild(opt);
                    });
                    
                    categoryContainer.appendChild(select);
                    
                } else if (specifySports.includes(selectedSport)) {
                    // 2. Create Text Input for Aquatics & Athletics
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.name = 'sport_specification';
                    input.className = 'w-full border-gray-300 rounded-md shadow-sm';
                    input.placeholder = 'Please specify...';
                    
                    // Don't put "None" or dropdown values into the text box
                    if (!['None', 'N/A', '-', 'Kyorugi', 'Poomsae', 'Artistic', 'Rhythmic'].includes(currentCategory)) {
                        input.value = currentCategory;
                    }
                    
                    categoryContainer.appendChild(input);

                } else {
                    // 3. For all other sports (Badminton, Judo, Table Tennis, Weightlifting), NO CHOICES
                    const select = document.createElement('select');
                    select.name = 'sport_specification';
                    select.className = 'w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500';
                    select.style.pointerEvents = 'none'; // Make it look disabled
                    
                    const noneOpt = document.createElement('option');
                    noneOpt.value = "None";
                    noneOpt.text = "None";
                    noneOpt.selected = true;
                    select.appendChild(noneOpt);
                    
                    categoryContainer.appendChild(select);
                }
            }

            if (sportSelect) {
                sportSelect.removeEventListener('change', updateCategories);
                sportSelect.addEventListener('change', updateCategories);
                // Trigger on load
                updateCategories();
            }
        }

        // --- EVENT LISTENERS ---
        document.addEventListener('DOMContentLoaded', initStudentForm);
        document.addEventListener('livewire:navigated', initStudentForm);

    </script>
</x-app-layout>