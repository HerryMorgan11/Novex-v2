---
title: '[Fase 3.1] Layout y Componentes de Landing'
labels: fase-3, landing, frontend, ui-ux, priority-medium
assignees:
milestone: Fase 3 - Landing Page
---

## Tarea: Completar Layout y Componentes de Landing

### Descripción

Completar el layout principal de la landing page y crear componentes compartidos reutilizables

### Objetivos

#### Layout

- [ ] Completar `landing/layout/app.blade.php`
- [ ] Implementar navbar responsive
- [ ] Implementar footer completo
- [ ] Configurar Tailwind CSS con tema personalizado

#### Componentes

- [ ] Crear componente de navbar con menú mobile
- [ ] Crear componente de footer con enlaces
- [ ] Crear componentes de botones CTA
- [ ] Crear componente de cards
- [ ] Implementar dark mode toggle (opcional)

### Implementación

#### 1. Layout Principal

`resources/views/landing/layout/app.blade.php`

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Novex ERP - Gestiona tu negocio')</title>

    <!-- SEO -->
    <meta name="description" content="@yield('description', 'ERP completo para gestionar inventario, ventas, CRM y más')">
    <meta name="keywords" content="@yield('keywords', 'ERP, inventario, ventas, CRM')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/landing.css', 'resources/js/landing.js'])
    @stack('styles')
</head>
<body class="antialiased">
    @include('landing.shared.navbar')

    <main>
        @yield('content')
    </main>

    @include('landing.shared.footer')

    @stack('scripts')
</body>
</html>
```

#### 2. Navbar Responsive

`resources/views/landing/shared/navbar.blade.php`

```blade
<nav class="bg-white shadow-sm" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="/images/logo.svg" alt="Novex" class="h-8 w-auto">
                    <span class="ml-2 text-xl font-bold text-gray-900">Novex</span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-gray-900">Inicio</a>
                <a href="#features" class="text-gray-700 hover:text-gray-900">Características</a>
                <a href="{{ route('pricing') }}" class="text-gray-700 hover:text-gray-900">Precios</a>
                <a href="#contact" class="text-gray-700 hover:text-gray-900">Contacto</a>
            </div>

            <!-- CTA Buttons -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">
                    Iniciar sesión
                </a>
                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    Empezar gratis
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="mobileMenuOpen" class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-700">Inicio</a>
            <a href="#features" class="block px-3 py-2 text-gray-700">Características</a>
            <a href="{{ route('pricing') }}" class="block px-3 py-2 text-gray-700">Precios</a>
            <a href="#contact" class="block px-3 py-2 text-gray-700">Contacto</a>
            <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-700">Iniciar sesión</a>
            <a href="{{ route('register') }}" class="block px-3 py-2 text-indigo-600 font-medium">Empezar gratis</a>
        </div>
    </div>
</nav>
```

#### 3. Footer

`resources/views/landing/shared/footer.blade.php`

```blade
<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Company -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Novex ERP</h3>
                <p class="text-gray-400">
                    La solución completa para gestionar tu negocio.
                </p>
            </div>

            <!-- Product -->
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider mb-4">Producto</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 hover:text-white">Características</a></li>
                    <li><a href="{{ route('pricing') }}" class="text-gray-400 hover:text-white">Precios</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Demo</a></li>
                </ul>
            </div>

            <!-- Company -->
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider mb-4">Empresa</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 hover:text-white">Acerca de</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Blog</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Contacto</a></li>
                </ul>
            </div>

            <!-- Legal -->
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider mb-4">Legal</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 hover:text-white">Privacidad</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Términos</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-8 border-t border-gray-800 pt-8 text-center text-gray-400">
            <p>&copy; {{ date('Y') }} Novex ERP. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>
```

#### 4. Componente de Botón CTA

`resources/views/landing/shared/cta-button.blade.php`

```blade
@props([
    'href' => '#',
    'variant' => 'primary', // primary, secondary, outline
    'size' => 'md', // sm, md, lg
])

@php
$classes = [
    'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700',
    'secondary' => 'bg-gray-200 text-gray-900 hover:bg-gray-300',
    'outline' => 'border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-50',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-base',
    'lg' => 'px-6 py-3 text-lg',
];

$class = ($classes[$variant] ?? $classes['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => "inline-block rounded-md font-medium transition {$class}"]) }}>
    {{ $slot }}
</a>
```

#### 5. Tailwind Config

`tailwind.config.js`

```javascript
export default {
    content: ['./resources/**/*.blade.php', './resources/**/*.js', './resources/**/*.vue'],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#eef2ff',
                    500: '#6366f1',
                    600: '#4f46e5',
                    700: '#4338ca',
                },
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
            },
        },
    },
    plugins: [],
};
```

### Criterios de Aceptación

- [ ] Layout responsive en todos los dispositivos (mobile, tablet, desktop)
- [ ] Navbar con menú hamburguesa en mobile funcionando
- [ ] Footer con todos los enlaces
- [ ] Componentes reutilizables documentados
- [ ] Tailwind configurado con tema personalizado
- [ ] Animaciones suaves en transiciones
- [ ] Accesible (WCAG 2.1 AA)
- [ ] Dark mode funcionando (si implementado)

### Referencias

- Tailwind CSS: https://tailwindcss.com
- Alpine.js: https://alpinejs.dev
- `/docs/landingDesign.md`

### Estimación

**2 días**

### Dependencias

Ninguna - puede desarrollarse en paralelo

### Notas

Usar Alpine.js para interactividad simple (menús, toggles) en lugar de JavaScript pesado.
