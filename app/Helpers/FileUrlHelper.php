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
        return asset('storage/' . $path); // New local storage path
    }
}
