<div wire:poll.5s> {{-- ⚡ AUTO-REFRESH EVERY 5 SECONDS --}}
    
    {{-- Notification --}}
    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-lg shadow-sm animate-fade-in-down flex items-center">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {!! session('success') !!}
        </div>
    @endif

    <div class="premium-card !p-0 overflow-hidden">
        <div class="p-6 md:p-8 text-gray-900 border-b border-white/40">
            
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
            {{-- Search and Filters --}}
            <div class="mb-6 grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <div class="lg:col-span-2">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class='bx bx-search text-slate-400'></i>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text" class="form-input block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search by Name or LRN...">
                    </div>
                </div>
                <div>
                    <select wire:model.live="filterStatus" class="form-select block w-full py-2 pl-3 pr-8 border border-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-600">
                        <option value="">All Statuses</option>
                        <option value="For Enrollment Verification">For Enrollment Verification</option>
                        <option value="Qualified (Returned)">Qualified (Returned)</option>
                        <option value="Renewal (Returned)">Renewal (Returned)</option>
                        <option value="Officially Enrolled">Officially Enrolled</option>
                        <option value="Pending Renewal">Pending Renewal</option>
                    </select>
                </div>
                <div>
                    <select wire:model.live="filterSport" class="form-select block w-full py-2 pl-3 pr-8 border border-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-600">
                        <option value="">All Sports</option>
                        @foreach($sports as $sportOption)
                            <option value="{{ $sportOption }}">{{ $sportOption }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select wire:model.live="filterRegion" class="form-select block w-full py-2 pl-3 pr-8 border border-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-600">
                        <option value="">All Regions</option>
                        @foreach($regions as $regionOption)
                            <option value="{{ $regionOption }}">{{ Str::limit($regionOption, 20) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end mb-4">
                <button wire:click="exportCSV" wire:loading.attr="disabled" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-emerald-500 active:bg-emerald-700 disabled:opacity-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                    <span wire:loading.remove wire:target="exportCSV">
                        <i class='bx bx-export mr-2 text-sm'></i> Export to CSV
                    </span>
                    <span wire:loading wire:target="exportCSV">
                        <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Exporting...
                    </span>
                </button>
            </div>
            
            @if($enrollees->isEmpty())
                <div class="text-center py-16 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                    <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    <h3 class="text-sm font-black text-slate-600 uppercase tracking-widest">No Enrollment Records Found</h3>
                    <p class="mt-2 text-xs text-slate-400 font-medium">Wait for qualified applicants to submit their enrollment forms.</p>
                </div>
            @else
                <div class="premium-table-container !rounded-none !border-x-0 !border-b-0">
                    <table class="min-w-full divide-y divide-gray-100/50 whitespace-nowrap bg-transparent">
                        <thead class="premium-table-header">
                            <tr>
                                <th>App ID</th>
                                <th>Name</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">LRN</th>
                                <th class="text-center">Sport</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/50">
                            @foreach($enrollees as $applicant)
                                @php
                                    $remarks = is_string($applicant->document_remarks) ? json_decode($applicant->document_remarks, true) : ($applicant->document_remarks ?? []);
                                    $isRenewal = ($applicant->status === 'Pending Renewal') || ($remarks['is_renewal'] ?? false);
                                @endphp
                                <tr class="premium-table-row align-middle group">
                                    
                                    {{-- App ID --}}
                                    <td class="premium-table-cell font-black text-indigo-600">
                                        {{ str_pad($applicant->id, 6, '0', STR_PAD_LEFT) }}
                                    </td>

                                    {{-- Name --}}
                                    <td class="premium-table-cell">
                                        <div class="font-black text-slate-800 uppercase tracking-tight">{{ $applicant->last_name }}, {{ $applicant->first_name }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $applicant->email }}</div>
                                    </td>

                                    {{-- Type --}}
                                    <td class="premium-table-cell text-center">
                                        @if($isRenewal)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-[9px] font-black uppercase tracking-widest bg-orange-100 text-orange-700 border border-orange-200">
                                                Renewal
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-[9px] font-black uppercase tracking-widest bg-indigo-100 text-indigo-700 border border-indigo-200">
                                                New
                                            </span>
                                        @endif
                                    </td>

                                    {{-- LRN --}}
                                    <td class="premium-table-cell text-center">
                                        <span class="bg-slate-100/50 text-slate-600 py-1.5 px-3 rounded-md text-xs font-mono font-bold border border-slate-200/50">
                                            {{ $applicant->lrn }}
                                        </span>
                                    </td>

                                    {{-- Sport --}}
                                    <td class="premium-table-cell text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-50 text-blue-700 border border-blue-100">
                                            {{ $applicant->sport }}
                                        </span>
                                    </td>

                                    {{-- ⚡ DYNAMIC STATUS BADGE ⚡ --}}
                                    <td class="premium-table-cell text-center">
                                        @php
                                            $stat = strtoupper($applicant->status);
                                            $isAdmitted = str_contains($stat, 'OFFICIALLY ENROLLED') || str_contains($stat, 'ADMITTED') || (str_contains($stat, 'ENROLLED') && !str_contains($stat, 'FOR') && !str_contains($stat, 'RENEWAL'));
                                            $isPending = str_contains($stat, 'FOR ENROLLMENT VERIFICATION') || str_contains($stat, 'VERIFICATION');
                                            $isRenewalPending = str_contains($stat, 'PENDING RENEWAL');
                                            $isReturned = str_contains($stat, 'RETURN');
                                        @endphp

                                        @if($isAdmitted)
                                            <span class="bg-emerald-100 text-emerald-800 py-1.5 px-4 rounded-full text-[9px] font-black uppercase tracking-widest border border-emerald-200 shadow-sm">
                                                Admitted
                                            </span>
                                        @elseif($isRenewalPending)
                                            <span class="bg-orange-100 text-orange-800 py-1.5 px-4 rounded-full text-[9px] font-black uppercase tracking-widest border border-orange-300 animate-pulse shadow-sm">
                                                Renewal Check
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
                                    <td class="premium-table-cell text-center">
                                        @if($isAdmitted)
                                            <a href="{{ route('official-enrollment.show', $applicant->id) }}" class="premium-btn-secondary !py-1.5 !px-4 !text-[10px] uppercase tracking-widest">
                                                <svg class="w-3.5 h-3.5 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                View Record
                                            </a>
                                        @else
                                            <a href="{{ route('official-enrollment.show', $applicant->id) }}" class="premium-btn-primary !py-1.5 !px-4 !text-[10px] uppercase tracking-widest">
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