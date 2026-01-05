<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Portal Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8 border-l-8 border-indigo-700">
                <div class="p-6 md:flex items-start justify-between">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="h-24 w-24 rounded-full bg-gray-200 border-4 border-indigo-500 shadow-sm overflow-hidden mr-6 flex-shrink-0 relative group">
                            @if(isset($student->photo) && $student->photo)
                                <img src="{{ asset('storage/' . $student->photo) }}" alt="Profile" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center bg-indigo-100 text-indigo-700 text-2xl font-bold">
                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                </div>
                            @endif
                        </div>

                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">{{ $student->last_name }}, {{ $student->first_name }}</h1>
                            <p class="text-sm text-gray-500 font-semibold">NAS ID: <span class="text-indigo-600">{{ $student->nas_student_id }}</span></p>
                            <p class="text-sm text-gray-500">LRN: {{ $student->lrn ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="text-left md:text-right space-y-1">
                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">
                            {{ $student->grade_level }} - {{ $student->section->section_name ?? 'Unassigned' }}
                        </span>
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">
                            {{ $student->team->team_name ?? $student->sport ?? 'No Sport' }}
                        </span>
                        <div class="text-xs text-gray-400 mt-2">Status: {{ $student->status }}</div>
                    </div>
                </div>
                
                <div x-data="{ showInfo: false }" class="border-t border-gray-100">
                    <button @click="showInfo = !showInfo" class="w-full text-center py-2 text-xs text-gray-500 hover:bg-gray-50 flex justify-center items-center bg-gray-50 transition">
                        <span x-show="!showInfo">View Full Profile Details</span>
                        <span x-show="showInfo">Hide Profile Details</span>
                        <svg class="w-4 h-4 ml-1 transform transition-transform" :class="{'rotate-180': showInfo}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="showInfo" x-transition class="p-6 bg-white grid grid-cols-1 md:grid-cols-3 gap-6 text-sm border-t">
                        <div>
                            <h4 class="font-bold text-indigo-700 mb-2 border-b pb-1">Personal Data</h4>
                            <p><span class="text-gray-500">Birthdate:</span> {{ date('M d, Y', strtotime($student->birthdate)) }}</p>
                            <p><span class="text-gray-500">Age:</span> {{ \Carbon\Carbon::parse($student->birthdate)->age }}</p>
                            <p><span class="text-gray-500">Sex:</span> {{ $student->sex }}</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-indigo-700 mb-2 border-b pb-1">Contact Info</h4>
                            <p><span class="text-gray-500">Email:</span> {{ $student->email_address }}</p>
                            <p><span class="text-gray-500">Address:</span> {{ $student->municipality_city }}, {{ $student->province }}</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-indigo-700 mb-2 border-b pb-1">Guardian</h4>
                            <p><span class="text-gray-500">Name:</span> {{ $student->guardian_name ?? 'N/A' }}</p>
                            <p><span class="text-gray-500">Contact:</span> {{ $student->guardian_contact ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            @if(isset($student->promotion_status) && $student->promotion_status)
                @php
                    $borderClass = 'border-red-500';
                    $textClass = 'text-red-600';
                    
                    if($student->promotion_status == 'Promoted') {
                        $borderClass = 'border-green-500';
                        $textClass = 'text-green-600';
                    } elseif($student->promotion_status == 'Conditional') {
                        $borderClass = 'border-yellow-500';
                        $textClass = 'text-yellow-600';
                    }
                @endphp

                <div class="mb-8 bg-white shadow-md rounded-lg border-l-8 {{ $borderClass }} p-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">End of School Year Status</h3>
                        <p class="text-sm text-gray-600">Based on academic performance.</p>
                    </div>
                    <span class="text-2xl font-extrabold uppercase tracking-wider {{ $textClass }}">
                        {{ $student->promotion_status }}
                    </span>
                </div>
            @endif

            <div x-data="{ showSchedule: false }" class="bg-white shadow-md rounded-lg overflow-hidden mb-8 border border-gray-200">
                <button @click="showSchedule = !showSchedule" class="w-full p-4 bg-blue-50 border-b border-gray-200 flex justify-between items-center hover:bg-blue-100 transition">
                    <div class="flex items-center text-blue-800">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 00-2-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <h3 class="text-lg font-bold">Class Schedule</h3>
                    </div>
                    <div class="text-xs font-bold text-blue-600 uppercase flex items-center">
                        <span x-show="!showSchedule">Show Schedule</span>
                        <span x-show="showSchedule">Hide Schedule</span>
                        <svg class="w-4 h-4 ml-1 transform transition-transform" :class="{'rotate-180': showSchedule}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </button>
                
                <div x-show="showSchedule" style="display: none;" class="p-4 bg-white transition-all duration-300">
                    @if($student->section && $student->section->schedules && $student->section->schedules->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase">
                                        <th class="p-3">Time</th>
                                        <th class="p-3">Mon</th>
                                        <th class="p-3">Tue</th>
                                        <th class="p-3">Wed</th>
                                        <th class="p-3">Thu</th>
                                        <th class="p-3">Fri</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Simple Table Version for Stability --}}
                                    @foreach($student->section->schedules as $sched)
                                    <tr class="border-b">
                                        <td class="p-3 font-mono text-xs text-blue-600 font-bold">
                                            {{ date('h:i A', strtotime($sched->time_start)) }} - {{ date('h:i A', strtotime($sched->time_end)) }}
                                        </td>
                                        <td colspan="5" class="p-3">
                                            <span class="font-bold text-gray-800">{{ $sched->subject->subject_name }}</span>
                                            <span class="text-gray-500 text-xs ml-2">({{ $sched->day }})</span>
                                            <div class="text-xs text-gray-400">Rm: {{ $sched->room ?? 'TBA' }}</div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-gray-500 italic p-4">No class schedule available yet.</p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-indigo-50">
                        <h3 class="text-lg font-bold text-indigo-800">Academic Records</h3>
                    </div>
                    <div class="p-0">
                        @if($student->grades && $student->grades->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr class="bg-white border-b text-gray-600">
                                            <th class="p-3 text-left pl-4">Subject</th>
                                            <th class="p-3 text-center">Period</th>
                                            <th class="p-3 text-right pr-4">Mark</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($student->grades as $grade)
                                            <tr class="border-b hover:bg-gray-50">
                                                <td class="p-3 pl-4 font-medium">
                                                    {{ $grade->schedule->subject->subject_name ?? 'Subject' }}
                                                </td>
                                                <td class="p-3 text-center text-gray-500">{{ $grade->grading_period }}</td>
                                                <td class="p-3 pr-4 text-right font-bold {{ $grade->mark < 75 ? 'text-red-600' : 'text-green-600' }}">
                                                    {{ $grade->mark }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-8 text-center text-gray-400 text-sm">No grades posted yet.</div>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                        <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-yellow-50">
                            <h3 class="text-lg font-bold text-yellow-800">Awards & Recognition</h3>
                        </div>
                        <div class="p-4">
                             @if($student->awards && $student->awards->count() > 0)
                                <ul class="space-y-2">
                                    @foreach($student->awards as $award)
                                        <li class="flex items-center p-2 bg-white border border-yellow-100 rounded text-sm">
                                            <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            <span class="font-bold text-gray-800">{{ $award->award_name }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else <p class="text-gray-400 text-center italic text-sm">No awards yet.</p> @endif
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                        <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-green-50">
                            <h3 class="text-lg font-bold text-green-800">Attendance Log</h3>
                        </div>
                        <div class="p-4">
                             @if($student->attendances && $student->attendances->count() > 0)
                                <div class="space-y-2">
                                    @foreach($student->attendances->sortByDesc('date')->take(5) as $att)
                                        <div class="flex justify-between items-center p-2 rounded border border-gray-100 text-sm bg-white">
                                            <div class="flex items-center">
                                                <span class="text-gray-700 font-medium mr-2">{{ date('M d', strtotime($att->date)) }}</span>
                                                <span class="text-xs text-gray-400">({{ $att->schedule->subject->subject_code ?? '' }})</span>
                                            </div>
                                            <span class="font-bold text-[10px] px-2 py-1 rounded uppercase {{ $att->status == 'Present' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                {{ $att->status }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else <p class="text-gray-400 text-center italic text-sm">No attendance records.</p> @endif
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg border border-gray-200">
                        <div class="p-4 flex items-center justify-between">
                            <div><h3 class="text-md font-bold text-gray-800">Exit Clearance</h3><p class="text-xs text-gray-500">Transfer/Graduation</p></div>
                            <button type="button" onclick="alert('Request sent.')" class="bg-gray-800 hover:bg-gray-700 text-white text-xs font-bold py-2 px-4 rounded shadow">Request</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>