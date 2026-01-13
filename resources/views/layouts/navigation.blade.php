<nav x-data="{ open: false }" class="fixed left-0 top-0 bottom-0 w-64 bg-white border-r border-gray-200 z-50 flex flex-col shadow-xl">
    
    {{-- 1. SIDEBAR HEADER --}}
    <div class="h-16 flex items-center justify-center bg-gradient-to-r from-blue-900 to-indigo-800 border-b border-blue-900 shadow-sm shrink-0 overflow-visible">
        <a href="{{ Auth::user()->role === 'student' ? route('student.dashboard') : route('dashboard') }}" class="flex items-center space-x-2">
            <img src="{{ asset('images/nas/nas-logo-spotlight.jpg') }}" class="h-10 w-10 bg-white rounded-full p-0.5 object-cover shadow-sm" alt="NAS Logo">
            <div class="flex flex-col">
                <span class="text-white font-black text-lg leading-tight tracking-wide">NAS SAIS</span>
                <span class="text-blue-200 text-[10px] uppercase tracking-wider">{{ Auth::user()->role ?? 'Portal' }}</span>
            </div>
        </a>
    </div>

    {{-- 2. SCROLLABLE MENU AREA (Nilagyan ng ID: sidebar-menu) --}}
    <div id="sidebar-menu" class="flex-1 overflow-y-auto custom-scrollbar py-4 px-3 space-y-1">

        {{-- ROLE: STUDENT --}}
        @if(Auth::user()->role === 'student')
            <a href="{{ route('student.dashboard') }}" 
               class="flex items-center px-4 py-3 text-sm font-bold rounded-lg transition-all duration-200 group
               {{ request()->routeIs('student.dashboard') ? 'bg-indigo-50 text-indigo-800 shadow-sm ring-1 ring-indigo-200' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-700' }}">
                <i class='bx bxs-dashboard text-xl mr-3 {{ request()->routeIs('student.dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}'></i>
                My Dashboard
            </a>

        {{-- ROLE: TEACHER --}}
        @elseif(Auth::user()->role === 'teacher')
            <a href="{{ route('dashboard') }}" 
               class="flex items-center px-4 py-3 text-sm font-bold rounded-lg transition-all duration-200 group
               {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-800 shadow-sm ring-1 ring-indigo-200' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-700' }}">
                <i class='bx bxs-dashboard text-xl mr-3 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}'></i>
                Teacher Dashboard
            </a>

            <div class="pt-4 pb-1">
                <p class="px-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Class Management</p>
            </div>

            <a href="{{ route('teacher.advisory') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('teacher.advisory') ? 'bg-indigo-50 text-indigo-800 border-r-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-700' }}">
                <i class='bx bx-chalkboard text-lg mr-3 {{ request()->routeIs('teacher.advisory') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}'></i>
                My Advisory Class
            </a>
            <a href="{{ route('schedules.my') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('schedules.my') ? 'bg-indigo-50 text-indigo-800 border-r-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-700' }}">
                <i class='bx bx-book-open text-lg mr-3 {{ request()->routeIs('schedules.my') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}'></i>
                My Loads & Sched
            </a>
            <a href="{{ route('grades.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('grades.*') ? 'bg-indigo-50 text-indigo-800 border-r-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-700' }}">
                <i class='bx bx-bar-chart-alt-2 text-lg mr-3 {{ request()->routeIs('grades.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}'></i>
                Grading Sheets
            </a>
            <a href="{{ route('attendances.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('attendances.*') ? 'bg-indigo-50 text-indigo-800 border-r-4 border-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-700' }}">
                <i class='bx bx-check-circle text-lg mr-3 {{ request()->routeIs('attendances.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}'></i>
                Attendance
            </a>

        {{-- ROLE: COACH --}}
        @elseif(Auth::user()->role === 'coach')
            <a href="{{ route('dashboard') }}" 
               class="flex items-center px-4 py-3 text-sm font-bold rounded-lg transition-all duration-200 group
               {{ request()->routeIs('dashboard') ? 'bg-orange-50 text-orange-800 shadow-sm ring-1 ring-orange-200' : 'text-gray-600 hover:bg-gray-50 hover:text-orange-700' }}">
                <i class='bx bxs-dashboard text-xl mr-3 {{ request()->routeIs('dashboard') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-600' }}'></i>
                Coach Dashboard
            </a>

            <div class="pt-4 pb-1">
                <p class="px-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Sports Management</p>
            </div>

            <a href="{{ route('teams.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('teams.*') ? 'bg-orange-50 text-orange-800 border-r-4 border-orange-600' : 'text-gray-600 hover:bg-gray-50 hover:text-orange-700' }}">
                <i class='bx bx-trophy text-lg mr-3 {{ request()->routeIs('teams.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-600' }}'></i>
                Sports Teams
            </a>
            <a href="{{ route('training-plans.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('training-plans.*') ? 'bg-orange-50 text-orange-800 border-r-4 border-orange-600' : 'text-gray-600 hover:bg-gray-50 hover:text-orange-700' }}">
                <i class='bx bx-run text-lg mr-3 {{ request()->routeIs('training-plans.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-600' }}'></i>
                Training Plans
            </a>
            <a href="{{ route('medical-records.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('medical-records.*') ? 'bg-orange-50 text-orange-800 border-r-4 border-orange-600' : 'text-gray-600 hover:bg-gray-50 hover:text-orange-700' }}">
                <i class='bx bx-pulse text-lg mr-3 {{ request()->routeIs('medical-records.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-600' }}'></i>
                Medical Records
            </a>
            <a href="{{ route('schedules.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('schedules.*') ? 'bg-orange-50 text-orange-800 border-r-4 border-orange-600' : 'text-gray-600 hover:bg-gray-50 hover:text-orange-700' }}">
                <i class='bx bx-calendar text-lg mr-3 {{ request()->routeIs('schedules.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-600' }}'></i>
                Training Schedules
            </a>
            <a href="{{ route('attendances.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('attendances.*') ? 'bg-orange-50 text-orange-800 border-r-4 border-orange-600' : 'text-gray-600 hover:bg-gray-50 hover:text-orange-700' }}">
                <i class='bx bx-check-circle text-lg mr-3 {{ request()->routeIs('attendances.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-600' }}'></i>
                Attendance
            </a>

        {{-- ROLE: ADMIN --}}
        @else
            <a href="{{ route('dashboard') }}" 
               class="flex items-center px-4 py-3 text-sm font-bold rounded-lg transition-all duration-200 group
               {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-800 shadow-sm ring-1 ring-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <i class='bx bxs-dashboard text-xl mr-3 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i>
                Admin Dashboard
            </a>

            <div class="pt-4 pb-1">
                <p class="px-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Enrollment</p>
            </div>
            <a href="{{ route('admission.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('admission.*') ? 'bg-blue-50 text-blue-800 border-r-4 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <i class='bx bx-id-card text-lg mr-3 {{ request()->routeIs('admission.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i>
                <span class="flex-1">Admissions</span>
                @if(isset($pendingAdmissionsCount) && $pendingAdmissionsCount > 0)
                    <span class="inline-flex items-center justify-center px-2 py-0.5 text-[10px] font-bold leading-none text-red-100 bg-red-600 rounded-full shadow-sm">
                        {{ $pendingAdmissionsCount }}
                    </span>
                @endif
            </a>
            <a href="{{ route('students.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('students.*') ? 'bg-blue-50 text-blue-800 border-r-4 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <i class='bx bx-user-pin text-lg mr-3 {{ request()->routeIs('students.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i>
                Student Directory
            </a>

            <div class="pt-4 pb-1">
                <p class="px-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Academics</p>
            </div>
            <a href="{{ route('sections.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('sections.*') ? 'bg-blue-50 text-blue-800 border-r-4 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <i class='bx bx-chalkboard text-lg mr-3 {{ request()->routeIs('sections.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i>
                Sections & Classes
            </a>
            <a href="{{ route('subjects.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('subjects.*') ? 'bg-blue-50 text-blue-800 border-r-4 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <i class='bx bx-book text-lg mr-3 {{ request()->routeIs('subjects.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i>
                Subjects
            </a>
            <a href="{{ route('schedules.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('schedules.*') ? 'bg-blue-50 text-blue-800 border-r-4 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <i class='bx bx-calendar text-lg mr-3 {{ request()->routeIs('schedules.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i>
                Class Schedules
            </a>
            <a href="{{ route('grades.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('grades.*') ? 'bg-blue-50 text-blue-800 border-r-4 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <i class='bx bx-bar-chart-alt-2 text-lg mr-3 {{ request()->routeIs('grades.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i>
                Grades
            </a>
            <a href="{{ route('attendances.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('attendances.*') ? 'bg-blue-50 text-blue-800 border-r-4 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <i class='bx bx-check-circle text-lg mr-3 {{ request()->routeIs('attendances.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i>
                Attendance
            </a>

            <div class="pt-4 pb-1">
                <p class="px-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Sports</p>
            </div>
            <a href="{{ route('teams.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('teams.*') ? 'bg-blue-50 text-blue-800 border-r-4 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <i class='bx bx-trophy text-lg mr-3 {{ request()->routeIs('teams.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i>
                Sports Teams
            </a>
            <a href="{{ route('training-plans.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('training-plans.*') ? 'bg-blue-50 text-blue-800 border-r-4 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <i class='bx bx-run text-lg mr-3 {{ request()->routeIs('training-plans.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i>
                Training Plans
            </a>
            <a href="{{ route('medical-records.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('medical-records.*') ? 'bg-blue-50 text-blue-800 border-r-4 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <i class='bx bx-pulse text-lg mr-3 {{ request()->routeIs('medical-records.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i>
                Medical Records
            </a>

            <div class="pt-4 pb-1">
                <p class="px-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">System</p>
            </div>
            <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('reports.*') ? 'bg-blue-50 text-blue-800 border-r-4 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <i class='bx bxs-report text-lg mr-3 {{ request()->routeIs('reports.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i>
                Reports & Forms
            </a>
            <a href="{{ route('staff.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('staff.*') ? 'bg-blue-50 text-blue-800 border-r-4 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                <i class='bx bx-cog text-lg mr-3 {{ request()->routeIs('staff.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i>
                User Management
            </a>
        @endif

    </div>

    {{-- 3. USER PROFILE & LOGOUT --}}
    <div class="p-4 border-t border-gray-200 bg-gray-50 shrink-0">
        
        {{-- User Info --}}
        <div class="flex items-center mb-3">
            <div class="flex-shrink-0">
                <div class="h-9 w-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
            <div class="ml-3 w-full min-w-0">
                <p class="text-sm font-bold text-gray-900 truncate" title="{{ Auth::user()->name }}">
                    {{ Auth::user()->name }}
                </p>
                <p class="text-xs text-gray-500 truncate capitalize">
                    {{ Auth::user()->role }}
                </p>
            </div>
        </div>

        {{-- Action Buttons Grid --}}
        <div class="grid grid-cols-2 gap-2">
            
            {{-- Profile Button --}}
            <a href="{{ route('profile.edit') }}" 
               class="flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-xs font-bold rounded-md text-gray-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <i class='bx bx-user mr-1 text-sm'></i>
                Profile
            </a>

            {{-- Sign Out Button --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center justify-center px-3 py-2 border border-transparent shadow-sm text-xs font-bold rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                    <i class='bx bx-log-out mr-1 text-sm'></i>
                    Sign Out
                </button>
            </form>

        </div>
    </div>
</nav>

{{-- SCRIPT PARA HINDI BUMALIK SA TAAS ANG SCROLLBAR --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar-menu');
        if (sidebar) {
            // Restore scroll position
            const pos = localStorage.getItem('sidebar-scroll-pos');
            if (pos) sidebar.scrollTop = pos;

            // Save scroll position when leaving pages
            window.addEventListener('beforeunload', () => {
                localStorage.setItem('sidebar-scroll-pos', sidebar.scrollTop);
            });
        }
    });
</script>