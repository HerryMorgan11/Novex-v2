import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/landing/shared/navbar.css',
                'resources/css/landing/sections/home/header.css',
                'resources/css/landing/sections/home/modules-section.css',
                'resources/js/app.js',
                'resources/css/auth/auth.css',
                'resources/css/landing/sections/home/choose.css',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
