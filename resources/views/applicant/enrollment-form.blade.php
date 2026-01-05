<x-applicant-layout>
    <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-10">
            <img src="{{ asset('images/nas/nas-logo-spotlight.jpg') }}" class="h-20 mx-auto mb-4 drop-shadow-md rounded-full" alt="NAS Logo">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">National Academy of Sports</h1>
            <p class="text-lg text-gray-600 mt-1 font-medium">Official Enrollment Submission</p>
            <p class="text-xs text-green-600 mt-1 font-bold uppercase tracking-wide">Phase 2: For Qualified Applicants</p>
        </div>

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-200">
            <div class="h-2 bg-green-600 w-full"></div>

            <div class="p-8 md:p-12 text-gray-900">
                
                <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-md shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm leading-5 font-medium text-green-800">Congratulations on qualifying!</h3>
                            <div class="mt-2 text-sm leading-5 text-green-700">
                                <p>To finalize your enrollment and receive your Student Portal credentials, please submit the required original documents below.</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-md text-sm shadow-sm">
                        <p class="font-bold mb-2">Please fix the following errors:</p>
                        <ul class="list-disc list-inside ml-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('applicant.submit_enrollment') }}" enctype="multipart/form-data">
                    @csrf 

                    <div class="mb-10">
                        <h3 class="text-xl font-bold text-gray-800 border-b-2 border-gray-200 pb-2 mb-6 flex items-center">
                            <span class="bg-green-600 text-white rounded-full h-8 w-8 flex items-center justify-center text-sm mr-3">✓</span> 
                            Enrollment Requirements
                        </h3>
                        <p class="text-sm text-gray-600 mb-6 italic bg-yellow-50 p-3 rounded border-l-4 border-yellow-400">
                            Please upload clear scanned copies or photos (PDF, JPG, PNG). Max 10MB per file.
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @php
                                $enrollmentReqs = [
                                    'form_138' => 'Original Report Card (Form 138)',
                                    'psa_original' => 'Original PSA Birth Certificate',
                                    'good_moral' => 'Certificate of Good Moral Character',
                                    'med_cert_original' => 'Original Medical Certificate',
                                ];
                            @endphp

                            @foreach($enrollmentReqs as $key => $label)
                                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 shadow-sm hover:border-green-400 hover:shadow-md transition-all duration-200 flex flex-col">
                                    <label class="text-sm font-bold text-gray-800 mb-3 block">{{ $label }} <span class="text-red-500">*</span></label>
                                    
                                    <input type="file" name="enrollment_files[{{ $key }}]" required 
                                           class="block w-full text-sm text-slate-600 
                                        file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 
                                        file:text-xs file:font-bold file:bg-green-600 file:text-white 
                                        hover:file:bg-green-700 cursor-pointer" 
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-center pt-4 pb-8">
                        <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-12 py-4 rounded-full font-bold text-xl shadow-lg transform transition hover:scale-105 focus:outline-none focus:ring-4 focus:ring-green-300 uppercase tracking-wide">
                            SUBMIT ENROLLMENT
                        </button>
                    </div>
                    
                    <div class="text-center">
                         <a href="{{ route('applicant.dashboard') }}" class="text-sm text-gray-500 hover:text-red-600 underline cursor-pointer font-medium">
                             Cancel & Go Back to Dashboard
                         </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-applicant-layout>