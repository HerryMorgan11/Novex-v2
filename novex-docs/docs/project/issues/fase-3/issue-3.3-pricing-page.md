---
title: '[Fase 3.3] Implementar Pricing Page'
labels: fase-3, landing, pricing, frontend, priority-high
assignees:
milestone: Fase 3 - Landing Page
---

## Tarea: Implementar Pricing Page

### Descripción

Crear página de precios con planes de suscripción y comparación de features

### Objetivos

- [ ] Diseñar cards de planes (Basic, Pro, Enterprise)
- [ ] Tabla de comparación de features
- [ ] Toggle mensual/anual
- [ ] FAQ de precios
- [ ] CTAs por plan

### Planes

```
Basic - $29/mes
- 5 usuarios
- 1000 productos
- Soporte email
- Reportes básicos

Pro - $79/mes [POPULAR]
- Usuarios ilimitados
- Productos ilimitados
- Soporte prioritario
- API access
- Reportes avanzados

Enterprise - Contactar
- Todo de Pro
- Soporte dedicado
- Customización
- Onboarding
- SLA
```

### Implementación

```blade
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Elige el plan perfecto</h2>

        <!-- Toggle Mensual/Anual -->
        <div class="flex justify-center mb-12">
            <div class="bg-gray-100 rounded-lg p-1">
                <button class="px-4 py-2 rounded-md bg-white">Mensual</button>
                <button class="px-4 py-2 rounded-md">Anual (ahorra 20%)</button>
            </div>
        </div>

        <!-- Cards de Planes -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Plan Basic -->
            <div class="border rounded-lg p-8">
                <h3 class="text-2xl font-bold mb-4">Basic</h3>
                <p class="text-4xl font-bold mb-6">$29<span class="text-lg text-gray-500">/mes</span></p>
                <ul class="mb-8 space-y-3">
                    <li>✓ 5 usuarios</li>
                    <li>✓ 1000 productos</li>
                    <li>✓ Soporte email</li>
                </ul>
                <x-cta-button href="{{ route('register') }}" class="w-full">
                    Empezar
                </x-cta-button>
            </div>

            <!-- Plan Pro (POPULAR) -->
            <div class="border-2 border-indigo-600 rounded-lg p-8 relative">
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-indigo-600 text-white px-4 py-1 rounded-full text-sm">
                    POPULAR
                </div>
                <h3 class="text-2xl font-bold mb-4">Pro</h3>
                <p class="text-4xl font-bold mb-6">$79<span class="text-lg text-gray-500">/mes</span></p>
                <ul class="mb-8 space-y-3">
                    <li>✓ Usuarios ilimitados</li>
                    <li>✓ Productos ilimitados</li>
                    <li>✓ Soporte prioritario</li>
                    <li>✓ API access</li>
                </ul>
                <x-cta-button href="{{ route('register') }}" class="w-full">
                    Empezar
                </x-cta-button>
            </div>

            <!-- Plan Enterprise -->
            <div class="border rounded-lg p-8">
                <h3 class="text-2xl font-bold mb-4">Enterprise</h3>
                <p class="text-4xl font-bold mb-6">Contactar</p>
                <ul class="mb-8 space-y-3">
                    <li>✓ Todo de Pro</li>
                    <li>✓ Soporte dedicado</li>
                    <li>✓ Customización</li>
                    <li>✓ SLA</li>
                </ul>
                <x-cta-button href="#contact" variant="outline" class="w-full">
                    Contactar
                </x-cta-button>
            </div>
        </div>
    </div>
</section>
```

### Criterios de Aceptación

- [ ] 3 planes claramente diferenciados
- [ ] Toggle mensual/anual funcionando
- [ ] Tabla de comparación responsive
- [ ] FAQ con preguntas comunes
- [ ] CTAs direccionando correctamente
- [ ] Responsive en todos los dispositivos

### Estimación

**1 día**

### Dependencias

- Issue 3.1 (Layout) completada
