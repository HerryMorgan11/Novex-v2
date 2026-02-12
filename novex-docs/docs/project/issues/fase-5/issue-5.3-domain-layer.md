---
title: '[Fase 5.3] Domain Layer del Módulo Inventario'
labels: fase-5, inventory, backend, domain-driven-design, priority-high
assignees:
milestone: Fase 5 - Módulo Inventario
---

## Tarea: Implementar Domain Layer Completo del Inventario

### Descripción

Implementar la capa de dominio del módulo inventario incluyendo Value Objects, Domain Events, Domain Services y Repositories. Esta es la capa más importante donde reside la lógica de negocio.

### Objetivos

#### Value Objects

- [ ] Crear Value Object SKU (ya base, implementar completo)
- [ ] Crear Value Object Barcode
- [ ] Crear Value Object StockQuantity
- [ ] Crear Value Object Price
- [ ] Crear Value Object Dimensions

#### Domain Events

- [ ] ProductCreated event
- [ ] ProductUpdated event
- [ ] ProductDeleted event
- [ ] StockAdjusted event
- [ ] LowStockDetected event
- [ ] StockTransferred event

#### Domain Services

- [ ] StockCalculationService
- [ ] PriceCalculationService
- [ ] InventoryService

#### Repository Implementations

- [ ] EloquentProductRepository
- [ ] EloquentCategoryRepository
- [ ] EloquentStockRepository
- [ ] EloquentBrandRepository

### Implementación

#### 1. Value Objects Completos

##### Value Object: Barcode

`app/Modules/Inventory/Domain/ValueObjects/Barcode.php`

```php
<?php

namespace App\Modules\Inventory\Domain\ValueObjects;

use App\Core\Domain\Shared\Exceptions\ValidationException;

final class Barcode
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
            throw new ValidationException('Barcode cannot be empty');
        }

        if (!preg_match('/^[0-9]{8,14}$/', $value)) {
            throw new ValidationException('Invalid barcode format. Must be 8-14 digits');
        }

        // Validación EAN-13
        if (strlen($value) === 13) {
            if (!$this->validateEAN13($value)) {
                throw new ValidationException('Invalid EAN-13 checksum');
            }
        }
    }

    private function validateEAN13(string $ean): bool
    {
        $check = 0;
        for ($i = 0; $i < 12; $i++) {
            $check += (int)$ean[$i] * ($i % 2 == 0 ? 1 : 3);
        }
        $check = (10 - ($check % 10)) % 10;
        return $check == $ean[12];
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(Barcode $other): bool
    {
        return $this->value === $other->value();
    }
}
```

##### Value Object: StockQuantity

`app/Modules/Inventory/Domain/ValueObjects/StockQuantity.php`

```php
<?php

namespace App\Modules\Inventory\Domain\ValueObjects;

use App\Core\Domain\Shared\Exceptions\ValidationException;

final class StockQuantity
{
    private int $quantity;

    public function __construct(int $quantity)
    {
        $this->validate($quantity);
        $this->quantity = $quantity;
    }

    private function validate(int $quantity): void
    {
        if ($quantity < 0) {
            throw new ValidationException('Stock quantity cannot be negative');
        }
    }

    public function value(): int
    {
        return $this->quantity;
    }

    public function add(StockQuantity $other): StockQuantity
    {
        return new self($this->quantity + $other->value());
    }

    public function subtract(StockQuantity $other): StockQuantity
    {
        $result = $this->quantity - $other->value();
        return new self(max(0, $result));
    }

    public function isLessThan(int $threshold): bool
    {
        return $this->quantity < $threshold;
    }

    public function isZero(): bool
    {
        return $this->quantity === 0;
    }

    public function __toString(): string
    {
        return (string)$this->quantity;
    }

    public function equals(StockQuantity $other): bool
    {
        return $this->quantity === $other->value();
    }
}
```

##### Value Object: Price

`app/Modules/Inventory/Domain/ValueObjects/Price.php`

```php
<?php

namespace App\Modules\Inventory\Domain\ValueObjects;

use App\Core\Domain\Shared\Exceptions\ValidationException;

final class Price
{
    private float $amount;
    private string $currency;

    public function __construct(float $amount, string $currency = 'USD')
    {
        $this->validate($amount);
        $this->amount = round($amount, 2);
        $this->currency = $currency;
    }

    private function validate(float $amount): void
    {
        if ($amount < 0) {
            throw new ValidationException('Price cannot be negative');
        }

        if ($amount > 999999.99) {
            throw new ValidationException('Price exceeds maximum value');
        }
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function add(Price $other): Price
    {
        if ($this->currency !== $other->currency()) {
            throw new ValidationException('Cannot add prices with different currencies');
        }

        return new self($this->amount + $other->amount(), $this->currency);
    }

    public function subtract(Price $other): Price
    {
        if ($this->currency !== $other->currency()) {
            throw new ValidationException('Cannot subtract prices with different currencies');
        }

        $result = $this->amount - $other->amount();
        return new self(max(0, $result), $this->currency);
    }

    public function multiply(float $factor): Price
    {
        return new self($this->amount * $factor, $this->currency);
    }

    public function isZero(): bool
    {
        return $this->amount === 0.0;
    }

    public function isGreaterThan(Price $other): bool
    {
        return $this->amount > $other->amount();
    }

    public function isLessThan(Price $other): bool
    {
        return $this->amount < $other->amount();
    }

    public function __toString(): string
    {
        return number_format($this->amount, 2, '.', ',') . ' ' . $this->currency;
    }

    public function equals(Price $other): bool
    {
        return $this->amount === $other->amount() && $this->currency === $other->currency();
    }
}
```

##### Value Object: Dimensions

`app/Modules/Inventory/Domain/ValueObjects/Dimensions.php`

```php
<?php

namespace App\Modules\Inventory\Domain\ValueObjects;

use App\Core\Domain\Shared\Exceptions\ValidationException;

final class Dimensions
{
    private float $length;
    private float $width;
    private float $height;
    private string $unit;

    public function __construct(float $length, float $width, float $height, string $unit = 'cm')
    {
        $this->validate($length, $width, $height, $unit);
        $this->length = $length;
        $this->width = $width;
        $this->height = $height;
        $this->unit = $unit;
    }

    private function validate(float $l, float $w, float $h, string $unit): void
    {
        if ($l <= 0 || $w <= 0 || $h <= 0) {
            throw new ValidationException('Dimensions must be positive numbers');
        }

        if (!in_array($unit, ['cm', 'm', 'mm', 'in'])) {
            throw new ValidationException('Invalid unit. Accepted: cm, m, mm, in');
        }
    }

    public function length(): float
    {
        return $this->length;
    }

    public function width(): float
    {
        return $this->width;
    }

    public function height(): float
    {
        return $this->height;
    }

    public function unit(): string
    {
        return $this->unit;
    }

    public function volume(): float
    {
        return $this->length * $this->width * $this->height;
    }

    public function __toString(): string
    {
        return "{$this->length}x{$this->width}x{$this->height}{$this->unit}";
    }

    public function equals(Dimensions $other): bool
    {
        return $this->length === $other->length()
            && $this->width === $other->width()
            && $this->height === $other->height()
            && $this->unit === $other->unit();
    }
}
```

#### 2. Domain Events

##### Event: StockAdjusted

`app/Modules/Inventory/Domain/Events/StockAdjusted.php`

```php
<?php

namespace App\Modules\Inventory\Domain\Events;

use App\Modules\Inventory\Domain\Models\Stock;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockAdjusted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Stock $stock,
        public int $previousQuantity,
        public int $newQuantity,
        public string $reason,
    ) {}

    public function getQuantityDifference(): int
    {
        return $this->newQuantity - $this->previousQuantity;
    }
}
```

##### Event: LowStockDetected

`app/Modules/Inventory/Domain/Events/LowStockDetected.php`

```php
<?php

namespace App\Modules\Inventory\Domain\Events;

use App\Modules\Inventory\Domain\Models\Product;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LowStockDetected
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Product $product,
        public int $currentStock,
        public int $minimumStock,
    ) {}
}
```

#### 3. Domain Services

##### StockCalculationService

`app/Modules/Inventory/Domain/Services/StockCalculationService.php`

```php
<?php

namespace App\Modules\Inventory\Domain\Services;

use App\Modules\Inventory\Domain\Models\Product;
use App\Modules\Inventory\Domain\Models\Stock;

class StockCalculationService
{
    /**
     * Obtener el stock total de un producto en todos los almacenes
     */
    public function getTotalStock(Product $product): int
    {
        return $product->stock()->sum('quantity');
    }

    /**
     * Obtener el stock disponible (cantidad - reservado)
     */
    public function getAvailableStock(Product $product): int
    {
        return $product->stock()
            ->sum(\DB::raw('quantity - reserved_quantity'));
    }

    /**
     * Obtener stock en un almacén específico
     */
    public function getStockByWarehouse(Product $product, int $warehouseId): int
    {
        return $product->stock()
            ->where('warehouse_id', $warehouseId)
            ->sum('quantity');
    }

    /**
     * Calcular stock promedio entre almacenes
     */
    public function getAverageStock(Product $product): float
    {
        $count = $product->stock()->count();
        if ($count === 0) {
            return 0;
        }

        return $this->getTotalStock($product) / $count;
    }

    /**
     * Determinar si el stock es bajo
     */
    public function isLowStock(Product $product): bool
    {
        return $this->getTotalStock($product) <= $product->reorder_level;
    }

    /**
     * Calcular cuánto stock se necesita ordenar
     */
    public function calculateReorderQuantity(Product $product): int
    {
        $current = $this->getTotalStock($product);

        if ($current >= $product->reorder_level) {
            return 0;
        }

        return $product->reorder_quantity - $current;
    }
}
```

##### PriceCalculationService

`app/Modules/Inventory/Domain/Services/PriceCalculationService.php`

```php
<?php

namespace App\Modules\Inventory\Domain\Services;

use App\Modules\Inventory\Domain\Models\Product;
use App\Modules\Inventory\Domain\ValueObjects\Price;

class PriceCalculationService
{
    /**
     * Calcular precio con descuento
     */
    public function calculateDiscountedPrice(Price $price, float $discountPercent): Price
    {
        $discount = $price->multiply($discountPercent / 100);
        return $price->subtract($discount);
    }

    /**
     * Calcular ganancia bruta
     */
    public function calculateGrossProfit(Product $product): Price
    {
        $cost = new Price($product->cost);
        $price = new Price($product->retail_price);

        return $price->subtract($cost);
    }

    /**
     * Calcular margen de ganancia
     */
    public function calculateProfitMargin(Product $product): float
    {
        if ($product->cost === 0) {
            return 0;
        }

        $profit = ($product->retail_price - $product->cost) / $product->cost;
        return round($profit * 100, 2);
    }

    /**
     * Obtener el mejor precio de compra de proveedores
     */
    public function getBestSupplierPrice(Product $product): ?float
    {
        return $product->suppliers()
            ->min('product_supplier.cost');
    }
}
```

#### 4. Repository Implementations

##### EloquentProductRepository

`app/Modules/Inventory/Infrastructure/Persistence/EloquentProductRepository.php`

```php
<?php

namespace App\Modules\Inventory\Infrastructure\Persistence;

use App\Modules\Inventory\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Inventory\Domain\Models\Product;
use App\Modules\Inventory\Application\DTOs\ProductDTO;
use Illuminate\Pagination\Paginator;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function create(ProductDTO $dto): Product
    {
        return Product::create([
            'name' => $dto->name,
            'description' => $dto->description,
            'sku' => $dto->sku,
            'barcode' => $dto->barcode,
            'cost' => $dto->cost,
            'retail_price' => $dto->sellPrice,
            'category_id' => $dto->categoryId,
            'brand_id' => $dto->brandId,
        ]);
    }

    public function update(int $id, ProductDTO $dto): Product
    {
        $product = $this->findById($id);

        $product->update([
            'name' => $dto->name,
            'description' => $dto->description,
            'sku' => $dto->sku,
            'barcode' => $dto->barcode,
            'cost' => $dto->cost,
            'retail_price' => $dto->sellPrice,
            'category_id' => $dto->categoryId,
            'brand_id' => $dto->brandId,
        ]);

        return $product;
    }

    public function findById(int $id): ?Product
    {
        return Product::find($id);
    }

    public function findBySKU(string $sku): ?Product
    {
        return Product::where('sku', $sku)->first();
    }

    public function getAll(int $perPage = 15): Paginator
    {
        return Product::active()->paginate($perPage);
    }

    public function getByCategory(int $categoryId, int $perPage = 15): Paginator
    {
        return Product::active()
            ->byCategory($categoryId)
            ->paginate($perPage);
    }

    public function getLowStockProducts(int $threshold = 10): array
    {
        return Product::active()
            ->whereHas('stock', function ($query) use ($threshold) {
                $query->where('quantity', '<=', $threshold);
            })
            ->get()
            ->toArray();
    }

    public function delete(int $id): bool
    {
        $product = $this->findById($id);

        if (!$product) {
            return false;
        }

        return $product->delete();
    }

    public function count(): int
    {
        return Product::count();
    }
}
```

### Criterios de Aceptación

- [ ] Todos los Value Objects creados y probados
- [ ] Todos los Domain Events definidos
- [ ] Domain Services implementados correctamente
- [ ] Repositories Eloquent implementados
- [ ] Lógica de negocio encapsulada en el dominio
- [ ] Tests unitarios de Domain Objects pasando
- [ ] Validaciones de negocio en el lugar correcto

### Testing

```php
// Tests/Unit/Modules/Inventory/Domain/ValueObjects/PriceTest.php
public function test_price_addition()
{
    $price1 = new Price(100.00);
    $price2 = new Price(50.00);

    $result = $price1->add($price2);

    $this->assertEquals(150.00, $result->amount());
}

public function test_price_cannot_be_negative()
{
    $this->expectException(ValidationException::class);

    new Price(-10.00);
}

// Tests/Unit/Modules/Inventory/Domain/Services/StockCalculationServiceTest.php
public function test_calculate_reorder_quantity()
{
    $product = Product::factory()
        ->create(['reorder_level' => 10, 'reorder_quantity' => 50]);

    Stock::factory()->create([
        'product_id' => $product->id,
        'quantity' => 5,
    ]);

    $service = new StockCalculationService();
    $reorder = $service->calculateReorderQuantity($product);

    $this->assertEquals(45, $reorder);
}
```

### Dependencias

- ✅ Fase 5.1: Estructura Clean Architecture
- ✅ Fase 5.2: Migraciones y Modelos

### Notas Importantes

1. **Business Logic First**: El dominio no debe depender de frameworks
2. **Value Objects Immutable**: Son inmutables y autovalidables
3. **Domain Events**: Usados para desacoplamiento y auditoria
4. **Services Stateless**: Los servicios de dominio no mantienen estado
5. **Repository Pattern**: Abstracción de persistencia

---

**Estimación:** 3-4 días  
**Prioridad:** Alta  
**Última actualización:** 2026-02-11
