<x-app-layout>
    <x-slot name="header">
        
        {{-- ============================================================= --}}
        {{-- 📱 MOBILE HEADER: Compact Badge & Live Indicator              --}}
        {{-- ============================================================= --}}
        <div class="flex md:hidden items-center justify-between w-full py-1">
            
            {{-- Badge --}}
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-orange-700 uppercase shadow-sm border border-orange-200">
                <i class='bx bxs-edit mr-1.5 text-sm'></i> Edit Section
            </span>

            {{-- Live Indicator --}}
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mr-1"></span> LIVE
            </span>

        </div>

        {{-- ============================================================= --}}
        {{-- 💻 DESKTOP HEADER: Standard View                              --}}
        {{-- ============================================================= --}}
        <div class="hidden md:flex items-center justify-between w-full py-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                {{ __('Edit Section') }}: <span class="text-orange-600 ml-2">{{ $section->section_name }}</span>
                <span class="ml-3 px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                    <span class="w-2 h-2 bg-red-600 rounded-full mr-1"></span> LIVE
                </span>
            </h2>
        </div>

    </x-slot>

    {{-- 👇 FIX: 'py-2' mobile, 'md:py-12' desktop --}}
    <div class="py-2 md:py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- ❌ REMOVED: Mobile Back Button --}}

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm text-sm">
                            <div class="flex items-center mb-2">
                                <i class='bx bx-error-circle mr-2 text-xl'></i>
                                <span class="font-bold">Please fix the following errors:</span>
                            </div>
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error) 
                                    <li>{{ $error }}</li> 
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('sections.update', $section->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            
                            {{-- Grade Level --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Grade Level <span class="text-red-500">*</span></label>
                                <select name="grade_level" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm cursor-pointer" required>
                                    <option value="" disabled>-- Select Grade --</option>
                                    @foreach(['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'] as $grade)
                                        <option value="{{ $grade }}" {{ old('grade_level', $section->grade_level) == $grade ? 'selected' : '' }}>{{ $grade }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Section Name --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Section Name <span class="text-red-500">*</span></label>
                                <input type="text" name="section_name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm" required value="{{ old('section_name', $section->section_name) }}">
                            </div>

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            
                            {{-- Adviser (Dropdown from Staff) --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Class Adviser <span class="text-red-500">*</span></label>
                                <select name="adviser_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm cursor-pointer" required>
                                    <option value="" disabled>-- Select Teacher --</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ (old('adviser_id', $section->adviser_id) == $teacher->id) ? 'selected' : '' }}>
                                            {{ $teacher->last_name }}, {{ $teacher->first_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Room Number --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Room Number (Optional)</label>
                                <input type="text" name="room_number" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm" value="{{ old('room_number', $section->room_number) }}">
                            </div>

                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('sections.index') }}" wire:navigate class="bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 font-bold py-2 px-4 rounded text-sm shadow-sm transition">
                                Cancel
                            </a>
                            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-6 rounded text-sm shadow-md transition transform hover:-translate-y-0.5">
                                Update Section
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>