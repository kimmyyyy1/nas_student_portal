<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;
use App\Models\Applicant;

echo "--- STUDENT ID PICTURES ---\n";
$students = Student::whereNotNull('id_picture')->limit(3)->get();
foreach ($students as $s) {
    echo "ID: {$s->id} | Path: {$s->id_picture}\n";
}

echo "\n--- APPLICANT UPLOADED FILES ---\n";
$applicants = Applicant::whereNotNull('uploaded_files')->limit(2)->get();
foreach ($applicants as $a) {
    echo "ID: {$a->id} | Data: " . json_encode($a->uploaded_files) . "\n";
}
