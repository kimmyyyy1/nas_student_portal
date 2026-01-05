<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Medical and Clearance Records') }}
            </h2>
            <a href="{{ route('medical-records.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md">Add Record</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Record Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Cleared</th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($medicalRecords as $record)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $record->student->last_name ?? 'N/A' }}, {{ $record->student->first_name ?? '' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $record->record_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold">
                                        @if ($record->status == 'Cleared')
                                            <span class="text-green-600">{{ $record->status }}</span>
                                        @elseif ($record->status == 'Restricted')
                                            <span class="text-red-600">{{ $record->status }}</span>
                                        @else
                                            <span class="text-yellow-600">{{ $record->status }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $record->date_cleared ? date('M d, Y', strtotime($record->date_cleared)) : 'N/A' }}</td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        
                                        <a href="{{ route('medical-records.edit', $record->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        
                                        <form class="inline-block ml-4" method="POST" action="{{ route('medical-records.destroy', $record->id) }}" onsubmit="return confirm('Sigurado ka bang gusto mong burahin ang record na ito?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                Delete
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                        No medical records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>