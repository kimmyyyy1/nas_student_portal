<?php

if (!function_exists('fileUrl')) {
    /**
     * Generate the correct display URL for a file path.
     * - If the path starts with 'http', it's an old Cloudinary URL → return as-is
     * - Otherwise, it's a local storage path → generate asset('storage/...') URL
     */
    function fileUrl($path) {
        if (empty($path)) return null;
        if (str_starts_with($path, 'http')) return $path; // Old Cloudinary URL
        
        // Clean the path to avoid double 'storage/' prefix issues
        $cleanPath = ltrim($path, '/');
        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }
        
        return asset('storage/' . $cleanPath);
    }
}
