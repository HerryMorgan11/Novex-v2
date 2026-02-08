<div class="container-navbar">
    <!-- Logo coorporativo -->
    <p>ImagenLogo</p>

    <!-- Enlaces a las subpaginas -->
    <nav class="nav-links">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('pricing') }}">Pricing</a>
    </nav>
    <!-- Enlaces a la autentificacion -->
    <div class="auth-links">
        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}">Register</a>
    </div>
</div>