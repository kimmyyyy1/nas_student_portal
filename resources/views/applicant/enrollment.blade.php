<x-applicant-layout>
    <div class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8 font-sans bg-pattern">
        
        <div class="max-w-6xl mx-auto">
            
            {{-- HEADER SECTION --}}
            <div class="text-center mb-12 animate-fade-in-down">
                <div class="inline-flex items-center justify-center p-3 bg-indigo-100 rounded-full mb-4 shadow-inner">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 uppercase tracking-tighter leading-tight">
                    Official <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Enrollment Form</span>
                </h1>
                <p class="text-slate-500 font-medium mt-3 max-w-2xl mx-auto">
                    Please review and complete your information below to finalize your enrollment.
                </p>
            </div>

            {{-- MASTER FORM WRAPPER (Initialize AlpineJS) --}}
            <form action="{{ route('applicant.enrollment.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  class="space-y-10"
                  x-data="{ 
                      sport: '{{ old('sport', $applicant->sport) }}',
                      gradeLevel: '{{ old('grade_level') }}', // ✅ ADDED: Track Grade Level
                      isIp: '{{ old('is_ip', $applicant->is_ip ? 'Yes' : 'No') }}',
                      isPwd: '{{ old('is_pwd', $applicant->is_pwd ? 'Yes' : 'No') }}',
                      is4ps: '{{ old('is_4ps', $applicant->is_4ps ? 'Yes' : 'No') }}',
                      isTransferee: 'No',
                      privacyConsent: false,
                      
                      // Track File Uploads
                      files: {
                          sa_info_form: false,
                          scholarship_app_form: false,
                          sa_profile_form: false,
                          ppe_clearance: false,
                          psa_birth_cert: false,
                          report_card: false,
                          guardian_id: false,
                          kukkiwon_cert: false,
                          ip_cert: false,
                          pwd_id: false,
                          '4ps_id': false
                      },

                      // Helper to update file state
                      fileSelected(key, event) {
                          this.files[key] = event.target.files.length > 0;
                      },

                      // Main Validation Logic
                      canSubmit() {
                          // 1. Check Privacy Consent
                          if (!this.privacyConsent) return false;
                          
                          // 2. Check Sport & Grade Level Selection
                          if (this.sport === '') return false;
                          if (this.gradeLevel === '') return false; // ✅ ADDED: Validation check

                          // 3. Check Mandatory Files
                          if (!this.files.sa_info_form) return false;
                          if (!this.files.scholarship_app_form) return false;
                          if (!this.files.sa_profile_form) return false;
                          if (!this.files.ppe_clearance) return false;
                          if (!this.files.psa_birth_cert) return false;
                          if (!this.files.report_card) return false;
                          if (!this.files.guardian_id) return false;

                          // 4. Check Conditional Files
                          if (this.sport.includes('Taekwondo') && !this.files.kukkiwon_cert) return false;
                          if (this.isIp === 'Yes' && !this.files.ip_cert) return false;
                          if (this.isPwd === 'Yes' && !this.files.pwd_id) return false;
                          if (this.is4ps === 'Yes' && !this.files['4ps_id']) return false;

                          return true;
                      }
                  }"
            >
                @csrf

                {{-- I. STUDENT INFORMATION --}}
                <div class="bg-white rounded-[2rem] shadow-xl shadow-indigo-100/50 border border-slate-100 overflow-hidden relative group hover:shadow-2xl transition-all duration-300">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-indigo-500 to-purple-600"></div>
                    
                    <div class="p-8 md:p-10">
                        <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-4">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-black shadow-sm">1</div>
                            <h3 class="text-xl font-black text-slate-800 uppercase tracking-wide">Student Information</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- LRN & Names --}}
                            <div class="group/input md:col-span-1">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Learner Reference Number (LRN) *</label>
                                <input type="text" name="lrn" value="{{ old('lrn', $applicant->lrn) }}" maxlength="12" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)" required placeholder="12 numeric characters only" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                            </div>
                            <div class="group/input">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Last Name *</label>
                                <input type="text" name="last_name" value="{{ old('last_name', $applicant->last_name) }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                            </div>
                            <div class="group/input">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">First Name *</label>
                                <input type="text" name="first_name" value="{{ old('first_name', $applicant->first_name) }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                            </div>
                            <div class="group/input">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Middle Name</label>
                                <input type="text" name="middle_name" value="{{ old('middle_name', $applicant->middle_name) }}" placeholder="Write N/A if not applicable" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                            </div>
                            <div class="group/input">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Extension Name</label>
                                <input type="text" name="extension_name" value="{{ old('extension_name', $applicant->extension_name) }}" placeholder="e.g. Jr., III (N/A if none)" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                            </div>

                            {{-- Bio --}}
                            <div class="group/input">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Date of Birth *</label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $applicant->date_of_birth) }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                            </div>
                            <div class="group/input">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Age *</label>
                                <input type="number" name="age" value="{{ old('age', $applicant->age) }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                            </div>
                            <div class="group/input">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Sex *</label>
                                <select name="sex" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                                    <option value="">Select Sex</option>
                                    <option value="Male" {{ old('sex', $applicant->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sex', $applicant->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="mt-8 border-t border-slate-100 pt-6">
                            <h4 class="text-xs font-black text-indigo-500 uppercase tracking-widest mb-6 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Permanent Address
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Region *</label>
                                    <select name="region" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                                        <option value="{{ $applicant->region }}" selected>{{ $applicant->region }}</option>
                                        {{-- Add other regions if needed --}}
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Province *</label>
                                    <input type="text" name="province" value="{{ old('province', $applicant->province) }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Municipality/City *</label>
                                    <input type="text" name="municipality_city" value="{{ old('municipality_city', $applicant->municipality_city) }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Barangay *</label>
                                    <input type="text" name="barangay" value="{{ old('barangay', $applicant->barangay) }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Street / House No. *</label>
                                    <input type="text" name="street_house_no" value="{{ old('street_house_no', $applicant->street_address) }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Zip Code *</label>
                                    <input type="text" name="zip_code" value="{{ old('zip_code', $applicant->zip_code) }}" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4)" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Email</label>
                                    <input type="email" name="email" value="{{ Auth::user()->email }}" readonly class="w-full bg-slate-100 border-transparent rounded-xl px-4 py-3 font-bold text-slate-500 cursor-not-allowed">
                                </div>
                            </div>
                        </div>

                        {{-- Groups (REACTIVE SECTION) --}}
                        <div class="mt-8 border-t border-slate-100 pt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            {{-- IP --}}
                            <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100">
                                <label class="block text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-1">Member of Indigenous People Group?</label>
                                <select name="is_ip" x-model="isIp" class="w-full bg-white border-indigo-200 rounded-lg text-sm font-bold text-indigo-900 focus:ring-indigo-500 mb-2">
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                                <div x-show="isIp === 'Yes'" x-transition>
                                    <input type="text" name="ip_group" value="{{ old('ip_group', $applicant->ip_group_name) }}" placeholder="If yes, what IP Group?" class="w-full bg-white border-indigo-200 rounded-lg text-xs">
                                </div>
                            </div>

                            {{-- PWD --}}
                            <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100">
                                <label class="block text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-1">Person with Disability?</label>
                                <select name="is_pwd" x-model="isPwd" class="w-full bg-white border-indigo-200 rounded-lg text-sm font-bold text-indigo-900 focus:ring-indigo-500 mb-2">
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                                <div x-show="isPwd === 'Yes'" x-transition>
                                    <input type="text" name="pwd_disability" value="{{ old('pwd_disability', $applicant->pwd_disability) }}" placeholder="If yes, what disability?" class="w-full bg-white border-indigo-200 rounded-lg text-xs">
                                </div>
                            </div>

                            {{-- 4Ps --}}
                            <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100">
                                <label class="block text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-1">Beneficiary of 4Ps?</label>
                                <select name="is_4ps" x-model="is4ps" class="w-full bg-white border-indigo-200 rounded-lg text-sm font-bold text-indigo-900 focus:ring-indigo-500">
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- II. SPORTS (REACTIVE) --}}
                <div class="bg-white rounded-[2rem] shadow-xl shadow-indigo-100/50 border border-slate-100 overflow-hidden relative group hover:shadow-2xl transition-all duration-300">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-blue-500 to-cyan-500"></div>
                    <div class="p-8 md:p-10">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 font-black shadow-sm">2</div>
                            <h3 class="text-xl font-black text-slate-800 uppercase tracking-wide">Sports</h3>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Sport *</label>
                            <select name="sport" x-model="sport" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-blue-500 transition-all">
                                <option value="">Select Sport</option>
                                <option>Aquatics</option>
                                <option>Athletics</option>
                                <option>Badminton</option>
                                <option>Gymnastic (Artistic)</option>
                                <option>Gymnastic (Rhythmic)</option>
                                <option>Judo</option>
                                <option>Table Tennis</option>
                                <option>Taekwondo (Kyorugi)</option>
                                <option>Taekwondo (Poomsae)</option>
                                <option>Weightlifting</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- III. PARENTS & GUARDIAN CARD --}}
                <div class="bg-white rounded-[2rem] shadow-xl shadow-indigo-100/50 border border-slate-100 overflow-hidden relative group hover:shadow-2xl transition-all duration-300">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-purple-500 to-pink-500"></div>
                    <div class="p-8 md:p-10">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 font-black shadow-sm">3</div>
                            <h3 class="text-xl font-black text-slate-800 uppercase tracking-wide">Parents' & Designated Guardian's Information</h3>
                        </div>
                        
                        {{-- Father --}}
                        <div class="mb-8">
                            <h4 class="text-xs font-black text-purple-500 uppercase tracking-widest mb-4">Father's Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" name="father_name" placeholder="Last Name, First Name, Middle Name" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all">
                                <input type="text" name="father_address" placeholder="Address" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all">
                                <input type="text" name="father_contact" placeholder="Contact No. (11 digits)" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all">
                                <input type="email" name="father_email" placeholder="Email" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all">
                            </div>
                        </div>

                        {{-- Mother --}}
                        <div class="mb-8">
                            <h4 class="text-xs font-black text-purple-500 uppercase tracking-widest mb-4">Mother's Maiden Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" name="mother_maiden_name" placeholder="Last Name, First Name, Middle Name" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all">
                                <input type="text" name="mother_address" placeholder="Address" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all">
                                <input type="text" name="mother_contact" placeholder="Contact No. (11 digits)" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all">
                                <input type="email" name="mother_email" placeholder="Email" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all">
                            </div>
                        </div>

                        {{-- Guardian --}}
                        <div>
                            <h4 class="text-xs font-black text-purple-500 uppercase tracking-widest mb-4">Guardian's Information (If not Parent) *</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" name="guardian_name" value="{{ old('guardian_name', $applicant->guardian_name) }}" required placeholder="Last Name, First Name, Middle Name" class="w-full bg-purple-50 border-purple-200 rounded-xl px-4 py-3 font-bold text-purple-900 focus:bg-white focus:border-purple-500 transition-all">
                                <input type="text" name="guardian_relationship" value="{{ old('guardian_relationship', $applicant->guardian_relationship) }}" required placeholder="Relationship" class="w-full bg-purple-50 border-purple-200 rounded-xl px-4 py-3 font-bold text-purple-900 focus:bg-white focus:border-purple-500 transition-all">
                                <input type="text" name="guardian_address" required placeholder="Address" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all">
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" name="guardian_contact" value="{{ old('guardian_contact', $applicant->guardian_contact) }}" required placeholder="Contact No. (11 digits)" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)" class="w-full bg-purple-50 border-purple-200 rounded-xl px-4 py-3 font-bold text-purple-900 focus:bg-white focus:border-purple-500 transition-all">
                                    <input type="email" name="guardian_email" value="{{ old('guardian_email', $applicant->guardian_email) }}" required placeholder="Email" class="w-full bg-purple-50 border-purple-200 rounded-xl px-4 py-3 font-bold text-purple-900 focus:bg-white focus:border-purple-500 transition-all">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- IV. SCHOOL INFORMATION --}}
                <div class="bg-white rounded-[2rem] shadow-xl shadow-indigo-100/50 border border-slate-100 overflow-hidden relative group hover:shadow-2xl transition-all duration-300">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-emerald-500 to-teal-500"></div>
                    <div class="p-8 md:p-10">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 font-black shadow-sm">4</div>
                                <h3 class="text-xl font-black text-slate-800 uppercase tracking-wide">School Information</h3>
                            </div>
                        </div>

                        {{-- ✅ NEW: Enrollment Grade Level Dropdown (Before Transferee) --}}
                        <div class="mb-8">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Enrollment Grade Level *</label>
                            <select name="grade_level" x-model="gradeLevel" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-emerald-500 transition-all cursor-pointer">
                                <option value="">Select Grade Level</option>
                                <option value="Grade 7">Grade 7</option>
                                <option value="Grade 8">Grade 8</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-between mb-8 pt-4 border-t border-slate-100">
                            {{-- TRANSFEREE TOGGLE --}}
                            <div class="flex items-center gap-3 bg-slate-100 p-2 rounded-lg">
                                <span class="text-xs font-bold text-slate-500 uppercase">Are you a Transferee?</span>
                                <select x-model="isTransferee" class="text-xs font-bold border-none rounded-md focus:ring-0 bg-white shadow-sm py-1 pl-2 pr-8">
                                    <option value="No">NO</option>
                                    <option value="Yes">YES</option>
                                </select>
                            </div>
                        </div>

                        {{-- HIDDEN/SHOWN SECTION BASED ON TOGGLE --}}
                        <div x-show="isTransferee === 'Yes'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Last Grade Level Completed</label>
                                <input type="text" name="last_grade_level" :required="isTransferee === 'Yes'" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-emerald-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Last School Year Completed</label>
                                <input type="text" name="last_school_year" :required="isTransferee === 'Yes'" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-emerald-500 transition-all">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">School Name</label>
                                <input type="text" name="school_name" :required="isTransferee === 'Yes'" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-emerald-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">School ID</label>
                                <input type="text" name="school_id" :required="isTransferee === 'Yes'" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-emerald-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">School Type</label>
                                <select name="school_type" :required="isTransferee === 'Yes'" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-emerald-500 transition-all">
                                    <option value="">Select Type</option>
                                    <option value="Public" {{ old('school_type', $applicant->school_type) == 'Public' ? 'selected' : '' }}>Public</option>
                                    <option value="Private" {{ old('school_type', $applicant->school_type) == 'Private' ? 'selected' : '' }}>Private</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">School Address</label>
                                <input type="text" name="school_address" :required="isTransferee === 'Yes'" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-emerald-500 transition-all">
                            </div>
                        </div>

                        {{-- MESSAGE IF NOT TRANSFEREE --}}
                        <div x-show="isTransferee === 'No'" class="text-center py-8 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                            <p class="text-xs font-bold text-slate-400 uppercase">Not a Transferee? Skip this section.</p>
                        </div>
                    </div>
                </div>

                {{-- V. FORMS UPLOAD --}}
                <div class="bg-white rounded-[2rem] shadow-xl shadow-indigo-100/50 border border-slate-100 overflow-hidden relative group hover:shadow-2xl transition-all duration-300">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-orange-500 to-amber-500"></div>
                    <div class="p-8 md:p-10">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600 font-black shadow-sm">5</div>
                            <h3 class="text-xl font-black text-slate-800 uppercase tracking-wide">Forms & Requirements</h3>
                        </div>
                        
                        <div class="space-y-6">
                            @foreach([
                                'sa_info_form' => 'Student-Athlete’s Information Form (New) (Old)',
                                'scholarship_app_form' => 'Scholarship Application Form',
                                'sa_profile_form' => 'Student-Athlete’s Profile Form',
                                'ppe_clearance' => 'Preparticipation Physical Evaluation Clearance Form',
                                'psa_birth_cert' => 'PSA Birth Certificate',
                                'report_card' => 'Grade 5 and 6 Report Card (for incoming Grade 7) or Grade 6 and 7 Report Card (for incoming Grade 8)',
                                'guardian_id' => 'Designated Guardian’s valid Government-Issued ID with signature',
                            ] as $key => $label)
                                <div class="relative flex items-center p-4 rounded-xl border-2 border-dashed border-slate-200 hover:border-orange-400 hover:bg-orange-50/30 transition-all group/file">
                                    <div class="flex-1">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1 group-hover/file:text-orange-600 transition-colors">{{ $label }} *</label>
                                        <input type="file" 
                                               name="files[{{ $key }}]" 
                                               accept=".pdf,.jpg,.png" 
                                               required 
                                               @change="fileSelected('{{ $key }}', $event)"
                                               class="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-100 file:text-slate-600 hover:file:bg-orange-500 hover:file:text-white cursor-pointer transition-all">
                                    </div>
                                </div>
                            @endforeach

                            {{-- NOTES --}}
                            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-xl flex gap-3">
                                <svg class="w-5 h-5 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div class="text-xs text-yellow-800 leading-relaxed">
                                    <p class="font-bold mb-1">IMPORTANT NOTES:</p>
                                    <ul class="list-disc list-inside space-y-1">
                                        <li><strong>Coach’s Recommendation Form:</strong> Not required to upload here. Please submit directly via email to the Secretariat.</li>
                                        <li><strong>Adviser’s Recommendation Form:</strong> Not required to upload here. Please submit directly via email to the Secretariat.</li>
                                    </ul>
                                </div>
                            </div>

                            {{-- CONDITIONAL UPLOADS --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-slate-100 mt-6">
                                <div x-show="sport.includes('Taekwondo')" x-transition class="relative p-3 rounded-xl border border-blue-200 bg-blue-50/50">
                                    <label class="block text-[10px] font-bold text-blue-600 uppercase mb-2">Kukkiwon Certificate</label>
                                    <input type="file" name="files[kukkiwon_cert]" accept=".pdf,.jpg,.png" @change="fileSelected('kukkiwon_cert', $event)" class="block w-full text-[10px] text-slate-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[9px] file:font-black file:uppercase file:bg-white file:text-blue-600 hover:file:bg-blue-600 hover:file:text-white cursor-pointer">
                                </div>
                                <div x-show="isIp === 'Yes'" x-transition class="relative p-3 rounded-xl border border-purple-200 bg-purple-50/50">
                                    <label class="block text-[10px] font-bold text-purple-600 uppercase mb-2">IP Certification</label>
                                    <input type="file" name="files[ip_cert]" accept=".pdf,.jpg,.png" @change="fileSelected('ip_cert', $event)" class="block w-full text-[10px] text-slate-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[9px] file:font-black file:uppercase file:bg-white file:text-purple-600 hover:file:bg-purple-600 hover:file:text-white cursor-pointer">
                                </div>
                                <div x-show="isPwd === 'Yes'" x-transition class="relative p-3 rounded-xl border border-pink-200 bg-pink-50/50">
                                    <label class="block text-[10px] font-bold text-pink-600 uppercase mb-2">PWD ID</label>
                                    <input type="file" name="files[pwd_id]" accept=".pdf,.jpg,.png" @change="fileSelected('pwd_id', $event)" class="block w-full text-[10px] text-slate-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[9px] file:font-black file:uppercase file:bg-white file:text-pink-600 hover:file:bg-pink-600 hover:file:text-white cursor-pointer">
                                </div>
                                <div x-show="is4ps === 'Yes'" x-transition class="relative p-3 rounded-xl border border-emerald-200 bg-emerald-50/50">
                                    <label class="block text-[10px] font-bold text-emerald-600 uppercase mb-2">4Ps ID or Certification</label>
                                    <input type="file" name="files[4ps_id]" accept=".pdf,.jpg,.png" @change="fileSelected('4ps_id', $event)" class="block w-full text-[10px] text-slate-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[9px] file:font-black file:uppercase file:bg-white file:text-emerald-600 hover:file:bg-emerald-600 hover:file:text-white cursor-pointer">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- VI. DATA PRIVACY CONSENT --}}
                <div class="bg-slate-50 p-8 rounded-[2.5rem] border border-slate-200 shadow-inner">
                    <h4 class="text-xl font-black text-slate-800 uppercase tracking-tighter mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Data Privacy Consent
                    </h4>
                    
                    <div class="h-64 overflow-y-auto bg-white p-6 rounded-xl border border-slate-200 text-slate-600 text-sm leading-relaxed mb-6 space-y-4 shadow-sm custom-scrollbar">
                        <p>I/We certify that the above information is true, complete and correct. I/We understand that any false or misleading information shall render my/our child ineligible for admission or may be subject for dismissal. If admitted, I/We agreed to abide by the policies, rules and regulations of the National Academy of Sports.</p>
                        
                        <p>For and in behalf of our minor child, I/We declare and confirm that, of my/our our volition, submit and will continue to submit, necessary information and documents to the National Academy of Sports (“NAS”), with the intention of applying, if qualified, enroll my/our child for the upcoming school year. In this regard, I/We acknowledge and understand that NAS requires our and our child’s personal and/or sensitive information (collectively “information”), for legitimate and lawful purposes, including but limited to verifying our identity, evaluating academic qualifications and eligibility, assessing physical fitness, and facilitating official communication with us.</p>
                        
                        <p>We acknowledge and agree that:</p>
                        <ul class="list-disc pl-5 space-y-2">
                            <li>NAS may collect, record, use, process, store, and transmit our information in accordance with the Data Privacy Act of 2012, its implementing Rules and Regulations (IRR), and other applicable laws.</li>
                            <li>NAS may disclose our information only with our consent, or when required or authorized under relevant laws, rules, and regulations.</li>
                            <li>NAS shall adopt appropriate organizational, physical, and technical measurement to ensure the confidentiality, integrity, and availability  of our information.</li>
                            <li>NAS may retain our information only for as long as necessary to fulfill the purposes stated herein, or as required by applicable laws and regulations.</li>
                        </ul>

                        <p>We also understand that as date subjects under the Data of 2012, we have right to:</p>
                        <ul class="list-disc pl-5 space-y-2">
                            <li>Inquire about, request access to, review or obtain a copy of our information in the custody of NAS.</li>
                            <li>Request correction or updating our information;</li>
                            <li>Withdraw or withhold consent, object to processing or request deletion of our information subject to limitations where NAS has a legal obligation or legitimate purpose to retain such information.</li>
                        </ul>

                        <p>I/We understand that refusal to provide the required information, or subsequent withdrawal of consent, may prevent NAS from processing our application and carrying out the purpose described in this document, we may contact NASCENT SAS secretariat at nascentsas@deped.gov.ph.</p>
                    </div>

                    <label class="flex items-start gap-4 p-4 rounded-xl hover:bg-white transition-colors cursor-pointer border border-transparent hover:border-slate-200">
                        <div class="relative flex items-center">
                            <input type="checkbox" x-model="privacyConsent" class="w-6 h-6 rounded-lg border-2 border-slate-300 text-blue-600 focus:ring-blue-500 transition-all cursor-pointer">
                        </div>
                        <span class="text-sm font-bold text-slate-700 select-none">
                            By signing below, I/We declared that we read, understand, and voluntarily consent to the collection, recording, use, processing, storage, disclosure, and transmission of our child’s information, including photographs,  videos, storage, data or documents, submitted to NAS in accordance with the Data Privacy Act of 2012 and applicable regulations.
                        </span>
                    </label>
                </div>

                {{-- SUBMIT BUTTON --}}
                <div class="pt-8 pb-20 text-center">
                    <button type="submit" 
                        :disabled="!canSubmit()"
                        :class="!canSubmit() ? 'bg-slate-300 text-slate-500 cursor-not-allowed shadow-none' : 'bg-slate-900 hover:bg-indigo-600 text-white shadow-2xl hover:shadow-indigo-300 hover:-translate-y-1'"
                        class="group relative inline-flex items-center justify-center w-full md:w-auto font-black py-5 px-16 rounded-2xl transition-all duration-300 transform">
                        <span class="relative z-10 uppercase tracking-widest text-sm flex items-center">
                            Submit Official Enrollment
                            <svg class="w-5 h-5 ml-3 transition-transform" :class="canSubmit() ? 'group-hover:translate-x-1' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </span>
                    </button>
                </div>

            </form>
        </div>
    </div>
    
    {{-- AlpineJS for interactivity --}}
    <script src="//unpkg.com/alpinejs" defer></script>
    <style> .bg-pattern { background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 24px 24px; } 
    .custom-scrollbar::-webkit-scrollbar { width: 8px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</x-applicant-layout>