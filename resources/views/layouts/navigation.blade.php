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

    {{-- 1. MOBILE & TABLET HEADER --}}
    <div class="lg:hidden fixed top-0 left-0 w-full h-16 bg-white/90 backdrop-blur-md border-b border-gray-200/50 z-40 flex items-center justify-between px-4 sm:px-6 shadow-sm transition-all duration-300">
        <div class="flex items-center">
            <img src="{{ asset('images/nas/horizontal.png') }}" alt="NAS Logo" class="h-8 md:h-10 w-auto drop-shadow-sm">
        </div>
        
        <div class="flex items-center gap-3">
            <button @click="open = !open" class="p-2 rounded-md text-gray-600 hover:bg-black/5 focus:outline-none transition transform active:scale-90">
                <i class='bx bx-menu text-3xl md:text-4xl'></i>
            </button>
        </div>
    </div>

    {{-- 2. MOBILE & TABLET OVERLAY --}}
    <div x-show="open" @click="open = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/60 z-40 lg:hidden backdrop-blur-[2px]">
    </div>

    {{-- 3. SIDEBAR --}}
    <nav :class="{'translate-x-0': open, '-translate-x-full': !open}"
         class="fixed left-0 top-0 bottom-0 w-64 bg-white/60 backdrop-blur-2xl border-r border-white/50 z-50 shadow-[4px_0_24px_rgba(0,0,0,0.02)] no-print 
                transition-transform duration-300 ease-in-out 
                lg:translate-x-0 transform -translate-x-full lg:transition-none flex flex-col">
        
        {{-- Close Button for Mobile & Tablet --}}
        <div class="lg:hidden absolute top-4 right-4 z-[60]">
             <button @click="open = false" class="text-gray-500 hover:text-red-600 bg-gray-100/80 rounded-full p-2 transition shadow-sm transform active:scale-90">
                <i class='bx bx-x text-2xl leading-none'></i>
             </button>
        </div>

        {{-- ⚡ WIRE:IGNORE.SELF ADDED HERE ⚡ --}}
        <div id="sidebar-menu" class="flex-1 overflow-y-auto no-scrollbar" wire:ignore.self>
             
            <div class="h-24 flex items-center justify-center pt-4 pb-2 shrink-0">
                <a href="{{ Auth::user()->role === 'student' ? route('student.dashboard') : route('dashboard') }}" wire:navigate 
                   class="block w-full px-6 transform active:scale-95 transition-transform duration-200">
                    <img src="{{ asset('images/nas/horizontal.png') }}" alt="NAS Logo" class="h-auto w-full object-contain drop-shadow-md hover:scale-105 transition-transform duration-300"> 
                </a>
            </div>

            <div class="px-3 space-y-1 pb-4">
                @php
                    $navMainClass = "flex items-center px-4 py-3 text-[13px] font-bold rounded-xl transition-all duration-300 group hover:translate-x-1";
                    $navSubClass = "flex items-center px-4 py-2.5 text-[13px] font-semibold rounded-xl transition-all duration-300 group hover:translate-x-1";
                    
                    $activeStyle = "bg-gradient-to-r from-indigo-100/80 to-transparent text-indigo-700 font-extrabold shadow-sm relative before:absolute before:inset-y-2 before:left-0 before:w-1.5 before:bg-indigo-600 before:rounded-r-full";
                    $inactiveStyle = "text-slate-500 hover:bg-white/50 hover:text-indigo-600";
                    
                    // We map the old variable names to the new unified premium style
                    $activeIndigo = $activeStyle;
                    $inactiveIndigo = $inactiveStyle;
                    $activeBlue = $activeStyle;
                    $inactiveBlue = $inactiveStyle;
                    $activeOrange = "bg-gradient-to-r from-orange-100/80 to-transparent text-orange-700 font-extrabold shadow-sm relative before:absolute before:inset-y-2 before:left-0 before:w-1.5 before:bg-orange-600 before:rounded-r-full";
                    $inactiveOrange = "text-slate-500 hover:bg-white/50 hover:text-orange-600";
                @endphp

                @if(Auth::user()->role === 'student')
                    <a href="{{ route('student.dashboard') }}" wire:navigate class="{{ $navMainClass }} {{ request()->routeIs('student.dashboard') ? $activeIndigo : $inactiveIndigo }}">
                        <i class='bx bxs-dashboard text-xl mr-3 {{ request()->routeIs('student.dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}'></i>
                        My Dashboard
                    </a>

                @elseif(Auth::user()->role === 'teacher')
                    <a href="{{ route('dashboard') }}" wire:navigate class="{{ $navMainClass }} {{ request()->routeIs('dashboard') ? $activeIndigo : $inactiveIndigo }}">
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
                    <a href="{{ route('dashboard') }}" wire:navigate class="{{ $navMainClass }} {{ request()->routeIs('dashboard') ? 'bg-orange-50 text-orange-800 shadow-sm ring-1 ring-orange-200' : 'text-gray-600 hover:bg-gray-50 hover:text-orange-700' }}">
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
                    {{-- ========================================================================= --}}
                    {{-- 👑 ADMIN DASHBOARD --}}
                    {{-- ========================================================================= --}}
                    
                    <a href="{{ route('dashboard') }}" wire:navigate
                       class="{{ $navMainClass }} {{ request()->routeIs('dashboard') ? $activeBlue : $inactiveBlue }}">
                        <i class='bx bxs-dashboard text-xl mr-3 {{ request()->routeIs('dashboard') ? 'text-indigo-600 drop-shadow-sm' : 'text-slate-400 group-hover:text-indigo-500' }}'></i> Admin Dashboard
                    </a>

                    {{-- 🔹 ENROLLMENT GROUP --}}
                    <div class="pt-5 pb-1.5"><p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Enrollment</p></div>
                    
                    <a href="{{ route('admission.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('admission.*') ? $activeBlue : $inactiveBlue }}">
                        <img src="{{ asset('images/nas/NASCENT SAS ICON.png') }}" alt="NASCENT SAS ICON" class="h-5 w-5 mr-3 {{ request()->routeIs('admission.*') ? 'drop-shadow-sm' : 'opacity-70 group-hover:opacity-100' }}">
                        <span class="flex-1">NASCENT SAS</span>
                        @if(isset($pendingAdmissionsCount) && $pendingAdmissionsCount > 0)
                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-[10px] font-bold text-rose-100 bg-gradient-to-r from-rose-500 to-pink-500 rounded-full shadow-sm">{{ $pendingAdmissionsCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('official-enrollment.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('official-enrollment.*') ? $activeBlue : $inactiveBlue }}">
                        <i class='bx bx-user-plus text-lg mr-3 {{ request()->routeIs('official-enrollment.*') ? 'text-indigo-600 drop-shadow-sm' : 'text-slate-400 group-hover:text-indigo-500' }}'></i>
                        Official Enrollment
                    </a>

                    <a href="{{ route('students.index') }}" wire:navigate class="{{ $navSubClass }} {{ request()->routeIs('students.*') ? $activeBlue : $inactiveBlue }}">
                        <i class='bx bx-user-pin text-lg mr-3 {{ request()->routeIs('students.*') ? 'text-indigo-600 drop-shadow-sm' : 'text-slate-400 group-hover:text-indigo-500' }}'></i> Student Directory
                    </a>

                    @if(Auth::user()->name !== 'Registrar')
                        <a href="{{ url('/admin/settings') }}" wire:navigate class="{{ $navSubClass }} {{ request()->is('admin/settings') ? $activeBlue : $inactiveBlue }}">
                            <i class='bx bx-cog text-lg mr-3 {{ request()->is('admin/settings') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}'></i> System Settings
                        </a>
                    @endif

                    @if(Auth::user()->name !== 'Registrar') 

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

                @endif
            </div>
        </div>

        {{-- ⚡ DYNAMIC PROFILE INFO LOGIC ⚡ --}}
        @php
            $displayRole = ucfirst(Auth::user()->role);
            $displayName = Auth::user()->name;

            if (Auth::user()->role === 'student') {
                $loggedInStudent = Auth::user()->student; 
                
                if ($loggedInStudent) {
                    $stDetails = \App\Models\EnrollmentDetail::where('lrn', $loggedInStudent->lrn)
                                ->orWhere('email', $loggedInStudent->email_address)
                                ->latest()->first();
                    $stAppFallback = \App\Models\Applicant::where('lrn', $loggedInStudent->lrn)->first();
                    
                    $rawExt = $stDetails->extension_name ?? ($stAppFallback->extension_name ?? '');
                    $extName = (in_array(strtoupper(trim($rawExt)), ['N/A', 'NONE', ''])) ? '' : trim($rawExt);
                    
                    $displayName = trim($loggedInStudent->first_name . ' ' . $loggedInStudent->middle_name . ' ' . $loggedInStudent->last_name . ' ' . $extName);
                }
            }
        @endphp

        <div class="p-5 border-t border-white/60 bg-white/40 shrink-0 backdrop-blur-md mt-auto relative shadow-[0_-4px_24px_rgba(0,0,0,0.02)]">
            <div class="flex items-center mb-4 justify-between">
                <div class="flex items-center min-w-0">
                    <div class="flex-shrink-0 relative">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-black text-sm shadow-md ring-2 ring-white">
                            {{ substr($displayName, 0, 1) }}
                        </div>
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-400 border-2 border-white rounded-full"></div>
                    </div>
                    <div class="ml-3 min-w-0">
                        <p class="text-[13px] font-black text-slate-800 truncate" title="{{ $displayName }}">{{ $displayName }}</p>
                        <p class="text-[10px] font-bold tracking-widest uppercase text-indigo-500 truncate">{{ $displayRole }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('profile.edit') }}" wire:navigate class="flex items-center justify-center px-3 py-2 border border-slate-200 shadow-sm text-xs font-bold rounded-xl text-slate-700 bg-white/80 hover:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all hover:shadow-md hover:-translate-y-0.5">
                    <i class='bx bx-user mr-1.5 text-sm text-slate-400'></i> Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-3 py-2 border border-transparent shadow-sm text-xs font-bold rounded-xl text-white bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-all hover:shadow-md hover:-translate-y-0.5">
                        <i class='bx bx-log-out mr-1.5 text-sm'></i> Sign Out
                    </button>
                </form>
            </div>
        </div>
    </nav>
</div>

{{-- ⚡ SIDEBAR SCROLL RESTORATION (wire:navigate compatible) ⚡ --}}
<script data-navigate-once>
    (function() {
        const STORAGE_KEY = 'sidebarScroll';

        function restoreSidebarScroll() {
            requestAnimationFrame(() => {
                const sidebar = document.getElementById('sidebar-menu');
                if (sidebar) {
                    const saved = parseInt(sessionStorage.getItem(STORAGE_KEY) || '0', 10);
                    sidebar.scrollTop = saved;
                    // Double-ensure after a tiny delay (browser may reset during paint)
                    setTimeout(() => { sidebar.scrollTop = saved; }, 50);
                }
            });
        }

        function saveSidebarScroll(e) {
            if (e.target && e.target.id === 'sidebar-menu') {
                sessionStorage.setItem(STORAGE_KEY, e.target.scrollTop);
            }
        }

        // 1. Save scroll position continuously
        document.addEventListener('scroll', saveSidebarScroll, true);

        // 2. Restore on initial page load
        restoreSidebarScroll();

        // 3. Restore after every wire:navigate page swap (Livewire v3 SPA)
        document.addEventListener('livewire:navigated', restoreSidebarScroll);

        // 4. Also intercept Livewire morphdom updates (for non-navigate re-renders)
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('morph.updated', ({ el }) => {
                if (el.id === 'sidebar-menu') {
                    restoreSidebarScroll();
                }
            });
        });
    })();
</script>