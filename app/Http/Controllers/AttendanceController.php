<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Section;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\ActivityLog; // 👈 1. ADDED IMPORT

class AttendanceController extends Controller
{
    // STEP 1: List Sections (Modified for Admin View)
    public function index()
    {
        $user = Auth::user();

        // --- 1. ADMIN / REGISTRAR CHECK ---
        // If Admin, get all sections + Adviser Info + Student Count
        if ($user->role === 'admin' || $user->role === 'registrar') {
            $sections = Section::with(['adviser', 'students']) // Eager load for performance
                               ->orderBy('grade_level')
                               ->orderBy('section_name')
                               ->get();
            
            return view('attendances.index', compact('sections'));
        }

        // --- 2. TEACHER CHECK ---
        $staff = Staff::where('email', $user->email)->first();

        if (!$staff) {
            $sections = collect(); // Empty collection to avoid errors
        } else {
            // Get teacher's advisory section
            $sections = Section::with('students')
                               ->where('adviser_id', $staff->id)
                               ->get();
        }

        return view('attendances.index', compact('sections'));
    }

    // STEP 2: Show Attendance Sheet
    public function show(Request $request, $sectionId)
    {
        $date = $request->query('date', Carbon::now()->format('Y-m-d'));

        // Load students sorted by Last Name
        $section = Section::with(['students' => function($query) {
            $query->orderBy('last_name')->orderBy('first_name');
        }])->findOrFail($sectionId);

        // Get records for this date
        $attendanceRecords = Attendance::where('section_id', $sectionId)
                                       ->whereDate('date', $date)
                                       ->get()
                                       ->keyBy('student_id');

        return view('attendances.show', compact('section', 'date', 'attendanceRecords'));
    }

    // STEP 3: Save Logic (Updated with Logging)
    public function bulkStore(Request $request)
    {
        $sectionId = $request->input('section_id');
        $date      = $request->input('date');
        $inputs    = $request->input('attendance');

        if ($inputs) {
            foreach ($inputs as $studentId => $data) {
                
                $status = $data['status'] ?? 'Present'; 
                $remarks = $data['remarks'] ?? null;

                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'section_id' => $sectionId,
                        'date'       => $date,
                    ],
                    [
                        'schedule_id' => null, // Keep existing fix
                        'status'      => $status,
                        'remarks'     => $remarks,
                    ]
                );
            }

            // 👇 2. ADDED ACTIVITY LOGGING HERE
            $user = Auth::user();
            $role = ucfirst($user->role);
            
            // Get Section Name for the log
            $section = Section::find($sectionId);
            $sectionName = $section ? ($section->grade_level . ' - ' . $section->section_name) : 'Unknown Section';
            
            // Format date for better readability (e.g., "Jan 13, 2026")
            $formattedDate = Carbon::parse($date)->format('M d, Y');

            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'Attendance Check',
                'description' => "<strong>{$role}</strong> {$user->name} checked attendance for <strong>{$sectionName}</strong> on {$formattedDate}.",
            ]);
        }

        return redirect()->back()->with('success', 'Attendance saved successfully for ' . $date);
    }
}