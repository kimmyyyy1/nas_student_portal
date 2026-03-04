<x-applicant-layout>
    {{-- JAVASCRIPT LOGIC --}}
    <script>
        function applicantForm() {
            return {
                // --- 1. INITIALIZE VARIABLES ---
                photoPreview: null,
                formLoaded: false,
                
                // Alpine Models (Tinatago dito ang data para sa Auto-Save)
                inputs: {
                    // Personal Info (REMOVED Extension Name)
                    lrn: "{{ old('lrn', '') }}",
                    last_name: "{{ old('last_name', '') }}",
                    first_name: "{{ old('first_name', '') }}",
                    middle_name: "{{ old('middle_name', '') }}",
                    date_of_birth: "{{ old('date_of_birth', '') }}",
                    age: "{{ old('age', '') }}",
                    gender: "{{ old('gender', '') }}",
                    
                    // Address
                    region: "{{ old('region', '') }}",
                    province: "{{ old('province', '') }}",
                    municipality_city: "{{ old('municipality_city', '') }}",
                    barangay: "{{ old('barangay', '') }}",
                    street_address: "{{ old('street_address', '') }}",
                    zip_code: "{{ old('zip_code', '') }}",

                    // Background
                    learn_about_nas: "{{ old('learn_about_nas', '') }}",
                    referrer_name: "{{ old('referrer_name', '') }}",
                    attended_campaign: "{{ old('attended_campaign', 'No') }}",

                    // Groups
                    is_ip: "{{ old('is_ip', 'No') }}",
                    ip_group_name: "{{ old('ip_group_name', '') }}",
                    is_pwd: "{{ old('is_pwd', 'No') }}",
                    pwd_disability: "{{ old('pwd_disability', '') }}",
                    is_4ps: "{{ old('is_4ps', 'No') }}",

                    // Sports
                    sport: "{{ old('sport', '') }}",
                    sport_specification: "{{ old('sport_specification', '') }}",
                    palaro_finisher: "{{ old('palaro_finisher', 'No') }}",
                    batang_pinoy_finisher: "{{ old('batang_pinoy_finisher', 'No') }}",
                    school_type: "{{ old('school_type', '') }}",
                    school_last_grade_level: "{{ old('school_last_grade_level', '') }}",
                    school_last_year_completed: "{{ old('school_last_year_completed', '') }}",
                    school_id: "{{ old('school_id', '') }}",

                    // Guardian (Strict Labels)
                    guardian_name: "{{ old('guardian_name', '') }}",
                    guardian_relationship: "{{ old('guardian_relationship', '') }}",
                    guardian_email: "{{ old('guardian_email', '') }}",
                    guardian_contact: "{{ old('guardian_contact', '') }}",
                },

                // UI Toggles
                privacyConsent: false,

                // Regions Data
                regionsData: {
                    'Cordillera Administrative Region': ['Abra', 'Apayao', 'Benguet', 'Ifugao', 'Kalinga', 'Mountain Province'],
                    'Region 1: Ilocos Region': ['Ilocos Norte', 'Ilocos Sur', 'La Union', 'Pangasinan'],
                    'Region 2: Cagayan Valley': ['Batanes', 'Cagayan', 'Isabela', 'Nueva Vizcaya', 'Quirino'],
                    'Region 3: Central Luzon': ['Aurora', 'Bataan', 'Bulacan', 'Nueva Ecija', 'Pampanga', 'Tarlac', 'Zambales'],
                    'Region IV-A: CALABARZON': ['Batangas', 'Cavite', 'Laguna', 'Quezon', 'Rizal'],
                    'Region IV-B: MIMAROPA': ['Marinduque', 'Occidental Mindoro', 'Oriental Mindoro', 'Palawan', 'Romblon'],
                    'Region 5: Bicol Region': ['Albay', 'Camarines Norte', 'Camarines Sur', 'Catanduanes', 'Masbate', 'Sorsogon'],
                    'National Capital Region': ['Metro Manila'],
                    'Region 6: Western Visayas': ['Aklan', 'Antique', 'Capiz', 'Guimaras', 'Iloilo', 'Negros Occidental'],
                    'Region 7: Central Visayas': ['Bohol', 'Cebu', 'Siquijor'],
                    'Region 8: Eastern Visayas': ['Biliran', 'Eastern Samar', 'Leyte', 'Northern Samar', 'Samar', 'Southern Leyte'],
                    'Negros Island Region': ['Negros Occidental', 'Negros Oriental', 'Siquijor'],
                    'Region 9: Zamboanga Peninsula': ['Zamboanga Del Norte', 'Zamboanga del Sur', 'Zamboanga Sibugay'],
                    'Region 10: Northern Mindanao': ['Bukidnon', 'Camiguin', 'Lanao Del Norte', 'Misamis Occidental', 'Misamis Oriental'],
                    'Region 11: Davao Region': ['Compostela Valley', 'Davao del Norte', 'Davao del Sur', 'Davao Occidental', 'Davao Oriental'],
                    'Region 12: SOCCSKSARGEN': ['Cotabato', 'Sarangani', 'South Cotabato', 'Sultan Kudarat'],
                    'Region 13: CARAGA': ['Agusan del Norte', 'Agusan del Sur', 'Dinagat Islands', 'Surigao del Norte', 'Surigao del Sur'],
                    'Bangsamoro Autonomous Region in Muslim Mindanao': ['Basilan', 'Lanao del Sur', 'Maguindanao', 'Sulu', 'Tawi-Tawi']
                },

                // --- 3. INIT & RESTORE ---
                init() {
                    const userId = "{{ Auth::id() }}";
                    const savedData = localStorage.getItem('nas_form_v2_' + userId);
                    const hasServerErrors = @json($errors->any());

                    // Restore from Local Storage if no validation errors
                    if (!hasServerErrors && savedData) {
                        try {
                            const parsed = JSON.parse(savedData);
                            this.inputs = { ...this.inputs, ...parsed };
                            console.log('Restored form data.');
                        } catch (e) {
                            console.error('Error loading draft', e);
                        }
                    }

                    // Delay auto-save binding to prevent overwriting with empty data on load
                    setTimeout(() => {
                        this.formLoaded = true;
                        this.$watch('inputs', (value) => {
                            localStorage.setItem('nas_form_v2_' + userId, JSON.stringify(value));
                        });
                    }, 800); 
                },

                // --- 4. CLEAR ON SUBMIT ---
                submitForm() {
                    if(!this.privacyConsent) return;
                    const userId = "{{ Auth::id() }}";
                    localStorage.removeItem('nas_form_v2_' + userId);
                    document.getElementById('applicantForm').submit();
                },

                handleFileUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => { this.photoPreview = e.target.result; };
                        reader.readAsDataURL(file);
                    }
                }
            }
        }
    </script>

    <div x-data="applicantForm()" x-init="init()" class="min-h-screen bg-slate-50 py-8 md:py-12 px-4 sm:px-6 lg:px-8 font-sans bg-pattern">
        
        {{-- HEADER --}}
        <div class="max-w-7xl mx-auto flex flex-col items-center justify-center mb-10 md:mb-16 text-center animate-fade-in-down">
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-8 md:gap-12 mb-6 md:mb-8 transition-transform hover:scale-105 duration-700 ease-out">
                <img src="{{ asset('images/nas/nas-logo-sidebar.png') }}" class="h-20 md:h-36 w-auto object-contain drop-shadow-2xl" alt="NAS Logo">
                <div class="h-px w-20 sm:h-20 sm:w-px bg-gradient-to-r sm:bg-gradient-to-b from-transparent via-slate-300 to-transparent hidden sm:block"></div>
                <img src="{{ asset('images/nas/NASCENT SAS.png') }}" class="h-16 md:h-24 w-auto object-contain drop-shadow-xl" alt="NASCENT SAS Logo">
            </div>
            <h1 class="text-2xl sm:text-4xl md:text-6xl font-black text-slate-900 uppercase tracking-tighter leading-tight bg-clip-text text-transparent bg-gradient-to-r from-blue-900 via-slate-800 to-blue-900 max-w-5xl mx-auto drop-shadow-sm font-heading">
                NAS Annual Search for Competent, Exceptional, Notable, and Talented Student-Athlete Scholars
            </h1>
            <p class="mt-3 md:mt-4 text-blue-600 font-bold tracking-[0.2em] text-xs md:text-lg uppercase">(NASCENT SAS)</p>
            
            {{-- Auto-save Indicator --}}
            <div x-show="formLoaded" x-transition class="mt-4 inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-[10px] md:text-xs font-bold shadow-sm border border-green-200">
                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Progress Auto-Saved
            </div>
        </div>

        <div class="max-w-7xl mx-auto bg-white shadow-2xl shadow-blue-100/70 rounded-3xl md:rounded-[2.5rem] overflow-hidden border border-slate-100 relative">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500"></div>

            {{-- Sticky Bar --}}
            <div class="p-4 md:p-8 bg-white/90 backdrop-blur-xl border-b border-slate-200 sticky top-0 z-30 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4 md:gap-6">
                <div class="flex items-center bg-slate-50 border border-slate-200 pl-2 pr-6 py-2 rounded-full shadow-sm ring-1 ring-slate-100">
                    <span class="relative flex h-6 w-6 md:h-8 md:w-8 mr-3 items-center justify-center bg-yellow-100 rounded-full">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 md:h-3 md:w-3 bg-yellow-500"></span>
                    </span>
                    <div>
                        <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-0.5 md:mb-1">Current Phase</p>
                        <p class="text-xs md:text-sm font-black text-blue-900 uppercase tracking-tight leading-none">Phase 1: Registration & Profiling</p>
                    </div>
                </div>

                {{-- STEPPER --}}
                <div class="hidden md:flex gap-1">
                    @foreach(['Register', 'Assess', 'Verify', 'Result'] as $index => $step)
                        <div class="flex flex-col items-center w-20 lg:w-24 group">
                            <div class="h-1.5 w-full rounded-full mb-2 transition-all duration-300 {{ $index === 0 ? 'bg-blue-600 scale-105' : 'bg-slate-200 group-hover:bg-slate-300' }}"></div>
                            <span class="text-[10px] font-bold uppercase transition-colors duration-300 {{ $index === 0 ? 'text-blue-600' : 'text-slate-300 group-hover:text-slate-400' }}">{{ $step }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- FORM --}}
            <form id="applicantForm" method="POST" action="{{ route('applicant.store') }}" @submit.prevent="submitForm()" enctype="multipart/form-data" class="p-6 md:p-16 space-y-12 md:space-y-20">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 md:p-6 rounded-r-xl shadow-lg mb-8">
                        <h3 class="text-sm font-black text-red-800 uppercase tracking-wide mb-2">Submission Failed</h3>
                        <ul class="list-disc list-inside text-xs text-red-700 font-bold space-y-1">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                {{-- I. PERSONAL INFORMATION --}}
                <section class="relative group">
                    <div class="flex items-center mb-6 md:mb-10">
                        <div class="bg-blue-600 text-white w-10 h-10 md:w-14 md:h-14 rounded-xl md:rounded-2xl flex items-center justify-center font-black text-lg md:text-2xl shadow-xl mr-4 md:mr-6 z-10">1</div>
                        <div>
                            <h3 class="text-xl md:text-3xl font-black text-slate-800 uppercase tracking-tighter font-heading">Personal Information</h3>
                            <p class="text-xs md:text-sm text-slate-400 font-medium tracking-wide">Please provide accurate personal details.</p>
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row gap-10 lg:gap-16">
                        
                        {{-- ========================================= --}}
                        {{--  👇 UPDATED PHOTO UPLOAD SECTION 👇  --}}
                        {{-- ========================================= --}}
                        <div class="w-full lg:w-72 flex-shrink-0 flex flex-col items-center">
                            {{-- Added Hover Transitions & Perspective --}}
                            <div class="relative group cursor-pointer w-48 h-48 md:w-64 md:h-64 mx-auto perspective-1000 transition-transform duration-500 hover:scale-[1.02]">
                                {{-- Enhanced Glow Effect on Hover --}}
                                <div class="absolute -inset-3 bg-gradient-to-tr from-blue-500 via-cyan-400 to-blue-600 rounded-[2rem] blur-md opacity-30 group-hover:opacity-50 group-hover:blur-xl transition-all duration-500"></div>

                                {{-- Main Box with Improved Border & Shadow --}}
                                <div class="relative w-full h-full bg-white rounded-[1.75rem] flex items-center justify-center overflow-hidden border-[5px] border-white shadow-xl transition-all duration-500 group-hover:border-blue-50/50">
                                    <template x-if="!photoPreview">
                                        <div class="flex flex-col items-center text-slate-400 p-4 text-center">
                                            {{-- Icon changes color on hover --}}
                                            <svg class="w-10 h-10 md:w-14 md:h-14 mb-3 stroke-1 text-blue-300 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            
                                            {{-- 👇 UPDATED TEXT HERE 👇 --}}
                                            <span class="text-[9px] md:text-[11px] font-black uppercase tracking-wider leading-tight text-slate-500/80 group-hover:text-blue-700 transition-colors px-2">
                                                Recent 2X2 Photograph<br>with White Background
                                            </span>
                                        </div>
                                    </template>
                                    <img x-show="photoPreview" :src="photoPreview" class="absolute inset-0 w-full h-full object-cover z-10">
                                    <input type="file" name="id_picture" accept="image/*" required @change="handleFileUpload" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-30">
                                </div>
                            </div>
                        </div>
                        {{-- ========================================= --}}
                        {{--  👆 END UPDATED SECTION 👆  --}}
                        {{-- ========================================= --}}


                        {{-- Fields --}}
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 md:gap-8">
                            <div class="md:col-span-2 xl:col-span-3">
                                <label class="block text-[10px] md:text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Learner Reference Number (LRN) *</label>
                                <input type="text" name="lrn" x-model="inputs.lrn" maxlength="12" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full h-12 md:h-16 px-4 md:px-6 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 font-mono text-lg md:text-2xl tracking-[0.15em] text-slate-800" placeholder="123456789012" required>
                            </div>

                            <div class="group relative">
                                <label class="block text-[10px] md:text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Last Name *</label>
                                <input type="text" name="last_name" x-model="inputs.last_name" required class="w-full h-12 md:h-14 px-4 md:px-5 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 font-bold text-slate-800 shadow-sm transition-all">
                            </div>
                            <div class="group relative">
                                <label class="block text-[10px] md:text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">First Name *</label>
                                <input type="text" name="first_name" x-model="inputs.first_name" required class="w-full h-12 md:h-14 px-4 md:px-5 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 font-bold text-slate-800 shadow-sm transition-all">
                            </div>
                            <div class="group relative">
                                <label class="block text-[10px] md:text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Middle Name</label>
                                <input type="text" name="middle_name" x-model="inputs.middle_name" placeholder="N/A if none" class="w-full h-12 md:h-14 px-4 md:px-5 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 font-bold text-slate-800 shadow-sm transition-all">
                            </div>

                            <div>
                                <label class="block text-[10px] md:text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Date of Birth *</label>
                                <input type="date" name="date_of_birth" x-model="inputs.date_of_birth" required class="w-full h-12 md:h-14 px-4 md:px-5 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 font-bold text-slate-800">
                            </div>
                            <div>
                                <label class="block text-[10px] md:text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Age *</label>
                                <input type="number" name="age" x-model="inputs.age" required class="w-full h-12 md:h-14 px-4 md:px-5 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 text-center font-black text-blue-600 text-lg">
                            </div>
                            
                            <div>
                                <label class="block text-[10px] md:text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Sex *</label>
                                <select name="gender" x-model="inputs.gender" required class="w-full h-12 md:h-14 px-4 md:px-5 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 font-bold text-slate-800">
                                    <option value="">Select Sex</option>
                                    <option value="Boy">Boy</option>
                                    <option value="Girl">Girl</option>
                                </select>
                            </div>

                            <div class="md:col-span-2 xl:col-span-3 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent my-4"></div> 

                            {{-- Address --}}
                            <div class="md:col-span-2 xl:col-span-1">
                                <label class="block text-[10px] md:text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Region *</label>
                                <select name="region" x-model="inputs.region" required class="w-full h-12 md:h-14 px-4 md:px-5 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 font-bold text-slate-700 text-xs md:text-sm">
                                    <option value="">Select Region</option>
                                    <template x-for="(provinces, region) in regionsData" :key="region">
                                        <option :value="region" x-text="region"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] md:text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Province *</label>
                                <select name="province" x-model="inputs.province" required class="w-full h-12 md:h-14 px-4 md:px-5 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 font-bold text-slate-700 disabled:opacity-50 text-xs md:text-sm" :disabled="!inputs.region">
                                    <option value="">Select Province</option>
                                    <template x-for="province in (regionsData[inputs.region] || [])" :key="province">
                                        <option :value="province" x-text="province"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] md:text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Municipality/City *</label>
                                <input type="text" name="municipality_city" x-model="inputs.municipality_city" required class="w-full h-12 md:h-14 px-4 md:px-5 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 font-bold text-slate-700">
                            </div>
                            <div>
                                <label class="block text-[10px] md:text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Barangay *</label>
                                <input type="text" name="barangay" x-model="inputs.barangay" required class="w-full h-12 md:h-14 px-4 md:px-5 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 font-bold text-slate-700">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] md:text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Street / House No.</label>
                                <input type="text" name="street_address" x-model="inputs.street_address" class="w-full h-12 md:h-14 px-4 md:px-5 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 font-bold text-slate-700">
                            </div>
                            <div>
                                <label class="block text-[10px] md:text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Zip Code *</label>
                                <input type="text" name="zip_code" x-model="inputs.zip_code" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4)" required class="w-full h-12 md:h-14 px-4 md:px-5 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 text-center font-mono text-sm font-bold text-slate-700" placeholder="0000">
                            </div>
                        </div>
                    </div>

                    {{-- Background Info --}}
                    <div class="mt-8 md:mt-12 bg-white p-6 md:p-10 rounded-3xl md:rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-100/50">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
                            <div class="space-y-6 md:space-y-8">
                                
                                {{-- REFERRAL SOURCE DROPDOWN --}}
                                <div>
                                    <label class="block text-[10px] md:text-xs font-black text-blue-900 uppercase mb-3 tracking-wide">Where did you learn about NAS?</label>
                                    <select name="learn_about_nas" x-model="inputs.learn_about_nas" required class="w-full h-12 md:h-14 px-4 md:px-5 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 font-bold text-slate-700 text-xs md:text-sm">
                                        <option value="">Select Source</option>
                                        <option value="NASCENT SAS Facebook Page">NASCENT SAS Facebook Page</option>
                                        <option value="NAS Social Media Page">NAS Social Media Page</option>
                                        <option value="NAS Personnel / Student-Athlete Referral">NAS Personnel / Student-Athlete Referral</option>
                                        <option value="National Sports Association / Coach">National Sports Association / Coach</option>
                                        <option value="News">News</option>
                                        <option value="Local Government Unit">Local Government Unit</option>
                                        <option value="School">School</option>
                                    </select>
                                </div>

                                {{-- CONDITIONAL REFERRER INPUT --}}
                                <div x-show="inputs.learn_about_nas.includes('Referral')" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                                    <label class="block text-[10px] md:text-xs font-black text-blue-900 uppercase mb-3 tracking-wide">Referrer Name</label>
                                    <input type="text" name="referrer_name" x-model="inputs.referrer_name" class="w-full h-12 md:h-14 px-4 md:px-5 rounded-xl md:rounded-2xl border-2 border-blue-100 bg-blue-50/30 font-bold text-slate-700">
                                </div>

                                <div>
                                    <label class="block text-[10px] md:text-xs font-black text-blue-900 uppercase mb-3 tracking-wide">Attended Articulation Campaign?</label>
                                    <div class="flex gap-4">
                                        <label class="cursor-pointer flex-1 items-center justify-center bg-slate-50 px-4 py-3 md:py-4 rounded-xl md:rounded-2xl border-2 border-slate-100 hover:border-blue-400 hover:bg-blue-50 transition w-full relative overflow-hidden group">
                                            <input type="radio" name="attended_campaign" value="Yes" x-model="inputs.attended_campaign" class="peer sr-only"> 
                                            <span class="relative z-10 font-black text-slate-500 peer-checked:text-blue-700 text-xs md:text-sm uppercase tracking-wide">Yes</span>
                                            <div class="absolute inset-0 bg-blue-100 opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                        </label>
                                        <label class="cursor-pointer flex-1 items-center justify-center bg-slate-50 px-4 py-3 md:py-4 rounded-xl md:rounded-2xl border-2 border-slate-100 hover:border-blue-400 hover:bg-blue-50 transition w-full relative overflow-hidden group">
                                            <input type="radio" name="attended_campaign" value="No" x-model="inputs.attended_campaign" class="peer sr-only"> 
                                            <span class="relative z-10 font-black text-slate-500 peer-checked:text-blue-700 text-xs md:text-sm uppercase tracking-wide">No</span>
                                            <div class="absolute inset-0 bg-blue-100 opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6 md:space-y-8">
                                {{-- GROUPS DROPDOWNS --}}
                                <div>
                                    <label class="block text-[10px] md:text-xs font-black text-blue-900 uppercase mb-3 tracking-wide">Member of Indigenous People Group?</label>
                                    <div class="flex flex-col sm:flex-row gap-4 items-start">
                                        <select name="is_ip" x-model="inputs.is_ip" class="w-full sm:w-32 h-12 md:h-14 px-4 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 font-bold text-slate-700">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                        <div class="flex-1 w-full" x-show="inputs.is_ip === 'Yes'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0">
                                            <input type="text" name="ip_group_name" x-model="inputs.ip_group_name" placeholder="Specify Group Name" class="w-full h-12 md:h-14 px-4 md:px-5 rounded-xl md:rounded-2xl border-2 border-blue-100 bg-blue-50/30 font-bold text-slate-700">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] md:text-xs font-black text-blue-900 uppercase mb-3 tracking-wide">Person with Disability?</label>
                                    <div class="flex flex-col sm:flex-row gap-4 items-start">
                                        <select name="is_pwd" x-model="inputs.is_pwd" class="w-full sm:w-32 h-12 md:h-14 px-4 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 font-bold text-slate-700">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                        <div class="flex-1 w-full" x-show="inputs.is_pwd === 'Yes'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0">
                                            <input type="text" name="pwd_disability" x-model="inputs.pwd_disability" placeholder="Specify Disability" class="w-full h-12 md:h-14 px-4 md:px-5 rounded-xl md:rounded-2xl border-2 border-blue-100 bg-blue-50/30 font-bold text-slate-700">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] md:text-xs font-black text-blue-900 uppercase mb-3 tracking-wide">Beneficiary of 4Ps?</label>
                                    <select name="is_4ps" x-model="inputs.is_4ps" class="w-full h-12 md:h-14 px-4 md:px-5 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 font-bold text-slate-700">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- SECTION 2: SPORTS --}}
                <section class="relative group">
                    <div class="flex items-center mb-6 md:mb-10">
                        <div class="bg-blue-600 text-white w-10 h-10 md:w-14 md:h-14 rounded-xl md:rounded-2xl flex items-center justify-center font-black text-lg md:text-2xl shadow-xl mr-4 md:mr-6 z-10">2</div>
                        <div>
                            <h3 class="text-xl md:text-3xl font-black text-slate-800 uppercase tracking-tighter font-heading">Sports & School Profile</h3>
                            <p class="text-xs md:text-sm text-slate-400 font-medium tracking-wide">Your athletic history and current school.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        {{-- FOCUS SPORTS --}}
                        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-6 md:p-10 rounded-3xl md:rounded-[2.5rem] shadow-2xl text-white relative overflow-hidden group">
                            <label class="block text-xs md:text-sm font-bold text-blue-100 uppercase mb-4 tracking-widest relative z-10">Choose Focus Sport</label>
                            <div class="relative z-10">
                                <select name="sport" x-model="inputs.sport" required class="w-full h-12 md:h-16 pl-4 md:pl-6 pr-10 md:pr-12 rounded-xl md:rounded-2xl border-none bg-white/10 backdrop-blur-md text-base md:text-xl font-black text-white focus:ring-4 focus:ring-white/20 shadow-inner appearance-none cursor-pointer hover:bg-white/20 transition-colors">
                                    <option value="" class="text-slate-800">-- Select Sport --</option>
                                    <option value="Aquatics" class="text-slate-800">Aquatics (Swimming)</option>
                                    <option value="Athletics" class="text-slate-800">Athletics (Track and Field)</option>
                                    <option value="Badminton" class="text-slate-800">Badminton</option>
                                    <option value="Gymnastics" class="text-slate-800">Gymnastics (Rhythmic/Artistic)</option>
                                    <option value="Judo" class="text-slate-800">Judo</option>
                                    <option value="Table Tennis" class="text-slate-800">Table Tennis</option>
                                    <option value="Taekwondo" class="text-slate-800">Taekwondo (Kyorugi/Poomsae)</option>
                                    <option value="Weightlifting" class="text-slate-800">Weightlifting</option>
                                </select>
                            </div>

                            {{-- DYNAMIC FOLLOW UP --}}
                            <div class="mt-6 md:mt-8 relative z-10" x-show="inputs.sport" x-transition>
                                <template x-if="inputs.sport === 'Aquatics'">
                                    <div><label class="block text-[10px] md:text-xs font-bold text-blue-200 uppercase mb-2 tracking-wide">Specific Event</label><input type="text" name="sport_specification" x-model="inputs.sport_specification" class="w-full rounded-xl border-none bg-white/20 h-12 md:h-14 px-4 md:px-5 text-white placeholder-blue-200 font-bold focus:ring-2 focus:ring-white/30" placeholder="e.g. Freestyle, Butterfly"></div>
                                </template>
                                <template x-if="inputs.sport === 'Athletics'">
                                    <div><label class="block text-[10px] md:text-xs font-bold text-blue-200 uppercase mb-2 tracking-wide">Specific Event</label><input type="text" name="sport_specification" x-model="inputs.sport_specification" class="w-full rounded-xl border-none bg-white/20 h-12 md:h-14 px-4 md:px-5 text-white placeholder-blue-200 font-bold focus:ring-2 focus:ring-white/30" placeholder="e.g. 100m Dash, Javelin Throw"></div>
                                </template>
                                <template x-if="inputs.sport === 'Taekwondo'">
                                    <div><label class="block text-[10px] md:text-xs font-bold text-blue-200 uppercase mb-2 tracking-wide">Category</label><div class="relative"><select name="sport_specification" x-model="inputs.sport_specification" class="w-full rounded-xl border-none bg-white/20 h-12 md:h-14 px-4 md:px-5 text-white font-bold appearance-none focus:ring-2 focus:ring-white/30"><option value="" class="text-slate-800">Select Category</option><option value="Poomsae" class="text-slate-800">Poomsae</option><option value="Kyorugi" class="text-slate-800">Kyorugi</option></select></div></div>
                                </template>
                                <template x-if="inputs.sport === 'Gymnastics'">
                                    <div><label class="block text-[10px] md:text-xs font-bold text-blue-200 uppercase mb-2 tracking-wide">Category</label><div class="relative"><select name="sport_specification" x-model="inputs.sport_specification" class="w-full rounded-xl border-none bg-white/20 h-12 md:h-14 px-4 md:px-5 text-white font-bold appearance-none focus:ring-2 focus:ring-white/30"><option value="" class="text-slate-800">Select Category</option><option value="Artistic" class="text-slate-800">Artistic</option><option value="Rhythmic" class="text-slate-800">Rhythmic</option></select></div></div>
                                </template>
                            </div>
                        </div>

                        {{-- ACHIEVEMENTS --}}
                        <div class="space-y-6 md:space-y-8">
                            <div class="bg-white p-6 md:p-8 rounded-3xl md:rounded-[2.5rem] border border-slate-100 shadow-lg">
                                <label class="block text-[10px] md:text-xs font-black text-slate-400 uppercase mb-4 tracking-widest">Palarong Pambansa Podium Finisher?</label>
                                <div class="flex gap-4">
                                    <label class="cursor-pointer relative w-full group">
                                        <input type="radio" name="palaro_finisher" value="Yes" x-model="inputs.palaro_finisher" class="peer sr-only" required>
                                        <div class="w-full py-3 md:py-4 text-center rounded-xl md:rounded-2xl border-2 border-slate-100 text-slate-400 text-sm md:text-base font-black tracking-widest peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition-all">YES</div>
                                    </label>
                                    <label class="cursor-pointer relative w-full group">
                                        <input type="radio" name="palaro_finisher" value="No" x-model="inputs.palaro_finisher" class="peer sr-only" required>
                                        <div class="w-full py-3 md:py-4 text-center rounded-xl md:rounded-2xl border-2 border-slate-100 text-slate-400 text-sm md:text-base font-black tracking-widest peer-checked:border-slate-400 peer-checked:bg-slate-100 peer-checked:text-slate-800 transition-all">NO</div>
                                    </label>
                                </div>
                            </div>
                            <div class="bg-white p-6 md:p-8 rounded-3xl md:rounded-[2.5rem] border border-slate-100 shadow-lg">
                                <label class="block text-[10px] md:text-xs font-black text-slate-400 uppercase mb-4 tracking-widest">Batang Pinoy Podium Finisher?</label>
                                <div class="flex gap-4">
                                    <label class="cursor-pointer relative w-full group">
                                        <input type="radio" name="batang_pinoy_finisher" value="Yes" x-model="inputs.batang_pinoy_finisher" class="peer sr-only" required>
                                        <div class="w-full py-3 md:py-4 text-center rounded-xl md:rounded-2xl border-2 border-slate-100 text-slate-400 text-sm md:text-base font-black tracking-widest peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition-all">YES</div>
                                    </label>
                                    <label class="cursor-pointer relative w-full group">
                                        <input type="radio" name="batang_pinoy_finisher" value="No" x-model="inputs.batang_pinoy_finisher" class="peer sr-only" required>
                                        <div class="w-full py-3 md:py-4 text-center rounded-xl md:rounded-2xl border-2 border-slate-100 text-slate-400 text-sm md:text-base font-black tracking-widest peer-checked:border-slate-400 peer-checked:bg-slate-100 peer-checked:text-slate-800 transition-all">NO</div>
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- SCHOOL INFORMATION --}}
                    <div class="mt-8 md:mt-12 bg-white p-6 md:p-10 rounded-3xl md:rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-100/50">
                        <h4 class="text-sm md:text-base font-black text-slate-800 uppercase tracking-tighter mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            School Information
                        </h4>
                        <div>
                            <label class="block text-[10px] md:text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">School Type *</label>
                            <select name="school_type" x-model="inputs.school_type" required class="w-full h-12 md:h-14 px-4 md:px-5 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 font-bold text-slate-700">
                                <option value="">Select Type</option>
                                <option value="Public">Public</option>
                                <option value="Private">Private</option>
                            </select>
                        </div>
                    </div>
                </section>

                {{-- SECTION 3: GUARDIAN --}}
                <section class="relative group">
                    <div class="flex items-center mb-6 md:mb-10">
                        <div class="bg-blue-600 text-white w-10 h-10 md:w-14 md:h-14 rounded-xl md:rounded-2xl flex items-center justify-center font-black text-lg md:text-2xl shadow-xl mr-4 md:mr-6 z-10">3</div>
                        <div>
                            <h3 class="text-xl md:text-3xl font-black text-slate-800 uppercase tracking-tighter font-heading">PARENTS' & DESIGNATED GUARDIAN'S INFORMATION</h3>
                            <p class="text-xs md:text-sm text-slate-400 font-medium tracking-wide">Emergency contact details.</p>
                        </div>
                    </div>
                    <div class="bg-white p-6 md:p-10 rounded-3xl md:rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                        <div>
                            <label class="block text-[10px] md:text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Designated Guardian *</label>
                            <input type="text" name="guardian_name" x-model="inputs.guardian_name" required class="w-full h-12 md:h-16 px-4 md:px-6 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 text-slate-800 font-bold transition-all shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] md:text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Relationship to the Applicant *</label>
                            <input type="text" name="guardian_relationship" x-model="inputs.guardian_relationship" required class="w-full h-12 md:h-16 px-4 md:px-6 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 text-slate-800 font-bold transition-all shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] md:text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Email Address *</label>
                            <input type="email" name="guardian_email" x-model="inputs.guardian_email" required class="w-full h-12 md:h-16 px-4 md:px-6 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 text-slate-800 font-bold transition-all shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] md:text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Contact Number *</label>
                            <input type="text" name="guardian_contact" x-model="inputs.guardian_contact" maxlength="11" required class="w-full h-12 md:h-16 px-4 md:px-6 rounded-xl md:rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 text-center font-mono text-lg md:text-xl font-bold tracking-widest text-blue-600 transition-all shadow-sm" placeholder="09xxxxxxxxx">
                        </div>
                    </div>
                </section>

                {{-- DATA PRIVACY CONSENT --}}
                <div class="bg-white p-6 md:p-8 rounded-3xl md:rounded-[2.5rem] border border-slate-200 shadow-inner">
                    <h4 class="text-lg md:text-xl font-black text-slate-800 uppercase tracking-tighter mb-4 flex items-center">
                        <svg class="w-5 h-5 md:w-6 md:h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Data Privacy Consent
                    </h4>
                    
                    <div class="h-48 md:h-64 overflow-y-auto bg-slate-50 p-4 md:p-6 rounded-xl border border-slate-200 text-slate-600 text-[10px] md:text-sm leading-relaxed mb-6 space-y-4 shadow-sm custom-scrollbar text-justify font-medium">
                        <p>I/We certify that the above information is true, complete and correct. I/We understand that any false or misleading information shall render my/our child ineligible for admission or may be subject for dismissal. If admitted, I/We agreed to abide by the policies, rules and regulations of the National Academy of Sports.</p>
                        
                        <p>For and in behalf of our minor child, I/We declare and confirm that, of my/our our volition, submit and will continue to submit, necessary information and documents to the National Academy of Sports (“NAS”), with the intention of applying, if qualified, enroll my/our child for the upcoming school year. In this regard, I/We acknowledge and understand that NAS requires our and our child’s personal and/or sensitive information (collectively “information”), for legitimate and lawful purposes, including but limited to verifying our identity, evaluating academic qualifications and eligibility, assessing physical fitness, and facilitating official communication with us.</p>
                        
                        <p>We acknowledge and agree that:</p>
                        <ul class="list-disc pl-5 space-y-2">
                            <li>NAS may collect, record, use, process, store, and transmit our information in accordance with the Data Privacy Act of 2012, its implementing Rules and Regulations (IRR), and other applicable laws.</li>
                            <li>NAS may disclose our information only with our consent, or when required or authorized under relevant laws, rules, and regulations.</li>
                            <li>NAS shall adopt appropriate organizational, physical, and technical measurement to ensure the confidentiality, integrity, and availability of our information.</li>
                            <li>NAS may retain our information only for as long as necessary to fulfill the purposes stated herein, or as required by applicable laws and regulations.</li>
                        </ul>

                        <p>We also understand that as data subjects under the Data Privacy Act of 2012, we have the right to:</p>
                        <ul class="list-disc pl-5 space-y-2">
                            <li>Inquire about, request access to, review or obtain a copy of our information in the custody of NAS.</li>
                            <li>Request correction or updating our information;</li>
                            <li>Withdraw or withhold consent, object to processing or request deletion of our information subject to limitations where NAS has a legal obligation or legitimate purpose to retain such information.</li>
                        </ul>

                        <p>I/We understand that refusal to provide the required information, or subsequent withdrawal of consent, may prevent NAS from processing our application and carrying out the purpose described in this document, we may contact NASCENT SAS secretariat at <a href="mailto:nascentsas@deped.gov.ph" class="text-blue-600 font-bold hover:underline">nascentsas@deped.gov.ph</a>.</p>
                    </div>

                    <label class="flex items-start gap-3 md:gap-4 p-3 md:p-4 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer border border-transparent hover:border-slate-200">
                        <div class="relative flex items-center pt-1">
                            <input type="checkbox" x-model="privacyConsent" class="w-5 h-5 md:w-6 md:h-6 rounded-lg border-2 border-slate-300 text-blue-600 focus:ring-blue-500 transition-all cursor-pointer">
                        </div>
                        <span class="text-xs md:text-sm font-bold text-slate-700 select-none leading-relaxed">
                            By signing below, I/We declared that we read, understand, and voluntarily consent to the collection, recording, use, processing, storage, disclosure, and transmission of our child’s information, including photographs,  videos, storage, data or documents, submitted to NAS in accordance with the Data Privacy Act of 2012 and applicable regulations.
                        </span>
                    </label>
                </div>

                {{-- SUBMIT BUTTON --}}
                <div class="pt-8 md:pt-12 border-t border-slate-200 flex justify-center pb-8">
                    <button type="submit" 
                        :disabled="!privacyConsent"
                        :class="!privacyConsent ? 'opacity-50 cursor-not-allowed grayscale' : 'hover:scale-105 hover:shadow-blue-300'"
                        class="group relative w-full md:w-auto bg-slate-900 hover:bg-blue-600 text-white font-black py-4 md:py-6 px-16 md:px-24 rounded-2xl md:rounded-full shadow-2xl shadow-slate-400 transition-all duration-300 transform overflow-hidden">
                        <span class="relative z-10 uppercase tracking-[0.25em] text-xs md:text-sm md:text-base flex items-center justify-center">
                            Submit Application
                            <svg class="w-4 h-4 md:w-5 md:h-5 ml-3 md:ml-4 -mr-1 group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </span>
                        <div class="absolute inset-0 bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300 ease-out"></div>
                    </button>
                </div>

            </form>
        </div>
    </div>
    
    <style>
        .bg-pattern {
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .perspective-1000 {
            perspective: 1000px;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</x-applicant-layout>