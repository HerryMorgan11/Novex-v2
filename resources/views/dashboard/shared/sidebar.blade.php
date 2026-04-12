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
            <a class="nav-item" href="{{ route('calendario') }}">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="lucide:calendar" width="16"></iconify-icon>
              </span>
              <span class="nav-label">Calendario</span>
              <iconify-icon icon="lucide:chevron-right" width="16" style="margin-left: auto; color: var(--muted);"></iconify-icon>
            </a>
          </nav>
        </div>
        
        </div>
        
        <!-- Sidebar Bottom -->
          <div class="sidebar-section">
            <div
              x-data="{
                open: false,
                toggle() {
                  if (this.open) {
                    return this.close()
                  }
                  this.$refs.button.focus()
                  this.open = true
                },
                close(focusAfter) {
                  if (!this.open) return
                  this.open = false
                  focusAfter && focusAfter.focus()
                }
              }"
              x-on:keydown.escape.prevent.stop="close($refs.button)"
              x-on:focusin.window="!$refs.panel.contains($event.target) && close()"
              x-id="['user-dropdown']"
              class="user-dropdown"
            >
              <!-- User Button -->
              <button
                x-ref="button"
                x-on:click="toggle()"
                :aria-expanded="open"
                :aria-controls="$id('user-dropdown')"
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
                x-ref="panel"
                x-show="open"
                x-transition.origin.bottom.left.duration.150ms
                x-on:click.outside="close($refs.button)"
                :id="$id('user-dropdown')"
                x-cloak
                class="dropdown-panel"
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
</script>
