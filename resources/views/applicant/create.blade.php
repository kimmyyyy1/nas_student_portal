<x-applicant-layout>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-10">
            {{-- 👇 UPDATED HEADER SECTION --}}
            {{-- 1. Changed image to horizontal.png --}}
            {{-- 2. Adjusted height to h-12 md:h-16 so it's not too big --}}
            {{-- 3. Removed the text titles --}}
            <img src="{{ asset('images/nas/horizontal.png') }}" class="h-12 md:h-16 mx-auto mb-4 drop-shadow-sm object-contain" alt="NAS Logo">
            
            <p class="text-sm text-gray-500 mt-1 uppercase tracking-widest font-bold">Based on SAIS Guidelines</p>
        </div>

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-200">
            <div class="h-2 bg-indigo-700 w-full"></div>

            <div class="p-8 md:p-12 text-gray-900">
                
                @if ($errors->any())
                    <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-md text-sm shadow-sm">
                        <p class="font-bold mb-2">Please check required fields:</p>
                        <ul class="list-disc list-inside ml-1">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('applicant.store') }}" enctype="multipart/form-data">
                    @csrf 

                    <div class="mb-10 bg-indigo-50 p-8 rounded-xl border border-indigo-100 flex flex-col md:flex-row items-center gap-8">
                        <div class="flex-shrink-0">
                            <div style="width: 200px; height: 200px;" class="bg-white border-4 border-dashed border-indigo-300 flex items-center justify-center text-indigo-400 rounded-lg overflow-hidden relative shadow-sm mx-auto">
                                <div id="preview-text" class="absolute inset-0 flex items-center justify-center pointer-events-none z-0">
                                    <span class="text-xs text-center px-2 font-bold uppercase tracking-wider">2x2 Photo<br>Preview</span>
                                </div>
                                <img id="preview" class="absolute inset-0 w-full h-full object-cover hidden z-10 bg-white">
                            </div>
                        </div>
                        <div class="flex-1 w-full text-center md:text-left">
                            <h3 class="text-xl font-bold text-indigo-900 mb-2">Upload ID Picture *</h3>
                            <p class="text-sm text-indigo-700 mb-4">Requirement: 2x2 size, formal attire, white background. (Max 5MB)</p>
                            <input type="file" name="id_picture" accept="image/*" required onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0]); document.getElementById('preview').classList.remove('hidden'); document.getElementById('preview-text').classList.add('hidden');" 
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer transition mx-auto md:mx-0 shadow-sm border border-gray-300 rounded-md bg-white">
                        </div>
                    </div>

                    <div class="mb-10">
                        <h3 class="text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-6 flex items-center">
                            <span class="bg-gray-800 text-white rounded-full h-8 w-8 flex items-center justify-center text-sm mr-3">1</span> Applicant Information
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">LRN (Learner Reference No.) *</label>
                                <input type="text" name="lrn" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('lrn') }}" placeholder="12-digit LRN">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Last Name *</label>
                                <input type="text" name="last_name" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('last_name', Auth::user()->last_name) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">First Name *</label>
                                <input type="text" name="first_name" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('first_name', Auth::user()->first_name) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Middle Name (Optional)</label>
                                <input type="text" name="middle_name" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('middle_name', Auth::user()->middle_name) }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
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

                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                             <div><label class="block text-sm font-bold text-gray-700 mb-2">Religion</label><input type="text" name="religion" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" value="{{ old('religion') }}"></div>
                             <div><label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label><input type="email" name="email_address" class="w-full rounded-lg border-gray-300 shadow-sm bg-gray-100 h-11 text-gray-500" required value="{{ Auth::user()->email }}" readonly></div>
                         </div>
                    </div>

                    <div class="bg-blue-50 p-6 rounded-xl border border-blue-100 mb-10">
                        <span class="block text-sm font-bold text-blue-800 mb-4 uppercase tracking-wide">Special Categories (Check if applicable):</span>
                        <div class="flex flex-col gap-3">
                            <div class="flex flex-col sm:flex-row gap-6">
                                <label class="flex items-center space-x-3 cursor-pointer p-2 hover:bg-blue-100 rounded transition"><input type="checkbox" name="categories[]" value="Indigenous People" {{ (is_array(old('categories')) && in_array('Indigenous People', old('categories'))) ? 'checked' : '' }} class="rounded text-indigo-600 shadow-sm w-5 h-5"> <span class="text-sm font-semibold text-gray-700">Indigenous People (IP)</span></label>
                                <label class="flex items-center space-x-3 cursor-pointer p-2 hover:bg-blue-100 rounded transition"><input type="checkbox" name="categories[]" value="PWD" {{ (is_array(old('categories')) && in_array('PWD', old('categories'))) ? 'checked' : '' }} class="rounded text-indigo-600 shadow-sm w-5 h-5"> <span class="text-sm font-semibold text-gray-700">Person with Disability (PWD)</span></label>
                                <label class="flex items-center space-x-3 cursor-pointer p-2 hover:bg-blue-100 rounded transition"><input type="checkbox" name="categories[]" value="4Ps Beneficiary" {{ (is_array(old('categories')) && in_array('4Ps Beneficiary', old('categories'))) ? 'checked' : '' }} class="rounded text-indigo-600 shadow-sm w-5 h-5"> <span class="text-sm font-semibold text-gray-700">4Ps Beneficiary</span></label>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row items-center gap-3 mt-2">
                                <label class="flex items-center space-x-3 cursor-pointer p-2 hover:bg-blue-100 rounded transition">
                                    <input type="checkbox" id="chk_others" name="categories[]" value="Others" {{ (is_array(old('categories')) && in_array('Others', old('categories'))) ? 'checked' : '' }} class="rounded text-indigo-600 shadow-sm w-5 h-5" onchange="toggleOthersInput()"> 
                                    <span class="text-sm font-semibold text-gray-700">Others:</span>
                                </label>
                                <input type="text" id="other_details" name="other_category_details" value="{{ old('other_category_details') }}" class="w-full sm:w-1/2 rounded-lg border-gray-300 shadow-sm h-10 focus:border-indigo-500 hidden" placeholder="Please specify category details...">
                            </div>
                        </div>
                    </div>

                    <div class="mb-10">
                        <h3 class="text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-8 w-8 flex items-center justify-center text-sm mr-3">2</span> Address Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Region *</label><input type="text" name="region" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('region') }}"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Province *</label><input type="text" name="province" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('province') }}"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Municipality/City *</label><input type="text" name="municipality_city" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('municipality_city') }}"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Barangay *</label><input type="text" name="barangay" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('barangay') }}"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Street / House No.</label><input type="text" name="street_address" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" value="{{ old('street_address') }}" required></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Zip Code</label><input type="text" name="zip_code" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" value="{{ old('zip_code') }}" required></div>
                        </div>
                    </div>

                    <div class="mb-10">
                        <h3 class="text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-8 w-8 flex items-center justify-center text-sm mr-3">3</span> Academic & Sports</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Last School Attended *</label><input type="text" name="previous_school" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" required value="{{ old('previous_school') }}"></div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Sport and Subcategory *</label>
                                <select name="sport" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="" disabled selected>-- Select Sport --</option>
                                    <option value="Aquatics - Swimming" {{ old('sport') == 'Aquatics - Swimming' ? 'selected' : '' }}>Aquatics - Swimming</option>
                                    <option value="Arnis" {{ old('sport') == 'Arnis' ? 'selected' : '' }}>Arnis</option>
                                    <option value="Athletics - Running" {{ old('sport') == 'Athletics - Running' ? 'selected' : '' }}>Athletics - Running</option>
                                    <option value="Athletics - Throwing" {{ old('sport') == 'Athletics - Throwing' ? 'selected' : '' }}>Athletics - Throwing</option>
                                    <option value="Athletics - Jumping" {{ old('sport') == 'Athletics - Jumping' ? 'selected' : '' }}>Athletics - Jumping</option>
                                    <option value="Badminton" {{ old('sport') == 'Badminton' ? 'selected' : '' }}>Badminton</option>
                                    <option value="Gymnastics" {{ old('sport') == 'Gymnastics' ? 'selected' : '' }}>Gymnastics</option>
                                    <option value="Judo" {{ old('sport') == 'Judo' ? 'selected' : '' }}>Judo</option>
                                    <option value="Table Tennis" {{ old('sport') == 'Table Tennis' ? 'selected' : '' }}>Table Tennis</option>
                                    <option value="Taekwondo" {{ old('sport') == 'Taekwondo' ? 'selected' : '' }}>Taekwondo</option>
                                    <option value="Weightlifting" {{ old('sport') == 'Weightlifting' ? 'selected' : '' }}>Weightlifting</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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

                    <div class="mb-10">
                        <h3 class="text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-8 w-8 flex items-center justify-center text-sm mr-3">4</span> Designated Guardian</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Guardian Name *</label><input type="text" name="guardian_name" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('guardian_name') }}"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Relationship *</label><input type="text" name="guardian_relationship" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('guardian_relationship') }}"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Contact Number *</label><input type="text" name="guardian_contact" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" required value="{{ old('guardian_contact') }}"></div>
                            <div><label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label><input type="email" name="guardian_email" class="w-full rounded-lg border-gray-300 shadow-sm h-11 focus:border-indigo-500" value="{{ old('guardian_email') }}"></div>
                        </div>
                    </div>

                    <div class="mb-12">
                        <h3 class="text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-6 flex items-center"><span class="bg-gray-800 text-white rounded-full h-8 w-8 flex items-center justify-center text-sm mr-3">5</span> Requirements Upload</h3>
                        <p class="text-sm text-gray-600 mb-6 italic bg-yellow-50 p-3 rounded border-l-4 border-yellow-400">Please upload clear copies (PDF, JPG, PNG). Max 5MB per file.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach(['scholarship_form' => 'Scholarship Application Forms', 'student_profile' => 'Student-Athlete Profile Form', 'coach_reco' => 'Coach Recommendation Form', 'adviser_reco' => 'Adviser Recommendation Form', 'medical_clearance' => 'Physical Eval Clearance', 'birth_cert' => 'PSA Birth Certificate', 'report_card' => 'Report Card (SF10)', 'guardian_id' => 'Guardian Valid ID'] as $key => $label)
                                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition">
                                    <label class="text-sm font-bold text-gray-800 mb-3 block uppercase tracking-wide">
                                        {{ $label }} <span class="text-red-600">*</span>
                                    </label>
                                    <input type="file" name="files[{{ $key }}]" 
                                           class="block w-full text-sm text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer" 
                                           accept=".pdf,.jpg,.jpeg,.png"
                                           required>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-center pb-8 pt-4">
                        <button type="submit" class="bg-indigo-700 hover:bg-indigo-800 text-white px-10 py-3 rounded-lg font-bold text-lg shadow-md transition transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-indigo-300">SUBMIT APPLICATION</button>
                    </div>
                </form>
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