<?php

namespace App\Http\Controllers;

use App\Models\Applicant; // Siguraduhin na tama ang Model Name mo
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class EnrollmentController extends Controller
{
    // --- 1. INDEX (DASHBOARD / LIST) ---
    public function index(Request $request): View
    {
        $query = Applicant::query();

        // Search Logic
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('last_name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('lrn', 'like', "%{$search}%")
                  ->orWhere('email_address', 'like', "%{$search}%");
            });
        }

        // Filter Logic
        if ($request->has('status') && $request->status != '') {
            $status = $request->status;
            
            if ($status === 'Pending') {
                $query->whereIn('status', ['Pending', 'pending', 'For Assessment', 'Pending Review']);
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

        // Statistics
        $totalSubmitted = Applicant::count();
        $countPending    = Applicant::whereIn('status', ['Pending', 'pending', 'For Assessment', 'Pending Review'])->count();
        $countQualified  = Applicant::whereIn('status', ['Qualified', 'qualified'])->count();
        $countWaitlisted = Applicant::whereIn('status', ['Waitlisted', 'waitlisted'])->count();
        $countRejected   = Applicant::whereIn('status', ['Not Qualified', 'not qualified', 'Rejected', 'Failed'])->count();

        // Pagination
        $applications = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admission.index', compact(
            'applications', 
            'totalSubmitted', 
            'countPending', 
            'countQualified', 
            'countWaitlisted', 
            'countRejected'
        ));
    }

    // --- 2. SHOW (REVIEW PAGE) ---
    public function show($id): View {
        $application = Applicant::findOrFail($id);
        
        // Optional: Update timestamp if freshly created to fix sorting
        if ($application->created_at == $application->updated_at) {
            $application->touch(); 
        }

        return view('admission.show', compact('application'));
    }
    
    // --- 3. PROCESS (SAVE UPDATES) ---
    public function process(Request $request, $id): RedirectResponse {
        $application = Applicant::findOrFail($id);
        
        // Validate Inputs
        $validated = $request->validate([
            'status' => 'required|string', 
            'assessment_score' => 'nullable|string', // General Remarks
            'rejection_reason' => 'nullable|string',
            'document_remarks' => 'nullable|array'   // Document-specific feedback
        ]);

        // Auto-clear rejection reason if not rejected
        if ($validated['status'] !== 'Not Qualified') {
            $validated['rejection_reason'] = null;
        }

        // Update 'date_checked' timestamp
        $validated['date_checked'] = now(); 

        // Get current statuses, or initialize an empty array
        $statuses = $application->document_statuses ?? [];

        // Loop through the submitted remarks to update statuses
        if (isset($validated['document_remarks'])) {
            foreach ($validated['document_remarks'] as $key => $remark) {
                if (!empty($remark)) {
                    // If a remark is added/present, set status to 'needs_update'
                    $statuses[$key] = 'needs_update';
                } elseif (isset($statuses[$key]) && $statuses[$key] === 'needs_update') {
                    // If a remark is cleared for a doc that needed an update, clear its status.
                    // This prevents clearing 'pending_review' or 'approved' statuses.
                    unset($statuses[$key]);
                }
            }
        }
        
        // Add the updated statuses to the data to be saved
        $validated['document_statuses'] = $statuses;

        // Update Database
        $application->update($validated);
        
        return back()->with('success', "Application status and remarks updated successfully.");
    }

    // --- 4. APPROVE DOCUMENT ---
    public function approveDocument(Request $request, $id, $doc_key): RedirectResponse
    {
        $application = Applicant::findOrFail($id);
        
        $statuses = $application->document_statuses ?? [];
        
        // Set the specific document's status to 'approved'
        $statuses[$doc_key] = 'approved';
        
        $application->update(['document_statuses' => $statuses]);
        
        return back()->with('success', 'Document approved successfully.');
    }

    // --- 5. DECLINE DOCUMENT ---
    public function declineDocument(Request $request, $id, $doc_key): RedirectResponse
    {
        $application = Applicant::findOrFail($id);
        
        $statuses = $application->document_statuses ?? [];
        
        // Set the specific document's status back to 'needs_update'
        $statuses[$doc_key] = 'needs_update';
        
        $application->update(['document_statuses' => $statuses]);
        
        return back()->with('success', 'Document declined. Please add a remark.');
    }

    // --- 6. PRINT PREVIEW ---
    public function generatePdf($id) {
        $application = Applicant::findOrFail($id);
        
        // Return view designed for printing
        return view('admission.print', compact('application'));
    }
}