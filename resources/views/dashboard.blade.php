<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <style>
        /* ============================================ */
        /* 🇵🇭 3D ISOMETRIC PHILIPPINES MAP             */
        /* ============================================ */
        .iso-map-container {
            position: relative;
            width: 100%;
            height: 450px;
            display: flex;
            align-items: center;
            justify-content: center;
            perspective: 1000px;
            background: transparent;
            border-radius: 1.5rem;
            overflow: hidden;
            /* REMOVED BORDER TO MAKE IT FLOAT */
        }
        .iso-map-wrapper {
            position: relative;
            width: 250px;
            height: 350px;
            /* Flat 2D Map */
            transform: scale(1.15) translateY(10px);
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .iso-map-part {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), filter 0.4s ease;
            cursor: pointer;
            filter: drop-shadow(0px 8px 12px rgba(0,0,0,0.15));
        }
        .iso-map-part:hover, .iso-map-part.active {
            transform: translateY(-8px) scale(1.03);
            filter: drop-shadow(0px 15px 20px rgba(0,0,0,0.25));
            z-index: 10;
        }

        /* Island Groups Colors (Flag Theme - Premium Hues) */
        
        /* LUZON */
        .part-luzon { 
            clip-path: polygon(0 0, 100% 0, 100% 41.5%, 0 41.5%); 
            color: #2563eb; /* Tailored Royal Blue */
            fill: currentColor;
            stroke: rgba(255,255,255,0.8);
            stroke-width: 0.7px;
        }
        .part-luzon:hover { 
            color: #3b82f6; 
            filter: drop-shadow(0px 10px 25px rgba(37, 99, 235, 0.4));
        }

        /* VISAYAS */
        .part-visayas { 
            clip-path: polygon(0 41.5%, 100% 41.5%, 100% 59.5%, 0 59.5%); 
            color: #eab308; /* Tailored Amber/Gold */
            fill: currentColor;
            stroke: rgba(255,255,255,0.8);
            stroke-width: 0.7px;
        }
        .part-visayas:hover { 
            color: #facc15; 
            filter: drop-shadow(0px 10px 25px rgba(234, 179, 8, 0.4));
        }

        /* MINDANAO */
        .part-mindanao { 
            clip-path: polygon(0 59.5%, 100% 59.5%, 100% 100%, 0 100%); 
            color: #dc2626; /* Tailored Crimson Red */
            fill: currentColor;
            stroke: rgba(255,255,255,0.8);
            stroke-width: 0.7px;
        }
        .part-mindanao:hover { 
            color: #ef4444; 
            filter: drop-shadow(0px 10px 25px rgba(220, 38, 38, 0.4));
        }

        .floating-info {
            position: absolute;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid #e2e8f0;
            padding: 12px 16px;
            border-radius: 12px;
            color: #1e293b;
            pointer-events: none;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s, transform 0.3s;
            z-index: 9999; /* Increased to ensure it floats above EVERYTHING */
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            min-width: 140px;
        }
        .floating-info.show { opacity: 1; transform: translateY(0); }
        .ph-sun-float { animation: sun-float 5s ease-in-out infinite; }
        @keyframes sun-float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            25% { transform: translateY(-6px) rotate(5deg); }
            75% { transform: translateY(3px) rotate(-3deg); }
        }
        .flag-stripe-bar { background: linear-gradient(to right, #0038A8 50%, #CE1126 50%); }
    </style>

    <div class="py-12 min-h-screen bg-transparent text-slate-800">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(auth()->user()->role === 'admin')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                    <!-- Total Students Card -->
                    <div x-data="{ 
                            hover: false, 
                            total: {{ $totalStudents }},
                            male: {{ $maleCount }},
                            female: {{ $femaleCount }},
                            get malePercent() { return this.total > 0 ? (this.male / this.total * 100).toFixed(0) : 0 },
                            get femalePercent() { return this.total > 0 ? (this.female / this.total * 100).toFixed(0) : 0 }
                         }" 
                         @mouseenter="hover = true" 
                         @mouseleave="hover = false"
                         class="bg-white/70 backdrop-blur-xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 sm:rounded-[2rem] p-7 relative transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] hover:-translate-y-1">
                        
                        <div class="flex items-center justify-between" x-show="!hover" x-transition:enter="transition ease-out duration-300">
                            <div>
                                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Total Students</p>
                                <h3 class="text-4xl font-black tracking-tight text-slate-800 count-up" data-target="{{ $totalStudents }}">0</h3>
                            </div>
                            <div class="bg-gradient-to-br from-indigo-100 to-indigo-50/50 p-4 rounded-2xl text-indigo-600 shadow-sm border border-indigo-100/50">
                                <i class='bx bxs-user-detail text-3xl'></i>
                            </div>
                        </div>

                        <div class="absolute inset-0 p-7 flex flex-col justify-center bg-white/95 backdrop-blur-3xl" x-show="hover" x-transition:enter="transition ease-out duration-300">
                            <div class="flex justify-between items-end mb-3">
                                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Gender Distribution</p>
                                <span class="text-[10px] font-black text-indigo-600 tracking-wider" x-text="total + ' TOTAL'"></span>
                            </div>
                            <div class="flex h-3.5 w-full rounded-full overflow-hidden bg-slate-100 shadow-inner">
                                <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-full transition-all duration-1000 ease-out" :style="'width: ' + malePercent + '%'"></div>
                                <div class="bg-gradient-to-r from-rose-400 to-pink-500 h-full transition-all duration-1000 ease-out" :style="'width: ' + femalePercent + '%'"></div>
                            </div>
                            <div class="flex justify-between mt-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-indigo-500"></div>
                                    <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wider" x-text="'M: ' + male + ' (' + malePercent + '%)'"></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-pink-500"></div>
                                    <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wider" x-text="'F: ' + female + ' (' + femalePercent + '%)'"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Sections -->
                    <div class="bg-white/70 backdrop-blur-xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 sm:rounded-[2rem] p-7 transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div><p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Active Sections</p><h3 class="text-4xl font-black text-slate-800 tracking-tight count-up" data-target="{{ $activeSections }}">0</h3></div>
                            <div class="bg-gradient-to-br from-emerald-100 to-emerald-50/50 p-4 rounded-2xl text-emerald-600 shadow-sm border border-emerald-100/50"><i class='bx bxs-objects-horizontal-left text-3xl'></i></div>
                        </div>
                    </div>

                    <!-- Sports Card -->
                    <div class="bg-white/70 backdrop-blur-xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 sm:rounded-[2rem] p-7 transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div><p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Sports</p><h3 class="text-4xl font-black text-slate-800 tracking-tight count-up" data-target="{{ $totalTeams }}">0</h3></div>
                            <div class="bg-gradient-to-br from-amber-100 to-amber-50/50 p-4 rounded-2xl text-amber-600 shadow-sm border border-amber-100/50"><i class='bx bxs-medal text-3xl'></i></div>
                        </div>
                    </div>

                    <!-- Upcoming Plans -->
                    <div class="bg-white/70 backdrop-blur-xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 sm:rounded-[2rem] p-7 transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div><p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Upcoming Plans</p><h3 class="text-4xl font-black text-slate-800 tracking-tight count-up" data-target="{{ $upcomingPlans }}">0</h3></div>
                            <div class="bg-gradient-to-br from-rose-100 to-rose-50/50 p-4 rounded-2xl text-rose-600 shadow-sm border border-rose-100/50"><i class='bx bxs-calendar-event text-3xl'></i></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Activity Stream (Expanded on Left) -->
                    <div class="md:col-span-3 bg-white/70 backdrop-blur-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 sm:rounded-[2rem] flex flex-col h-[480px] overflow-hidden relative">
                        <!-- Decorative top glow -->
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 opacity-80"></div>
                        
                        <div class="px-8 py-6 border-b border-gray-100/50 bg-white/40 flex justify-between items-center">
                            <div>
                                <h3 class="text-[13px] font-black text-slate-800 uppercase tracking-widest flex items-center"><i class='bx bx-history mr-2.5 text-indigo-500 text-lg'></i> Activity Stream</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1">Real-time system updates</p>
                            </div>
                            <span class="flex items-center gap-2 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-100">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Live</span>
                            </span>
                        </div>
                        <div class="p-8 flex-grow overflow-y-auto custom-scroll relative">
                            <div id="activity-list" class="space-y-7 relative">
                                <div class="absolute left-[11px] top-2 bottom-2 w-px bg-slate-200"></div>
                                @foreach($activities as $activity)
                                    @php $color = match($activity->action) { 'Updated Grades' => 'bg-indigo-500', 'Checked Attendance' => 'bg-emerald-500', 'Login' => 'bg-blue-400', default => 'bg-slate-400' }; @endphp
                                    <div class="flex gap-x-5 relative z-10">
                                        <div class="flex-none w-[22px] flex justify-center mt-1"><div class="w-4 h-4 rounded-full border-[3px] border-white {{ $color }} shadow-sm"></div></div>
                                        <div class="flex-grow bg-white/60 p-4 rounded-2xl border border-white shadow-sm hover:shadow-md transition-shadow">
                                            <div class="text-[13px] text-slate-700 leading-relaxed"><span class="font-bold text-slate-900">{{ $activity->user->name ?? 'System' }}</span> {{ strtolower($activity->action) }}.</div>
                                            <p class="text-[10px] text-slate-400 mt-1.5 uppercase font-bold tracking-wider"><i class='bx bx-time-five mr-1'></i>{{ $activity->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- 🇵🇭 2D PHILIPPINES MAP (Wrapped in Card) -->
                    <div class="md:col-span-1 bg-white/70 backdrop-blur-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/60 sm:rounded-[2rem] flex flex-col h-[480px] relative z-50 overflow-hidden">
                        <!-- Decorative top glow -->
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-amber-400 to-rose-500 opacity-80"></div>
                        
                        <div class="px-7 py-6 border-b border-gray-100/50 bg-white/40 flex justify-between items-center">
                            <div>
                                <h3 class="text-[13px] font-black text-slate-800 uppercase tracking-widest flex items-center"><i class='bx bx-map-alt mr-2.5 text-rose-500 text-lg'></i> Map</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1">Demographics</p>
                            </div>
                        </div>
                        <div class="flex-grow flex items-center justify-center p-4 relative bg-slate-50/30">
                            <!-- Flat 2D Map Container -->
                            <div class="iso-map-container !h-[320px] !w-[220px]" id="map-container" style="background: transparent; border: none; overflow: visible;">
                                <div class="iso-map-wrapper !h-full !w-full" style="transform: scale(0.95) translateY(5px);">
                                    <!-- Luzon -->
                                    <div class="iso-map-part part-luzon" onmousemove="moveTooltip(event, 'LUZON', {{ $islandCounts['Luzon']['total'] }}, {{ $islandCounts['Luzon']['male'] }}, {{ $islandCounts['Luzon']['female'] }})" onmouseleave="hideTooltip()">
                                        <x-phmap_detailed class="w-full h-full drop-shadow-sm" />
                                    </div>
                                    <!-- Visayas -->
                                    <div class="iso-map-part part-visayas" onmousemove="moveTooltip(event, 'VISAYAS', {{ $islandCounts['Visayas']['total'] }}, {{ $islandCounts['Visayas']['male'] }}, {{ $islandCounts['Visayas']['female'] }})" onmouseleave="hideTooltip()">
                                        <x-phmap_detailed class="w-full h-full drop-shadow-sm" />
                                    </div>
                                    <!-- Mindanao -->
                                    <div class="iso-map-part part-mindanao" onmousemove="moveTooltip(event, 'MINDANAO', {{ $islandCounts['Mindanao']['total'] }}, {{ $islandCounts['Mindanao']['male'] }}, {{ $islandCounts['Mindanao']['female'] }})" onmouseleave="hideTooltip()">
                                        <x-phmap_detailed class="w-full h-full drop-shadow-sm" />
                                    </div>
                                </div>
                                
                                <!-- Floating Tooltip -->
                                <div id="map-tooltip" class="floating-info flex flex-col items-center !min-w-[130px] !p-3">
                                    <span id="tt-island" class="text-[11px] font-black uppercase tracking-widest text-slate-500"></span>
                                    <span class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest"><span id="tt-count" class="text-indigo-600 text-lg font-black leading-none"></span> Scholars</span>
                                    <div class="w-full border-t border-slate-100 my-2"></div>
                                    <div class="flex justify-between w-full text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-blue-500 shadow-sm border border-blue-600"></span> M <span id="tt-male" class="text-slate-700"></span></span>
                                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-rose-400 shadow-sm border border-rose-500"></span> F <span id="tt-female" class="text-slate-700"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function initDashboard() {
            animateCounters();
        }

        const tooltip = document.getElementById('map-tooltip');
        const ttIsland = document.getElementById('tt-island');
        const ttCount = document.getElementById('tt-count');
        const ttMale = document.getElementById('tt-male');
        const ttFemale = document.getElementById('tt-female');

        function showTooltip(island, count, maleCount = 0, femaleCount = 0) {
            ttIsland.textContent = island;
            ttCount.textContent = count;
            ttMale.textContent = maleCount;
            ttFemale.textContent = femaleCount;
            tooltip.classList.add('show');
        }

        function hideTooltip() {
            tooltip.classList.remove('show');
        }

        function moveTooltip(e, island, count, mCount, fCount) {
            const container = document.getElementById('map-container');
            if(!container) return;
            const rect = container.getBoundingClientRect();
            
            showTooltip(island, count, mCount, fCount);

            // Get tooltip dimensions
            const ttRect = tooltip.getBoundingClientRect();
            const ttWidth = ttRect.width || 140;
            const ttHeight = ttRect.height || 80;

            let x = e.clientX - rect.left;
            let y = e.clientY - rect.top;

            // If cursor is on the right half of the screen, place tooltip on the LEFT of the cursor
            if (e.clientX > window.innerWidth / 2) {
                x = x - ttWidth - 20; 
            } else {
                x = x + 20; // Default: right of cursor
            }

            // Vertical bounds
            if (e.clientY + ttHeight > window.innerHeight) {
                y = y - ttHeight - 10;
            } else {
                y = y + 20;
            }

            tooltip.style.left = x + 'px';
            tooltip.style.top = y + 'px';
        }

        function animateCounters() {
            document.querySelectorAll('.count-up').forEach(c => {
                const target = +c.getAttribute('data-target'), duration = 1500, start = performance.now();
                if (target === 0) return;
                function u(now) {
                    const progress = Math.min((now - start) / duration, 1), ease = 1 - Math.pow(1 - progress, 3);
                    c.innerText = Math.floor(ease * target).toLocaleString();
                    if (progress < 1) requestAnimationFrame(u);
                }
                requestAnimationFrame(u);
            });
        }

        document.addEventListener('DOMContentLoaded', initDashboard);
        document.addEventListener('livewire:navigated', () => setTimeout(initDashboard, 100));
    </script>
</x-app-layout>