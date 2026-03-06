<aside class="sidebar" aria-label="Sidebar">
        <!-- Top Section: Logo de marca -->
        <div class="sidebar-top">
          <img src="/assets/logo/logo-novex-color.png" alt="Novex Logo" class="sidebar-logo">
        </div>

        <!-- Secciones: 1ºSeccion Dashboard general -->
        <div class="sidebar-section">
          <div class="sidebar-section-title">General</div>

          <nav class="nav" aria-label="Navegación general">
            <a class="nav-item" href="#">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="lucide:layout-grid" width="16"></iconify-icon>
              </span>
              <span class="nav-label">Dashboard</span>
            </a>

            <a class="nav-item" href="#">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="lucide:list-todo" width="16"></iconify-icon>
              </span>
              <span class="nav-label">Tasks</span>
            </a>

            <a class="nav-item is-active" href="#">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="lucide:box" width="16"></iconify-icon>
              </span>
              <span class="nav-label">Apps</span>
            </a>

            <a class="nav-item" href="#">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="lucide:message-square" width="16"></iconify-icon>
              </span>
              <span class="nav-label">Chats</span>
            </a>

            <a class="nav-item" href="#">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="lucide:users" width="16"></iconify-icon>
              </span>
              <span class="nav-label">Users</span>
            </a>

            <a class="nav-item" href="#">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="lucide:shield-half" width="16"></iconify-icon>
              </span>
              <span class="nav-label">Secured by Clerk</span>
              <iconify-icon icon="lucide:chevron-right" width="16" style="margin-left: auto; color: var(--muted);"></iconify-icon>
            </a>
          </nav>
        </div>

        <!-- Secciones: 2ºSeccion Pages -->
        <div class="sidebar-section">
          <div class="sidebar-section-title">Pages</div>

          <nav class="nav" aria-label="Navegación pages">
            <a class="nav-item" href="#">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="lucide:shield-check" width="16"></iconify-icon>
              </span>
              <span class="nav-label">Auth</span>
              <iconify-icon icon="lucide:chevron-right" width="16" style="margin-left: auto; color: var(--muted);"></iconify-icon>
            </a>

            <a class="nav-item" href="#">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="lucide:bug" width="16"></iconify-icon>
              </span>
              <span class="nav-label">Errors</span>
              <iconify-icon icon="lucide:chevron-right" width="16" style="margin-left: auto; color: var(--muted);"></iconify-icon>
            </a>
          </nav>
        </div>

        <!-- Secciones: 3ºSeccion Other -->
        <div class="sidebar-section">
          <div class="sidebar-section-title">Other</div>

          <nav class="nav" aria-label="Navegación pages">
            <a class="nav-item" href="#">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="lucide:settings" width="16"></iconify-icon>
              </span>
              <span class="nav-label">Settings</span>
              <iconify-icon icon="lucide:chevron-right" width="16" style="margin-left: auto; color: var(--muted);"></iconify-icon>
            </a>

            <a class="nav-item" href="#">
              <span class="nav-icon" aria-hidden="true">
                <iconify-icon icon="lucide:help-circle" width="16"></iconify-icon>
              </span>
              <span class="nav-label">Help Center</span>
            </a>
          </nav>

          <button onclick="themeToggle()" class="theme-toggle nav-item" type="button" aria-label="Cambiar tema">
            <span class="nav-icon" aria-hidden="true">
              <iconify-icon icon="mynaui:sun" width="16"></iconify-icon>
            </span>
            <span class="nav-label">Dark Mode</span>
          </button>
        </div>

        <div class="sidebar-bottom">
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" style="background:none;border:none;padding:0;color:#3490dc;cursor:pointer">Logout</button>
            </form>
        </div>
      </aside>

<script>
    function themeToggle() {
        const body = document.body;
        body.classList.toggle('dark-theme');

        // Cambia el icono del botón
        const themeToggleBtn = document.querySelector('.theme-toggle iconify-icon');
        
        //cambiamos el icono dependiendo del tema actual
        if (body.classList.contains('dark-theme')) {
            themeToggleBtn.setAttribute('icon', 'mynaui:moon');
        } else {
            themeToggleBtn.setAttribute('icon', 'mynaui:sun');
        }
    }
</script>