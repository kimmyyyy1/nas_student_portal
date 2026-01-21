<x-app-layout>
    <x-slot name="header">
        
        {{-- ============================================================= --}}
        {{-- 📱 MOBILE HEADER (Visible lang sa Cellphone)                  --}}
        {{-- ============================================================= --}}
        <div class="flex md:hidden items-center justify-between w-full py-2">
            
            {{-- KALIWA: Academic Report Badge --}}
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 tracking-wide uppercase shadow-sm border border-green-200">
                <i class='bx bxs-report mr-1.5 text-sm'></i> Academic Report
            </span>

            {{-- KANAN: Back Button --}}
            {{-- FIX: Added 'flex-shrink-0' at 'w-10 h-10' para hindi mapipi --}}
            <a href="{{ route('reports.index') }}" 
               class="flex-shrink-0 flex items-center justify-center w-10 h-10 bg-white border border-gray-300 rounded-full text-gray-700 shadow-sm hover:bg-gray-50 active:scale-95 transition-all ml-2"
               style="min-width: 40px; min-height: 40px;"> 
                <i class='bx bx-arrow-back text-xl'></i>
            </a>

        </div>


        {{-- ============================================================= --}}
        {{-- 💻 DESKTOP HEADER (Visible lang sa PC/Laptop)                 --}}
        {{-- ============================================================= --}}
        <div class="hidden md:flex items-center justify-between w-full py-2">
            
            {{-- KALIWA: Back Button + Title --}}
            <div class="flex items-center gap-4">
                <a href="{{ route('reports.index') }}" 
                   class="group flex items-center text-gray-500 hover:text-indigo-600 transition-colors p-1" 
                   title="Back to Reports">
                    <i class='bx bx-arrow-back text-2xl transform group-hover:-translate-x-1 transition-transform'></i>
                </a>

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Grade Sheets Generator') }}
                </h2>
            </div>

            {{-- KANAN: Badge --}}
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 tracking-wide uppercase shadow-sm border border-green-200">
                <i class='bx bxs-report mr-1.5 text-sm'></i> Academic Report
            </span>

        </div>

    </x-slot>

    <div class="py-6 md:py-12" x-data="{ showFilters: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- 🟢 MOBILE FILTER TOGGLE BUTTON --}}
            <div class="md:hidden mb-6">
                <button @click="showFilters = !showFilters" 
                        class="w-full flex items-center justify-between bg-white p-4 rounded-lg shadow-sm border border-gray-200 text-gray-700 font-bold transition hover:bg-gray-50 active:scale-[0.99]">
                    <span class="flex items-center text-indigo-600"><i class='bx bx-filter-alt mr-2 text-xl'></i> Filter Options</span>
                    <i class='bx bx-chevron-down text-2xl transition-transform duration-200 text-gray-400' :class="{'rotate-180': showFilters}"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                
                {{-- 🟢 FILTER PANEL --}}
                <div class="md:col-span-1 md:!block" 
                     x-show="showFilters" 
                     x-cloak 
                     x-transition.opacity.duration.300ms
                     :class="{'block': showFilters, 'hidden': !showFilters}">
                    
                    <div class="bg-white shadow-md rounded-lg p-6 sticky top-6 border border-gray-200">
                        <h3 class="font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider flex items-center border-b pb-2">
                            <i class='bx bx-slider-alt mr-2 text-lg text-indigo-600'></i>
                            Filter Selection
                        </h3>
                        
                        <form action="#" method="GET"> 
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">School Year</label>
                                <select class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option>2024-2025</option>
                                    <option>2023-2024</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Grade Level</label>
                                <select class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="">-- Select Grade --</option>
                                    @foreach(['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'] as $gl)
                                        <option value="{{ $gl }}">{{ $gl }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Section</label>
                                <select class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="">-- Select Section --</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Subject</label>
                                <select class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="">-- Select Subject --</option>
                                </select>
                            </div>

                            <div class="mb-6">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Grading Period</label>
                                <select class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="1">1st Quarter</option>
                                    <option value="2">2nd Quarter</option>
                                    <option value="3">3rd Quarter</option>
                                    <option value="4">4th Quarter</option>
                                </select>
                            </div>

                            <button type="button" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition text-sm flex justify-center items-center group transform hover:-translate-y-0.5">
                                <i class='bx bx-layer mr-2 text-lg group-hover:animate-bounce'></i>
                                Generate Sheet
                            </button>
                        </form>
                    </div>
                </div>

                {{-- 🟢 PREVIEW PANEL --}}
                <div class="md:col-span-3">
                    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 min-h-[400px] md:min-h-[500px]">
                        
                        {{-- Preview Header --}}
                        <div class="bg-gray-50 border-b border-gray-200 px-4 md:px-6 py-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                            <h3 class="text-sm font-bold text-gray-700 flex items-center">
                                <i class='bx bx-file-blank mr-2 text-gray-400 text-lg'></i> Preview Mode
                            </h3>
                            <button class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-xs font-bold flex justify-center items-center shadow-sm transition hover:shadow-md">
                                <i class='bx bx-printer mr-2 text-base'></i>
                                Print Grade Sheet
                            </button>
                        </div>

                        {{-- Empty State / Content --}}
                        <div class="flex flex-col items-center justify-center h-80 md:h-96 text-center p-8">
                            <div class="bg-indigo-50 p-6 rounded-full mb-4 animate-pulse ring-4 ring-indigo-50/50">
                                <i class='bx bx-spreadsheet text-5xl text-indigo-500'></i>
                            </div>
                            <h4 class="text-lg md:text-xl font-bold text-gray-800">Select Criteria to Generate</h4>
                            <p class="text-gray-500 text-sm max-w-xs mt-2 mx-auto leading-relaxed">
                                Please use the <span class="md:hidden font-bold text-indigo-600">Filter Options</span><span class="hidden md:inline font-bold text-indigo-600">filters on the left</span> to load the required Grade Sheet.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>