<x-app-layout>
    <x-slot name="header">
        
        {{-- ============================================================= --}}
        {{-- 📱 MOBILE HEADER: Compact Badge & Live Indicator              --}}
        {{-- ============================================================= --}}
        <div class="flex md:hidden items-center justify-between w-full py-1">
            
            {{-- Badge --}}
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700 uppercase shadow-sm border border-indigo-200">
                <i class='bx bxs-book-content mr-1.5 text-sm'></i> Subject
            </span>

            {{-- Live Indicator --}}
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mr-1"></span> LIVE
            </span>

        </div>

        {{-- ============================================================= --}}
        {{-- 💻 DESKTOP HEADER: Standard View (WALANG BUTTON)              --}}
        {{-- ============================================================= --}}
        <div class="hidden md:flex items-center justify-between w-full py-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                {{ __('Create New Subject') }}
                <span class="ml-3 px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                    <span class="w-2 h-2 bg-red-600 rounded-full mr-1"></span> LIVE
                </span>
            </h2>
        </div>

    </x-slot>

    {{-- 👇 FIX: 'py-2' mobile, 'md:py-12' desktop --}}
    <div class="py-2 md:py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- 🟢 MOBILE BACK BUTTON --}}
            <div class="md:hidden mb-3">
                <a href="{{ route('subjects.index') }}" 
                   wire:navigate
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-full shadow-md text-gray-700 font-bold text-sm hover:bg-gray-50 active:scale-95 transition-all">
                    <i class='bx bx-arrow-back mr-2 text-lg text-gray-600'></i>
                    Back
                </a>
            </div>

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

                    <form method="POST" action="{{ route('subjects.store') }}">
                        @csrf 

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            
                            {{-- Subject Code --}}
                            <div>
                                <label for="subject_code" class="block text-xs font-bold text-gray-500 uppercase mb-1">Subject Code <span class="text-red-500">*</span></label>
                                <input type="text" name="subject_code" id="subject_code" placeholder="e.g., MATH101" 
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" 
                                       value="{{ old('subject_code') }}" required>
                            </div>

                            {{-- Subject Name --}}
                            <div>
                                <label for="subject_name" class="block text-xs font-bold text-gray-500 uppercase mb-1">Subject Name <span class="text-red-500">*</span></label>
                                <input type="text" name="subject_name" id="subject_name" placeholder="e.g., Calculus 1" 
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" 
                                       value="{{ old('subject_name') }}" required>
                            </div>
                            
                            {{-- Description --}}
                            <div class="md:col-span-2">
                                <label for="description" class="block text-xs font-bold text-gray-500 uppercase mb-1">Description (Optional)</label>
                                <textarea name="description" id="description" rows="4" 
                                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" 
                                          placeholder="Enter subject description...">{{ old('description') }}</textarea>
                            </div>

                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('subjects.index') }}" wire:navigate class="bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 font-bold py-2 px-4 rounded text-sm shadow-sm transition">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded text-sm shadow-md transition transform hover:-translate-y-0.5">
                                Save Subject
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>