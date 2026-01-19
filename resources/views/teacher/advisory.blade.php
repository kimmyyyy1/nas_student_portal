<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Advisory Class') }} | Masterlist
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(!$section)
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 shadow-sm rounded">
                    <p class="font-bold">Notice:</p>
                    <p>You do not have an advisory class assigned yet.</p>
                </div>
            @else

                {{-- SECTION HEADER --}}
                <div class="bg-white shadow-sm sm:rounded-t-lg border-b border-gray-200 p-6 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                    <div>
                        <h1 class="text-3xl font-extrabold text-indigo-700">{{ $section->grade_level }} - {{ $section->section_name }}</h1>
                        <p class="text-sm text-gray-500 mt-1">Adviser: <span class="font-bold uppercase text-gray-700">{{ Auth::user()->name }}</span></p>
                        <p class="text-sm text-gray-500">Room: <span class="font-bold text-gray-700">{{ $section->room_number ?? 'TBA' }}</span></p>
                    </div>
                    <div class="text-right">
                        <span class="bg-blue-100 text-blue-800 text-sm font-bold px-4 py-2 rounded-full">
                            Total Students: {{ $section->students->count() }}
                        </span>
                    </div>
                </div>

                {{-- SIMPLE LIST TABLE --}}
                <div class="bg-white shadow-sm sm:rounded-b-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-bold text-gray-500 uppercase">#</th>
                                <th class="px-6 py-3 text-left font-bold text-gray-500 uppercase">Student Name</th>
                                <th class="px-6 py-3 text-center font-bold text-gray-500 uppercase">Sex</th>
                                <th class="px-6 py-3 text-center font-bold text-gray-500 uppercase">LRN</th>
                                <th class="px-6 py-3 text-center font-bold text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($section->students as $index => $student)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 font-bold text-gray-800">
                                        {{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}
                                    </td>
                                    <td class="px-6 py-4 text-center">{{ $student->sex ? substr($student->sex, 0, 1) : '-' }}</td>
                                    <td class="px-6 py-4 text-center font-mono text-gray-600">{{ $student->lrn ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $student->status ?? 'Enrolled' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-6 text-gray-500">No students found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>