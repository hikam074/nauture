import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/global.css',
                'resources/css/auth-login.css',
                'resources/css/auth-register.css',
                'resources/js/auth-login.js',
                'resources/js/auth-register.js',
            ], // Sesuaikan dengan file yang kamu gunakan
            refresh: true,
        }),
    ],
});
