<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;
use App\Models\Applicant;

echo "--- FIXING STUDENT PATHS ---\n";
$students = Student::whereNotNull('id_picture')->get();
$sFixed = 0;
foreach ($students as $s) {
    $old = $s->id_picture;
    // Remove http://127.0.0.1:8000/storage/ or /storage/
    $new = str_replace(['http://127.0.0.1:8000/storage/', 'http://localhost:8000/storage/', '/storage/'], '', $old);
    $new = ltrim($new, '/');
    
    if ($old !== $new) {
        $s->id_picture = $new;
        $s->save();
        $sFixed++;
    }
}
echo "Fixed {$sFixed} students.\n";

echo "\n--- FIXING APPLICANT PATHS ---\n";
$applicants = Applicant::whereNotNull('uploaded_files')->get();
$aFixed = 0;
foreach ($applicants as $a) {
    $files = is_string($a->uploaded_files) ? json_decode($a->uploaded_files, true) : $a->uploaded_files;
    if (!$files) continue;
    
    $hasChanges = false;
    foreach ($files as $key => $val) {
        if (is_string($val)) {
            $new = str_replace(['http://127.0.0.1:8000/storage/', 'http://localhost:8000/storage/', '/storage/'], '', $val);
            $new = ltrim($new, '/');
            if ($val !== $new) {
                $files[$key] = $new;
                $hasChanges = true;
            }
        }
    }
    
    if ($hasChanges) {
        $a->uploaded_files = $files;
        $a->save();
        $aFixed++;
    }
}
echo "Fixed {$aFixed} applicants.\n";
