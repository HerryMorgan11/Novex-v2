<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @vite('resources/css/landing/shared/navbar.css')
    @vite('resources/css/landing/sections/home/header.css')
    @vite('resources/css/landing/sections/home/modules-section.css')
    @vite('resources/css/landing/sections/home/choose.css')
    @vite('resources/css/landing/shared/footer.css')

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
