<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog; // ✅ Imported

class GradeController extends Controller
{
    /**
     * STEP 1: Selection Page (Mamimili ng Section)
     */
    public function index()
    {
        $user = Auth::user();
        
        // --- 1. ADMIN / REGISTRAR ACCESS ---
        if ($user->role === 'admin' || $user->role === 'registrar') {
            $sections = Section::with('adviser')
                ->withCount('students') // 👈 IMPORTANT: Para sa bilang ng students sa card
                ->orderBy('grade_level')
                ->orderBy('section_name')
                ->get();
            
            return view('grades.index', compact('sections'));
        }

        // --- 2. TEACHER ACCESS ---
        $staff = Staff::where('email', $user->email)->first();

        if (!$staff) {
            $sections = collect(); 
        } else {
            // Hanapin ang Advisory Class (Added withCount here too)
            $sections = Section::where('adviser_id', $staff->id)
                ->withCount('students') // 👈 Added here as well for consistency
                ->get();
        }

        return view('grades.index', compact('sections'));
    }

    /**
     * STEP 2: The Grading Sheet (Dito na mag-eencode)
     */
    public function show($id)
    {
        // Hanapin ang section at ang mga estudyante nito
        $section = Section::with(['students' => function($query) {
                        $query->orderBy('last_name')->orderBy('first_name');
                    }])->findOrFail($id);

        return view('grades.show', compact('section'));
    }

    /**
     * STEP 3: Save Logic (With Activity Logging)
     */
    public function bulkUpdate(Request $request)
    {
        $grades = $request->input('grades');
        $updatedCount = 0;

        if ($grades) {
            foreach ($grades as $studentId => $data) {
                $student = Student::find($studentId);
                
                if ($student) {
                    // Normalize inputs (empty string becomes null)
                    $q1 = isset($data['q1']) && $data['q1'] !== '' ? floatval($data['q1']) : null;
                    $q2 = isset($data['q2']) && $data['q2'] !== '' ? floatval($data['q2']) : null;
                    $q3 = isset($data['q3']) && $data['q3'] !== '' ? floatval($data['q3']) : null;
                    $q4 = isset($data['q4']) && $data['q4'] !== '' ? floatval($data['q4']) : null;
                    
                    // Compute Average dynamically based on input count
                    $gradesArray = [];
                    if (!is_null($q1)) $gradesArray[] = $q1;
                    if (!is_null($q2)) $gradesArray[] = $q2;
                    if (!is_null($q3)) $gradesArray[] = $q3;
                    if (!is_null($q4)) $gradesArray[] = $q4;
                    
                    $average = null;
                    if (count($gradesArray) > 0) {
                        // Standard DepEd averaging (usually divide by 4, but let's keep it dynamic if that's preferred)
                        $divisor = (count($gradesArray) === 4) ? 4 : count($gradesArray);
                        $average = array_sum($gradesArray) / $divisor;
                    }

                    $student->update([
                        'q1' => $q1, 
                        'q2' => $q2, 
                        'q3' => $q3, 
                        'q4' => $q4,
                        'general_average' => $average,
                        'promotion_status' => $data['status'] ?? null,
                    ]);
                    
                    $updatedCount++;
                }
            }
        }

        // ✅ ACTIVITY LOGGING
        if ($updatedCount > 0) {
            $user = Auth::user();
            $role = ucfirst($user->role);
            
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'Grade Update',
                'description' => "<strong>{$role}</strong> {$user->name} updated the grades/status of {$updatedCount} student(s).",
            ]);
        }

        return redirect()->back()->with('success', 'Grades updated successfully!');
    }
}