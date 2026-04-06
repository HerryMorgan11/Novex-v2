<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calendario - Novex ERP</title>
    
    <!-- Configuración e Integración de activos CSS/JS base mediante Build Tools (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite(['resources/css/dashboard/sidebar.css'])
    @vite(['resources/css/dashboard/general-dashboard.css'])
    @vite(['resources/css/dashboard/navbar.css'])
    
    <!-- Cargadores de módulo específicos para optimizar la carga del calendario bajo demanda -->
    @vite(['resources/css/dashboard/features/calendario.css', 'resources/js/dashboard/features/calendario.js'])

    <!-- Integración con la capa de reactividad de backend Livewire -->
    @livewireStyles

    <!-- Script de configuración inicial para persistencia de tema corporativo (Modo Oscuro/Claro) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark-theme');
            }
        })();
    </script>
</head>
<body>
    <!-- Nodo raíz principal que inicializa el layout general de la aplicación  -->
    <div class="app">
        
        <!-- Componente estático: Barra de control de Módulos (Sidebar) -->
        @include('dashboard.shared.sidebar')

        <!-- Estructura central en cascada para la delegación del panel principal -->
        <main class="main-layout">
            <div class="main-panel">
                
                <!-- Barra superior de búsqueda global y perfiles -->
                <nav>
                    @include('dashboard.shared.navbar')
                </nav>
                
                <!-- Subnodo del dominio funcional: Instancia de FullCalendar -->
                <div class="calendar-wrapper" style="height: calc(100vh - 100px); padding-top: 20px;">
                    
                    <!-- Elemento ancla objetivo manipulado por Javascript Vanilla (@fullcalendar/core) -->
                    <div id="calendar"></div>

                    <!-- 
                        Capa de abstracción UI: Diálogo nativo modal HTML5.
                        Independizado de la tabla de estilos del motor con posicionamiento centralizado. 
                    -->
                    <dialog id="eventModal" class="event-modal">
                        <h3 id="modalTitle">Nuevo Evento</h3>
                        <input type="text" id="eventTitleInput" placeholder="Título del evento..." class="modal-input" autocomplete="off" />
                        
                        <!-- Panel de acciones CRUD conectadas al State Manager de calendario.js -->
                        <div class="modal-actions">
                            <button id="btnDelete" class="modal-btn btn-delete" style="display: none;">Eliminar</button>
                            <button id="btnCancel" class="modal-btn btn-cancel">Cancelar</button>
                            <button id="btnSave" class="modal-btn btn-save">Guardar</button>
                        </div>
                    </dialog>
                    
                </div>
            </div>
        </main>
    </div>

    <!-- Integración de motor de Frontend Alpine.js requerido para la interactividad de menús estáticos del Sidebar -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Scripts compilados inyectables de Livewire -->
    @livewireScripts
</body>
</html>
