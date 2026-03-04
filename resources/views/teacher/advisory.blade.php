<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title border-none">
            {{ __('My Advisory Class') }} <span class="mx-3 text-slate-300">|</span> <span class="text-indigo-600">Masterlist</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(!$section)
                <div class="bg-white/70 backdrop-blur-xl border border-rose-100 p-8 rounded-[2rem] shadow-xl flex items-center gap-6">
                    <div class="h-16 w-16 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500">
                        <i class='bx bx-info-circle text-3xl'></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-slate-800 uppercase tracking-widest">Notice</h4>
                        <p class="text-slate-500 font-bold">You do not have an advisory class assigned yet. Please contact the Registrar.</p>
                    </div>
                </div>
            @else

                {{-- SECTION HEADER CARD --}}
                <div class="bg-white/70 backdrop-blur-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 sm:rounded-[2.5rem] p-10 mb-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-10">
                        <div class="flex items-center gap-7">
                            <div class="h-20 w-20 rounded-[1.8rem] bg-indigo-600 flex items-center justify-center text-white shadow-2xl shadow-indigo-200">
                                <i class='bx bxs-school text-4xl'></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-black text-indigo-500 uppercase tracking-[0.25em] mb-2">Section Profile</p>
                                <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-none">{{ $section->grade_level }} — {{ $section->section_name }}</h1>
                                <div class="mt-4 flex flex-wrap gap-4 font-bold text-slate-500 text-[11px] uppercase tracking-widest">
                                    <div class="flex items-center gap-2"><i class='bx bx-user text-indigo-400'></i> Adviser: <span class="text-slate-900">{{ Auth::user()->name }}</span></div>
                                    <div class="flex items-center gap-2"><i class='bx bx-door-open text-indigo-400'></i> Room: <span class="text-slate-900">{{ $section->room ?? 'TBA' }}</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 bg-white px-8 py-5 rounded-2xl border border-slate-100 shadow-sm">
                            <div class="text-right">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Authenticated Total</p>
                                <p class="text-3xl font-black text-slate-900 leading-none">{{ $section->students->count() }} <span class="text-sm text-slate-400 ml-1">Students</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STUDENT MASTERLIST TABLE --}}
                <div class="bg-white/70 backdrop-blur-xl shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-white/60 sm:rounded-[2.5rem] overflow-hidden">
                    <div class="px-10 py-8 border-b border-gray-100/50 bg-white/40 flex justify-between items-center">
                        <h3 class="text-[13px] font-black text-slate-900 uppercase tracking-[0.2em] flex items-center gap-3">
                            <i class='bx bx-list-check text-indigo-500 text-xl'></i> Student Masterlist
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-10 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">#</th>
                                    <th class="px-10 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Student Name</th>
                                    <th class="px-10 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Sex</th>
                                    <th class="px-10 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">LRN</th>
                                    <th class="px-10 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100/60">
                                @forelse($section->students as $index => $student)
                                    <tr class="hover:bg-indigo-50/30 transition-colors duration-200">
                                        <td class="px-10 py-6 text-xs font-bold text-slate-400">{{ sprintf('%02d', $index + 1) }}</td>
                                        <td class="px-10 py-6">
                                            <div class="flex items-center gap-4">
                                                <div class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 font-bold overflow-hidden border border-slate-200 shadow-sm">
                                                    @if($student->id_picture)
                                                        <img src="{{ fileUrl($student->id_picture) }}" class="h-full w-full object-cover">
                                                    @else
                                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                    @endif
                                                </div>
                                                <span class="text-sm font-black text-slate-800 tracking-tight">{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-10 py-6 text-center">
                                            <span class="px-2.5 py-1 rounded-md bg-slate-100 text-[10px] font-black text-slate-500 uppercase tracking-widest border border-slate-200">
                                                {{ $student->sex ? substr($student->sex, 0, 1) : '-' }}
                                            </span>
                                        </td>
                                        <td class="px-10 py-6 text-center font-mono text-[11px] font-bold text-slate-500">{{ $student->lrn ?? 'N/A' }}</td>
                                        <td class="px-10 py-6 text-center">
                                            <span class="px-4 py-1.5 text-[9px] font-black rounded-lg uppercase tracking-widest {{ ($student->status ?? 'Enrolled') === 'Enrolled' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                                {{ $student->status ?? 'Enrolled' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-10 py-20 text-center">
                                            <div class="flex flex-col items-center opacity-30">
                                                <i class='bx bx-user-x text-6xl mb-4'></i>
                                                <p class="text-[12px] font-black uppercase tracking-[0.2em]">No students found in this roster</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>