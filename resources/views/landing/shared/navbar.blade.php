<nav class="navbar">
    <div class="navbar-container">
        <!-- Logo -->
        <div class="navbar-logo">
            <img src="{{ asset('assets/logo/logo-novex-color.png') }}" alt="Novex" class="logo-img">
        </div>

        <!-- Menu items -->
        <div class="navbar-menu">            
            <a href="#productos" class="navbar-link">Productos</a>
            <a href="#soluciones" class="navbar-link">Soluciones</a>
            <a href="#precios" class="navbar-link">Precios</a>
            <a href="#acerca-de" class="navbar-link">Acerca de</a>
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


