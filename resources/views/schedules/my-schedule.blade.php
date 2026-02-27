<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title border-none">
            {{ __('Class Schedule') }} <span class="mx-3 text-slate-300">|</span> <span class="text-indigo-600">Weekly Distribution</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Error Message --}}
            @if(session('error'))
                <div class="mb-6 p-6 bg-rose-50 border-l-4 border-rose-500 rounded-2xl flex items-center gap-4 shadow-sm">
                    <i class='bx bxs-error-circle text-rose-500 text-3xl'></i>
                    <p class="text-rose-700 font-bold">{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white/90 backdrop-blur-xl shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-white/60 sm:rounded-[2.5rem] overflow-hidden">
                <div class="px-10 py-8 border-b border-gray-100/50 bg-indigo-50/20 flex justify-between items-center">
                    <h3 class="text-[13px] font-black text-slate-900 uppercase tracking-[0.2em] flex items-center gap-3">
                        <i class='bx bx-calendar text-indigo-500 text-xl'></i> My Academic Load
                    </h3>
                </div>

                <div class="p-0">
                    @if($mySchedules->isEmpty())
                        <div class="flex flex-col items-center justify-center py-24 opacity-40">
                            <div class="h-24 w-24 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                                <i class='bx bx-calendar-x text-6xl text-slate-300'></i>
                            </div>
                            <h4 class="text-lg font-black text-slate-400 uppercase tracking-[0.2em]">Assignment Pending</h4>
                            <p class="text-sm font-bold text-slate-400 mt-2">Please contact the Registrar for course assignments.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-slate-50/50">
                                        <th class="px-10 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Day</th>
                                        <th class="px-10 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Time Window</th>
                                        <th class="px-10 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Subject Intelligence</th>
                                        <th class="px-10 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Section</th>
                                        <th class="px-10 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Room</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100/60">
                                    @foreach($mySchedules as $sched)
                                        <tr class="hover:bg-indigo-50/30 transition-colors duration-200">
                                            <td class="px-10 py-6">
                                                <span class="px-3 py-1 bg-slate-900 text-white text-[10px] font-black rounded-lg uppercase tracking-widest shadow-lg shadow-slate-200">
                                                    {{ substr($sched->day, 0, 3) }}
                                                </span>
                                            </td>
                                            <td class="px-10 py-6">
                                                <div class="flex items-center gap-2">
                                                    <i class='bx bx-time text-indigo-400'></i>
                                                    <span class="text-sm font-black text-slate-700 font-mono tracking-tight">
                                                        {{ date('h:i A', strtotime($sched->time_start)) }} — {{ date('h:i A', strtotime($sched->time_end)) }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-10 py-6">
                                                <span class="text-sm font-black text-slate-800 tracking-tight">{{ $sched->subject->subject_name }}</span>
                                            </td>
                                            <td class="px-10 py-6 text-center">
                                                <span class="px-3 py-1.5 bg-indigo-50 text-indigo-600 text-[10px] font-black rounded-lg uppercase tracking-widest border border-indigo-100">
                                                    {{ $sched->section->grade_level }} — {{ $sched->section->section_name }}
                                                </span>
                                            </td>
                                            <td class="px-10 py-6 text-center">
                                                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest flex items-center justify-center gap-2">
                                                    <i class='bx bx-building text-indigo-400/50 text-base'></i>
                                                    {{ $sched->room ?? 'TBA' }}
                                                </span>
                                            </td>
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