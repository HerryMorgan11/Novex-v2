---
title: '[Fase 5.5] CRUD Productos con Componentes Livewire'
labels: fase-5, inventory, frontend, livewire, priority-high
assignees:
milestone: Fase 5 - Módulo Inventario
---

## 🎨 Tarea: Crear CRUD Completo de Productos con Livewire

### Descripción

Implementar las vistas y componentes Livewire para el CRUD completo de productos. Incluye listado con búsqueda/filtros, formulario de crear/editar, vista de detalle y gestión de stock.

### Objetivos

#### Vistas Blade

- [ ] `resources/views/inventory/products/index.blade.php` - Listado
- [ ] `resources/views/inventory/products/create.blade.php` - Crear
- [ ] `resources/views/inventory/products/edit.blade.php` - Editar
- [ ] `resources/views/inventory/products/show.blade.php` - Detalle

#### Componentes Livewire

- [ ] ProductTable component (listado con búsqueda/filtros)
- [ ] ProductForm component (formulario reutilizable)
- [ ] ProductVariantManager component
- [ ] StockAdjuster component
- [ ] ProductSearch component (búsqueda en vivo)

#### Funcionalidades

- [ ] CRUD completo de productos
- [ ] Búsqueda en vivo
- [ ] Filtrado por categoría
- [ ] Gestión de variantes (opcional)
- [ ] Ajuste de stock
- [ ] Validación en tiempo real
- [ ] Paginación

### Implementación

#### 1. Vista de Listado

`resources/views/inventory/products/index.blade.php`

```blade
@extends('dashboard.layouts.app')

@section('title', 'Productos')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Productos</h1>
                <p class="mt-2 text-gray-600">Gestiona tu inventario de productos</p>
            </div>
            <a href="{{ route('inventory.products.create') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 font-medium">
                + Nuevo Producto
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text"
                       placeholder="Buscar por nombre o SKU..."
                       class="form-input rounded-md border-gray-300"
                       wire:model.debounce-500ms="search">

                <select class="form-select rounded-md border-gray-300" wire:model="categoryId">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <select class="form-select rounded-md border-gray-300" wire:model="status">
                    <option value="">Todos los estados</option>
                    <option value="active">Activo</option>
                    <option value="inactive">Inactivo</option>
                </select>

                <button wire:click="resetFilters" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300">
                    Limpiar Filtros
                </button>
            </div>
        </div>

        <!-- Table -->
        @livewire('inventory.products.product-table', ['products' => $products])
    </div>
@endsection
```

#### 2. Componente Livewire: ProductTable

`app/Http/Livewire/Inventory/Products/ProductTable.php`

```php
<?php

namespace App\Http\Livewire\Inventory\Products;

use Livewire\Component;
use Livewire\WithPagination;
use App\Modules\Inventory\Domain\Models\Product;
use App\Modules\Inventory\Domain\Services\StockCalculationService;

class ProductTable extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $categoryId = null;
    public ?string $status = null;
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryId()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function deleteProduct(int $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        session()->flash('success', "Producto '{$product->name}' eliminado exitosamente");
    }

    public function render()
    {
        $query = Product::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('sku', 'like', "%{$this->search}%")
                  ->orWhere('barcode', 'like', "%{$this->search}%");
            });
        }

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        if ($this->status === 'active') {
            $query->where('is_active', true);
        } elseif ($this->status === 'inactive') {
            $query->where('is_active', false);
        }

        $products = $query->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(15);

        return view('livewire.inventory.products.product-table', [
            'products' => $products,
        ]);
    }
}
```

`resources/views/livewire/inventory/products/product-table.blade.php`

```blade
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <button wire:click="sortBy('name')" class="hover:text-gray-700">
                        Nombre
                        @if($sortBy === 'name')
                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                        @endif
                    </button>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                        <div class="text-xs text-gray-500">{{ $product->barcode }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="bg-gray-100 px-3 py-1 rounded text-xs font-mono">{{ $product->sku }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $product->category?->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        ${{ number_format($product->retail_price, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-900">{{ $product->getTotalStock() }}</span>
                            @if($product->isLowStock())
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-semibold">
                                    Bajo Stock
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($product->is_active)
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded text-xs font-semibold">
                                Activo
                            </span>
                        @else
                            <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded text-xs font-semibold">
                                Inactivo
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <a href="{{ route('inventory.products.show', $product) }}"
                           class="text-indigo-600 hover:text-indigo-900">Ver</a>
                        <a href="{{ route('inventory.products.edit', $product) }}"
                           class="text-blue-600 hover:text-blue-900">Editar</a>
                        <button wire:click="deleteProduct({{ $product->id }})"
                                wire:confirm="¿Estás seguro de que deseas eliminar este producto?"
                                class="text-red-600 hover:text-red-900">Eliminar</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        No se encontraron productos
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="bg-white px-4 py-3 border-t border-gray-200">
        {{ $products->links() }}
    </div>
</div>
```

#### 3. Vista de Crear Producto

`resources/views/inventory/products/create.blade.php`

```blade
@extends('dashboard.layouts.app')

@section('title', 'Crear Producto')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Nuevo Producto</h1>
        </div>

        <x-card>
            <form action="{{ route('inventory.products.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombre -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500
                                      @error('name') border-red-500 @enderror"
                               placeholder="Ej: Laptop Dell XPS 13">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SKU -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SKU *</label>
                        <input type="text" name="sku" value="{{ old('sku') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500
                                      @error('sku') border-red-500 @enderror"
                               placeholder="Ej: DELL-XPS-001">
                        @error('sku')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Barcode -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Código de Barras</label>
                        <input type="text" name="barcode" value="{{ old('barcode') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="Ej: 5901234123457">
                    </div>

                    <!-- Categoría -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoría *</label>
                        <select name="category_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500
                                       @error('category_id') border-red-500 @enderror">
                            <option value="">Seleccionar categoría</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Marca -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Marca</label>
                        <select name="brand_id" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                            <option value="">Seleccionar marca</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}"
                                        {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Costo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Costo *</label>
                        <input type="number" name="cost" value="{{ old('cost') }}" step="0.01"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500
                                      @error('cost') border-red-500 @enderror"
                               placeholder="0.00">
                        @error('cost')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Precio de Venta -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Precio de Venta *</label>
                        <input type="number" name="sell_price" value="{{ old('sell_price') }}" step="0.01"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500
                                      @error('sell_price') border-red-500 @enderror"
                               placeholder="0.00">
                        @error('sell_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Peso -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Peso (kg)</label>
                        <input type="number" name="weight" value="{{ old('weight') }}" step="0.01"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md">
                    </div>

                    <!-- Nivel de Reorden -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stock Mínimo</label>
                        <input type="number" name="reorder_level" value="{{ old('reorder_level', 10) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md">
                    </div>
                </div>

                <!-- Descripción -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Descripción del producto...">{{ old('description') }}</textarea>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <a href="{{ route('inventory.products.index') }}"
                       class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-medium">
                        Crear Producto
                    </button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
```

#### 4. Componente Livewire: StockAdjuster

`app/Http/Livewire/Inventory/StockAdjuster.php`

```php
<?php

namespace App\Http\Livewire\Inventory;

use Livewire\Component;
use App\Modules\Inventory\Domain\Models\Product;
use App\Modules\Inventory\Application\UseCases\Stock\AdjustStockUseCase;

class StockAdjuster extends Component
{
    public Product $product;
    public int $warehouseId;
    public int $quantity = 0;
    public string $reason = '';

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function adjustStock()
    {
        $this->validate([
            'quantity' => 'required|integer|not_in:0',
            'warehouseId' => 'required|integer|exists:warehouses,id',
            'reason' => 'required|string|max:255',
        ]);

        $useCase = app(AdjustStockUseCase::class);
        $useCase->execute(
            productId: $this->product->id,
            warehouseId: $this->warehouseId,
            quantity: $this->quantity,
            reason: $this->reason,
        );

        $this->reset();
        session()->flash('success', 'Stock ajustado exitosamente');
    }

    public function render()
    {
        return view('livewire.inventory.stock-adjuster', [
            'warehouses' => \App\Modules\Inventory\Domain\Models\Warehouse::active()->get(),
        ]);
    }
}
```

### Criterios de Aceptación

- [ ] CRUD completo de productos funcional
- [ ] Búsqueda en vivo implementada
- [ ] Filtros funcionando correctamente
- [ ] Validación en frontend y backend
- [ ] Mensajes flash de éxito/error
- [ ] Confirmación de eliminación
- [ ] Componentes Livewire funcionando
- [ ] Diseño responsive
- [ ] Paginación funcionando

### Testing

```php
// Tests/Feature/Inventory/ProductCRUDTest.php
public function test_can_view_products_list()
{
    $products = Product::factory(3)->create();

    $response = $this->actingAs($this->user)->get(route('inventory.products.index'));

    $response->assertStatus(200);
    $response->assertViewHas('products');
}

public function test_can_search_products()
{
    Product::factory()->create(['sku' => 'TEST-001']);

    $response = Livewire::test('inventory.products.product-table')
        ->set('search', 'TEST-001')
        ->assertSee('TEST-001');
}

public function test_can_adjust_stock()
{
    $product = Product::factory()->create();

    Livewire::test('inventory.stock-adjuster', ['product' => $product])
        ->set('warehouseId', 1)
        ->set('quantity', 10)
        ->set('reason', 'Initial stock')
        ->call('adjustStock')
        ->assertSessionHas('success');
}
```

### Dependencias

- ✅ Fase 5.1: Estructura Clean Architecture
- ✅ Fase 5.2: Migraciones y Modelos
- ✅ Fase 5.3: Domain Layer
- ✅ Fase 5.4: Application & Presentation Layer
- ✅ Fase 4: Dashboard Foundation (componentes reutilizables)

### Notas Importantes

1. **Validación**: Validar en Form Requests y en frontend con Livewire
2. **UX**: Usar confirmaciones para acciones destructivas
3. **Performance**: Paginar resultados para grandes volúmenes
4. **Feedback**: Mensajes claros al usuario de todas las acciones
5. **Accessibilidad**: Asegurar que el formulario sea accesible

---

**Estimación:** 3-4 días  
**Prioridad:** Alta  
**Última actualización:** 2026-02-11
