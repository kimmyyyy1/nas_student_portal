<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Student Record') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 bg-white border-b border-gray-200">

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
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Email Address</label><input type="email" name="email_address" value="{{ old('email_address', $student->email_address) }}" class="w-full border-gray-300 rounded-md shadow-sm" required></div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Sex</label>
                                        <select name="sex" class="w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="Male" {{ old('sex', $student->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('sex', $student->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Birthdate</label><input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate', $student->birthdate ? $student->birthdate->format('Y-m-d') : '') }}" class="w-full border-gray-300 rounded-md shadow-sm" required></div>
                                    <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Birthplace</label><input type="text" name="birthplace" value="{{ old('birthplace', $student->birthplace) }}" class="w-full border-gray-300 rounded-md shadow-sm" required></div>
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Religion</label><input type="text" name="religion" value="{{ old('religion', $student->religion) }}" class="w-full border-gray-300 rounded-md shadow-sm"></div>
                                    
                                    {{-- CHECKBOXES & OTHERS (FIXED) --}}
                                    <div class="md:col-span-3 mt-4 pt-4 border-t border-gray-100 border-dashed">
                                        <div class="flex flex-col md:flex-row md:items-center gap-4">
                                            
                                            {{-- Group 1: Checkboxes --}}
                                            <div class="flex flex-wrap gap-4">
                                                <label class="flex items-center space-x-2 cursor-pointer">
                                                    <input type="checkbox" name="is_ip" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-4 w-4" {{ old('is_ip', $student->is_ip) ? 'checked' : '' }}> 
                                                    <span class="text-xs font-bold text-gray-600 uppercase">Indigenous People (IP)</span>
                                                </label>
                                                <label class="flex items-center space-x-2 cursor-pointer">
                                                    <input type="checkbox" name="is_pwd" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-4 w-4" {{ old('is_pwd', $student->is_pwd) ? 'checked' : '' }}> 
                                                    <span class="text-xs font-bold text-gray-600 uppercase">PWD</span>
                                                </label>
                                                <label class="flex items-center space-x-2 cursor-pointer">
                                                    <input type="checkbox" name="is_4ps" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-4 w-4" {{ old('is_4ps', $student->is_4ps) ? 'checked' : '' }}> 
                                                    <span class="text-xs font-bold text-gray-600 uppercase">4Ps Beneficiary</span>
                                                </label>
                                            </div>

                                            {{-- Group 2: Input Field for "Others" --}}
                                            {{-- NOTE: Siguraduhin na may 'other_remarks' column sa database o palitan ito ng tamang column name --}}
                                            <div class="flex items-center flex-1 w-full md:w-auto">
                                                <label for="other_remarks" class="text-xs font-bold text-gray-600 uppercase mr-2 whitespace-nowrap">Others:</label>
                                                <input type="text" id="other_remarks" name="other_remarks" value="{{ old('other_remarks', $student->other_remarks ?? '') }}" 
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
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Region</label><input type="text" name="region" value="{{ old('region', $student->region) }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required></div>
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Province</label><input type="text" name="province" value="{{ old('province', $student->province) }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required></div>
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">City</label><input type="text" name="municipality_city" value="{{ old('municipality_city', $student->municipality_city) }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required></div>
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Barangay</label><input type="text" name="barangay" value="{{ old('barangay', $student->barangay) }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required></div>
                                </div>
                                <div class="mt-3"><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Street</label><input type="text" name="street_address" value="{{ old('street_address', $student->street_address) }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm"></div>
                            </div>

                            {{-- ACADEMIC & SPORTS --}}
                            <div class="md:col-span-2 bg-gray-50 p-6 rounded-lg border border-gray-200">
                                <h3 class="font-bold text-gray-800 mb-4 flex items-center"><i class='bx bx-trophy mr-2 text-yellow-600'></i> Academic & Sports</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    
                                    {{-- 1. GRADE LEVEL --}}
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Grade Level</label>
                                        <select id="grade_level" name="grade_level" class="w-full border-gray-300 rounded-md shadow-sm">
                                            @foreach(['Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'] as $gl)
                                                <option value="{{ $gl }}" {{ old('grade_level', $student->grade_level) == $gl ? 'selected' : '' }}>{{ $gl }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- 2. SECTION ASSIGNMENT (DEPENDENT) --}}
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Section Assignment</label>
                                        <select id="section_id" name="section_id" class="w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="">-- No Section Yet --</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Sports Team</label>
                                        <select name="team_id" class="w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="">-- No Team Yet --</option>
                                            @foreach($teams as $team)
                                                <option value="{{ $team->id }}" {{ old('team_id', $student->team_id) == $team->id ? 'selected' : '' }}>{{ $team->team_name }}</option>
                                            @endforeach
                                        </select>
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

                            {{-- ENROLLMENT & GUARDIAN --}}
                            <div class="md:col-span-2 bg-blue-50 p-4 rounded border border-blue-100">
                                <h3 class="text-lg font-bold text-blue-800 mb-4">Enrollment System Fields</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                                    <div><label class="block text-sm font-medium text-gray-700">Enrollment Date</label><input type="date" name="enrollment_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('enrollment_date', $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : '') }}"></div>
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

                            <div class="md:col-span-2">
                                <h3 class="font-bold text-gray-700 mb-4 border-b pb-2 flex items-center"><i class='bx bx-shield-alt-2 mr-2'></i> Guardian Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Full Name</label><input type="text" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}" class="w-full border-gray-300 rounded-md shadow-sm" required></div>
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Relationship</label><input type="text" name="guardian_relationship" value="{{ old('guardian_relationship', $student->guardian_relationship) }}" class="w-full border-gray-300 rounded-md shadow-sm" required></div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Contact Number</label><input type="text" name="guardian_contact" value="{{ old('guardian_contact', $student->guardian_contact) }}" class="w-full border-gray-300 rounded-md shadow-sm" required></div>
                                    <div><label class="block text-xs font-bold text-gray-600 uppercase mb-1">Guardian Address</label><input type="text" name="guardian_address" value="{{ old('guardian_address', $student->guardian_address) }}" class="w-full border-gray-300 rounded-md shadow-sm"></div>
                                </div>
                            </div>

                        </div> 
                        
                        <div class="mt-10 flex justify-end gap-4 border-t border-gray-100 pt-6">
                            <a href="{{ route('students.index', $queryParams ?? []) }}" class="px-6 py-2 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200 transition">Cancel</a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5 flex items-center">
                                <i class='bx bx-save mr-2'></i> Update Record
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS (PHOTO PREVIEW & FILTER) --}}
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
            // 1. KUNIN ANG DATA (Blade -> JS)
            const allSections = @json($sections ?? []); 
            const currentSectionId = @json(old('section_id', $student->section_id ?? ''));

            const gradeSelect = document.getElementById('grade_level');
            const sectionSelect = document.getElementById('section_id');

            // Safety check
            if (!gradeSelect || !sectionSelect) return;

            function filterSections() {
                const selectedGradeText = gradeSelect.value;
                // Extract number only (e.g., "Grade 7" -> "7")
                const gradeNumber = selectedGradeText.replace(/[^0-9]/g, '');

                // Clear dropdown
                sectionSelect.innerHTML = ''; 

                if (gradeNumber) {
                    // Filter Sections based on Grade Level
                    const filteredSections = allSections.filter(section => {
                        if(!section.grade_level) return false;
                        const sectionGradeNumber = section.grade_level.toString().replace(/[^0-9]/g, '');
                        return sectionGradeNumber === gradeNumber;
                    });

                    if (filteredSections.length > 0) {
                        // Add default option
                        const defaultOption = document.createElement('option');
                        defaultOption.value = "";
                        defaultOption.text = "-- Select Section --";
                        sectionSelect.appendChild(defaultOption);
                        
                        // Populate Options
                        filteredSections.forEach(section => {
                            const option = document.createElement('option');
                            option.value = section.id;
                            option.text = section.section_name;

                            // Pre-select logic
                            if (currentSectionId && currentSectionId == section.id) {
                                option.selected = true;
                            }

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

            // Remove old listener to avoid duplication (sa Livewire navigation)
            gradeSelect.removeEventListener('change', filterSections);
            // Add new listener
            gradeSelect.addEventListener('change', filterSections);

            // Run immediately logic to populate saved data
            if(gradeSelect.value) {
                filterSections();
            }
        }

        // --- EVENT LISTENERS ---
        // 1. Para sa Hard Refresh (F5)
        document.addEventListener('DOMContentLoaded', initStudentForm);
        
        // 2. Para sa Livewire Navigation (wire:navigate)
        document.addEventListener('livewire:navigated', initStudentForm);

    </script>
</x-app-layout>