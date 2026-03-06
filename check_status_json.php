<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Student;
use App\Models\Applicant;

$s = Student::where('nas_student_id', 'M-2026-2027')->orWhere('last_name', 'like', '%Medrano%')->first();
$result = ['student' => null, 'applicant' => null];

if ($s) {
    $result['student'] = [
        'id' => $s->id,
        'nas_student_id' => $s->nas_student_id,
        'lrn' => $s->lrn,
        'status' => $s->status
    ];
    
    $a = Applicant::where('lrn', $s->lrn)->first();
    if (!$a) {
        $a = Applicant::where('user_id', function($q) use ($s) {
            $q->select('id')->from('users')->where('student_id', $s->nas_student_id)->orWhere('email', $s->email_address);
        })->first();
    }
    
    if ($a) {
        $result['applicant'] = [
            'id' => $a->id,
            'status' => $a->status,
            'document_statuses' => is_string($a->document_statuses) ? json_decode($a->document_statuses, true) : $a->document_statuses,
            'document_remarks' => is_string($a->document_remarks) ? json_decode($a->document_remarks, true) : $a->document_remarks
        ];
    }
}

echo json_encode($result, JSON_PRETTY_PRINT);
