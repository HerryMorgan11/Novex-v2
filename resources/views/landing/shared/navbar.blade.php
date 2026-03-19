<nav class="navbar">
    <div class="navbar-container">
        <!-- Logo -->
        <div class="navbar-logo">
            <a href="{{ route('home') }}">
                <img src="{{ asset('assets/logo/logo-novex-color.png') }}" alt="Novex" class="logo-img">
            </a>
        </div>

        <!-- Menu items -->
        <div class="navbar-menu">
            <div class="navbar-link-arrow" onclick="toggleProductsMenu(event)">
                <button class="product-btn">Productos</button>
                <iconify-icon icon="material-symbols:arrow-back-ios-new-rounded" class="arrow-icon"></iconify-icon>

                <div class="drop-productos">
                    <a href="#producto1" class="navbar-link">Producto1</a>
                </div>
            </div>
            <a href="#soluciones" class="navbar-link">Soluciones</a>
            <a href="{{ route('precios') }}" class="navbar-link">Precios</a>
            <a href="{{ route('about') }}" class="navbar-link">Acerca de</a>
            <a href="{{ route('contabilidad') }}" class="navbar-link">Contabilidad</a>
            <a href="{{ route('recursos-humanos') }}" class="navbar-link">Recursos Humanos</a>
        </div>

        <!-- Right side actions -->
        <div class="navbar-actions">
            <!-- Login button -->
            <a href="{{ route('login') }}" class="btn-login">Iniciar Sesión</a>

            <!-- Get Started button -->
            <a href="{{ route('register') }}" class="btn-get-started">Comenzar</a>
        </div>
    </div>
</nav>

<script>
    function toggleProductsMenu(event) {
        if (event && event.stopPropagation) event.stopPropagation();
        const menu = document.querySelector('.drop-productos');
        if (menu) menu.classList.toggle('show');

        const arrowIcon = document.querySelector('.arrow-icon');
        if (arrowIcon) arrowIcon.classList.toggle('open');
    }

    // Cierra el menú si el usuario hace click fuera de él
    window.addEventListener('click', function(e) {
        const menu = document.querySelector('.drop-productos');
        if (!e.target.closest('.navbar-link-arrow')) {
            if (menu && menu.classList.contains('show')) {
                menu.classList.remove('show');
            }
        }

        // Asegura que la flecha vuelva a su estado original
        const arrowIcon = document.querySelector('.arrow-icon');
        if (arrowIcon) arrowIcon.classList.remove('open');
    });

    // Agrega/quita la clase .navbar--solid según el scroll y el hero
    function updateNavbarSolid() {
        const nav = document.querySelector('.navbar');
        const hero = document.querySelector('.hero-section');
        const threshold = hero ? (hero.offsetHeight - 20) : 80;
        if (!nav) return;
        if (window.scrollY > threshold) {
            nav.classList.add('navbar--solid');
        } else {
            nav.classList.remove('navbar--solid');
        }
    }

    window.addEventListener('scroll', updateNavbarSolid, {
        passive: true
    });
    document.addEventListener('DOMContentLoaded', updateNavbarSolid);
</script>
