---
title: '[Fase 5.1] Estructura Clean Architecture del Módulo Inventario'
labels: fase-5, inventory, clean-architecture, backend, priority-high
assignees:
milestone: Fase 5 - Módulo Inventario
---

## 🏗️ Tarea: Crear Estructura Clean Architecture del Módulo Inventario

### Descripción

Implementar la estructura base del primer módulo ERP siguiendo Clean Architecture. Este módulo servirá como plantilla para los demás módulos (CRM, Ventas, Contabilidad, etc.).

### Objetivos

#### Estructura de Directorios

- [ ] Crear directorio `app/Modules/Inventory/`
- [ ] Crear estructura Domain Layer
- [ ] Crear estructura Application Layer
- [ ] Crear estructura Infrastructure Layer
- [ ] Crear estructura Presentation (HTTP) Layer

#### Domain Layer

- [ ] Crear interfaces de Repositories
- [ ] Crear Domain Events base
- [ ] Crear Domain Services base
- [ ] Crear Value Objects específicos del dominio

#### Application Layer

- [ ] Crear DTOs del módulo
- [ ] Crear Use Cases base
- [ ] Crear Service Provider del módulo

#### Infrastructure Layer

- [ ] Crear implementaciones de Repositories con Eloquent
- [ ] Crear Event Listeners
- [ ] Crear Query scopes reutilizables

### Implementación

#### 1. Estructura de Directorios

```
app/Modules/Inventory/
├── Domain/
│   ├── Models/
│   │   ├── Category.php
│   │   ├── Product.php
│   │   ├── Brand.php
│   │   ├── Warehouse.php
│   │   ├── Stock.php
│   │   ├── StockMovement.php
│   │   ├── Supplier.php
│   │   └── ProductSupplier.php
│   ├── ValueObjects/
│   │   ├── SKU.php
│   │   ├── Barcode.php
│   │   ├── StockQuantity.php
│   │   ├── Price.php
│   │   └── Dimensions.php
│   ├── Events/
│   │   ├── ProductCreated.php
│   │   ├── ProductUpdated.php
│   │   ├── StockAdjusted.php
│   │   ├── LowStockDetected.php
│   │   ├── ProductDeleted.php
│   │   └── StockTransferred.php
│   ├── Repositories/
│   │   ├── ProductRepositoryInterface.php
│   │   ├── CategoryRepositoryInterface.php
│   │   ├── StockRepositoryInterface.php
│   │   ├── BrandRepositoryInterface.php
│   │   ├── SupplierRepositoryInterface.php
│   │   └── WarehouseRepositoryInterface.php
│   └── Services/
│       ├── StockCalculationService.php
│       ├── PriceCalculationService.php
│       └── InventoryService.php
├── Application/
│   ├── UseCases/
│   │   ├── Product/
│   │   │   ├── CreateProductUseCase.php
│   │   │   ├── UpdateProductUseCase.php
│   │   │   ├── DeleteProductUseCase.php
│   │   │   ├── GetProductUseCase.php
│   │   │   └── ListProductsUseCase.php
│   │   ├── Category/
│   │   │   ├── CreateCategoryUseCase.php
│   │   │   ├── UpdateCategoryUseCase.php
│   │   │   └── DeleteCategoryUseCase.php
│   │   ├── Stock/
│   │   │   ├── AdjustStockUseCase.php
│   │   │   ├── TransferStockUseCase.php
│   │   │   └── GetStockLevelsUseCase.php
│   │   └── Brand/
│   │       ├── CreateBrandUseCase.php
│   │       ├── UpdateBrandUseCase.php
│   │       └── DeleteBrandUseCase.php
│   ├── DTOs/
│   │   ├── ProductDTO.php
│   │   ├── CategoryDTO.php
│   │   ├── StockMovementDTO.php
│   │   ├── BrandDTO.php
│   │   └── StockLevelDTO.php
│   └── InventoryServiceProvider.php
├── Infrastructure/
│   ├── Persistence/
│   │   ├── EloquentProductRepository.php
│   │   ├── EloquentCategoryRepository.php
│   │   ├── EloquentStockRepository.php
│   │   ├── EloquentBrandRepository.php
│   │   ├── EloquentSupplierRepository.php
│   │   └── EloquentWarehouseRepository.php
│   ├── Events/
│   │   ├── SendLowStockNotification.php
│   │   ├── LogStockMovement.php
│   │   └── UpdateProductSearchIndex.php
│   └── Queries/
│       ├── ProductQuery.php
│       ├── StockQuery.php
│       └── SupplierQuery.php
├── Http/
│   ├── Controllers/
│   │   ├── ProductController.php
│   │   ├── CategoryController.php
│   │   ├── BrandController.php
│   │   ├── WarehouseController.php
│   │   └── StockController.php
│   ├── Requests/
│   │   ├── StoreProductRequest.php
│   │   ├── UpdateProductRequest.php
│   │   ├── StoreCategoryRequest.php
│   │   ├── AdjustStockRequest.php
│   │   └── TransferStockRequest.php
│   └── Resources/
│       ├── ProductResource.php
│       ├── CategoryResource.php
│       └── StockResource.php
└── routes/
    └── web.php
```

#### 2. Ejemplo: Value Object SKU

`app/Modules/Inventory/Domain/ValueObjects/SKU.php`

```php
<?php

namespace App\Modules\Inventory\Domain\ValueObjects;

use App\Core\Domain\Shared\Exceptions\ValidationException;

final class SKU
{
    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    private function validate(string $value): void
    {
        if (empty($value)) {
            throw new ValidationException('SKU cannot be empty');
        }

        if (strlen($value) > 50) {
            throw new ValidationException('SKU cannot exceed 50 characters');
        }

        if (!preg_match('/^[A-Z0-9\-\.]+$/', $value)) {
            throw new ValidationException('SKU must contain only uppercase letters, numbers, hyphens and dots');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(SKU $other): bool
    {
        return $this->value === $other->value();
    }
}
```

#### 3. Ejemplo: Repository Interface

`app/Modules/Inventory/Domain/Repositories/ProductRepositoryInterface.php`

```php
<?php

namespace App\Modules\Inventory\Domain\Repositories;

use App\Modules\Inventory\Domain\Models\Product;
use App\Modules\Inventory\Application\DTOs\ProductDTO;
use Illuminate\Pagination\Paginator;

interface ProductRepositoryInterface
{
    /**
     * Crear un nuevo producto
     */
    public function create(ProductDTO $dto): Product;

    /**
     * Actualizar un producto existente
     */
    public function update(int $id, ProductDTO $dto): Product;

    /**
     * Obtener producto por ID
     */
    public function findById(int $id): ?Product;

    /**
     * Obtener producto por SKU
     */
    public function findBySKU(string $sku): ?Product;

    /**
     * Obtener todos los productos con paginación
     */
    public function getAll(int $perPage = 15): Paginator;

    /**
     * Filtrar productos por categoría
     */
    public function getByCategory(int $categoryId, int $perPage = 15): Paginator;

    /**
     * Obtener productos con stock bajo
     */
    public function getLowStockProducts(int $threshold = 10): array;

    /**
     * Eliminar un producto
     */
    public function delete(int $id): bool;

    /**
     * Contar total de productos
     */
    public function count(): int;
}
```

#### 4. Ejemplo: Use Case

`app/Modules/Inventory/Application/UseCases/Product/CreateProductUseCase.php`

```php
<?php

namespace App\Modules\Inventory\Application\UseCases\Product;

use App\Modules\Inventory\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Inventory\Application\DTOs\ProductDTO;
use App\Modules\Inventory\Domain\Models\Product;
use App\Modules\Inventory\Domain\Events\ProductCreated;

class CreateProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function execute(ProductDTO $dto): Product
    {
        // Crear el producto
        $product = $this->productRepository->create($dto);

        // Disparar evento de dominio
        event(new ProductCreated($product));

        return $product;
    }
}
```

#### 5. Ejemplo: DTO

`app/Modules/Inventory/Application/DTOs/ProductDTO.php`

```php
<?php

namespace App\Modules\Inventory\Application\DTOs;

class ProductDTO
{
    public function __construct(
        public string $name,
        public string $description,
        public string $sku,
        public string $barcode,
        public float $cost,
        public float $sellPrice,
        public int $categoryId,
        public ?int $brandId = null,
        public ?array $dimensions = null,
        public ?array $supplier_ids = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'],
            sku: $data['sku'],
            barcode: $data['barcode'],
            cost: $data['cost'],
            sellPrice: $data['sell_price'],
            categoryId: $data['category_id'],
            brandId: $data['brand_id'] ?? null,
            dimensions: $data['dimensions'] ?? null,
            supplier_ids: $data['supplier_ids'] ?? null,
        );
    }
}
```

#### 6. Service Provider

`app/Modules/Inventory/Application/InventoryServiceProvider.php`

```php
<?php

namespace App\Modules\Inventory\Application;

use Illuminate\Support\ServiceProvider;
use App\Modules\Inventory\Domain\Repositories\{
    ProductRepositoryInterface,
    CategoryRepositoryInterface,
    StockRepositoryInterface,
    BrandRepositoryInterface,
};
use App\Modules\Inventory\Infrastructure\Persistence\{
    EloquentProductRepository,
    EloquentCategoryRepository,
    EloquentStockRepository,
    EloquentBrandRepository,
};

class InventoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registrar bindings de repositories
        $this->app->bind(
            ProductRepositoryInterface::class,
            EloquentProductRepository::class
        );

        $this->app->bind(
            CategoryRepositoryInterface::class,
            EloquentCategoryRepository::class
        );

        $this->app->bind(
            StockRepositoryInterface::class,
            EloquentStockRepository::class
        );

        $this->app->bind(
            BrandRepositoryInterface::class,
            EloquentBrandRepository::class
        );
    }

    public function boot(): void
    {
        // Cargar migraciones del módulo
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Cargar rutas del módulo
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        // Publicar configuración
        $this->publishes([
            __DIR__ . '/../../config' => config_path('inventory'),
        ]);
    }
}
```

### Criterios de Aceptación

- [ ] Estructura de directorios completa creada
- [ ] Todas las interfaces de Repository definidas
- [ ] Value Objects creados (SKU, Barcode, StockQuantity, Price, Dimensions)
- [ ] Domain Events creados
- [ ] Use Cases base implementados
- [ ] DTOs creados para transferencia de datos
- [ ] Service Provider configurado
- [ ] Bindings de Dependency Injection configurados

### Testing

```php
// Tests/Unit/Modules/Inventory/ValueObjects/SKUTest.php
public function test_valid_sku_creation()
{
    $sku = new SKU('SKU-001-ABC');

    $this->assertEquals('SKU-001-ABC', $sku->value());
}

public function test_invalid_sku_throws_exception()
{
    $this->expectException(ValidationException::class);

    new SKU('invalid sku'); // Contiene espacios
}

// Tests/Unit/Modules/Inventory/UseCases/CreateProductUseCaseTest.php
public function test_create_product_use_case()
{
    $dto = new ProductDTO(
        name: 'Test Product',
        description: 'Test Description',
        sku: 'TEST-001',
        barcode: '1234567890',
        cost: 10.00,
        sellPrice: 20.00,
        categoryId: 1,
    );

    $useCase = new CreateProductUseCase($this->productRepository);
    $product = $useCase->execute($dto);

    $this->assertNotNull($product->id);
}
```

### Dependencias

- ✅ Fase 1: Infraestructura y Core
- ✅ Fase 2: Autenticación y Multi-Tenancy
- Fase 5.2: Migraciones BD del módulo

### Notas Importantes

1. **Clean Architecture Estricta**: Mantener separación clara entre capas
2. **No Mezclar Lógica**: Application layer solo orquesta, no contiene lógica de negocio
3. **Domain Events**: Usar para desacoplamiento de eventos importantes
4. **Value Objects**: Encapsular validaciones de negocio
5. **Repository Pattern**: Abstracción de persistencia para facilitar testing

---

**Estimación:** 3-4 días  
**Prioridad:** Alta  
**Última actualización:** 2026-02-11
