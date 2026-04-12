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
                'resources/css/landing/sections/precios.css',
                'resources/css/landing/sections/about.css',
                'resources/css/landing/sections/contabilidad.css',
                'resources/css/landing/sections/rh.css',
                'resources/js/app.js',
                'resources/css/auth/auth.css',
                'resources/css/landing/general-style.css',
                'resources/css/dashboard/sidebar.css',
                'resources/css/dashboard/general-dashboard.css',
                'resources/css/dashboard/settings-profile.css',
                'resources/css/dashboard/control-panel.css',
                'resources/css/dashboard/features/calendario.css',
                'resources/js/dashboard/features/calendario.js',
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
