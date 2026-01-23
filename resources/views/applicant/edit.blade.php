<x-applicant-layout>
    {{-- WRAPPER FOR ALPINE DATA --}}
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

                <form method="POST" action="{{ route('applicant.update') }}" enctype="multipart/form-data">
                    @csrf 
                    @method('PATCH')

                    {{-- ID PICTURE UPLOAD SECTION --}}
                    <div class="mb-8 sm:mb-10 bg-indigo-50 p-6 sm:p-8 rounded-xl border border-indigo-100 flex flex-col md:flex-row items-center gap-6 sm:gap-8">
                        <div class="flex-shrink-0 text-center">
                            <div style="width: 150px; height: 150px; sm:width: 200px; sm:height: 200px;" class="w-40 h-40 sm:w-52 sm:h-52 bg-white border-4 border-dashed border-indigo-300 flex items-center justify-center text-indigo-400 rounded-lg overflow-hidden relative shadow-sm mx-auto">
                                
                                @if(isset($application->uploaded_files['id_picture']))
                                    <img src="{{ $application->uploaded_files['id_picture'] }}" class="absolute inset-0 w-full h-full object-cover z-10" id="current-preview">
                                @else
                                    <div id="preview-text" class="absolute inset-0 flex items-center justify-center pointer-events-none z-0">
                                        <span class="text-xs text-center px-2 font-bold uppercase tracking-wider">2x2 Photo<br>Preview</span>
                                    </div>
                                @endif
                                
                                <img id="preview" class="absolute inset-0 w-full h-full object-cover hidden z-20 bg-white">
                            </div>
                            @if(isset($application->uploaded_files['id_picture']))
                                <p class="text-xs text-green-600 font-bold mt-2">✔ Current Photo Loaded</p>
                            @endif
                        </div>
                        <div class="flex-1 w-full text-center md:text-left">
                            <h3 class="text-lg sm:text-xl font-bold text-indigo-900 mb-2">Update ID Picture</h3>
                            <p class="text-xs sm:text-sm text-indigo-700 mb-4">Upload only if you want to replace the current photo. (Max 5MB)</p>
                            <input type="file" name="id_picture" accept="image/*" 
                                   onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0]); document.getElementById('preview').classList.remove('hidden');" 
                                   class="block w-full text-xs sm:text-sm text-slate-500 file:mr-4 file:py-2 sm:file:py-3 file:px-4 sm:file:px-6 file:rounded-full file:border-0 file:text-xs sm:file:text-sm file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer transition mx-auto md:mx-0 shadow-sm border border-gray-300 rounded-md bg-white">
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
                                <input type="text" name="lrn" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('lrn', $application->lrn) }}" placeholder="12-digit LRN" maxlength="12" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Last Name *</label>
                                <input type="text" name="last_name" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('last_name', $application->last_name) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">First Name *</label>
                                <input type="text" name="first_name" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('first_name', $application->first_name) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Middle Name (Optional)</label>
                                <input type="text" name="middle_name" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('middle_name', $application->middle_name) }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 mb-4 sm:mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Birthday *</label>
                                <input type="date" 
                                       id="date_of_birth" 
                                       name="date_of_birth" 
                                       class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" 
                                       required 
                                       x-model="dob"
                                       @change="calculateAge()">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Age</label>
                                <input type="number" 
                                       id="age" 
                                       name="age" 
                                       class="w-full rounded-lg border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed h-11 text-gray-600 font-bold" 
                                       readonly 
                                       x-model="age">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Sex *</label>
                                <select name="gender" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required>
                                    <option value="">Select</option>
                                    <option value="Male" {{ old('gender', $application->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $application->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Place of Birth *</label>
                                <input type="text" name="birthplace" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('birthplace', $application->birthplace) }}">
                            </div>
                        </div>

                         <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                             <div><label class="block text-sm font-bold text-gray-700 mb-2">Religion</label><input type="text" name="religion" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" value="{{ old('religion', $application->religion) }}"></div>
                             <div><label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label><input type="email" name="email_address" class="w-full rounded-lg border-gray-300 shadow-sm bg-gray-100 h-11 text-gray-500" required value="{{ $application->email_address }}" readonly></div>
                         </div>
                    </div>

                    {{-- 2. ADDRESS INFORMATION --}}
                    <div class="mb-8 sm:mb-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-4 sm:mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">2</span> Address Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Region *</label>
                                <select name="region" x-model="selectedRegion" @change="updateProvinces()" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required>
                                    <option value="">Select Region</option>
                                    <template x-for="(provinces, region) in regionsData" :key="region">
                                        <option :value="region" x-text="region"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Province *</label>
                                <select name="province" x-model="selectedProvince" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required :disabled="!selectedRegion">
                                    <option value="">Select Province</option>
                                    <template x-for="province in availableProvinces" :key="province">
                                        <option :value="province" x-text="province"></option>
                                    </template>
                                </select>
                            </div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Municipality/City *</label><input type="text" name="municipality_city" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('municipality_city', $application->municipality_city) }}"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Barangay *</label><input type="text" name="barangay" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('barangay', $application->barangay) }}"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Street / House No.</label><input type="text" name="street_address" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" value="{{ old('street_address', $application->street_address) }}" required></div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Zip Code</label>
                                <input type="text" name="zip_code" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" value="{{ old('zip_code', $application->zip_code) }}" required maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4)">
                            </div>
                        </div>
                    </div>

                    {{-- 3. ACADEMIC & SPORTS --}}
                    <div class="mb-8 sm:mb-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-4 sm:mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">3</span> Academic & Sports</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Last School Attended *</label><input type="text" name="previous_school" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('previous_school', $application->previous_school) }}"></div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">School Type *</label>
                                <select name="school_type" class="w-full rounded-lg border-gray-300 shadow-sm h-11">
                                    <option value="Public" {{ old('school_type', $application->school_type) == 'Public' ? 'selected' : '' }}>Public</option>
                                    <option value="Private" {{ old('school_type', $application->school_type) == 'Private' ? 'selected' : '' }}>Private</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Grade Level Applying For *</label>
                                <select name="grade_level_applied" class="w-full rounded-lg border-gray-300 shadow-sm h-11" required>
                                    <option value="">Select</option>
                                    <option value="Grade 7" {{ old('grade_level_applied', $application->grade_level_applied) == 'Grade 7' ? 'selected' : '' }}>Grade 7</option>
                                    <option value="Grade 8" {{ old('grade_level_applied', $application->grade_level_applied) == 'Grade 8' ? 'selected' : '' }}>Grade 8</option>
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
                                    <label class="flex items-center space-x-2">
                                        <input type="radio" name="palaro_finisher" value="Yes" class="form-radio text-indigo-600 w-4 h-4" x-model="hasPalaro">
                                        <span class="text-sm font-medium text-gray-700">Yes</span>
                                    </label>
                                    <label class="flex items-center space-x-2">
                                        <input type="radio" name="palaro_finisher" value="No" class="form-radio text-indigo-600 w-4 h-4" x-model="hasPalaro">
                                        <span class="text-sm font-medium text-gray-700">No</span>
                                    </label>
                                </div>
                                <div class="mt-3" x-show="hasPalaro === 'Yes'">
                                    <input type="text" name="palaro_year" placeholder="Year Participated" x-model="palaroYear" class="w-full rounded-md border-gray-300 shadow-sm h-10 text-sm">
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg border">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Batang Pinoy Podium Finisher?</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center space-x-2">
                                        {{-- Fixed: Manually check radio button value with server data if needed in x-init, but here name attribute works for submission --}}
                                        <input type="radio" name="batang_pinoy_finisher" value="Yes" class="form-radio text-indigo-600 w-4 h-4" {{ old('batang_pinoy_finisher', $application->batang_pinoy_finisher) == 'Yes' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-gray-700">Yes</span>
                                    </label>
                                    <label class="flex items-center space-x-2">
                                        <input type="radio" name="batang_pinoy_finisher" value="No" class="form-radio text-indigo-600 w-4 h-4" {{ old('batang_pinoy_finisher', $application->batang_pinoy_finisher) == 'No' ? 'checked' : '' }}>
                                        <span class="text-sm font-medium text-gray-700">No</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 4. ADDITIONAL INFORMATION --}}
                    <div class="mb-8 sm:mb-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-4 sm:mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">4</span> Background Information</h3>
                        
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
                                <label class="block text-sm font-bold text-yellow-800 mb-1">If referred, write the name (One name only):</label>
                                <input type="text" name="referrer_name" x-model="referrerName" class="w-full rounded-md border-gray-300 shadow-sm h-10">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Have you attended any of our articulation campaign or visited an information booth?</label>
                                <div class="flex space-x-6">
                                    <label class="flex items-center"><input type="radio" name="attended_campaign" value="Yes" class="mr-2 text-indigo-600" {{ old('attended_campaign', $application->attended_campaign) == 'Yes' ? 'checked' : '' }}> Yes</label>
                                    <label class="flex items-center"><input type="radio" name="attended_campaign" value="No" class="mr-2 text-indigo-600" {{ old('attended_campaign', $application->attended_campaign) == 'No' ? 'checked' : '' }}> No</label>
                                </div>
                            </div>
                        </div>

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
                                    <label class="flex items-center"><input type="radio" name="is_4ps" value="Yes" class="mr-2 text-indigo-600" {{ old('is_4ps', $application->is_4ps ? 'Yes' : 'No') == 'Yes' ? 'checked' : '' }}> Yes</label>
                                    <label class="flex items-center"><input type="radio" name="is_4ps" value="No" class="mr-2 text-indigo-600" {{ old('is_4ps', $application->is_4ps ? 'Yes' : 'No') == 'No' ? 'checked' : '' }}> No</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 5. DESIGNATED GUARDIAN --}}
                    <div class="mb-8 sm:mb-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-4 sm:mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">5</span> Designated Guardian</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Guardian Name *</label><input type="text" name="guardian_name" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('guardian_name', $application->guardian_name) }}"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Relationship *</label><input type="text" name="guardian_relationship" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('guardian_relationship', $application->guardian_relationship) }}"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Contact Number *</label><input type="text" name="guardian_contact" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('guardian_contact', $application->guardian_contact) }}"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label><input type="email" name="guardian_email" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" value="{{ old('guardian_email', $application->guardian_email) }}"></div>
                        </div>
                    </div>

                    {{-- 6. REQUIREMENTS --}}
                    <div class="mb-8 sm:mb-12">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-4 sm:mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">6</span> Requirements Upload</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mb-4 sm:mb-6 italic bg-yellow-50 p-3 rounded border-l-4 border-yellow-400">
                            <strong>Note:</strong> You only need to upload files below if you wish to <u>replace</u> your current submission. If left empty, your existing file will be kept.
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            @foreach([
                                'scholarship_form'  => 'Scholarship Application Form',
                                'student_profile'   => 'Student-Athlete’s Profile Form',
                                'medical_clearance' => 'Preparticipation Physical Evaluation Clearance Form',
                                'coach_reco'        => 'Coach’s Recommendation Form w/ Valid ID & Signature',
                                'adviser_reco'      => 'Adviser’s Recommendation Form w/ Valid ID & Signature',
                                'birth_cert'        => 'PSA Birth Certificate',
                                'report_card'       => 'Report Cards (Gr 5/6 or 6/7)',
                                'guardian_id'       => 'Designated Guardian’s Valid ID w/ Signature'
                            ] as $key => $label)
                                
                                <div class="bg-gray-50 p-4 sm:p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-2">
                                        <label class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-1 sm:mb-0">
                                            {{ $label }} <span class="text-red-600">*</span>
                                        </label>
                                        @if(isset($application->uploaded_files[$key]))
                                            <span class="text-xs font-bold text-green-600 bg-green-100 px-2 py-1 rounded inline-block self-start sm:self-auto">✔ File on Record</span>
                                        @else
                                            <span class="text-xs font-bold text-red-500 bg-red-100 px-2 py-1 rounded inline-block self-start sm:self-auto">Missing</span>
                                        @endif
                                    </div>

                                    <input type="file" name="files[{{ $key }}]" 
                                           class="block w-full text-xs sm:text-sm text-slate-600 file:mr-4 file:py-2 sm:file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer" 
                                           accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-center pb-4 sm:pb-8 pt-4 gap-4">
                        <a href="{{ route('applicant.dashboard') }}" class="w-full sm:w-auto text-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 sm:px-10 py-3 rounded-lg font-bold text-base sm:text-lg shadow-md transition">Cancel</a>
                        <button type="submit" class="w-full sm:w-auto bg-indigo-700 hover:bg-indigo-800 text-white px-6 sm:px-10 py-3 rounded-lg font-bold text-base sm:text-lg shadow-md transition transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-indigo-300">UPDATE APPLICATION</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ALPINE.JS LOGIC --}}
    <script>
        function applicantForm() {
            return {
                age: @json(old('age', $application->age)),
                selectedSport: @json(old('sport', $application->sport)),
                sportSpec: @json(old('sport_specification', $application->sport_specification)), 
                referralSource: @json(old('learn_about_nas', $application->learn_about_nas)), 
                referrerName: @json(old('referrer_name', $application->referrer_name)),
                isIP: @json(old('is_ip', $application->is_ip ? "Yes" : "No")),
                ipGroup: @json(old('ip_group_name', $application->ip_group_name)),
                isPWD: @json(old('is_pwd', $application->is_pwd ? "Yes" : "No")),
                pwdType: @json(old('pwd_disability', $application->pwd_disability)),
                hasPalaro: @json(old('palaro_finisher', $application->has_palaro_participation ? "Yes" : "No")),
                palaroYear: @json(old('palaro_year', $application->palaro_year)),
                dob: @json(old('date_of_birth', \Carbon\Carbon::parse($application->date_of_birth)->format('Y-m-d'))),
                
                // Region & Province Data
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
                    // Populate provinces on load based on saved region
                    if (this.selectedRegion) {
                        this.availableProvinces = this.regionsData[this.selectedRegion] || [];
                    }
                    
                    // Re-calculate age if dob exists but age is empty for some reason
                    if (this.dob && !this.age) {
                        this.calculateAge();
                    }
                },

                updateProvinces() {
                    this.availableProvinces = this.regionsData[this.selectedRegion] || [];
                    this.selectedProvince = ''; // Reset province selection
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