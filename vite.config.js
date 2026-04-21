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
                // Landing section CSS
                'resources/css/landing/sections/crm/cta.css',
                'resources/css/landing/sections/crm/hero.css',
                'resources/css/landing/sections/crm/metricas.css',
                'resources/css/landing/sections/crm/funcionalidades.css',
                'resources/css/landing/sections/recursos-humanos/cta.css',
                'resources/css/landing/sections/recursos-humanos/hero.css',
                'resources/css/landing/sections/recursos-humanos/metricas.css',
                'resources/css/landing/sections/recursos-humanos/funcionalidades.css',
                'resources/css/landing/sections/inventario/cta.css',
                'resources/css/landing/sections/inventario/hero.css',
                'resources/css/landing/sections/inventario/metricas.css',
                'resources/css/landing/sections/inventario/funcionalidades.css',
                'resources/css/landing/sections/contabilidad/cta.css',
                'resources/css/landing/sections/contabilidad/hero.css',
                'resources/css/landing/sections/contabilidad/dashboard.css',
                'resources/css/landing/sections/contabilidad/funcionalidades.css',
                // Settings CSS
                'resources/css/dashboard/features/settings/settings.css',
                // Reminders CSS
                'resources/css/dashboard/features/reminders/reminders.css',
                // Inventario CSS
                'resources/css/dashboard/features/inventario/inventario.css',
                'resources/css/dashboard/features/inventario/almacenes.css',
                'resources/css/dashboard/features/inventario/expediciones.css',
                'resources/css/dashboard/features/inventario/produccion.css',
                'resources/css/dashboard/features/inventario/stock.css',
                'resources/css/dashboard/features/inventario/transportes.css',
                'resources/css/dashboard/features/inventario/trazabilidad.css',
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
