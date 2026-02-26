<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #student-map {
            height: 580px;
            width: 100%;
            border-radius: 2rem;
            z-index: 1;
            background: #0f172a !important; 
            box-shadow: inset 0 0 50px rgba(0,0,0,0.5);
        }
        
        /* 🛰️ ELITE SATELLITE PULSE (Cyan Neon) */
        .custom-div-icon { background: none; border: none; }
        .marker-neon { width: 40px; height: 40px; position: relative; }
        .pulse-ring {
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 12px; height: 12px;
            background: rgba(34, 211, 238, 0.4);
            border: 1.5px solid #22d3ee; border-radius: 50%;
            animation: pulse-ring 2.5s infinite;
        }
        @keyframes pulse-ring {
            0% { width: 8px; height: 8px; opacity: 1; }
            100% { width: 45px; height: 45px; opacity: 0; }
        }
        .marker-inner-elite {
            width: 36px; height: 36px; border-radius: 50%;
            background: #1e293b; border: 2.5px solid #22d3ee;
            box-shadow: 0 0 15px rgba(34, 211, 238, 0.5);
            display: flex; align-items: center; justify-content: center; overflow: hidden;
            position: relative; z-index: 2;
        }
        .marker-img-elite { width: 100%; height: 100%; object-fit: cover; }
        .marker-label-elite {
            position: absolute; bottom: -22px; left: 50%; transform: translateX(-50%);
            background: rgba(15, 23, 42, 0.9); backdrop-blur: 4px;
            color: #fff; padding: 1px 8px; border-radius: 6px;
            font-size: 8px; font-weight: 800; text-transform: uppercase; white-space: nowrap;
            border: 1px solid rgba(255,255,255,0.1); opacity: 0; transition: 0.3s;
        }
        .custom-div-icon:hover .marker-label-elite { opacity: 1; bottom: -28px; }

        .glow-favicon {
            filter: drop-shadow(0 0 10px rgba(34, 211, 238, 0.6));
            animation: float-favicon 4s ease-in-out infinite;
        }
        @keyframes float-favicon {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
    </style>

    <div class="py-12 min-h-screen bg-transparent text-slate-800">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(auth()->user()->role === 'admin')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
                         class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-sm sm:rounded-2xl p-6 border-l-4 border-indigo-500 relative transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                        
                        <div class="flex items-center justify-between" x-show="!hover" x-transition:enter="transition ease-out duration-300">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Students</p>
                                <h3 class="text-3xl font-black tracking-tighter count-up" data-target="{{ $totalStudents }}">0</h3>
                            </div>
                            <div class="bg-indigo-50 p-3 rounded-2xl text-indigo-600">
                                <i class='bx bxs-user-detail text-3xl'></i>
                            </div>
                        </div>

                        <div class="absolute inset-0 p-6 flex flex-col justify-center bg-white/95" x-show="hover" x-transition:enter="transition ease-out duration-300">
                            <div class="flex justify-between items-end mb-2">
                                <p class="text-[10px] font-black text-slate-500 uppercase">Gender Distribution</p>
                                <span class="text-[10px] font-black text-indigo-600" x-text="total + ' TOTAL'"></span>
                            </div>
                            <div class="flex h-3.5 w-full rounded-full overflow-hidden bg-slate-100 shadow-inner">
                                <div class="bg-blue-500 h-full transition-all duration-1000 ease-out" :style="'width: ' + malePercent + '%'"></div>
                                <div class="bg-rose-400 h-full transition-all duration-1000 ease-out" :style="'width: ' + femalePercent + '%'"></div>
                            </div>
                            <div class="flex justify-between mt-3">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                    <span class="text-[10px] font-black text-slate-600 uppercase" x-text="'M: ' + male + ' (' + malePercent + '%)'"></span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2 h-2 rounded-full bg-rose-400"></div>
                                    <span class="text-[10px] font-black text-slate-600 uppercase" x-text="'F: ' + female + ' (' + femalePercent + '%)'"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Sections -->
                    <div class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-sm sm:rounded-2xl p-6 border-l-4 border-emerald-500 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Active Sections</p><h3 class="text-3xl font-black tracking-tighter count-up" data-target="{{ $activeSections }}">0</h3></div>
                            <div class="bg-emerald-50 p-3 rounded-2xl text-emerald-600"><i class='bx bxs-objects-horizontal-left text-3xl'></i></div>
                        </div>
                    </div>

                    <!-- Sports Card -->
                    <div class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-sm sm:rounded-2xl p-6 border-l-4 border-amber-500 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Sports</p><h3 class="text-3xl font-black tracking-tighter count-up" data-target="{{ $totalTeams }}">0</h3></div>
                            <div class="bg-amber-50 p-3 rounded-2xl text-amber-600"><i class='bx bxs-medal text-3xl'></i></div>
                        </div>
                    </div>

                    <!-- Upcoming Plans -->
                    <div class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-sm sm:rounded-2xl p-6 border-l-4 border-rose-500 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Upcoming Plans</p><h3 class="text-3xl font-black tracking-tighter count-up" data-target="{{ $upcomingPlans }}">0</h3></div>
                            <div class="bg-rose-50 p-3 rounded-2xl text-rose-600"><i class='bx bxs-calendar-event text-3xl'></i></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Activity Stream -->
                    <div class="md:col-span-1 bg-white/90 backdrop-blur-sm overflow-hidden shadow-md sm:rounded-2xl flex flex-col h-full border border-gray-200">
                        <div class="p-5 border-b border-gray-100 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest flex items-center"><i class='bx bx-history mr-2 text-indigo-500'></i> Activity Stream</h3>
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        </div>
                        <div class="p-6 flex-grow overflow-y-auto max-h-[500px] custom-scroll relative">
                            <div id="activity-list" class="space-y-6 relative">
                                <div class="absolute left-[9px] top-2 bottom-2 w-px bg-slate-200"></div>
                                @foreach($activities as $activity)
                                    @php $color = match($activity->action) { 'Updated Grades' => 'bg-indigo-500', 'Checked Attendance' => 'bg-emerald-500', 'Login' => 'bg-blue-400', default => 'bg-slate-400' }; @endphp
                                    <div class="flex gap-x-4 relative z-10 mb-6 last:mb-0">
                                        <div class="flex-none w-[18px] flex justify-center mt-1.5"><div class="w-3.5 h-3.5 rounded-full border-2 border-white {{ $color }} shadow-sm"></div></div>
                                        <div class="flex-grow">
                                            <div class="text-[13px] text-slate-700 leading-relaxed font-medium"><span class="font-bold text-slate-900">{{ $activity->user->name ?? 'System' }}</span> {{ strtolower($activity->action) }}.</div>
                                            <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold tracking-wider">{{ $activity->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- 🗺️ SATELLITE NATIONAL SCHOLAR LOCATOR -->
                    <div class="md:col-span-2 bg-slate-900 overflow-hidden shadow-2xl sm:rounded-3xl flex flex-col h-full border border-slate-700 relative">
                        <div class="p-6 border-b border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-900/80 text-white">
                            <div><h3 class="text-sm font-black uppercase tracking-widest flex items-center"><i class='bx bxs-map-pin mr-2 text-cyan-400'></i> National Scholar Locator</h3><p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">HD Satellite Surveillance View</p></div>
                            <div class="flex flex-wrap items-center gap-2">
                                <button onclick="focusIsland('Luzon')" class="px-3 py-1.5 bg-cyan-500/10 text-cyan-400 text-[10px] font-black rounded-xl border border-cyan-500/20 hover:bg-cyan-500/20 transition">LUZON: {{ $islandCounts['Luzon'] }}</button>
                                <button onclick="focusIsland('Visayas')" class="px-3 py-1.5 bg-amber-50 text-amber-600 text-[10px] font-black rounded-xl border border-amber-100/20 hover:bg-amber-100/20 transition">VISAYAS: {{ $islandCounts['Visayas'] }}</button>
                                <button onclick="focusIsland('Mindanao')" class="px-3 py-1.5 bg-rose-50 text-rose-600 text-[10px] font-black rounded-xl border border-rose-100/20 hover:bg-rose-100/20 transition">MINDANAO: {{ $islandCounts['Mindanao'] }}</button>
                            </div>
                        </div>
                        
                        <div class="p-4 flex-grow relative min-h-[500px] bg-slate-900">
                            <!-- Floating Favicon -->
                            <div class="absolute top-8 right-8 z-[1000] glow-favicon"><img src="{{ asset('images/nas/favicon1.png') }}" class="w-14 h-14 object-contain" alt="NAS Logo"></div>
                            <div id="student-map"></div>
                            
                            <!-- Legend -->
                            <div class="absolute bottom-8 left-8 z-[1000] bg-slate-900/95 backdrop-blur-xl p-4 rounded-2xl shadow-2xl border border-slate-700 w-40 text-center">
                                <h4 class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-3 border-b border-slate-800 pb-2">Tracking Legend</h4>
                                <div class="flex items-center justify-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-cyan-400 shadow-[0_0_10px_#22d3ee]"></div>
                                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-wide">Scholar Pin</span>
                                </div>
                            </div>

                            <div class="absolute top-8 left-20 z-[1000]">
                                <div class="bg-cyan-600 px-4 py-2 rounded-xl shadow-lg border border-cyan-400/20 flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                    <span class="text-[10px] font-black text-white uppercase tracking-wider">{{ count($mapMarkers) }} {{ count($mapMarkers) === 1 ? 'SCHOLAR' : 'SCHOLARS' }} LOCATED</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        let map = null, geoLayer = null;
        const markersData = @json($mapMarkers);

        function initDashboard() {
            animateCounters();
            initMap();
        }

        function initMap() {
            const phBounds = L.latLngBounds(L.latLng(4.0, 116.0), L.latLng(22.0, 127.0));
            map = L.map('student-map', { center: [12.8797, 121.7740], zoom: 6, minZoom: 5, maxZoom: 18, maxBounds: phBounds, maxBoundsViscosity: 1.0, zoomControl: true, scrollWheelZoom: true });

            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '&copy; Esri' }).addTo(map);

            fetch('https://raw.githubusercontent.com/macandv/philippines-geojson/master/philippines-regions.json')
                .then(r => r.json()).then(data => {
                    const worldCoords = [[[-90, -200], [-90, 200], [90, 200], [90, -200], [-90, -200]]];
                    data.features.forEach(feature => {
                        const coords = L.GeoJSON.coordsToLatLngs(feature.geometry.coordinates, feature.geometry.type === 'MultiPolygon' ? 1 : 0);
                        if (feature.geometry.type === 'Polygon') worldCoords.push(coords); else coords.forEach(c => worldCoords.push(c));
                    });
                    L.polygon(worldCoords, { color: 'none', fillColor: '#0f172a', fillOpacity: 1, interactive: false }).addTo(map);
                    geoLayer = L.geoJSON(data, {
                        style: { color: 'rgba(34, 211, 238, 0.2)', weight: 1.5, fillOpacity: 0 },
                        onEachFeature: (feature, layer) => {
                            const reg = feature.properties.REGION || '';
                            if (['NCR','CAR','1','2','3','4A','4B','5'].some(id => reg.includes(id))) feature.properties.island = 'Luzon';
                            else if (['6','7','8'].some(id => reg.includes(id))) feature.properties.island = 'Visayas'; else feature.properties.island = 'Mindanao';
                        }
                    }).addTo(map);
                });

            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}{r}.png', { subdomains: 'abcd', opacity: 0.8, pointerEvents: 'none' }).addTo(map);

            markersData.forEach(s => {
                const icon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div class="marker-neon"><div class="pulse-ring"></div><div class="marker-inner-elite"><img src="${s.photo}" class="marker-img-elite" onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(s.name)}&background=4f46e5&color=fff&bold=true'"></div><div class="marker-label-elite">${s.name}</div></div>`,
                    iconSize: [40, 40], iconAnchor: [20, 20]
                });
                L.marker(s.coords, { icon: icon }).addTo(map)
                    .on('click', function(e) { map.flyTo(e.latlng, 16, { duration: 1.5 }); })
                    .bindPopup(`<div class="p-4 text-center bg-slate-900 text-white rounded-2xl border border-slate-700"><div class="w-20 h-20 rounded-full mx-auto mb-3 border-4 border-cyan-500 shadow-xl overflow-hidden"><img src="${s.photo}" class="w-full h-full object-cover"></div><h5 class="text-sm font-black uppercase tracking-wide">${s.name}</h5><p class="text-[10px] font-bold text-cyan-400 uppercase tracking-widest mb-3">${s.grade}</p><div class="bg-slate-800 px-3 py-2 rounded-xl text-[9px] font-bold text-slate-400 uppercase"><i class='bx bxs-map text-rose-500 mr-1'></i> ${s.location}</div></div>`, { closeButton: false, offset: [0, -5] });
            });
        }

        function focusIsland(island) {
            if (!map) return;
            const colors = { 'Luzon': '#22d3ee', 'Visayas': '#fbbf24', 'Mindanao': '#f43f5e' };
            map.flyTo({ 'Luzon': [16.0, 121.0], 'Visayas': [11.0, 123.0], 'Mindanao': [7.8, 125.0] }[island], island === 'Luzon' ? 7 : 8);
            if (geoLayer) {
                geoLayer.eachLayer(layer => {
                    if (layer.feature.properties.island === island) layer.setStyle({ color: colors[island], fillOpacity: 0.1, weight: 2.5 });
                    else layer.setStyle({ color: 'rgba(34, 211, 238, 0.2)', fillOpacity: 0, weight: 1.5 });
                });
            }
        }

        function animateCounters() {
            document.querySelectorAll('.count-up').forEach(c => {
                const target = +c.getAttribute('data-target'), duration = 1500, start = performance.now();
                function u(now) {
                    const progress = Math.min((now - start) / duration, 1), ease = 1 - Math.pow(1 - progress, 3);
                    c.innerText = Math.floor(ease * target).toLocaleString();
                    if (progress < 1) requestAnimationFrame(u);
                }
                requestAnimationFrame(u);
            });
        }

        document.addEventListener('DOMContentLoaded', initDashboard);
        document.addEventListener('livewire:navigated', initDashboard);
    </script>
</x-app-layout>