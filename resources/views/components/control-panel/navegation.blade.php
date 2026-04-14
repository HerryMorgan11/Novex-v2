{{-- Control Panel - Navegación por secciones del panel de control.
     La sección activa se controla mediante JS (resources/js/controlPanel/navigation.js).
     La lista de usuarios del tenant se recibe desde ControlPanelController. --}}

<div class="panel-container">
    <div class="panel-wrapper">
        <div class="panel-layout">
            <!-- SIDEBAR de navegación -->
            <div class="panel-sidebar">
                <nav class="panel-nav">
                    <button data-panel-section="home" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        Dashboard
                    </button>
                    <button data-panel-section="users" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        Usuarios
                    </button>
                    <button data-panel-section="company" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/></svg>
                        Empresa
                    </button>
                    <button data-panel-section="modules" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                        Módulos
                    </button>
                </nav>
            </div>

            <!-- CONTENT: secciones del panel -->
            <div class="panel-content">
                <div data-panel-content="home">
                    @include('dashboard.features.control-panel.ui.home')
                </div>

                <div data-panel-content="users">
                    @include('dashboard.features.control-panel.ui.users', ['users' => $users ?? []])
                </div>

                <div data-panel-content="company">
                    @include('dashboard.features.control-panel.ui.company')
                </div>

                <div data-panel-content="modules">
                    @include('dashboard.features.control-panel.ui.modules')
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @vite('resources/js/controlPanel/navigation.js')
@endpush
