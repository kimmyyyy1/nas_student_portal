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

                @php
                    $remarks = is_string($applicant->document_remarks) ? json_decode($applicant->document_remarks, true) : ($applicant->document_remarks ?? []);
                    $hasReturnRemarks = !empty($remarks['overall']) || !empty($remarks['documents']);
                @endphp

                @if($hasReturnRemarks)
                    <div class="mt-8 p-6 bg-red-50 border-2 border-red-200 rounded-[2rem] shadow-lg animate-fade-in text-left">
                        <div class="flex items-center gap-4 mb-3">
                             <div class="bg-red-500 p-2 rounded-lg text-white shadow-md">
                                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                             </div>
                             <h4 class="text-xl font-black text-red-700 uppercase tracking-tighter">Action Required: Revision Needed</h4>
                        </div>
                        <p class="text-red-600 font-bold mb-1">Message from the Registrar:</p>
                        <p class="text-red-800 italic leading-relaxed text-sm bg-white/50 p-4 rounded-xl border border-red-100">{{ $remarks['overall'] ?? 'Please check the specific document remarks below.' }}</p>
                    </div>
                @endif
            </div>

            {{-- MASTER FORM WRAPPER (Initialize AlpineJS) --}}
            <form action="{{ route('applicant.enrollment.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  class="space-y-10"
                  x-data="{ 
                      sport: '{{ old('sport', $details->sport ?? $applicant->sport) }}',
                      gradeLevel: '{{ old('grade_level', $details->enrollment_grade_level ?? '') }}',
                      isIp: '{{ old('is_ip', (isset($details->is_ip) ? ($details->is_ip ? 'Yes' : 'No') : ($applicant->is_ip ? 'Yes' : 'No'))) }}',
                      isPwd: '{{ old('is_pwd', (isset($details->is_pwd) ? ($details->is_pwd ? 'Yes' : 'No') : ($applicant->is_pwd ? 'Yes' : 'No'))) }}',
                      is4ps: '{{ old('is_4ps', (isset($details->is_4ps) ? ($details->is_4ps ? 'Yes' : 'No') : ($applicant->is_4ps ? 'Yes' : 'No'))) }}',
                      isTransferee: 'No',
                      selectedRegion: '{{ old('region', $details->region ?? ($applicant->region ?? '')) }}',
                      selectedProvince: '{{ old('province', $details->province ?? ($applicant->province ?? '')) }}',
                      regionProvinces: {
                          'Cordillera Administrative Region': ['Abra','Apayao','Benguet','Ifugao','Kalinga','Mountain Province'],
                          'Region 1: Ilocos Region': ['Ilocos Norte','Ilocos Sur','La Union','Pangasinan'],
                          'Region 2: Cagayan Valley': ['Batanes','Cagayan','Isabela','Nueva Vizcaya','Quirino'],
                          'Region 3: Central Luzon': ['Aurora','Bataan','Bulacan','Nueva Ecija','Pampanga','Tarlac','Zambales'],
                          'Region IV-A: CALABARZON': ['Batangas','Cavite','Laguna','Quezon','Rizal'],
                          'Region IV-B: MIMAROPA': ['Marinduque','Occidental Mindoro','Oriental Mindoro','Palawan','Romblon'],
                          'Region 5: Bicol Region': ['Albay','Camarines Norte','Camarines Sur','Catanduanes','Masbate','Sorsogon'],
                          'National Capital Region': ['Metro Manila'],
                          'Region 6: Western Visayas': ['Aklan','Antique','Capiz','Guimaras','Iloilo'],
                          'Region 7: Central Visayas': ['Bohol','Cebu','Siquijor'],
                          'Region 8: Eastern Visayas': ['Biliran','Eastern Samar','Leyte','Northern Samar','Samar','Southern Leyte'],
                          'Negros Island Region': ['Negros Occidental','Negros Oriental'],
                          'Region 9: Zamboanga Peninsula': ['Zamboanga Del Norte','Zamboanga del Sur','Zamboanga Sibugay'],
                          'Region 10: Northern Mindanao': ['Bukidnon','Camiguin','Lanao Del Norte','Misamis Occidental','Misamis Oriental'],
                          'Region 11: Davao Region': ['Compostela Valley','Davao del Norte','Davao del Sur','Davao Occidental','Davao Oriental'],
                          'Region 12: SOCCSKSARGEN': ['Cotabato','Sarangani','South Cotabato','Sultan Kudarat'],
                          'Region 13: CARAGA': ['Agusan del Norte','Agusan del Sur','Dinagat Islands','Surigao del Norte','Surigao del Sur'],
                          'Bangsamoro Autonomous Region in Muslim Mindanao': ['Basilan','Lanao del Sur','Maguindanao','Sulu','Tawi-Tawi'],
                      },
                      get filteredProvinces() {
                          return this.selectedRegion ? (this.regionProvinces[this.selectedRegion] || []) : [];
                      },
                      
                      // Track File Uploads (Pre-fill if already uploaded)
                      files: {
                          sa_info_form: {{ (isset($applicant->uploaded_files['sa_info_form']) && !isset($remarks['documents']['sa_info_form'])) ? 'true' : 'false' }},
                          basic_ed_form: {{ (isset($applicant->uploaded_files['basic_ed_form']) && !isset($remarks['documents']['basic_ed_form'])) ? 'true' : 'false' }},
                          scholarship_agreement: {{ (isset($applicant->uploaded_files['scholarship_agreement']) && !isset($remarks['documents']['scholarship_agreement'])) ? 'true' : 'false' }},
                          uniform_measurement: {{ (isset($applicant->uploaded_files['uniform_measurement']) && !isset($remarks['documents']['uniform_measurement'])) ? 'true' : 'false' }},
                          ppe_clearance: {{ (isset($applicant->uploaded_files['ppe_clearance']) && !isset($remarks['documents']['ppe_clearance'])) ? 'true' : 'false' }},
                          report_card: {{ (isset($applicant->uploaded_files['report_card']) && !isset($remarks['documents']['report_card'])) ? 'true' : 'false' }},
                          psa_birth_cert: {{ (isset($applicant->uploaded_files['psa_birth_cert']) && !isset($remarks['documents']['psa_birth_cert'])) ? 'true' : 'false' }},
                          passport: {{ (isset($applicant->uploaded_files['passport']) && !isset($remarks['documents']['passport'])) ? 'true' : 'false' }},
                          mother_id: {{ (isset($applicant->uploaded_files['mother_id']) && !isset($remarks['documents']['mother_id'])) ? 'true' : 'false' }},
                          father_id: {{ (isset($applicant->uploaded_files['father_id']) && !isset($remarks['documents']['father_id'])) ? 'true' : 'false' }},
                          guardian_id: {{ (isset($applicant->uploaded_files['guardian_id']) && !isset($remarks['documents']['guardian_id'])) ? 'true' : 'false' }},
                          ip_cert: {{ (isset($applicant->uploaded_files['ip_cert']) && !isset($remarks['documents']['ip_cert'])) ? 'true' : 'false' }},
                          pwd_id: {{ (isset($applicant->uploaded_files['pwd_id']) && !isset($remarks['documents']['pwd_id'])) ? 'true' : 'false' }},
                          '4ps_id': {{ (isset($applicant->uploaded_files['4ps_id']) && !isset($remarks['documents']['4ps_id'])) ? 'true' : 'false' }}
                      },

                      // Helper to update file state
                      fileSelected(key, event) {
                          this.files[key] = event.target.files.length > 0;
                      },

                      // Main Validation Logic
                      canSubmit() {
                          if (this.sport === '') return false;
                          if (this.gradeLevel === '') return false;

                          if (!this.files.sa_info_form) return false;
                          if (!this.files.basic_ed_form) return false;
                          if (!this.files.scholarship_agreement) return false;
                          if (!this.files.uniform_measurement) return false;
                          if (!this.files.ppe_clearance) return false;
                          if (!this.files.report_card) return false;
                          if (!this.files.psa_birth_cert) return false;
                          if (!this.files.guardian_id) return false;

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
                                <input type="text" name="lrn" value="{{ old('lrn', $details->lrn ?? $applicant->lrn) }}" maxlength="12" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)" required placeholder="12 numeric characters only" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                            </div>
                            <div class="group/input">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Last Name *</label>
                                <input type="text" name="last_name" value="{{ old('last_name', $details->last_name ?? $applicant->last_name) }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                            </div>
                            <div class="group/input">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">First Name *</label>
                                <input type="text" name="first_name" value="{{ old('first_name', $details->first_name ?? $applicant->first_name) }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                            </div>
                            <div class="group/input">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Middle Name *</label>
                                <input type="text" name="middle_name" value="{{ old('middle_name', $details->middle_name ?? $applicant->middle_name) }}" required placeholder="Write N/A if not applicable" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                            </div>
                            <div class="group/input">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Extension Name *</label>
                                <input type="text" name="extension_name" value="{{ old('extension_name', $details->extension_name ?? $applicant->extension_name) }}" required placeholder="e.g. Jr., III (Write N/A if none)" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                            </div>

                            {{-- Bio --}}
                            <div class="group/input">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Date of Birth *</label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $details->date_of_birth ?? $applicant->date_of_birth) }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                            </div>
                            <div class="group/input">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Age *</label>
                                <input type="number" name="age" value="{{ old('age', $details->age ?? $applicant->age) }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                            </div>
                            <div class="group/input">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Sex *</label>
                                <select name="sex" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                                    <option value="">Select Sex</option>
                                    <option value="Male" {{ old('sex', $details->sex ?? $applicant->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sex', $details->sex ?? $applicant->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>

                            {{-- Birthplace & Religion --}}
                            <div class="group/input">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Birthplace *</label>
                                <input type="text" name="birthplace" value="{{ old('birthplace', $details->birthplace ?? ($applicant->birthplace ?? '')) }}" required placeholder="City/Municipality, Province" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                            </div>
                            <div class="group/input">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Religion</label>
                                <input type="text" name="religion" value="{{ old('religion', $details->religion ?? ($applicant->religion ?? '')) }}" placeholder="e.g. Roman Catholic" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
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
                                    <select name="region" x-model="selectedRegion" @change="selectedProvince = ''" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                                        <option value="">Select Region</option>
                                        <template x-for="region in Object.keys(regionProvinces)" :key="region">
                                            <option :value="region" x-text="region" :selected="region === selectedRegion"></option>
                                        </template>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Province *</label>
                                    <select name="province" x-model="selectedProvince" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                                        <option value="">Select Province</option>
                                        <template x-for="prov in filteredProvinces" :key="prov">
                                            <option :value="prov" x-text="prov" :selected="prov === selectedProvince"></option>
                                        </template>
                                    </select>
                                    <p x-show="!selectedRegion" class="text-[9px] text-slate-400 mt-1 italic">Please select a region first</p>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Municipality/City *</label>
                                    <input type="text" name="municipality_city" value="{{ old('municipality_city', $details->municipality_city ?? $applicant->municipality_city) }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Barangay *</label>
                                    <input type="text" name="barangay" value="{{ old('barangay', $details->barangay ?? $applicant->barangay) }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Street / House No. *</label>
                                    <input type="text" name="street_house_no" value="{{ old('street_house_no', $details->street_house_no ?? $applicant->street_address) }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Zip Code *</label>
                                    <input type="text" name="zip_code" value="{{ old('zip_code', $details->zip_code ?? $applicant->zip_code) }}" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4)" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-indigo-500 transition-all">
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
                                    <input type="text" name="ip_group" value="{{ old('ip_group', $details->ip_group_name ?? $applicant->ip_group_name) }}" placeholder="If yes, what IP Group?" class="w-full bg-white border-indigo-200 rounded-lg text-xs">
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
                                    <input type="text" name="pwd_disability" value="{{ old('pwd_disability', $details->pwd_disability ?? $applicant->pwd_disability) }}" placeholder="If yes, what disability?" class="w-full bg-white border-indigo-200 rounded-lg text-xs">
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
                                @foreach(['Aquatics', 'Athletics', 'Badminton', 'Gymnastic (Artistic)', 'Gymnastic (Rhythmic)', 'Judo', 'Table Tennis', 'Taekwondo (Kyorugi)', 'Taekwondo (Poomsae)', 'Weightlifting'] as $sportOption)
                                    <option value="{{ $sportOption }}" {{ old('sport', $details->sport ?? $applicant->sport) == $sportOption ? 'selected' : '' }}>{{ $sportOption }}</option>
                                @endforeach
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
                                <input type="text" name="father_name" value="{{ old('father_name', $details->father_name ?? '') }}" placeholder="Last Name, First Name, Middle Name" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all">
                                <input type="text" name="father_address" value="{{ old('father_address', $details->father_address ?? '') }}" placeholder="Address" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all">
                                <input type="text" name="father_contact" value="{{ old('father_contact', $details->father_contact ?? '') }}" placeholder="Contact No. (11 digits)" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all">
                                <input type="email" name="father_email" value="{{ old('father_email', $details->father_email ?? '') }}" placeholder="Email" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all">
                            </div>
                        </div>

                        {{-- Mother --}}
                        <div class="mb-8">
                            <h4 class="text-xs font-black text-purple-500 uppercase tracking-widest mb-4">Mother's Maiden Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" name="mother_maiden_name" value="{{ old('mother_maiden_name', $details->mother_maiden_name ?? '') }}" placeholder="Last Name, First Name, Middle Name" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all">
                                <input type="text" name="mother_address" value="{{ old('mother_address', $details->mother_address ?? '') }}" placeholder="Address" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all">
                                <input type="text" name="mother_contact" value="{{ old('mother_contact', $details->mother_contact ?? '') }}" placeholder="Contact No. (11 digits)" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all">
                                <input type="email" name="mother_email" value="{{ old('mother_email', $details->mother_email ?? '') }}" placeholder="Email" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all">
                            </div>
                        </div>

                        {{-- Guardian --}}
                        <div>
                            <h4 class="text-xs font-black text-purple-500 uppercase tracking-widest mb-4">Guardian's Information (If not Parent) *</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" name="guardian_name" value="{{ old('guardian_name', $details->guardian_name ?? $applicant->guardian_name) }}" required placeholder="Last Name, First Name, Middle Name" class="w-full bg-purple-50 border-purple-200 rounded-xl px-4 py-3 font-bold text-purple-900 focus:bg-white focus:border-purple-500 transition-all">
                                <input type="text" name="guardian_relationship" value="{{ old('guardian_relationship', $details->guardian_relationship ?? $applicant->guardian_relationship) }}" required placeholder="Relationship" class="w-full bg-purple-50 border-purple-200 rounded-xl px-4 py-3 font-bold text-purple-900 focus:bg-white focus:border-purple-500 transition-all">
                                <input type="text" name="guardian_address" value="{{ old('guardian_address', $details->guardian_address ?? '') }}" required placeholder="Address" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all">
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" name="guardian_contact" value="{{ old('guardian_contact', $details->guardian_contact ?? $applicant->guardian_contact) }}" required placeholder="Contact No. (11 digits)" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)" class="w-full bg-purple-50 border-purple-200 rounded-xl px-4 py-3 font-bold text-purple-900 focus:bg-white focus:border-purple-500 transition-all">
                                    <input type="email" name="guardian_email" value="{{ old('guardian_email', $details->guardian_email ?? '') }}" required placeholder="Email" class="w-full bg-purple-50 border-purple-200 rounded-xl px-4 py-3 font-bold text-purple-900 focus:bg-white focus:border-purple-500 transition-all">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- IV. SCHOOL INFORMATION --}}
                <div class="bg-white rounded-[2rem] shadow-xl shadow-indigo-100/50 border border-slate-100 overflow-hidden relative group hover:shadow-2xl transition-all duration-300">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-emerald-500 to-teal-500"></div>
                    <div class="p-8 md:p-10">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 font-black shadow-sm">4</div>
                            <h3 class="text-xl font-black text-slate-800 uppercase tracking-wide">School Information</h3>
                        </div>

                        {{-- Enrollment Grade Level (Always Visible) --}}
                        <div class="mb-8">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Enrollment Grade Level *</label>
                            <select name="grade_level" x-model="gradeLevel" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-emerald-500 transition-all cursor-pointer">
                                <option value="">Select Grade Level</option>
                                <option value="Grade 7">Grade 7</option>
                                <option value="Grade 8">Grade 8</option>
                            </select>
                        </div>

                        <div class="border-t border-slate-100 pt-6">
                            <h4 class="text-xs font-black text-emerald-500 uppercase tracking-widest mb-6">Previous School Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Last Grade Level Completed *</label>
                                    <input type="text" name="last_grade_level" value="{{ old('last_grade_level', $details->last_grade_level ?? '') }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-emerald-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Last School Year Completed *</label>
                                    <input type="text" name="last_school_year" value="{{ old('last_school_year', $details->last_school_year ?? '') }}" required placeholder="e.g. 2023-2024" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-emerald-500 transition-all">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">School Name *</label>
                                    <input type="text" name="school_name" value="{{ old('school_name', $details->school_name ?? '') }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-emerald-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">School ID *</label>
                                    <input type="text" name="school_id" value="{{ old('school_id', $details->school_id ?? '') }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-emerald-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">School Type *</label>
                                    <select name="school_type" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-emerald-500 transition-all">
                                        <option value="">Select Type</option>
                                        <option value="Public" {{ old('school_type', $details->school_type ?? '') == 'Public' ? 'selected' : '' }}>Public</option>
                                        <option value="Private" {{ old('school_type', $details->school_type ?? '') == 'Private' ? 'selected' : '' }}>Private</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">School Address *</label>
                                    <input type="text" name="school_address" value="{{ old('school_address', $details->school_address ?? '') }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:bg-white focus:border-emerald-500 transition-all">
                                </div>
                            </div>
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
                                'sa_info_form' => 'Student-Athlete’s Information Form',
                                'basic_ed_form' => 'Basic Education Enrollment Form',
                                'scholarship_agreement' => 'Scholarship Agreement',
                                'uniform_measurement' => 'Student Uniform Measurement Form',
                                'ppe_clearance' => 'Pre-ingress Health Assessment Forms',
                                'report_card' => 'Grade 6 or Grade 7 Report Card',
                                'psa_birth_cert' => 'PSA Birth Certificate',
                                'guardian_id' => 'Designated Guardian’s valid Government-Issued ID with signature'
                            ] as $key => $label)
                                <div class="relative flex flex-col p-4 rounded-xl border-2 {{ isset($remarks['documents'][$key]) ? 'border-red-400 bg-red-50/20' : (isset($applicant->uploaded_files[$key]) ? 'border-emerald-400 bg-emerald-50/20' : 'border-dashed border-slate-200 hover:border-orange-400 hover:bg-orange-50/30') }} transition-all group/file">
                                    @if(isset($remarks['documents'][$key]))
                                        <div class="mb-3 p-2.5 bg-red-600 text-white text-[10px] font-black uppercase rounded-lg shadow-sm flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            REVISION: {{ $remarks['documents'][$key] }}
                                        </div>
                                    @elseif(isset($applicant->uploaded_files[$key]))
                                        <div class="mb-3 p-2.5 bg-emerald-600 text-white text-[10px] font-black uppercase rounded-lg shadow-sm flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                PREVIOUSLY UPLOADED & VERIFIED
                                            </div>
                                            <a href="{{ $applicant->uploaded_files[$key] }}" target="_blank" class="bg-white/20 hover:bg-white/40 px-2 py-0.5 rounded transition-all flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                VIEW
                                            </a>
                                        </div>
                                    @endif
                                    <div class="flex items-center w-full">
                                        <div class="flex-1">
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1 group-hover/file:text-orange-600 transition-colors">{{ $label }} {{ str_contains($label, '(Optional)') || str_contains($label, '(if available)') ? '' : '*' }}</label>
                                            <input type="file" 
                                                   name="files[{{ $key }}]" 
                                                   accept=".pdf,.jpg,.png" 
                                                   {{ (isset($applicant->uploaded_files[$key])) ? '' : 'required' }}
                                                   @change="fileSelected('{{ $key }}', $event)"
                                                   class="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-100 file:text-slate-600 hover:file:bg-orange-500 hover:file:text-white cursor-pointer transition-all">
                                            @if(isset($applicant->uploaded_files[$key]))
                                                <p class="text-[9px] text-emerald-600 font-bold mt-1 italic">Note: File exists. Upload only if you wish to replace it.</p>
                                            @endif
                                        </div>
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

                            {{-- CONDITIONAL / OPTIONAL UPLOADS --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-slate-100 mt-6">
                                <div class="relative p-3 rounded-xl border {{ isset($remarks['documents']['passport']) ? 'border-red-400 bg-red-50/20' : (isset($applicant->uploaded_files['passport']) ? 'border-emerald-400 bg-emerald-50/20' : 'border-blue-200 bg-blue-50/50') }}">
                                    @if(isset($remarks['documents']['passport']))
                                        <div class="mb-2 p-1.5 bg-red-600 text-white text-[8px] font-black uppercase rounded shadow-sm flex items-center gap-1.5">
                                            REVISION: {{ $remarks['documents']['passport'] }}
                                        </div>
                                    @elseif(isset($applicant->uploaded_files['passport']))
                                        <div class="mb-2 p-1.5 bg-emerald-600 text-white text-[8px] font-black uppercase rounded shadow-sm flex items-center justify-between">
                                            <span>✔ PREVIOUSLY UPLOADED</span>
                                            <a href="{{ $applicant->uploaded_files['passport'] }}" target="_blank" class="bg-white/20 hover:bg-white/40 px-1 rounded transition-all">VIEW</a>
                                        </div>
                                    @endif
                                    <label class="block text-[10px] font-bold text-blue-600 uppercase mb-2">Passport of the Student-Athlete (if available)</label>
                                    <p class="text-[9px] text-blue-500/70 mb-2 italic">Official Document</p>
                                    <input type="file" name="files[passport]" accept=".pdf,.jpg,.png" @change="fileSelected('passport', $event)" class="block w-full text-[10px] text-slate-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[9px] file:font-black file:uppercase file:bg-white file:text-blue-600 hover:file:bg-blue-600 hover:file:text-white cursor-pointer">
                                </div>
                                <div class="relative p-3 rounded-xl border {{ isset($remarks['documents']['mother_id']) ? 'border-red-400 bg-red-50/20' : (isset($applicant->uploaded_files['mother_id']) ? 'border-emerald-400 bg-emerald-50/20' : 'border-slate-200 bg-slate-50/50') }}">
                                    @if(isset($remarks['documents']['mother_id']))
                                        <div class="mb-2 p-1.5 bg-red-600 text-white text-[8px] font-black uppercase rounded shadow-sm flex items-center gap-1.5">
                                            REVISION: {{ $remarks['documents']['mother_id'] }}
                                        </div>
                                    @elseif(isset($applicant->uploaded_files['mother_id']))
                                        <div class="mb-2 p-1.5 bg-emerald-600 text-white text-[8px] font-black uppercase rounded shadow-sm flex items-center justify-between">
                                            <span>✔ PREVIOUSLY UPLOADED</span>
                                            <a href="{{ $applicant->uploaded_files['mother_id'] }}" target="_blank" class="bg-white/20 hover:bg-white/40 px-1 rounded transition-all">VIEW</a>
                                        </div>
                                    @endif
                                    <label class="block text-[10px] font-bold text-slate-600 uppercase mb-2">Mother’s valid Government-Issued ID with signature (not required for all)</label>
                                    <p class="text-[9px] text-slate-400 mb-2 italic">Guardian Document</p>
                                    <input type="file" name="files[mother_id]" accept=".pdf,.jpg,.png" @change="fileSelected('mother_id', $event)" class="block w-full text-[10px] text-slate-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[9px] file:font-black file:uppercase file:bg-white file:text-slate-600 hover:file:bg-slate-600 hover:file:text-white cursor-pointer">
                                </div>
                                <div class="relative p-3 rounded-xl border {{ isset($remarks['documents']['father_id']) ? 'border-red-400 bg-red-50/20' : (isset($applicant->uploaded_files['father_id']) ? 'border-emerald-400 bg-emerald-50/20' : 'border-slate-200 bg-slate-50/50') }}">
                                    @if(isset($remarks['documents']['father_id']))
                                        <div class="mb-2 p-1.5 bg-red-600 text-white text-[8px] font-black uppercase rounded shadow-sm flex items-center gap-1.5">
                                            REVISION: {{ $remarks['documents']['father_id'] }}
                                        </div>
                                    @elseif(isset($applicant->uploaded_files['father_id']))
                                        <div class="mb-2 p-1.5 bg-emerald-600 text-white text-[8px] font-black uppercase rounded shadow-sm flex items-center justify-between">
                                            <span>✔ PREVIOUSLY UPLOADED</span>
                                            <a href="{{ $applicant->uploaded_files['father_id'] }}" target="_blank" class="bg-white/20 hover:bg-white/40 px-1 rounded transition-all">VIEW</a>
                                        </div>
                                    @endif
                                    <label class="block text-[10px] font-bold text-slate-600 uppercase mb-2">Father’s valid Government-Issued ID with signature (not required for all)</label>
                                    <p class="text-[9px] text-slate-400 mb-2 italic">Guardian Document</p>
                                    <input type="file" name="files[father_id]" accept=".pdf,.jpg,.png" @change="fileSelected('father_id', $event)" class="block w-full text-[10px] text-slate-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[9px] file:font-black file:uppercase file:bg-white file:text-slate-600 hover:file:bg-slate-600 hover:file:text-white cursor-pointer">
                                </div>
                                <div x-show="isIp === 'Yes'" x-transition class="relative p-3 rounded-xl border {{ isset($remarks['documents']['ip_cert']) ? 'border-red-400 bg-red-50/20' : (isset($applicant->uploaded_files['ip_cert']) ? 'border-emerald-400 bg-emerald-50/20' : 'border-purple-200 bg-purple-50/50') }}">
                                    @if(isset($remarks['documents']['ip_cert']))
                                        <div class="mb-2 p-1.5 bg-red-600 text-white text-[8px] font-black uppercase rounded shadow-sm flex items-center gap-1.5">
                                            REVISION: {{ $remarks['documents']['ip_cert'] }}
                                        </div>
                                    @elseif(isset($applicant->uploaded_files['ip_cert']))
                                        <div class="mb-2 p-1.5 bg-emerald-600 text-white text-[8px] font-black uppercase rounded shadow-sm flex items-center justify-between">
                                            <span>✔ PREVIOUSLY UPLOADED</span>
                                            <a href="{{ $applicant->uploaded_files['ip_cert'] }}" target="_blank" class="bg-white/20 hover:bg-white/40 px-1 rounded transition-all">VIEW</a>
                                        </div>
                                    @endif
                                    <label class="block text-[10px] font-bold text-purple-600 uppercase mb-2">IP Certification (If member of an indigenous group) (not required for all)</label>
                                    <p class="text-[9px] text-purple-500 mb-2 italic">Conditional Document</p>
                                    <input type="file" name="files[ip_cert]" accept=".pdf,.jpg,.png" @change="fileSelected('ip_cert', $event)" class="block w-full text-[10px] text-slate-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[9px] file:font-black file:uppercase file:bg-white file:text-purple-600 hover:file:bg-purple-600 hover:file:text-white cursor-pointer">
                                </div>
                                <div x-show="isPwd === 'Yes'" x-transition class="relative p-3 rounded-xl border {{ isset($remarks['documents']['pwd_id']) ? 'border-red-400 bg-red-50/20' : (isset($applicant->uploaded_files['pwd_id']) ? 'border-emerald-400 bg-emerald-50/20' : 'border-pink-200 bg-pink-50/50') }}">
                                    @if(isset($remarks['documents']['pwd_id']))
                                        <div class="mb-2 p-1.5 bg-red-600 text-white text-[8px] font-black uppercase rounded shadow-sm flex items-center gap-1.5">
                                            REVISION: {{ $remarks['documents']['pwd_id'] }}
                                        </div>
                                    @elseif(isset($applicant->uploaded_files['pwd_id']))
                                        <div class="mb-2 p-1.5 bg-emerald-600 text-white text-[8px] font-black uppercase rounded shadow-sm flex items-center justify-between">
                                            <span>✔ PREVIOUSLY UPLOADED</span>
                                            <a href="{{ $applicant->uploaded_files['pwd_id'] }}" target="_blank" class="bg-white/20 hover:bg-white/40 px-1 rounded transition-all">VIEW</a>
                                        </div>
                                    @endif
                                    <label class="block text-[10px] font-bold text-pink-600 uppercase mb-2">PWD ID (If person with disability) (not required for all)</label>
                                    <p class="text-[9px] text-pink-500 mb-2 italic">Conditional Document</p>
                                    <input type="file" name="files[pwd_id]" accept=".pdf,.jpg,.png" @change="fileSelected('pwd_id', $event)" class="block w-full text-[10px] text-slate-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[9px] file:font-black file:uppercase file:bg-white file:text-pink-600 hover:file:bg-pink-600 hover:file:text-white cursor-pointer">
                                </div>
                                <div x-show="is4ps === 'Yes'" x-transition class="relative p-3 rounded-xl border {{ isset($remarks['documents']['4ps_id']) ? 'border-red-400 bg-red-50/20' : (isset($applicant->uploaded_files['4ps_id']) ? 'border-emerald-400 bg-emerald-50/20' : 'border-emerald-200 bg-emerald-50/50') }}">
                                    @if(isset($remarks['documents']['4ps_id']))
                                        <div class="mb-2 p-1.5 bg-red-600 text-white text-[8px] font-black uppercase rounded shadow-sm flex items-center gap-1.5">
                                            REVISION: {{ $remarks['documents']['4ps_id'] }}
                                        </div>
                                    @elseif(isset($applicant->uploaded_files['4ps_id']))
                                        <div class="mb-2 p-1.5 bg-emerald-600 text-white text-[8px] font-black uppercase rounded shadow-sm flex items-center justify-between">
                                            <span>✔ PREVIOUSLY UPLOADED</span>
                                            <a href="{{ $applicant->uploaded_files['4ps_id'] }}" target="_blank" class="bg-white/20 hover:bg-white/40 px-1 rounded transition-all">VIEW</a>
                                        </div>
                                    @endif
                                    <label class="block text-[10px] font-bold text-emerald-600 uppercase mb-2">4Ps ID or Certification (If beneficiary of the 4Ps) (not required for all)</label>
                                    <p class="text-[9px] text-emerald-500 mb-2 italic">Conditional Document</p>
                                    <input type="file" name="files[4ps_id]" accept=".pdf,.jpg,.png" @change="fileSelected('4ps_id', $event)" class="block w-full text-[10px] text-slate-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[9px] file:font-black file:uppercase file:bg-white file:text-emerald-600 hover:file:bg-emerald-600 hover:file:text-white cursor-pointer">
                                </div>
                            </div>
                        </div>
                    </div>
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
    
    <style> .bg-pattern { background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 24px 24px; } 
    .custom-scrollbar::-webkit-scrollbar { width: 8px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</x-applicant-layout>