{{-- CSS OPTIMIZATION --}}
<style>
    nav.fixed { will-change: transform; z-index: 50; }
    
    /* Hide scrollbar */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* Instant scroll restoration */
    #sidebar-menu { scroll-behavior: auto !important; }
</style>

<div x-data="{ open: false }">

    {{-- 1. MOBILE HEADER --}}
    <div class="md:hidden fixed top-0 left-0 w-full h-16 bg-white/90 backdrop-blur-md border-b border-gray-200/50 z-40 flex items-center justify-between px-4 shadow-sm transition-all duration-300">
        <div class="flex items-center">
            <img src="{{ asset('images/nas/horizontal.png') }}" alt="NAS Logo" class="h-8 w-auto drop-shadow-sm">
        </div>
        
        {{-- Hamburger with Click Effect --}}
        <button @click="open = !open" class="p-2 rounded-md text-gray-600 hover:bg-black/5 focus:outline-none transition transform active:scale-90">
            <i class='bx bx-menu text-3xl'></i>
        </button>
    </div>

    {{-- 2. MOBILE OVERLAY --}}
    <div x-show="open" @click="open = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/60 z-40 md:hidden backdrop-blur-[2px]">
    </div>

    {{-- 3. SIDEBAR --}}
    <nav :class="{'translate-x-0': open, '-translate-x-full': !open}"
         class="fixed left-0 top-0 bottom-0 w-64 bg-white/95 backdrop-blur-xl border-r border-white/20 z-50 shadow-2xl no-print 
                transition-transform duration-300 ease-in-out 
                md:translate-x-0 transform -translate-x-full md:transition-none flex flex-col">
        
        {{-- Close Button with Click Effect --}}
        <div class="md:hidden absolute top-4 right-4 z-[60]">
             <button @click="open = false" class="text-gray-500 hover:text-red-600 bg-gray-100/80 rounded-full p-2 transition shadow-sm transform active:scale-90">
                <i class='bx bx-x text-2xl leading-none'></i>
             </button>
        </div>

        {{-- SCROLLABLE CONTAINER --}}
        <div id="sidebar-menu" class="flex-1 overflow-y-auto no-scrollbar">

            {{-- HEADER LOGO (Animated) --}}
            <div class="h-24 flex items-center justify-center pt-4 pb-2 shrink-0">
                <a href="{{ Auth::user()->role === 'student' ? route('student.dashboard') : route('dashboard') }}" wire:navigate 
                   class="block w-full px-6 transform active:scale-95 transition-transform duration-200">
                    <img src="{{ asset('images/nas/horizontal.png') }}" alt="NAS Logo" class="h-auto w-full object-contain drop-shadow-md hover:scale-105 transition-transform duration-300"> 
                </a>
            </div>

            {{-- MENU ITEMS --}}
            <div class="px-3 space-y-1 pb-4">
                
                {{-- 👇 HELPERS: Animation Classes Added (active:scale-95) --}}
                @php
                    // For Main Dashboard items (Bolder font)
                    $navMainClass = "flex items-center px-4 py-3 text-sm font-bold rounded-lg transition-all duration-200 group transform active:scale-95";
                    // For Sub-items (Medium font)
                    $navSubClass = "flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group transform active:scale-95";
                    
                    // Colors
                    $activeIndigo = "bg-indigo-50 text-indigo-800 shadow-sm ring-1 ring-indigo-200";
                    $inactiveIndigo = "text-gray-600 hover:bg-gray-50 hover:text-indigo-700";
                    
                    $activeBlue = "bg-blue-50 text-blue-800 border-r-4 border-blue-600";
                    $inactiveBlue = "text-gray-600 hover:bg-gray-50 hover:text-blue-700";

                    $activeOrange = "bg-orange-50 text-orange-800 border-r-4 border-orange-600";
                    $inactiveOrange = "text-gray-600 hover:bg-gray-50 hover:text-orange-700";
                @endphp

                @if(Auth::user()->role === 'student')
                    <a href="{{ route('student.dashboard') }}" wire:navigate
                       class="{{ $navMainClass }} {{ request()->routeIs('student.dashboard') ? $activeIndigo : $inactiveIndigo }}">
                        <i class='bx bxs-dashboard text-xl mr-3 {{ request()->routeIs('student.dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}'></i>
                        My Dashboard
                    </a>

                @elseif(Auth::user()->role === 'teacher')
                    <a href="{{ route('dashboard') }}" wire:navigate
                       class="{{ $navMainClass }} {{ request()->routeIs('dashboard') ? $activeIndigo : $inactiveIndigo }}">
                        <i class='bx bxs-dashboard text-xl mr-3 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}'></i>
                        Teacher Dashboard
                    </a>
                    <div class="pt-4 pb-1"><p class="px-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Class Management</p></div>
                    
                    <a href="{{ route('teacher.advisory') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('teacher.advisory') ? $activeBlue : $inactiveBlue }}">
                        <i class='bx bx-chalkboard text-lg mr-3 {{ request()->routeIs('teacher.advisory') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}'></i> My Advisory Class
                    </a>
                    <a href="{{ route('schedules.my') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('schedules.my') ? $activeBlue : $inactiveBlue }}">
                        <i class='bx bx-book-open text-lg mr-3 {{ request()->routeIs('schedules.my') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}'></i> My Loads & Sched
                    </a>
                    <a href="{{ route('grades.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('grades.*') ? $activeBlue : $inactiveBlue }}">
                        <i class='bx bx-bar-chart-alt-2 text-lg mr-3 {{ request()->routeIs('grades.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}'></i> Grading Sheets
                    </a>
                    <a href="{{ route('attendances.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('attendances.*') ? $activeBlue : $inactiveBlue }}">
                        <i class='bx bx-check-circle text-lg mr-3 {{ request()->routeIs('attendances.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}'></i> Attendance
                    </a>

                @elseif(Auth::user()->role === 'coach')
                    <a href="{{ route('dashboard') }}" wire:navigate
                       class="{{ $navMainClass }} {{ request()->routeIs('dashboard') ? 'bg-orange-50 text-orange-800 shadow-sm ring-1 ring-orange-200' : 'text-gray-600 hover:bg-gray-50 hover:text-orange-700' }}">
                        <i class='bx bxs-dashboard text-xl mr-3 {{ request()->routeIs('dashboard') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-600' }}'></i> Coach Dashboard
                    </a>
                    <div class="pt-4 pb-1"><p class="px-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Sports Management</p></div>
                    
                    <a href="{{ route('teams.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('teams.*') ? $activeOrange : $inactiveOrange }}">
                        <i class='bx bx-trophy text-lg mr-3 {{ request()->routeIs('teams.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-600' }}'></i> Sports Teams
                    </a>
                    <a href="{{ route('training-plans.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('training-plans.*') ? $activeOrange : $inactiveOrange }}">
                        <i class='bx bx-run text-lg mr-3 {{ request()->routeIs('training-plans.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-600' }}'></i> Training Plans
                    </a>
                    <a href="{{ route('medical-records.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('medical-records.*') ? $activeOrange : $inactiveOrange }}">
                        <i class='bx bx-pulse text-lg mr-3 {{ request()->routeIs('medical-records.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-600' }}'></i> Medical Records
                    </a>
                    <a href="{{ route('schedules.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('schedules.*') ? $activeOrange : $inactiveOrange }}">
                        <i class='bx bx-calendar text-lg mr-3 {{ request()->routeIs('schedules.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-600' }}'></i> Training Schedules
                    </a>
                    <a href="{{ route('attendances.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('attendances.*') ? $activeOrange : $inactiveOrange }}">
                        <i class='bx bx-check-circle text-lg mr-3 {{ request()->routeIs('attendances.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-600' }}'></i> Attendance
                    </a>

                @else
                    {{-- ADMIN --}}
                    <a href="{{ route('dashboard') }}" wire:navigate
                       class="{{ $navMainClass }} {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-800 shadow-sm ring-1 ring-blue-200' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-700' }}">
                        <i class='bx bxs-dashboard text-xl mr-3 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i> Admin Dashboard
                    </a>

                    <div class="pt-4 pb-1"><p class="px-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Enrollment</p></div>
                    
                    <a href="{{ route('admission.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('admission.*') ? $activeBlue : $inactiveBlue }}">
                        <i class='bx bx-id-card text-lg mr-3 {{ request()->routeIs('admission.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i>
                        <span class="flex-1">Admissions</span>
                        @if(isset($pendingAdmissionsCount) && $pendingAdmissionsCount > 0)
                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-[10px] font-bold text-red-100 bg-red-600 rounded-full shadow-sm">{{ $pendingAdmissionsCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('students.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('students.*') ? $activeBlue : $inactiveBlue }}">
                        <i class='bx bx-user-pin text-lg mr-3 {{ request()->routeIs('students.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i> Student Directory
                    </a>

                    <div class="pt-4 pb-1"><p class="px-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Academics</p></div>
                    
                    <a href="{{ route('sections.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('sections.*') ? $activeBlue : $inactiveBlue }}">
                        <i class='bx bx-chalkboard text-lg mr-3 {{ request()->routeIs('sections.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i> Sections & Classes
                    </a>
                    <a href="{{ route('subjects.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('subjects.*') ? $activeBlue : $inactiveBlue }}">
                        <i class='bx bx-book text-lg mr-3 {{ request()->routeIs('subjects.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i> Subjects
                    </a>
                    <a href="{{ route('schedules.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('schedules.*') ? $activeBlue : $inactiveBlue }}">
                        <i class='bx bx-calendar text-lg mr-3 {{ request()->routeIs('schedules.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i> Class Schedules
                    </a>
                    <a href="{{ route('grades.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('grades.*') ? $activeBlue : $inactiveBlue }}">
                        <i class='bx bx-bar-chart-alt-2 text-lg mr-3 {{ request()->routeIs('grades.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i> Grades
                    </a>
                    <a href="{{ route('attendances.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('attendances.*') ? $activeBlue : $inactiveBlue }}">
                        <i class='bx bx-check-circle text-lg mr-3 {{ request()->routeIs('attendances.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i> Attendance
                    </a>

                    <div class="pt-4 pb-1"><p class="px-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Sports</p></div>
                    
                    <a href="{{ route('teams.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('teams.*') ? $activeBlue : $inactiveBlue }}">
                        <i class='bx bx-trophy text-lg mr-3 {{ request()->routeIs('teams.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i> Sports Teams
                    </a>
                    <a href="{{ route('training-plans.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('training-plans.*') ? $activeBlue : $inactiveBlue }}">
                        <i class='bx bx-run text-lg mr-3 {{ request()->routeIs('training-plans.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i> Training Plans
                    </a>
                    <a href="{{ route('medical-records.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('medical-records.*') ? $activeBlue : $inactiveBlue }}">
                        <i class='bx bx-pulse text-lg mr-3 {{ request()->routeIs('medical-records.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i> Medical Records
                    </a>

                    <div class="pt-4 pb-1"><p class="px-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">System</p></div>
                    
                    <a href="{{ route('reports.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('reports.*') ? $activeBlue : $inactiveBlue }}">
                        <i class='bx bxs-report text-lg mr-3 {{ request()->routeIs('reports.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i> Reports & Forms
                    </a>
                    <a href="{{ route('staff.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('staff.*') ? $activeBlue : $inactiveBlue }}">
                        <i class='bx bx-cog text-lg mr-3 {{ request()->routeIs('staff.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i> User Management
                    </a>
                @endif
            </div>
        </div>

        {{-- USER PROFILE & LOGOUT --}}
        <div class="p-4 border-t border-gray-200/50 bg-gray-50/80 shrink-0 backdrop-blur-sm mt-auto">
            <div class="flex items-center mb-3">
                <div class="flex-shrink-0">
                    <div class="h-9 w-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
                <div class="ml-3 w-full min-w-0">
                    <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate capitalize">{{ Auth::user()->role }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('profile.edit') }}" wire:navigate class="flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-xs font-bold rounded-md text-gray-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition transform active:scale-95">
                    <i class='bx bx-user mr-1 text-sm'></i> Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-3 py-2 border border-transparent shadow-sm text-xs font-bold rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition transform active:scale-95">
                        <i class='bx bx-log-out mr-1 text-sm'></i> Sign Out
                    </button>
                </form>
            </div>
        </div>
    </nav>
</div>

{{-- SCRIPT: Restore sidebar scroll position --}}
<script>
    (function() {
        const sidebar = document.getElementById('sidebar-menu');
        const key = 'sidebarScroll';
        if (sidebar) {
            const saved = sessionStorage.getItem(key);
            if (saved) sidebar.scrollTop = parseInt(saved);
            sidebar.addEventListener('scroll', () => sessionStorage.setItem(key, sidebar.scrollTop));
        }
    })();
</script>