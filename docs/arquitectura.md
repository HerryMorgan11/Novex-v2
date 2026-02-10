# Documentación Arquitectura Novex v2

# 📚 Guía Completa

# Arquitectura Modular + Service + Repository Pattern con Multi-Tenancy

---

## 📖 Índice

1. Decisión Arquitectónica
2. ¿Qué es Modular + Service + Repository?
3. Principios Fundamentales
4. Estructura Completa del Proyecto
5. Componentes Principales
    - Models (Domain Logic)
    - Repositories (Data Access)
    - Services (Application Logic)
    - Controllers (HTTP Layer)
    - Requests (Validation)
    - Events & Listeners
6. Core Compartido
7. Multi-Tenancy en Laravel
8. Paquete: Tenancy for Laravel (stancl/tenancy)
9. Flujo de Trabajo Completo
10. Fases de Desarrollo Detalladas
11. Testing
12. Reglas de Oro
13. Referencias y Recursos

---

## 🎯 Decisión Arquitectónica

Después de evaluar **Clean Architecture**, **MVC Tradicional** y **Modular + Service + Repository**, hemos elegido:

### 🏆 Arquitectura Modular + Service + Repository Pattern

**¿Por qué?**

- ✅ **Productividad inmediata** sin sacrificar calidad
- ✅ **Organización modular** perfecta para ERP
- ✅ **Escalable** conforme crece el proyecto
- ✅ **No abrumadora** para equipo de 1-2 personas
- ✅ **Path de migración claro** a Clean Architecture si crece el equipo
- ✅ **Balance perfecto** entre simplicidad y estructura

---

## 🏛️ ¿Qué es Modular + Service + Repository?

Es un patrón arquitectónico que combina:

1. **Arquitectura Modular**: Cada módulo de negocio (Inventario, Ventas, CRM) vive en su propia carpeta
2. **Service Layer**: Lógica de aplicación y orquestación en servicios
3. **Repository Pattern**: Abstracción del acceso a datos

### Ventajas sobre Clean Architecture:
- **40% menos archivos** por feature
- **Desarrollo más rápido** sin perder organización
- **Curva de aprendizaje baja** para nuevos desarrolladores
- **Testeable** sin complejidad excesiva

### Ventajas sobre MVC Tradicional:
- **Organización modular** clara
- **Separación de responsabilidades**
- **Escalable** a largo plazo
- **Evita "Fat Controllers"**

---

## 🎯 Principios Fundamentales

### 1. Un Módulo = Una Carpeta
Cada módulo de negocio está completamente aislado en su carpeta.

### 2. Separación de Responsabilidades
- **Models**: Lógica de negocio y relaciones
- **Repositories**: Acceso a datos (queries complejas)
- **Services**: Lógica de aplicación (orquestación)
- **Controllers**: Orquestación HTTP (delegación)

### 3. Dependencias Claras
```
Controller → Service → Repository → Model
     ↓
 FormRequest
```

### 4. Core Compartido
Funcionalidad compartida entre módulos vive en `app/Core/`

---

## 🏗️ Estructura Completa del Proyecto

```
app/
├── Core/                           # Funcionalidad compartida
│   ├── Shared/
│   │   ├── Services/               # Servicios transversales
│   │   │   ├── FileStorageService.php
│   │   │   ├── NotificationService.php
│   │   │   └── AuditService.php
│   │   ├── Traits/                 # Traits reutilizables
│   │   │   ├── HasUuid.php
│   │   │   ├── Auditable.php
│   │   │   └── SoftDeletesWithUser.php
│   │   ├── Helpers/                # Helper functions
│   │   │   ├── MoneyHelper.php
│   │   │   └── DateHelper.php
│   │   ├── Contracts/              # Interfaces compartidas
│   │   │   ├── Auditable.php
│   │   │   └── Searchable.php
│   │   └── Exceptions/             # Excepciones base
│   │       ├── BusinessException.php
│   │       └── NotFoundException.php
│   │
│   └── Tenant/                     # Multi-tenancy core
│       ├── Services/
│       │   ├── TenantService.php
│       │   └── TenantSwitcher.php
│       ├── Middleware/
│       │   └── InitializeTenancy.php
│       └── Models/
│           └── Tenant.php
│
├── Modules/                        # Módulos de negocio
│   ├── Inventory/
│   │   ├── Models/                 # Eloquent Models con lógica de negocio
│   │   │   ├── Product.php
│   │   │   ├── Category.php
│   │   │   └── StockMovement.php
│   │   │
│   │   ├── Repositories/           # Data Access Layer
│   │   │   ├── Interfaces/
│   │   │   │   ├── ProductRepositoryInterface.php
│   │   │   │   └── CategoryRepositoryInterface.php
│   │   │   └── Implementations/
│   │   │       ├── ProductRepository.php
│   │   │       └── CategoryRepository.php
│   │   │
│   │   ├── Services/               # Application Logic
│   │   │   ├── ProductService.php
│   │   │   ├── StockService.php
│   │   │   └── CategoryService.php
│   │   │
│   │   ├── Http/
│   │   │   ├── Controllers/        # HTTP Layer
│   │   │   │   ├── ProductController.php
│   │   │   │   └── CategoryController.php
│   │   │   └── Requests/           # Form Validation
│   │   │       ├── StoreProductRequest.php
│   │   │       └── UpdateProductRequest.php
│   │   │
│   │   ├── Events/                 # Domain Events
│   │   │   ├── ProductCreated.php
│   │   │   └── StockUpdated.php
│   │   │
│   │   ├── Listeners/              # Event Listeners
│   │   │   └── NotifyLowStock.php
│   │   │
│   │   ├── Exceptions/             # Module-specific exceptions
│   │   │   ├── InsufficientStockException.php
│   │   │   └── InvalidPriceException.php
│   │   │
│   │   └── Providers/              # Module Service Provider
│   │       └── InventoryServiceProvider.php
│   │
│   ├── Sales/
│   │   ├── Models/
│   │   │   ├── Order.php
│   │   │   └── OrderItem.php
│   │   ├── Repositories/
│   │   ├── Services/
│   │   ├── Http/
│   │   ├── Events/
│   │   └── Providers/
│   │
│   ├── CRM/
│   │   ├── Models/
│   │   │   ├── Customer.php
│   │   │   └── Contact.php
│   │   ├── Repositories/
│   │   ├── Services/
│   │   ├── Http/
│   │   ├── Events/
│   │   └── Providers/
│   │
│   └── Invoicing/
│       ├── Models/
│       ├── Repositories/
│       ├── Services/
│       ├── Http/
│       ├── Events/
│       └── Providers/
│
└── Http/
    └── Middleware/                 # Global Middleware
        └── TenantMiddleware.php
```

---

## 🧩 Componentes Principales

### 1️⃣ Models (Domain Logic)

**Eloquent Models** que contienen:
- Propiedades del modelo (fillable, casts, etc.)
- Relaciones entre modelos
- Lógica de negocio (métodos de dominio)
- Scopes para queries comunes

```php
<?php

namespace App\Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\Shared\Traits\HasUuid;
use App\Modules\Inventory\Exceptions\InsufficientStockException;

class Product extends Model
{
    use HasUuid;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'price',
        'stock_quantity',
        'min_stock',
        'category_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'min_stock' => 'integer',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // ============================================
    // BUSINESS LOGIC (Domain Methods)
    // ============================================
    
    public function hasStock(int $quantity): bool
    {
        return $this->stock_quantity >= $quantity;
    }

    public function decreaseStock(int $quantity): void
    {
        if (!$this->hasStock($quantity)) {
            throw new InsufficientStockException(
                "Stock insuficiente para el producto {$this->name}"
            );
        }

        $this->decrement('stock_quantity', $quantity);
    }

    public function increaseStock(int $quantity): void
    {
        $this->increment('stock_quantity', $quantity);
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->min_stock;
    }

    public function updatePrice(float $newPrice): void
    {
        if ($newPrice < 0) {
            throw new InvalidPriceException('El precio no puede ser negativo');
        }

        $this->update(['price' => $newPrice]);
    }

    // ============================================
    // SCOPES (Query Helpers)
    // ============================================
    
    public function scopeLowStock($query, ?int $threshold = null)
    {
        $threshold = $threshold ?? $this->min_stock;
        return $query->where('stock_quantity', '<=', $threshold);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}
```

**Responsabilidades:**
- ✅ Definir propiedades y relaciones
- ✅ Lógica de negocio simple (`hasStock()`, `isLowStock()`)
- ✅ Validaciones de reglas de negocio
- ❌ Queries complejas (van en Repository)
- ❌ Orquestación (va en Service)

---

### 2️⃣ Repositories (Data Access)

**Abstraen el acceso a datos** con queries complejas, agregaciones, joins.

#### Interface
```php
<?php

namespace App\Modules\Inventory\Repositories\Interfaces;

use App\Modules\Inventory\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function find(int $id): ?Product;
    
    public function findBySku(string $sku): ?Product;
    
    public function all(array $filters = []): Collection;
    
    public function create(array $data): Product;
    
    public function update(Product $product, array $data): Product;
    
    public function delete(int $id): bool;
    
    public function getLowStockProducts(int $threshold = 10): Collection;
    
    public function getProductsWithStockValue(): Collection;
    
    public function searchByName(string $search): Collection;
}
```

#### Implementation
```php
<?php

namespace App\Modules\Inventory\Repositories\Implementations;

use App\Modules\Inventory\Models\Product;
use App\Modules\Inventory\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function find(int $id): ?Product
    {
        return Product::with('category')->find($id);
    }

    public function findBySku(string $sku): ?Product
    {
        return Product::where('sku', $sku)->first();
    }

    public function all(array $filters = []): Collection
    {
        $query = Product::with('category');

        if (isset($filters['category_id'])) {
            $query->byCategory($filters['category_id']);
        }

        if (isset($filters['is_active'])) {
            $query->active();
        }

        if (isset($filters['low_stock']) && $filters['low_stock']) {
            $query->lowStock();
        }

        return $query->get();
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product->fresh();
    }

    public function delete(int $id): bool
    {
        return Product::destroy($id) > 0;
    }

    public function getLowStockProducts(int $threshold = 10): Collection
    {
        return Product::lowStock($threshold)
            ->with('category')
            ->orderBy('stock_quantity', 'asc')
            ->get();
    }

    public function getProductsWithStockValue(): Collection
    {
        return Product::query()
            ->selectRaw('products.*, (price * stock_quantity) as stock_value')
            ->with('category')
            ->orderBy('stock_value', 'desc')
            ->get();
    }

    public function searchByName(string $search): Collection
    {
        return Product::where('name', 'like', "%{$search}%")
            ->orWhere('sku', 'like', "%{$search}%")
            ->with('category')
            ->get();
    }
}
```

**Responsabilidades:**
- ✅ Queries complejas con joins, agregaciones
- ✅ Filtros y búsquedas avanzadas
- ✅ Eager loading de relaciones
- ✅ Abstracción de acceso a datos
- ❌ Lógica de negocio (va en Model)
- ❌ Orquestación (va en Service)

---

### 3️⃣ Services (Application Logic)

**Orquestan la lógica de aplicación** coordinando Repositories, Models y Events.

```php
<?php

namespace App\Modules\Inventory\Services;

use App\Modules\Inventory\Models\Product;
use App\Modules\Inventory\Repositories\Interfaces\ProductRepositoryInterface;
use App\Modules\Inventory\Events\ProductCreated;
use App\Modules\Inventory\Events\StockUpdated;
use App\Core\Shared\Services\AuditService;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private AuditService $auditService
    ) {}

    public function getAllProducts(array $filters = []): Collection
    {
        return $this->productRepository->all($filters);
    }

    public function findProduct(int $id): ?Product
    {
        return $this->productRepository->find($id);
    }

    public function createProduct(array $data): Product
    {
        // Validar si el SKU ya existe
        if ($this->productRepository->findBySku($data['sku'])) {
            throw new \Exception('El SKU ya existe');
        }

        // Crear producto
        $product = $this->productRepository->create($data);

        // Disparar evento
        event(new ProductCreated($product));

        // Auditoría
        $this->auditService->log('product_created', $product);

        return $product;
    }

    public function updateProduct(int $id, array $data): Product
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw new \Exception('Producto no encontrado');
        }

        // Validar SKU único (si cambió)
        if (isset($data['sku']) && $data['sku'] !== $product->sku) {
            if ($this->productRepository->findBySku($data['sku'])) {
                throw new \Exception('El SKU ya existe');
            }
        }

        $product = $this->productRepository->update($product, $data);

        $this->auditService->log('product_updated', $product);

        return $product;
    }

    public function deleteProduct(int $id): bool
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw new \Exception('Producto no encontrado');
        }

        $deleted = $this->productRepository->delete($id);

        if ($deleted) {
            $this->auditService->log('product_deleted', ['id' => $id]);
        }

        return $deleted;
    }

    public function adjustStock(int $productId, int $quantity, string $type): Product
    {
        $product = $this->productRepository->find($productId);

        if (!$product) {
            throw new \Exception('Producto no encontrado');
        }

        // Ajustar stock según el tipo
        if ($type === 'increase') {
            $product->increaseStock($quantity);
        } elseif ($type === 'decrease') {
            $product->decreaseStock($quantity);
        }

        // Disparar evento de stock actualizado
        event(new StockUpdated($product, $quantity, $type));

        // Auditoría
        $this->auditService->log('stock_adjusted', [
            'product_id' => $product->id,
            'quantity' => $quantity,
            'type' => $type
        ]);

        return $product->fresh();
    }

    public function getLowStockProducts(): Collection
    {
        return $this->productRepository->getLowStockProducts();
    }

    public function getStockReport(): Collection
    {
        return $this->productRepository->getProductsWithStockValue();
    }
}
```

**Responsabilidades:**
- ✅ Orquestación de lógica de aplicación
- ✅ Coordinación entre múltiples repositorios
- ✅ Dispatch de eventos
- ✅ Manejo de transacciones
- ✅ Validaciones de negocio complejas
- ❌ Lógica HTTP (va en Controller)
- ❌ Queries directas (van en Repository)

---

### 4️⃣ Controllers (HTTP Layer)

**Delgados**, solo orquestan la petición HTTP delegando al Service.

```php
<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Inventory\Services\ProductService;
use App\Modules\Inventory\Http\Requests\StoreProductRequest;
use App\Modules\Inventory\Http\Requests\UpdateProductRequest;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    public function index(): JsonResponse
    {
        $filters = request()->only(['category_id', 'is_active', 'low_stock']);
        
        $products = $this->productService->getAllProducts($filters);

        return response()->json($products);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->createProduct(
            $request->validated()
        );

        return response()->json($product, 201);
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->productService->findProduct($id);

        if (!$product) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }

        return response()->json($product);
    }

    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $product = $this->productService->updateProduct(
            $id,
            $request->validated()
        );

        return response()->json($product);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->productService->deleteProduct($id);

        return response()->json(null, 204);
    }

    public function adjustStock(int $id): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:increase,decrease'
        ]);

        $product = $this->productService->adjustStock(
            $id,
            $request->quantity,
            $request->type
        );

        return response()->json($product);
    }

    public function lowStock(): JsonResponse
    {
        $products = $this->productService->getLowStockProducts();

        return response()->json($products);
    }
}
```

**Responsabilidades:**
- ✅ Recibir peticiones HTTP
- ✅ Delegar al Service
- ✅ Retornar respuestas HTTP
- ✅ Manejo básico de errores
- ❌ Lógica de negocio
- ❌ Queries a base de datos
- ❌ Validaciones complejas

---

### 5️⃣ Form Requests (Validation)

**Validan los datos** de entrada de forma centralizada.

```php
<?php

namespace App\Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Manejar autorización si es necesario
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio',
            'sku.unique' => 'El SKU ya está registrado',
            'price.min' => 'El precio no puede ser negativo',
            'category_id.exists' => 'La categoría seleccionada no existe',
        ];
    }
}
```

---

### 6️⃣ Events & Listeners

**Desacoplar acciones secundarias** de la lógica principal.

#### Event
```php
<?php

namespace App\Modules\Inventory\Events;

use App\Modules\Inventory\Models\Product;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Product $product
    ) {}
}
```

#### Listener
```php
<?php

namespace App\Modules\Inventory\Listeners;

use App\Modules\Inventory\Events\StockUpdated;
use App\Core\Shared\Services\NotificationService;

class NotifyLowStock
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    public function handle(StockUpdated $event): void
    {
        if ($event->product->isLowStock()) {
            $this->notificationService->send(
                'admin@example.com',
                "Stock bajo: {$event->product->name}"
            );
        }
    }
}
```

---

## 🌐 Core Compartido

El directorio `app/Core/` contiene funcionalidad **transversal** utilizada por todos los módulos.

### Shared Services

Servicios que cruzan múltiples módulos:

```php
<?php
// app/Core/Shared/Services/AuditService.php

namespace App\Core\Shared\Services;

use Illuminate\Support\Facades\Log;

class AuditService
{
    public function log(string $action, mixed $data): void
    {
        Log::channel('audit')->info($action, [
            'user_id' => auth()->id(),
            'tenant_id' => tenant('id'),
            'data' => $data,
            'timestamp' => now()
        ]);
    }
}
```

```php
<?php
// app/Core/Shared/Services/FileStorageService.php

namespace App\Core\Shared\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class FileStorageService
{
    public function store(UploadedFile $file, string $path): string
    {
        $tenantId = tenant('id');
        $filename = time() . '_' . $file->getClientOriginalName();
        
        return Storage::disk('tenant')
            ->putFileAs("{$tenantId}/{$path}", $file, $filename);
    }

    public function delete(string $path): bool
    {
        return Storage::disk('tenant')->delete($path);
    }
}
```

### Shared Traits

Traits reutilizables:

```php
<?php
// app/Core/Shared/Traits/HasUuid.php

namespace App\Core\Shared\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    protected static function bootHasUuid(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }
}
```

```php
<?php
// app/Core/Shared/Traits/Auditable.php

namespace App\Core\Shared\Traits;

trait Auditable
{
    protected static function bootAuditable(): void
    {
        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->log('created');
        });

        static::updated(function ($model) {
            activity()
                ->performedOn($model)
                ->log('updated');
        });
    }
}
```

### Shared Contracts (Interfaces)

```php
<?php
// app/Core/Shared/Contracts/Searchable.php

namespace App\Core\Shared\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface Searchable
{
    public function search(string $query): Collection;
}
```

### Shared Exceptions

```php
<?php
// app/Core/Shared/Exceptions/BusinessException.php

namespace App\Core\Shared\Exceptions;

use Exception;

class BusinessException extends Exception
{
    public function __construct(string $message, int $code = 422)
    {
        parent::__construct($message, $code);
    }
}
```

---

## 🏢 Multi-Tenancy en Laravel

**Multi-Tenancy** permite que una sola aplicación sirva a múltiples clientes (tenants) con **aislamiento total de datos**.

### ¿Por qué Multi-Tenancy?

En un ERP como Novex:
- Cada **empresa** es un tenant
- Datos completamente aislados
- Base de datos separada por tenant
- Seguridad y privacidad garantizadas

---

## 🧠 Estrategia Multi-Tenant Elegida

### ✅ Database por Tenant

```
central_db          # Base de datos central (tenants, domains)
tenant_1_db         # Base de datos del tenant 1
tenant_2_db         # Base de datos del tenant 2
tenant_3_db         # Base de datos del tenant 3
```

**Ventajas:**
- ✅ Máximo aislamiento de datos
- ✅ Backups independientes por cliente
- ✅ Escalado por cliente
- ✅ Cumplimiento normativo (GDPR, LOPD)
- ✅ Performance optimizada por tenant
- ✅ Migrar clientes entre servidores fácilmente

**Desventajas:**
- ⚠️ Más consumo de recursos
- ⚠️ Migraciones deben ejecutarse por tenant

---

## 📦 Paquete: stancl/tenancy

El paquete **Tenancy for Laravel** (`stancl/tenancy`) es el estándar de la industria.

### Instalación

```bash
composer require stancl/tenancy
php artisan tenancy:install
php artisan migrate
```

### Configuración Básica

```php
// config/tenancy.php
return [
    'tenant_model' => \App\Core\Tenant\Models\Tenant::class,
    'id_generator' => Stancl\Tenancy\UUIDGenerator::class,
    
    'database' => [
        'prefix' => 'tenant',
        'suffix' => '',
        'central_connection' => env('DB_CONNECTION', 'mysql'),
    ],
    
    'features' => [
        Stancl\Tenancy\Features\TenantConfig::class,
        Stancl\Tenancy\Features\TenancyBootstrapper::class,
    ],
];
```

### Modelo Tenant

```php
<?php
// app/Core/Tenant/Models/Tenant.php

namespace App\Core\Tenant\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    protected $fillable = [
        'id',
        'name',
        'email',
        'domain',
        'database',
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'domain',
            'database',
        ];
    }
}
```

### Middleware

```php
<?php
// app/Http/Middleware/TenantMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Tenancy;
use Stancl\Tenancy\Resolvers\DomainTenantResolver;

class TenantMiddleware
{
    public function __construct(
        private Tenancy $tenancy,
        private DomainTenantResolver $resolver
    ) {}

    public function handle(Request $request, Closure $next)
    {
        $this->tenancy->initialize($this->resolver->resolve($request));

        return $next($request);
    }
}
```

### Registro en Kernel

```php
// bootstrap/app.php o app/Http/Kernel.php
protected $middlewareGroups = [
    'tenant' => [
        'web',
        \App\Http\Middleware\TenantMiddleware::class,
    ],
];
```

### Rutas Tenant

```php
// routes/tenant.php
Route::middleware(['tenant'])->group(function () {
    // Todas estas rutas son tenant-aware
    
    Route::prefix('inventory')->group(function () {
        Route::apiResource('products', ProductController::class);
        Route::post('products/{id}/adjust-stock', [ProductController::class, 'adjustStock']);
    });
    
    Route::prefix('sales')->group(function () {
        Route::apiResource('orders', OrderController::class);
    });
    
    Route::prefix('crm')->group(function () {
        Route::apiResource('customers', CustomerController::class);
    });
});
```

### Crear Tenant

```php
<?php
// app/Core/Tenant/Services/TenantService.php

namespace App\Core\Tenant\Services;

use App\Core\Tenant\Models\Tenant;
use Illuminate\Support\Str;

class TenantService
{
    public function createTenant(array $data): Tenant
    {
        $tenant = Tenant::create([
            'id' => $data['id'] ?? Str::uuid()->toString(),
            'name' => $data['name'],
            'email' => $data['email'],
            'domain' => $data['domain'],
        ]);

        // Crear dominio
        $tenant->domains()->create([
            'domain' => $data['domain']
        ]);

        return $tenant;
    }

    public function deleteTenant(string $tenantId): bool
    {
        $tenant = Tenant::find($tenantId);
        
        if (!$tenant) {
            return false;
        }

        $tenant->delete(); // Esto también elimina la base de datos
        
        return true;
    }
}
```

---

## 🔄 Flujo de Trabajo Completo

### Ejemplo: Crear un Producto en Tenant "empresa-abc"

```
1. HTTP Request
   ↓
   GET https://empresa-abc.novex.com/api/inventory/products

2. TenantMiddleware detecta tenant por dominio
   ↓
   Tenant ID: "uuid-empresa-abc"

3. Tenancy::initialize() cambia conexión de BD
   ↓
   DB connection → tenant_empresa_abc_db

4. ProductController recibe request
   ↓
   public function store(StoreProductRequest $request)

5. Validación en StoreProductRequest
   ↓
   Validar: name, sku, price, stock_quantity

6. Controller delega a ProductService
   ↓
   $this->productService->createProduct($request->validated())

7. ProductService orquesta la operación
   ↓
   - Valida SKU único via ProductRepository
   - Crea producto via ProductRepository
   - Dispara evento ProductCreated
   - Registra auditoría via AuditService

8. ProductRepository accede a datos
   ↓
   Product::create($data) → Inserta en tenant_empresa_abc_db.products

9. Evento ProductCreated disparado
   ↓
   Listeners ejecutados (notificaciones, cache, etc.)

10. Respuesta HTTP
   ↓
   return response()->json($product, 201)
```

### Diagrama de Flujo

```
┌─────────────────────────────────────────────────────────┐
│  HTTP Request (empresa-abc.novex.com/api/products)     │
└──────────────────────┬──────────────────────────────────┘
                       ↓
              ┌────────────────┐
              │ TenantMiddleware │
              └────────┬───────┘
                       ↓
              Initialize Tenant
              (empresa-abc DB)
                       ↓
          ┌────────────────────────┐
          │  ProductController      │
          │  - store()              │
          └────────┬───────────────┘
                   ↓
       ┌───────────────────────┐
       │ StoreProductRequest   │
       │ (Validación)          │
       └───────┬───────────────┘
               ↓
    ┌──────────────────────────┐
    │  ProductService           │
    │  - createProduct()        │
    └──────┬───────────────────┘
           ↓
    ┌──────────────────────────┐
    │  ProductRepository        │
    │  - create()               │
    └──────┬───────────────────┘
           ↓
    ┌──────────────────────────┐
    │  Product Model            │
    │  (Tenant DB)              │
    └──────┬───────────────────┘
           ↓
    ┌──────────────────────────┐
    │  Events & Listeners       │
    │  - ProductCreated         │
    └───────────────────────────┘
```

---

## 📅 Fases de Desarrollo Detalladas

Esta sección describe el plan de implementación por fases para construir Novex v2 con la arquitectura Modular + Service + Repository Pattern.

---

### 🔵 FASE 1: Estructura Base y Multi-Tenancy (Semana 1)

**Objetivos:**
- Crear estructura de carpetas modular
- Configurar Multi-Tenancy con stancl/tenancy
- Establecer Core compartido
- Configurar Service Providers

#### Tareas:

**1.1. Crear Estructura de Carpetas**
```bash
mkdir -p app/Modules
mkdir -p app/Core/Shared/{Services,Traits,Helpers,Contracts,Exceptions}
mkdir -p app/Core/Tenant/{Models,Services,Middleware}
```

**1.2. Instalar y Configurar Multi-Tenancy**
```bash
composer require stancl/tenancy
php artisan tenancy:install
php artisan migrate
```

Configurar:
- `config/tenancy.php`
- Modelo `Tenant` personalizado
- Middleware `TenantMiddleware`
- Rutas `routes/tenant.php`

**1.3. Crear Core Compartido**
Implementar:
- `AuditService`
- `FileStorageService`
- `NotificationService`
- Traits: `HasUuid`, `Auditable`
- Excepciones base

**1.4. Configurar Autoloading**
```json
// composer.json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "App\\Modules\\": "app/Modules/",
        "App\\Core\\": "app/Core/"
    }
}
```

```bash
composer dump-autoload
```

**1.5. Testing de Multi-Tenancy**
- Crear tenant de prueba
- Verificar aislamiento de datos
- Test de cambio de contexto

**Entregables:**
- ✅ Estructura de carpetas completa
- ✅ Multi-Tenancy funcionando
- ✅ Core compartido implementado
- ✅ Tests de tenancy pasando

---

### 🟢 FASE 2: Módulo Piloto - Inventory (Semanas 2-3)

**Objetivos:**
- Implementar módulo completo de Inventario
- Establecer patrones y convenciones
- Crear documentación de referencia
- Implementar tests

#### Tareas:

**2.1. Crear Estructura del Módulo**
```bash
php artisan make:module Inventory
```

Crear:
```
app/Modules/Inventory/
├── Models/
├── Repositories/Interfaces/
├── Repositories/Implementations/
├── Services/
├── Http/Controllers/
├── Http/Requests/
├── Events/
├── Listeners/
├── Exceptions/
└── Providers/
```

**2.2. Implementar Entidad Product**

**a) Migration**
```bash
php artisan make:migration create_products_table --tenant
```

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->uuid('uuid')->unique();
    $table->string('name');
    $table->string('sku')->unique();
    $table->text('description')->nullable();
    $table->decimal('price', 10, 2);
    $table->integer('stock_quantity')->default(0);
    $table->integer('min_stock')->default(10);
    $table->foreignId('category_id')->constrained();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    $table->softDeletes();
});
```

**b) Model**
```php
// app/Modules/Inventory/Models/Product.php
- Fillable, casts, relationships
- Business logic methods
- Scopes
```

**c) Repository Interface & Implementation**
```php
// ProductRepositoryInterface
// ProductRepository
```

**d) Service**
```php
// ProductService
- CRUD operations
- Stock management
- Business validations
```

**e) Controller**
```php
// ProductController
- index, store, show, update, destroy
- adjustStock, lowStock
```

**f) Form Requests**
```php
// StoreProductRequest
// UpdateProductRequest
```

**g) Events & Listeners**
```php
// ProductCreated, StockUpdated
// NotifyLowStock
```

**2.3. Implementar Entidad Category**
- Model + Migration
- Repository
- Service
- Controller
- Requests

**2.4. Implementar StockMovement (Auditoría de Stock)**
- Model + Migration
- Repository
- Tracking de cambios de inventario

**2.5. Service Provider del Módulo**
```php
// app/Modules/Inventory/Providers/InventoryServiceProvider.php

namespace App\Modules\Inventory\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Inventory\Repositories\Interfaces\ProductRepositoryInterface;
use App\Modules\Inventory\Repositories\Implementations\ProductRepository;

class InventoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repositories
        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );
        
        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );
    }

    public function boot(): void
    {
        // Register routes
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        
        // Register migrations
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        
        // Register events
        // Event::listen(...);
    }
}
```

Registrar en `config/app.php`:
```php
'providers' => [
    // ...
    App\Modules\Inventory\Providers\InventoryServiceProvider::class,
],
```

**2.6. Rutas del Módulo**
```php
// app/Modules/Inventory/Routes/api.php
Route::middleware(['tenant'])->prefix('api/inventory')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::post('products/{id}/adjust-stock', [ProductController::class, 'adjustStock']);
    Route::get('products/reports/low-stock', [ProductController::class, 'lowStock']);
    
    Route::apiResource('categories', CategoryController::class);
});
```

**2.7. Testing**

**a) Unit Tests**
```php
// tests/Unit/Modules/Inventory/Models/ProductTest.php
- Test hasStock()
- Test decreaseStock()
- Test increaseStock()
- Test isLowStock()
```

**b) Feature Tests**
```php
// tests/Feature/Modules/Inventory/ProductControllerTest.php
- Test CRUD operations
- Test stock adjustments
- Test low stock report
- Test tenant isolation
```

**c) Repository Tests**
```php
// tests/Unit/Modules/Inventory/Repositories/ProductRepositoryTest.php
- Test complex queries
- Test filters
- Test eager loading
```

**2.8. Documentación**
Crear: `docs/modulos/inventory.md`
- Descripción del módulo
- Entidades y relaciones
- Endpoints API
- Ejemplos de uso
- Patrones implementados

**Entregables:**
- ✅ Módulo Inventory completo y funcional
- ✅ CRUD de Products y Categories
- ✅ Gestión de Stock
- ✅ Tests unitarios y de integración (>80% cobertura)
- ✅ Documentación del módulo
- ✅ Patrones establecidos para replicar

---

### 🟡 FASE 3: Módulo Sales (Semana 4)

**Objetivos:**
- Replicar patrones del módulo Inventory
- Implementar Orders y OrderItems
- Integración con Inventory (decrementar stock)

#### Tareas:

**3.1. Estructura del Módulo**
```
app/Modules/Sales/
├── Models/ (Order, OrderItem)
├── Repositories/
├── Services/
├── Http/
├── Events/
├── Listeners/
└── Providers/
```

**3.2. Implementar Entidades**
- **Order**: Cliente, fecha, total, estado
- **OrderItem**: Producto, cantidad, precio unitario

**3.3. Lógica de Negocio**
- Crear orden → decrementar stock
- Cancelar orden → incrementar stock
- Calcular totales automáticamente

**3.4. Integración con Inventory**
```php
// SalesService integra con ProductService
public function createOrder(array $data): Order
{
    DB::transaction(function () use ($data) {
        $order = $this->orderRepository->create($data);
        
        foreach ($data['items'] as $item) {
            // Decrementar stock via ProductService
            $this->productService->adjustStock(
                $item['product_id'],
                $item['quantity'],
                'decrease'
            );
        }
        
        return $order;
    });
}
```

**3.5. Tests**
- Test creación de orden
- Test cancelación de orden
- Test integración con inventario
- Test tenant isolation

**Entregables:**
- ✅ Módulo Sales funcional
- ✅ Integración con Inventory
- ✅ Tests completos

---

### 🟠 FASE 4: Módulo CRM (Semana 5)

**Objetivos:**
- Gestión de Clientes y Contactos
- Integración con Sales

#### Tareas:

**4.1. Estructura del Módulo**
```
app/Modules/CRM/
├── Models/ (Customer, Contact, Lead)
├── Repositories/
├── Services/
├── Http/
└── Providers/
```

**4.2. Implementar Entidades**
- **Customer**: Empresa cliente
- **Contact**: Persona de contacto
- **Lead**: Oportunidad de venta (opcional)

**4.3. Integración con Sales**
- Orders vinculadas a Customers

**4.4. Tests**

**Entregables:**
- ✅ Módulo CRM funcional
- ✅ Integración con Sales

---

### 🔴 FASE 5: Módulo Invoicing (Semana 6)

**Objetivos:**
- Gestión de Facturas
- Generación de PDF
- Control de pagos

#### Tareas:

**4.1. Estructura del Módulo**
```
app/Modules/Invoicing/
├── Models/ (Invoice, InvoiceItem, Payment)
├── Repositories/
├── Services/
├── Http/
└── Providers/
```

**4.2. Implementar Entidades**
- **Invoice**: Cliente, fecha, subtotal, IVA, total
- **InvoiceItem**: Producto/servicio, cantidad, precio
- **Payment**: Factura, método de pago, monto

**4.3. Generación de PDF**
```bash
composer require barryvdh/laravel-dompdf
```

**4.4. Integración**
- Invoice desde Order
- Payment tracking

**Entregables:**
- ✅ Módulo Invoicing funcional
- ✅ Generación de PDF
- ✅ Control de pagos

---

### 🟣 FASE 6: Optimización y Refactoring (Semana 7)

**Objetivos:**
- Extraer servicios compartidos
- Optimizar queries
- Refactorizar código duplicado
- Documentación final

#### Tareas:

**6.1. Identificar Código Duplicado**
- Extraer a `app/Core/Shared/Services`
- Crear Traits comunes

**6.2. Optimización**
- Implementar caché en queries frecuentes
- Eager loading para evitar N+1
- Índices en base de datos

**6.3. Documentación**
- Guía de desarrollo
- Convenciones del equipo
- Diagrama de arquitectura actualizado
- API documentation (Swagger/OpenAPI)

**6.4. Code Quality**
```bash
composer require --dev phpstan/phpstan
composer require --dev larastan/larastan

./vendor/bin/phpstan analyse
```

**Entregables:**
- ✅ Código optimizado
- ✅ Documentación completa
- ✅ Análisis estático pasando

---

### 📊 Resumen de Fases

| Fase | Duración | Enfoque | Entregables |
|------|----------|---------|-------------|
| **Fase 1** | Semana 1 | Estructura Base + Multi-Tenancy | Estructura completa, Tenancy funcionando |
| **Fase 2** | Semanas 2-3 | Módulo Inventory (Piloto) | Módulo completo + patrones establecidos |
| **Fase 3** | Semana 4 | Módulo Sales | Módulo Sales + integración Inventory |
| **Fase 4** | Semana 5 | Módulo CRM | Módulo CRM + integración Sales |
| **Fase 5** | Semana 6 | Módulo Invoicing | Módulo Invoicing + PDF |
| **Fase 6** | Semana 7 | Optimización | Refactoring + documentación final |

**Total: ~7 semanas** para MVP completo con 4 módulos principales.

---

## 🧪 Testing

## 🧪 Testing

### Estrategia de Testing

**3 niveles de testing:**
1. **Unit Tests**: Lógica de negocio en Models
2. **Integration Tests**: Repositories y Services
3. **Feature Tests**: Controllers y flujo completo

### Testing con Multi-Tenancy

```php
<?php

namespace Tests\Feature\Modules\Inventory;

use Tests\TestCase;
use App\Core\Tenant\Models\Tenant;
use App\Modules\Inventory\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear tenant de prueba
        $this->tenant = Tenant::create([
            'id' => 'test-tenant',
            'name' => 'Test Company',
            'email' => 'test@example.com',
        ]);

        // Crear dominio para el tenant
        $this->tenant->domains()->create([
            'domain' => 'test.localhost'
        ]);

        // Inicializar contexto del tenant
        tenancy()->initialize($this->tenant);
    }

    /** @test */
    public function it_can_create_a_product(): void
    {
        $data = [
            'name' => 'Laptop HP',
            'sku' => 'LAP-HP-001',
            'price' => 999.99,
            'stock_quantity' => 50,
            'min_stock' => 10,
            'category_id' => 1,
        ];

        $response = $this->postJson('/api/inventory/products', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('products', [
            'name' => 'Laptop HP',
            'sku' => 'LAP-HP-001',
        ]);
    }

    /** @test */
    public function it_prevents_duplicate_sku(): void
    {
        Product::factory()->create(['sku' => 'LAP-HP-001']);

        $data = [
            'name' => 'Another Product',
            'sku' => 'LAP-HP-001',
            'price' => 500,
        ];

        $response = $this->postJson('/api/inventory/products', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['sku']);
    }

    /** @test */
    public function it_ensures_tenant_isolation(): void
    {
        // Crear producto en tenant 1
        $product = Product::factory()->create(['name' => 'Tenant 1 Product']);

        // Cambiar a tenant 2
        $tenant2 = Tenant::create(['id' => 'tenant-2', 'name' => 'Tenant 2']);
        tenancy()->initialize($tenant2);

        // Verificar que no se puede acceder al producto del tenant 1
        $this->assertDatabaseMissing('products', [
            'name' => 'Tenant 1 Product'
        ]);
    }

    /** @test */
    public function it_can_adjust_stock(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 100]);

        $response = $this->postJson("/api/inventory/products/{$product->id}/adjust-stock", [
            'quantity' => 10,
            'type' => 'decrease'
        ]);

        $response->assertStatus(200);
        
        $product->refresh();
        $this->assertEquals(90, $product->stock_quantity);
    }

    /** @test */
    public function it_throws_exception_on_insufficient_stock(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 5]);

        $response = $this->postJson("/api/inventory/products/{$product->id}/adjust-stock", [
            'quantity' => 10,
            'type' => 'decrease'
        ]);

        $response->assertStatus(422);
    }
}
```

### Unit Test Example

```php
<?php

namespace Tests\Unit\Modules\Inventory\Models;

use Tests\TestCase;
use App\Modules\Inventory\Models\Product;
use App\Modules\Inventory\Exceptions\InsufficientStockException;

class ProductTest extends TestCase
{
    /** @test */
    public function it_checks_if_has_stock(): void
    {
        $product = new Product(['stock_quantity' => 10]);

        $this->assertTrue($product->hasStock(5));
        $this->assertTrue($product->hasStock(10));
        $this->assertFalse($product->hasStock(11));
    }

    /** @test */
    public function it_decreases_stock(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 100]);

        $product->decreaseStock(30);

        $this->assertEquals(70, $product->stock_quantity);
    }

    /** @test */
    public function it_throws_exception_when_insufficient_stock(): void
    {
        $this->expectException(InsufficientStockException::class);

        $product = Product::factory()->create(['stock_quantity' => 5]);
        $product->decreaseStock(10);
    }

    /** @test */
    public function it_identifies_low_stock(): void
    {
        $product = new Product([
            'stock_quantity' => 8,
            'min_stock' => 10
        ]);

        $this->assertTrue($product->isLowStock());
    }
}
```

### Repository Test Example

```php
<?php

namespace Tests\Unit\Modules\Inventory\Repositories;

use Tests\TestCase;
use App\Modules\Inventory\Models\Product;
use App\Modules\Inventory\Repositories\Implementations\ProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected ProductRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProductRepository();
    }

    /** @test */
    public function it_finds_products_with_low_stock(): void
    {
        Product::factory()->create(['stock_quantity' => 5, 'min_stock' => 10]);
        Product::factory()->create(['stock_quantity' => 50, 'min_stock' => 10]);

        $lowStockProducts = $this->repository->getLowStockProducts(10);

        $this->assertCount(1, $lowStockProducts);
    }

    /** @test */
    public function it_calculates_stock_value(): void
    {
        Product::factory()->create(['price' => 100, 'stock_quantity' => 10]);

        $products = $this->repository->getProductsWithStockValue();

        $this->assertEquals(1000, $products->first()->stock_value);
    }
}
```

---

## 📏 Reglas de Oro

### 1. Un Módulo = Una Carpeta
Cada módulo de negocio vive completamente aislado en `app/Modules/{ModuleName}/`

### 2. Lógica de Negocio en Models
```php
// ✅ CORRECTO
class Product extends Model
{
    public function hasStock(int $quantity): bool
    {
        return $this->stock_quantity >= $quantity;
    }
}

// ❌ INCORRECTO - Lógica en Controller
class ProductController 
{
    public function checkStock($id, $quantity)
    {
        $product = Product::find($id);
        return $product->stock_quantity >= $quantity; // No!
    }
}
```

### 3. Lógica de Aplicación en Services
```php
// ✅ CORRECTO - Service orquesta
class ProductService
{
    public function createProduct(array $data): Product
    {
        $product = $this->repository->create($data);
        event(new ProductCreated($product));
        $this->auditService->log('product_created', $product);
        return $product;
    }
}

// ❌ INCORRECTO - Controller hace demasiado
class ProductController
{
    public function store(Request $request)
    {
        $product = Product::create($request->all());
        event(new ProductCreated($product));
        Log::info('Product created');
        return response()->json($product);
    }
}
```

### 4. Data Access en Repositories
```php
// ✅ CORRECTO
class ProductRepository implements ProductRepositoryInterface
{
    public function getLowStockProducts(int $threshold): Collection
    {
        return Product::where('stock_quantity', '<=', $threshold)->get();
    }
}

// ❌ INCORRECTO - Query en Service
class ProductService
{
    public function getLowStock()
    {
        return Product::where('stock_quantity', '<=', 10)->get(); // No!
    }
}
```

### 5. Validación en FormRequests
```php
// ✅ CORRECTO
class StoreProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|unique:products',
        ];
    }
}

// ❌ INCORRECTO - Validación en Controller
$request->validate([...]); // No en controllers!
```

### 6. Controllers Delgados
```php
// ✅ CORRECTO
class ProductController extends Controller
{
    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->createProduct(
            $request->validated()
        );
        
        return response()->json($product, 201);
    }
}

// ❌ INCORRECTO - Fat Controller
class ProductController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([...]);
        
        if (Product::where('sku', $validated['sku'])->exists()) {
            return response()->json(['error' => 'SKU exists'], 422);
        }
        
        $product = Product::create($validated);
        event(new ProductCreated($product));
        Log::info('Product created', ['id' => $product->id]);
        Cache::forget('products');
        
        return response()->json($product, 201);
    }
}
```

### 7. Inyección de Dependencias
```php
// ✅ CORRECTO
class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $repository,
        private AuditService $auditService
    ) {}
}

// ❌ INCORRECTO
class ProductService
{
    public function createProduct()
    {
        $repository = new ProductRepository(); // No instanciar directamente
        $repository->create(...);
    }
}
```

### 8. Nombres Consistentes
```
Model: Product
Repository Interface: ProductRepositoryInterface
Repository Implementation: ProductRepository
Service: ProductService
Controller: ProductController
Request: StoreProductRequest, UpdateProductRequest
Event: ProductCreated, ProductUpdated
```

### 9. Transacciones en Operaciones Complejas
```php
// ✅ CORRECTO
public function createOrder(array $data): Order
{
    return DB::transaction(function () use ($data) {
        $order = $this->orderRepository->create($data);
        
        foreach ($data['items'] as $item) {
            $this->productService->decreaseStock($item['product_id'], $item['qty']);
        }
        
        return $order;
    });
}
```

### 10. Multi-Tenancy Siempre Activo
```php
// Todas las rutas de módulos DEBEN usar middleware 'tenant'
Route::middleware(['tenant'])->group(function () {
    // Rutas aquí
});

// NUNCA hacer queries sin contexto de tenant
// El middleware se encarga automáticamente
```

---

## 🔐 Seguridad y Aislamiento

### Aislamiento de Datos
- ✅ Cada tenant tiene su propia base de datos
- ✅ Middleware automático detecta tenant por dominio
- ✅ Imposible acceder a datos de otro tenant
- ✅ Cache y storage también aislados por tenant

### Cache Tenant-Aware
```php
Cache::tags(['tenant:' . tenant('id'), 'products'])->remember(...);
```

### Storage Tenant-Aware
```php
Storage::disk('tenant')->put(
    tenant('id') . '/uploads/file.pdf',
    $file
);
```

### Jobs Tenant-Aware
```php
use Stancl\Tenancy\Concerns\QueueableTenantAware;

class ProcessOrder implements ShouldQueue
{
    use QueueableTenantAware;
    
    // El job mantiene contexto del tenant automáticamente
}
```

---

## 📚 Referencias y Recursos

### Documentación Oficial
- [Laravel Documentation](https://laravel.com/docs)
- [Tenancy for Laravel](https://tenancyforlaravel.com/)
- [Repository Pattern](https://martinfowler.com/eaaCatalog/repository.html)
- [Service Layer Pattern](https://martinfowler.com/eaaCatalog/serviceLayer.html)

### Libros Recomendados
- **Domain-Driven Design** – Eric Evans
- **Patterns of Enterprise Application Architecture** – Martin Fowler
- **Clean Architecture** – Robert C. Martin (para entender conceptos avanzados)

### Artículos
- [Laravel Best Practices](https://github.com/alexeymezenin/laravel-best-practices)
- [Laravel Repository Pattern](https://dev.to/carlomigueldy/getting-started-with-repository-pattern-in-laravel-using-inheritance-and-dependency-injection-2ohe)
- [Modular Laravel Applications](https://laraveldaily.com/post/modular-structure-laravel-projects)

---

## 🎯 Conclusión

Esta arquitectura **Modular + Service + Repository Pattern** proporciona:

✅ **Balance perfecto** entre simplicidad y estructura  
✅ **Productividad inmediata** sin sacrificar calidad  
✅ **Organización modular** ideal para ERP  
✅ **Escalabilidad** conforme crece el proyecto  
✅ **Testabilidad** sin complejidad excesiva  
✅ **Mantenibilidad** a largo plazo  
✅ **Onboarding rápido** para nuevos desarrolladores  

### ¿Cuándo migrar a Clean Architecture?

Considera migrar cuando:
- El equipo crezca a **5+ desarrolladores**
- Necesites **independencia total del framework**
- La complejidad del dominio **requiera Value Objects y Aggregates**
- Tengas recursos para **invertir 2-3x más tiempo** en desarrollo

Hasta entonces, esta arquitectura te permitirá:
- Avanzar rápido
- Mantener código limpio
- Escalar sin problemas
- Evolucionar gradualmente

> **"La mejor arquitectura es la que se adapta a tu contexto, no la más sofisticada."**

---

## 🚀 Próximos Pasos

1. **Iniciar Fase 1**: Estructura Base + Multi-Tenancy
2. **Crear tenant de prueba** para desarrollo
3. **Implementar módulo Inventory** como piloto
4. **Documentar patrones** mientras desarrollas
5. **Replicar en otros módulos**

---

**Documentación creada para**: Novex v2 ERP  
**Fecha**: 2026-02-10  
**Versión**: 1.0  
**Arquitectura**: Modular + Service + Repository Pattern con Multi-Tenancy

[Tenancy for Laravel](https://tenancyforlaravel.com/)