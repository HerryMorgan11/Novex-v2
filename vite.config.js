import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/landing/shared/navbar.css',
                'resources/css/landing/shared/footer.css',
                'resources/css/landing/sections/home/header.css',
                'resources/css/landing/sections/home/modules-section.css',
                'resources/css/landing/sections/home/choose.css',
                'resources/css/landing/sections/home/pricing.css',
                'resources/css/landing/sections/home/scale-fast.css',
                'resources/js/app.js',
                'resources/css/auth/auth.css',
                'resources/css/landing/general-style.css',
                'resources/css/dashboard/sidebar.css',
                'resources/css/dashboard/general-dashboard.css',
                'resources/css/dashboard/settings-profile.css',
                'resources/css/dashboard/control-panel.css',
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
