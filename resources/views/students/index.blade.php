<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            {{-- 👇 LIVE BADGE ADDED HERE --}}
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                {{ __('Student Directory') }}
                <span class="ml-3 px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-600 animate-pulse flex items-center shadow-sm border border-red-200">
                    <span class="w-2 h-2 bg-red-600 rounded-full mr-1"></span> LIVE
                </span>
            </h2>
            
            <div class="flex gap-3">
                <a href="{{ route('students.bulk-upload') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm flex items-center shadow transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Bulk Upload Photos
                </a>

                <a href="{{ route('students.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm flex items-center shadow transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add New Student
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 p-6 rounded shadow-lg relative">
                    <button onclick="this.parentElement.style.display='none'" class="absolute top-4 right-4 text-green-600 hover:text-green-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="w-full">
                            {!! session('success') !!}
                        </div>
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 p-6 rounded shadow-lg relative">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div class="w-full">
                            <p class="font-bold">{{ session('warning') }}</p>
                            @if($errors->any())
                                <ul class="mt-2 list-disc list-inside text-sm text-yellow-700">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="mb-4 flex justify-end">
                <form method="GET" action="{{ route('students.index') }}" class="flex gap-2">
                    <input type="text" name="search" placeholder="Search by name or ID..." value="{{ request('search') }}" class="border border-gray-300 rounded-md text-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500 w-64">
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-700 shadow transition">Search</button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Student ID</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Grade & Section</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Sport</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        
                        {{-- 👇 ADDED ID="student-list" HERE --}}
                        <tbody id="student-list" class="bg-white divide-y divide-gray-200">
                            @forelse($students as $student)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 mr-3">
                                                @if($student->id_picture)
                                                    <img class="h-10 w-10 rounded-full object-cover border border-gray-300" src="{{ $student->id_picture }}" alt="">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-xs">
                                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-sm font-mono text-blue-600 font-bold">
                                                {{ $student->nas_student_id }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $student->last_name }}, {{ $student->first_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $student->email_address }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $student->grade_level }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $student->section ? $student->section->section_name : 'Unassigned' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $student->team ? $student->team->team_name : 'No Team' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $student->status == 'Enrolled' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $student->status }}
                                        </span>
                                    </td>
                                    
                                    {{-- 👇 UPDATED ACTION COLUMN --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('students.show', $student->id) }}" class="text-blue-600 hover:text-blue-900 mr-3 font-bold">View</a>
                                        <a href="{{ route('students.edit', $student->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Edit</a>
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
                
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- 👇 LIVE UPDATE SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Refresh table every 5 seconds
            setInterval(function() {
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
                    
                    document.getElementById('student-list').innerHTML = newBody;
                })
                .catch(error => console.error('Error updating table:', error));
        }
    </script>
</x-app-layout>