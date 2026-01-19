<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Section;
use App\Models\Staff;
use App\Models\Student;

class TeacherController extends Controller
{
    /**
     * Display the teacher's advisory class list.
     * Route: GET /teacher/advisory
     */
    public function advisory()
    {
        // 1. Kunin ang User Info
        $user = Auth::user();

        // 2. Hanapin ang STAFF record gamit ang email
        $staff = Staff::where('email', $user->email)->first();

        // Kung walang staff profile
        if (!$staff) {
            return view('teacher.advisory', ['section' => null])
                ->with('error', 'Staff profile not found.');
        }

        // 3. Hanapin ang section gamit ang STAFF ID
        $section = Section::where('adviser_id', $staff->id)
                        ->with(['students' => function($query) {
                            $query->orderBy('last_name')->orderBy('first_name');
                        }])
                        ->first();

        // 4. Ibalik ang view
        return view('teacher.advisory', compact('section'));
    }

    /**
     * Bulk Update Grades for Advisory Class
     * Route: PATCH /teacher/advisory/bulk-update
     */
    public function bulkUpdateGrades(Request $request)
    {
        // Kunin ang submitted grades array mula sa form
        $grades = $request->input('grades');

        if ($grades) {
            foreach ($grades as $studentId => $data) {
                // Hanapin ang student
                $student = Student::find($studentId);
                
                if ($student) {
                    // Compute Final Grade (Automatic Average)
                    $q1 = isset($data['q1']) ? floatval($data['q1']) : null;
                    $q2 = isset($data['q2']) ? floatval($data['q2']) : null;
                    $q3 = isset($data['q3']) ? floatval($data['q3']) : null;
                    $q4 = isset($data['q4']) ? floatval($data['q4']) : null;
                    
                    $average = null;
                    
                    // Mag-compute lang kung may laman kahit isa
                    // (Pwede mong baguhin ang logic na ito kung gusto mong running average)
                    $gradesArray = array_filter([$q1, $q2, $q3, $q4], function($v) { return !is_null($v) && $v !== ''; });
                    
                    if (count($gradesArray) > 0) {
                        if (count($gradesArray) === 4) {
                            // Kung kumpleto Q1-Q4, exact average
                            $average = array_sum($gradesArray) / 4;
                        } else {
                            // Kung hindi pa kumpleto, temporary average muna based sa count
                            $average = array_sum($gradesArray) / count($gradesArray);
                        }
                    }

                    // I-update ang record sa database
                    $student->update([
                        'q1' => $q1,
                        'q2' => $q2,
                        'q3' => $q3,
                        'q4' => $q4,
                        'general_average' => $average,
                        'promotion_status' => $data['status'] ?? null,
                    ]);
                }
            }
        }

        // Bumalik sa page na may success message
        return redirect()->back()->with('success', 'All grades saved successfully!');
    }
}