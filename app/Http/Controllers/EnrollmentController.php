<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; // Added for Auth access
use App\Models\AuditLog;

class EnrollmentController extends Controller
{
    public function index(Request $request): View
    {
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

    // --- UPDATED SHOW METHOD FOR NOTIFICATION MARK AS READ ---
    public function show(Request $request, $id): View 
    {
        // 1. Mark Notification as Read (If clicked from bell)
        if ($request->has('read')) {
            $notificationId = $request->query('read');
            $notification = Auth::user()->notifications()->find($notificationId);
            
            if ($notification) {
                $notification->markAsRead();
            }
        }

        $application = Applicant::findOrFail($id);
        
        // Update timestamp if newly created (optional logic from your code)
        if ($application->created_at == $application->updated_at) { 
            $application->touch(); 
        }
        
        $auditLogs = \App\Models\AuditLog::with('user')->where('applicant_id', $id)->latest()->get();

        return view('admission.show', compact('application', 'auditLogs'));
    }

    public function process(Request $request, $id) {
        $application = Applicant::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|string', 
            'assessment_score' => 'nullable|string',
            'rejection_reason' => 'nullable|string',
            'document_remarks' => 'nullable|array'
        ]);

        if ($validated['status'] !== 'Not Qualified') { $validated['rejection_reason'] = null; }
        $validated['date_checked'] = now(); 

        // Update Remarks only
        $currentRemarks = $application->document_remarks ?? [];
        if (isset($validated['document_remarks'])) {
            $currentRemarks = array_replace($currentRemarks, $validated['document_remarks']);
        }
        $validated['document_remarks'] = $currentRemarks;

        $application->update($validated);

        // LOG AUDIT TRAIL
        AuditLog::create([
            'user_id' => Auth::id(),
            'applicant_id' => $application->id,
            'action' => 'Updated Application Status',
            'details' => json_encode([
                'status' => $validated['status'], 
                'remarks' => 'Updated via Application Review'
            ])
        ]);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Application details updated.']);
        }

        return redirect()->route('admission.show', ['id' => $id])->with('success', 'Application details updated.');
    }

    public function approveDocument(Request $request, $id, $doc_key)
    {
        $application = Applicant::findOrFail($id);
        
        $statuses = is_array($application->document_statuses) ? $application->document_statuses : (is_string($application->document_statuses) ? json_decode($application->document_statuses, true) : []);
        if (!is_array($statuses)) $statuses = [];

        $remarks = is_array($application->document_remarks) ? $application->document_remarks : (is_string($application->document_remarks) ? json_decode($application->document_remarks, true) : []);
        if (!is_array($remarks)) $remarks = [];
        
        $statuses[$doc_key] = 'Accepted';
        
        if(isset($remarks[$doc_key])) {
            $remarks[$doc_key] = null;
        }
        
        $application->update([
            'document_statuses' => $statuses,
            'document_remarks' => $remarks, 
            'date_checked' => now()
        ]);

        // LOG AUDIT TRAIL
        AuditLog::create([
            'user_id' => Auth::id(),
            'applicant_id' => $application->id,
            'action' => 'Accepted Document',
            'details' => json_encode([
                'document' => $doc_key, 
                'status' => 'Accepted'
            ])
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'Accepted', 'doc_key' => $doc_key]);
        }
        
        return back()->with('success', strtoupper(str_replace('_', ' ', $doc_key)) . ' Accepted.');
    }

    public function declineDocument(Request $request, $id, $doc_key)
    {
        $application = Applicant::findOrFail($id);
        
        $statuses = is_array($application->document_statuses) ? $application->document_statuses : (is_string($application->document_statuses) ? json_decode($application->document_statuses, true) : []);
        if (!is_array($statuses)) $statuses = [];
        
        $statuses[$doc_key] = 'Needs resubmission';
        
        $application->update([
            'document_statuses' => $statuses,
            'date_checked' => now()
        ]);

        // LOG AUDIT TRAIL
        AuditLog::create([
            'user_id' => Auth::id(),
            'applicant_id' => $application->id,
            'action' => 'Returned Document',
            'details' => json_encode([
                'document' => $doc_key, 
                'status' => 'Needs resubmission'
            ])
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'Needs resubmission', 'doc_key' => $doc_key]);
        }
        
        return back()->with('error', strtoupper(str_replace('_', ' ', $doc_key)) . ' marked for resubmission. Please verify/add remarks.');
    }

    public function generatePdf($id) {
        $application = Applicant::findOrFail($id);
        return view('admission.print', compact('application'));
    }
}