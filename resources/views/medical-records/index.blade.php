<x-app-layout>
    {{-- 👇 1. DIRECT INJECTION: Force Poppins on this page --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Force Poppins on everything in this view */
        * { font-family: 'Poppins', sans-serif !important; }
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="page-title">
                {{ __('Medical and Clearance Records') }}
            </h2>
            
            {{-- 🟢 DESKTOP ADD BUTTON (Hidden on Mobile) --}}
            <a href="{{ route('medical-records.create') }}" class="hidden md:inline-flex premium-btn-primary gap-2">
                {{-- SVG Plus Icon --}}
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Record
            </a>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        {{-- Added px-4 para may spacing sa gilid pag mobile --}}
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- 🟢 MOBILE ADD BUTTON (Visible only on Mobile) --}}
            {{-- Malaking button sa content area para madaling makita --}}
            <div class="md:hidden mb-6">
                <a href="{{ route('medical-records.create') }}" class="w-full justify-center premium-btn-primary gap-2 mb-6">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Record
                </a>
            </div>

            <div class="premium-card !p-0 overflow-hidden">
                <div class="p-6 md:p-8 border-b border-white/40">
                    
                    <div class="premium-table-container !rounded-none !border-x-0 !border-b-0">
                        <table class="min-w-full divide-y divide-gray-100/50 bg-transparent">
                            <thead class="premium-table-header">
                                <tr>
                                    <th>Student Name</th>
                                    <th>Record Type</th>
                                    <th>Status</th>
                                    <th>Date Cleared</th>
                                    <th class="relative pl-6 pr-4 sm:pr-6 text-right">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100/50">
                                @forelse ($medicalRecords as $record)
                                    <tr class="premium-table-row group">
                                        <td class="premium-table-cell font-bold text-slate-800 uppercase tracking-wide">
                                            {{ $record->student->last_name ?? 'N/A' }}, {{ $record->student->first_name ?? '' }}
                                        </td>
                                        <td class="premium-table-cell text-sm text-gray-700">{{ $record->record_type }}</td>
                                        <td class="premium-table-cell font-bold text-sm">
                                            @if ($record->status == 'Cleared')
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold tracking-widest uppercase rounded-full bg-green-100 text-green-800">
                                                    {{ $record->status }}
                                                </span>
                                            @elseif ($record->status == 'Restricted')
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold tracking-widest uppercase rounded-full bg-red-100 text-red-800">
                                                    {{ $record->status }}
                                                </span>
                                            @else
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold tracking-widest uppercase rounded-full bg-yellow-100 text-yellow-800">
                                                    {{ $record->status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="premium-table-cell text-[13px] font-bold text-slate-600">
                                            {{ $record->date_cleared ? date('M d, Y', strtotime($record->date_cleared)) : 'Not Cleared' }}
                                        </td>
                                        
                                        <td class="premium-table-cell text-right text-[13px] font-bold">
                                            
                                            <a href="{{ route('medical-records.edit', $record->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold mr-3 transition">Edit</a>
                                            
                                            <form class="inline-block" method="POST" action="{{ route('medical-records.destroy', $record->id) }}" onsubmit="return confirm('Sigurado ka bang gusto mong burahin ang record na ito?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold transition">
                                                    Delete
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 whitespace-nowrap text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class='bx bx-plus-medical text-4xl text-gray-300 mb-2'></i>
                                                <p class="text-lg font-medium">No medical records found.</p>
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