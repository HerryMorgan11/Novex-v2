<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Novex</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite(['resources/css/dashboard/sidebar.css'])
    @vite(['resources/css/dashboard/general-dashboard.css'])
    @vite(['resources/css/dashboard/navbar.css'])
    @vite(['resources/css/dashboard/settings-profile.css'])
    @vite(['resources/css/dashboard/control-panel.css'])

    @stack('styles')
    <script>
        // Aplicar tema guardado antes de que el DOM se pinte (evita flash)
        (function() {
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark-theme');
            }
        })();
    </script>
</head>

<body>
    <div class="app" id="app-root">
        {{-- Overlay para cerrar sidebar en móvil --}}
        <div class="sidebar-overlay" id="sidebar-overlay"></div>

        @include('dashboard.shared.sidebar')

        <main class="main-layout">
            <div class="main-panel">
                <nav>
                    @include('dashboard.shared.navbar')
                </nav>
                @yield('content')
            </div>
        </main>
    </div>

    {{-- Modal de creación de empresa (solo si usuario no tiene tenant) --}}
    @if($showModal ?? false)
        @include('dashboard.partials.create-company-modal')
    @endif

    @vite('resources/js/dashboard/sidebar.js')
    @stack('scripts')
</body>

</html>

</body>

</html>