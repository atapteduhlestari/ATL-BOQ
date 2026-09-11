import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0', // Mengizinkan akses dari semua IP
        port: 5173,
        https: false,
        hmr: {
            host: '192.168.1.58', // Ganti dengan IP komputer Anda
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});