<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog; // 👈 1. ADDED IMPORT

class GradeController extends Controller
{
    /**
     * STEP 1: Selection Page (Mamimili ng Section)
     */
    public function index()
    {
        $user = Auth::user();
        
        // --- 1. ADMIN / REGISTRAR ACCESS ---
        // Kung Admin o Registrar, ipakita ang LAHAT ng sections
        if ($user->role === 'admin' || $user->role === 'registrar') {
            $sections = Section::with('adviser') // Optional: Isama ang adviser info
                               ->orderBy('grade_level')
                               ->orderBy('section_name')
                               ->get();
            
            return view('grades.index', compact('sections'));
        }

        // --- 2. TEACHER ACCESS ---
        // Kung Teacher, hanapin lang ang hawak niya
        $staff = Staff::where('email', $user->email)->first();

        if (!$staff) {
            // FIX SA ERROR: Gumamit ng collect() para empty Collection, hindi Array []
            // Para gumana ang $sections->isEmpty() sa view
            $sections = collect(); 
        } else {
            // Hanapin ang Advisory Class
            $sections = Section::where('adviser_id', $staff->id)->get();
        }

        return view('grades.index', compact('sections'));
    }

    /**
     * STEP 2: The Grading Sheet (Dito na mag-eencode)
     */
    public function show($id)
    {
        // Security Check: Pwede mong dagdagan dito kung allowed ba si user i-access to.
        // Pero sa ngayon, hayaan nating open para sa Admin at Adviser.

        // Hanapin ang section at ang mga estudyante nito
        $section = Section::with(['students' => function($query) {
                        $query->orderBy('last_name')->orderBy('first_name');
                    }])->findOrFail($id);

        return view('grades.show', compact('section'));
    }

    /**
     * STEP 3: Save Logic (Updated with Logging)
     */
    public function bulkUpdate(Request $request)
    {
        $grades = $request->input('grades');
        $updatedCount = 0; // Pang-track kung may nabago

        if ($grades) {
            foreach ($grades as $studentId => $data) {
                $student = Student::find($studentId);
                
                if ($student) {
                    $q1 = isset($data['q1']) && $data['q1'] !== '' ? floatval($data['q1']) : null;
                    $q2 = isset($data['q2']) && $data['q2'] !== '' ? floatval($data['q2']) : null;
                    $q3 = isset($data['q3']) && $data['q3'] !== '' ? floatval($data['q3']) : null;
                    $q4 = isset($data['q4']) && $data['q4'] !== '' ? floatval($data['q4']) : null;
                    
                    $gradesArray = [];
                    if (!is_null($q1)) $gradesArray[] = $q1;
                    if (!is_null($q2)) $gradesArray[] = $q2;
                    if (!is_null($q3)) $gradesArray[] = $q3;
                    if (!is_null($q4)) $gradesArray[] = $q4;
                    
                    $average = null;
                    if (count($gradesArray) > 0) {
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

        // 👇 2. ADDED ACTIVITY LOGGING HERE
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