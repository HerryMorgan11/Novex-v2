<aside id="main-sidebar" class="sidebar" :class="{ 'open': sidebarOpen }" aria-label="Sidebar">
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
            <!-- Inventario -->
            <a class="nav-item" href="{{ route('inventario.index') }}" data-module="inventory">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="streamline-ultimate:warehouse-cart-packages-2-bold"></iconify-icon>
              </span>
              <span class="nav-label">Inventario</span>
            </a>

            <!-- Contabilidad -->
            <a class="nav-item disabled" href="#" :class="{ 'opacity-50': !modules.accounting }">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="material-symbols:finance-rounded"></iconify-icon>
              </span>
              <span class="nav-label">Contabilidad</span>
              <iconify-icon icon="uil:padlock"></iconify-icon>
            </a>

            <!-- Recursos Humanos -->
            <a class="nav-item disabled" href="#" :class="{ 'opacity-50': !modules.hr }">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="formkit:people"></iconify-icon>
              </span>
              <span class="nav-label">Recursos Humanos</span>
              <iconify-icon icon="uil:padlock"></iconify-icon>
            </a>
          </nav>
        </div>

        <!-- Secciones: 2ºSeccion Tools -->
        <div class="sidebar-section">
          <div class="sidebar-section-title">Tools</div>

          <nav class="nav" aria-label="Navegación de herramientas">
            <!-- Notas -->
            <a class="nav-item" href="{{ route('dashboard.features.notes.index') }}">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="lucide:shield-check" width="16"></iconify-icon>
              </span>
              <span class="nav-label">Notas</span>
            </a>

            <!-- Recordatorios -->
            <a class="nav-item" href="{{ route('reminders.index') }}">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="lucide:bell" width="16"></iconify-icon>
              </span>
              <span class="nav-label">Recordatorios</span>
            </a>

            <!-- Calendario -->
            <a class="nav-item" href="{{ route('calendario') }}">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="lucide:calendar" width="16"></iconify-icon>
              </span>
              <span class="nav-label">Calendario</span>
            </a>
          </nav>
        </div>
        
        </div>
        
        <!-- Sidebar Bottom: User Dropdown -->
        <div class="sidebar-bottom">
          <div class="user-dropdown" id="user-dropdown-container">
            <!-- User Button -->
            <button
              id="user-dropdown-btn"
              type="button"
              class="user"
              aria-haspopup="menu"
              aria-expanded="false"
              aria-controls="user-dropdown-panel"
            >
              @php($sidebarUser = auth()->user())
              <div class="avatar">{{ strtoupper(substr($sidebarUser?->name ?? 'U', 0, 1)) }}</div>
              <div class="user-meta">
                <span class="user-name">{{ $sidebarUser?->name ?? 'Usuario' }}</span>
                <span class="user-email">{{ $sidebarUser?->email ?? 'Cuenta' }}</span>
              </div>
              <iconify-icon icon="lucide:chevron-right" width="16" class="user-icon"></iconify-icon>
            </button>

            <!-- Dropdown Panel -->
            <div
              id="user-dropdown-panel"
              class="dropdown-panel"
              role="menu"
              style="display: none"
            >
              <!-- Profile Settings -->
              <a 
                href="{{ route('settings.profile') }}"
                class="dropdown-item"
                role="menuitem"
              >
                <iconify-icon icon="lucide:user" width="16"></iconify-icon>
                <span>Profile Settings</span>
              </a>

              <!-- Control Panel -->
              <a 
                href="{{ route('controlpanel.home') }}"
                class="dropdown-item"
                role="menuitem"
              >
                <iconify-icon icon="lucide:sliders" width="16"></iconify-icon>
                <span>Control Panel</span>
              </a>

              <!-- Logout -->
              <form method="POST" action="{{ url('/logout', [], request()->isSecure()) }}" class="dash-sidebar-logout-form">
                @csrf
                <button 
                  type="submit"
                  class="dropdown-item logout"
                  role="menuitem"
                >
                  <iconify-icon icon="lucide:log-out" width="16"></iconify-icon>
                  <span>Logout</span>
                </button>
              </form>
            </div>
          </div>
        </div>
      </aside>
