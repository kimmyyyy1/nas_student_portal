<x-app-layout>
    {{-- 👇 1. DIRECT INJECTION: Force Poppins on this page --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Force Poppins on everything in this view */
        * { font-family: 'Poppins', sans-serif !important; }
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Medical and Clearance Records') }}
            </h2>
            
            {{-- 🟢 DESKTOP ADD BUTTON (Hidden on Mobile) --}}
            <a href="{{ route('medical-records.create') }}" class="hidden md:inline-flex bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-sm text-sm items-center gap-2 transition duration-150 ease-in-out">
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- 🟢 MOBILE ADD BUTTON (Visible only on Mobile) --}}
            {{-- Malaking button sa content area para madaling makita --}}
            <div class="md:hidden mb-6">
                <a href="{{ route('medical-records.create') }}" class="w-full flex justify-center items-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-md text-sm transition duration-150 ease-in-out">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Record
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    
                    <div class="overflow-x-auto"> {{-- Added overflow wrapper --}}
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Student Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Record Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date Cleared</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($medicalRecords as $record)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            {{ $record->student->last_name ?? 'N/A' }}, {{ $record->student->first_name ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $record->record_type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-bold text-sm">
                                            @if ($record->status == 'Cleared')
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ $record->status }}
                                                </span>
                                            @elseif ($record->status == 'Restricted')
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    {{ $record->status }}
                                                </span>
                                            @else
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    {{ $record->status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $record->date_cleared ? date('M d, Y', strtotime($record->date_cleared)) : 'Not Cleared' }}
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            
                                            <a href="{{ route('medical-records.edit', $record->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold mr-3">Edit</a>
                                            
                                            <form class="inline-block" method="POST" action="{{ route('medical-records.destroy', $record->id) }}" onsubmit="return confirm('Sigurado ka bang gusto mong burahin ang record na ito?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold">
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