<x-app-layout>
    <x-slot name="header">
        
        {{-- ============================================================= --}}
        {{-- 📱 MOBILE HEADER: Badge Lang (Clean Look)                     --}}
        {{-- ============================================================= --}}
        <div class="flex md:hidden items-center w-full py-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700 tracking-wide uppercase shadow-sm border border-gray-200">
                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                System Reports
            </span>
        </div>

        {{-- ============================================================= --}}
        {{-- 💻 DESKTOP HEADER: Title + Badge (No Back Button)             --}}
        {{-- ============================================================= --}}
        <div class="hidden md:flex items-center justify-between w-full py-2">
            
            {{-- KALIWA: Title Only --}}
            <div class="flex items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Reports & Documents Generation') }}
                </h2>
            </div>

            {{-- KANAN: Badge --}}
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700 tracking-wide uppercase shadow-sm border border-gray-200">
                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                System Reports
            </span>

        </div>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- CARD 1: Grade Sheets --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500 hover:shadow-md transition">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Grade Sheets</h3>
                                <p class="text-sm text-gray-500">Generate summary of grades per section.</p>
                            </div>
                        </div>
                        <a href="{{ route('reports.grade_sheets') }}" class="flex items-center justify-center w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded shadow transition transform hover:scale-105">
                            Generate Grade Sheets
                        </a>
                    </div>
                </div>

                {{-- CARD 2: Report Cards --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500 hover:shadow-md transition">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884-.896 1.743-2.25 2.004-1.354.261-2.25-1.12-2.25-2.004M14 6c0 .884.896 1.743 2.25 2.004 1.354.261 2.25-1.12 2.25-2.004M6.75 22h.008v.008H6.75V22zm4.5 0h.008v.008H11.25V22zm4.5 0h.008v.008H15.75V22z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Report Cards (SF9)</h3>
                                <p class="text-sm text-gray-500">Generate individual learner progress report.</p>
                            </div>
                        </div>
                        <a href="{{ route('reports.report_cards') }}" class="flex items-center justify-center w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded shadow transition transform hover:scale-105">
                            Generate Report Cards
                        </a>
                    </div>
                </div>

                {{-- CARD 3: Academic Awardees --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-500 hover:shadow-md transition">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Academic Awardees</h3>
                                <p class="text-sm text-gray-500">List of honors and achievers.</p>
                            </div>
                        </div>
                        <a href="{{ route('reports.awardees') }}" class="flex items-center justify-center w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-4 rounded shadow transition transform hover:scale-105">
                            Generate Awardees List
                        </a>
                    </div>
                </div>

                {{-- CARD 4: Student Ranking --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-600 hover:shadow-md transition">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Student Ranking</h3>
                                <p class="text-sm text-gray-500">Overall ranking based on general average.</p>
                            </div>
                        </div>
                        
                        <a href="{{ route('reports.ranking') }}" class="flex items-center justify-center w-full bg-indigo-700 hover:bg-indigo-800 text-white font-bold py-3 px-4 rounded shadow transition transform hover:scale-105">
                            View Ranking
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>