<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title flex border-none">
            Finalize Enrollment: {{ $application->last_name }}, {{ $application->first_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('students.enrollment') }}" class="text-gray-500 hover:text-gray-700 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Enrollment Manager
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <div class="premium-card !p-0 overflow-hidden">
                    <div class="p-6 md:p-8 text-slate-800 border-t border-white/40">
                        <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Submitted Enrollment Requirements
                        </h3>
                        
                        @if(!empty($application->enrollment_files))
                            <div class="grid grid-cols-1 gap-3">
                                @foreach($application->enrollment_files as $key => $path)
                                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded border border-gray-200 hover:bg-blue-50 transition">
                                        <div>
                                            <span class="text-sm font-bold text-gray-800 block">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                            <span class="text-xs text-gray-500">Uploaded via Applicant Portal</span>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ asset('storage/' . $path) }}" target="_blank" class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-bold hover:bg-blue-200 transition">
                                                View
                                            </a>
                                            <a href="{{ asset('storage/' . $path) }}" download class="text-xs bg-gray-200 text-gray-700 px-3 py-1 rounded-full font-bold hover:bg-gray-300 transition">
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-4 bg-red-50 text-red-600 rounded border border-red-200 italic text-sm">
                                No enrollment files submitted yet.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="premium-card !p-0 overflow-hidden">
                    <div class="p-6 md:p-8 text-slate-800 border-t border-white/40">
                        <h3 class="text-lg font-bold text-green-800 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002 2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            Confirm Official Enrollment
                        </h3>

                        <div class="mb-4 text-sm text-gray-600">
                            <p>By proceeding, you confirm that the submitted documents are valid. This action will:</p>
                            <ul class="list-disc list-inside ml-2 mt-1">
                                <li>Create an <strong>Official Student Record</strong>.</li>
                                <li>Generate a <strong>Student Portal Account</strong>.</li>
                                <li>Send credentials to the student via email.</li>
                            </ul>
                        </div>

                        <form method="POST" action="{{ route('admission.process', $application->id) }}">
                            @csrf
                            @method('PATCH')
                            
                            <input type="hidden" name="status" value="Enrolled">

                            <div class="bg-green-50 p-5 rounded border border-green-100 mb-6">
                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Assign NAS Student ID *</label>
                                    <input type="text" name="nas_student_id" class="w-full rounded-md border-gray-300 shadow-sm text-sm font-mono" 
                                           value="{{ 'NAS-' . date('Y') . '-' . str_pad(\App\Models\Student::count() + 1, 4, '0', STR_PAD_LEFT) }}" required>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Assign Section *</label>
                                    <select name="section_id" class="w-full rounded-md border-gray-300 shadow-sm text-sm" required>
                                        <option value="">-- Select Section --</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->grade_level }} - {{ $section->section_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-green-700 hover:bg-green-800 text-white font-bold py-3 px-4 rounded shadow-lg transform transition hover:scale-105">
                                CONFIRM & ENROLL STUDENT
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>