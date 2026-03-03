---
title: '[Fase 4.3] Dashboard Home con Widgets y Estadísticas'
labels: fase-4, dashboard, frontend, widgets, priority-high
assignees:
milestone: Fase 4 - Dashboard Foundation
---

## Tarea: Implementar Dashboard Home con Widgets

### Descripción

Crear la página principal del dashboard con widgets de estadísticas, gráficos básicos y lista de actividad reciente. Esta página servirá como punto de entrada para todos los usuarios del sistema.

### Objetivos

#### Página Principal

- [ ] Crear vista `resources/views/dashboard/home.blade.php`
- [ ] Diseñar layout con grid responsive
- [ ] Implementar header con título y fecha/hora
- [ ] Crear sección de widgets de estadísticas

#### Widgets de Estadísticas

- [ ] Widget: Total de Productos
- [ ] Widget: Ventas del Mes
- [ ] Widget: Stock Bajo
- [ ] Widget: Clientes Activos
- [ ] Widget: Órdenes Pendientes (futuro)
- [ ] Widget: Movimientos del Día

#### Gráficos

- [ ] Gráfico de ventas mensual (Chart.js)
- [ ] Gráfico de productos más vendidos
- [ ] Gráfico de categorías más activas
- [ ] Gráfico de tendencia de inventario

#### Actividad Reciente

- [ ] Lista de últimos movimientos
- [ ] Últimos productos agregados
- [ ] Últimas transacciones

### Implementación

#### 1. Dashboard Controller

`app/Http/Controllers/Dashboard/DashboardController.php`

```php
<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Modules\Inventory\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Inventory\Domain\Repositories\StockRepositoryInterface;

class DashboardController extends Controller
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private StockRepositoryInterface $stockRepository,
    ) {}

    public function index(): View
    {
        // Obtener datos para los widgets
        $totalProducts = $this->productRepository->count();
        $lowStockCount = $this->stockRepository->getLowStockCount();

        // Obtener datos para gráficos
        $monthlySalesData = $this->getMonthlySalesData();
        $topProductsData = $this->getTopProductsData();
        $recentActivities = $this->getRecentActivities();

        return view('dashboard.home', [
            'totalProducts' => $totalProducts,
            'lowStockCount' => $lowStockCount,
            'monthlySalesData' => $monthlySalesData,
            'topProductsData' => $topProductsData,
            'recentActivities' => $recentActivities,
        ]);
    }

    private function getMonthlySalesData(): array
    {
        // Implementar lógica para obtener datos de ventas
        return [];
    }

    private function getTopProductsData(): array
    {
        // Implementar lógica para obtener productos más vendidos
        return [];
    }

    private function getRecentActivities(): array
    {
        // Implementar lógica para obtener actividad reciente
        return [];
    }
}
```

#### 2. Dashboard Home View

`resources/views/dashboard/home.blade.php`

```blade
@extends('dashboard.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            Bienvenido, {{ auth()->user()->name }}
        </h1>
        <p class="mt-2 text-gray-600">
            {{ now()->locale('es')->format('l, j \\de F \\de Y') }}
        </p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Products Widget -->
        <x-card class="relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 h-20 w-20 bg-blue-100 rounded-full opacity-50"></div>

            <div class="relative">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total de Productos</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalProducts }}</p>
                    </div>
                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8-4m-8 4v10l8-4m0-10l-8-4"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4">
                    <span class="text-green-600 font-semibold">+12</span> vs mes anterior
                </p>
            </div>
        </x-card>

        <!-- Sales This Month Widget -->
        <x-card class="relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 h-20 w-20 bg-green-100 rounded-full opacity-50"></div>

            <div class="relative">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Ventas Este Mes</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">$45,200</p>
                    </div>
                    <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4">
                    <span class="text-green-600 font-semibold">+8.5%</span> vs mes anterior
                </p>
            </div>
        </x-card>

        <!-- Low Stock Widget -->
        <x-card class="relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 h-20 w-20 bg-yellow-100 rounded-full opacity-50"></div>

            <div class="relative">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Stock Bajo</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $lowStockCount }}</p>
                    </div>
                    <div class="h-12 w-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M6.228 6.228l1.414 1.414m2.828-2.828l1.414 1.414m2.828-2.828l1.414 1.414m-1.414 1.414l1.414 1.414m2.828-2.828l1.414 1.414m-8.486 8.486l1.414 1.414m2.828-2.828l1.414 1.414"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4">
                    <a href="{{ route('inventory.products.index') }}?status=low-stock" class="text-yellow-600 hover:text-yellow-700 font-semibold">
                        Ver productos →
                    </a>
                </p>
            </div>
        </x-card>

        <!-- Active Customers Widget -->
        <x-card class="relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 h-20 w-20 bg-purple-100 rounded-full opacity-50"></div>

            <div class="relative">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Clientes Activos</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">328</p>
                    </div>
                    <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4">
                    <span class="text-green-600 font-semibold">+24</span> vs mes anterior
                </p>
            </div>
        </x-card>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Sales Chart -->
        <x-card class="lg:col-span-1">
            <x-slot:header>
                <h3 class="text-lg font-semibold">Ventas del Mes</h3>
            </x-slot>

            <canvas id="salesChart" height="300"></canvas>
        </x-card>

        <!-- Top Products -->
        <x-card>
            <x-slot:header>
                <h3 class="text-lg font-semibold">Productos Más Vendidos</h3>
            </x-slot>

            <div class="space-y-4">
                @forelse($topProductsData as $product)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">{{ $product['name'] }}</p>
                            <p class="text-xs text-gray-500">{{ $product['sales'] }} ventas</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">${{ $product['revenue'] }}</p>
                            <div class="w-20 bg-gray-200 rounded-full h-2 mt-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $product['percentage'] }}%"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Sin datos disponibles</p>
                @endforelse
            </div>
        </x-card>
    </div>

    <!-- Recent Activity -->
    <x-card>
        <x-slot:header>
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">Actividad Reciente</h3>
                <a href="#" class="text-indigo-600 hover:text-indigo-700 text-sm font-semibold">Ver todo →</a>
            </div>
        </x-slot>

        <div class="space-y-4">
            @forelse($recentActivities as $activity)
                <div class="flex items-start space-x-3 pb-4 border-b border-gray-200 last:pb-0 last:border-b-0">
                    <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $activity['description'] }}</p>
                        <p class="text-xs text-gray-500">{{ $activity['created_at']->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">Sin actividad reciente</p>
            @endforelse
        </div>
    </x-card>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                datasets: [{
                    label: 'Ventas',
                    data: [12000, 19000, 15000, 25000, 22000, 30000, 28000, 32000, 35000, 38000, 40000, 45200],
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#4F46E5',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
```

#### 3. Routes

`routes/web.php`

```php
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
```

### Criterios de Aceptación

- [ ] Página dashboard home creada y funcional
- [ ] 4 widgets de estadísticas mostrando datos correctos
- [ ] Gráficos Chart.js renderizando correctamente
- [ ] Lista de actividad reciente mostrando datos
- [ ] Diseño responsive en todos los tamaños
- [ ] Datos actualizados en tiempo real (opcional con Livewire)
- [ ] Estilos consistentes con el dashboard

### Testing

```php
// Tests/Feature/Dashboard/DashboardTest.php
public function test_authenticated_user_can_view_dashboard()
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertViewIs('dashboard.home');
}

public function test_dashboard_contains_all_widgets()
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertViewHas('totalProducts');
    $response->assertViewHas('lowStockCount');
    $response->assertViewHas('monthlySalesData');
    $response->assertViewHas('topProductsData');
}

public function test_unauthenticated_user_cannot_view_dashboard()
{
    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('login'));
}
```

### Dependencias

- ✅ Fase 4.1: Layout Dashboard
- ✅ Fase 4.2: Componentes Compartidos
- Fase 5: Módulo Inventario (para datos reales de productos)

### Notas Importantes

1. **Datos Iniciales**: Los datos mostrados pueden ser placeholders hasta tener Fase 5 implementada
2. **Chart.js**: Usar CDN o npm para Chart.js
3. **Performance**: Optimizar queries para no tener N+1 problems
4. **Real-time Updates**: Preparar estructura para actualización en tiempo real con Livewire (futuro)
5. **Permisos**: Solo mostrar datos que el usuario tenga permiso de ver

---

**Estimación:** 2-3 días  
**Prioridad:** Alta  
**Última actualización:** 2026-02-11
