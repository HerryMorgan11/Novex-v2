<aside class="sidebar" :class="{ 'open': sidebarOpen }" aria-label="Sidebar">
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

        <div class="sidebar-section" x-data="sidebarModules()" @modules-updated.window="loadModules()">
          <div class="sidebar-section-title">Modules</div>

          <nav class="nav" aria-label="Navegación de módulos">
            <!-- Inventario -->
            <a class="nav-item" href="#" x-show="modules.inventory">
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
              id="user-dropdown-button"
              type="button"
              class="user"
            >
              <div class="avatar">DJ</div>
              <div class="user-meta">
                <span class="user-name">David Jacobo</span>
                <span class="user-email">Admin</span>
              </div>
              <iconify-icon icon="lucide:chevron-right" width="16" class="user-icon"></iconify-icon>
            </button>

            <!-- Dropdown Panel -->
            <div
              id="user-dropdown-panel"
              class="dropdown-panel"
              style="display: none;"
            >
              <!-- Profile Settings -->
              <a 
                href="{{ route('settings.profile') }}"
                class="dropdown-item"
              >
                <iconify-icon icon="lucide:user" width="16"></iconify-icon>
                <span>Profile Settings</span>
              </a>

              <!-- Control Panel -->
              <a 
                href="{{ route('controlpanel.home') }}"
                class="dropdown-item"
              >
                <iconify-icon icon="lucide:sliders" width="16"></iconify-icon>
                <span>Control Panel</span>
              </a>

              <!-- Logout -->
              <form method="POST" action="{{ route('logout') }}" style="display: contents;">
                @csrf
                <button 
                  type="submit"
                  class="dropdown-item logout"
                >
                  <iconify-icon icon="lucide:log-out" width="16"></iconify-icon>
                  <span>Logout</span>
                </button>
              </form>
            </div>
          </div>
        </div>
      </aside>

<script>
  function sidebarModules() {
    return {
      modules: {
        inventory: true,
        accounting: true,
        hr: true
      },
      init() {
        this.loadModules();
      },
      loadModules() {
        const saved = localStorage.getItem('novex_modules');
        if (saved) {
          this.modules = JSON.parse(saved);
        }
      }
    }
  }

  // User Dropdown functionality
  document.addEventListener('DOMContentLoaded', function() {
    const button = document.getElementById('user-dropdown-button');
    const panel = document.getElementById('user-dropdown-panel');
    const container = document.getElementById('user-dropdown-container');

    if (!button || !panel) return;

    // Toggle dropdown on button click
    button.addEventListener('click', function(e) {
      e.stopPropagation();
      const isOpen = panel.style.display !== 'none';
      panel.style.display = isOpen ? 'none' : 'block';
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
      const isClickInside = container.contains(e.target);
      if (!isClickInside && panel.style.display !== 'none') {
        panel.style.display = 'none';
      }
    });

    // Close dropdown on Escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && panel.style.display !== 'none') {
        panel.style.display = 'none';
        button.focus();
      }
    });
  });
</script>
