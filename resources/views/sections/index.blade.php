<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                {{ __('Section Management') }}
                <span class="ml-3 px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                    <span class="w-2 h-2 bg-red-600 rounded-full mr-1"></span> LIVE
                </span>
            </h2>
            <a href="{{ route('sections.create') }}" wire:navigate class="hidden md:inline-flex bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm items-center shadow transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Section
            </a>
        </div>
    </x-slot>

    <div class="py-4 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- 🟢 MOBILE ADD BUTTON --}}
            <div class="block md:hidden mb-4">
                <a href="{{ route('sections.create') }}" wire:navigate class="flex items-center justify-center w-full px-4 py-3 bg-blue-600 text-white rounded-lg shadow-md font-bold text-sm hover:bg-blue-700 active:scale-95 transition-all">
                    <i class='bx bx-plus mr-2 text-xl'></i> Add New Section
                </a>
            </div>

            {{-- ALERTS --}}
            @if(session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded shadow relative text-sm">
                    <button onclick="this.parentElement.style.display='none'" class="absolute top-2 right-2 text-green-600 hover:text-green-800"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>{!! session('success') !!}</span>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-0 md:p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 whitespace-nowrap">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 md:px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Grade Level</th>
                                    <th scope="col" class="px-4 md:px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Section Name</th>
                                    <th scope="col" class="hidden md:table-cell px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Adviser</th>
                                    <th scope="col" class="relative px-4 md:px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($sections as $section)
                                    <tr class="hover:bg-gray-50 transition">
                                        {{-- 1. Grade Level --}}
                                        <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800 border border-orange-200">
                                                {{ $section->grade_level }}
                                            </span>
                                        </td>

                                        {{-- 2. Section Name --}}
                                        <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                            {{ $section->section_name }}
                                        </td>

                                        {{-- 3. ADVISER (Fixed Logic) --}}
                                        <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <i class='bx bx-user text-gray-400 mr-2'></i>
                                                
                                                {{-- Check kung may Relationship sa Staff --}}
                                                @if(isset($section->adviser) && $section->adviser)
                                                    <span class="font-medium text-gray-900">
                                                        {{ $section->adviser->first_name }} {{ $section->adviser->last_name }}
                                                    </span>
                                                
                                                {{-- Fallback: Check kung may adviser_name na text column --}}
                                                @elseif(!empty($section->adviser_name))
                                                    <span class="font-medium text-gray-900">{{ $section->adviser_name }}</span>
                                                
                                                {{-- Wala talagang assigned --}}
                                                @else
                                                    <span class="italic text-gray-400">No Adviser Assigned</span>
                                                @endif
                                            </div>
                                        </td>
                                        
                                        {{-- 4. Actions --}}
                                        <td class="px-4 md:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('sections.edit', $section->id) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900 font-bold mr-3 transition">Edit</a>
                                            <form class="inline-block" method="POST" action="{{ route('sections.destroy', $section->id) }}" onsubmit="return confirm('Are you sure you want to delete this section?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold transition">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 whitespace-nowrap text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class='bx bx-door-open text-4xl text-gray-300 mb-2'></i>
                                                <p class="text-lg font-medium">No sections found.</p>
                                                <p class="text-sm mt-1">Click "Add New Section" button above.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>