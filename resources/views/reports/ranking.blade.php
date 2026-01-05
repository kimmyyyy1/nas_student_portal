<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Student Ranking') }}
            </h2>
            <span class="text-xs font-bold text-indigo-600 bg-indigo-100 px-2 py-1 rounded-full uppercase tracking-wide">
                Academic Performance
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                
                <div class="md:col-span-1">
                    <div class="bg-white shadow-md rounded-lg p-6 sticky top-6 border border-gray-200">
                        <h3 class="font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
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

                            <button type="button" class="w-full bg-indigo-700 hover:bg-indigo-800 text-white font-bold py-2.5 px-4 rounded shadow-md transition text-sm flex justify-center items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                Calculate Ranking
                            </button>
                        </form>
                    </div>
                </div>

                <div class="md:col-span-3">
                    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 min-h-[500px]">
                        
                        <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                            <h3 class="text-sm font-bold text-gray-700">Top Students</h3>
                            <div class="flex space-x-3">
                                <button class="bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 hover:text-gray-800 px-3 py-1.5 rounded text-xs font-bold flex items-center shadow-sm transition">
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