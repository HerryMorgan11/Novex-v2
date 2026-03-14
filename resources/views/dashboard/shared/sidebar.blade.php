<aside class="sidebar" aria-label="Sidebar">
        <!-- Top Section: Logo de marca -->
        <div class="sidebar-top">
          <img src="/assets/logo/logo-novex-color.png" alt="Novex Logo" class="sidebar-logo">
        </div>

        <div class="sidebar-body">
        <!-- Secciones: 1ºSeccion Dashboard general -->
        <div class="sidebar-section">
          <div class="sidebar-section-title">General</div>

          <nav class="nav" aria-label="Navegación general">
            <a class="nav-item" href="{{ route('dashboard') }}">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="lucide:layout-grid" width="16"></iconify-icon>
              </span>
              <span class="nav-label">Dashboard</span>
            </a>

          </nav>
        </div>

        <div class="sidebar-section">
          <div class="sidebar-section-title">Modules</div>

          <nav class="nav" aria-label="Navegación de módulos">
            <a class="nav-item" href="#">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="streamline-ultimate:warehouse-cart-packages-2-bold"></iconify-icon>
              </span>
              <span class="nav-label">Inventario</span>
            </a>

            <a class="nav-item is-active" href="#">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="material-symbols:finance-rounded"></iconify-icon>
              </span>
              <span class="nav-label">Contabilidad</span>
            </a>

            <a class="nav-item" href="#">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="formkit:people"></iconify-icon>
              </span>
              <span class="nav-label">Recursos Humanos</span>
            </a>
          </nav>
        </div>

        <!-- Secciones: 2ºSeccion Tools -->
        <div class="sidebar-section">
          <div class="sidebar-section-title">Tools</div>

          <nav class="nav" aria-label="Navegación de herramientas">
            <!-- Centro de trabajo general abarca todas herramientas de abajo en modelo de vista general -->
            <a class="nav-item" href="#">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="lucide:bug" width="16"></iconify-icon>
              </span>
              <span class="nav-label">WorkCenter</span>
              <iconify-icon icon="lucide:chevron-right" width="16" style="margin-left: auto; color: var(--muted);"></iconify-icon>
            </a>
            
            <!-- Notas -->
            <a class="nav-item" href="#">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="lucide:shield-check" width="16"></iconify-icon>
              </span>
              <span class="nav-label">Notas</span>
              <iconify-icon icon="lucide:chevron-right" width="16" style="margin-left: auto; color: var(--muted);"></iconify-icon>
            </a>

            <!-- Trello para organización de tareas -->
            <a class="nav-item" href="#">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="lucide:bug" width="16"></iconify-icon>
              </span>
              <span class="nav-label">Trello</span>
              <iconify-icon icon="lucide:chevron-right" width="16" style="margin-left: auto; color: var(--muted);"></iconify-icon>
            </a>

            <!-- Recordatorios -->
            <a class="nav-item" href="#">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="lucide:bug" width="16"></iconify-icon>
              </span>
              <span class="nav-label">Recordatorios</span>
              <iconify-icon icon="lucide:chevron-right" width="16" style="margin-left: auto; color: var(--muted);"></iconify-icon>
            </a>

            <!-- Calendario -->
            <a class="nav-item" href="#">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="lucide:bug" width="16"></iconify-icon>
              </span>
              <span class="nav-label">Calendario</span>
              <iconify-icon icon="lucide:chevron-right" width="16" style="margin-left: auto; color: var(--muted);"></iconify-icon>
            </a>
          </nav>
        </div>
        
        </div>
        
        <!-- Sidebar Bottom -->
          <div class="sidebar-section">
            <div class="sidebar-section-title">Other</div>

            <nav class="nav" aria-label="Navegación pages">
              <a class="nav-item" href="{{ route('controlpanel.home') }}">
                <span class="nav-icon" aria-hidden="true">
                  <iconify-icon icon="mingcute:dashboard-3-line"></iconify-icon>
                </span>
                <span class="nav-label">Control Panel</span>
                
              </a>

              <a class="nav-item" href="{{ route('settings.profile') }}">
                <span class="nav-icon" aria-hidden="true">
                  <iconify-icon icon="lucide:settings" width="16"></iconify-icon>
                </span>
                <span class="nav-label">Settings</span>
                
              </a>

              <a class="nav-item" href="#">
                <span class="nav-icon" aria-hidden="true">
                  <iconify-icon icon="lucide:help-circle" width="16"></iconify-icon>
                </span>
                <span class="nav-label">Help Center</span>
              </a>
            </nav>
         
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" style="background:none;border:none;padding:0;color:#3490dc;cursor:pointer">Logout</button>
            </form>
        </div>
      </aside>
