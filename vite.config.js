import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // 👇 IDAGDAG MO ANG SECTION NA ITO:
    build: {
        // Hides source maps (ito yung nagpapakita ng file structure sa browser)
        sourcemap: false,
        
        // Increases chunk size warning limit (optional, para iwas warning sa build)
        chunkSizeWarningLimit: 1600,
        
        // Minify output (standard for production)
        minify: 'esbuild',
    },
    // 👇 OPTIONAL: Tanggalin ang console.logs sa production para malinis
    esbuild: {
        drop: ['console', 'debugger'],
    },
}); 