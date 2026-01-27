<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <a href="{{ route('official-enrollment.index') }}" class="text-indigo-600 hover:text-indigo-800">Enrollment</a> &rsaquo; Process
            </h2>
            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold uppercase tracking-wide border border-green-200">
                Status: Qualified
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ALERTS --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex items-center">
                    <i class='bx bx-check-circle text-2xl mr-2'></i>
                    {!! session('success') !!}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm flex items-center">
                    <i class='bx bx-error-circle text-2xl mr-2'></i>
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- LEFT COLUMN: STUDENT INFO & DOCUMENTS REVIEW --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- 1. STUDENT PROFILE CARD --}}
                    <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200 flex flex-col sm:flex-row items-center sm:items-start gap-6">
                        {{-- Profile Picture Logic --}}
                        <div class="flex-shrink-0">
                            @php
                                // 1. Retrieve the 'uploaded_files' data safely
                                $files = $application->uploaded_files; 
                                
                                // 2. Check for 'id_picture' key in array or property in object
                                $idPic = null;
                                if (is_array($files) && isset($files['id_picture'])) {
                                    $idPic = $files['id_picture'];
                                } elseif (is_object($files) && isset($files->id_picture)) {
                                    $idPic = $files->id_picture;
                                }
                            @endphp

                            <div class="h-24 w-24 rounded-full border-4 border-indigo-100 overflow-hidden shadow-sm bg-gray-100 relative group">
                                @if($idPic)
                                    {{-- TRY 1: Use the Proxy Route (Secure) --}}
                                    <img class="w-full h-full object-cover" 
                                         src="{{ route('applicant.view_file', ['id' => $application->id, 'type' => 'id_picture']) }}" 
                                         alt="ID Photo"
                                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($application->first_name . '+' . $application->last_name) }}&background=6366f1&color=fff&size=128&bold=true';">
                                @else
                                    {{-- FALLBACK: Initials if NO data exists at all --}}
                                    <div class="w-full h-full flex items-center justify-center bg-indigo-500 text-white font-bold text-2xl">
                                        {{ substr($application->first_name, 0, 1) }}{{ substr($application->last_name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Details --}}
                        <div class="flex-1 text-center sm:text-left">
                            <h1 class="text-2xl font-extrabold text-gray-900 leading-tight">
                                {{ $application->last_name }}, {{ $application->first_name }}
                            </h1>
                            <div class="flex flex-wrap justify-center sm:justify-start gap-2 mt-2">
                                <span class="bg-indigo-50 text-indigo-700 border border-indigo-200 py-1 px-3 rounded-md text-xs font-bold uppercase tracking-wide">
                                    {{ $application->grade_level_applied }}
                                </span>
                                <span class="bg-orange-50 text-orange-700 border border-orange-200 py-1 px-3 rounded-md text-xs font-bold uppercase tracking-wide">
                                    {{ $application->sport }}
                                </span>
                            </div>
                            
                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm text-gray-600">
                                <div class="flex items-center justify-center sm:justify-start">
                                    <i class='bx bx-envelope mr-2 text-gray-400'></i> {{ $application->email_address }}
                                </div>
                                <div class="flex items-center justify-center sm:justify-start">
                                    <i class='bx bx-phone mr-2 text-gray-400'></i> {{ $application->guardian_contact ?? 'N/A' }}
                                </div>
                                <div class="flex items-center justify-center sm:justify-start sm:col-span-2">
                                    <i class='bx bx-id-card mr-2 text-gray-400'></i> LRN: <span class="font-mono font-bold ml-1">{{ $application->lrn }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. DOCUMENT REVIEW SECTION --}}
                    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800 flex items-center">
                                <i class='bx bx-file-find text-xl mr-2 text-indigo-600'></i> Enrollment Documents Review
                            </h3>
                        </div>
                        
                        {{-- FORM: RETURN TO APPLICANT --}}
                        <form action="{{ route('official-enrollment.return', $application->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="p-6 space-y-4">
                                @php
                                    $docs = [
                                        'sf10' => 'Form 137 / SF10',
                                        'good_moral' => 'Good Moral Certificate',
                                        'psa_birth_cert' => 'PSA Birth Certificate',
                                        'medical_cert' => 'Medical Certificate',
                                        'coach_reco' => 'Coach Recommendation',
                                        'id_picture' => '2x2 ID Picture'
                                    ];
                                    $existingRemarks = $application->document_remarks ?? [];
                                    
                                    // Retrieve files again for loop usage
                                    $filesLoop = $application->uploaded_files;
                                @endphp

                                @foreach($docs as $key => $label)
                                    @php
                                        // Robust check for file existence
                                        $fileUrl = null;
                                        if (is_array($filesLoop) && isset($filesLoop[$key])) {
                                            $fileUrl = $filesLoop[$key];
                                        } elseif (is_object($filesLoop) && isset($filesLoop->$key)) {
                                            $fileUrl = $filesLoop->$key;
                                        }
                                        
                                        $isUploaded = !empty($fileUrl);
                                        $remarkVal = $existingRemarks[$key] ?? '';
                                    @endphp
                                    
                                    {{-- DOCUMENT ROW --}}
                                    <div class="flex flex-col sm:flex-row items-center gap-4 p-4 border rounded-lg transition hover:bg-gray-50 {{ $remarkVal ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">
                                        
                                        {{-- 1. Document Name & Status --}}
                                        <div class="w-full sm:w-1/3">
                                            <p class="text-sm font-bold text-gray-800">{{ $label }}</p>
                                            <div class="mt-1">
                                                @if($isUploaded)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800">
                                                        <i class='bx bx-check mr-1'></i> UPLOADED
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-800">
                                                        <i class='bx bx-x mr-1'></i> MISSING
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- 2. View Button --}}
                                        <div class="w-full sm:w-auto flex-shrink-0 text-center">
                                            @if($isUploaded)
                                                <a href="{{ route('applicant.view_file', ['id' => $application->id, 'type' => $key]) }}" 
                                                   target="_blank" 
                                                   class="inline-flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-800 border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded transition">
                                                    <i class='bx bx-show mr-1'></i> VIEW
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400 italic">No file</span>
                                            @endif
                                        </div>

                                        {{-- 3. Remarks Input --}}
                                        <div class="w-full flex-1">
                                            <div class="relative">
                                                <input type="text" name="remarks[{{ $key }}]" value="{{ $remarkVal }}" 
                                                    class="w-full text-xs border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 pl-8 py-2"
                                                    placeholder="Type remark here if invalid (e.g. 'Blurred image')...">
                                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                                    <i class='bx bx-edit text-gray-400'></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                                <span class="text-xs text-gray-500 italic">If everything is correct, ignore remarks and proceed to enroll on the right.</span>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-red-600 uppercase tracking-widest shadow-sm hover:bg-red-50 hover:border-red-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <i class='bx bx-reply mr-2'></i> Return for Correction
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- RIGHT COLUMN: ACTION PANEL --}}
                <div class="lg:col-span-1">
                    <div class="bg-white shadow-xl rounded-xl border border-indigo-100 sticky top-6 overflow-hidden">
                        
                        <div class="bg-indigo-600 px-6 py-4">
                            <h3 class="text-lg font-bold text-white flex items-center">
                                <i class='bx bxs-graduation mr-2'></i> Finalize Enrollment
                            </h3>
                        </div>

                        <div class="p-6">
                            <p class="text-xs text-gray-600 mb-6 bg-indigo-50 p-3 rounded border border-indigo-100">
                                Ensure all documents are valid. This action will create a permanent student record and generate a user account.
                            </p>

                            {{-- FORM: CONFIRM ENROLLMENT --}}
                            <form action="{{ route('official-enrollment.store', $application->id) }}" method="POST">
                                @csrf
                                
                                {{-- Generated ID --}}
                                <div class="mb-5">
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Student ID (Generated)</label>
                                    <div class="flex items-center bg-gray-100 p-3 rounded border border-gray-300">
                                        <i class='bx bx-barcode text-gray-500 mr-2 text-lg'></i>
                                        <span class="font-mono font-bold text-gray-800 text-lg">
                                            {{ date('Y') }}-{{ str_pad($application->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Section Selection --}}
                                <div class="mb-6">
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Assign Section <span class="text-red-500">*</span></label>
                                    <select name="section_id" required class="block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                                        <option value="" disabled selected>-- Choose Section --</option>
                                        @forelse($sections as $section)
                                            <option value="{{ $section->id }}">
                                                {{ $section->section_name }} ({{ $section->adviser_name ?? 'No Adviser' }})
                                            </option>
                                        @empty
                                            <option value="" disabled>No sections available</option>
                                        @endforelse
                                    </select>
                                    @error('section_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Submit Button --}}
                                <button type="submit" onclick="return confirm('WARNING: Are you sure? This will officially enroll the student.')" 
                                    class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md transition transform hover:-translate-y-0.5 flex justify-center items-center group">
                                    <span>CONFIRM & ENROLL</span>
                                    <i class='bx bx-right-arrow-alt ml-2 text-xl group-hover:translate-x-1 transition'></i>
                                </button>
                            </form>

                            <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                                <a href="{{ route('official-enrollment.index') }}" class="text-xs text-gray-500 hover:text-indigo-600 font-bold transition flex items-center justify-center">
                                    <i class='bx bx-left-arrow-alt mr-1'></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>