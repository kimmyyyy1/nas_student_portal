<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Student Profile') }}
            </h2>
            {{-- DESKTOP BACK BUTTON --}}
            <a href="{{ route('students.index', $queryParams ?? []) }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-700 flex items-center">
                <i class='bx bx-arrow-back mr-1'></i> Back to Directory
            </a>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- 🟢 MOBILE BACK BUTTON (Visible only on mobile) --}}
            {{-- Ito ang solusyon para makabalik ka kapag naka-mobile --}}
            <div class="block md:hidden mb-4">
                <a href="{{ route('students.index', $queryParams ?? []) }}" wire:navigate 
                   class="flex items-center text-sm font-bold text-gray-600 hover:text-indigo-600 transition">
                    <div class="bg-white rounded-full p-1 shadow-sm mr-2 flex items-center justify-center border border-gray-200">
                        <i class='bx bx-chevron-left text-2xl'></i> 
                    </div>
                    Back to Directory
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-200 relative pb-10">
                
                {{-- COVER IMAGE --}}
                <div class="bg-gradient-to-r from-blue-900 to-indigo-800 h-32 md:h-48 relative z-0">
                    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                </div>
                
                <div class="px-4 md:px-8 relative z-10">
                    
                    {{-- EDIT BUTTON (Unified Logic) --}}
                    {{-- Mobile: Block sa baba. Desktop (md): Absolute sa Right Top. --}}
                    <div class="mt-6 w-full md:w-auto md:absolute md:top-6 md:right-8 md:mt-0 z-50">
                        <a href="{{ route('students.edit', ['student' => $student->id] + ($queryParams ?? [])) }}" wire:navigate
                           class="inline-flex items-center justify-center w-full md:w-auto px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md cursor-pointer">
                            <i class='bx bx-edit text-lg mr-2'></i> Edit Profile
                        </a>
                    </div>

                    {{-- PROFILE HEADER --}}
                    {{-- Adjusted margin for overlap effect --}}
                    <div class="relative flex flex-col md:flex-row items-center md:items-end -mt-16 md:-mt-20 mb-8">
                        
                        {{-- Profile Picture --}}
                        <div class="relative group z-20">
                            <img src="{{ $student->id_picture ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->first_name . ' ' . $student->last_name) . '&background=random&size=256' }}" 
                                 class="w-32 h-32 md:w-44 md:h-44 rounded-full border-4 border-white shadow-xl object-cover bg-white" 
                                 alt="Profile">
                            
                            {{-- Status Dot --}}
                            <div class="absolute bottom-2 right-2 w-6 h-6 rounded-full border-2 border-white {{ $student->status === 'Enrolled' ? 'bg-green-500' : 'bg-gray-400' }}" title="{{ $student->status }}"></div>
                        </div>
                        
                        {{-- Name & Details --}}
                        {{-- md:mt-12 ensures name sits on white background on Desktop --}}
                        <div class="mt-4 md:mt-12 md:ml-6 mb-1 text-center md:text-left w-full md:w-auto z-10 flex-1 md:pb-2">
                            
                            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 leading-tight break-words px-2 md:px-0">
                                {{ $student->last_name }}, {{ $student->first_name }} 
                                <span class="text-gray-500 font-normal text-lg md:text-xl block md:inline">{{ $student->middle_name }}</span>
                            </h1>
                            
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mt-2 text-sm text-gray-600">
                                <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-mono font-bold border border-blue-100 flex items-center shadow-sm">
                                    <i class='bx bx-id-card mr-1'></i> {{ $student->nas_student_id }}
                                </span>
                                <span class="flex items-center text-gray-500 font-medium">
                                    <i class='bx bx-barcode mr-1'></i> LRN: {{ $student->lrn }}
                                </span>
                            </div>

                        </div>
                    </div>

                    {{-- DETAILS GRID --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 border-t border-gray-100 pt-8 relative z-0">
                        
                        {{-- LEFT COLUMN: INFO CARDS --}}
                        <div class="space-y-6">
                            {{-- ACADEMIC INFO --}}
                            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition">
                                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2 flex items-center">
                                    <i class='bx bx-book-reader mr-2 text-indigo-500 text-lg'></i> Academic Info
                                </h3>
                                <div class="space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Grade Level</span>
                                        <span class="font-bold text-gray-800">{{ $student->grade_level }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Section</span>
                                        <span class="font-bold text-gray-800">{{ $student->section->section_name ?? 'Unassigned' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-500">Adviser</span>
                                        <span class="text-sm font-medium text-indigo-600 text-right">
                                            @if($student->section && $student->section->adviser)
                                                {{ $student->section->adviser->first_name }} {{ $student->section->adviser->last_name }}
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center pt-2">
                                        <span class="text-sm text-gray-500">Status</span>
                                        <span class="px-2 py-1 text-xs font-bold rounded-full {{ $student->status === 'Enrolled' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $student->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- SPORTS INFO --}}
                            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition">
                                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2 flex items-center">
                                    <i class='bx bx-trophy mr-2 text-yellow-500 text-lg'></i> Sports Info
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Team / Sport</p>
                                        <p class="font-bold text-gray-800 text-lg">{{ $student->team->team_name ?? 'No Team Assigned' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Category / Type</p>
                                        <p class="font-medium text-gray-700">{{ $student->team->sport_type ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT COLUMN: PERSONAL DETAILS --}}
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm md:col-span-2">
                            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center pb-2 border-b border-gray-100">
                                <span class="bg-indigo-100 p-2 rounded-lg mr-3 text-indigo-600">
                                    <i class='bx bx-user'></i>
                                </span>
                                Personal Information
                            </h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-500 font-bold mb-1">Sex / Gender</p>
                                    <p class="font-medium text-gray-900">{{ $student->sex }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-500 font-bold mb-1">Birthdate (Age)</p>
                                    <p class="font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($student->birthdate)->format('F d, Y') }} 
                                        <span class="text-gray-500 ml-1">({{ \Carbon\Carbon::parse($student->birthdate)->age }} yrs old)</span>
                                    </p>
                                </div>
                                <div class="sm:col-span-2">
                                    <p class="text-xs uppercase tracking-wide text-gray-500 font-bold mb-1">Birthplace</p>
                                    <p class="font-medium text-gray-900">{{ $student->birthplace }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-500 font-bold mb-1">Religion</p>
                                    <p class="font-medium text-gray-900">{{ $student->religion }}</p>
                                </div>

                                {{-- IP, PWD, 4Ps SECTION --}}
                                <div class="sm:col-span-2 mt-2 pt-4 border-t border-gray-100 border-dashed">
                                    <div class="flex flex-wrap gap-y-3 gap-x-6">
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-4 w-4" disabled {{ $student->is_ip ? 'checked' : '' }}>
                                            <span class="text-xs font-bold text-gray-600 uppercase">Indigenous People (IP)</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-4 w-4" disabled {{ $student->is_pwd ? 'checked' : '' }}>
                                            <span class="text-xs font-bold text-gray-600 uppercase">PWD</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-4 w-4" disabled {{ $student->is_4ps ? 'checked' : '' }}>
                                            <span class="text-xs font-bold text-gray-600 uppercase">4Ps Beneficiary</span>
                                        </label>
                                        <div class="flex items-center space-x-2 ml-auto sm:ml-0">
                                            <span class="text-xs font-bold text-gray-600 uppercase">Others:</span>
                                            <span class="border-b border-gray-400 w-32 inline-block h-4"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="sm:col-span-2 bg-gray-50 p-4 rounded-lg border border-gray-100 mt-2">
                                    <p class="text-xs uppercase tracking-wide text-gray-500 font-bold mb-1 flex items-center">
                                        <i class='bx bx-map mr-1'></i> Home Address
                                    </p>
                                    <p class="font-medium text-gray-800">
                                        {{ $student->street_address }}, {{ $student->barangay }}, {{ $student->municipality_city }}, {{ $student->province }}
                                        @if($student->zip_code) <span class="text-gray-500">({{ $student->zip_code }})</span> @endif
                                    </p>
                                </div>
                            </div>

                            <h3 class="text-lg font-bold text-gray-800 mt-10 mb-6 flex items-center pb-2 border-b border-gray-100">
                                <span class="bg-blue-100 p-2 rounded-lg mr-3 text-blue-600">
                                    <i class='bx bx-phone-call'></i>
                                </span>
                                Contact & Emergency Info
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-500 font-bold mb-1">Student Email</p>
                                    <a href="mailto:{{ $student->email_address }}" class="font-medium text-blue-600 hover:underline flex items-center break-all">
                                        {{ $student->email_address }}
                                    </a>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-500 font-bold mb-1">Contact Number</p>
                                    <p class="font-medium text-gray-900 font-mono">{{ $student->contact_number ?? 'N/A' }}</p>
                                </div>
                                
                                <div class="sm:col-span-2 pt-2">
                                    <div class="bg-orange-50 border border-orange-100 rounded-lg p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-orange-600 font-bold mb-1">Guardian / Emergency Contact</p>
                                            <p class="font-bold text-gray-900 text-lg">{{ $student->guardian_name }}</p>
                                            <p class="text-sm text-gray-600">{{ $student->guardian_relationship }}</p>
                                        </div>
                                        <div class="w-full sm:w-auto">
                                            <a href="tel:{{ $student->guardian_contact }}" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-white border border-orange-200 rounded-md font-semibold text-xs text-orange-700 uppercase tracking-widest shadow-sm hover:bg-orange-50 transition">
                                                <i class='bx bxs-phone mr-2 text-lg'></i> {{ $student->guardian_contact }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>