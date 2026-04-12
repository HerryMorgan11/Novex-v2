<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite(['resources/css/dashboard/sidebar.css'])
    @vite(['resources/css/dashboard/general-dashboard.css'])
    @vite(['resources/css/dashboard/navbar.css'])
    @vite(['resources/css/dashboard/settings-profile.css'])
    @vite(['resources/css/dashboard/control-panel.css'])

    @stack('styles')
    @livewireStyles
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark-theme');
            }
        })();
    </script>
</head>

<body>
    <div class="app" x-data="{ sidebarOpen: false }">
        <!-- Overlay para móvil -->
        <div class="sidebar-overlay" x-show="sidebarOpen" x-on:click="sidebarOpen = false" x-cloak></div>

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

    <!-- Modal de creación de empresa (solo se muestra si usuario no tiene tenant) -->
    @livewire('create-company-modal')

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
    @livewireScripts
</body>

</html>