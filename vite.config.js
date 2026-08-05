import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/filament/admin/theme.css',
                'resources/js/app.js',
            ],
            detectTls: false,
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '127.0.0.1',
        hmr: {
            host: '127.0.0.1',
        },
    },
});
