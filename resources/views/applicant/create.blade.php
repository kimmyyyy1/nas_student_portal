<x-applicant-layout>
    {{-- WRAPPER FOR MODAL STATE & ALPINE DATA --}}
    <div x-data="applicantForm()" class="max-w-7xl mx-auto py-6 sm:py-10 px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-6 sm:mb-10">
            <img src="{{ asset('images/nas/horizontal.png') }}" class="h-10 sm:h-12 md:h-16 mx-auto mb-3 sm:mb-4 drop-shadow-sm object-contain" alt="NAS Logo">
            <p class="text-xs sm:text-sm text-gray-500 mt-1 uppercase tracking-widest font-bold">Based on SAIS Guidelines</p>
        </div>

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-200">
            <div class="h-2 bg-indigo-700 w-full"></div>

            <div class="p-6 sm:p-8 md:p-12 text-gray-900">
                
                @if ($errors->any())
                    <div class="mb-6 sm:mb-8 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-md text-sm shadow-sm">
                        <p class="font-bold mb-2">Please check required fields:</p>
                        <ul class="list-disc list-inside ml-1">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form id="applicantForm" method="POST" action="{{ route('applicant.store') }}" enctype="multipart/form-data">
                    @csrf 

                    {{-- ID PICTURE UPLOAD --}}
                    <div class="mb-8 sm:mb-10 bg-indigo-50 p-6 sm:p-8 rounded-xl border border-indigo-100 flex flex-col md:flex-row items-center gap-6 sm:gap-8">
                        <div class="flex-shrink-0">
                            <div style="width: 150px; height: 150px; sm:width: 200px; sm:height: 200px;" class="w-40 h-40 sm:w-52 sm:h-52 bg-white border-4 border-dashed border-indigo-300 flex items-center justify-center text-indigo-400 rounded-lg overflow-hidden relative shadow-sm mx-auto">
                                <div id="preview-text" class="absolute inset-0 flex items-center justify-center pointer-events-none z-0">
                                    <span class="text-xs text-center px-2 font-bold uppercase tracking-wider">2x2 Photo<br>Preview</span>
                                </div>
                                <img id="preview" class="absolute inset-0 w-full h-full object-cover hidden z-10 bg-white">
                            </div>
                        </div>
                        <div class="flex-1 w-full text-center md:text-left">
                            <h3 class="text-lg sm:text-xl font-bold text-indigo-900 mb-2">Upload ID Picture *</h3>
                            <p class="text-xs sm:text-sm text-indigo-700 mb-4">Requirement: 2x2 size, formal attire, white background. (Max 5MB)</p>
                            <input type="file" name="id_picture" accept="image/*" required onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0]); document.getElementById('preview').classList.remove('hidden'); document.getElementById('preview-text').classList.add('hidden');" 
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 sm:file:py-3 file:px-4 sm:file:px-6 file:rounded-full file:border-0 file:text-xs sm:file:text-sm file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer transition mx-auto md:mx-0 shadow-sm border border-gray-300 rounded-md bg-white">
                        </div>
                    </div>

                    {{-- 1. APPLICANT INFORMATION --}}
                    <div class="mb-8 sm:mb-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-4 sm:mb-6 flex items-center">
                            <span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">1</span> Applicant Information
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 sm:gap-6 mb-4 sm:mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">LRN (Learner Reference No.) *</label>
                                <input type="text" name="lrn" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('lrn') }}" placeholder="12-digit LRN" maxlength="12" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Last Name *</label>
                                <input type="text" name="last_name" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('last_name', Auth::user()->last_name ?? '') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">First Name *</label>
                                <input type="text" name="first_name" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('first_name', Auth::user()->first_name ?? '') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Middle Name (Optional)</label>
                                <input type="text" name="middle_name" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('middle_name', Auth::user()->middle_name ?? '') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 mb-4 sm:mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Birthday *</label>
                                <input type="date" id="date_of_birth" name="date_of_birth" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('date_of_birth') }}" @change="calculateAge()">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Age</label>
                                <input type="number" id="age" name="age" class="w-full rounded-lg border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed h-11 text-gray-600 font-bold" value="{{ old('age') }}" readonly x-model="age">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Sex *</label>
                                <select name="gender" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required>
                                    <option value="">Select</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Place of Birth *</label><input type="text" name="birthplace" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('birthplace') }}"></div>
                        </div>

                         <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                             <div><label class="block text-sm font-bold text-gray-700 mb-2">Religion</label><input type="text" name="religion" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" value="{{ old('religion') }}"></div>
                             <div><label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label><input type="email" name="email_address" class="w-full rounded-lg border-gray-300 shadow-sm bg-gray-100 h-11 text-gray-500" required value="{{ Auth::user()->email ?? '' }}" readonly></div>
                         </div>
                    </div>

                    {{-- 2. ADDRESS INFORMATION --}}
                    <div class="mb-8 sm:mb-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-4 sm:mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">2</span> Address Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">
                            {{-- Region Dropdown --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Region *</label>
                                <select name="region" x-model="selectedRegion" @change="updateProvinces()" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required>
                                    <option value="">Select Region</option>
                                    <template x-for="(provinces, region) in regionsData" :key="region">
                                        <option :value="region" x-text="region"></option>
                                    </template>
                                </select>
                            </div>

                            {{-- Province Dropdown (Filtered) --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Province *</label>
                                <select name="province" x-model="selectedProvince" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required :disabled="!selectedRegion">
                                    <option value="">Select Province</option>
                                    <template x-for="province in availableProvinces" :key="province">
                                        <option :value="province" x-text="province"></option>
                                    </template>
                                </select>
                            </div>

                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Municipality/City *</label><input type="text" name="municipality_city" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('municipality_city') }}"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Barangay *</label><input type="text" name="barangay" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('barangay') }}"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Street / House No.</label><input type="text" name="street_address" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" value="{{ old('street_address') }}" required></div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Zip Code</label>
                                <input type="text" name="zip_code" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" value="{{ old('zip_code') }}" required maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4)">
                            </div>
                        </div>
                    </div>

                    {{-- 3. ACADEMIC & SPORTS --}}
                    <div class="mb-8 sm:mb-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-4 sm:mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">3</span> Academic & Sports</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Last School Attended *</label><input type="text" name="previous_school" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('previous_school') }}"></div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">School Type *</label>
                                <select name="school_type" class="w-full rounded-lg border-gray-300 shadow-sm h-11">
                                    <option value="Public">Public</option>
                                    <option value="Private">Private</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Grade Level Applying For *</label>
                                <select name="grade_level_applied" class="w-full rounded-lg border-gray-300 shadow-sm h-11" required>
                                    <option value="">Select</option>
                                    <option value="Grade 7">Grade 7</option>
                                    <option value="Grade 8">Grade 8</option>
                                </select>
                            </div>
                        </div>

                        {{-- FOCUS SPORTS --}}
                        <div class="bg-indigo-50 p-6 rounded-lg mb-6 border border-indigo-100">
                            <label class="block text-sm font-bold text-indigo-900 mb-3 uppercase tracking-wide">Focus Sports *</label>
                            <select name="sport" x-model="selectedSport" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 mb-4" required>
                                <option value="">-- Select Sport --</option>
                                <option value="Aquatics">Aquatics (Swimming)</option>
                                <option value="Athletics">Athletics (Track and Field)</option>
                                <option value="Badminton">Badminton</option>
                                <option value="Gymnastics">Gymnastics</option>
                                <option value="Judo">Judo</option>
                                <option value="Table Tennis">Table Tennis</option>
                                <option value="Taekwondo">Taekwondo</option>
                                <option value="Weightlifting">Weightlifting</option>
                            </select>

                            {{-- Conditional Sport Inputs --}}
                            <div x-show="selectedSport === 'Aquatics'" class="mt-2">
                                <label class="block text-xs font-bold text-gray-600 mb-1">Please specify Aquatics event:</label>
                                <input type="text" name="sport_specification" x-model="sportSpec" class="w-full rounded-md border-gray-300 shadow-sm h-10 text-sm">
                            </div>
                            <div x-show="selectedSport === 'Athletics'" class="mt-2">
                                <label class="block text-xs font-bold text-gray-600 mb-1">Please specify Athletics event:</label>
                                <input type="text" name="sport_specification" x-model="sportSpec" class="w-full rounded-md border-gray-300 shadow-sm h-10 text-sm">
                            </div>
                            <div x-show="selectedSport === 'Taekwondo'" class="mt-2">
                                <label class="block text-xs font-bold text-gray-600 mb-1">Category:</label>
                                <select name="sport_specification" x-model="sportSpec" class="w-full rounded-md border-gray-300 shadow-sm h-10 text-sm">
                                    <option value="Poomsae">Poomsae</option>
                                    <option value="Kyorugi">Kyorugi</option>
                                </select>
                            </div>
                            <div x-show="selectedSport === 'Gymnastics'" class="mt-2">
                                <label class="block text-xs font-bold text-gray-600 mb-1">Category:</label>
                                <select name="sport_specification" x-model="sportSpec" class="w-full rounded-md border-gray-300 shadow-sm h-10 text-sm">
                                    <option value="Artistic">Artistic</option>
                                    <option value="Rhythmic">Rhythmic</option>
                                </select>
                            </div>
                        </div>

                        {{-- Achievements --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 p-4 rounded-lg border">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Palarong Pambansa Podium Finisher?</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center"><input type="radio" name="palaro_finisher" value="Yes" class="mr-2 text-indigo-600"> Yes</label>
                                    <label class="flex items-center"><input type="radio" name="palaro_finisher" value="No" class="mr-2 text-indigo-600" checked> No</label>
                                </div>
                                <div class="mt-2" x-data="{ show: false }" x-show="document.querySelector('input[name=palaro_finisher]:checked')?.value === 'Yes'" @change="show = $event.target.value === 'Yes'">
                                    <input type="text" name="palaro_year" placeholder="Year Participated" class="w-full rounded-md border-gray-300 shadow-sm h-10 text-sm">
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg border">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Batang Pinoy Podium Finisher?</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center"><input type="radio" name="batang_pinoy_finisher" value="Yes" class="mr-2 text-indigo-600"> Yes</label>
                                    <label class="flex items-center"><input type="radio" name="batang_pinoy_finisher" value="No" class="mr-2 text-indigo-600" checked> No</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 4. ADDITIONAL INFORMATION --}}
                    <div class="mb-8 sm:mb-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-4 sm:mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">4</span> Background Information</h3>
                        
                        <div class="grid grid-cols-1 gap-6 mb-6">
                            {{-- Learn about NAS --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Where did you learn about NAS?</label>
                                <select x-model="referralSource" name="learn_about_nas" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500">
                                    <option value="">Select</option>
                                    <option value="NASCENT SAS Facebook Page">NASCENT SAS Facebook Page</option>
                                    <option value="NAS Social Media Page">NAS Social Media Page</option>
                                    <option value="NAS Personnel / Student-Athlete Referral">NAS Personnel / Student-Athlete Referral</option>
                                    <option value="National Sports Association / Coach">National Sports Association / Coach</option>
                                    <option value="News">News</option>
                                    <option value="Local Government Unit">Local Government Unit</option>
                                    <option value="School">School</option>
                                </select>
                            </div>
                            
                            {{-- Conditional Referral Name --}}
                            <div x-show="referralSource === 'NAS Personnel / Student-Athlete Referral'" class="bg-yellow-50 p-4 rounded-md border border-yellow-200">
                                <label class="block text-sm font-bold text-yellow-800 mb-1">If referred, write the name (One name only):</label>
                                <input type="text" name="referrer_name" x-model="referrerName" class="w-full rounded-md border-gray-300 shadow-sm h-10">
                            </div>

                            {{-- Articulation Campaign --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Have you attended any of our articulation campaign or visited an information booth?</label>
                                <div class="flex space-x-6">
                                    <label class="flex items-center"><input type="radio" name="attended_campaign" value="Yes" class="mr-2 text-indigo-600"> Yes</label>
                                    <label class="flex items-center"><input type="radio" name="attended_campaign" value="No" class="mr-2 text-indigo-600" checked> No</label>
                                </div>
                            </div>
                        </div>

                        {{-- Special Groups --}}
                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 space-y-4">
                            {{-- IP --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Member of Indigenous Group?</label>
                                <div class="flex space-x-4 mb-2">
                                    <label class="flex items-center"><input type="radio" x-model="isIP" name="is_ip" value="Yes" class="mr-2 text-indigo-600"> Yes</label>
                                    <label class="flex items-center"><input type="radio" x-model="isIP" name="is_ip" value="No" class="mr-2 text-indigo-600"> No</label>
                                </div>
                                <div x-show="isIP === 'Yes'">
                                    <input type="text" name="ip_group_name" x-model="ipGroup" placeholder="If yes, specify IP Group" class="w-full rounded-md border-gray-300 shadow-sm h-10 text-sm">
                                </div>
                            </div>

                            {{-- PWD --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Person with Disability?</label>
                                <div class="flex space-x-4 mb-2">
                                    <label class="flex items-center"><input type="radio" x-model="isPWD" name="is_pwd" value="Yes" class="mr-2 text-indigo-600"> Yes</label>
                                    <label class="flex items-center"><input type="radio" x-model="isPWD" name="is_pwd" value="No" class="mr-2 text-indigo-600"> No</label>
                                </div>
                                <div x-show="isPWD === 'Yes'">
                                    <input type="text" name="pwd_disability" x-model="pwdType" placeholder="If yes, specify disability" class="w-full rounded-md border-gray-300 shadow-sm h-10 text-sm">
                                </div>
                            </div>

                            {{-- 4Ps --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Beneficiary of 4Ps?</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center"><input type="radio" name="is_4ps" value="Yes" class="mr-2 text-indigo-600"> Yes</label>
                                    <label class="flex items-center"><input type="radio" name="is_4ps" value="No" class="mr-2 text-indigo-600" checked> No</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 5. DESIGNATED GUARDIAN --}}
                    <div class="mb-8 sm:mb-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-4 sm:mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">5</span> Designated Guardian</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Guardian Name *</label><input type="text" name="guardian_name" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('guardian_name') }}"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Relationship *</label><input type="text" name="guardian_relationship" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('guardian_relationship') }}"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Contact Number *</label><input type="text" name="guardian_contact" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('guardian_contact') }}"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label><input type="email" name="guardian_email" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" value="{{ old('guardian_email') }}"></div>
                        </div>
                    </div>

                    {{-- 6. REQUIREMENTS --}}
                    <div class="mb-8 sm:mb-12">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-4 sm:mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">6</span> Requirements Upload</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mb-4 sm:mb-6 italic bg-yellow-50 p-3 rounded border-l-4 border-yellow-400">Please upload clear copies (PDF, JPG, PNG). Max 5MB per file.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            @foreach([
                                'scholarship_form'  => 'Scholarship Application Form',
                                'student_profile'   => 'Student-Athlete’s Profile Form',
                                'medical_clearance' => 'Preparticipation Physical Evaluation Clearance Form',
                                'coach_reco'        => 'Coach’s Recommendation Form (w/ Valid ID)',
                                'adviser_reco'      => 'Adviser’s Recommendation Form (w/ Valid ID)',
                                'birth_cert'        => 'PSA Birth Certificate',
                                'report_card'       => 'Report Cards (Gr 5/6 or 6/7)',
                                'guardian_id'       => 'Guardian’s Valid ID w/ Signature'
                            ] as $key => $label)
                                
                                <div class="bg-gray-50 p-4 sm:p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition">
                                    <label class="text-sm font-bold text-gray-800 mb-3 block uppercase tracking-wide">
                                        {{ $label }} <span class="text-red-600">*</span>
                                    </label>
                                    <input type="file" name="files[{{ $key }}]" 
                                           class="block w-full text-xs sm:text-sm text-slate-600 file:mr-4 file:py-2 sm:file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer" 
                                           accept=".pdf,.jpg,.jpeg,.png"
                                           required>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- SUBMIT BUTTON --}}
                    <div class="flex justify-center pb-4 sm:pb-8 pt-4">
                        <button type="button" 
                                @click="if(document.getElementById('applicantForm').reportValidity()) { showPrivacyModal = true }" 
                                class="w-full sm:w-auto bg-indigo-700 hover:bg-indigo-800 text-white px-6 sm:px-10 py-3 rounded-lg font-bold text-base sm:text-lg shadow-md transition transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-indigo-300 uppercase tracking-wide">
                            REVIEW & SUBMIT APPLICATION
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- PRIVACY MODAL --}}
        <div x-show="showPrivacyModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showPrivacyModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showPrivacyModal" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full border-t-8 border-indigo-700">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg sm:text-xl leading-6 font-extrabold text-gray-900" id="modal-title">Data Privacy Consent</h3>
                                    <i class='bx bx-shield-quarter text-2xl sm:text-3xl text-indigo-600'></i>
                                </div>
                                <div class="mt-2 h-48 sm:h-64 overflow-y-auto text-xs sm:text-sm text-gray-600 bg-gray-50 p-3 sm:p-4 rounded border border-gray-200 text-justify">
                                    <p class="mb-3 font-bold">Please read carefully:</p>
                                    <p class="mb-3">In compliance with the <strong>Data Privacy Act of 2012 (RA 10173)</strong>, the National Academy of Sports (NAS) is committed to protecting your personal data.</p>
                                    <p class="mb-3">By submitting this application form, you acknowledge and agree that:</p>
                                    <ul class="list-disc ml-5 mb-3 space-y-2">
                                        <li><strong>Collection:</strong> NAS collects your personal information solely for admission/scholarship purposes.</li>
                                        <li><strong>Use:</strong> Your data will be used by authorized NAS personnel.</li>
                                        <li><strong>Protection:</strong> NAS implements security measures to protect your data.</li>
                                    </ul>
                                    <p>You attest that all information provided is true and correct.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse flex-col gap-2 sm:gap-0">
                        <button type="button" @click="isSubmitting = true; document.getElementById('applicantForm').submit();" :disabled="isSubmitting" class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-700 text-sm sm:text-base font-medium text-white hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!isSubmitting">I AGREE & SUBMIT APPLICATION</span>
                            <span x-show="isSubmitting">Processing...</span>
                        </button>
                        <button type="button" @click="showPrivacyModal = false" class="mt-2 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm sm:text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ALPINE.JS LOGIC --}}
    <script>
        function applicantForm() {
            return {
                showPrivacyModal: false,
                isSubmitting: false,
                age: '',
                selectedSport: '',
                referralSource: '',
                isIP: 'No',
                isPWD: 'No',
                
                // Region & Province Data
                selectedRegion: '',
                selectedProvince: '',
                availableProvinces: [],
                regionsData: {
                    'Cordillera Administrative Region': ['Abra', 'Apayao', 'Benguet', 'Ifugao', 'Kalinga', 'Mountain Province'],
                    'Region 1: Ilocos Region': ['Ilocos Norte', 'Ilocos Sur', 'La Union', 'Pangasinan'],
                    'Region 2: Cagayan Valley': ['Batanes', 'Cagayan', 'Isabela', 'Nueva Vizcaya', 'Quirino'],
                    'Region 3: Central Luzon': ['Aurora', 'Bataan', 'Bulacan', 'Nueva Ecija', 'Pampanga', 'Tarlac', 'Zambales'],
                    'Region IV-A: CALABARZON': ['Batangas', 'Cavite', 'Laguna', 'Quezon', 'Rizal'],
                    'Region IV-B: MIMAROPA': ['Marinduque', 'Occidental Mindoro', 'Oriental Mindoro', 'Palawan', 'Romblon'],
                    'Region 5: Bicol Region': ['Albay', 'Camarines Norte', 'Camarines Sur', 'Catanduanes', 'Masbate', 'Sorsogon'],
                    'National Capital Region': ['Metro Manila'],
                    'Region 6: Western Visayas': ['Aklan', 'Antique', 'Capiz', 'Guimaras', 'Iloilo', 'Negros Occidental'],
                    'Region 7: Central Visayas': ['Bohol', 'Cebu', 'Siquijor'],
                    'Region 8: Eastern Visayas': ['Biliran', 'Eastern Samar', 'Leyte', 'Northern Samar', 'Samar', 'Southern Leyte'],
                    'Negros Island Region': ['Negros Occidental', 'Negros Oriental', 'Siquijor'],
                    'Region 9: Zamboanga Peninsula': ['Zamboanga Del Norte', 'Zamboanga del Sur', 'Zamboanga Sibugay'],
                    'Region 10: Northern Mindanao': ['Bukidnon', 'Camiguin', 'Lanao Del Norte', 'Misamis Occidental', 'Misamis Oriental'],
                    'Region 11: Davao Region': ['Compostela Valley', 'Davao del Norte', 'Davao del Sur', 'Davao Occidental', 'Davao Oriental'],
                    'Region 12: SOCCSKSARGEN': ['Cotabato', 'Sarangani', 'South Cotabato', 'Sultan Kudarat'],
                    'Region 13: CARAGA': ['Agusan del Norte', 'Agusan del Sur', 'Dinagat Islands', 'Surigao del Norte', 'Surigao del Sur'],
                    'BARMM': ['Basilan', 'Lanao del Sur', 'Maguindanao', 'Sulu', 'Tawi-Tawi']
                },

                updateProvinces() {
                    this.availableProvinces = this.regionsData[this.selectedRegion] || [];
                    this.selectedProvince = ''; // Reset province selection
                },

                calculateAge() {
                    // Get the date string from the input (controlled by Alpine logic now)
                    let dob = document.getElementById('date_of_birth').value;

                    if (dob) {
                        let today = new Date();
                        let birthDate = new Date(dob);
                        let age = today.getFullYear() - birthDate.getFullYear();
                        let m = today.getMonth() - birthDate.getMonth();
                        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                            age--;
                        }
                        this.age = age;
                    }
                }
            }
        }
    </script>
</x-applicant-layout>