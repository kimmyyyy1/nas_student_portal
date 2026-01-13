<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Student Profile') }}
            </h2>
            <a href="{{ route('students.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                &larr; Back to Directory
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- MAIN PROFILE CARD --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                
                {{-- COVER / HEADER --}}
                <div class="bg-gradient-to-r from-blue-900 to-indigo-800 h-32 relative"></div>
                
                <div class="px-8 pb-8">
                    <div class="relative flex items-end -mt-12 mb-6">
                        {{-- PROFILE PICTURE --}}
                        <div class="relative">
                            <img src="{{ $student->id_picture ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->first_name . ' ' . $student->last_name) . '&background=random' }}" 
                                 class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover bg-white" 
                                 alt="Profile">
                        </div>
                        
                        {{-- NAME & ID --}}
                        <div class="ml-6 mb-2">
                            <h1 class="text-3xl font-bold text-gray-900">{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}</h1>
                            <div class="flex items-center text-sm text-gray-500 mt-1">
                                <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-bold mr-2">{{ $student->nas_student_id }}</span>
                                <span>LRN: {{ $student->lrn }}</span>
                            </div>
                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="ml-auto mb-4 flex gap-2">
                            <a href="{{ route('students.edit', $student->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition shadow flex items-center">
                                <i class='bx bx-edit mr-2'></i> Edit Profile
                            </a>
                        </div>
                    </div>

                    {{-- DETAILS GRID --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
                        
                        {{-- LEFT COLUMN: ACADEMIC & SPORTS --}}
                        <div class="space-y-6">
                            <div class="bg-gray-50 p-5 rounded-lg border border-gray-100">
                                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4 border-b pb-2">Academic Info</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-xs text-gray-500">Grade Level</p>
                                        <p class="font-semibold text-gray-800">{{ $student->grade_level }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Section</p>
                                        <p class="font-semibold text-gray-800">{{ $student->section->section_name ?? 'Unassigned' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Adviser</p>
                                        <p class="font-semibold text-gray-800">{{ $student->section->adviser->name ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Status</p>
                                        <span class="inline-flex px-2 text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $student->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-5 rounded-lg border border-gray-100">
                                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4 border-b pb-2">Sports Info</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-xs text-gray-500">Team / Sport</p>
                                        <p class="font-semibold text-gray-800">{{ $student->team->team_name ?? 'No Team' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Category</p>
                                        <p class="font-semibold text-gray-800">{{ $student->team->sport_type ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- CENTER COLUMN: PERSONAL DETAILS --}}
                        <div class="bg-white p-5 rounded-lg border border-gray-200 md:col-span-2">
                            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                                <i class='bx bx-user mr-2 text-blue-500'></i> Personal Information
                            </h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-500">Sex</p>
                                    <p class="font-medium">{{ $student->sex }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Birthdate (Age)</p>
                                    <p class="font-medium">{{ \Carbon\Carbon::parse($student->birthdate)->format('F d, Y') }} ({{ \Carbon\Carbon::parse($student->birthdate)->age }} y/o)</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Birthplace</p>
                                    <p class="font-medium">{{ $student->birthplace }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Religion</p>
                                    <p class="font-medium">{{ $student->religion }}</p>
                                </div>
                                
                                <div class="sm:col-span-2 border-t pt-4 mt-2">
                                    <p class="text-sm text-gray-500 mb-1">Address</p>
                                    <p class="font-medium">
                                        {{ $student->street_address }}, {{ $student->barangay }}, {{ $student->municipality_city }}, {{ $student->province }}, {{ $student->region }} {{ $student->zip_code }}
                                    </p>
                                </div>
                            </div>

                            <h3 class="text-lg font-bold text-gray-800 mt-8 mb-6 flex items-center">
                                <i class='bx bx-phone mr-2 text-blue-500'></i> Contact & Guardian
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-500">Student Email</p>
                                    <p class="font-medium text-blue-600">{{ $student->email_address }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Student Contact</p>
                                    <p class="font-medium">{{ $student->contact_number ?? 'N/A' }}</p>
                                </div>
                                <div class="sm:col-span-2 border-t border-dashed pt-4"></div>
                                <div>
                                    <p class="text-sm text-gray-500">Guardian Name</p>
                                    <p class="font-medium">{{ $student->guardian_name }} ({{ $student->guardian_relationship }})</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Guardian Contact</p>
                                    <p class="font-medium">{{ $student->guardian_contact }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>