<x-applicant-layout>
    {{-- WRAPPER FOR ALPINE DATA --}}
    <div x-data="applicantForm()" class="max-w-7xl mx-auto py-6 sm:py-10 px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-6 sm:mb-10">
            <img src="{{ asset('images/nas/horizontal.png') }}" class="h-10 sm:h-12 md:h-16 mx-auto mb-3 sm:mb-4 drop-shadow-sm object-contain" alt="NAS Logo">
            <h2 class="text-sm sm:text-base md:text-lg font-extrabold text-gray-700 uppercase tracking-wide leading-tight max-w-2xl mx-auto">
                NAS Annual Search for Competent, Exceptional, Notable, and Talented Student-Athlete Scholars (NASCENT SAS)
            </h2>
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

                    {{-- 1. PERSONAL INFORMATION --}}
                    <div class="mb-8 sm:mb-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-6 flex items-center">
                            <span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">1</span> 
                            PERSONAL INFORMATION
                        </h3>

                        {{-- FLEX CONTAINER: Left (Inputs) | Right (Photo) --}}
                        <div class="flex flex-col-reverse md:flex-row gap-8 items-start">
                            
                            {{-- === LEFT COLUMN: ALL TEXT INPUTS === --}}
                            <div class="flex-1 w-full space-y-6">
                                
                                {{-- LRN --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Learner Reference Number (LRN):</label>
                                    <p class="text-xs text-gray-500 mb-2 italic">(limit input to 12 numeric characters only)</p>
                                    <input type="text" name="lrn" 
                                           class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500 font-mono text-lg tracking-wide" 
                                           required value="{{ old('lrn') }}" 
                                           placeholder="e.g. 123456789012" 
                                           maxlength="12" 
                                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)">
                                </div>

                                {{-- NAME GRID --}}
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Last Name:</label>
                                        <input type="text" name="last_name" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500 uppercase" required value="{{ old('last_name', Auth::user()->last_name) }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">First Name:</label>
                                        <input type="text" name="first_name" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500 uppercase" required value="{{ old('first_name', Auth::user()->first_name) }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Middle Name:</label>
                                        <input type="text" name="middle_name" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500 uppercase" placeholder="Write N/A if not applicable" value="{{ old('middle_name', Auth::user()->middle_name) }}">
                                    </div>
                                </div>

                                {{-- BIRTH & SEX GRID --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Date of Birth:</label>
                                        <input type="date" id="date_of_birth" name="date_of_birth" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('date_of_birth') }}" x-model="dob" @change="calculateAge()">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Age:</label>
                                            <input type="number" id="age" name="age" class="w-full rounded-lg border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed h-11 text-gray-600 font-bold text-center" value="{{ old('age') }}" readonly x-model="age">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Sex:</label>
                                            <select name="gender" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required>
                                                <option value="">Select</option>
                                                <option value="Boy" {{ old('gender') == 'Boy' ? 'selected' : '' }}>Boy</option>
                                                <option value="Girl" {{ old('gender') == 'Girl' ? 'selected' : '' }}>Girl</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Place of Birth *</label>
                                        <input type="text" name="birthplace" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('birthplace') }}">
                                    </div>
                                </div>

                                {{-- RELIGION & EMAIL --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                     <div><label class="block text-sm font-bold text-gray-700 mb-2">Religion</label><input type="text" name="religion" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" value="{{ old('religion') }}"></div>
                                     <div><label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label><input type="email" name="email_address" class="w-full rounded-lg border-gray-300 shadow-sm bg-gray-100 h-11 text-gray-500" required value="{{ Auth::user()->email }}" readonly></div>
                                </div>
                            </div>

                            {{-- === RIGHT COLUMN: PHOTO UPLOAD === --}}
                            <div class="w-full md:w-64 flex-shrink-0 flex flex-col items-center">
                                <label class="block text-sm font-bold text-gray-700 mb-2 text-center">Recent 2X2 Photograph <br><span class="font-normal text-xs text-gray-500">(White Background)</span></label>
                                
                                <div style="width: 200px; height: 200px;" class="bg-white border-4 border-dashed border-indigo-300 flex items-center justify-center text-indigo-400 rounded-lg overflow-hidden relative shadow-sm mb-3">
                                    <div id="preview-text" class="absolute inset-0 flex items-center justify-center pointer-events-none z-0">
                                        <span class="text-xs text-center px-2 font-bold uppercase tracking-wider text-indigo-300">Photo<br>Preview</span>
                                    </div>
                                    <img id="preview" class="absolute inset-0 w-full h-full object-cover hidden z-10 bg-white">
                                </div>

                                <input type="file" name="id_picture" accept="image/*" required 
                                       onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0]); document.getElementById('preview').classList.remove('hidden'); document.getElementById('preview-text').classList.add('hidden');" 
                                       class="block w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer text-center">
                            </div>

                        </div> {{-- End Flex Container --}}
                    </div>

                    {{-- 2. ADDRESS INFORMATION --}}
                    <div class="mb-8 sm:mb-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">2</span> Address Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Region *</label>
                                <select name="region" x-model="selectedRegion" @change="updateProvinces()" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required>
                                    <option value="">Select Region</option>
                                    <template x-for="(provinces, region) in regionsData" :key="region">
                                        <option :value="region" x-text="region" :selected="region == selectedRegion"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Province *</label>
                                <select name="province" x-model="selectedProvince" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required :disabled="!selectedRegion">
                                    <option value="">Select Province</option>
                                    <template x-for="province in availableProvinces" :key="province">
                                        <option :value="province" x-text="province" :selected="province == selectedProvince"></option>
                                    </template>
                                </select>
                            </div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Municipality/City *</label><input type="text" name="municipality_city" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('municipality_city') }}"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">3</span> Academic & Sports</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Last School Attended *</label><input type="text" name="previous_school" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('previous_school') }}"></div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">School Type *</label>
                                <select name="school_type" class="w-full rounded-lg border-gray-300 shadow-sm h-11">
                                    <option value="Public" {{ old('school_type') == 'Public' ? 'selected' : '' }}>Public</option>
                                    <option value="Private" {{ old('school_type') == 'Private' ? 'selected' : '' }}>Private</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Grade Level Applying For *</label>
                                <select name="grade_level_applied" class="w-full rounded-lg border-gray-300 shadow-sm h-11" required>
                                    <option value="">Select</option>
                                    <option value="Grade 7" {{ old('grade_level_applied') == 'Grade 7' ? 'selected' : '' }}>Grade 7</option>
                                    <option value="Grade 8" {{ old('grade_level_applied') == 'Grade 8' ? 'selected' : '' }}>Grade 8</option>
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
                            {{-- PALARONG PAMBANSA --}}
                            <div class="bg-gray-50 p-4 rounded-lg border">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Palarong Pambansa Podium Finisher?</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center space-x-2">
                                        <input type="radio" name="palaro_finisher" value="Yes" class="form-radio text-indigo-600 w-4 h-4" 
                                        {{ old('palaro_finisher') == 'Yes' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-gray-700">Yes</span>
                                    </label>
                                    <label class="flex items-center space-x-2">
                                        <input type="radio" name="palaro_finisher" value="No" class="form-radio text-indigo-600 w-4 h-4"
                                        {{ old('palaro_finisher', 'No') == 'No' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-gray-700">No</span>
                                    </label>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg border">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Batang Pinoy Podium Finisher?</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center space-x-2">
                                        <input type="radio" name="batang_pinoy_finisher" value="Yes" class="form-radio text-indigo-600 w-4 h-4" {{ old('batang_pinoy_finisher') == 'Yes' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-gray-700">Yes</span>
                                    </label>
                                    <label class="flex items-center space-x-2">
                                        <input type="radio" name="batang_pinoy_finisher" value="No" class="form-radio text-indigo-600 w-4 h-4" {{ old('batang_pinoy_finisher', 'No') == 'No' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-gray-700">No</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 4. BACKGROUND INFORMATION --}}
                    <div class="mb-8 sm:mb-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">4</span> Background Information</h3>
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
                                    <label class="flex items-center"><input type="radio" name="attended_campaign" value="Yes" class="mr-2 text-indigo-600" {{ old('attended_campaign') == 'Yes' ? 'checked' : '' }}> Yes</label>
                                    <label class="flex items-center"><input type="radio" name="attended_campaign" value="No" class="mr-2 text-indigo-600" {{ old('attended_campaign', 'No') == 'No' ? 'checked' : '' }}> No</label>
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
                                    <input type="text" name="ip_group_name" x-model="ipGroup" :required="isIP === 'Yes'" placeholder="If yes, specify IP Group" class="w-full rounded-md border-gray-300 shadow-sm h-10 text-sm" :required="isIP === 'Yes'">
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
                                    <input type="text" name="pwd_disability" x-model="pwdType" :required="isPWD === 'Yes'" placeholder="If yes, specify disability" class="w-full rounded-md border-gray-300 shadow-sm h-10 text-sm" :required="isPWD === 'Yes'">
                                </div>
                            </div>

                            {{-- 4Ps --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Beneficiary of 4Ps?</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center"><input type="radio" x-model="is4Ps" name="is_4ps" value="Yes" class="mr-2 text-indigo-600" {{ old('is_4ps') == 'Yes' ? 'checked' : '' }}> Yes</label>
                                    <label class="flex items-center"><input type="radio" x-model="is4Ps" name="is_4ps" value="No" class="mr-2 text-indigo-600" {{ old('is_4ps', 'No') == 'No' ? 'checked' : '' }}> No</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 5. PARENTS' & DESIGNATED GUARDIAN'S INFORMATION --}}
                    <div class="mb-8 sm:mb-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-6 flex items-center">
                            <span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">5</span> 
                            PARENTS' & DESIGNATED GUARDIAN'S INFORMATION
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            {{-- Designated Guardian Name --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Designated Guardian *</label>
                                <input type="text" name="guardian_name" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('guardian_name') }}">
                            </div>
                            
                            {{-- Relationship --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Relationship to the Applicant *</label>
                                <input type="text" name="guardian_relationship" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('guardian_relationship') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Email Address --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                                <input type="email" name="guardian_email" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" value="{{ old('guardian_email') }}">
                            </div>

                            {{-- Contact Number --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Contact Number *</label>
                                <input type="text" 
                                       name="guardian_contact" 
                                       class="w-full rounded-lg border-gray-300 h-11" 
                                       required 
                                       value="{{ old('guardian_contact') }}" 
                                       maxlength="11" 
                                       placeholder="09XXXXXXXXX"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)">
                            </div>
                        </div>
                    </div>

                    {{-- 6. REQUIREMENTS --}}
                    <div class="mb-8 sm:mb-12">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">6</span> Requirements Upload</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mb-4 sm:mb-6 italic bg-yellow-50 p-3 rounded border-l-4 border-yellow-400">
                            <strong>Note:</strong> Please upload clear copies (PDF, JPG, PNG). Max 5MB per file.
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- MANDATORY REQUIREMENTS --}}
                            @foreach([
                                'scholarship_form'  => 'Scholarship Application Form',
                                'student_profile'   => 'Student-Athlete’s Profile Form',
                                'medical_clearance' => 'Preparticipation Physical Evaluation Clearance Form',
                                // 👇 OPTIONAL
                                'coach_reco'        => 'Coach’s Recommendation Form with Coach’s Valid Government-Issued ID with Signature',
                                'adviser_reco'      => 'Adviser’s Recommendation Form with Adviser’s Valid Government-Issued ID with Signature',
                                'birth_cert'        => 'PSA Birth Certificate',
                                'report_card'       => 'Report Card (SF9)',
                                'guardian_id'       => 'Designated Guardian’s Valid Government-Issued ID with Signature'
                            ] as $key => $label)
                                
                                @php 
                                    $isOptional = in_array($key, ['coach_reco', 'adviser_reco']); 
                                @endphp

                                <div class="bg-gray-50 p-4 sm:p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-2">
                                        <label class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-1 sm:mb-0">
                                            {{ $label }} 
                                            @if(!$isOptional) <span class="text-red-600">*</span> @endif
                                        </label>
                                    </div>
                                    <input type="file" name="files[{{ $key }}]" 
                                           class="block w-full text-xs sm:text-sm text-slate-600 file:mr-4 file:py-2 sm:file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer" 
                                           accept=".pdf,.jpg,.jpeg,.png" 
                                           {{-- Only add 'required' if NOT optional --}}
                                           {{ !$isOptional ? 'required' : '' }}>
                                </div>
                            @endforeach

                            {{-- DYNAMIC REQUIREMENTS --}}
                            
                            {{-- Taekwondo: Kukkiwon Certificate --}}
                            <div x-show="selectedSport === 'Taekwondo'" class="bg-gray-50 p-4 sm:p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-2">
                                    <label class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-1 sm:mb-0">
                                        Kukkiwon Certificate <span class="text-red-600">*</span>
                                    </label>
                                </div>
                                <input type="file" name="files[kukkiwon_cert]" 
                                       class="block w-full text-xs sm:text-sm text-slate-600 file:mr-4 file:py-2 sm:file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer" 
                                       accept=".pdf,.jpg,.jpeg,.png" :required="selectedSport === 'Taekwondo'">
                            </div>

                            {{-- IP: Certification --}}
                            <div x-show="isIP === 'Yes'" class="bg-gray-50 p-4 sm:p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-2">
                                    <label class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-1 sm:mb-0">
                                        IP Certification <span class="text-red-600">*</span>
                                    </label>
                                </div>
                                <input type="file" name="files[ip_cert]" 
                                       class="block w-full text-xs sm:text-sm text-slate-600 file:mr-4 file:py-2 sm:file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer" 
                                       accept=".pdf,.jpg,.jpeg,.png" :required="isIP === 'Yes'">
                            </div>

                            {{-- PWD: ID --}}
                            <div x-show="isPWD === 'Yes'" class="bg-gray-50 p-4 sm:p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-2">
                                    <label class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-1 sm:mb-0">
                                        PWD ID <span class="text-red-600">*</span>
                                    </label>
                                </div>
                                <input type="file" name="files[pwd_id]" 
                                       class="block w-full text-xs sm:text-sm text-slate-600 file:mr-4 file:py-2 sm:file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer" 
                                       accept=".pdf,.jpg,.jpeg,.png" :required="isPWD === 'Yes'">
                            </div>

                            {{-- 4Ps: ID or Certification --}}
                            <div x-show="is4Ps === 'Yes'" class="bg-gray-50 p-4 sm:p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-2">
                                    <label class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-1 sm:mb-0">
                                        4Ps ID or Certification <span class="text-red-600">*</span>
                                    </label>
                                </div>
                                <input type="file" name="files[4ps_id]" 
                                       class="block w-full text-xs sm:text-sm text-slate-600 file:mr-4 file:py-2 sm:file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer" 
                                       accept=".pdf,.jpg,.jpeg,.png" :required="is4Ps === 'Yes'">
                            </div>

                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-center pb-4 sm:pb-8 pt-4 gap-4">
                        <button type="button" @click="if(document.getElementById('applicantForm').reportValidity()) { showPrivacyModal = true }" class="w-full sm:w-auto bg-indigo-700 hover:bg-indigo-800 text-white px-6 sm:px-10 py-3 rounded-lg font-bold text-base sm:text-lg shadow-md transition transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-indigo-300">REVIEW & SUBMIT APPLICATION</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL --}}
        <div x-show="showPrivacyModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showPrivacyModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full border-t-8 border-indigo-700">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-extrabold text-gray-900 mb-4">Data Privacy Consent</h3>
                                <div class="mt-2 h-80 overflow-y-auto text-xs sm:text-sm text-gray-700 bg-gray-50 p-6 rounded-md border border-gray-200 text-justify space-y-4 leading-relaxed">
                                    <p>
                                        <strong>I/We certify that the above information is true, complete and correct.</strong> I/We understand that any false or misleading information shall render my/our child ineligible for admission or may be subject for dismissal. If admitted, I/We agreed to abide by the policies, rules and regulations of the National Academy of Sports.
                                        <br><span class="italic text-gray-500 font-normal">(Ako/Kami ay nagpapatunay na ang lahat ng impormasyong nakasaad sa itaas ay totoo, kumpleto, at wasto. Nauunawaan ko/namin na ang anumang maling o mapanlinlang na impormasyon ay magiging dahilan upang hindi tanggapin ang aking/aming anak sa pagpasok o maaaring maging batayan ng kanyang pagkakadismiss. Kung siya ay tatanggapin, ako/kami ay sumasang-ayon na sumunod sa mga polisiya, alituntunin, at regulasyon ng National Academy of Sports.)</span>
                                    </p>

                                    <p>
                                        For and in behalf of our minor child, I/We declare and confirm that, of my/our our volition, submit and will continue to submit, necessary information and documents to the National Academy of Sports (“NAS”), with the intention of applying, if qualified, enroll my/our child for the upcoming school year. In this regard, I/We acknowledge and understand that NAS requires our and our child’s personal and/or sensitive information (collectively “information”), for legitimate and lawful purposes, including but limited to verifying our identity, evaluating academic qualifications and eligibility, assessing physical fitness, and facilitating official communication with us.
                                        <br><span class="italic text-gray-500 font-normal">(Para at sa ngalan ng aming menor de edad na anak, ako/kami ay nagpahayag at nagpapatibay na sa aming sariling kagustuhan ay nagsusumite at patuloy na magsusumite ng kinakailangang impormasyon at mga dokumento sa National Academy of Sports (“NAS”), na may layuning makapag-apply at, kung kwalipikado, ma-enrol ang aming anak para sa darating na taong panuruan. Kaugnay nito, aking/aming kinikilala at nauunawaan na ang NAS ay nangangailangan ng aming at ng aming anak na personal at/o sensitibong impormasyon (sama-samang tinutukoy bilang “impormasyon”), para sa lehitimo at makatarungang layunin, kabilang ngunit hindi limitado sa pagberipika ng aming pagkakakilanlan, pagsusuri ng akademikong kwalipikasyon at eligibility, pagtaya ng pisikal na kakayahan, at pagpapadali ng opisyal na komunikasyon sa amin.)</span>
                                    </p>

                                    <p>
                                        <strong>We acknowledge and agree that:</strong> <span class="italic text-gray-500 font-normal">(Amin pong kinikilala at sinasang-ayunan na:)</span>
                                    </p>
                                    <ol class="list-decimal ml-6 space-y-2">
                                        <li>
                                            NAS may collect, record, use, process, store, and transmit our information in accordance with the Data Privacy Act of 2012, its implementing Rules and Regulations (IRR), and other applicable laws.
                                            <br><span class="italic text-gray-500 font-normal">(Maaaring mangalap, magtala, gumamit, magproseso, mag-imbak, at magpadala ng aming impormasyon ang NAS alinsunod sa Data Privacy Act of 2012, ang mga kaukulang Implementing Rules and Regulations (IRR), at iba pang naaangkop na batas.)</span>
                                        </li>
                                        <li>
                                            NAS may disclose our information only with our consent, or when required or authorized under relevant laws, rules, and regulations.
                                            <br><span class="italic text-gray-500 font-normal">(Maaaring ibunyag ng NAS ang aming impormasyon lamang sa aming pahintulot, o kung ito ay kinakailangan o pinahihintulutan sa ilalim ng mga naaangkop na batas, alituntunin, at regulasyon.)</span>
                                        </li>
                                        <li>
                                            NAS shall adopt appropriate organizational, physical, and technical measurement to ensure the confidentiality, integrity, and availability of our information.
                                            <br><span class="italic text-gray-500 font-normal">(Magsasagawa ang NAS ng angkop na mga hakbang na pang-organisasyonal, pisikal, at teknikal upang matiyak ang pagiging kumpidensyal, integridad, at pagkakaroon ng aming impormasyon.)</span>
                                        </li>
                                        <li>
                                            NAS may retain our information only for as long as necessary to fulfill the purposes stated herein, or as required by applicable laws and regulations.
                                            <br><span class="italic text-gray-500 font-normal">(Maaaring panatilihin ng NAS ang aming impormasyon hangga’t kinakailangan upang maisakatuparan ang mga layuning nakasaad dito, o ayon sa hinihingi ng mga umiiral na batas at regulasyon.)</span>
                                        </li>
                                    </ol>

                                    <p>
                                        <strong>We also understand that as data subjects under the Data Privacy Act of 2012, we have right to:</strong> <span class="italic text-gray-500 font-normal">(Nauunawaan din namin na bilang mga data subject sa ilalim ng Data Privacy Act of 2012, kami ay may karapatang:)</span>
                                    </p>
                                    <ul class="list-[lower-alpha] ml-6 space-y-2">
                                        <li>
                                            Inquire about, request access to, review or obtain a copy of our information in the custody of NAS.
                                            <br><span class="italic text-gray-500 font-normal">(Magtanong tungkol sa, humiling ng access, suriin, o kumuha ng kopya ng aming impormasyon na nasa pangangalaga ng NAS.)</span>
                                        </li>
                                        <li>
                                            Request correction or updating our information;
                                            <br><span class="italic text-gray-500 font-normal">(Humiling ng pagwawasto o pag-update ng aming impormasyon;)</span>
                                        </li>
                                        <li>
                                            Withdraw or withhold consent, object to processing or request deletion of our information subject to limitations where NAS has a legal obligation or legitimate purpose to retain such information.
                                            <br><span class="italic text-gray-500 font-normal">(Bawiin o ipagkait ang pahintulot, tutulan ang pagproseso, o humiling ng pagbura ng aming impormasyon, sang-ayon sa mga limitasyon kung saan ang NAS ay may legal na obligasyon o lehitimong layunin na panatilihin ang nasabing impormasyon.)</span>
                                        </li>
                                    </ul>

                                    <p>
                                        I/We understand that refusal to provide the required information, or subsequent withdrawal of consent, may prevent NAS from processing our application and carrying out the purpose described in this document, we may contact NASCENT SAS secretariat at <a href="mailto:nascentsas@deped.gov.ph" class="text-blue-600 hover:underline font-bold">nascentsas@deped.gov.ph</a>.
                                        <br><span class="italic text-gray-500 font-normal">(Aking/aming nauunawaan na ang pagtanggi na magbigay ng hinihinging impormasyon, o ang pagbawi ng pahintulot pagkatapos, ay maaaring pumigil sa NAS na iproseso ang aming aplikasyon at isakatuparan ang layunin na nakasaad sa dokumentong ito. Maaari kaming makipag-ugnayan sa NASCENT SAS Secretariat sa nascentsas@deped.gov.ph.)</span>
                                    </p>

                                    <p>
                                        <strong>By signing below, I/We declared that we read, understand, and voluntarily consent to the collection, recording, use, processing, storage, disclosure, and transmission of our child’s information, including photographs, videos, storage, data or documents, submitted to NAS in accordance with the Data Privacy Act of 2012 and applicable regulations.</strong>
                                        <br><span class="italic text-gray-500 font-normal">(Sa pamamagitan ng pagpirma sa ibaba, ako/kami ay nagpapatunay na aking/aming nabasa, naunawaan, at kusang-loob na sumasang-ayon sa pangangalap, pagtatala, paggamit, pagproseso, pag-iimbak, pagbubunyag, at pagpapadala ng impormasyon ng aming anak, kabilang ang mga litrato, bidyo, datos o dokumento na isinumite sa NAS, alinsunod sa Data Privacy Act of 2012 at mga naaangkop na regulasyon.)</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse flex-col gap-2">
                        <button type="button" @click="isSubmitting = true; document.getElementById('applicantForm').submit();" :disabled="isSubmitting" class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-700 text-base font-medium text-white hover:bg-indigo-800 sm:ml-3">
                            <span x-show="!isSubmitting">I AGREE & SUBMIT APPLICATION</span>
                            <span x-show="isSubmitting">Processing...</span>
                        </button>
                        <button type="button" @click="showPrivacyModal = false" class="mt-2 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
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
                
                // Initialize variables with existing data
                dob: @json(old('date_of_birth')),
                age: @json(old('age')),
                selectedSport: @json(old('sport')),
                sportSpec: @json(old('sport_specification')), 
                referralSource: @json(old('learn_about_nas')), 
                referrerName: @json(old('referrer_name')),
                
                isIP: @json(old('is_ip', 'No')),
                ipGroup: @json(old('ip_group_name')),
                
                isPWD: @json(old('is_pwd', 'No')),
                pwdType: @json(old('pwd_disability')),

                is4Ps: @json(old('is_4ps', 'No')), // Added for 4Ps logic
                
                // Region & Province Data
                selectedRegion: @json(old('region')),
                selectedProvince: @json(old('province')),
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

                init() {
                    // Populate provinces on load based on saved region
                    if (this.selectedRegion && this.regionsData[this.selectedRegion]) {
                        this.availableProvinces = this.regionsData[this.selectedRegion];
                    }
                    
                    // Re-calculate age if dob exists but age is empty
                    if (this.dob && !this.age) {
                        this.calculateAge();
                    }
                },

                updateProvinces() {
                    this.availableProvinces = this.regionsData[this.selectedRegion] || [];
                    this.selectedProvince = ''; // Reset province selection ONLY when changing region
                },

                calculateAge() {
                    if (this.dob) {
                        let today = new Date();
                        let birthDate = new Date(this.dob);
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