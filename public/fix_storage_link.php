<?php
$target = __DIR__ . '/../storage/app/public';
$link = __DIR__ . '/storage';

if (file_exists($link)) {
    if (is_link($link)) {
        echo "Symlink already exists.\n";
    } else {
        echo "Error: 'storage' exists but is a directory, not a symlink. Please delete the 'public/storage' folder first.\n";
    }
} else {
    if (symlink($target, $link)) {
        echo "Symlink created successfully.\n";
    } else {
        echo "Error: Failed to create symlink.\n";
    }
}
