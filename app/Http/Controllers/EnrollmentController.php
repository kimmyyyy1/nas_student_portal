<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class EnrollmentController extends Controller
{
    // ... (Keep index, show, generatePdf functions as is) ...
    public function index(Request $request): View
    {
        // ... (Kopyahin ang dating index code o hayaan kung meron na) ...
        // Para sa ikli, proceed tayo sa methods na kailangang ayusin:
        $query = Applicant::query();
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('last_name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('lrn', 'like', "%{$search}%")
                  ->orWhere('email_address', 'like', "%{$search}%");
            });
        }
        if ($request->has('status') && $request->status != '') {
            $status = $request->status;
            if ($status === 'Pending') {
                $query->whereIn('status', ['Pending', 'pending', 'For Assessment', 'Pending Review', 'With Pending Requirements', 'With Complete Requirements & for 1st Level Assessment']);
            } elseif ($status === 'Qualified') {
                $query->whereIn('status', ['Qualified', 'qualified']);
            } elseif ($status === 'Waitlisted') {
                $query->whereIn('status', ['Waitlisted', 'waitlisted']);
            } elseif ($status === 'Not Qualified') {
                $query->whereIn('status', ['Not Qualified', 'not qualified', 'Rejected', 'Failed']);
            } else {
                $query->where('status', $status);
            }
        }
        $totalSubmitted = Applicant::count();
        $countPending    = Applicant::whereIn('status', ['Pending', 'pending', 'For Assessment', 'Pending Review', 'With Pending Requirements', 'With Complete Requirements & for 1st Level Assessment'])->count();
        $countQualified  = Applicant::whereIn('status', ['Qualified', 'qualified'])->count();
        $countWaitlisted = Applicant::whereIn('status', ['Waitlisted', 'waitlisted'])->count();
        $countRejected   = Applicant::whereIn('status', ['Not Qualified', 'not qualified', 'Rejected', 'Failed'])->count();
        $applications = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admission.index', compact('applications', 'totalSubmitted', 'countPending', 'countQualified', 'countWaitlisted', 'countRejected'));
    }

    public function show($id): View {
        $application = Applicant::findOrFail($id);
        if ($application->created_at == $application->updated_at) { $application->touch(); }
        return view('admission.show', compact('application'));
    }

    public function process(Request $request, $id): RedirectResponse {
        $application = Applicant::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|string', 
            'assessment_score' => 'nullable|string',
            'rejection_reason' => 'nullable|string',
            'document_remarks' => 'nullable|array'
        ]);

        if ($validated['status'] !== 'Not Qualified') { $validated['rejection_reason'] = null; }
        $validated['date_checked'] = now(); 

        // Update Remarks only (Statuses are handled by buttons)
        $currentRemarks = $application->document_remarks ?? [];
        if (isset($validated['document_remarks'])) {
            // Merge new remarks
            $currentRemarks = array_replace($currentRemarks, $validated['document_remarks']);
        }
        $validated['document_remarks'] = $currentRemarks;

        // Force Status Update based on Document Statuses
        // Kung may declined, dapat "With Pending Requirements" ang status kung nasa 2nd Level na
        // Pero sundin muna natin ang manual select sa view.

        $application->update($validated);
        
        return back()->with('success', "Application details updated.");
    }

    // --- 4. APPROVE DOCUMENT (FIXED) ---
    public function approveDocument($id, $doc_key): RedirectResponse
    {
        $application = Applicant::findOrFail($id);
        
        $statuses = $application->document_statuses ?? [];
        $remarks = $application->document_remarks ?? [];
        
        // 1. Set Status to Approved
        $statuses[$doc_key] = 'approved';
        
        // 2. Clear Remarks (Optional: para malinis)
        if(isset($remarks[$doc_key])) {
            $remarks[$doc_key] = null;
        }
        
        $application->update([
            'document_statuses' => $statuses,
            'document_remarks' => $remarks, // Save cleared remarks
            'date_checked' => now()
        ]);
        
        return back()->with('success', strtoupper(str_replace('_', ' ', $doc_key)) . ' approved.');
    }

    // --- 5. DECLINE DOCUMENT (FIXED) ---
    public function declineDocument($id, $doc_key): RedirectResponse
    {
        $application = Applicant::findOrFail($id);
        
        $statuses = $application->document_statuses ?? [];
        
        // 1. Set Status to Declined
        $statuses[$doc_key] = 'declined';
        
        $application->update([
            'document_statuses' => $statuses,
            'date_checked' => now()
        ]);
        
        return back()->with('error', strtoupper(str_replace('_', ' ', $doc_key)) . ' declined. Please verify/add remarks.');
    }

    public function generatePdf($id) {
        $application = Applicant::findOrFail($id);
        return view('admission.print', compact('application'));
    }
}