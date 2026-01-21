<x-app-layout>
    <x-slot name="header">
        
        {{-- ============================================================= --}}
        {{-- 📱 MOBILE HEADER: Badge Lang (Clean Look)                     --}}
        {{-- ============================================================= --}}
        <div class="flex md:hidden items-center w-full py-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700 tracking-wide uppercase shadow-sm border border-indigo-200">
                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Academic Performance
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
                   class="group flex items-center text-gray-500 hover:text-indigo-600 transition-colors p-1" 
                   title="Back to Reports">
                    <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Student Ranking') }}
                </h2>
            </div>

            {{-- KANAN: Badge --}}
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 tracking-wide uppercase shadow-sm border border-indigo-200">
                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Academic Performance
            </span>

        </div>

    </x-slot>

    <div class="py-6 md:py-12" x-data="{ showFilters: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- 🟢 NEW MOBILE BACK BUTTON (White Pill Style) --}}
            <div class="md:hidden mb-5">
                <a href="{{ route('reports.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-full shadow-md text-gray-700 font-bold text-sm hover:bg-gray-50 active:scale-95 transition-all">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Reports
                </a>
            </div>

            {{-- 🟢 MOBILE FILTER TOGGLE BUTTON --}}
            <div class="md:hidden mb-6">
                <button @click="showFilters = !showFilters" 
                        class="w-full flex items-center justify-between bg-white p-4 rounded-lg shadow-sm border border-gray-200 text-gray-700 font-bold transition hover:bg-gray-50 active:scale-[0.99]">
                    <span class="flex items-center text-indigo-600">
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
                            <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            Generate Ranking
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
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Grading Period</label>
                                <select class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="1">1st Quarter</option>
                                    <option value="2">2nd Quarter</option>
                                    <option value="3">3rd Quarter</option>
                                    <option value="4">4th Quarter</option>
                                    <option value="Final">Final Grade</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Grade Level</label>
                                <select class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="">All Levels</option>
                                    <option>Grade 7</option>
                                    <option>Grade 8</option>
                                    <option>Grade 9</option>
                                    <option>Grade 10</option>
                                    <option>Grade 11</option>
                                    <option>Grade 12</option>
                                </select>
                            </div>

                            <button type="button" class="w-full bg-indigo-700 hover:bg-indigo-800 text-white font-bold py-2.5 px-4 rounded shadow-md transition text-sm flex justify-center items-center group">
                                <svg class="w-4 h-4 mr-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                Calculate Ranking
                            </button>
                        </form>
                    </div>
                </div>

                {{-- 🟢 PREVIEW PANEL --}}
                <div class="md:col-span-3">
                    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 min-h-[500px]">
                        
                        <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                            <h3 class="text-sm font-bold text-gray-700 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                Top Students
                            </h3>
                            <div class="flex space-x-3 w-full md:w-auto">
                                <button class="flex-1 md:flex-none justify-center bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 hover:text-gray-800 px-3 py-1.5 rounded text-xs font-bold flex items-center shadow-sm transition">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Print List
                                </button>
                            </div>
                        </div>

                        <div class="flex flex-col items-center justify-center h-96 text-center p-8">
                            <div class="bg-indigo-50 p-6 rounded-full mb-4 animate-pulse">
                                <svg class="w-12 h-12 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold text-gray-800">Generate Class Ranking</h4>
                            <p class="text-gray-500 max-w-sm mt-2">
                                Please select the grading period and grade level to view the top performing students.
                            </p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>