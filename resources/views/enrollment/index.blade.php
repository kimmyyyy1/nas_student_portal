<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('NASCENT SAS Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-600 transition hover:shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-bold uppercase">Total Applicants</p>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ \Illuminate\Support\Facades\DB::table('applicants')->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow border-l-4 border-yellow-500 transition hover:shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-bold uppercase">Pending Review</p>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ \Illuminate\Support\Facades\DB::table('applicants')->where('status', 'Submitted (with Pending)')->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow border-l-4 border-indigo-500 transition hover:shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002 2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-bold uppercase">For Assessment</p>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ \Illuminate\Support\Facades\DB::table('applicants')->where('status', 'For Assessment')->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-500 transition hover:shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-bold uppercase">Qualified</p>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ \Illuminate\Support\Facades\DB::table('applicants')->whereIn('status', ['Qualified', 'Enrolled'])->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
                        <h3 class="text-lg font-bold text-gray-700">List of Applicants</h3>
                        
                        <form method="GET" action="{{ route('admission.index') }}" class="relative w-full md:w-auto">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or LRN..." class="border border-gray-300 rounded-md pl-10 pr-4 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 w-full md:w-64">
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">App ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Applicant Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Grade & Sport</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Categories</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date Applied</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($applications as $app)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                            {{ str_pad($app->id, 5, '0', STR_PAD_LEFT) }}
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900 uppercase">{{ $app->last_name }}, {{ $app->first_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $app->email_address }}</div>
                                            <div class="text-xs text-gray-400">LRN: {{ $app->lrn }}</div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 font-semibold">{{ $app->grade_level_applied }}</div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mt-1 uppercase">
                                                {{ $app->sport }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-wrap gap-1">
                                                @if($app->is_ip == 1)
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700 border border-indigo-200">IP</span>
                                                @endif
                                                @if($app->is_pwd == 1)
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-700 border border-purple-200">PWD</span>
                                                @endif
                                                @if($app->is_4ps == 1)
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">4Ps</span>
                                                @endif
                                                @if($app->is_others == 1 || !empty($app->others_specify))
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200">Other</span>
                                                @endif
                                                
                                                @if(!$app->is_ip && !$app->is_pwd && !$app->is_4ps && !$app->is_others)
                                                    <span class="text-xs text-gray-400">-</span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @php
                                                $statusClass = match($app->status) {
                                                    'Qualified', 'Enrolled' => 'bg-green-100 text-green-800 border border-green-200',
                                                    'Not Qualified' => 'bg-red-100 text-red-800 border border-red-200',
                                                    'Waitlisted' => 'bg-orange-100 text-orange-800 border border-orange-200',
                                                    'For Assessment' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                                    'Submitted (with Pending)' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                                    default => 'bg-gray-100 text-gray-800 border border-gray-200'
                                                };
                                            @endphp
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $statusClass }}">
                                                {{ $app->status }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $app->created_at->format('M d, Y') }}
                                            <span class="block text-xs text-gray-400">{{ $app->created_at->format('h:i A') }}</span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admission.show', $app->id) }}" class="inline-flex items-center text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded shadow text-xs font-bold transition transform hover:scale-105">
                                                Review &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-10 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002 2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                                <p class="text-gray-500">No applications found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($applications instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="mt-4">
                            {{ $applications->appends(request()->query())->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>