<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Applicant;
use App\Models\Student;
use Illuminate\Support\Str;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "[1/2] MIGRATING APPLICANTS uploaded_files...\n";

$applicants = Applicant::whereNotNull('uploaded_files')->get();
$migratedCount = 0;
$failedCount = 0;

foreach ($applicants as $applicant) {
    if (empty($applicant->uploaded_files)) continue;

    $files = is_string($applicant->uploaded_files) ? json_decode($applicant->uploaded_files, true) : $applicant->uploaded_files;
    if (!$files) continue;

    $hasChanges = false;

    foreach ($files as $key => $url) {
        if (is_string($url) && str_contains($url, 'res.cloudinary.com')) {
            // echo "Downloading: {$url}\n";
            try {
                $contents = file_get_contents($url);
                if ($contents) {
                    $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                    $filename = uniqid('migrated_') . '_' . time() . '.' . $extension;
                    Storage::disk('public')->put('nas_requirements/' . $filename, $contents);
                    $files[$key] = 'nas_requirements/' . $filename;
                    $hasChanges = true;
                    $migratedCount++;
                } else {
                    $failedCount++;
                }
            } catch (\Exception $e) {
                // echo "  -> ERROR: " . $e->getMessage() . "\n";
                $failedCount++;
            }
        }
    }

    if ($hasChanges) {
        $applicant->uploaded_files = $files;
        $applicant->save();
        echo "[Applicant ID: {$applicant->id}] Migrated files.\n";
    }
}

echo "Applicants Migration Complete: {$migratedCount} files migrated, {$failedCount} failed.\n";


echo "\n[2/2] MIGRATING STUDENTS id_picture...\n";

$students = Student::whereNotNull('id_picture')->where('id_picture', 'like', '%res.cloudinary.com%')->get();
$studentMigratedCount = 0;
$studentFailedCount = 0;

foreach ($students as $student) {
    $url = $student->id_picture;
    if (str_contains($url, 'res.cloudinary.com')) {
        try {
            $contents = file_get_contents($url);
            if ($contents) {
                $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                $filename = uniqid('student_migrated_') . '_' . time() . '.' . $extension;
                Storage::disk('public')->put('nas_requirements/' . $filename, $contents);
                
                $student->id_picture = 'nas_requirements/' . $filename;
                $student->save();
                
                $studentMigratedCount++;
                echo "[Student ID: {$student->id}] Migrated id_picture.\n";
            } else {
                $studentFailedCount++;
            }
        } catch (\Exception $e) {
            $studentFailedCount++;
        }
    }
}

echo "Students Migration Complete: {$studentMigratedCount} files migrated, {$studentFailedCount} failed.\n";
