import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/landing/shared/navbar.css',
                'resources/css/landing/shared/footer.css',
                'resources/css/landing/sections/home/header.css',
                'resources/css/landing/sections/home/modules-section.css',
                'resources/css/landing/sections/home/scale-fast.css',
                'resources/css/landing/sections/home/choose.css',
                'resources/css/landing/sections/precios.css',
                'resources/css/landing/sections/about.css',
                'resources/css/landing/sections/contabilidad.css',
                'resources/css/landing/sections/rh.css',
                'resources/js/app.js',
                'resources/css/auth/auth.css',
                'resources/css/auth/register.css',
                'resources/css/landing/general-style.css',
                // Dashboard CSS
                'resources/css/dashboard/sidebar.css',
                'resources/css/dashboard/general-dashboard.css',
                'resources/css/dashboard/navbar.css',
                'resources/css/dashboard/settings-profile.css',
                'resources/css/dashboard/control-panel.css',
                'resources/css/dashboard/features/calendario.css',
                'resources/css/dashboard/features/notes.css',
                'resources/css/dashboard/features/dashboard.css',
                // Dashboard JS
                'resources/js/dashboard/sidebar.js',
                'resources/js/dashboard/subtasks.js',
                'resources/js/dashboard/createCompanyModal.js',
                'resources/js/dashboard/features/calendario.js',
                'resources/js/dashboard/features/dashboard.js',
                // Settings & Control Panel JS
                'resources/js/settings/settings.js',
                'resources/js/controlPanel/navigation.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
