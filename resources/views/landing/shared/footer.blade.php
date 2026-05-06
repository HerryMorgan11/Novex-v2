<footer class="footer-container">
    <div class="footer-elementos">
        <div class="box-logo-description">
            <img src="{{ asset('assets/logo/logo-novex-color.png') }}" height="120" width="120" alt="Novex" class="logo-footer">
            <p class="description-footer">Novex es la plataforma de gestión empresarial que une inventario, finanzas, ventas y personas en un único sistema.</p>
            <div class="iconos">
                <a href="#" aria-label="LinkedIn"><iconify-icon icon="line-md:linkedin" class="icon"></iconify-icon></a>
                <a href="#" aria-label="Twitter"><iconify-icon icon="line-md:twitter" class="icon"></iconify-icon></a>
            </div>
        </div>

        <div class="box-container">
            <div class="box-links">
                <h3 class="title-links">Módulos</h3>
                <ul class="link-box">
                    <li><a href="{{ route('inventario') }}" class="footer-link">Inventario</a></li>
                    <li><a href="{{ route('contabilidad') }}" class="footer-link">Finanzas</a></li>
                    <li><a href="{{ route('crm') }}" class="footer-link">CRM y Ventas</a></li>
                    <li><a href="{{ route('recursos-humanos') }}" class="footer-link">Recursos Humanos</a></li>
                </ul>
            </div>

            <div class="box-links">
                <h3 class="title-links">Empresa</h3>
                <ul class="link-box">
                    <li><a href="{{ url('/') }}#soluciones" class="footer-link">Soluciones</a></li>
                    <li><a href="{{ route('precios') }}" class="footer-link">Precios</a></li>
                    <li><a href="{{ route('register') }}" class="footer-link">Comenzar gratis</a></li>
                    <li><a href="{{ route('login') }}" class="footer-link">Iniciar sesión</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="footer-divider"></div>

    <div class="footer-info">
        <p class="info-footer">© {{ date('Y') }} Novex. Todos los derechos reservados.</p>
        <div class="footer-legal">
            <button onclick="themeToggle()" class="theme-toggle" aria-label="Cambiar tema">
                <iconify-icon icon="mynaui:sun"></iconify-icon>
            </button>
            <a href="#" class="legal-link">Privacidad</a>
            <a href="#" class="legal-link">Términos</a>
        </div>
    </div>
</footer>

<script>
    // Actualizar icono del tema
    function updateThemeIcon() {
        const isDark = document.documentElement.classList.contains('dark-theme');
        document.querySelectorAll('.theme-toggle iconify-icon').forEach(icon => {
            icon.setAttribute('icon', isDark ? 'mynaui:moon' : 'mynaui:sun');
        });
    }

    // Toggle del tema
    function themeToggle() {
        const html = document.documentElement;
        html.classList.toggle('dark-theme');
        const isDark = html.classList.contains('dark-theme');
        
        // Guardar en localStorage
        if (isDark) {
            localStorage.setItem('novex-theme', 'dark');
        } else {
            localStorage.removeItem('novex-theme');
        }
        
        updateThemeIcon();
    }

    // Inicializar icono al cargar la página
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', updateThemeIcon);
    } else {
        updateThemeIcon();
    }
</script>