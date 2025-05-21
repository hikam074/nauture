import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/global.css',
                'resources/js/app.js',
                'resources/js/bootstrap.js',
                'resources/js/sweetalert.js',
            ], // Sesuaikan dengan file yang kamu gunakan
            refresh: true,
        }),
    ],
});
