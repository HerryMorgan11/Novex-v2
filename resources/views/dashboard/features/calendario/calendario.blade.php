<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario - Novex ERP</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite(['resources/css/dashboard/sidebar.css'])
    @vite(['resources/css/dashboard/general-dashboard.css'])
    @vite(['resources/css/dashboard/navbar.css'])
    @vite(['resources/css/dashboard/features/calendario.css', 'resources/js/dashboard/features/calendario.js'])

    <script>
        (function() {
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark-theme');
            }
        })();
    </script>
</head>
<body>
    <div class="app" id="app-root">
        <div class="sidebar-overlay" id="sidebar-overlay"></div>

        @include('dashboard.shared.sidebar')

        <main class="main-layout">
            <div class="main-panel">
                <nav>
                    @include('dashboard.shared.navbar')
                </nav>

                <div class="calendar-wrapper">
                    <div id="calendar"></div>

                    <dialog id="eventModal" class="event-modal">
                        <h3 id="modalTitle">Nuevo Evento</h3>
                        <input type="text" id="eventTitleInput" placeholder="Título del evento..." class="modal-input" autocomplete="off" />
                        <div class="modal-actions">
                            <button id="btnDelete" class="modal-btn btn-delete hidden">Eliminar</button>
                            <button id="btnCancel" class="modal-btn btn-cancel">Cancelar</button>
                            <button id="btnSave" class="modal-btn btn-save">Guardar</button>
                        </div>
                    </dialog>
                </div>
            </div>
        </main>
    </div>

    @vite('resources/js/dashboard/sidebar.js')
</body>
</html>
