<x-app-layout>
    <x-slot name="header">
        
        {{-- ============================================================= --}}
        {{-- 📱 MOBILE HEADER: Badge Lang (Clean Look)                     --}}
        {{-- ============================================================= --}}
        <div class="flex md:hidden items-center w-full py-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 tracking-wide uppercase shadow-sm border border-blue-200">
                {{-- Gumamit ako ng SVG icon para consistent sa code mo --}}
                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                System Report
            </span>
        </div>

        {{-- ============================================================= --}}
        {{-- 💻 DESKTOP HEADER: Back + Title + Badge                       --}}
        {{-- ============================================================= --}}
        <div class="hidden md:flex items-center justify-between w-full py-2">
            
            {{-- KALIWA: Back Button + Title --}}
            <div class="flex items-center gap-4">
                {{-- Back Arrow --}}
                <a href="{{ route('reports.index') }}" 
                   class="group flex items-center text-gray-500 hover:text-blue-600 transition-colors p-1" 
                   title="Back to Reports">
                    <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Report Cards (SF9)') }}
                </h2>
            </div>

            {{-- KANAN: Badge --}}
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 tracking-wide uppercase shadow-sm border border-blue-200">
                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                System Report
            </span>

        </div>

    </x-slot>

    <div class="py-6 md:py-12" x-data="{ showFilters: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- 🟢 NEW MOBILE BACK BUTTON (White Pill Style) --}}
            <div class="md:hidden mb-5">
                <a href="{{ route('reports.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-full shadow-md text-gray-700 font-bold text-sm hover:bg-gray-50 active:scale-95 transition-all">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Reports
                </a>
            </div>

            {{-- 🟢 MOBILE FILTER TOGGLE BUTTON --}}
            <div class="md:hidden mb-6">
                <button @click="showFilters = !showFilters" 
                        class="w-full flex items-center justify-between bg-white p-4 rounded-lg shadow-sm border border-gray-200 text-gray-700 font-bold transition hover:bg-gray-50 active:scale-[0.99]">
                    <span class="flex items-center text-blue-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        Filter Options
                    </span>
                    <svg class="w-6 h-6 text-gray-400 transition-transform duration-200" :class="{'rotate-180': showFilters}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                
                {{-- 🟢 FILTER PANEL (Responsive) --}}
                <div class="md:col-span-1 md:!block" 
                     x-show="showFilters" 
                     x-cloak 
                     x-transition.opacity.duration.300ms
                     :class="{'block': showFilters, 'hidden': !showFilters}">
                     
                    <div class="bg-white shadow-md rounded-lg p-6 sticky top-6 border border-gray-200">
                        <h3 class="font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider flex items-center border-b pb-2">
                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            Filter Selection
                        </h3>
                        
                        <form action="#" method="GET"> 
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">School Year</label>
                                <select class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option>2024-2025</option>
                                    <option>2023-2024</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Grade Level</label>
                                <select class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option value="">-- Select Grade --</option>
                                    @foreach(['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'] as $gl)
                                        <option value="{{ $gl }}">{{ $gl }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Section</label>
                                <select class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option value="">-- Select Section --</option>
                                </select>
                            </div>

                            <div class="mb-6">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Period</label>
                                <select class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option value="1">1st Quarter</option>
                                    <option value="2">2nd Quarter</option>
                                    <option value="3">3rd Quarter</option>
                                    <option value="4">4th Quarter</option>
                                    <option value="Final">Final Grade</option>
                                </select>
                            </div>

                            <button type="button" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-2.5 px-4 rounded shadow-md transition text-sm flex justify-center items-center group">
                                <svg class="w-4 h-4 mr-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Load Students
                            </button>
                        </form>
                    </div>
                </div>

                <div class="md:col-span-3">
                    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 min-h-[500px]">
                        
                        <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                            <h3 class="text-sm font-bold text-gray-700 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                Class List
                            </h3>
                            <div class="flex space-x-3 w-full md:w-auto">
                                <button class="flex-1 md:flex-none justify-center bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 hover:text-gray-800 px-3 py-1.5 rounded text-xs font-bold flex items-center shadow-sm transition">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Batch Print
                                </button>
                                <button class="flex-1 md:flex-none justify-center bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-xs font-bold flex items-center shadow-sm transition">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Export Excel
                                </button>
                            </div>
                        </div>

                        <div class="flex flex-col items-center justify-center h-96 text-center p-8">
                            <div class="bg-blue-50 p-6 rounded-full mb-4 animate-pulse">
                                <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold text-gray-800">No Class Selected</h4>
                            <p class="text-gray-500 max-w-sm mt-2">
                                Please select a <strong>Grade Level</strong> and <strong>Section</strong> from the left panel to load the student list and generate report cards.
                            </p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>