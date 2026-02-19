<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
            <i class='bx bx-cog mr-2 text-2xl'></i> {{ __('System Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- SUCCESS MESSAGE --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded shadow-sm relative">
                    <div class="flex items-center">
                        <i class='bx bx-check-circle text-xl mr-2 text-green-600'></i>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf

                {{-- 1. SCHOOL YEAR SETTINGS --}}
                <div class="bg-white shadow-md sm:rounded-lg mb-8 overflow-hidden border border-gray-200">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center">
                        <i class='bx bx-calendar text-2xl text-blue-600 mr-2'></i>
                        <h3 class="text-lg font-bold text-gray-800">Academic School Year</h3>
                    </div>
                    <div class="p-6">
                        <div class="w-full md:w-1/2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Current School Year</label>
                            <input type="text" name="current_school_year" 
                                value="{{ $settings['current_school_year'] ?? '' }}" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 font-bold text-blue-700" 
                                placeholder="e.g. 2025-2026" required>
                            <p class="text-xs text-gray-500 mt-2">This is the global school year used across the system's dashboard, forms, and reports.</p>
                        </div>
                    </div>
                </div>

                {{-- 2. ENROLLMENT SETTINGS (Centered and Expanded) --}}
                <div class="bg-white shadow-md sm:rounded-lg overflow-hidden border border-gray-200 mb-8">
                    <div class="px-6 py-4 bg-green-50 border-b border-green-100 flex items-center">
                        <i class='bx bx-edit text-2xl text-green-600 mr-2'></i>
                        <h3 class="text-lg font-bold text-green-900">Enrollment & Renewal Period</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-6">Set the dates when existing students can log into their portal to renew their enrollment for the new school year.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Opening Date</label>
                                <input type="date" name="enrollment_start_date" 
                                    value="{{ $settings['enrollment_start_date'] ?? '' }}" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Closing Date</label>
                                <input type="date" name="enrollment_end_date" 
                                    value="{{ $settings['enrollment_end_date'] ?? '' }}" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SUBMIT BUTTON --}}
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition-transform transform hover:-translate-y-0.5 flex items-center">
                        <i class='bx bx-save text-xl mr-2'></i> Save Settings
                    </button>
                </div>
                
            </form>
        </div>
    </div>
</x-app-layout>