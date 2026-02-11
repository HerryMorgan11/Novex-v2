---
title: '[Fase 4.1] Layout y Navegación del Dashboard'
labels: fase-4, dashboard, frontend, ui-ux, priority-high
assignees:
milestone: Fase 4 - Dashboard Foundation
---

## 🖥️ Tarea: Crear Layout Principal del Dashboard

### Descripción

Implementar el layout base del dashboard con sidebar navegable, navbar superior y estructura responsive. Este es el foundation sobre el que se construirán todos los módulos del ERP.

### Objetivos

#### Layout Principal

- [ ] Crear `resources/views/dashboard/layouts/app.blade.php`
- [ ] Implementar sidebar colapsable con navegación
- [ ] Implementar navbar superior con funcionalidades
- [ ] Crear layout responsive para dispositivos móviles
- [ ] Configurar estructura CSS/Tailwind para dashboard

#### Sidebar

- [ ] Crear estructura del sidebar con menú principal
- [ ] Implementar iconos para cada módulo
- [ ] Crear funcionalidad de colapso/expansión
- [ ] Indicadores visuales de la página activa
- [ ] Enlace al logo/home del dashboard

#### Navbar Superior

- [ ] Crear componente navbar con breadcrumbs
- [ ] Implementar bell icon con notificaciones
- [ ] Crear dropdown de perfil de usuario
- [ ] Agregar funcionalidades: Logout, Settings, Help
- [ ] Responsive menu toggle para mobile

### Implementación

#### 1. Layout Principal del Dashboard

`resources/views/dashboard/layouts/app.blade.php`

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body class="antialiased bg-gray-50">
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        @include('dashboard.shared.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Navbar -->
            @include('dashboard.shared.navbar')

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
```

#### 2. Sidebar Navegable

`resources/views/dashboard/shared/sidebar.blade.php`

```blade
<div class="hidden md:flex flex-col w-64 bg-gray-800 text-white" x-data="{ sidebarOpen: true }">
    <!-- Sidebar Header -->
    <div class="h-16 flex items-center px-6 border-b border-gray-700">
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <img src="/images/logo-white.svg" alt="Novex" class="h-8 w-auto">
            <span class="ml-2 font-bold text-lg">Novex</span>
        </a>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="group flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
            <svg class="mr-3 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"></path>
                <path d="M3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6z"></path>
                <path d="M14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
            </svg>
            Dashboard
        </a>

        <!-- Inventory Module -->
        <div class="pt-2">
            <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Módulos
            </p>
            <a href="{{ route('inventory.products.index') }}"
               class="group flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('inventory.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                <svg class="mr-3 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path>
                    <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                </svg>
                Inventario
            </a>
        </div>

        <!-- Settings Section -->
        <div class="pt-6 border-t border-gray-700">
            <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Configuración
            </p>
            <a href="{{ route('profile.show') }}"
               class="group flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors text-gray-300 hover:bg-gray-700">
                <svg class="mr-3 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
                Perfil
            </a>
            <a href="{{ route('settings.company') }}"
               class="group flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors text-gray-300 hover:bg-gray-700">
                <svg class="mr-3 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                </svg>
                Configuración
            </a>
        </div>
    </nav>

    <!-- Sidebar Footer -->
    <div class="h-16 border-t border-gray-700 px-4 flex items-center text-sm text-gray-400">
        <span>Novex v2.0</span>
    </div>
</div>

<!-- Mobile Sidebar Overlay -->
<div class="md:hidden fixed inset-0 bg-gray-600 bg-opacity-75 z-40"
     x-show="sidebarOpen"
     @click="sidebarOpen = false">
</div>
```

#### 3. Navbar Superior

`resources/views/dashboard/shared/navbar.blade.php`

```blade
<div class="h-16 bg-white border-b border-gray-200 flex items-center px-6 space-x-4">
    <!-- Mobile Menu Button -->
    <button class="md:hidden text-gray-500 hover:text-gray-700">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    <!-- Breadcrumbs -->
    <div class="flex-1">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                    </a>
                </li>
                @if (isset($breadcrumbs))
                    @foreach ($breadcrumbs as $breadcrumb)
                    <li>
                        <div class="flex items-center">
                            <svg class="text-gray-400 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ $breadcrumb['url'] ?? '#' }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                {{ $breadcrumb['label'] }}
                            </a>
                        </div>
                    </li>
                    @endforeach
                @endif
            </ol>
        </nav>
    </div>

    <!-- Right Side Actions -->
    <div class="flex items-center space-x-4">
        <!-- Notifications -->
        <button class="text-gray-500 hover:text-gray-700 relative">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                0
            </span>
        </button>

        <!-- User Menu -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center text-gray-500 hover:text-gray-700">
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}"
                     alt="{{ auth()->user()->name }}"
                     class="h-8 w-8 rounded-full">
            </button>

            <div x-show="open"
                 @click.away="open = false"
                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-50">
                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Mi Perfil
                </a>
                <a href="{{ route('settings.company') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Configuración
                </a>
                <hr class="my-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
```

### Criterios de Aceptación

- [ ] Layout dashboard responsive y funcional
- [ ] Sidebar con navegación a todos los módulos
- [ ] Navbar superior con breadcrumbs y acciones de usuario
- [ ] Mobile menu funcional en dispositivos pequeños
- [ ] Estilos consistentes usando Tailwind CSS
- [ ] Indicadores visuales de la página activa
- [ ] Componentes compartidos reutilizables

### Testing

```php
// Tests/Feature/Dashboard/DashboardLayoutTest.php
public function test_authenticated_user_sees_dashboard_layout()
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertViewHas('user');
}

public function test_sidebar_shows_all_modules()
{
    // Verificar que los enlaces del sidebar estén presentes
    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertViewIs('dashboard.layouts.app');
}

public function test_unauthenticated_user_redirected_to_login()
{
    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('login'));
}
```

### Dependencias

- ✅ Fase 2: Autenticación y Multi-Tenancy debe estar completa
- Debe completarse antes de Fase 4.2

### Notas Importantes

1. **Responsive Design**: El layout debe ser completamente responsive usando Tailwind CSS
2. **Alpine.js**: Usar Alpine.js para interactividad del sidebar y menús
3. **Breadcrumbs**: Preparar estructura para breadcrumbs dinámicos
4. **Icons**: Usar heroicons o svg inline para los iconos
5. **Color Scheme**: Mantener consistencia con tema del proyecto

---

**Estimación:** 2-3 días  
**Prioridad:** Alta  
**Última actualización:** 2026-02-11
