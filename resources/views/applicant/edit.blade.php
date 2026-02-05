<x-applicant-layout>
    <div x-data="applicantForm()" class="max-w-7xl mx-auto py-6 sm:py-10 px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-6 sm:mb-10">
            <img src="{{ asset('images/nas/horizontal.png') }}" class="h-10 sm:h-12 md:h-16 mx-auto mb-3 sm:mb-4 drop-shadow-sm object-contain" alt="NAS Logo">
            <h2 class="text-sm sm:text-base md:text-lg font-extrabold text-gray-700 uppercase tracking-wide leading-tight max-w-2xl mx-auto">
                Edit Application: NASCENT SAS
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

                <form id="applicantForm" method="POST" action="{{ route('applicant.update') }}" enctype="multipart/form-data">
                    @csrf 
                    @method('PATCH')

                    {{-- 1. PERSONAL INFORMATION (Bio-data Layout) --}}
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
                                           class="w-full rounded-lg border-gray-300 shadow-sm h-11 bg-gray-100 text-gray-600 cursor-not-allowed focus:border-indigo-500 focus:ring-indigo-500 font-mono text-lg tracking-wide" 
                                           required value="{{ old('lrn', $application->lrn) }}" 
                                           readonly>
                                </div>

                                {{-- NAME GRID --}}
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Last Name:</label>
                                        <input type="text" name="last_name" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500 uppercase" required value="{{ old('last_name', $application->last_name) }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">First Name:</label>
                                        <input type="text" name="first_name" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500 uppercase" required value="{{ old('first_name', $application->first_name) }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Middle Name:</label>
                                        <input type="text" name="middle_name" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500 uppercase" placeholder="Write N/A if not applicable" value="{{ old('middle_name', $application->middle_name) }}">
                                    </div>
                                </div>

                                {{-- BIRTH & SEX GRID --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Date of Birth:</label>
                                        <input type="date" id="date_of_birth" name="date_of_birth" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('date_of_birth', \Carbon\Carbon::parse($application->date_of_birth)->format('Y-m-d')) }}" x-model="dob" @change="calculateAge()">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Age:</label>
                                            <input type="number" id="age" name="age" class="w-full rounded-lg border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed h-11 text-gray-600 font-bold text-center" value="{{ old('age', $application->age) }}" readonly x-model="age">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Sex:</label>
                                            <select name="gender" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required>
                                                <option value="Boy" {{ old('gender', $application->gender) == 'Boy' ? 'selected' : '' }}>Boy</option>
                                                <option value="Girl" {{ old('gender', $application->gender) == 'Girl' ? 'selected' : '' }}>Girl</option>
                                                <option value="Male" {{ old('gender', $application->gender) == 'Male' ? 'selected' : '' }} hidden>Boy</option>
                                                <option value="Female" {{ old('gender', $application->gender) == 'Female' ? 'selected' : '' }} hidden>Girl</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Place of Birth *</label>
                                        <input type="text" name="birthplace" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('birthplace', $application->birthplace) }}">
                                    </div>
                                </div>

                                {{-- RELIGION & EMAIL --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                     <div><label class="block text-sm font-bold text-gray-700 mb-2">Religion</label><input type="text" name="religion" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" value="{{ old('religion', $application->religion) }}"></div>
                                     <div><label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label><input type="email" name="email_address" class="w-full rounded-lg border-gray-300 shadow-sm bg-gray-100 h-11 text-gray-500" required value="{{ $application->email_address }}" readonly></div>
                                </div>
                            </div>

                            {{-- === RIGHT COLUMN: PHOTO UPLOAD === --}}
                            <div class="w-full md:w-64 flex-shrink-0 flex flex-col items-center">
                                <label class="block text-sm font-bold text-gray-700 mb-2 text-center">Recent 2X2 Photograph <br><span class="font-normal text-xs text-gray-500">(White Background)</span></label>
                                
                                <div style="width: 200px; height: 200px;" class="bg-white border-4 border-dashed border-indigo-300 flex items-center justify-center text-indigo-400 rounded-lg overflow-hidden relative shadow-sm mb-3">
                                    @if($application->uploaded_files['id_picture'] ?? false)
                                        <img src="{{ $application->uploaded_files['id_picture'] }}" class="absolute inset-0 w-full h-full object-cover z-10" id="current-preview">
                                    @else
                                        <div id="preview-text" class="absolute inset-0 flex items-center justify-center pointer-events-none z-0">
                                            <span class="text-xs text-center px-2 font-bold uppercase tracking-wider text-indigo-300">Photo<br>Preview</span>
                                        </div>
                                    @endif
                                    <img id="preview" class="absolute inset-0 w-full h-full object-cover hidden z-20 bg-white">
                                </div>

                                <input type="file" name="id_picture" accept="image/*" 
                                       onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0]); document.getElementById('preview').classList.remove('hidden');" 
                                       class="block w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer text-center">
                            </div>

                        </div>
                    </div>

                    {{-- 2. ADDRESS INFORMATION --}}
                    <div class="mb-8 sm:mb-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">2</span> Address Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Region</label>
                                <select name="region" x-model="selectedRegion" @change="updateProvinces()" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required>
                                    <option value="">Select Region</option>
                                    <template x-for="(provinces, region) in regionsData" :key="region">
                                        <option :value="region" x-text="region" :selected="region == selectedRegion"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Province</label>
                                <select name="province" x-model="selectedProvince" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required :disabled="!selectedRegion">
                                    <option value="">Select Province</option>
                                    <template x-for="province in availableProvinces" :key="province">
                                        <option :value="province" x-text="province" :selected="province == selectedProvince"></option>
                                    </template>
                                </select>
                            </div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Municipality/City</label><input type="text" name="municipality_city" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('municipality_city', $application->municipality_city) }}"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Barangay</label><input type="text" name="barangay" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('barangay', $application->barangay) }}"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Street / House No.</label><input type="text" name="street_address" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" value="{{ old('street_address', $application->street_address) }}" required></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Zip Code</label><input type="text" name="zip_code" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" value="{{ old('zip_code', $application->zip_code) }}" required maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4)"></div>
                        </div>
                    </div>

                    {{-- 3. ACADEMIC & SPORTS --}}
                    <div class="mb-8 sm:mb-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">3</span> Academic & Sports</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Last School Attended</label><input type="text" name="previous_school" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('previous_school', $application->previous_school) }}"></div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">School Type</label>
                                <select name="school_type" class="w-full rounded-lg border-gray-300 shadow-sm h-11">
                                    <option value="Public" {{ old('school_type', $application->school_type) == 'Public' ? 'selected' : '' }}>Public</option>
                                    <option value="Private" {{ old('school_type', $application->school_type) == 'Private' ? 'selected' : '' }}>Private</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Grade Level Applying For</label>
                                <select name="grade_level_applied" class="w-full rounded-lg border-gray-300 shadow-sm h-11" required>
                                    <option value="Grade 7" {{ old('grade_level_applied', $application->grade_level_applied) == 'Grade 7' ? 'selected' : '' }}>Grade 7</option>
                                    <option value="Grade 8" {{ old('grade_level_applied', $application->grade_level_applied) == 'Grade 8' ? 'selected' : '' }}>Grade 8</option>
                                </select>
                            </div>
                        </div>

                        {{-- FOCUS SPORTS --}}
                        <div class="bg-indigo-50 p-6 rounded-lg mb-6 border border-indigo-100">
                            <label class="block text-sm font-bold text-indigo-900 mb-3 uppercase tracking-wide">Focus Sports</label>
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
                                <label class="block text-sm font-bold text-gray-700 mb-2">Palarong Pambansa Finisher?</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center"><input type="radio" name="palaro_finisher" value="Yes" class="mr-2" {{ old('palaro_finisher', $application->has_palaro_participation ? 'Yes' : 'No') == 'Yes' ? 'checked' : '' }}> Yes</label>
                                    <label class="flex items-center"><input type="radio" name="palaro_finisher" value="No" class="mr-2" {{ old('palaro_finisher', $application->has_palaro_participation ? 'Yes' : 'No') == 'No' ? 'checked' : '' }}> No</label>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg border">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Batang Pinoy Finisher?</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center"><input type="radio" name="batang_pinoy_finisher" value="Yes" class="mr-2" {{ $application->batang_pinoy_finisher == 'Yes' ? 'checked' : '' }}> Yes</label>
                                    <label class="flex items-center"><input type="radio" name="batang_pinoy_finisher" value="No" class="mr-2" {{ $application->batang_pinoy_finisher == 'No' ? 'checked' : '' }}> No</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 4. BACKGROUND INFO --}}
                    <div class="mb-8 sm:mb-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">4</span> Background Information</h3>
                        <div class="grid grid-cols-1 gap-6 mb-6">
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
                            <div x-show="referralSource === 'NAS Personnel / Student-Athlete Referral'" class="bg-yellow-50 p-4 rounded-md border border-yellow-200">
                                <label class="block text-sm font-bold text-yellow-800 mb-1">If referred, write the name:</label>
                                <input type="text" name="referrer_name" x-model="referrerName" class="w-full rounded-md border-gray-300 shadow-sm h-10">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Attended articulation campaign?</label>
                                <div class="flex space-x-6">
                                    <label class="flex items-center"><input type="radio" name="attended_campaign" value="Yes" class="mr-2" {{ old('attended_campaign', $application->attended_campaign) == 'Yes' ? 'checked' : '' }}> Yes</label>
                                    <label class="flex items-center"><input type="radio" name="attended_campaign" value="No" class="mr-2" {{ old('attended_campaign', $application->attended_campaign) == 'No' ? 'checked' : '' }}> No</label>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Indigenous Group?</label>
                                <div class="flex space-x-4 mb-2">
                                    <label class="flex items-center"><input type="radio" x-model="isIP" name="is_ip" value="Yes" class="mr-2 text-indigo-600"> Yes</label>
                                    <label class="flex items-center"><input type="radio" x-model="isIP" name="is_ip" value="No" class="mr-2 text-indigo-600"> No</label>
                                </div>
                                <div x-show="isIP === 'Yes'"><input type="text" name="ip_group_name" x-model="ipGroup" :required="isIP === 'Yes'" placeholder="Specify IP Group" class="w-full rounded-md border-gray-300 shadow-sm h-10 text-sm" :required="isIP === 'Yes'"></div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">PWD?</label>
                                <div class="flex space-x-4 mb-2">
                                    <label class="flex items-center"><input type="radio" x-model="isPWD" name="is_pwd" value="Yes" class="mr-2 text-indigo-600"> Yes</label>
                                    <label class="flex items-center"><input type="radio" x-model="isPWD" name="is_pwd" value="No" class="mr-2 text-indigo-600"> No</label>
                                </div>
                                <div x-show="isPWD === 'Yes'"><input type="text" name="pwd_disability" x-model="pwdType" :required="isPWD === 'Yes'" placeholder="Specify disability" class="w-full rounded-md border-gray-300 shadow-sm h-10 text-sm" :required="isPWD === 'Yes'"></div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">4Ps?</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center"><input type="radio" name="is_4ps" value="Yes" class="mr-2 text-indigo-600" {{ old('is_4ps', $application->is_4ps ? 'Yes' : 'No') == 'Yes' ? 'checked' : '' }}> Yes</label>
                                    <label class="flex items-center"><input type="radio" name="is_4ps" value="No" class="mr-2 text-indigo-600" {{ old('is_4ps', $application->is_4ps ? 'Yes' : 'No') == 'No' ? 'checked' : '' }}> No</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 5. PARENTS' & DESIGNATED GUARDIAN'S INFORMATION --}}
                    <div class="mb-8 sm:mb-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">5</span> PARENTS' & DESIGNATED GUARDIAN'S INFORMATION</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Designated Guardian *</label><input type="text" name="guardian_name" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('guardian_name', $application->guardian_name) }}"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Relationship to the Applicant *</label><input type="text" name="guardian_relationship" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('guardian_relationship', $application->guardian_relationship) }}"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Contact Number *</label><input type="text" name="guardian_contact" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('guardian_contact', $application->guardian_contact) }}" maxlength="11"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label><input type="email" name="guardian_email" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" value="{{ old('guardian_email', $application->guardian_email) }}"></div>
                        </div>
                    </div>

                    {{-- REMOVED SECTION 6: REQUIREMENTS UPLOAD --}}

                    <div class="flex flex-col sm:flex-row justify-center pb-4 sm:pb-8 pt-4 gap-4">
                        <a href="{{ route('applicant.dashboard') }}" class="w-full sm:w-auto text-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 sm:px-10 py-3 rounded-lg font-bold text-base sm:text-lg shadow-md transition">Cancel</a>
                        <button type="submit" class="w-full sm:w-auto bg-indigo-700 hover:bg-indigo-800 text-white px-6 sm:px-10 py-3 rounded-lg font-bold text-base sm:text-lg shadow-md transition transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-indigo-300">UPDATE PROFILE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ALPINE.JS LOGIC --}}
    <script>
        function applicantForm() {
            return {
                dob: @json(old('date_of_birth', \Carbon\Carbon::parse($application->date_of_birth)->format('Y-m-d'))),
                age: @json(old('age', $application->age)),
                selectedSport: @json(old('sport', $application->sport)),
                sportSpec: @json(old('sport_specification', $application->sport_specification)), 
                referralSource: @json(old('learn_about_nas', $application->learn_about_nas)), 
                referrerName: @json(old('referrer_name', $application->referrer_name)),
                isIP: @json(old('is_ip', $application->is_ip ? "Yes" : "No")),
                ipGroup: @json(old('ip_group_name', $application->ip_group_name)),
                isPWD: @json(old('is_pwd', $application->is_pwd ? "Yes" : "No")),
                pwdType: @json(old('pwd_disability', $application->pwd_disability)),
                
                // Added for 4Ps
                is4Ps: @json(old('is_4ps', $application->is_4ps ? "Yes" : "No")),

                selectedRegion: @json(old('region', $application->region)),
                selectedProvince: @json(old('province', $application->province)),
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
                    if (this.selectedRegion && this.regionsData[this.selectedRegion]) {
                        this.availableProvinces = this.regionsData[this.selectedRegion];
                    }
                    if (this.dob && !this.age) { this.calculateAge(); }
                },
                updateProvinces() {
                    this.availableProvinces = this.regionsData[this.selectedRegion] || [];
                    this.selectedProvince = '';
                },
                calculateAge() {
                    if (this.dob) {
                        let today = new Date();
                        let birthDate = new Date(this.dob);
                        let age = today.getFullYear() - birthDate.getFullYear();
                        let m = today.getMonth() - birthDate.getMonth();
                        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) { age--; }
                        this.age = age;
                    }
                }
            }
        }
    </script>
</x-applicant-layout>