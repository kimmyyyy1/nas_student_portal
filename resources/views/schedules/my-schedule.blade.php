<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Class Schedule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(isset($warning))
                <div class="mb-4 p-4 bg-yellow-100 text-yellow-800 rounded border-l-4 border-yellow-500 shadow-sm">
                    {{ $warning }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if($schedules->isEmpty())
                        <div class="text-center py-12 text-gray-500 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-lg font-medium">You have no assigned classes yet.</p>
                            <p class="text-sm">Please contact the Registrar/Admin for assignments.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-indigo-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Day</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Subject</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Section</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Room</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($schedules as $sched)
                                        <tr class="hover:bg-indigo-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-700">{{ $sched->day }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-blue-600 font-medium">
                                                {{ date('h:i A', strtotime($sched->time_start)) }} - {{ date('h:i A', strtotime($sched->time_end)) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">{{ $sched->subject->subject_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 rounded bg-blue-100 text-blue-800 text-xs font-bold uppercase">
                                                    {{ $sched->section->section_name }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $sched->room ?? 'TBA' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>