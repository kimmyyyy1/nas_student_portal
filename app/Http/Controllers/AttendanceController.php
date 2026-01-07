<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Section;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // STEP 1: Listahan ng Sections (Modified for Admin View)
    public function index()
    {
        $user = Auth::user();

        // --- 1. ADMIN / REGISTRAR CHECK ---
        // Kung Admin, kunin lahat ng sections + Adviser Info + Student Count
        if ($user->role === 'admin' || $user->role === 'registrar') {
            $sections = Section::with(['adviser', 'students']) // Eager load para mabilis
                               ->orderBy('grade_level')
                               ->orderBy('section_name')
                               ->get();
            
            return view('attendances.index', compact('sections'));
        }

        // --- 2. TEACHER CHECK ---
        $staff = Staff::where('email', $user->email)->first();

        if (!$staff) {
            $sections = collect(); // Empty collection para walang error
        } else {
            // Kunin ang advisory section ng teacher
            $sections = Section::with('students')
                               ->where('adviser_id', $staff->id)
                               ->get();
        }

        return view('attendances.index', compact('sections'));
    }

    // STEP 2: Ipakita ang Attendance Sheet
    public function show(Request $request, $sectionId)
    {
        $date = $request->query('date', Carbon::now()->format('Y-m-d'));

        // Load students sorted by Last Name
        $section = Section::with(['students' => function($query) {
            $query->orderBy('last_name')->orderBy('first_name');
        }])->findOrFail($sectionId);

        // Kunin ang mga records para sa date na ito
        $attendanceRecords = Attendance::where('section_id', $sectionId)
                                       ->whereDate('date', $date)
                                       ->get()
                                       ->keyBy('student_id');

        return view('attendances.show', compact('section', 'date', 'attendanceRecords'));
    }

    // STEP 3: Save Logic
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
        }

        return redirect()->back()->with('success', 'Attendance saved successfully for ' . $date);
    }
}