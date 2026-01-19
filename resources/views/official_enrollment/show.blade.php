<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Process Official Enrollment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                    <p class="font-bold">Please fix the following errors:</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-2 space-y-6">
                    
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
                        <div class="bg-blue-900 px-6 py-4 border-b border-blue-800 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-white uppercase tracking-wide">Applicant Information</h3>
                            <span class="bg-blue-700 text-white text-xs px-2 py-1 rounded border border-blue-500">
                                Qualified
                            </span>
                        </div>
                        
                        <div class="p-6">
                            <div class="flex flex-col sm:flex-row items-center sm:items-start mb-6 gap-6">
                                <div class="w-32 h-32 bg-gray-200 rounded-md overflow-hidden border-2 border-gray-300 flex-shrink-0">
                                    {{-- 👇 FIXED IMAGE: Removed asset('storage/...') --}}
                                    @if(isset($application->uploaded_files['id_picture']))
                                        <img src="{{ $application->uploaded_files['id_picture'] }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 font-bold">NO PHOTO</div>
                                    @endif
                                </div>

                                <div class="text-center sm:text-left">
                                    <h2 class="text-3xl font-extrabold text-gray-800 uppercase">{{ $application->last_name }}, {{ $application->first_name }}</h2>
                                    <p class="text-gray-500 font-bold text-lg mb-2">{{ $application->middle_name ?? '' }}</p>
                                    
                                    <div class="inline-flex flex-col items-start gap-1">
                                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded text-sm font-mono border border-gray-200">
                                            LRN: {{ $application->lrn }}
                                        </span>
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded text-xs font-bold border border-green-200 uppercase">
                                            Applying for: {{ $application->grade_level_applied }} ({{ $application->sport }})
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 text-sm text-gray-700 border-t pt-4">
                                <div><span class="text-xs text-gray-500 uppercase font-bold">Email:</span> {{ $application->email_address }}</div>
                                <div><span class="text-xs text-gray-500 uppercase font-bold">Contact:</span> {{ $application->guardian_contact }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
                        <div class="bg-gray-100 px-6 py-4 border-b border-gray-200 flex items-center">
                            <svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <h3 class="text-lg font-bold text-gray-800">Enrollment Documents Review</h3>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-gray-600 mb-4">Review the latest uploaded files by the applicant before confirming enrollment. Check physical documents when available.</p>
                            
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                                @php
                                    // List of all expected files
                                    $requiredDocs = [
                                        'sf10' => 'SF10 / Form 137',
                                        'good_moral' => 'Good Moral Certificate',
                                        'psa_birth_cert' => 'PSA Birth Certificate',
                                        'medical_cert' => 'Medical Certificate',
                                        'id_picture' => '2x2 ID Picture'
                                    ];
                                @endphp

                                @foreach($requiredDocs as $key => $label)
                                    <div class="border-b pb-2">
                                        <dt class="text-xs font-bold text-gray-500 uppercase">{{ $label }}</dt>
                                        @if(isset($application->uploaded_files[$key]))
                                            <dd class="mt-1 flex justify-between items-center text-green-700 font-medium">
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    AVAILABLE (Uploaded)
                                                </span>
                                                {{-- 👇 FIXED LINK: Removed asset('storage/...') --}}
                                                <a href="{{ $application->uploaded_files[$key] }}" target="_blank" class="text-blue-600 hover:underline text-xs font-bold px-2 py-0.5 rounded bg-blue-50">
                                                    VIEW FILE
                                                </a>
                                            </dd>
                                        @else
                                            <dd class="mt-1 text-red-500 font-medium italic flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                MISSING / PENDING
                                            </dd>
                                        @endif
                                    </div>
                                @endforeach
                            </dl>

                            @if($application->assessment_score)
                                <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 rounded text-sm">
                                    <strong>Assessment Note:</strong> {{ $application->assessment_score }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="md:col-span-1">
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden border-t-4 border-green-500 sticky top-6">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">Finalize Enrollment</h3>
                            <p class="text-sm text-gray-600 mb-6">Assign a section and confirm to officially enroll this student.</p>

                            <form method="POST" action="{{ route('official-enrollment.store', $application->id) }}">
                                @csrf
                                
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Generated Student ID</label>
                                    <input type="text" value="{{ date('Y') }}-{{ str_pad($application->id, 4, '0', STR_PAD_LEFT) }}" readonly class="w-full bg-gray-100 border-gray-300 rounded text-gray-500 font-mono font-bold cursor-not-allowed">
                                </div>

                                <div class="mb-6">
                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Assign Section <span class="text-red-500">*</span></label>
                                    <select name="section_id" required class="w-full border-gray-300 rounded focus:border-green-500 focus:ring-green-500 text-sm">
                                        <option value="">-- Select Section --</option>
                                        @forelse($sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->section_name }} ({{ $section->grade_level }})</option>
                                        @empty
                                            <option value="" disabled>No sections found for {{ $application->grade_level_applied }}</option>
                                        @endforelse
                                    </select>
                                    @error('section_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" onclick="return confirm('WARNING: Are you sure you want to officially enroll this student? Ensure all documents are complete.')" 
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded shadow-lg transform transition hover:-translate-y-0.5">
                                    CONFIRM & ENROLL STUDENT
                                </button>
                            </form>
                            </div>
                        <div class="bg-gray-50 px-6 py-3 border-t text-center">
                            <a href="{{ route('students.enrollment') }}" class="text-sm text-gray-500 hover:text-gray-700 font-bold hover:underline">← Back to Qualified List</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>