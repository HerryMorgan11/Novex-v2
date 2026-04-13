{{-- Settings Profile - Navegación por secciones de ajustes (Perfil / Seguridad).
     La sección activa se controla mediante JS (resources/js/settings/settings.js). --}}

<div class="settings-container">
    <div class="settings-wrapper">
        <div class="settings-layout">
            <!-- Sidebar de navegación -->
            <div class="settings-sidebar">
                <nav class="settings-nav">
                    <button data-settings-section="perfil" type="button">
                        <iconify-icon icon="gg:profile" width="16" height="16"></iconify-icon>
                        Perfil
                    </button>
                    <button data-settings-section="seguridad" type="button">
                        <iconify-icon icon="gg:lock" width="16" height="16"></iconify-icon>
                        Seguridad
                    </button>
                </nav>
            </div>

            <!-- Contenido principal -->
            <div class="settings-content">
                <div class="settings-header">
                    <h1>Ajustes de la Cuenta</h1>
                    <p>Configura las opciones de tu perfil, seguridad y preferencias del sistema general.</p>
                </div>

                <div data-settings-content="perfil">
                    @include('dashboard.features.settings.ui.profile-content')
                </div>

                <div data-settings-content="seguridad">
                    @include('dashboard.features.settings.ui.security')
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @vite('resources/js/settings/settings.js')
@endpush

