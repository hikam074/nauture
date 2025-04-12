import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/global.css',
                'resources/css/auth.css',
                'resources/js/auth.js',
            ], // Sesuaikan dengan file yang kamu gunakan
            refresh: true,
        }),
    ],
});
