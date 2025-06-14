import fs from 'fs';
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
                'resources/js/chart.js',
                'resources/js/toastr.js',
                // 'resources/js/anime.js',
            ], // Sesuaikan dengan file yang kamu gunakan
            refresh: true,
            resolve: {
                alias: {
                    '@': '/resources/js',
                },
            },
        }),
    ],
    // server: {
    //     // host: 'nauture_devmidtrans.test', // Ganti dengan domain Laragon Anda
    //     host: 'nauture_devmidtrans.test', // Ganti dengan domain Laragon Anda
    //     https: {
    //         key: fs.readFileSync('C:/Apps/laragon/etc/ssl/laragon.key'),
    //         cert: fs.readFileSync('C:/Apps/laragon/etc/ssl/laragon.crt'),
    //     },
    // },
});
