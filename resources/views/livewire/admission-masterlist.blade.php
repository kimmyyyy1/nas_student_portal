<div wire:poll.5s>
    {{-- DASHBOARD CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-7 gap-4 mb-6">

        <a href="{{ route('admission.index') }}" wire:navigate
            class="bg-white overflow-hidden shadow-sm rounded-lg p-4 border-t-4 border-blue-600 flex flex-col justify-between hover:shadow-md transition cursor-pointer transform hover:-translate-y-1 {{ !request('status') ? 'ring-2 ring-blue-500 bg-blue-50' : '' }}">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-wider">Total</p>
                    <p class="text-xl md:text-2xl font-extrabold text-gray-800" id="stat-submitted">
                        {{ $totalSubmitted ?? 0 }}</p>
                </div>
                <i class='bx bx-folder-open text-2xl text-blue-200'></i>
            </div>
            <p class="text-[10px] md:text-xs text-blue-600 mt-2 font-medium">All Applications</p>
        </a>

        <a href="{{ route('admission.index', ['status' => 'Pending']) }}" wire:navigate
            class="bg-white overflow-hidden shadow-sm rounded-lg p-4 border-t-4 border-yellow-500 flex flex-col justify-between hover:shadow-md transition cursor-pointer transform hover:-translate-y-1 {{ request('status') == 'Pending' ? 'ring-2 ring-yellow-500 bg-yellow-50' : '' }}">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-wider">Pending</p>
                    <p class="text-xl md:text-2xl font-extrabold text-gray-800" id="stat-pending">
                        {{ $countPending ?? 0 }}</p>
                </div>
                <i class='bx bx-time text-2xl text-yellow-200'></i>
            </div>
            <p class="text-[10px] md:text-xs text-yellow-600 mt-2 font-medium">With Pending Requirements</p>
        </a>

        <a href="{{ route('admission.index', ['status' => 'Assessment']) }}" wire:navigate
            class="bg-white overflow-hidden shadow-sm rounded-lg p-4 border-t-4 border-cyan-500 flex flex-col justify-between hover:shadow-md transition cursor-pointer transform hover:-translate-y-1 {{ request('status') == 'Assessment' ? 'ring-2 ring-cyan-500 bg-cyan-50' : '' }}">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-wider">Assessment</p>
                    <p class="text-xl md:text-2xl font-extrabold text-gray-800" id="stat-assessment">
                        {{ $countAssessment ?? 0 }}</p>
                </div>
                <i class='bx bx-edit text-2xl text-cyan-200'></i>
            </div>
            <p class="text-[10px] md:text-xs text-cyan-600 mt-2 font-medium">With Complete Requirements & for 1st Level Assessment / For 2nd Level Assessment</p>
        </a>

        <a href="{{ route('admission.index', ['status' => 'Qualified']) }}" wire:navigate
            class="bg-white overflow-hidden shadow-sm rounded-lg p-4 border-t-4 border-green-500 flex flex-col justify-between hover:shadow-md transition cursor-pointer transform hover:-translate-y-1 {{ request('status') == 'Qualified' ? 'ring-2 ring-green-500 bg-green-50' : '' }}">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-wider">Qualified</p>
                    <p class="text-xl md:text-2xl font-extrabold text-gray-800" id="stat-qualified">
                        {{ $countQualified ?? 0 }}</p>
                </div>
                <i class='bx bx-check-circle text-2xl text-green-200'></i>
            </div>
            <p class="text-[10px] md:text-xs text-green-600 mt-2 font-medium">Qualified</p>
        </a>

        <a href="{{ route('admission.index', ['status' => 'Waitlisted']) }}" wire:navigate
            class="bg-white overflow-hidden shadow-sm rounded-lg p-4 border-t-4 border-indigo-500 flex flex-col justify-between hover:shadow-md transition cursor-pointer transform hover:-translate-y-1 {{ request('status') == 'Waitlisted' ? 'ring-2 ring-indigo-500 bg-indigo-50' : '' }}">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-wider">Waitlisted</p>
                    <p class="text-xl md:text-2xl font-extrabold text-gray-800" id="stat-waitlisted">
                        {{ $countWaitlisted ?? 0 }}</p>
                </div>
                <i class='bx bx-list-ul text-2xl text-indigo-200'></i>
            </div>
            <p class="text-[10px] md:text-xs text-indigo-600 mt-2 font-medium">Waitlisted</p>
        </a>

        <a href="{{ route('admission.index', ['status' => 'Not Qualified']) }}" wire:navigate
            class="bg-white overflow-hidden shadow-sm rounded-lg p-4 border-t-4 border-red-500 flex flex-col justify-between hover:shadow-md transition cursor-pointer transform hover:-translate-y-1 {{ request('status') == 'Not Qualified' ? 'ring-2 ring-red-500 bg-red-50' : '' }}">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-wider">Failed</p>
                    <p class="text-xl md:text-2xl font-extrabold text-gray-800" id="stat-rejected">
                        {{ $countRejected ?? 0 }}</p>
                </div>
                <i class='bx bx-x-circle text-2xl text-red-200'></i>
            </div>
            <p class="text-[10px] md:text-xs text-red-600 mt-2 font-medium">Not Qualified</p>
        </a>

        <a href="{{ route('admission.index', ['status' => 'Enrolled']) }}" wire:navigate
            class="bg-white overflow-hidden shadow-sm rounded-lg p-4 border-t-4 border-purple-500 flex flex-col justify-between hover:shadow-md transition cursor-pointer transform hover:-translate-y-1 {{ request('status') == 'Enrolled' ? 'ring-2 ring-purple-500 bg-purple-50' : '' }}">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-wider">Enrolled</p>
                    <p class="text-xl md:text-2xl font-extrabold text-gray-800" id="stat-enrolled">
                        {{ $countEnrolled ?? 0 }}</p>
                </div>
                <i class='bx bxs-user-check text-2xl text-purple-200'></i>
            </div>
            <p class="text-[10px] md:text-xs text-purple-600 mt-2 font-medium">Endorsed for Enrollment</p>
        </a>

    </div>

    {{-- MAIN CONTENT AREA --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
        <div class="p-4 md:p-6 text-gray-900">

            {{-- HEADER & SEARCH --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div class="flex items-center w-full md:w-auto justify-between md:justify-start">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class='bx bx-list-ul mr-2 text-blue-600'></i>
                        @if (request('status') == 'Pending')
                            With Pending Requirements List
                        @elseif (request('status') == 'Assessment')
                            With Complete Requirements & for 1st Level Assessment / For 2nd Level Assessment List
                        @elseif (request('status'))
                            {{ request('status') }} List
                        @else
                            Masterlist
                        @endif
                    </h3>
                    @if (request('status') || request('search'))
                        <a href="{{ route('admission.index') }}" wire:navigate
                            class="ml-4 text-xs text-red-500 hover:underline flex items-center md:hidden">
                            <i class='bx bx-x-circle mr-1'></i> Clear
                        </a>
                    @endif
                </div>

                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class='bx bx-search text-gray-400'></i>
                    </div>
                    <input type="text" name="search" placeholder="Search Name/LRN..."
                        wire:model.live.debounce.500ms="search"
                        class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            @if (request('status') || request('search'))
                <div class="hidden md:block mb-4">
                    <a href="{{ route('admission.index') }}" wire:navigate
                        class="text-xs text-red-500 hover:underline flex items-center">
                        <i class='bx bx-x-circle mr-1'></i> Clear Filters
                    </a>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg border border-green-200 flex items-center"
                    role="alert">
                    <i class='bx bx-check-circle mr-2 text-lg'></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- TABLE --}}
            @if ($applications->count() > 0)
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 md:px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Details</th>
                                <th
                                    class="hidden md:table-cell px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Applied For</th>
                                <th
                                    class="hidden md:table-cell px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Dates</th>
                                <th
                                    class="px-4 md:px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-4 md:px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Action</th>
                            </tr>
                        </thead>

                        <tbody id="admission-list" class="bg-white divide-y divide-gray-200">
                            @foreach ($applications as $app)
                                <tr class="hover:bg-gray-50 transition">

                                    {{-- APPLICANT DETAILS --}}
                                    <td class="px-4 md:px-6 py-4">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-xs uppercase">
                                                {{ substr($app->first_name, 0, 1) }}{{ substr($app->last_name, 0, 1) }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-bold text-gray-900">{{ $app->last_name }},
                                                    {{ $app->first_name }}</div>
                                                <div class="text-[10px] md:text-xs text-gray-500">App ID:
                                                    #{{ str_pad($app->id, 4, '0', STR_PAD_LEFT) }}</div>

                                                {{-- Mobile View Details --}}
                                                <div class="md:hidden mt-1 text-[10px] text-gray-500">
                                                    <span
                                                        class="bg-gray-100 px-1 rounded">{{ $app->grade_level_applied }}</span>
                                                    •
                                                    {{ $app->created_at->format('M d') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- GRADE LEVEL (Desktop) --}}
                                    <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <span
                                            class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-300">
                                            {{ $app->grade_level_applied }}
                                        </span>
                                    </td>

                                    {{-- DATES (Desktop) --}}
                                    <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex flex-col text-xs">
                                            <span>Submitted: {{ $app->created_at->format('M d, Y') }}</span>
                                            @if ($app->date_checked)
                                                <span class="text-green-600">Checked:
                                                    {{ \Carbon\Carbon::parse($app->date_checked)->format('M d, Y') }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- STATUS --}}
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                        @if ($app->status == 'With Pending Requirements')
                                            <span
                                                class="px-2 inline-flex text-[10px] md:text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                {{ $app->status }}
                                            </span>
                                        @elseif(in_array($app->status, ['With Complete Requirements & for 1st Level Assessment', 'For 2nd Level Assessment']))
                                            <span
                                                class="px-2 inline-flex text-[10px] md:text-xs leading-5 font-semibold rounded-full bg-cyan-100 text-cyan-800 border border-cyan-200">
                                                {{ $app->status }}
                                            </span>
                                        @elseif($app->status == 'Qualified')
                                            <span
                                                class="px-2 inline-flex text-[10px] md:text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                {{ $app->status }}
                                            </span>
                                        @elseif($app->status == 'Waitlisted')
                                            <span
                                                class="px-2 inline-flex text-[10px] md:text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800 border border-indigo-200">
                                                {{ $app->status }}
                                            </span>
                                        @elseif($app->status == 'Not Qualified')
                                            <span
                                                class="px-2 inline-flex text-[10px] md:text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                                {{ $app->status }}
                                            </span>
                                        @elseif($app->status == 'Endorsed for Enrollment')
                                            <span
                                                class="px-2 inline-flex text-[10px] md:text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 border border-purple-200">
                                                {{ $app->status }}
                                            </span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-[10px] md:text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $app->status }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- ACTION --}}
                                    <td class="px-4 md:px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="{{ route('admission.show', $app->id) }}" wire:navigate
                                            class="text-indigo-600 hover:text-white border border-indigo-600 hover:bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center inline-flex items-center transition">
                                            <i class='bx bx-show mr-1'></i> <span
                                                class="hidden md:inline">Review</span>
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
                        @if (request('status'))
                            No <strong>{{ request('status') }}</strong> applications yet.
                        @else
                            Try adjusting your search terms.
                        @endif
                    </p>
                    @if (request('search') || request('status'))
                        <a href="{{ route('admission.index') }}" wire:navigate
                            class="text-blue-600 hover:underline text-sm mt-2 block">Clear Filters</a>
                    @endif
                </div>
            @endif

        </div>
    </div>
</div>