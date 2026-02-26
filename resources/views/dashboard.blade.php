<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- MapLibre GL JS — True 3D Terrain Engine -->
    <link rel="stylesheet" href="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.css" />
    <style>
        /* ============================================ */
        /* 🇵🇭 TRUE 3D PHILIPPINES MAP                  */
        /* ============================================ */
        #student-map {
            height: 400px;
            width: 100%;
            border-radius: 1.25rem;
            overflow: hidden;
        }

        /* Scholar Avatar Markers */
        .scholar-marker {
            width: 48px; height: 48px;
            cursor: pointer;
            position: relative;
        }
        .scholar-marker .pulse {
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 48px; height: 48px;
            border-radius: 50%;
            background: rgba(252, 209, 22, 0.2);
            border: 2px solid rgba(252, 209, 22, 0.5);
            animation: marker-pulse 2.5s ease-out infinite;
        }
        @keyframes marker-pulse {
            0% { transform: translate(-50%, -50%) scale(0.5); opacity: 1; }
            100% { transform: translate(-50%, -50%) scale(2); opacity: 0; }
        }
        .scholar-marker .avatar {
            width: 44px; height: 44px;
            border-radius: 50%;
            border: 3px solid #FCD116;
            box-shadow: 0 0 20px rgba(252, 209, 22, 0.5), 0 4px 15px rgba(0,0,0,0.4);
            overflow: hidden;
            position: relative; z-index: 2;
            background: linear-gradient(135deg, #0038A8, #CE1126);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .scholar-marker:hover .avatar {
            transform: scale(1.25);
            box-shadow: 0 0 35px rgba(252, 209, 22, 0.9), 0 8px 25px rgba(0,0,0,0.5);
        }
        .scholar-marker .avatar img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .scholar-marker .name-tag {
            position: absolute; bottom: -22px; left: 50%; transform: translateX(-50%);
            background: linear-gradient(135deg, #0038A8, #002060);
            color: #FCD116; padding: 2px 10px; border-radius: 8px;
            font-size: 8px; font-weight: 800; text-transform: uppercase; white-space: nowrap;
            border: 1px solid rgba(252, 209, 22, 0.3);
            opacity: 0; transition: all 0.3s;
            pointer-events: none;
        }
        .scholar-marker:hover .name-tag { opacity: 1; bottom: -28px; }

        /* MapLibre Popup Override */
        .maplibregl-popup-content {
            background: linear-gradient(145deg, #001a4d, #002b80) !important;
            border: 2px solid #FCD116 !important;
            border-radius: 1.25rem !important;
            box-shadow: 0 15px 50px rgba(0,0,0,0.5), 0 0 25px rgba(252, 209, 22, 0.15) !important;
            color: white !important;
            padding: 20px !important;
            min-width: 200px;
            text-align: center;
        }
        .maplibregl-popup-tip { border-top-color: #002b80 !important; }
        .maplibregl-popup-close-button { color: #FCD116 !important; font-size: 18px; }

        /* Sun float */
        .ph-sun-float { animation: sun-float 5s ease-in-out infinite; }
        @keyframes sun-float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            25% { transform: translateY(-6px) rotate(5deg); }
            75% { transform: translateY(3px) rotate(-3deg); }
        }

        /* Flag bar */
        .flag-stripe-bar {
            background: linear-gradient(to right, #0038A8 50%, #CE1126 50%);
            position: relative; overflow: hidden;
        }
        .flag-stripe-bar::before {
            content: '';
            position: absolute; left: 0; top: 0; bottom: 0; width: 0;
            border-left: 30px solid white;
            border-top: 20px solid transparent;
            border-bottom: 20px solid transparent;
        }

        /* Navigation controls restyle */
        .maplibregl-ctrl-group { background: rgba(0, 26, 77, 0.9) !important; border: 1px solid rgba(252,209,22,0.3) !important; border-radius: 12px !important; }
        .maplibregl-ctrl-group button { color: #FCD116 !important; }
        .maplibregl-ctrl-group button + button { border-top: 1px solid rgba(252,209,22,0.15) !important; }
        .maplibregl-ctrl-group button:hover { background: rgba(252,209,22,0.1) !important; }
        .maplibregl-ctrl-attrib { background: rgba(0,26,77,0.7) !important; color: rgba(252,209,22,0.5) !important; font-size: 9px !important; border-radius: 8px !important; }
        .maplibregl-ctrl-attrib a { color: rgba(252,209,22,0.6) !important; }
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

                    <!-- 🇵🇭 TRUE 3D PHILIPPINES MAP -->
                    <div class="md:col-span-2 overflow-hidden shadow-2xl sm:rounded-3xl flex flex-col h-full relative" style="border: 2px solid #FCD116;">
                        
                        <div class="flag-stripe-bar h-1.5"></div>
                        
                        <!-- Header -->
                        <div class="p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 text-white" style="background: linear-gradient(135deg, #001a4d, #002b80);">
                            <div class="flex items-center gap-4">
                                <div class="ph-sun-float flex-shrink-0">
                                    <svg width="44" height="44" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="50" cy="50" r="18" fill="#FCD116"/>
                                        @for($i = 0; $i < 8; $i++)
                                            <line x1="50" y1="10" x2="50" y2="28" stroke="#FCD116" stroke-width="3" stroke-linecap="round" transform="rotate({{ $i * 45 }} 50 50)"/>
                                        @endfor
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-black uppercase tracking-widest" style="color: #FCD116;">
                                        <i class='bx bxs-map-pin mr-2' style="color: #CE1126;"></i> 3D National Scholar Map
                                    </h3>
                                    <p class="text-[9px] font-bold uppercase tracking-tighter" style="color: rgba(252,209,22,0.5);">National Academy of Sports — Real Terrain • Drag to Rotate • Scroll to Zoom</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <button onclick="focusIsland('Luzon')" class="px-4 py-2 text-[10px] font-black rounded-xl border transition-all hover:scale-105" style="background: rgba(0,56,168,0.3); color: #FCD116; border-color: rgba(252,209,22,0.3);">🔵 LUZON: {{ $islandCounts['Luzon'] }}</button>
                                <button onclick="focusIsland('Visayas')" class="px-4 py-2 text-[10px] font-black rounded-xl border transition-all hover:scale-105" style="background: rgba(252,209,22,0.1); color: #FCD116; border-color: rgba(252,209,22,0.3);">🌟 VISAYAS: {{ $islandCounts['Visayas'] }}</button>
                                <button onclick="focusIsland('Mindanao')" class="px-4 py-2 text-[10px] font-black rounded-xl border transition-all hover:scale-105" style="background: rgba(206,17,38,0.2); color: #FCD116; border-color: rgba(252,209,22,0.3);">🔴 MINDANAO: {{ $islandCounts['Mindanao'] }}</button>
                                <button onclick="resetMap()" class="px-4 py-2 text-[10px] font-black rounded-xl border transition-all hover:scale-105" style="background: rgba(252,209,22,0.15); color: #FCD116; border-color: #FCD116;"><i class='bx bx-reset mr-1'></i> RESET MAP</button>
                            </div>
                        </div>
                        
                        <!-- 3D Map -->
                        <div class="relative flex-grow min-h-[380px]" style="background: #001a4d;">
                            
                            <!-- NAS Logo -->
                            <div class="absolute top-4 right-4 z-[10] ph-sun-float">
                                <img src="{{ asset('images/nas/favicon1.png') }}" class="w-12 h-12 object-contain" alt="NAS" style="filter: drop-shadow(0 0 12px rgba(252,209,22,0.6));">
                            </div>

                            <!-- Scholar Count -->
                            <div class="absolute top-4 left-4 z-[10]">
                                <div class="px-4 py-2 rounded-xl shadow-lg flex items-center gap-2" style="background: linear-gradient(135deg, #CE1126, #a00d1e); border: 1px solid rgba(252,209,22,0.3);">
                                    <span class="w-2 h-2 rounded-full animate-pulse" style="background: #FCD116;"></span>
                                    <span class="text-[10px] font-black text-white uppercase tracking-wider">{{ count($mapMarkers) }} {{ count($mapMarkers) === 1 ? 'SCHOLAR' : 'SCHOLARS' }} LOCATED</span>
                                </div>
                            </div>

                            <!-- 3D Controls Hint -->
                            <div class="absolute bottom-4 left-4 z-[10] px-3 py-2 rounded-xl" style="background: rgba(0,26,77,0.85); border: 1px solid rgba(252,209,22,0.15);">
                                <div class="text-[9px] font-bold text-white/60 space-y-0.5">
                                    <div>🖱️ <span style="color: #FCD116;">Right-drag</span> = Rotate & Tilt</div>
                                    <div>🖱️ <span style="color: #FCD116;">Scroll</span> = Zoom in/out</div>
                                    <div>🖱️ <span style="color: #FCD116;">Left-drag</span> = Pan</div>
                                </div>
                            </div>

                            <!-- Lock/Unlock Button -->
                            <div class="absolute top-4 left-1/2 -translate-x-1/2 z-[10]">
                                <button id="map-lock-btn" onclick="toggleMapLock()" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all hover:scale-105 flex items-center gap-2" style="background: rgba(0,26,77,0.9); color: #FCD116; border: 1px solid #FCD116;">
                                    <i class='bx bxs-lock-alt' id="lock-icon"></i> <span id="lock-text">CLICK TO UNLOCK MAP</span>
                                </button>
                            </div>

                            <div id="student-map"></div>
                        </div>
                        
                        <div class="flag-stripe-bar h-1.5"></div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- MapLibre GL JS — 3D Terrain Engine -->
    <script src="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.js"></script>
    <script>
        let map = null;
        const markersData = @json($mapMarkers ?? []);
        const markerElements = [];

        function initDashboard() {
            animateCounters();
            initMap();
        }

        function initMap() {
            const container = document.getElementById('student-map');
            if (!container) return;

            // Destroy previous map instance
            if (map) { map.remove(); map = null; }
            markerElements.forEach(m => m.remove());
            markerElements.length = 0;

            // Create 3D Map with real terrain
            map = new maplibregl.Map({
                container: 'student-map',
                style: {
                    version: 8,
                    sources: {
                        'satellite': {
                            type: 'raster',
                            tiles: ['https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'],
                            tileSize: 256,
                            attribution: '&copy; Esri'
                        },
                        'terrain-source': {
                            type: 'raster-dem',
                            tiles: ['https://s3.amazonaws.com/elevation-tiles-prod/terrarium/{z}/{x}/{y}.png'],
                            tileSize: 256,
                            encoding: 'terrarium'
                        },
                        'labels-bold': {
                            type: 'raster',
                            tiles: [
                                'https://a.basemaps.cartocdn.com/dark_only_labels/{z}/{x}/{y}@2x.png',
                                'https://b.basemaps.cartocdn.com/dark_only_labels/{z}/{x}/{y}@2x.png',
                                'https://c.basemaps.cartocdn.com/dark_only_labels/{z}/{x}/{y}@2x.png'
                            ],
                            tileSize: 512,
                            minzoom: 0,
                            maxzoom: 18
                        },
                        'labels-extra': {
                            type: 'raster',
                            tiles: [
                                'https://a.basemaps.cartocdn.com/dark_only_labels/{z}/{x}/{y}@2x.png',
                                'https://b.basemaps.cartocdn.com/dark_only_labels/{z}/{x}/{y}@2x.png'
                            ],
                            tileSize: 512,
                            minzoom: 0,
                            maxzoom: 18
                        }
                    },
                    layers: [
                        {
                            id: 'satellite-layer',
                            type: 'raster',
                            source: 'satellite',
                            paint: { 'raster-brightness-min': 0.05, 'raster-brightness-max': 0.85, 'raster-contrast': 0.25, 'raster-saturation': 0.3 }
                        },
                        {
                            id: 'labels-layer-1',
                            type: 'raster',
                            source: 'labels-bold',
                            paint: { 'raster-opacity': 1.0 }
                        },
                        {
                            id: 'labels-layer-2',
                            type: 'raster',
                            source: 'labels-extra',
                            paint: { 'raster-opacity': 0.7 }
                        }
                    ],
                    terrain: {
                        source: 'terrain-source',
                        exaggeration: 3.0
                    },
                    sky: {
                        'sky-color': '#001a4d',
                        'sky-horizon-blend': 0.4,
                        'horizon-color': '#0038A8',
                        'horizon-fog-blend': 0.7,
                        'fog-color': '#001540',
                        'fog-ground-blend': 0.85
                    }
                },
                center: [121.774, 12.8797],
                zoom: 6,
                pitch: 45,
                bearing: -10,
                maxPitch: 85,
                minZoom: 5.5,
                maxZoom: 18,
                maxBounds: [[116, 4.5], [128, 21.5]],
                scrollZoom: false,
                dragPan: false,
                dragRotate: false,
                touchZoomRotate: false,
                doubleClickZoom: false
            });

            // Navigation controls
            map.addControl(new maplibregl.NavigationControl({ visualizePitch: true }), 'bottom-right');

            map.on('load', () => {
                // === MASK: Hide everything outside Philippines ===
                fetch('https://raw.githubusercontent.com/macandv/philippines-geojson/master/philippines-regions.json')
                    .then(r => r.json())
                    .then(phGeo => {
                        if (!map) return;

                        // Build an inverted polygon: a huge world rectangle with PH cut out
                        const worldOuter = [[-180, -90], [180, -90], [180, 90], [-180, 90], [-180, -90]];
                        const phHoles = [];

                        phGeo.features.forEach(f => {
                            const coords = f.geometry.coordinates;
                            if (f.geometry.type === 'Polygon') {
                                phHoles.push(coords[0]);
                            } else if (f.geometry.type === 'MultiPolygon') {
                                coords.forEach(poly => phHoles.push(poly[0]));
                            }
                        });

                        const maskCoords = [worldOuter, ...phHoles];

                        map.addSource('ph-mask', {
                            type: 'geojson',
                            data: {
                                type: 'Feature',
                                geometry: { type: 'Polygon', coordinates: maskCoords }
                            }
                        });

                        map.addLayer({
                            id: 'ph-mask-layer',
                            type: 'fill',
                            source: 'ph-mask',
                            paint: {
                                'fill-color': '#001030',
                                'fill-opacity': 0.92
                            }
                        });

                        // Add PH border outline (golden glow)
                        map.addSource('ph-border', {
                            type: 'geojson',
                            data: phGeo
                        });

                        map.addLayer({
                            id: 'ph-border-line',
                            type: 'line',
                            source: 'ph-border',
                            paint: {
                                'line-color': 'rgba(252, 209, 22, 0.4)',
                                'line-width': 1.5
                            }
                        });
                    })
                    .catch(e => console.log('GeoJSON mask fetch error:', e));

                // === SCHOLAR MARKERS ===
                markersData.forEach(s => {
                    const el = document.createElement('div');
                    el.className = 'scholar-marker';
                    el.innerHTML = `
                        <div class="pulse"></div>
                        <div class="avatar">
                            <img src="${s.photo}" onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(s.name)}&background=0038A8&color=FCD116&bold=true'" alt="${s.name}">
                        </div>
                        <div class="name-tag">${s.name}</div>
                    `;

                    const popup = new maplibregl.Popup({ offset: [0, -30], closeButton: true })
                        .setHTML(`
                            <div style="text-align:center;">
                                <div style="width:70px;height:70px;border-radius:50%;border:3px solid #FCD116;overflow:hidden;margin:0 auto 10px;box-shadow:0 0 20px rgba(252,209,22,0.3);">
                                    <img src="${s.photo}" style="width:100%;height:100%;object-fit:cover;" onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(s.name)}&background=0038A8&color=FCD116&bold=true'">
                                </div>
                                <h5 style="font-size:13px;font-weight:900;text-transform:uppercase;letter-spacing:1px;margin:0 0 4px;">${s.name}</h5>
                                <p style="font-size:10px;font-weight:700;color:#FCD116;text-transform:uppercase;letter-spacing:2px;margin:0 0 10px;">${s.grade}</p>
                                <div style="background:rgba(0,56,168,0.4);padding:6px 12px;border-radius:10px;font-size:9px;font-weight:700;text-transform:uppercase;color:rgba(255,255,255,0.8);">
                                    📍 ${s.location}
                                </div>
                            </div>
                        `);

                    const marker = new maplibregl.Marker({ element: el, anchor: 'center' })
                        .setLngLat([s.coords[1], s.coords[0]])
                        .setPopup(popup)
                        .addTo(map);

                    markerElements.push(marker);

                    el.addEventListener('click', () => {
                        map.flyTo({ center: [s.coords[1], s.coords[0]], zoom: 14, pitch: 70, bearing: Math.random() * 60 - 30, duration: 2000 });
                    });
                });
            });
        }

        function focusIsland(island) {
            if (!map) return;
            const targets = {
                'Luzon':    { center: [121.0, 16.0], zoom: 7, pitch: 55, bearing: -10 },
                'Visayas':  { center: [123.0, 11.0], zoom: 7.5, pitch: 60, bearing: 15 },
                'Mindanao': { center: [125.0, 7.8], zoom: 7.5, pitch: 50, bearing: -20 }
            };
            const t = targets[island];
            map.flyTo({ center: t.center, zoom: t.zoom, pitch: t.pitch, bearing: t.bearing, duration: 2500, essential: true });
        }

        function resetMap() {
            if (!map) return;
            map.flyTo({
                center: [121.774, 12.8797],
                zoom: 6,
                pitch: 45,
                bearing: -10,
                duration: 2000,
                essential: true
            });
        }

        let mapLocked = true;
        function toggleMapLock() {
            mapLocked = !mapLocked;
            const icon = document.getElementById('lock-icon');
            const text = document.getElementById('lock-text');
            const btn = document.getElementById('map-lock-btn');
            if (mapLocked) {
                map.scrollZoom.disable();
                map.dragPan.disable();
                map.dragRotate.disable();
                map.touchZoomRotate.disable();
                map.doubleClickZoom.disable();
                icon.className = 'bx bxs-lock-alt';
                text.textContent = 'CLICK TO UNLOCK MAP';
                btn.style.borderColor = '#FCD116';
                btn.style.background = 'rgba(0,26,77,0.9)';
            } else {
                map.scrollZoom.enable();
                map.dragPan.enable();
                map.dragRotate.enable();
                map.touchZoomRotate.enable();
                map.doubleClickZoom.enable();
                icon.className = 'bx bxs-lock-open-alt';
                text.textContent = 'MAP UNLOCKED';
                btn.style.borderColor = '#22c55e';
                btn.style.background = 'rgba(0,77,26,0.9)';
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
        document.addEventListener('livewire:navigated', () => setTimeout(initDashboard, 100));
    </script>
</x-app-layout>