<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- Main Content Wrapper --}}
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            
            {{-- ======================================================= --}}
            {{-- LOGIC: TEACHER VIEW                                     --}}
            {{-- ======================================================= --}}
            @if(Auth::user()->role === 'teacher')
                
                {{-- 1. ERROR ALERT (Kung walang Staff Profile) --}}
                @if(isset($staffError))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm flex items-center">
                        <i class='bx bx-error-circle text-2xl mr-3'></i>
                        <div>
                            <p class="font-bold">Configuration Error</p>
                            <p>{{ $staffError }} Please contact the Admin to create your Staff Profile.</p>
                        </div>
                    </div>
                @else

                    {{-- 2. WELCOME BANNER --}}
                    <div class="bg-gradient-to-r from-blue-900 to-indigo-800 text-white overflow-hidden shadow-lg sm:rounded-lg mb-6 relative border border-blue-700">
                        <div class="p-6 relative z-10 flex justify-between items-center">
                            <div>
                                <h3 class="text-2xl font-bold drop-shadow-md">Welcome, Teacher {{ Auth::user()->name }}!</h3>
                                <p class="text-blue-100 text-sm mt-1">Manage your advisory class and subject loads efficiently.</p>
                            </div>
                            <div class="text-right hidden md:block">
                                <span class="text-xs font-bold text-blue-300 uppercase tracking-wider">System Date</span>
                                <p class="text-xl font-semibold drop-shadow-md">{{ date('F d, Y') }}</p>
                            </div>
                        </div>
                        {{-- Decorative Overlay --}}
                        <div class="absolute right-0 top-0 h-full w-1/3 bg-white opacity-10 skew-x-12 transform origin-bottom-right"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        {{-- 3. ADVISORY CLASS CARD (Left Side - Wider) --}}
                        <div class="md:col-span-2">
                            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg border border-gray-200 h-full flex flex-col">
                                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                                    <h4 class="font-bold text-gray-700 flex items-center uppercase text-sm tracking-wide">
                                        <i class='bx bx-chalkboard text-xl mr-2 text-indigo-600'></i>
                                        My Advisory Class
                                    </h4>
                                    {{-- Student Count Badge --}}
                                    @if(isset($advisorySection) && $advisorySection)
                                        <span class="bg-indigo-100 text-indigo-800 text-xs font-bold px-3 py-1 rounded-full border border-indigo-200">
                                            {{ $advisoryCount ?? 0 }} Students
                                        </span>
                                    @endif
                                </div>

                                <div class="p-6 flex-grow flex flex-col justify-center">
                                    @if(isset($advisorySection) && $advisorySection)
                                        <div class="text-center mb-8">
                                            <h1 class="text-4xl font-extrabold text-indigo-700 leading-tight">
                                                {{ $advisorySection->grade_level }} - {{ $advisorySection->section_name }}
                                            </h1>
                                            <p class="text-sm text-gray-500 mt-2 font-medium flex justify-center items-center">
                                                <i class='bx bx-building-house mr-1'></i> Room: {{ $advisorySection->room_number ?? 'TBA' }}
                                            </p>
                                        </div>
                                        
                                        {{-- Action Buttons --}}
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-auto">
                                            <a href="{{ route('teacher.advisory') }}" class="block p-4 bg-indigo-50 border border-indigo-100 rounded-lg hover:bg-indigo-100 transition text-center group shadow-sm hover:shadow-md">
                                                <i class='bx bx-list-ul text-3xl text-indigo-600 mb-2 group-hover:scale-110 transition block'></i>
                                                <span class="font-bold text-indigo-800 text-xs uppercase tracking-wide">View Masterlist</span>
                                            </a>
                                            <a href="{{ route('attendances.index') }}" class="block p-4 bg-green-50 border border-green-100 rounded-lg hover:bg-green-100 transition text-center group shadow-sm hover:shadow-md">
                                                <i class='bx bx-check-circle text-3xl text-green-600 mb-2 group-hover:scale-110 transition block'></i>
                                                <span class="font-bold text-green-800 text-xs uppercase tracking-wide">Check Attendance</span>
                                            </a>
                                        </div>
                                    @else
                                        {{-- Empty State --}}
                                        <div class="text-center py-10 text-gray-400">
                                            <i class='bx bx-folder-minus text-6xl mb-3 opacity-50'></i>
                                            <p class="font-medium text-lg">No advisory class assigned yet.</p>
                                            <p class="text-xs mt-1">Please contact the Registrar/Admin for assignments.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- 4. MY LOADS / SCHEDULE (Right Side - Narrower) --}}
                        <div class="md:col-span-1 space-y-6">
                            
                            {{-- Schedule List --}}
                            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg border border-gray-200">
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 font-bold text-gray-700 text-sm uppercase flex justify-between items-center">
                                    <span><i class='bx bx-book-open mr-1'></i> My Loads</span>
                                    <a href="{{ route('schedules.my') }}" class="text-xs text-blue-600 hover:text-blue-800 hover:underline">View All</a>
                                </div>
                                <div class="p-4">
                                    @if(isset($mySchedules) && $mySchedules->count() > 0)
                                        <ul class="space-y-3">
                                            @foreach($mySchedules->take(5) as $sched)
                                                <li class="text-sm border-b border-gray-100 pb-2 last:border-0 hover:bg-gray-50 p-2 rounded transition">
                                                    <div class="flex justify-between items-start">
                                                        <span class="font-bold text-gray-800">{{ $sched->subject->subject_name ?? 'Subject' }}</span>
                                                        <span class="text-[10px] font-bold text-white bg-gray-400 px-1.5 py-0.5 rounded uppercase">{{ substr($sched->day, 0, 3) }}</span>
                                                    </div>
                                                    <div class="flex justify-between mt-1 items-center">
                                                        <span class="text-xs text-gray-500">{{ $sched->section->section_name ?? 'Section' }}</span>
                                                        <span class="text-xs text-indigo-600 font-mono font-bold">{{ date('h:i A', strtotime($sched->time_start)) }}</span>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-center py-6">
                                            <p class="text-xs text-gray-400 italic">No teaching loads assigned.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Grading Button --}}
                            <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg shadow-md text-white overflow-hidden group">
                                <div class="p-5 text-center">
                                    <i class='bx bx-edit text-4xl mb-2 text-white opacity-90 group-hover:scale-110 transition duration-300'></i>
                                    <h4 class="font-bold mb-1">Grading System</h4>
                                    <p class="text-xs mb-4 opacity-90">Encode grades for your students.</p>
                                    <a href="{{ route('grades.index') }}" class="inline-block bg-white text-orange-600 font-bold py-2 px-6 rounded-full hover:bg-gray-100 transition shadow text-xs uppercase">
                                        Open Grade Sheet
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            {{-- ======================================================= --}}
            {{-- LOGIC: ADMIN VIEW (Default)                             --}}
            {{-- ======================================================= --}}
            @else
                
                {{-- 1. STATISTICS CARDS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    
                    {{-- Students --}}
                    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-6 border-l-4 border-blue-600 flex items-center justify-between group hover:shadow-xl transition transform hover:-translate-y-1">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Students</p>
                            <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $totalStudents ?? 0 }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition">
                            <i class='bx bx-user-pin text-3xl'></i>
                        </div>
                    </div>

                    {{-- Sections --}}
                    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-6 border-l-4 border-green-500 flex items-center justify-between group hover:shadow-xl transition transform hover:-translate-y-1">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Active Sections</p>
                            <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $totalSections ?? 0 }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-green-100 text-green-600 group-hover:bg-green-600 group-hover:text-white transition">
                            <i class='bx bx-chalkboard text-3xl'></i>
                        </div>
                    </div>

                    {{-- Teams --}}
                    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-6 border-l-4 border-yellow-500 flex items-center justify-between group hover:shadow-xl transition transform hover:-translate-y-1">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sports Teams</p>
                            <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $totalTeams ?? 0 }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 group-hover:bg-yellow-600 group-hover:text-white transition">
                            <i class='bx bx-trophy text-3xl'></i>
                        </div>
                    </div>

                    {{-- Events/Plans --}}
                    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-6 border-l-4 border-red-500 flex items-center justify-between group hover:shadow-xl transition transform hover:-translate-y-1">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Upcoming Plans</p>
                            <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $upcomingPlansCount ?? 0 }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-red-100 text-red-600 group-hover:bg-red-600 group-hover:text-white transition">
                            <i class='bx bx-run text-3xl'></i>
                        </div>
                    </div>
                </div>

                {{-- 2. BOTTOM SECTION: ACTIVITY & SPOTLIGHT --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    {{-- Recent Activity --}}
                    <div class="md:col-span-2 bg-white overflow-hidden shadow-md sm:rounded-lg border border-gray-200">
                        <div class="p-6 text-gray-900">
                            <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-2">
                                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                                    <i class='bx bx-history mr-2 text-gray-500'></i> Recent System Activity
                                </h3>
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">Latest Updates</span>
                            </div>
                            <div class="space-y-6">
                                @php 
                                    $safeActivities = $activities ?? collect([]); 
                                @endphp

                                @forelse($safeActivities as $activity)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-3">
                                            <div class="w-2 h-2 rounded-full bg-blue-500 mt-2 ring-4 ring-blue-50"></div>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-800 font-medium">{!! $activity->description !!}</p>
                                            <p class="text-xs text-gray-400 flex items-center mt-1">
                                                <i class='bx bx-time-five mr-1'></i>
                                                {{ $activity->created_at ? $activity->created_at->diffForHumans() : 'Just now' }}
                                            </p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <i class='bx bx-sleep-y text-4xl text-gray-300 mb-2'></i>
                                        <p class="text-sm text-gray-400 italic">No recent activities logged.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Campus Spotlight (Modal Feature) --}}
                    <div x-data="{ showModal: false }" class="bg-white overflow-hidden shadow-md sm:rounded-lg flex flex-col h-full border border-gray-200">
                        <div class="p-6 text-gray-900 flex-grow">
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Campus Spotlight</h3>
                            <p class="text-xs text-gray-500 mb-4 uppercase tracking-wide">National Academy of Sports</p>
                            
                            {{-- THUMBNAIL --}}
                            <div @click="showModal = true" class="bg-gray-100 h-48 rounded-lg flex items-center justify-center overflow-hidden mb-4 border border-gray-300 relative group cursor-pointer hover:shadow-lg transition-all duration-300">
                                <img src="{{ asset('images/nas/NAS.png') }}" 
                                     class="h-full w-full object-cover transition duration-500 group-hover:scale-110" 
                                     alt="NAS Campus View"
                                     onerror="this.src='https://placehold.co/600x400?text=No+Image';">
                                
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                    <i class='bx bx-zoom-in text-white text-4xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 drop-shadow-md'></i>
                                </div>
                            </div>
                            
                            <div class="text-center mt-4">
                                <p class="text-sm font-serif italic text-gray-600">
                                    "Home of the Filipino Student-Athletes"
                                </p>
                            </div>
                        </div>
                        
                        {{-- MODAL --}}
                        <template x-teleport="body">
                            <div x-show="showModal" 
                                 style="display: none;"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 z-[9999] flex items-center justify-center p-6"> 
                                
                                <div class="fixed inset-0 bg-gray-900/95 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>

                                <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-5xl flex flex-col max-h-[90vh] overflow-hidden transform transition-all scale-100">
                                    <button @click="showModal = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-900 z-20 bg-white/80 rounded-full p-2 hover:bg-white transition shadow-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>

                                    <div class="flex-1 bg-gray-100 flex items-center justify-center min-h-0 p-1 overflow-hidden">
                                        <img src="{{ asset('images/nas/NAS.png') }}" 
                                             class="max-w-full max-h-full w-auto h-auto object-contain rounded shadow-sm" 
                                             alt="NAS Campus Large">
                                    </div>
                                    
                                    <div class="p-4 bg-white border-t border-gray-100 text-center shrink-0">
                                        <h2 class="text-xl font-bold text-blue-900 mb-1">National Academy of Sports - Main Campus</h2>
                                        <p class="text-sm text-gray-600 font-serif italic">
                                            Located at New Clark City, Capas, Tarlac. A world-class facility for our future champions.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            @endif

        </div>
    </div>
</x-app-layout>