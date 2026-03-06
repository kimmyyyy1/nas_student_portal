<?php
// Simple script to check status without full HTTP kernel handling
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Student;
use App\Models\Applicant;

$s = Student::where('nas_student_id', 'M-2026-2027')->orWhere('last_name', 'like', '%Medrano%')->first();
if ($s) {
    echo "Student ID: " . $s->nas_student_id . "\n";
    echo "Student LRN: " . $s->lrn . "\n";
    echo "Student Status: " . $s->status . "\n";
    
    $a = Applicant::where('lrn', $s->lrn)->first();
    if (!$a) {
        $a = Applicant::where('user_id', function($q) use ($s) {
            $q->select('id')->from('users')->where('student_id', $s->nas_student_id)->orWhere('email', $s->email_address);
        })->first();
    }
    
    if ($a) {
        echo "Applicant ID: " . $a->id . "\n";
        echo "Applicant Status: " . $a->status . "\n";
        echo "Document Statuses: " . json_encode($a->document_statuses) . "\n";
        echo "Document Remarks: " . json_encode($a->document_remarks) . "\n";
    } else {
        echo "No Applicant found for Student.\n";
    }
} else {
    echo "Student Medrano not found.\n";
}
