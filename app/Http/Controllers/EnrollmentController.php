<?php

namespace App\Http\Controllers;

use App\Models\EnrollmentApplication;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Barryvdh\DomPDF\Facade\Pdf; 

class EnrollmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = EnrollmentApplication::query();

        // 1. Search Logic
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('last_name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('lrn', 'like', "%{$search}%")
                  ->orWhere('email_address', 'like', "%{$search}%");
            });
        }

        // 2. Filter Logic (Kapag kinlick ang cards)
        if ($request->has('status') && $request->status != '') {
            $status = $request->status;
            
            if ($status === 'Pending') {
                $query->whereIn('status', ['Pending', 'pending', 'For Assessment']);
            } 
            elseif ($status === 'Qualified') {
                $query->whereIn('status', ['Qualified', 'qualified']);
            }
            elseif ($status === 'Waitlisted') {
                $query->whereIn('status', ['Waitlisted', 'waitlisted']);
            }
            elseif ($status === 'Not Qualified') {
                $query->whereIn('status', ['Not Qualified', 'not qualified', 'Rejected', 'Failed']);
            }
            else {
                $query->where('status', $status);
            }
        }

        // --- STATS LOGIC (ITO ANG NAGBIBILANG) ---
        
        // 1. TOTAL SUBMITTED: Lahat ng record sa database (Walang filter)
        $totalSubmitted = EnrollmentApplication::count(); 
        
        // 2. Breakdown per Status
        $countPending    = EnrollmentApplication::whereIn('status', ['Pending', 'pending', 'For Assessment'])->count();
        $countQualified  = EnrollmentApplication::whereIn('status', ['Qualified', 'qualified'])->count();
        $countWaitlisted = EnrollmentApplication::whereIn('status', ['Waitlisted', 'waitlisted'])->count();
        $countRejected   = EnrollmentApplication::whereIn('status', ['Not Qualified', 'not qualified', 'Rejected', 'Failed'])->count();
        // ----------------------------------------

        // 3. Table Data (Pagination)
        $applications = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admission.index', compact(
            'applications', 
            'totalSubmitted', // Ipapasa natin ito sa view
            'countPending', 
            'countQualified', 
            'countWaitlisted', 
            'countRejected'
        ));
    }

    // ... (Show, Process, and GeneratePDF functions remain the same)
    public function show($id): View {
        $application = EnrollmentApplication::findOrFail($id);
        if ($application->created_at == $application->updated_at) $application->touch(); 
        return view('admission.show', compact('application'));
    }
    
    public function process(Request $request, $id): RedirectResponse {
        $application = EnrollmentApplication::findOrFail($id);
        $validated = $request->validate(['status' => 'required|string', 'assessment_score' => 'nullable|string', 'rejection_reason' => 'nullable|string']);
        if ($validated['status'] !== 'Not Qualified') $validated['rejection_reason'] = null;
        $application->update($validated);
        return back()->with('success', "Status updated successfully.");
    }

    public function generatePdf($id) {
        $application = EnrollmentApplication::findOrFail($id);
        $pdf = Pdf::loadView('admission.pdf', compact('application'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('NAS_Application_' . $application->lrn . '.pdf');
    }
}