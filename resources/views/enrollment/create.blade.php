<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>NASCENT SAS Application Form</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 font-sans antialiased">
        <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
            
            <div class="w-full max-w-5xl p-6 text-center">
                <img src="{{ asset('images/nas/nas-logo-spotlight.jpg') }}" class="h-24 mx-auto mb-4" alt="NAS Logo">
                
                <h1 class="text-3xl font-bold text-gray-800">National Academy of Sports</h1>
                <p class="text-gray-600">Student-Athlete Application</p>
                <p class="text-xs text-gray-500 mt-2">Based on SAIS Guidelines</p>
            </div>

            <div class="w-full max-w-5xl mt-6 px-8 py-10 bg-white shadow-lg overflow-hidden sm:rounded-lg mb-10 border-t-4 border-indigo-600">
                
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded text-center">
                        <h3 class="font-bold text-lg">Application Submitted!</h3>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded">
                        <p class="font-bold">Please fix the following errors:</p>
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admission.submit') }}" enctype="multipart/form-data">
                    @csrf 

                    <div class="mb-8 bg-gray-50 p-6 rounded border border-gray-200 flex items-center">
                        <div class="mr-6">
                            <div class="h-32 w-32 bg-gray-200 border-2 border-dashed border-gray-400 flex items-center justify-center text-gray-500 rounded-md overflow-hidden relative">
                                <span class="text-xs text-center px-2">2x2 Photo Preview</span>
                                <img id="preview" class="absolute inset-0 w-full h-full object-cover hidden">
                            </div>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Upload ID Picture (2x2 Formal)</label>
                            <p class="text-xs text-gray-500 mb-2">Required for Student Directory.</p>
                            <input type="file" name="id_picture" accept="image/*" required onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0]); document.getElementById('preview').classList.remove('hidden');" 
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
                        </div>
                    </div>

                    <h3 class="text-lg font-bold text-white bg-indigo-700 p-3 rounded mb-6">1. Applicant Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                        <div><label class="block text-sm font-medium text-gray-700">Last Name *</label><input type="text" name="last_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required value="{{ old('last_name') }}"></div>
                        <div><label class="block text-sm font-medium text-gray-700">First Name *</label><input type="text" name="first_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required value="{{ old('first_name') }}"></div>
                        <div><label class="block text-sm font-medium text-gray-700">Middle Name</label><input type="text" name="middle_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('middle_name') }}"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div><label class="block text-sm font-medium text-gray-700">Date of Birth *</label><input type="date" name="date_of_birth" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required value="{{ old('date_of_birth') }}"></div>
                        <div><label class="block text-sm font-medium text-gray-700">Sex *</label><select name="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required><option value="">Select</option><option value="Male">Male</option><option value="Female">Female</option></select></div>
                        <div><label class="block text-sm font-medium text-gray-700">LRN *</label><input type="text" name="lrn" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required value="{{ old('lrn') }}" placeholder="12-digit LRN"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div><label class="block text-sm font-medium text-gray-700">Place of Birth *</label><input type="text" name="birthplace" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required value="{{ old('birthplace') }}"></div>
                        <div><label class="block text-sm font-medium text-gray-700">Religion</label><input type="text" name="religion" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('religion') }}"></div>
                    </div>

                    <div class="mb-6 bg-blue-50 p-4 rounded border border-blue-200">
                        <span class="block text-sm font-bold text-gray-700 mb-3">Special Categories (Check if applicable):</span>
                        <div class="flex flex-wrap gap-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="is_ip" value="1" class="rounded text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('is_ip') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Indigenous People (IP)</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="is_pwd" value="1" class="rounded text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('is_pwd') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">PWD</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="is_4ps" value="1" class="rounded text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('is_4ps') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">4Ps Beneficiary</span>
                            </label>
                        </div>
                    </div>

                    <h3 class="text-lg font-bold text-white bg-indigo-700 p-3 rounded mb-6 mt-8">2. Address & Contact Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                        <div><label class="block text-sm font-medium text-gray-700">Region *</label><input type="text" name="region" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required value="{{ old('region') }}"></div>
                        <div><label class="block text-sm font-medium text-gray-700">Province *</label><input type="text" name="province" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required value="{{ old('province') }}"></div>
                        <div><label class="block text-sm font-medium text-gray-700">City/Municipality *</label><input type="text" name="municipality_city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required value="{{ old('municipality_city') }}"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div><label class="block text-sm font-medium text-gray-700">Barangay *</label><input type="text" name="barangay" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required value="{{ old('barangay') }}"></div>
                        <div><label class="block text-sm font-medium text-gray-700">Street</label><input type="text" name="street_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('street_address') }}"></div>
                        <div><label class="block text-sm font-medium text-gray-700">Zip Code</label><input type="text" name="zip_code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('zip_code') }}"></div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Applicant's Email Address (Required for Notification) *</label>
                        <input type="email" name="email_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required value="{{ old('email_address') }}">
                    </div>

                    <h3 class="text-lg font-bold text-white bg-indigo-700 p-3 rounded mb-6 mt-8">3. Parent / Guardian Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div><label class="block text-sm font-medium text-gray-700">Guardian Name *</label><input type="text" name="guardian_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required value="{{ old('guardian_name') }}"></div>
                        <div><label class="block text-sm font-medium text-gray-700">Relationship *</label><input type="text" name="guardian_relationship" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required value="{{ old('guardian_relationship') }}"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                         <div><label class="block text-sm font-medium text-gray-700">Guardian Contact No. *</label><input type="text" name="guardian_contact" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required value="{{ old('guardian_contact') }}"></div>
                        <div><label class="block text-sm font-medium text-gray-700">Guardian Email</label><input type="email" name="guardian_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('guardian_email') }}"></div>
                    </div>

                    <h3 class="text-lg font-bold text-white bg-indigo-700 p-3 rounded mb-6 mt-8">4. School & Sports Background</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div><label class="block text-sm font-medium text-gray-700">Sport and Subcategory *</label><input type="text" name="sport" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="e.g. Swimming - Freestyle" required value="{{ old('sport') }}"></div>
                        <div><label class="block text-sm font-medium text-gray-700">Previous School</label><input type="text" name="previous_school" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('previous_school') }}"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div><label class="block text-sm font-medium text-gray-700">School Type</label><select name="school_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><option value="Public">Public</option><option value="Private">Private</option></select></div>
                        <div class="bg-blue-50 p-3 rounded border border-blue-200">
                            <div class="flex items-center mt-2">
                                <input type="checkbox" name="has_palaro_participation" id="has_palaro" class="rounded text-indigo-600 shadow-sm" value="1">
                                <label for="has_palaro" class="ml-2 text-sm text-gray-900 font-bold">Participated in Palarong Pambansa / Batang Pinoy?</label>
                            </div>
                            <input type="text" name="palaro_year" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm text-sm" placeholder="If yes, what year?">
                        </div>
                    </div>

                    <h3 class="text-lg font-bold text-white bg-indigo-700 p-3 rounded mb-6 mt-8">5. Requirements Upload</h3>
                    <p class="text-sm text-gray-600 mb-4 italic">Please upload clear copies (PDF, JPG, PNG). Max 5MB per file.</p>
                    
                    <div class="grid grid-cols-1 gap-4 mb-8">
                        @php
                            $requirements = [
                                'scholarship_form' => 'Scholarship Application Forms',
                                'student_profile' => 'Student-Athlete Profile Form',
                                'coach_reco' => 'Coach’s Recommendation Form with valid ID',
                                'adviser_reco' => 'Adviser’s Recommendation Form with valid ID',
                                'medical_clearance' => 'Pre-participation Physical Evaluation Clearance Form',
                                'birth_cert' => 'Birth Certificate',
                                'report_card' => 'Report Card',
                                'guardian_id' => 'Valid ID of Designated Guardian',
                            ];
                        @endphp
                        @foreach($requirements as $key => $label)
                            <div class="bg-white p-4 rounded-md border border-gray-300 shadow-sm flex flex-col md:flex-row md:items-center justify-between hover:bg-gray-50 transition">
                                <label class="text-sm font-bold text-gray-700 mb-2 md:mb-0 md:w-2/3">{{ $label }}</label>
                                <input type="file" name="files[{{ $key }}]" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-10 flex items-center justify-between border-t pt-6">
                        <a href="{{ route('admission.track') }}" class="text-indigo-600 hover:underline text-sm font-bold">Check Application Status</a>
                        <button type="submit" class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white px-12 py-4 rounded-full font-bold text-xl shadow-xl transform transition hover:scale-105">
                            SUBMIT APPLICATION
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="mb-6 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} National Academy of Sports.<br>
                <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Authorized Personnel Login</a>
            </div>
        </div>
    </body>
</html>