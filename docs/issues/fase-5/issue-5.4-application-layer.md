---
title: '[Fase 5.4] Application & Presentation Layer del Inventario'
labels: fase-5, inventory, backend, use-cases, priority-high
assignees:
milestone: Fase 5 - Módulo Inventario
---

## 🎯 Tarea: Implementar Application y Presentation Layer del Inventario

### Descripción

Implementar la capa de aplicación (Use Cases) y la capa de presentación (Controllers, Requests) del módulo inventario. Esta capa orquesta el dominio y expone funcionalidades al usuario.

### Objetivos

#### Use Cases - Productos

- [ ] CreateProductUseCase
- [ ] UpdateProductUseCase
- [ ] DeleteProductUseCase
- [ ] GetProductUseCase
- [ ] ListProductsUseCase

#### Use Cases - Categorías

- [ ] CreateCategoryUseCase
- [ ] UpdateCategoryUseCase
- [ ] DeleteCategoryUseCase
- [ ] ListCategoriesUseCase

#### Use Cases - Stock

- [ ] AdjustStockUseCase
- [ ] TransferStockUseCase
- [ ] GetStockLevelsUseCase

#### Controllers

- [ ] ProductController (CRUD)
- [ ] CategoryController (CRUD)
- [ ] StockController (ajustes y transferencias)
- [ ] BrandController (CRUD)

#### Form Requests (Validación)

- [ ] StoreProductRequest
- [ ] UpdateProductRequest
- [ ] StoreCategoryRequest
- [ ] AdjustStockRequest
- [ ] TransferStockRequest

### Implementación

#### 1. Use Cases - Productos

##### CreateProductUseCase

`app/Modules/Inventory/Application/UseCases/Product/CreateProductUseCase.php`

```php
<?php

namespace App\Modules\Inventory\Application\UseCases\Product;

use App\Modules\Inventory\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Inventory\Application\DTOs\ProductDTO;
use App\Modules\Inventory\Domain\Models\Product;
use App\Modules\Inventory\Domain\Events\ProductCreated;
use Illuminate\Support\Str;

class CreateProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function execute(ProductDTO $dto): Product
    {
        // Generar slug si no existe
        $dto->slug = $dto->slug ?? Str::slug($dto->name);

        // Crear el producto
        $product = $this->productRepository->create($dto);

        // Disparar evento de dominio
        event(new ProductCreated($product));

        return $product;
    }
}
```

##### UpdateProductUseCase

`app/Modules/Inventory/Application/UseCases/Product/UpdateProductUseCase.php`

```php
<?php

namespace App\Modules\Inventory\Application\UseCases\Product;

use App\Modules\Inventory\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Inventory\Domain\Models\Product;
use App\Modules\Inventory\Application\DTOs\ProductDTO;
use App\Modules\Inventory\Domain\Events\ProductUpdated;
use Illuminate\Support\Str;

class UpdateProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function execute(int $id, ProductDTO $dto): Product
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            throw new \Exception("Product not found");
        }

        // Actualizar slug si el nombre cambió
        $dto->slug = $dto->slug ?? Str::slug($dto->name);

        // Actualizar
        $product = $this->productRepository->update($id, $dto);

        // Disparar evento
        event(new ProductUpdated($product));

        return $product;
    }
}
```

##### DeleteProductUseCase

`app/Modules/Inventory/Application/UseCases/Product/DeleteProductUseCase.php`

```php
<?php

namespace App\Modules\Inventory\Application\UseCases\Product;

use App\Modules\Inventory\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Inventory\Domain\Events\ProductDeleted;

class DeleteProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function execute(int $id): bool
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            throw new \Exception("Product not found");
        }

        // Disparar evento antes de eliminar
        event(new ProductDeleted($product));

        return $this->productRepository->delete($id);
    }
}
```

##### ListProductsUseCase

`app/Modules/Inventory/Application/UseCases/Product/ListProductsUseCase.php`

```php
<?php

namespace App\Modules\Inventory\Application\UseCases\Product;

use App\Modules\Inventory\Domain\Repositories\ProductRepositoryInterface;
use Illuminate\Pagination\Paginator;

class ListProductsUseCase
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function execute(
        int $perPage = 15,
        ?int $categoryId = null,
    ): Paginator {
        if ($categoryId) {
            return $this->productRepository->getByCategory($categoryId, $perPage);
        }

        return $this->productRepository->getAll($perPage);
    }
}
```

#### 2. Use Cases - Stock

##### AdjustStockUseCase

`app/Modules/Inventory/Application/UseCases/Stock/AdjustStockUseCase.php`

```php
<?php

namespace App\Modules\Inventory\Application\UseCases\Stock;

use App\Modules\Inventory\Domain\Models\{Product, Stock, StockMovement};
use App\Modules\Inventory\Domain\Events\StockAdjusted;
use App\Modules\Inventory\Domain\Events\LowStockDetected;
use Illuminate\Support\Facades\Auth;

class AdjustStockUseCase
{
    public function execute(
        int $productId,
        int $warehouseId,
        int $quantity,
        string $reason,
        string $reference = null,
    ): Stock {
        $product = Product::findOrFail($productId);

        // Obtener o crear stock en almacén
        $stock = Stock::firstOrCreate(
            [
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
            ],
            ['quantity' => 0]
        );

        $previousQuantity = $stock->quantity;
        $newQuantity = max(0, $stock->quantity + $quantity);

        // Actualizar stock
        $stock->update(['quantity' => $newQuantity]);

        // Registrar movimiento
        StockMovement::create([
            'product_id' => $productId,
            'warehouse_id' => $warehouseId,
            'type' => $quantity > 0 ? 'adjustment' : 'damage',
            'quantity' => $quantity,
            'reference' => $reference,
            'notes' => $reason,
            'created_by' => Auth::id(),
        ]);

        // Disparar eventos
        event(new StockAdjusted($stock, $previousQuantity, $newQuantity, $reason));

        // Verificar stock bajo
        if ($newQuantity <= $product->reorder_level) {
            event(new LowStockDetected($product, $newQuantity, $product->reorder_level));
        }

        return $stock;
    }
}
```

#### 3. Form Requests

##### StoreProductRequest

`app/Modules/Inventory/Http/Requests/StoreProductRequest.php`

```php
<?php

namespace App\Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'required|string|max:50|unique:products,sku',
            'barcode' => 'nullable|string|max:14|unique:products,barcode',
            'category_id' => 'required|integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'cost' => 'required|numeric|min:0|max:999999.99',
            'sell_price' => 'required|numeric|min:0|max:999999.99',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'reorder_quantity' => 'nullable|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
            'supplier_ids' => 'nullable|array',
            'supplier_ids.*' => 'integer|exists:suppliers,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es requerido',
            'sku.required' => 'El SKU es requerido',
            'sku.unique' => 'Este SKU ya existe en el sistema',
            'category_id.required' => 'Debes seleccionar una categoría',
            'cost.required' => 'El costo es requerido',
            'sell_price.required' => 'El precio de venta es requerido',
        ];
    }
}
```

#### 4. Controllers

##### ProductController

`app/Modules/Inventory/Http/Controllers/ProductController.php`

```php
<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Inventory\Http\Requests\StoreProductRequest;
use App\Modules\Inventory\Http\Requests\UpdateProductRequest;
use App\Modules\Inventory\Domain\Models\Product;
use App\Modules\Inventory\Application\DTOs\ProductDTO;
use App\Modules\Inventory\Application\UseCases\Product\{
    CreateProductUseCase,
    UpdateProductUseCase,
    DeleteProductUseCase,
    ListProductsUseCase,
};
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    public function __construct(
        private CreateProductUseCase $createUseCase,
        private UpdateProductUseCase $updateUseCase,
        private DeleteProductUseCase $deleteUseCase,
        private ListProductsUseCase $listUseCase,
    ) {
        $this->middleware('auth');
    }

    /**
     * Listar productos con paginación
     */
    public function index(): View
    {
        $categoryId = request('category_id');
        $products = $this->listUseCase->execute(
            perPage: 15,
            categoryId: $categoryId,
        );

        return view('inventory.products.index', [
            'products' => $products,
        ]);
    }

    /**
     * Mostrar formulario de crear producto
     */
    public function create(): View
    {
        return view('inventory.products.create');
    }

    /**
     * Guardar nuevo producto
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $dto = ProductDTO::fromArray($request->validated());
        $product = $this->createUseCase->execute($dto);

        return redirect()
            ->route('inventory.products.show', $product)
            ->with('success', 'Producto creado exitosamente');
    }

    /**
     * Mostrar detalle de producto
     */
    public function show(Product $product): View
    {
        return view('inventory.products.show', [
            'product' => $product,
        ]);
    }

    /**
     * Mostrar formulario de editar
     */
    public function edit(Product $product): View
    {
        return view('inventory.products.edit', [
            'product' => $product,
        ]);
    }

    /**
     * Actualizar producto
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $dto = ProductDTO::fromArray($request->validated());
        $this->updateUseCase->execute($product->id, $dto);

        return redirect()
            ->route('inventory.products.show', $product)
            ->with('success', 'Producto actualizado exitosamente');
    }

    /**
     * Eliminar producto
     */
    public function destroy(Product $product): RedirectResponse
    {
        $this->deleteUseCase->execute($product->id);

        return redirect()
            ->route('inventory.products.index')
            ->with('success', 'Producto eliminado exitosamente');
    }
}
```

##### StockController

`app/Modules/Inventory/Http/Controllers/StockController.php`

```php
<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Inventory\Http\Requests\AdjustStockRequest;
use App\Modules\Inventory\Domain\Models\Product;
use App\Modules\Inventory\Application\UseCases\Stock\AdjustStockUseCase;
use Illuminate\Http\RedirectResponse;

class StockController extends Controller
{
    public function __construct(
        private AdjustStockUseCase $adjustStockUseCase,
    ) {
        $this->middleware('auth');
    }

    /**
     * Mostrar formulario de ajuste de stock
     */
    public function adjustForm(Product $product)
    {
        return view('inventory.stock.adjust', [
            'product' => $product,
        ]);
    }

    /**
     * Procesar ajuste de stock
     */
    public function adjust(AdjustStockRequest $request, Product $product): RedirectResponse
    {
        $this->adjustStockUseCase->execute(
            productId: $product->id,
            warehouseId: $request->warehouse_id,
            quantity: $request->quantity,
            reason: $request->reason,
        );

        return redirect()
            ->back()
            ->with('success', 'Stock ajustado exitosamente');
    }
}
```

#### 5. Service Provider

`app/Modules/Inventory/Application/InventoryServiceProvider.php` (añadir use cases)

```php
public function register(): void
{
    // Repositories...

    // Use Cases
    $this->app->bind(CreateProductUseCase::class, function ($app) {
        return new CreateProductUseCase(
            $app->make(ProductRepositoryInterface::class),
        );
    });

    $this->app->bind(UpdateProductUseCase::class, function ($app) {
        return new UpdateProductUseCase(
            $app->make(ProductRepositoryInterface::class),
        );
    });

    $this->app->bind(DeleteProductUseCase::class, function ($app) {
        return new DeleteProductUseCase(
            $app->make(ProductRepositoryInterface::class),
        );
    });

    $this->app->bind(ListProductsUseCase::class, function ($app) {
        return new ListProductsUseCase(
            $app->make(ProductRepositoryInterface::class),
        );
    });

    $this->app->bind(AdjustStockUseCase::class, function ($app) {
        return new AdjustStockUseCase();
    });
}
```

### Criterios de Aceptación

- [ ] Todos los Use Cases implementados
- [ ] Controllers CRUD funcionales
- [ ] Form Requests con validación completa
- [ ] Manejo de errores y excepciones
- [ ] Redirecciones con mensajes flash
- [ ] Inyección de dependencias configurada
- [ ] Rutas del módulo definidas

### Testing

```php
// Tests/Feature/Inventory/ProductControllerTest.php
public function test_can_create_product()
{
    $response = $this->actingAs($this->user)->post(
        route('inventory.products.store'),
        [
            'name' => 'Test Product',
            'sku' => 'TEST-001',
            'barcode' => '1234567890123',
            'category_id' => 1,
            'cost' => 10.00,
            'sell_price' => 20.00,
        ]
    );

    $response->assertRedirect(route('inventory.products.show', Product::first()));
    $this->assertDatabaseHas('products', ['sku' => 'TEST-001']);
}

public function test_can_update_product()
{
    $product = Product::factory()->create();

    $response = $this->actingAs($this->user)->patch(
        route('inventory.products.update', $product),
        ['name' => 'Updated Name']
    );

    $response->assertRedirect();
    $this->assertEquals('Updated Name', $product->fresh()->name);
}

// Tests/Feature/Inventory/StockControllerTest.php
public function test_can_adjust_stock()
{
    $product = Product::factory()->create();

    $response = $this->actingAs($this->user)->post(
        route('inventory.stock.adjust', $product),
        [
            'warehouse_id' => 1,
            'quantity' => 10,
            'reason' => 'Initial stock',
        ]
    );

    $response->assertRedirect();
    $this->assertDatabaseHas('stock', [
        'product_id' => $product->id,
        'quantity' => 10,
    ]);
}
```

### Dependencias

- ✅ Fase 5.1: Estructura Clean Architecture
- ✅ Fase 5.2: Migraciones y Modelos
- ✅ Fase 5.3: Domain Layer

### Notas Importantes

1. **Use Cases Simples**: Orquestar el dominio sin contener lógica de negocio
2. **Controllers Thin**: Los controllers solo orquestan requests/responses
3. **Validación en Requests**: Usar Form Requests para validación
4. **Dependency Injection**: Inyectar use cases en constructores
5. **Error Handling**: Manejar excepciones apropiadamente

---

**Estimación:** 3-4 días  
**Prioridad:** Alta  
**Última actualización:** 2026-02-11
