<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Applicant; 
use App\Models\Section;
use App\Models\Team;
use App\Models\User;
use App\Models\Staff;
use App\Models\Schedule;
use App\Models\ActivityLog; 
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. REDIRECT LOGIC
        if ($user->role === 'student') return redirect()->route('student.dashboard');
        if ($user->role === 'applicant') return redirect()->route('applicant.dashboard');

        // 2. TEACHER LOGIC
        if ($user->role === 'teacher') {
            $staff = Staff::where('email', $user->email)->first();
            $advisorySection = null;
            $advisoryCount = 0;
            $mySchedules = collect([]);
            $staffError = null;

            if ($staff) {
                $advisorySection = Section::where('adviser_id', $staff->id)->first();
                if ($advisorySection) $advisoryCount = Student::where('section_id', $advisorySection->id)->count();
                $mySchedules = Schedule::with(['subject', 'section'])->where('staff_id', $staff->id)->orderBy('day')->orderBy('time_start')->get();
            } else {
                $staffError = "Staff profile not found.";
            }

            return view('dashboard', compact('advisorySection', 'advisoryCount', 'mySchedules', 'staffError'));
        }

        // 3. ADMIN LOGIC (Default View)
        $totalStudents = Student::count(); 
        $maleCount     = Student::whereIn('sex', ['MALE', 'Boy', 'M', 'Male'])->count();
        $femaleCount   = Student::whereIn('sex', ['FEMALE', 'Girl', 'F', 'Female'])->count();
        $totalApplicants = Applicant::where('status', 'Pending')->count();
        $activeSections = Section::count(); 
        $totalTeams = Team::count();       
        $upcomingPlans = 0;

        $sportsBreakdown = Team::select('sport', DB::raw('count(*) as count'))->groupBy('sport')->orderBy('count', 'desc')->take(3)->get();
        $activities = ActivityLog::with('user')->latest()->take(5)->get();

        // --- GEOCACHE: Load cached geocode results ---
        $geocachePath = storage_path('app/geocache.json');
        $geocache = file_exists($geocachePath) ? json_decode(file_get_contents($geocachePath), true) : [];
        $geocacheUpdated = false;

        // --- HYPER-PRECISION COORDINATE MAPPING (Centered on residential areas) ---
        $locationMap = [
            // EXACT COORDINATES — Barangays in Capas, Tarlac
            'Cristo Rey, Capas' => [15.3604, 120.5371],
            'Cristo Rey'        => [15.3604, 120.5371],
            'Casalamitao'       => [15.3580, 120.5340],
            'Ferrer'            => [15.3550, 120.5300],
            'Cojuangco'         => [15.3604, 120.5371],
            
            // TARLAC MUNICIPALITIES
            'Capas'           => [15.3333, 120.5833], 
            'Concepcion'      => [15.3258, 120.6567], 
            'Tarlac City'     => [15.4828, 120.5979],
            'Tarlac'          => [15.4828, 120.5979],
            'Bamban'          => [15.2744, 120.5667],
            'Victoria'        => [15.5722, 120.6781],
            'Paniqui'         => [15.6667, 120.5833],
            'Gerona'          => [15.6056, 120.5986],
            'Camiling'        => [15.6833, 120.4167],
            'Moncada'         => [15.8333, 120.5833],
            'San Manuel'      => [15.8333, 120.6],
            'Santa Ignacia'   => [15.6167, 120.4333],
            'San Jose'        => [15.45, 120.45],
            'Mayantoc'        => [15.5, 120.3833],
            'Pura'            => [15.6167, 120.65],
            'Ramos'           => [15.65, 120.6333],
            'Anao'            => [15.7333, 120.6167],
            'San Clemente'    => [15.7167, 120.35],
            'O\'Donnell'      => [15.3456, 120.4987],

            // MAJOR HUBS
            'Manila'          => [14.5995, 120.9842],
            'Quezon City'     => [14.6760, 121.0437]
        ];

        // --- NOMINATIM GEOCODING FUNCTION (with file cache) ---
        $geocodeAddress = function($brgy, $city, $prov) use (&$geocache, &$geocacheUpdated, $geocachePath) {
            $cacheKey = strtolower(trim("$brgy, $city, $prov"));
            if (isset($geocache[$cacheKey])) {
                return $geocache[$cacheKey]; // Return cached result
            }

            // Build query string for Nominatim
            $parts = array_filter([$brgy, $city, $prov, 'Philippines']);
            $query = implode(', ', $parts);

            try {
                $url = 'https://nominatim.openstreetmap.org/search?' . http_build_query([
                    'q' => $query,
                    'format' => 'json',
                    'limit' => 1,
                    'countrycodes' => 'ph',
                ]);

                $context = stream_context_create([
                    'http' => [
                        'header' => "User-Agent: NAS-StudentPortal/1.0\r\n",
                        'timeout' => 5,
                    ]
                ]);

                $response = @file_get_contents($url, false, $context);
                if ($response) {
                    $data = json_decode($response, true);
                    if (!empty($data) && isset($data[0]['lat'], $data[0]['lon'])) {
                        $coords = [(float)$data[0]['lat'], (float)$data[0]['lon']];
                        $geocache[$cacheKey] = $coords;
                        $geocacheUpdated = true;
                        return $coords;
                    }
                }
            } catch (\Exception $e) {
                // Silently fail — fallback to locationMap
            }

            // Cache null to avoid repeated failed lookups
            $geocache[$cacheKey] = null;
            $geocacheUpdated = true;
            return null;
        };

        $allStudents = Student::select('first_name', 'last_name', 'region', 'province', 'grade_level', 'id_picture', 'municipality_city', 'barangay', 'street_address')->get();
        $mapMarkers = [];
        $islandCounts = ['Luzon' => 0, 'Visayas' => 0, 'Mindanao' => 0, 'Unknown' => 0];

        $luzonRegions = ['NCR', 'CAR', 'REGION I', 'REGION II', 'REGION III', 'REGION IV-A', 'REGION IV-B', 'REGION V', 'ILOCOS', 'CAGAYAN', 'CENTRAL LUZON', 'CALABARZON', 'MIMAROPA', 'BICOL'];
        $visayasRegions = ['REGION VI', 'REGION VII', 'REGION VIII', 'WESTERN VISAYAS', 'CENTRAL VISAYAS', 'EASTERN VISAYAS'];
        $mindanaoRegions = ['REGION IX', 'REGION X', 'REGION XI', 'REGION XII', 'REGION XIII', 'BARMM', 'ZAMBOANGA', 'NORTHERN MINDANAO', 'DAVAO', 'SOCCSKSARGEN', 'CARAGA', 'BANGSAMORO'];

        foreach ($allStudents as $student) {
            $street = trim($student->street_address ?: '');
            $brgy   = trim($student->barangay ?: '');
            $city   = trim($student->municipality_city ?: '');
            $prov   = trim($student->province ?: '');
            $reg    = trim($student->region ?: '');

            // Island Grouping Logic
            $r = strtoupper($reg);
            $foundIsland = false;
            foreach($luzonRegions as $l) if(strpos($r, $l) !== false) { $islandCounts['Luzon']++; $foundIsland = true; break; }
            if(!$foundIsland) foreach($visayasRegions as $v) if(strpos($r, $v) !== false) { $islandCounts['Visayas']++; $foundIsland = true; break; }
            if(!$foundIsland) foreach($mindanaoRegions as $m) if(strpos($r, $m) !== false) { $islandCounts['Mindanao']++; $foundIsland = true; break; }
            if(!$foundIsland) $islandCounts['Unknown']++;

            if (empty($city) && empty($prov) && empty($reg)) continue;

            $coords = null;
            $fullAddress = strtoupper($street . ' ' . $brgy . ' ' . $city . ' ' . $prov . ' ' . $reg);

            // Step 1: Try hardcoded locationMap first
            foreach ($locationMap as $key => $loc) {
                if (stripos($fullAddress, strtoupper($key)) !== false) {
                    $coords = $loc;
                    break; 
                }
            }

            // Step 2: If no match, try Nominatim geocoding
            if (!$coords && (!empty($brgy) || !empty($city) || !empty($prov))) {
                $coords = $geocodeAddress($brgy, $city, $prov);
            }

            if ($coords) {
                $addressKey = $student->street_address . $student->barangay . $student->municipality_city;
                $hash = crc32($addressKey);
                srand($hash);
                $offsetLat = (rand(-10, 10) / 2000000); 
                $offsetLon = (rand(-10, 10) / 2000000);
                $finalCoords = [$coords[0] + $offsetLat, $coords[1] + $offsetLon];
                srand();

                $mapMarkers[] = [
                    'name' => trim($student->first_name . ' ' . $student->last_name),
                    'grade' => $student->grade_level ?? '-',
                    'location' => trim(($street ? $street . ', ' : '') . ($brgy ? $brgy . ', ' : '') . ($city ?: $prov)),
                    'coords' => $finalCoords,
                    'photo' => $student->id_picture ?: 'https://ui-avatars.com/api/?name=' . urlencode($student->first_name) . '&background=4f46e5&color=fff&bold=true'
                ];
            }
        }

        // Save geocache if updated
        if ($geocacheUpdated) {
            @file_put_contents($geocachePath, json_encode($geocache, JSON_PRETTY_PRINT));
        }

        return view('dashboard', compact(
            'totalStudents', 'maleCount', 'femaleCount', 'totalApplicants', 
            'activeSections', 'totalTeams', 'upcomingPlans', 'activities', 
            'mapMarkers', 'sportsBreakdown', 'islandCounts'
        ));
    }

    public function getRecentActivity()
    {
        $activities = ActivityLog::with('user')->latest()->take(5)->get()->map(function ($activity) {
            return [
                'action' => $activity->action,
                'description' => $activity->description,
                'time_ago' => $activity->created_at->diffForHumans(),
                'user' => $activity->user ? [
                    'name' => $activity->user->name,
                    'role' => $activity->user->role,
                ] : null,
            ];
        });
        return response()->json($activities);
    }

    public function getStats()
    {
        return response()->json([
            'totalStudents' => Student::count(),
            'maleCount'     => Student::whereIn('sex', ['MALE', 'Boy', 'M', 'Male'])->count(),
            'femaleCount'   => Student::whereIn('sex', ['FEMALE', 'Girl', 'F', 'Female'])->count(),
            'activeSections' => Section::count(),
            'totalTeams' => Team::count(),
            'upcomingPlans' => 0,
        ]);
    }
}