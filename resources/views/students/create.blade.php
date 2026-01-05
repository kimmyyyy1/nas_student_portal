<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Student') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 p-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="md:col-span-2 bg-gray-50 p-4 rounded border">
                                <h3 class="font-bold text-gray-700 mb-4">Identification</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase">Student ID</label>
                                        <input type="text" name="nas_student_id" value="{{ old('nas_student_id') }}" class="w-full border-gray-300 rounded mt-1" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase">LRN</label>
                                        <input type="text" name="lrn" value="{{ old('lrn') }}" class="w-full border-gray-300 rounded mt-1" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase">Status</label>
                                        <select name="status" class="w-full border-gray-300 rounded mt-1">
                                            <option value="New">New</option>
                                            <option value="Continuing">Continuing</option>
                                            <option value="Transfer out">Transfer Out</option>
                                            <option value="Graduate">Graduate</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase">Last Name</label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" class="w-full border-gray-300 rounded mt-1" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase">First Name</label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" class="w-full border-gray-300 rounded mt-1" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase">Middle Name</label>
                                <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="w-full border-gray-300 rounded mt-1">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase">Email Address</label>
                                <input type="email" name="email_address" value="{{ old('email_address') }}" class="w-full border-gray-300 rounded mt-1" required>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase">Sex</label>
                                    <select name="sex" class="w-full border-gray-300 rounded mt-1">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase">Birthdate</label>
                                    <input type="date" name="birthdate" value="{{ old('birthdate') }}" class="w-full border-gray-300 rounded mt-1" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase">Birthplace</label>
                                <input type="text" name="birthplace" value="{{ old('birthplace') }}" class="w-full border-gray-300 rounded mt-1" required>
                            </div>

                            <div class="md:col-span-2">
                                <h3 class="font-bold text-gray-700 mt-4 mb-2">Address</h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <input type="text" name="region" placeholder="Region" class="border-gray-300 rounded" value="Region III" required>
                                    <input type="text" name="province" placeholder="Province" class="border-gray-300 rounded" required>
                                    <input type="text" name="municipality_city" placeholder="City/Municipality" class="border-gray-300 rounded" required>
                                    <input type="text" name="barangay" placeholder="Barangay" class="border-gray-300 rounded" required>
                                </div>
                                <input type="text" name="street_address" placeholder="Street / House No." class="w-full border-gray-300 rounded mt-2">
                            </div>

                            <div class="md:col-span-2 bg-blue-50 p-4 rounded border border-blue-100">
                                <h3 class="font-bold text-blue-800 mb-4">Academic & Sports</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase">Grade Level</label>
                                        <select name="grade_level" class="w-full border-gray-300 rounded mt-1">
                                            <option value="Grade 7">Grade 7</option>
                                            <option value="Grade 8">Grade 8</option>
                                            <option value="Grade 9">Grade 9</option>
                                            <option value="Grade 10">Grade 10</option>
                                            <option value="Grade 11">Grade 11</option>
                                            <option value="Grade 12">Grade 12</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase">Section</label>
                                        <select name="section_id" class="w-full border-gray-300 rounded mt-1">
                                            <option value="">-- Select Section --</option>
                                            @foreach($sections as $section)
                                                <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase">Team / Sport</label>
                                        <select name="team_id" class="w-full border-gray-300 rounded mt-1">
                                            <option value="">-- Select Team --</option>
                                            @foreach($teams as $team)
                                                <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <h3 class="font-bold text-gray-700 mb-2">Guardian Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <input type="text" name="guardian_name" placeholder="Guardian Name" class="border-gray-300 rounded" required>
                                    <input type="text" name="guardian_relationship" placeholder="Relationship" class="border-gray-300 rounded" required>
                                    <input type="text" name="guardian_contact" placeholder="Contact No." class="border-gray-300 rounded" required>
                                </div>
                            </div>

                        </div> <div class="mt-6 flex justify-end">
                            <a href="{{ route('students.index') }}" class="mr-4 text-gray-600 hover:text-gray-900 font-bold">Cancel</a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">
                                Save Student
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>