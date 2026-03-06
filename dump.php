<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Applicant;

$a = Applicant::find(649072);
if ($a) {
    $data = [
        'id' => $a->id,
        'status' => $a->status,
        'document_remarks' => is_string($a->document_remarks) ? json_decode($a->document_remarks, true) : $a->document_remarks,
        'document_statuses' => is_string($a->document_statuses) ? json_decode($a->document_statuses, true) : $a->document_statuses,
    ];
    file_put_contents(__DIR__ . '/dump.json', json_encode($data, JSON_PRETTY_PRINT));
    echo "Dump written to dump.json.\n";
} else {
    echo "Applicant not found.\n";
}
