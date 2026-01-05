<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admission Management') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                
                <a href="{{ route('admission.index') }}" 
                   class="bg-white overflow-hidden shadow-sm rounded-lg p-4 border-t-4 border-blue-600 flex flex-col justify-between hover:shadow-md transition cursor-pointer transform hover:-translate-y-1 {{ !request('status') ? 'ring-2 ring-blue-500 bg-blue-50' : '' }}">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Submitted</p>
                            <p class="text-2xl font-extrabold text-gray-800">{{ $totalSubmitted ?? 0 }}</p>
                        </div>
                        <i class='bx bx-folder-open text-2xl text-blue-200'></i>
                    </div>
                    <p class="text-xs text-blue-600 mt-2 font-medium">All Applications</p>
                </a>

                <a href="{{ route('admission.index', ['status' => 'Pending']) }}" 
                   class="bg-white overflow-hidden shadow-sm rounded-lg p-4 border-t-4 border-yellow-500 flex flex-col justify-between hover:shadow-md transition cursor-pointer transform hover:-translate-y-1 {{ request('status') == 'Pending' ? 'ring-2 ring-yellow-500 bg-yellow-50' : '' }}">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Assessment</p>
                            <p class="text-2xl font-extrabold text-gray-800">{{ $countPending ?? 0 }}</p>
                        </div>
                        <i class='bx bx-time text-2xl text-yellow-200'></i>
                    </div>
                    <p class="text-xs text-yellow-600 mt-2 font-medium">Pending Review</p>
                </a>

                <a href="{{ route('admission.index', ['status' => 'Qualified']) }}" 
                   class="bg-white overflow-hidden shadow-sm rounded-lg p-4 border-t-4 border-green-500 flex flex-col justify-between hover:shadow-md transition cursor-pointer transform hover:-translate-y-1 {{ request('status') == 'Qualified' ? 'ring-2 ring-green-500 bg-green-50' : '' }}">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Qualified</p>
                            <p class="text-2xl font-extrabold text-gray-800">{{ $countQualified ?? 0 }}</p>
                        </div>
                        <i class='bx bx-check-circle text-2xl text-green-200'></i>
                    </div>
                    <p class="text-xs text-green-600 mt-2 font-medium">Passed</p>
                </a>

                <a href="{{ route('admission.index', ['status' => 'Waitlisted']) }}" 
                   class="bg-white overflow-hidden shadow-sm rounded-lg p-4 border-t-4 border-indigo-500 flex flex-col justify-between hover:shadow-md transition cursor-pointer transform hover:-translate-y-1 {{ request('status') == 'Waitlisted' ? 'ring-2 ring-indigo-500 bg-indigo-50' : '' }}">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Waitlisted</p>
                            <p class="text-2xl font-extrabold text-gray-800">{{ $countWaitlisted ?? 0 }}</p>
                        </div>
                        <i class='bx bx-list-ul text-2xl text-indigo-200'></i>
                    </div>
                    <p class="text-xs text-indigo-600 mt-2 font-medium">On Hold</p>
                </a>

                <a href="{{ route('admission.index', ['status' => 'Not Qualified']) }}" 
                   class="bg-white overflow-hidden shadow-sm rounded-lg p-4 border-t-4 border-red-500 flex flex-col justify-between hover:shadow-md transition cursor-pointer transform hover:-translate-y-1 {{ request('status') == 'Not Qualified' ? 'ring-2 ring-red-500 bg-red-50' : '' }}">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Not Qualified</p>
                            <p class="text-2xl font-extrabold text-gray-800">{{ $countRejected ?? 0 }}</p>
                        </div>
                        <i class='bx bx-x-circle text-2xl text-red-200'></i>
                    </div>
                    <p class="text-xs text-red-600 mt-2 font-medium">Rejected</p>
                </a>

            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <div class="flex items-center">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                                <i class='bx bx-list-ul mr-2 text-blue-600'></i> 
                                @if(request('status'))
                                    List of {{ request('status') }} Applicants
                                @else
                                    List of All Applicants
                                @endif
                            </h3>
                            @if(request('status') || request('search'))
                                <a href="{{ route('admission.index') }}" class="ml-4 text-xs text-red-500 hover:underline flex items-center">
                                    <i class='bx bx-x-circle mr-1'></i> Clear Filters
                                </a>
                            @endif
                        </div>
                        
                        <form method="GET" action="{{ route('admission.index') }}" class="flex w-full md:w-auto">
                            @if(request('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif
                            
                            <div class="relative w-full md:w-64">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class='bx bx-search text-gray-400'></i>
                                </div>
                                <input type="text" name="search" placeholder="Search Name, LRN or Email..." value="{{ request('search') }}" 
                                    class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-l-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-r-lg text-sm px-4 py-2">
                                Search
                            </button>
                        </form>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg border border-green-200 flex items-center" role="alert">
                            <i class='bx bx-check-circle mr-2 text-lg'></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($applications->count() > 0)
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">App ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Applicant Details</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Grade Level</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date Submitted</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date Checked</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($applications as $app)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">
                                        #{{ str_pad($app->id, 4, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-xs uppercase">
                                                {{ substr($app->first_name, 0, 1) }}{{ substr($app->last_name, 0, 1) }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-bold text-gray-900">{{ $app->last_name }}, {{ $app->first_name }}</div>
                                                <div class="text-xs text-gray-500">{{ $app->email_address }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-300">
                                            {{ $app->grade_level_applied }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $app->created_at->format('M d, Y') }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if(!in_array($app->status, ['Pending', 'For Assessment']))
                                            <div class="flex flex-col">
                                                <span class="font-bold text-gray-700">{{ $app->updated_at->format('M d, Y') }}</span>
                                                <span class="text-xs text-gray-400">{{ $app->updated_at->format('h:i A') }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic text-xs">-- Pending --</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(in_array($app->status, ['Pending', 'pending', 'For Assessment']))
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                For Assessment
                                            </span>
                                        @elseif(in_array($app->status, ['Qualified', 'qualified']))
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                Qualified
                                            </span>
                                        @elseif(in_array($app->status, ['Waitlisted', 'waitlisted']))
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800 border border-indigo-200">
                                                Waitlisted
                                            </span>
                                        @elseif(in_array($app->status, ['Not Qualified', 'not qualified']))
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                                Not Qualified
                                            </span>
                                        @elseif(in_array($app->status, ['Enrolled', 'enrolled']))
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                                Enrolled
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $app->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="{{ route('admission.show', $app->id) }}" class="text-indigo-600 hover:text-white border border-indigo-600 hover:bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center inline-flex items-center transition">
                                            <i class='bx bx-show mr-1'></i> Review
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $applications->appends(request()->query())->links() }}
                    </div>

                    @else
                        <div class="text-center py-12">
                            <i class='bx bx-search text-4xl text-gray-300 mb-2'></i>
                            <h3 class="text-lg font-medium text-gray-900">No applications found</h3>
                            <p class="text-gray-500 text-sm mt-1">
                                @if(request('status'))
                                    No <strong>{{ request('status') }}</strong> applications yet.
                                @else
                                    Try adjusting your search terms.
                                @endif
                            </p>
                            @if(request('search') || request('status'))
                                <a href="{{ route('admission.index') }}" class="text-blue-600 hover:underline text-sm mt-2 block">Clear Filters</a>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>