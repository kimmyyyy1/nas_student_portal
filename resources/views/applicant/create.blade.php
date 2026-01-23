<x-applicant-layout>
    {{-- 👇 WRAPPER FOR MODAL STATE --}}
    <div x-data="{ showPrivacyModal: false, isSubmitting: false }" class="max-w-7xl mx-auto py-6 sm:py-10 px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-6 sm:mb-10">
            {{-- HEADER LOGO --}}
            <img src="{{ asset('images/nas/horizontal.png') }}" class="h-10 sm:h-12 md:h-16 mx-auto mb-3 sm:mb-4 drop-shadow-sm object-contain" alt="NAS Logo">
            <p class="text-xs sm:text-sm text-gray-500 mt-1 uppercase tracking-widest font-bold">Based on SAIS Guidelines</p>
        </div>

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-200">
            <div class="h-2 bg-indigo-700 w-full"></div>

            <div class="p-6 sm:p-8 md:p-12 text-gray-900">
                
                {{-- ERROR MESSAGES --}}
                @if ($errors->any())
                    <div class="mb-6 sm:mb-8 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-md text-sm shadow-sm">
                        <p class="font-bold mb-2">Please check required fields:</p>
                        <ul class="list-disc list-inside ml-1">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                {{-- 👇 ADDED ID="applicantForm" para matawag sa Javascript --}}
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
                                <input type="text" name="lrn" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('lrn') }}" placeholder="12-digit LRN">
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
                                <input type="date" id="date_of_birth" name="date_of_birth" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('date_of_birth') }}" onchange="calculateAge()">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Age</label>
                                <input type="number" id="age" name="age" class="w-full rounded-lg border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed h-11 text-gray-600 font-bold" value="{{ old('age') }}" readonly>
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

                    {{-- SPECIAL CATEGORIES --}}
                    <div class="bg-blue-50 p-4 sm:p-6 rounded-xl border border-blue-100 mb-8 sm:mb-10">
                        <span class="block text-sm font-bold text-blue-800 mb-3 sm:mb-4 uppercase tracking-wide">Special Categories (Check if applicable):</span>
                        <div class="flex flex-col gap-2 sm:gap-3">
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-6">
                                <label class="flex items-center space-x-3 cursor-pointer p-2 hover:bg-blue-100 rounded transition"><input type="checkbox" name="categories[]" value="Indigenous People" {{ (is_array(old('categories')) && in_array('Indigenous People', old('categories'))) ? 'checked' : '' }} class="rounded text-indigo-600 shadow-sm w-5 h-5"> <span class="text-sm font-semibold text-gray-700">Indigenous People (IP)</span></label>
                                <label class="flex items-center space-x-3 cursor-pointer p-2 hover:bg-blue-100 rounded transition"><input type="checkbox" name="categories[]" value="PWD" {{ (is_array(old('categories')) && in_array('PWD', old('categories'))) ? 'checked' : '' }} class="rounded text-indigo-600 shadow-sm w-5 h-5"> <span class="text-sm font-semibold text-gray-700">Person with Disability (PWD)</span></label>
                                <label class="flex items-center space-x-3 cursor-pointer p-2 hover:bg-blue-100 rounded transition"><input type="checkbox" name="categories[]" value="4Ps Beneficiary" {{ (is_array(old('categories')) && in_array('4Ps Beneficiary', old('categories'))) ? 'checked' : '' }} class="rounded text-indigo-600 shadow-sm w-5 h-5"> <span class="text-sm font-semibold text-gray-700">4Ps Beneficiary</span></label>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mt-1 sm:mt-2 w-full">
                                <label class="flex items-center space-x-3 cursor-pointer p-2 hover:bg-blue-100 rounded transition shrink-0">
                                    <input type="checkbox" id="chk_others" name="categories[]" value="Others" {{ (is_array(old('categories')) && in_array('Others', old('categories'))) ? 'checked' : '' }} class="rounded text-indigo-600 shadow-sm w-5 h-5" onchange="toggleOthersInput()"> 
                                    <span class="text-sm font-semibold text-gray-700">Others:</span>
                                </label>
                                <input type="text" id="other_details" name="other_category_details" value="{{ old('other_category_details') }}" class="w-full sm:w-1/2 rounded-lg border-gray-300 shadow-sm h-10 focus:border-indigo-500 hidden" placeholder="Please specify category details...">
                            </div>
                        </div>
                    </div>

                    {{-- 2. ADDRESS INFORMATION --}}
                    <div class="mb-8 sm:mb-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-4 sm:mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">2</span> Address Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Region *</label><input type="text" name="region" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('region') }}"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Province *</label><input type="text" name="province" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('province') }}"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Municipality/City *</label><input type="text" name="municipality_city" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('municipality_city') }}"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Barangay *</label><input type="text" name="barangay" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('barangay') }}"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Street / House No.</label><input type="text" name="street_address" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" value="{{ old('street_address') }}" required></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Zip Code</label><input type="text" name="zip_code" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" value="{{ old('zip_code') }}" required></div>
                        </div>
                    </div>

                    {{-- 3. ACADEMIC & SPORTS --}}
                    <div class="mb-8 sm:mb-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-4 sm:mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">3</span> Academic & Sports</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Last School Attended *</label><input type="text" name="previous_school" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('previous_school') }}"></div>
                            
                            {{-- DYNAMIC & CLEANED SPORTS DROPDOWN --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Sport and Subcategory *</label>
                                <select name="sport" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="" disabled selected>-- Select Sport --</option>
                                    
                                    @if(isset($teams) && count($teams) > 0)
                                        @foreach($teams as $team)
                                            @php
                                                // Remove "NAS " from the start and " Team" from the end
                                                $sportName = str_replace(['NAS ', ' Team'], '', $team->team_name);
                                            @endphp
                                            <option value="{{ $sportName }}" {{ old('sport') == $sportName ? 'selected' : '' }}>
                                                {{ $sportName }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>No sports available.</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">School Type *</label><select name="school_type" class="w-full rounded-lg border-gray-300 shadow-sm h-11"><option value="Public">Public</option><option value="Private">Private</option></select></div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Grade Level Applying For *</label>
                                <select name="grade_level_applied" class="w-full rounded-lg border-gray-300 shadow-sm h-11" required>
                                    <option value="">Select</option>
                                    <option value="Grade 7" {{ old('grade_level_applied') == 'Grade 7' ? 'selected' : '' }}>Grade 7</option>
                                    <option value="Grade 8" {{ old('grade_level_applied') == 'Grade 8' ? 'selected' : '' }}>Grade 8</option>
                                </select>
                            </div>
                            
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Palarong Pambansa Year</label><input type="text" name="palaro_year" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" placeholder="If yes, enter year" value="{{ old('palaro_year') }}"><div class="mt-2 flex items-center"><input type="checkbox" name="has_palaro_participation" value="1" {{ old('has_palaro_participation') ? 'checked' : '' }} class="mr-2 rounded text-indigo-600 shadow-sm w-4 h-4"> <span class="text-xs text-gray-600 font-medium">Check if participated</span></div></div>
                        </div>
                    </div>

                    {{-- 4. DESIGNATED GUARDIAN --}}
                    <div class="mb-8 sm:mb-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-4 sm:mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">4</span> Designated Guardian</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Guardian Name *</label><input type="text" name="guardian_name" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('guardian_name') }}"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Relationship *</label><input type="text" name="guardian_relationship" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('guardian_relationship') }}"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Contact Number *</label><input type="text" name="guardian_contact" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('guardian_contact') }}"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label><input type="email" name="guardian_email" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" value="{{ old('guardian_email') }}"></div>
                        </div>
                    </div>

                    {{-- 5. REQUIREMENTS --}}
                    <div class="mb-8 sm:mb-12">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-4 sm:mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center text-xs sm:text-sm mr-2 sm:mr-3">5</span> Requirements Upload</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mb-4 sm:mb-6 italic bg-yellow-50 p-3 rounded border-l-4 border-yellow-400">Please upload clear copies (PDF, JPG, PNG). Max 5MB per file.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            @foreach(['scholarship_form' => 'Scholarship Application Forms', 'student_profile' => 'Student-Athlete Profile Form', 'coach_reco' => 'Coach Recommendation Form', 'adviser_reco' => 'Adviser Recommendation Form', 'medical_clearance' => 'Physical Eval Clearance', 'birth_cert' => 'PSA Birth Certificate', 'report_card' => 'Report Card (SF10)', 'guardian_id' => 'Guardian Valid ID'] as $key => $label)
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

                    {{-- 
                        👇 BUTTON NA NAGBUBUKAS NG MODAL (TYPE="BUTTON") 
                        Hindi ito magsusubmit. Ichecheck muna nito kung valid ang form.
                    --}}
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

        {{-- 
            👇 DATA PRIVACY CONSENT MODAL 
            Lumalabas lang ito kapag valid na ang form at pinindot ang button sa taas.
        --}}
        <div x-show="showPrivacyModal" 
             style="display: none;" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            
            {{-- Backdrop --}}
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showPrivacyModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Modal Panel --}}
                <div x-show="showPrivacyModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full border-t-8 border-indigo-700">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg sm:text-xl leading-6 font-extrabold text-gray-900" id="modal-title">
                                        Data Privacy Consent
                                    </h3>
                                    <i class='bx bx-shield-quarter text-2xl sm:text-3xl text-indigo-600'></i>
                                </div>
                                
                                <div class="mt-2 h-48 sm:h-64 overflow-y-auto text-xs sm:text-sm text-gray-600 bg-gray-50 p-3 sm:p-4 rounded border border-gray-200 text-justify">
                                    <p class="mb-3 font-bold">Please read carefully:</p>
                                    <p class="mb-3">
                                        In compliance with the <strong>Data Privacy Act of 2012 (RA 10173)</strong>, the National Academy of Sports (NAS) is committed to protecting your personal data.
                                    </p>
                                    <p class="mb-3">
                                        By submitting this application form, you acknowledge and agree that:
                                    </p>
                                    <ul class="list-disc ml-5 mb-3 space-y-2">
                                        <li><strong>Collection:</strong> NAS collects your personal information (name, age, academic records, sports profile, etc.) solely for the purpose of admission, scholarship evaluation, and student records management.</li>
                                        <li><strong>Use:</strong> Your data will be used by authorized NAS personnel (Registrar, Coaches, Medical Staff) to assess your qualification for the NAS Scholarship Program.</li>
                                        <li><strong>Protection:</strong> NAS implements reasonable security measures to protect your data against unauthorized access, alteration, or disclosure.</li>
                                        <li><strong>Retention:</strong> Your data will be retained as long as necessary for the fulfillment of the stated purposes and in accordance with applicable laws.</li>
                                    </ul>
                                    <p>
                                        You attest that all information provided in this application is true and correct. Any falsification of documents may be grounds for disqualification.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse flex-col gap-2 sm:gap-0">
                        {{-- 
                            👇 REAL SUBMIT BUTTON 
                            Ito ang pipindutin para mag-submit talaga. Gumagamit ito ng Javascript para i-submit ang form sa likod.
                        --}}
                        <button type="button" 
                                @click="isSubmitting = true; document.getElementById('applicantForm').submit();"
                                :disabled="isSubmitting"
                                class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-700 text-sm sm:text-base font-medium text-white hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!isSubmitting">I AGREE & SUBMIT APPLICATION</span>
                            <span x-show="isSubmitting">Processing...</span>
                        </button>
                        
                        <button type="button" 
                                @click="showPrivacyModal = false" 
                                class="mt-2 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm sm:text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Calculate Age Logic
        function calculateAge() {
            var dob = document.getElementById('date_of_birth').value;
            if (dob) {
                var today = new Date();
                var birthDate = new Date(dob);
                var age = today.getFullYear() - birthDate.getFullYear();
                var m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                document.getElementById('age').value = age;
            }
        }

        // Toggle Others Input Logic
        function toggleOthersInput() {
            var chk = document.getElementById('chk_others');
            var input = document.getElementById('other_details');
            if(chk.checked) {
                input.classList.remove('hidden');
                input.required = true;
                input.focus();
            } else {
                input.classList.add('hidden');
                input.required = false;
                input.value = ''; // Clear value if unchecked
            }
        }

        // Init functions on load
        window.addEventListener('load', function() {
            calculateAge();
            toggleOthersInput(); // Check initial state (useful for old input retrieval)
        });
    </script>
</x-applicant-layout>