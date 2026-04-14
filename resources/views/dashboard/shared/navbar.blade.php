<nav class="navbar">
    @php
        $routeName = Route::currentRouteName();

        $breadcrumbs = [
            'dashboard' => [['label' => 'Dashboard', 'url' => route('dashboard')]],
            'controlpanel.home' => [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Panel de control', 'url' => route('controlpanel.home')], // actual
            ],

            'settings.profile' => [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Ajustes', 'url' => route('settings.profile')], // actual
            ],
        ];
    @endphp

    <div class="navbar-left">
        <button class="mobile-toggle icon-btn" data-sidebar-toggle aria-label="Abrir menú">
            <iconify-icon icon="lucide:menu" width="20"></iconify-icon>
        </button>
        @if (isset($breadcrumbs[$routeName]))
            <nav aria-label="breadcrumb">
                @foreach ($breadcrumbs[$routeName] as $item)
                    @if (!$loop->last && $item['url'])
                        <a class="breadcrumb-item" href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                    @else
                        <span class="breadcrumb-item active">{{ $item['label'] }}</span>
                    @endif
                @endforeach
            </nav>
        @endif
    </div>

    <div class="navbar-right">
        <div class="notif-wrapper" onclick="toggleNotificationsNav(event)">
            <button class="icon-btn notif-btn" aria-label="Notificaciones" title="Notificaciones">
                <iconify-icon icon="lucide:bell" width="18"></iconify-icon>
                <span class="badge" aria-hidden="true">2</span>
            </button>

            <div class="drop-notifications notif-dropdown" id="notifDropdown">
                <!-- Configuración del header -->
                <div class="notif-header">
                    <div class="notif-header-title">
                        Notificaciones
                        <span class="notif-count">2 nuevas</span>
                    </div>
                    <button class="notif-mark-read">Marcar todas como leídas</button>
                </div>

                <!-- Cuerpo de notificaciones -->
                <div class="notif-body">
                    <!-- Card 1: Factura vencida (Crítica) [No leída] -->
                    <div class="notif-card unread critical">
                        <div class="notif-icon-wrapper">
                            <iconify-icon icon="lucide:file-warning"></iconify-icon>
                        </div>
                        <div class="notif-content">
                            <div class="notif-top">
                                <div class="notif-title">Factura vencida</div>
                                <span class="notif-time">hace 5 min</span>
                            </div>
                            <p class="notif-desc">La factura #INV-2023 para ClienteCorp ha sobrepasado su fecha de pago
                                por 5 días.</p>
                            <div class="notif-meta">
                                <span class="notif-tag tag-finance">Finanzas</span>
                            </div>
                            <div class="notif-actions">
                                <button class="btn-quick primary">Ver factura</button>
                                <button class="btn-quick">Recordatorio</button>
                            </div>
                        </div>
                        <button class="notif-more" aria-label="Más opciones"><iconify-icon
                                icon="lucide:more-vertical"></iconify-icon></button>
                    </div>

                    <!-- Card 2: Stock bajo (Advertencia) [No leída] -->
                    <div class="notif-card unread warning">
                        <div class="notif-icon-wrapper">
                            <iconify-icon icon="lucide:box"></iconify-icon>
                        </div>
                        <div class="notif-content">
                            <div class="notif-top">
                                <div class="notif-title">Nivel de stock bajo</div>
                                <span class="notif-time">hace 2 horas</span>
                            </div>
                            <p class="notif-desc">El artículo "Procesador AMD Ryzen 9" tiene 3 unidades restantes
                                (mínimo: 10).</p>
                            <div class="notif-meta">
                                <span class="notif-tag tag-inventory">Inventario</span>
                            </div>
                            <div class="notif-actions">
                                <button class="btn-quick primary">Reabastecer</button>
                                <button class="btn-quick">Ignorar</button>
                            </div>
                        </div>
                        <button class="notif-more" aria-label="Más opciones"><iconify-icon
                                icon="lucide:more-vertical"></iconify-icon></button>
                    </div>

                    <!-- Card 3: Nuevo comentario (Informativa) -->
                    <div class="notif-card info">
                        <div class="notif-icon-wrapper">
                            <iconify-icon icon="lucide:message-square"></iconify-icon>
                        </div>
                        <div class="notif-content">
                            <div class="notif-top">
                                <div class="notif-title">Nuevo comentario en proyecto</div>
                                <span class="notif-time">hace 4 horas</span>
                            </div>
                            <p class="notif-desc">Ana López comentó en el proyecto "Migración Cloud Fase 1".</p>
                            <div class="notif-meta">
                                <span class="notif-tag tag-projects">Proyectos</span>
                            </div>
                            <div class="notif-actions">
                                <button class="btn-quick primary">Responder</button>
                                <button class="btn-quick">Ver todos</button>
                            </div>
                        </div>
                        <button class="notif-more" aria-label="Más opciones"><iconify-icon
                                icon="lucide:more-vertical"></iconify-icon></button>
                    </div>

                    <!-- Card 4: Sincronización completada (Éxito) -->
                    <div class="notif-card success">
                        <div class="notif-icon-wrapper">
                            <iconify-icon icon="lucide:check-circle-2"></iconify-icon>
                        </div>
                        <div class="notif-content">
                            <div class="notif-top">
                                <div class="notif-title">Sincronización completada</div>
                                <span class="notif-time">Ayer</span>
                            </div>
                            <p class="notif-desc">La importación de datos bancarios finalizó correctamente.</p>
                            <div class="notif-meta">
                                <span class="notif-tag tag-finance">Finanzas</span>
                            </div>
                            <div class="notif-actions">
                                <button class="btn-quick primary">Ver reporte</button>
                            </div>
                        </div>
                        <button class="notif-more" aria-label="Más opciones"><iconify-icon
                                icon="lucide:more-vertical"></iconify-icon></button>
                    </div>

                    <!-- Card 5: Tarea vence hoy (Advertencia) -->
                    <div class="notif-card warning">
                        <div class="notif-icon-wrapper">
                            <iconify-icon icon="lucide:calendar-clock"></iconify-icon>
                        </div>
                        <div class="notif-content">
                            <div class="notif-top">
                                <div class="notif-title">Tarea vence hoy</div>
                                <span class="notif-time">Ayer</span>
                            </div>
                            <p class="notif-desc">"Revisión de nóminas mensuales" vence a las 18:00 hrs.</p>
                            <div class="notif-meta">
                                <span class="notif-tag tag-hr">Recursos Humanos</span>
                            </div>
                            <div class="notif-actions">
                                <button class="btn-quick primary">Resolver</button>
                                <button class="btn-quick">Posponer</button>
                            </div>
                        </div>
                        <button class="notif-more" aria-label="Más opciones"><iconify-icon
                                icon="lucide:more-vertical"></iconify-icon></button>
                    </div>
                </div>
            </div>
        </div>


        <form class="nav-search" role="search" action="#" method="GET">
            <input type="search" name="q" placeholder="Buscar..." aria-label="Buscar" />
            <button type="submit" class="icon-btn" aria-label="Buscar">
                <iconify-icon icon="lucide:search" width="16"></iconify-icon>
            </button>
        </form>

        <button class="theme-toggle icon-btn" type="button" onclick="themeToggle()" aria-label="Cambiar tema">
            <iconify-icon icon="mynaui:sun" width="16"></iconify-icon>
        </button>

        <div class="profile-menu">
            <button class="user-btn" aria-haspopup="true" aria-expanded="false">
                <span class="avatar">JD</span>
            </button>
           
        </div>
    </div>
</nav>


<script>
    function toggleNotificationsNav(e) {
        if (e && e.stopPropagation) e.stopPropagation();
        const dropdown = document.getElementById('notifDropdown');
        if (dropdown) {
            dropdown.classList.toggle('show');
        }
    }

    // Cerrar dropdown al hacer click fuera
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('notifDropdown');
        if (!e.target.closest('.notif-wrapper')) {
            if (dropdown && dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        }
    });

    function themeToggle() {
        const html = document.documentElement;
        html.classList.toggle('dark-theme');

        const isDark = html.classList.contains('dark-theme');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');

        updateThemeIcons(isDark);
    }

    function updateThemeIcons(isDark) {
        const icons = document.querySelectorAll('.theme-toggle iconify-icon');
        icons.forEach(icon => {
            if (icon && icon.setAttribute) {
                icon.setAttribute('icon', isDark ? 'mynaui:moon' : 'mynaui:sun');
            }
        });
    }

    // Sync icons on page load
    document.addEventListener('DOMContentLoaded', () => {
        const isDark = document.documentElement.classList.contains('dark-theme');
        updateThemeIcons(isDark);
    });
</script>
