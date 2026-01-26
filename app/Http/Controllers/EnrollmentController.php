<?php

namespace App\Http\Controllers;

use App\Models\Applicant; // ✅ FIXED: Changed from EnrollmentApplication to Applicant
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
// use Barryvdh\DomPDF\Facade\Pdf; // Commented out: Hindi na kailangan para sa Client-Side Print
use Carbon\Carbon;

class EnrollmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Applicant::query(); // ✅ FIXED

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

        // --- STATS LOGIC (ITO ANG NAGBIBILANG) ---
        
        // 1. TOTAL SUBMITTED: Lahat ng record sa database (Walang filter)
        $totalSubmitted = Applicant::count(); // ✅ FIXED
        
        // 2. Breakdown per Status
        $countPending    = Applicant::whereIn('status', ['Pending', 'pending', 'For Assessment', 'Pending Review'])->count(); // ✅ FIXED
        $countQualified  = Applicant::whereIn('status', ['Qualified', 'qualified'])->count(); // ✅ FIXED
        $countWaitlisted = Applicant::whereIn('status', ['Waitlisted', 'waitlisted'])->count(); // ✅ FIXED
        $countRejected   = Applicant::whereIn('status', ['Not Qualified', 'not qualified', 'Rejected', 'Failed'])->count(); // ✅ FIXED
        // ----------------------------------------

        // 3. Table Data (Pagination)
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

    public function show($id): View {
        $application = Applicant::findOrFail($id); // ✅ FIXED
        // Update timestamp if created matches updated (fresh record)
        if ($application->created_at == $application->updated_at) $application->touch(); 
        return view('admission.show', compact('application'));
    }
    
    // --- UPDATED PROCESS FUNCTION (Handles Status, Remarks, and Document Feedback) ---
    public function process(Request $request, $id): RedirectResponse {
        $application = Applicant::findOrFail($id); // ✅ FIXED
        
        $validated = $request->validate([
            'status' => 'required|string', 
            'assessment_score' => 'nullable|string', // General Remarks
            'rejection_reason' => 'nullable|string',
            'document_remarks' => 'nullable|array'   // ✨ NEW: Document-specific feedback
        ]);

        // Logic para sa rejection reason
        if ($validated['status'] !== 'Not Qualified') {
            $validated['rejection_reason'] = null;
        }

        // FIX: I-save ang current date/time sa 'date_checked'
        $validated['date_checked'] = now(); 

        // Update ang record. Dahil sa $casts sa Model, ang 'document_remarks' 
        // ay automatic na magiging JSON string sa database.
        $application->update($validated);
        
        return back()->with('success', "Application status and remarks updated successfully.");
    }

    // --- REPLACED PDF GENERATION WITH PRINT VIEW ---
    public function generatePdf($id) {
        $application = Applicant::findOrFail($id); // ✅ FIXED
        
        // Instead of using DomPDF which requires GD extension, we return a blade view
        // designed for printing. This shifts the rendering to the client's browser.
        // This is the FIX for the Vercel 500 Error.
        return view('admission.print', compact('application'));
    }
}