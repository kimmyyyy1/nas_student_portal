<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $disk = \Illuminate\Support\Facades\Storage::disk('cloudinary');
    $disk->put('test-livewire/test.txt', 'hello vercel!');
    echo "Size: " . $disk->size('test-livewire/test.txt') . "\n";
    echo "Exists: " . ($disk->exists('test-livewire/test.txt') ? 'Yes' : 'No') . "\n";
    echo "Mime: " . $disk->mimeType('test-livewire/test.txt') . "\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
