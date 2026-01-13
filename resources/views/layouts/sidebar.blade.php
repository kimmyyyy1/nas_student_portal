<aside class="w-64 bg-white border-r border-gray-200 h-screen fixed left-0 top-0 flex flex-col z-50 no-print transition-all duration-300">
    
    {{-- 👇 Updated Header Section with Horizontal Logo --}}
    <div class="h-24 flex items-center justify-center border-b border-gray-100 bg-white shrink-0 px-4">
        {{-- Link to Dashboard --}}
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center">
            {{-- Horizontal Logo Image --}}
            <img src="{{ asset('images/nas/horizontal.png') }}" 
                 alt="NAS Logo" 
                 class="h-12 w-auto object-contain mb-1"> {{-- Adjusted height to fit --}}
            
            {{-- User Role Badge below logo --}}
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50 px-2 py-0.5 rounded-full border border-gray-100">
                {{ ucfirst(Auth::user()->role) }}
            </span>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-6 custom-scrollbar">
        <ul class="space-y-1 pb-20">
            
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg text-sm font-medium transition-colors group {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
            </li>

            @if(Auth::user()->role === 'admin')
                <li class="mt-6 mb-2 px-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Registrar</li>
                
                <li>
                    <a href="{{ route('admission.index') }}" class="flex items-center justify-between p-3 rounded-lg text-sm font-medium transition-colors group {{ request()->routeIs('admission.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Admission
                        </div>
                        @if(isset($unreadCount) && $unreadCount > 0)
                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold text-white bg-red-600 rounded-full shadow-sm animate-pulse">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </li>

                <li>
                    <a href="{{ route('students.enrollment') }}" class="flex items-center justify-between p-3 rounded-lg text-sm font-medium transition-colors group {{ request()->routeIs('students.enrollment') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Enrollment
                        </div>
                        @if(isset($enrollmentCount) && $enrollmentCount > 0)
                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold text-white bg-green-600 rounded-full shadow-sm">{{ $enrollmentCount }}</span>
                        @endif
                    </a>
                </li>
            @endif

            @if(in_array(Auth::user()->role, ['admin', 'coach', 'teacher', 'sass']))
                <li class="mt-6 mb-2 px-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Directory</li>
                <li>
                    <a href="{{ route('students.index') }}" class="flex items-center p-3 rounded-lg text-sm font-medium transition-colors group {{ request()->routeIs('students.index') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Student Directory
                    </a>
                </li>
            @endif

            @if(in_array(Auth::user()->role, ['admin', 'coach', 'teacher']))
                <li class="mt-6 mb-2 px-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Academic & Grading</li>
                
                @if(Auth::user()->role === 'admin')
                    <li><a href="{{ route('sections.index') }}" class="flex items-center p-3 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100"><span class="ml-8">Classes & Sections</span></a></li>
                    <li><a href="{{ route('subjects.index') }}" class="flex items-center p-3 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100"><span class="ml-8">Subjects</span></a></li>
                    <li><a href="{{ route('schedules.index') }}" class="flex items-center p-3 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100"><span class="ml-8">Manage Schedules</span></a></li>
                @endif
                
                @if(in_array(Auth::user()->role, ['teacher', 'coach']))
                    <li>
                        <a href="{{ route('schedules.my') }}" class="flex items-center p-3 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 group">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            My Schedule
                        </a>
                    </li>
                @endif

                <li>
                    <a href="{{ route('grades.index') }}" class="flex items-center p-3 rounded-lg text-sm font-medium transition-colors group {{ request()->routeIs('grades.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Encode Grades
                    </a>
                </li>
                <li>
                    <a href="{{ route('attendances.index') }}" class="flex items-center p-3 rounded-lg text-sm font-medium transition-colors group {{ request()->routeIs('attendances.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Attendance
                    </a>
                </li>
            @endif

            @if(in_array(Auth::user()->role, ['admin', 'coach']))
                <li class="mt-6 mb-2 px-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Sports</li>
                <li><a href="{{ route('teams.index') }}" class="flex items-center p-3 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100"><span class="ml-8">Sports & Teams</span></a></li>
                <li><a href="{{ route('training-plans.index') }}" class="flex items-center p-3 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100"><span class="ml-8">Training Plans</span></a></li>
            @endif

            @if(in_array(Auth::user()->role, ['admin', 'sass']))
                <li class="mt-6 mb-2 px-3 text-xs font-bold text-gray-400 uppercase tracking-wider">SASS / Welfare</li>
                <li>
                    <a href="{{ route('medical-records.index') }}" class="flex items-center p-3 rounded-lg text-sm font-medium transition-colors group {{ request()->routeIs('medical-records.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        Medical Records
                    </a>
                </li>
            @endif

            @if(Auth::user()->role === 'admin')
                <li class="mt-6 mb-2 px-3 text-xs font-bold text-gray-400 uppercase tracking-wider">System</li>
                <li><a href="{{ route('reports.index') }}" class="flex items-center p-3 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100"><span class="ml-8">Reports</span></a></li>
                <li><a href="{{ route('staff.index') }}" class="flex items-center p-3 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100"><span class="ml-8">Staff Management</span></a></li>
            @endif

        </ul>
    </nav>

    <div class="border-t border-gray-100 bg-white p-4 shrink-0">
        <a href="{{ route('profile.edit') }}" class="flex items-center p-2 mb-2 text-gray-700 rounded-lg hover:bg-gray-100 transition">
            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <span class="text-sm font-medium">My Profile</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center w-full p-2 text-gray-600 hover:text-red-600 rounded-lg hover:bg-red-50 transition duration-200 group">
                <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span class="text-sm font-medium">Log Out</span>
            </button>
        </form>
    </div>
</aside>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
</style>