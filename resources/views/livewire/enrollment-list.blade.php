<div wire:poll.5s> {{-- ⚡ AUTO-REFRESH EVERY 5 SECONDS --}}
    
    {{-- Notification --}}
    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-lg shadow-sm animate-fade-in-down flex items-center">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {!! session('success') !!}
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-100">
        <div class="p-6 md:p-8 text-gray-900">
            
            {{-- Header Area --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <h3 class="font-black text-lg md:text-xl text-indigo-900 uppercase tracking-widest flex items-center">
                    <span class="bg-indigo-100 text-indigo-600 p-2 rounded-lg mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </span>
                    Official Enrollment List
                </h3>
                
                <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-xl border border-slate-100">
                    {{-- Loading Indicator --}}
                    <div wire:loading class="text-xs text-indigo-500 font-bold flex items-center">
                        <svg class="w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Syncing...
                    </div>
                    <span class="text-[10px] md:text-xs text-slate-400 font-bold uppercase tracking-wider" wire:loading.remove>
                        <i class='bx bx-refresh mr-1'></i> Auto-refresh active
                    </span>
                </div>
            </div>
            
            @if($enrollees->isEmpty())
                <div class="text-center py-16 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                    <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    <h3 class="text-sm font-black text-slate-600 uppercase tracking-widest">No Enrollment Records Found</h3>
                    <p class="mt-2 text-xs text-slate-400 font-medium">Wait for qualified applicants to submit their enrollment forms.</p>
                </div>
            @else
                <div class="overflow-x-auto rounded-xl border border-slate-200 shadow-sm">
                    <table class="min-w-full bg-white text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr class="text-slate-500 uppercase text-[10px] font-black tracking-wider">
                                <th class="py-4 px-6 whitespace-nowrap">App ID</th>
                                <th class="py-4 px-6">Name</th>
                                <th class="py-4 px-6 text-center whitespace-nowrap">LRN</th>
                                <th class="py-4 px-6 text-center">Sport</th>
                                <th class="py-4 px-6 text-center">Status</th>
                                <th class="py-4 px-6 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-600 text-sm font-medium divide-y divide-slate-100">
                            @foreach($enrollees as $applicant)
                                <tr class="hover:bg-slate-50 transition-colors group align-middle">
                                    
                                    {{-- App ID --}}
                                    <td class="py-4 px-6 whitespace-nowrap font-black text-indigo-600">
                                        {{ str_pad($applicant->id, 6, '0', STR_PAD_LEFT) }}
                                    </td>

                                    {{-- Name --}}
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <div class="font-black text-slate-800 uppercase tracking-tight">{{ $applicant->last_name }}, {{ $applicant->first_name }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $applicant->email }}</div>
                                    </td>

                                    {{-- LRN --}}
                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        <span class="bg-slate-100 text-slate-600 py-1.5 px-3 rounded-md text-xs font-mono font-bold border border-slate-200">
                                            {{ $applicant->lrn }}
                                        </span>
                                    </td>

                                    {{-- Sport --}}
                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-50 text-blue-700 border border-blue-100">
                                            {{ $applicant->sport }}
                                        </span>
                                    </td>

                                    {{-- ⚡ DYNAMIC STATUS BADGE ⚡ --}}
                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        @php
                                            $stat = strtoupper($applicant->status);
                                            $isAdmitted = str_contains($stat, 'ADMITTED') || str_contains($stat, 'ENROLLED') && !str_contains($stat, 'OFFICIALLY');
                                            $isPending = str_contains($stat, 'OFFICIALLY ENROLLED') || str_contains($stat, 'VERIFICATION');
                                            $isReturned = str_contains($stat, 'RETURN');
                                        @endphp

                                        @if($isAdmitted)
                                            <span class="bg-emerald-100 text-emerald-800 py-1.5 px-4 rounded-full text-[9px] font-black uppercase tracking-widest border border-emerald-200 shadow-sm">
                                                Admitted
                                            </span>
                                        @elseif($isPending)
                                            <span class="bg-yellow-100 text-yellow-800 py-1.5 px-4 rounded-full text-[9px] font-black uppercase tracking-widest border border-yellow-300 animate-pulse shadow-sm">
                                                For Verification
                                            </span>
                                        @elseif($isReturned)
                                            <span class="bg-red-100 text-red-800 py-1.5 px-4 rounded-full text-[9px] font-black uppercase tracking-widest border border-red-200 shadow-sm">
                                                Returned
                                            </span>
                                        @else
                                            <span class="bg-slate-100 text-slate-700 py-1.5 px-4 rounded-full text-[9px] font-black uppercase tracking-widest border border-slate-200 shadow-sm">
                                                {{ $applicant->status }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- ⚡ DYNAMIC ACTION BUTTON ⚡ --}}
                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        @if($isAdmitted)
                                            <a href="{{ route('official-enrollment.show', $applicant->id) }}" class="inline-flex items-center justify-center bg-slate-700 hover:bg-slate-800 text-white py-2 px-5 rounded-xl text-[10px] font-black shadow-md transition transform hover:-translate-y-0.5 whitespace-nowrap uppercase tracking-widest">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                View Record
                                            </a>
                                        @else
                                            <a href="{{ route('official-enrollment.show', $applicant->id) }}" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-5 rounded-xl text-[10px] font-black shadow-md transition transform hover:-translate-y-0.5 whitespace-nowrap uppercase tracking-widest">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Verify & Enroll
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $enrollees->links() }}
                </div>
            @endif
        </div>
    </div>
</div>