<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="page-title">
                {{ __('Section Management') }}
                <span class="ml-4 px-2.5 py-1 rounded-md text-[10px] font-black tracking-widest bg-rose-100 text-rose-600 animate-pulse flex items-center shadow-sm border border-rose-200">
                    <span class="w-1.5 h-1.5 bg-rose-600 rounded-full mr-1.5"></span> LIVE
                </span>
            </h2>
            <a href="{{ route('sections.create') }}" wire:navigate class="hidden md:inline-flex premium-btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Section
            </a>
        </div>
    </x-slot>

    <div class="py-4 md:py-8">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- 🟢 MOBILE ADD BUTTON --}}
            <div class="block md:hidden mb-4">
                <a href="{{ route('sections.create') }}" wire:navigate class="w-full justify-center premium-btn-primary gap-2">
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

            <div class="premium-card !p-0 overflow-hidden">
                <div class="p-6 md:p-8 border-b border-white/40">
                    <div class="premium-table-container !rounded-none !border-x-0 !border-b-0">
                        <table class="min-w-full divide-y divide-gray-100/50 whitespace-nowrap bg-transparent">
                            <thead class="premium-table-header">
                                <tr>
                                    <th>Grade Level</th>
                                    <th>Section Name</th>
                                    <th class="hidden md:table-cell">Adviser</th>
                                    <th class="relative pl-6 pr-4 sm:pr-6 text-right"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            
                            <tbody class="divide-y divide-slate-100/50">
                                @forelse ($sections as $section)
                                    <tr class="premium-table-row group">
                                        {{-- 1. Grade Level --}}
                                        <td class="premium-table-cell">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold tracking-widest uppercase rounded-full bg-orange-100 text-orange-800 border border-orange-200">
                                                {{ $section->grade_level }}
                                            </span>
                                        </td>

                                        {{-- 2. Section Name --}}
                                        <td class="premium-table-cell text-sm font-bold text-slate-800 uppercase tracking-wide">
                                            {{ $section->section_name }}
                                        </td>

                                        {{-- 3. ADVISER (Fixed Logic) --}}
                                        <td class="hidden md:table-cell premium-table-cell text-[13px]">
                                            <div class="flex items-center text-slate-700">
                                                <i class='bx bx-user text-indigo-400 mr-2 text-lg'></i>
                                                
                                                {{-- Check kung may Relationship sa Staff --}}
                                                @if(isset($section->adviser) && $section->adviser)
                                                    <span class="font-bold text-slate-800 uppercase tracking-tight">
                                                        {{ $section->adviser->first_name }} {{ $section->adviser->last_name }}
                                                    </span>
                                                
                                                {{-- Fallback: Check kung may adviser_name na text column --}}
                                                @elseif(!empty($section->adviser_name))
                                                    <span class="font-bold text-slate-800 uppercase tracking-tight">{{ $section->adviser_name }}</span>
                                                
                                                {{-- Wala talagang assigned --}}
                                                @else
                                                    <span class="italic text-slate-400 font-medium">No Adviser Assigned</span>
                                                @endif
                                            </div>
                                        </td>
                                        
                                        {{-- 4. Actions --}}
                                        <td class="premium-table-cell text-right text-[13px] font-bold">
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