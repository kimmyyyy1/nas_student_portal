<?php
echo "Current Directory: " . getcwd() . "<br>";
$link = 'storage';
if (is_link($link)) {
    echo "Symlink 'storage' exists.<br>";
    echo "Target: " . readlink($link) . "<br>";
} else {
    echo "Symlink 'storage' does NOT exist or is not a link.<br>";
}

$target = __DIR__ . '/../storage/app/public';
echo "Expected target: " . realpath($target) . "<br>";
if (file_exists($target)) {
    echo "Target directory exists.<br>";
} else {
    echo "Target directory does NOT exist.<br>";
}
