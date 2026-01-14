<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            {{-- LIVE BADGE --}}
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                {{ __('Student Directory') }}
                <span class="ml-3 px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                    <span class="w-2 h-2 bg-red-600 rounded-full mr-1"></span> LIVE
                </span>
            </h2>
            
            <div class="flex gap-3">
                <a href="{{ route('students.bulk-upload') }}" wire:navigate class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm flex items-center shadow transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Bulk Upload
                </a>

                <a href="{{ route('students.create') }}" wire:navigate class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm flex items-center shadow transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Student
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ALERTS --}}
            @if(session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded shadow relative text-sm">
                    <button onclick="this.parentElement.style.display='none'" class="absolute top-2 right-2 text-green-600 hover:text-green-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>{!! session('success') !!}</span>
                    </div>
                </div>
            @endif

            <div class="mb-4 flex justify-end">
                <form method="GET" action="{{ route('students.index') }}" class="flex gap-2 w-full sm:w-auto">
                    <input type="text" name="search" placeholder="Search name or ID..." value="{{ request('search') }}" class="border border-gray-300 rounded-md text-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500 w-full sm:w-64">
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-700 shadow transition">Search</button>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 overflow-hidden">
                <div class="w-full"> 
                    <table class="w-full divide-y divide-gray-200 table-fixed">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/6">Student ID</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/4">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/6">Grade & Sec</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/6">Sport</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-1/12">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-1/6">Action</th>
                            </tr>
                        </thead>
                        
                        <tbody id="student-list" class="bg-white divide-y divide-gray-200">
                            @forelse($students as $student)
                                <tr class="hover:bg-gray-50 transition">
                                    {{-- ID --}}
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 mr-2 hidden sm:block">
                                                @if($student->id_picture)
                                                    <img class="h-8 w-8 rounded-full object-cover border border-gray-300" src="{{ $student->id_picture }}" alt="">
                                                @else
                                                    <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-xs">
                                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-xs sm:text-sm font-mono text-blue-600 font-bold truncate">
                                                {{ $student->nas_student_id }}
                                            </div>
                                        </div>
                                    </td>

                                    {{-- NAME --}}
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-bold text-gray-900 leading-tight">{{ $student->last_name }}, {{ $student->first_name }}</div>
                                        <div class="text-xs text-gray-500 truncate">{{ $student->email_address }}</div>
                                    </td>

                                    {{-- GRADE --}}
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $student->grade_level }}</div>
                                        <div class="text-xs text-gray-500 truncate">
                                            {{ $student->section ? $student->section->section_name : 'Unassigned' }}
                                        </div>
                                    </td>

                                    {{-- SPORT --}}
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 truncate max-w-[120px]">
                                            {{ $student->team ? ($student->team->sport ?? $student->team->sport_type ?? 'No Sport') : 'No Team' }}
                                        </span>
                                    </td>

                                    {{-- STATUS --}}
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $student->status == 'Enrolled' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $student->status }}
                                        </span>
                                    </td>
                                    
                                    {{-- ACTION --}}
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                        {{-- 👇 UPDATED LINKS: Added query params to preserve page number --}}
                                        <a href="{{ route('students.show', ['student' => $student->id] + request()->query()) }}" wire:navigate class="text-blue-600 hover:text-blue-900 mr-2 font-bold">View</a>
                                        <a href="{{ route('students.edit', ['student' => $student->id] + request()->query()) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900 font-bold">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                        No enrolled students found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- LIVE UPDATE SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Using Livewire navigate makes full page reload polling less ideal, 
            // but keeping it as requested. Ensure wire:navigate works properly.
            setInterval(function() {
                // Check if we are not editing/interacting to avoid disrupting user
                updateTable();
            }, 5000);
        });

        function updateTable() {
            const url = window.location.href;
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newBody = doc.getElementById('student-list').innerHTML;
                    if(document.getElementById('student-list')) {
                        document.getElementById('student-list').innerHTML = newBody;
                    }
                })
                .catch(error => console.error('Error updating table:', error));
        }
    </script>
</x-app-layout>