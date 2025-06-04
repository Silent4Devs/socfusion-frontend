import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'vendor/tomatophp/filament-simple-theme/resources/css/theme.css',
                'resources/css/filament/admin/theme.css',
                'resources/js/filament-sweet.js', 
            ],
            refresh: true,
        }),
    ],
});
