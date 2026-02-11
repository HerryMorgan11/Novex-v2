---
title: '[Fase 5.2] Migraciones y Modelos Base del Módulo Inventario'
labels: fase-5, inventory, database, migrations, priority-high
assignees:
milestone: Fase 5 - Módulo Inventario
---

## 🗄️ Tarea: Crear Migraciones y Modelos Eloquent del Inventario

### Descripción

Implementar todas las migraciones y modelos Eloquent para el módulo de inventario. Incluye tablas para productos, categorías, marcas, almacenes, stock y movimientos de inventario.

### Objetivos

#### Migraciones

- [ ] Crear tabla `categories`
- [ ] Crear tabla `brands`
- [ ] Crear tabla `products`
- [ ] Crear tabla `product_variants`
- [ ] Crear tabla `warehouses`
- [ ] Crear tabla `stock_locations`
- [ ] Crear tabla `stock`
- [ ] Crear tabla `stock_movements`
- [ ] Crear tabla `stock_transfers`
- [ ] Crear tabla `stock_transfer_items`
- [ ] Crear tabla `suppliers`
- [ ] Crear tabla `product_supplier`

#### Modelos Eloquent

- [ ] Modelo Category con relaciones
- [ ] Modelo Brand con relaciones
- [ ] Modelo Product con relaciones
- [ ] Modelo ProductVariant
- [ ] Modelo Warehouse
- [ ] Modelo StockLocation
- [ ] Modelo Stock con scopes útiles
- [ ] Modelo StockMovement
- [ ] Modelo StockTransfer
- [ ] Modelo Supplier
- [ ] Modelo ProductSupplier (pivot)

#### Factories & Seeders

- [ ] Factory para Category
- [ ] Factory para Brand
- [ ] Factory para Product
- [ ] Factory para Stock
- [ ] Factory para StockMovement
- [ ] Seeder para datos iniciales

### Implementación

#### 1. Migraciones

##### Migration: Create Categories Table

`database/migrations/[timestamp]_create_categories_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->tenantId(); // Para multi-tenancy
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('order')->default(0);
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index(['tenant_id', 'is_active']);
            $table->index(['parent_id']);

            // Foreign keys
            $table->foreign('parent_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
```

##### Migration: Create Brands Table

`database/migrations/[timestamp]_create_brands_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->tenantId();
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('website')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
```

##### Migration: Create Products Table

`database/migrations/[timestamp]_create_products_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->tenantId();
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->string('sku')->unique(); // Stock Keeping Unit
            $table->string('barcode')->nullable()->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id')->nullable();

            // Precios
            $table->decimal('cost', 10, 2); // Costo de compra
            $table->decimal('retail_price', 10, 2); // Precio de venta
            $table->decimal('wholesale_price', 10, 2)->nullable(); // Precio mayorista

            // Dimensiones
            $table->decimal('weight', 8, 2)->nullable(); // en kg
            $table->decimal('length', 8, 2)->nullable(); // en cm
            $table->decimal('width', 8, 2)->nullable(); // en cm
            $table->decimal('height', 8, 2)->nullable(); // en cm

            // Información
            $table->integer('reorder_level')->default(10); // Stock mínimo
            $table->integer('reorder_quantity')->default(50); // Cantidad a ordenar
            $table->string('unit')->default('pcs'); // Unidad de medida
            $table->integer('shelf_life')->nullable(); // Vida útil en días

            // Estado
            $table->boolean('is_active')->default(true);
            $table->boolean('is_trackable')->default(true);
            $table->boolean('requires_lot_tracking')->default(false);
            $table->string('image_path')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index(['category_id']);
            $table->index(['brand_id']);
            $table->index(['tenant_id', 'is_active']);
            $table->index(['sku']);

            // Foreign keys
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('restrict');

            $table->foreign('brand_id')
                ->references('id')
                ->on('brands')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

##### Migration: Create Warehouses Table

`database/migrations/[timestamp]_create_warehouses_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->tenantId();
            $table->string('name')->index();
            $table->string('code')->unique();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
```

##### Migration: Create Stock Table

`database/migrations/[timestamp]_create_stock_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock', function (Blueprint $table) {
            $table->id();
            $table->tenantId();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->integer('quantity')->default(0);
            $table->integer('reserved_quantity')->default(0); // Reservado para órdenes
            $table->integer('available_quantity')->virtualAs('quantity - reserved_quantity');
            $table->string('lot_number')->nullable();
            $table->date('expiration_date')->nullable();
            $table->date('last_counted_at')->nullable();
            $table->timestamps();

            // Índices
            $table->unique(['product_id', 'warehouse_id', 'lot_number']);
            $table->index(['warehouse_id']);
            $table->index(['quantity']);
            $table->index(['last_counted_at']);

            // Foreign keys
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->foreign('warehouse_id')
                ->references('id')
                ->on('warehouses')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock');
    }
};
```

##### Migration: Create Stock Movements Table

`database/migrations/[timestamp]_create_stock_movements_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->tenantId();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->enum('type', [
                'purchase', // Compra de proveedor
                'sale', // Venta
                'adjustment', // Ajuste manual
                'damage', // Daño/pérdida
                'transfer', // Transferencia entre almacenes
                'return', // Devolución
                'count', // Conteo de inventario
            ]);
            $table->integer('quantity'); // Cantidad positiva o negativa
            $table->string('reference')->nullable(); // Número de PO, factura, etc
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            // Índices
            $table->index(['product_id']);
            $table->index(['warehouse_id']);
            $table->index(['type']);
            $table->index(['created_at']);
            $table->index(['created_by']);

            // Foreign keys
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->foreign('warehouse_id')
                ->references('id')
                ->on('warehouses')
                ->onDelete('cascade');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
```

##### Migration: Create Stock Transfers Table

`database/migrations/[timestamp]_create_stock_transfers_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->tenantId();
            $table->string('reference_number')->unique();
            $table->unsignedBigInteger('from_warehouse_id');
            $table->unsignedBigInteger('to_warehouse_id');
            $table->enum('status', ['pending', 'in_transit', 'received', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('received_by')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamps();

            // Índices
            $table->index(['from_warehouse_id']);
            $table->index(['to_warehouse_id']);
            $table->index(['status']);
            $table->index(['created_at']);

            // Foreign keys
            $table->foreign('from_warehouse_id')
                ->references('id')
                ->on('warehouses')
                ->onDelete('restrict');

            $table->foreign('to_warehouse_id')
                ->references('id')
                ->on('warehouses')
                ->onDelete('restrict');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');

            $table->foreign('received_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
    }
};
```

##### Migration: Create Suppliers Table

`database/migrations/[timestamp]_create_suppliers_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->tenantId();
            $table->string('name')->index();
            $table->string('company_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->string('tax_id')->nullable(); // RUC, RFC, etc
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
```

##### Migration: Create Product Supplier Pivot Table

`database/migrations/[timestamp]_create_product_supplier_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_supplier', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('supplier_id');
            $table->string('supplier_sku')->nullable();
            $table->decimal('cost', 10, 2);
            $table->integer('lead_time_days')->default(0);
            $table->integer('minimum_order')->default(1);
            $table->timestamps();

            $table->unique(['product_id', 'supplier_id']);
            $table->index(['supplier_id']);

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_supplier');
    }
};
```

#### 2. Modelos Eloquent

`app/Modules/Inventory/Domain/Models/Product.php`

```php
<?php

namespace App\Modules\Inventory\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, BelongsToMany};
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Product extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'sku', 'barcode', 'description',
        'category_id', 'brand_id', 'cost', 'retail_price', 'wholesale_price',
        'weight', 'length', 'width', 'height', 'reorder_level',
        'reorder_quantity', 'unit', 'shelf_life', 'is_active',
        'is_trackable', 'requires_lot_tracking', 'image_path',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_trackable' => 'boolean',
        'requires_lot_tracking' => 'boolean',
        'cost' => 'decimal:2',
        'retail_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
    ];

    // Relaciones
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function stock(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class)
            ->withPivot('supplier_sku', 'cost', 'lead_time_days', 'minimum_order')
            ->withTimestamps();
    }

    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeLowStock($query, int $threshold = 10)
    {
        return $query->whereHas('stock', function ($q) {
            $q->where('quantity', '<=', 10);
        });
    }

    // Métodos
    public function getTotalStock(): int
    {
        return $this->stock()->sum('quantity');
    }

    public function isLowStock(): bool
    {
        return $this->getTotalStock() <= $this->reorder_level;
    }
}
```

#### 3. Factories

`database/factories/Modules/Inventory/ProductFactory.php`

```php
<?php

namespace Database\Factories\Modules\Inventory;

use App\Modules\Inventory\Domain\Models\{Product, Category, Brand};
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'slug' => $this->faker->slug(),
            'sku' => $this->faker->unique()->bothify('SKU-####-##'),
            'barcode' => $this->faker->unique()->ean13(),
            'description' => $this->faker->paragraph(),
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'brand_id' => Brand::inRandomOrder()->first()?->id,
            'cost' => $this->faker->numberBetween(10, 100),
            'retail_price' => $this->faker->numberBetween(20, 200),
            'weight' => $this->faker->numberBetween(1, 50),
            'reorder_level' => 10,
            'reorder_quantity' => 50,
            'unit' => 'pcs',
            'is_active' => true,
            'is_trackable' => true,
        ];
    }
}
```

### Criterios de Aceptación

- [ ] Todas las migraciones creadas y funcionando
- [ ] Todos los modelos Eloquent creados
- [ ] Relaciones entre modelos configuradas correctamente
- [ ] Índices de base de datos optimizados
- [ ] Factories y seeders creados
- [ ] Datos de prueba pueden ser generados
- [ ] Foreign keys configuradas correctamente

### Testing

```php
// Tests/Unit/Modules/Inventory/Models/ProductTest.php
public function test_product_has_category()
{
    $product = Product::factory()->create();

    $this->assertNotNull($product->category);
    $this->assertInstanceOf(Category::class, $product->category);
}

public function test_product_total_stock()
{
    $product = Product::factory()->create();
    Stock::factory(3)->create(['product_id' => $product->id]);

    $total = $product->getTotalStock();
    $this->assertGreaterThan(0, $total);
}
```

### Dependencias

- ✅ Fase 5.1: Estructura Clean Architecture

### Notas Importantes

1. **Multi-Tenancy**: Todas las tablas incluyen `tenant_id`
2. **Soft Deletes**: Productos, categorías y suppliers usan soft delete
3. **Índices**: Índices optimizados para búsquedas frecuentes
4. **Foreign Keys**: Configuradas apropiadamente para integridad referencial
5. **Escalabilidad**: Estructura preparada para crecer sin cambios mayores

---

**Estimación:** 2-3 días  
**Prioridad:** Alta  
**Última actualización:** 2026-02-11
