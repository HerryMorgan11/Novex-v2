<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Novex</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        // Inicializar tema oscuro desde localStorage ANTES de renderizar
        if (localStorage.getItem('novex-theme') === 'dark') {
            document.documentElement.classList.add('dark-theme');
        }
    </script>
    @vite('resources/css/app.css')
    @vite('resources/css/landing/general-style.css')
    @vite('resources/js/app.js')
    @vite('resources/css/landing/shared/navbar.css')
    @vite('resources/css/landing/sections/home/header.css')
    @vite('resources/css/landing/sections/home/modules-section.css')
    @vite('resources/css/landing/sections/home/scale-fast.css')
    @vite('resources/css/landing/sections/home/choose.css')
    @vite('resources/css/landing/sections/precios.css')
    @vite('resources/css/landing/sections/about.css')
    @vite('resources/css/landing/shared/footer.css')
    @stack('styles')
</head>

<body>
    <!-- Navbar -->
    @include('landing.shared.navbar')

    <!-- Contenido de todas las subpaginas -->
    @yield('content')

    <!-- Footer -->
    @include('landing.shared.footer')
</body>

</html>
