<x-applicant-layout>
    <script>
        function applicantForm() {
            return {
                photoPreview: null,
                selectedRegion: '',
                selectedProvince: '',
                selectedSport: '',
                referralSource: '',
                isIP: 'No',
                isPWD: 'No',
                is4Ps: 'No',
                
                // Regions Data (Complete structure for dropdown logic)
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

    <div x-data="applicantForm()" class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8 font-sans bg-pattern">
        
        {{-- HEADER SECTION --}}
        <div class="max-w-7xl mx-auto flex flex-col items-center justify-center mb-16 text-center animate-fade-in-down">
            <div class="flex items-center justify-center gap-8 md:gap-12 mb-8 transition-transform hover:scale-105 duration-700 ease-out">
                <img src="{{ asset('images/nas/nas-logo-sidebar.png') }}" class="h-28 md:h-36 w-auto object-contain drop-shadow-2xl" alt="NAS Logo">
                <div class="h-20 w-px bg-gradient-to-b from-transparent via-slate-300 to-transparent hidden md:block"></div>
                <img src="{{ asset('images/nas/NASCENT SAS.png') }}" class="h-20 md:h-24 w-auto object-contain drop-shadow-xl" alt="NASCENT SAS Logo">
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 uppercase tracking-tighter leading-none bg-clip-text text-transparent bg-gradient-to-r from-blue-900 via-slate-800 to-blue-900 max-w-5xl mx-auto drop-shadow-sm font-heading">
                NAS Annual Search for Competent, Exceptional, Notable, and Talented Student-Athlete Scholars
            </h1>
            <p class="mt-4 text-blue-600 font-bold tracking-[0.2em] text-sm md:text-lg uppercase">(NASCENT SAS)</p>
        </div>

        <div class="max-w-7xl mx-auto bg-white shadow-2xl shadow-blue-100/70 rounded-[2.5rem] overflow-hidden border border-slate-100 relative">
            
            {{-- TOP ACCENT LINE --}}
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500"></div>

            {{-- STICKY STATUS BAR --}}
            <div class="p-6 md:p-8 bg-white/90 backdrop-blur-xl border-b border-slate-200 sticky top-0 z-30 shadow-sm flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center bg-slate-50 border border-slate-200 pl-2 pr-6 py-2 rounded-full shadow-sm ring-1 ring-slate-100">
                    <span class="relative flex h-8 w-8 mr-3 items-center justify-center bg-yellow-100 rounded-full">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                    </span>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Current Phase</p>
                        <p class="text-xs md:text-sm font-black text-blue-900 uppercase tracking-tight leading-none">
                            Phase 1: Registration & Profiling
                        </p>
                    </div>
                </div>

                {{-- STEPPER --}}
                <div class="hidden md:flex gap-1">
                    @foreach(['Register', 'Assess', 'Verify', 'Result'] as $index => $step)
                        <div class="flex flex-col items-center w-24 group">
                            <div class="h-1.5 w-full rounded-full mb-2 transition-all duration-300 {{ $index === 0 ? 'bg-blue-600 scale-105' : 'bg-slate-200 group-hover:bg-slate-300' }}"></div>
                            <span class="text-[10px] font-bold uppercase transition-colors duration-300 {{ $index === 0 ? 'text-blue-600' : 'text-slate-300 group-hover:text-slate-400' }}">{{ $step }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- MAIN FORM --}}
            <form id="applicantForm" method="POST" action="{{ route('applicant.store') }}" enctype="multipart/form-data" class="p-8 md:p-16 space-y-20">
                @csrf

                {{-- ERROR DISPLAY --}}
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-r-xl shadow-lg shadow-red-100 mb-10 transform scale-100 animate-pulse flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="w-full">
                            <h3 class="text-sm font-black text-red-800 uppercase tracking-wide mb-2">Submission Failed</h3>
                            <ul class="list-disc list-inside text-xs text-red-700 font-bold space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- SECTION 1: PERSONAL INFORMATION --}}
                <section class="relative group">
                    <div class="flex items-center mb-10">
                        <div class="bg-blue-600 text-white w-14 h-14 rounded-2xl flex items-center justify-center font-black text-2xl shadow-xl shadow-blue-200 mr-6 z-10 group-hover:scale-110 transition-transform duration-300 transform rotate-3">1</div>
                        <div>
                            <h3 class="text-3xl font-black text-slate-800 uppercase tracking-tighter font-heading">Personal Information</h3>
                            <p class="text-sm text-slate-400 font-medium tracking-wide">Please provide accurate personal details.</p>
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row gap-16">
                        {{-- 2x2 Photo Upload --}}
                        <div class="w-full lg:w-72 flex-shrink-0 flex flex-col items-center">
                            <div class="relative group cursor-pointer w-64 h-64 mx-auto perspective-1000">
                                <div class="absolute -inset-2 bg-gradient-to-tr from-blue-500 to-cyan-400 rounded-[2rem] blur opacity-20 group-hover:opacity-50 transition duration-500"></div>
                                <div class="relative w-full h-full bg-white rounded-[1.75rem] flex items-center justify-center overflow-hidden border-4 border-slate-100 group-hover:border-blue-500/30 group-hover:shadow-2xl transition-all duration-300 transform group-hover:-translate-y-1">
                                    <template x-if="!photoPreview">
                                        <div class="flex flex-col items-center text-slate-400 group-hover:text-blue-600 transition-colors p-6 text-center">
                                            <svg class="w-16 h-16 mb-4 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-xs font-black uppercase tracking-widest">Upload 2x2 Photo</span>
                                            <span class="text-[10px] font-bold text-slate-400 mt-2 bg-slate-100 px-2 py-1 rounded">White Background Required</span>
                                        </div>
                                    </template>
                                    <img x-show="photoPreview" :src="photoPreview" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    <input type="file" name="id_picture" accept="image/*" required @change="handleFileUpload" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                                </div>
                            </div>
                        </div>

                        {{-- Personal Fields --}}
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <div class="md:col-span-3">
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Learner Reference Number (LRN) *</label>
                                <input type="text" name="lrn" maxlength="12" required oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="{{ old('lrn') }}"
                                    class="w-full h-16 px-6 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 font-mono text-2xl tracking-[0.15em] placeholder-slate-300 shadow-sm transition-all duration-300 text-slate-800" placeholder="123456789012">
                            </div>

                            <div class="group relative">
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Last Name *</label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full h-14 px-5 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 font-bold text-slate-800 shadow-sm transition-all">
                            </div>
                            <div class="group relative">
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">First Name *</label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full h-14 px-5 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 font-bold text-slate-800 shadow-sm transition-all">
                            </div>
                            <div class="group relative">
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Middle Name</label>
                                <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="w-full h-14 px-5 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 font-bold text-slate-800 shadow-sm transition-all" placeholder="N/A if none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Date of Birth *</label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required class="w-full h-14 px-5 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 shadow-sm text-slate-800 font-bold">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Age *</label>
                                <input type="number" name="age" value="{{ old('age') }}" required class="w-full h-14 px-5 rounded-2xl border-2 border-slate-100 bg-slate-50 text-center font-black text-blue-600 text-lg shadow-sm focus:bg-white focus:border-blue-500 focus:ring-0">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Sex *</label>
                                <div class="relative">
                                    <select name="gender" required class="w-full h-14 px-5 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 shadow-sm font-bold text-slate-800 appearance-none transition-colors">
                                        <option value="">Select Sex</option>
                                        <option value="Boy">Boy</option>
                                        <option value="Girl">Girl</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="md:col-span-3 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent my-4"></div> 

                            {{-- Address Fields --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Region *</label>
                                <select name="region" x-model="selectedRegion" required class="w-full h-14 px-5 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 shadow-sm text-sm font-bold text-slate-700">
                                    <option value="">Select Region</option>
                                    <template x-for="(provinces, region) in regionsData" :key="region"><option :value="region" x-text="region"></option></template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Province *</label>
                                <select name="province" required class="w-full h-14 px-5 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 shadow-sm disabled:opacity-50 text-sm font-bold text-slate-700" :disabled="!selectedRegion">
                                    <option value="">Select Province</option>
                                    <template x-for="province in regionsData[selectedRegion]" :key="province"><option :value="province" x-text="province"></option></template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Municipality/City *</label>
                                <input type="text" name="municipality_city" value="{{ old('municipality_city') }}" required class="w-full h-14 px-5 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 shadow-sm text-sm font-bold text-slate-700">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Barangay *</label>
                                <input type="text" name="barangay" value="{{ old('barangay') }}" required class="w-full h-14 px-5 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 shadow-sm text-sm font-bold text-slate-700">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Street / House No.</label>
                                <input type="text" name="street_address" value="{{ old('street_address') }}" class="w-full h-14 px-5 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 shadow-sm text-sm font-bold text-slate-700">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Zip Code *</label>
                                <input type="text" name="zip_code" value="{{ old('zip_code') }}" required class="w-full h-14 px-5 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 shadow-sm text-center font-mono text-sm font-bold text-slate-700">
                            </div>
                        </div>
                    </div>

                    {{-- Background Info --}}
                    <div class="mt-12 bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-100/50">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                            <div class="space-y-8">
                                <div>
                                    <label class="block text-xs font-black text-blue-900 uppercase mb-3 tracking-wide">Where did you learn about NAS?</label>
                                    <div class="relative">
                                        <select name="learn_about_nas" x-model="referralSource" required class="w-full h-14 px-5 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 shadow-sm text-sm font-bold text-slate-700 appearance-none transition-colors">
                                            <option value="">Select Source</option>
                                            <option value="NASCENT SAS Facebook Page">NASCENT SAS Facebook Page</option>
                                            <option value="NAS Social Media Page">NAS Social Media Page</option>
                                            <option value="NAS Personnel / Student-Athlete Referral">NAS Personnel / Student-Athlete Referral</option>
                                            <option value="National Sports Association / Coach">National Sports Association / Coach</option>
                                            <option value="News">News</option>
                                            <option value="Local Government Unit">Local Government Unit</option>
                                            <option value="School">School</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="referralSource.includes('Referral')" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                                    <label class="block text-xs font-black text-blue-900 uppercase mb-3 tracking-wide">Referrer Name</label>
                                    <input type="text" name="referrer_name" value="{{ old('referrer_name') }}" class="w-full h-14 px-5 rounded-2xl border-2 border-blue-100 bg-blue-50/30 focus:bg-white focus:border-blue-500 focus:ring-0 shadow-sm font-bold text-slate-700">
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-blue-900 uppercase mb-3 tracking-wide">Attended Articulation Campaign?</label>
                                    <div class="flex gap-4">
                                        <label class="cursor-pointer flex-1 items-center justify-center bg-slate-50 px-4 py-4 rounded-2xl border-2 border-slate-100 hover:border-blue-400 hover:bg-blue-50 transition w-full relative overflow-hidden group">
                                            <input type="radio" name="attended_campaign" value="Yes" class="peer sr-only"> 
                                            <span class="relative z-10 font-black text-slate-500 peer-checked:text-blue-700 text-sm uppercase tracking-wide group-hover:text-blue-600 transition-colors">Yes</span>
                                            <div class="absolute inset-0 bg-blue-100 opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                        </label>
                                        <label class="cursor-pointer flex-1 items-center justify-center bg-slate-50 px-4 py-4 rounded-2xl border-2 border-slate-100 hover:border-blue-400 hover:bg-blue-50 transition w-full relative overflow-hidden group">
                                            <input type="radio" name="attended_campaign" value="No" class="peer sr-only" checked> 
                                            <span class="relative z-10 font-black text-slate-500 peer-checked:text-blue-700 text-sm uppercase tracking-wide group-hover:text-blue-600 transition-colors">No</span>
                                            <div class="absolute inset-0 bg-blue-100 opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-8">
                                <div>
                                    <label class="block text-xs font-black text-blue-900 uppercase mb-3 tracking-wide">Member of Indigenous Group?</label>
                                    <div class="flex gap-4 items-start">
                                        <select name="is_ip" x-model="isIP" class="w-32 h-14 px-4 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 shadow-sm font-bold text-slate-700">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                        <div class="flex-1" x-show="isIP === 'Yes'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0">
                                            <input type="text" name="ip_group_name" value="{{ old('ip_group_name') }}" placeholder="Specify Group Name" class="w-full h-14 px-5 rounded-2xl border-2 border-blue-100 bg-blue-50/30 focus:bg-white focus:border-blue-500 focus:ring-0 shadow-sm font-bold text-slate-700">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-blue-900 uppercase mb-3 tracking-wide">Person with Disability?</label>
                                    <div class="flex gap-4 items-start">
                                        <select name="is_pwd" x-model="isPWD" class="w-32 h-14 px-4 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 shadow-sm font-bold text-slate-700">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                        <div class="flex-1" x-show="isPWD === 'Yes'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0">
                                            <input type="text" name="pwd_disability" value="{{ old('pwd_disability') }}" placeholder="Specify Disability" class="w-full h-14 px-5 rounded-2xl border-2 border-blue-100 bg-blue-50/30 focus:bg-white focus:border-blue-500 focus:ring-0 shadow-sm font-bold text-slate-700">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-blue-900 uppercase mb-3 tracking-wide">Beneficiary of 4Ps?</label>
                                    <select name="is_4ps" x-model="is4Ps" class="w-full h-14 px-5 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 shadow-sm font-bold text-slate-700">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- SECTION 2: SPORTS & SCHOOL --}}
                <section class="relative group">
                    <div class="flex items-center mb-10">
                        <div class="bg-blue-600 text-white w-14 h-14 rounded-2xl flex items-center justify-center font-black text-2xl shadow-xl shadow-blue-200 mr-6 z-10 group-hover:scale-110 transition-transform duration-300 transform rotate-3">2</div>
                        <div>
                            <h3 class="text-3xl font-black text-slate-800 uppercase tracking-tighter font-heading">Sports & School Profile</h3>
                            <p class="text-sm text-slate-400 font-medium tracking-wide">Your athletic history and current school.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        {{-- FOCUS SPORTS CARD --}}
                        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-10 rounded-[2.5rem] shadow-2xl shadow-blue-200 text-white relative overflow-hidden group hover:scale-[1.01] transition-transform duration-500">
                            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl group-hover:opacity-20 transition-opacity duration-500"></div>
                            <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-40 h-40 bg-cyan-400 opacity-20 rounded-full blur-2xl"></div>
                            
                            <label class="block text-sm font-bold text-blue-100 uppercase mb-4 tracking-widest relative z-10">Choose Focus Sport</label>
                            <div class="relative z-10">
                                <select name="sport" x-model="selectedSport" required class="w-full h-16 pl-6 pr-12 rounded-2xl border-none bg-white/10 backdrop-blur-md text-xl font-black text-white focus:ring-4 focus:ring-white/20 shadow-inner appearance-none cursor-pointer hover:bg-white/20 transition-colors">
                                    <option value="" class="text-slate-800">-- Select Sport --</option>
                                    <option value="Aquatics" class="text-slate-800">Aquatics (Swimming)</option>
                                    <option value="Athletics" class="text-slate-800">Athletics (Track and Field)</option>
                                    <option value="Badminton" class="text-slate-800">Badminton</option>
                                    <option value="Gymnastics" class="text-slate-800">Gymnastics</option>
                                    <option value="Judo" class="text-slate-800">Judo</option>
                                    <option value="Table Tennis" class="text-slate-800">Table Tennis</option>
                                    <option value="Taekwondo" class="text-slate-800">Taekwondo</option>
                                    <option value="Weightlifting" class="text-slate-800">Weightlifting</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>

                            {{-- DYNAMIC FOLLOW UP --}}
                            <div class="mt-8 relative z-10" x-show="selectedSport" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                                <template x-if="selectedSport === 'Aquatics'">
                                    <div><label class="block text-xs font-bold text-blue-200 uppercase mb-2 tracking-wide">Specific Event</label><input type="text" name="sport_specification" class="w-full rounded-xl border-none bg-white/20 h-14 px-5 text-white placeholder-blue-200 font-bold focus:ring-2 focus:ring-white/30" placeholder="e.g. Freestyle"></div>
                                </template>
                                <template x-if="selectedSport === 'Athletics'">
                                    <div><label class="block text-xs font-bold text-blue-200 uppercase mb-2 tracking-wide">Specific Event</label><input type="text" name="sport_specification" class="w-full rounded-xl border-none bg-white/20 h-14 px-5 text-white placeholder-blue-200 font-bold focus:ring-2 focus:ring-white/30" placeholder="e.g. 100m Dash"></div>
                                </template>
                                <template x-if="selectedSport === 'Taekwondo'">
                                    <div><label class="block text-xs font-bold text-blue-200 uppercase mb-2 tracking-wide">Category</label><div class="relative"><select name="sport_specification" class="w-full rounded-xl border-none bg-white/20 h-14 px-5 text-white font-bold appearance-none focus:ring-2 focus:ring-white/30"><option value="Poomsae" class="text-slate-800">Poomsae</option><option value="Kyorugi" class="text-slate-800">Kyorugi</option></select><div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-white"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div></div></div>
                                </template>
                                <template x-if="selectedSport === 'Gymnastics'">
                                    <div><label class="block text-xs font-bold text-blue-200 uppercase mb-2 tracking-wide">Category</label><div class="relative"><select name="sport_specification" class="w-full rounded-xl border-none bg-white/20 h-14 px-5 text-white font-bold appearance-none focus:ring-2 focus:ring-white/30"><option value="Artistic" class="text-slate-800">Artistic</option><option value="Rhythmic" class="text-slate-800">Rhythmic</option></select><div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-white"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div></div></div>
                                </template>
                            </div>
                        </div>

                        {{-- ACHIEVEMENTS & SCHOOL TYPE --}}
                        <div class="space-y-8">
                            {{-- PALARO --}}
                            <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-lg shadow-slate-100 hover:border-blue-200 hover:shadow-xl transition-all duration-300">
                                <label class="block text-xs font-black text-slate-400 uppercase mb-4 tracking-widest">Palarong Pambansa Podium Finisher?</label>
                                <div class="flex gap-4">
                                    <label class="cursor-pointer relative w-full group">
                                        <input type="radio" name="palaro_finisher" value="Yes" class="peer sr-only" required>
                                        <div class="w-full py-4 text-center rounded-2xl border-2 border-slate-100 text-slate-400 font-black tracking-widest peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition-all group-hover:border-blue-200">YES</div>
                                    </label>
                                    <label class="cursor-pointer relative w-full group">
                                        <input type="radio" name="palaro_finisher" value="No" class="peer sr-only" required>
                                        <div class="w-full py-4 text-center rounded-2xl border-2 border-slate-100 text-slate-400 font-black tracking-widest peer-checked:border-slate-400 peer-checked:bg-slate-100 peer-checked:text-slate-800 transition-all group-hover:border-slate-300">NO</div>
                                    </label>
                                </div>
                            </div>

                            {{-- BATANG PINOY --}}
                            <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-lg shadow-slate-100 hover:border-blue-200 hover:shadow-xl transition-all duration-300">
                                <label class="block text-xs font-black text-slate-400 uppercase mb-4 tracking-widest">Batang Pinoy Podium Finisher?</label>
                                <div class="flex gap-4">
                                    <label class="cursor-pointer relative w-full group">
                                        <input type="radio" name="batang_pinoy_finisher" value="Yes" class="peer sr-only" required>
                                        <div class="w-full py-4 text-center rounded-2xl border-2 border-slate-100 text-slate-400 font-black tracking-widest peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition-all group-hover:border-blue-200">YES</div>
                                    </label>
                                    <label class="cursor-pointer relative w-full group">
                                        <input type="radio" name="batang_pinoy_finisher" value="No" class="peer sr-only" required>
                                        <div class="w-full py-4 text-center rounded-2xl border-2 border-slate-100 text-slate-400 font-black tracking-widest peer-checked:border-slate-400 peer-checked:bg-slate-100 peer-checked:text-slate-800 transition-all group-hover:border-slate-300">NO</div>
                                    </label>
                                </div>
                            </div>

                            {{-- SCHOOL TYPE --}}
                            <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-lg shadow-slate-100 hover:border-blue-200 hover:shadow-xl transition-all duration-300">
                                <label class="block text-xs font-black text-slate-400 uppercase mb-4 tracking-widest">Current School Type</label>
                                <div class="relative">
                                    <select name="school_type" class="w-full h-14 pl-6 pr-12 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 font-bold text-slate-700 appearance-none cursor-pointer transition-colors" required>
                                        <option value="">Select Type</option>
                                        <option value="Public">Public</option>
                                        <option value="Private">Private</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-slate-400">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- SECTION 3: GUARDIAN --}}
                <section class="relative group">
                    <div class="flex items-center mb-10">
                        <div class="bg-blue-600 text-white w-14 h-14 rounded-2xl flex items-center justify-center font-black text-2xl shadow-xl shadow-blue-200 mr-6 z-10 group-hover:scale-110 transition-transform duration-300 transform rotate-3">3</div>
                        <div>
                            <h3 class="text-3xl font-black text-slate-800 uppercase tracking-tighter font-heading">Guardian Information</h3>
                            <p class="text-sm text-slate-400 font-medium tracking-wide">Emergency contact details.</p>
                        </div>
                    </div>
                    <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-100 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Guardian Name *</label>
                            <input type="text" name="guardian_name" value="{{ old('guardian_name') }}" required class="w-full h-16 px-6 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 text-slate-800 font-bold transition-all shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Relationship *</label>
                            <input type="text" name="guardian_relationship" value="{{ old('guardian_relationship') }}" required class="w-full h-16 px-6 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 text-slate-800 font-bold transition-all shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Email Address *</label>
                            <input type="email" name="guardian_email" value="{{ old('guardian_email') }}" required class="w-full h-16 px-6 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 text-slate-800 font-bold transition-all shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">Mobile Number *</label>
                            <input type="text" name="guardian_contact" value="{{ old('guardian_contact') }}" maxlength="11" required class="w-full h-16 px-6 rounded-2xl border-2 border-slate-100 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-0 text-center font-mono text-xl font-bold tracking-widest text-blue-600 transition-all shadow-sm" placeholder="09xxxxxxxxx">
                        </div>
                    </div>
                </section>

                {{-- SUBMIT BUTTON --}}
                <div class="pt-12 border-t border-slate-200 flex justify-center pb-8">
                    <button type="submit" class="group relative w-full md:w-auto bg-slate-900 hover:bg-blue-600 text-white font-black py-6 px-24 rounded-full shadow-2xl shadow-slate-400 hover:shadow-blue-300 transition-all duration-300 transform hover:-translate-y-1 hover:scale-105 overflow-hidden">
                        <span class="relative z-10 uppercase tracking-[0.25em] text-sm md:text-base flex items-center justify-center">
                            Submit Application
                            <svg class="w-5 h-5 ml-4 -mr-1 group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
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
    </style>
</x-applicant-layout>