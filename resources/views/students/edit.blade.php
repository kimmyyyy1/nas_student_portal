<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Student: {{ $student->last_name }}, {{ $student->first_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('students.update', $student->id) }}" enctype="multipart/form-data">
                        @csrf @method('PATCH')

                        <div class="mb-8 bg-gray-50 p-6 rounded border border-gray-200 flex items-center">
                            <div class="mr-6">
                                <div class="h-32 w-32 bg-gray-200 border-2 border-dashed border-gray-400 flex items-center justify-center rounded-md overflow-hidden relative">
                                    @if($student->photo)
                                        <img src="{{ asset('storage/' . $student->photo) }}" id="preview" class="absolute inset-0 w-full h-full object-cover">
                                    @else
                                        <img id="preview" class="absolute inset-0 w-full h-full object-cover hidden">
                                        <span class="text-xs text-gray-500 text-center p-2">No Photo</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Update Photo (2x2 Formal)</label>
                                <input type="file" name="photo" accept="image/*" onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0]); document.getElementById('preview').classList.remove('hidden');" 
                                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                        </div>

                        <div class="mb-8 border-b pb-4">
                            <h3 class="text-lg font-bold text-indigo-700 mb-4">1. Identifiers</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div><label class="block text-sm font-medium text-gray-700">NAS Student ID</label><input type="text" name="nas_student_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('nas_student_id', $student->nas_student_id) }}"></div>
                                <div><label class="block text-sm font-medium text-gray-700">LRN</label><input type="text" name="lrn" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('lrn', $student->lrn) }}"></div>
                            </div>
                        </div>

                        <div class="mb-8 border-b pb-4">
                            <h3 class="text-lg font-bold text-indigo-700 mb-4">2. Personal Info</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                                <div><label class="block text-sm font-medium text-gray-700">Last Name</label><input type="text" name="last_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required value="{{ old('last_name', $student->last_name) }}"></div>
                                <div><label class="block text-sm font-medium text-gray-700">First Name</label><input type="text" name="first_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required value="{{ old('first_name', $student->first_name) }}"></div>
                                <div><label class="block text-sm font-medium text-gray-700">Middle Name</label><input type="text" name="middle_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('middle_name', $student->middle_name) }}"></div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-4">
                                <div><label class="block text-sm font-medium text-gray-700">Sex</label><select name="sex" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><option value="Male" {{ $student->sex == 'Male' ? 'selected' : '' }}>Male</option><option value="Female" {{ $student->sex == 'Female' ? 'selected' : '' }}>Female</option></select></div>
                                <div><label class="block text-sm font-medium text-gray-700">Birthdate</label><input type="date" id="birthdate" name="birthdate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required value="{{ old('birthdate', $student->birthdate) }}" onchange="calculateAge()"></div>
                                <div><label class="block text-sm font-medium text-gray-700">Age</label><input type="number" id="age" name="age" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly value="{{ old('age', $student->age) }}"></div>
                                <div><label class="block text-sm font-medium text-gray-700">Religion</label><input type="text" name="religion" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('religion', $student->religion) }}"></div>
                            </div>
                            <div class="mb-4"><label class="block text-sm font-medium text-gray-700">Birthplace</label><input type="text" name="birthplace" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('birthplace', $student->birthplace) }}"></div>
                            <div class="flex space-x-6 mt-4 p-4 bg-gray-50 rounded-md">
                                <label class="flex items-center"><input type="checkbox" name="is_ip" value="1" class="rounded text-indigo-600 shadow-sm" {{ $student->is_ip ? 'checked' : '' }}> <span class="ml-2 text-sm">IP</span></label>
                                <label class="flex items-center"><input type="checkbox" name="is_pwd" value="1" class="rounded text-indigo-600 shadow-sm" {{ $student->is_pwd ? 'checked' : '' }}> <span class="ml-2 text-sm">PWD</span></label>
                                <label class="flex items-center"><input type="checkbox" name="is_4ps" value="1" class="rounded text-indigo-600 shadow-sm" {{ $student->is_4ps ? 'checked' : '' }}> <span class="ml-2 text-sm">4Ps</span></label>
                            </div>
                        </div>

                        <div class="mb-8 border-b pb-4">
                            <h3 class="text-lg font-bold text-indigo-700 mb-4">3. Academic & Sports</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                                <div><label class="block text-sm font-medium text-gray-700">Status</label><select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><option value="New" {{ $student->status == 'New' ? 'selected' : '' }}>New</option><option value="Continuing" {{ $student->status == 'Continuing' ? 'selected' : '' }}>Continuing</option><option value="Transfer out" {{ $student->status == 'Transfer out' ? 'selected' : '' }}>Transfer out</option><option value="Graduate" {{ $student->status == 'Graduate' ? 'selected' : '' }}>Graduate</option></select></div>
                                <div><label class="block text-sm font-medium text-gray-700">Promotion Status</label><select name="promotion_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><option value="">-- Select --</option><option value="Promoted" {{ $student->promotion_status == 'Promoted' ? 'selected' : '' }}>Promoted</option><option value="Conditional" {{ $student->promotion_status == 'Conditional' ? 'selected' : '' }}>Conditional</option><option value="Retained" {{ $student->promotion_status == 'Retained' ? 'selected' : '' }}>Retained</option></select></div>
                                <div><label class="block text-sm font-medium text-gray-700">Entry Year</label><input type="number" name="entry_year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('entry_year', $student->entry_year) }}"></div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div><label class="block text-sm font-medium text-gray-700">Grade Level</label><select name="grade_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><option value="Grade 7" {{ $student->grade_level == 'Grade 7' ? 'selected' : '' }}>Grade 7</option><option value="Grade 8" {{ $student->grade_level == 'Grade 8' ? 'selected' : '' }}>Grade 8</option><option value="Grade 9" {{ $student->grade_level == 'Grade 9' ? 'selected' : '' }}>Grade 9</option><option value="Grade 10" {{ $student->grade_level == 'Grade 10' ? 'selected' : '' }}>Grade 10</option></select></div>
                                <div><label class="block text-sm font-medium text-gray-700">Section</label><select name="section_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><option value="">-- Select --</option>@foreach($sections as $section)<option value="{{ $section->id }}" {{ $student->section_id == $section->id ? 'selected' : '' }}>{{ $section->section_name }}</option>@endforeach</select></div>
                                <div><label class="block text-sm font-medium text-gray-700">Team</label><select name="team_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><option value="">-- Select --</option>@foreach($teams as $team)<option value="{{ $team->id }}" {{ $student->team_id == $team->id ? 'selected' : '' }}>{{ $team->team_name }}</option>@endforeach</select></div>
                            </div>
                        </div>

                        <div class="mb-8 border-b pb-4 bg-blue-50 p-4 rounded border border-blue-100">
                            <h3 class="text-lg font-bold text-blue-800 mb-4">4. Enrollment System Fields</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                                <div><label class="block text-sm font-medium text-gray-700">Enrollment Date</label><input type="date" name="enrollment_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('enrollment_date', $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : '') }}"></div>
                                <div><label class="block text-sm font-medium text-gray-700">LIS Status</label><select name="lis_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><option value="">-- Select --</option><option value="Enrolled" {{ $student->lis_status == 'Enrolled' ? 'selected' : '' }}>Enrolled</option><option value="Pending" {{ $student->lis_status == 'Pending' ? 'selected' : '' }}>Pending</option><option value="For Follow-up" {{ $student->lis_status == 'For Follow-up' ? 'selected' : '' }}>For Follow-up</option></select></div>
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">Remarks</label><textarea name="enrollment_remarks" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('enrollment_remarks', $student->enrollment_remarks) }}</textarea></div>
                        </div>

                        <div class="mb-8 border-b pb-4">
                            <h3 class="text-lg font-bold text-indigo-700 mb-4">5. Address & Contact</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div><label class="block text-sm font-medium text-gray-700">Email</label><input type="email" name="email_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('email_address', $student->email_address) }}"></div>
                                <div><label class="block text-sm font-medium text-gray-700">Contact No.</label><input type="text" name="contact_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('contact_number', $student->contact_number) }}"></div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                                <div><label class="block text-sm font-medium text-gray-700">Region</label><input type="text" name="region" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('region', $student->region) }}"></div>
                                <div><label class="block text-sm font-medium text-gray-700">Province</label><input type="text" name="province" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('province', $student->province) }}"></div>
                                <div><label class="block text-sm font-medium text-gray-700">City/Municipality</label><input type="text" name="municipality_city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('municipality_city', $student->municipality_city) }}"></div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div><label class="block text-sm font-medium text-gray-700">Barangay</label><input type="text" name="barangay" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('barangay', $student->barangay) }}"></div>
                                <div><label class="block text-sm font-medium text-gray-700">Street</label><input type="text" name="street_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('street_address', $student->street_address) }}"></div>
                                <div><label class="block text-sm font-medium text-gray-700">Zip Code</label><input type="text" name="zip_code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('zip_code', $student->zip_code) }}"></div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-indigo-700 mb-4">6. Guardian Info</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div><label class="block text-sm font-medium text-gray-700">Name</label><input type="text" name="guardian_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('guardian_name', $student->guardian_name) }}"></div>
                                <div><label class="block text-sm font-medium text-gray-700">Relationship</label><input type="text" name="guardian_relationship" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('guardian_relationship', $student->guardian_relationship) }}"></div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div><label class="block text-sm font-medium text-gray-700">Contact No.</label><input type="text" name="guardian_contact" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('guardian_contact', $student->guardian_contact) }}"></div>
                                <div><label class="block text-sm font-medium text-gray-700">Address</label><input type="text" name="guardian_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('guardian_address', $student->guardian_address) }}"></div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end pt-6 border-t">
                            <a href="{{ route('students.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-md mr-4 hover:bg-gray-600">Cancel</a>
                            <button type="submit" class="bg-indigo-700 text-white px-8 py-2 rounded-md hover:bg-indigo-800 font-bold">Update Student</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function calculateAge() {
            var dob = document.getElementById('birthdate').value;
            if (dob) {
                var today = new Date();
                var birthDate = new Date(dob);
                var age = today.getFullYear() - birthDate.getFullYear();
                var m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) age--;
                document.getElementById('age').value = age;
            }
        }
    </script>
</x-app-layout>