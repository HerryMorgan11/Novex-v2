# Documentación Arquitectura

# 📚 Guía Completa

# Clean Architecture & Multi-Tenancy para ERP en Laravel

---

## 📖 Índice

1. ¿Qué es Clean Architecture?
2. Principios Fundamentales
3. Capas de Clean Architecture
4. Estructura Recomendada del Proyecto
5. Componentes Principales
    - Entidades (Domain Models)
    - Value Objects
    - Repositories
    - DTOs
    - Use Cases
    - Domain Events
    - Domain Services
6. Multi-Tenancy en Laravel
7. Estrategia Multi-Tenant Elegida
8. Paquete: Tenancy for Laravel (stancl/tenancy)
9. Flujo de Trabajo Completo
10. Seguridad y Aislamiento
11. Testing con Multi-Tenancy
12. Referencias y Recursos
13. Conclusión

---

## 🏛️ ¿Qué es Clean Architecture?

**Clean Architecture** es un enfoque arquitectónico propuesto por **Robert C. Martin (Uncle Bob)** cuyo objetivo es construir sistemas:

- Independientes de frameworks
- Altamente testeables
- Independientes de la UI
- Independientes de la base de datos
- Independientes de servicios externos

La lógica de negocio debe sobrevivir incluso si el framework cambia.

---

## 🎯 Principio Clave: Regla de Dependencia

> **Las dependencias del código deben apuntar siempre hacia adentro.**

Esto implica:

- El dominio no depende de nada
- La aplicación depende solo del dominio
- La infraestructura depende de dominio y aplicación
- La presentación depende de todo lo anterior

---

## 🧱 Capas de Clean Architecture

```
┌─────────────────────────────────────────────┐
│ Presentation (Controllers, Views, Routes)   │
├─────────────────────────────────────────────┤
│ Application (Use Cases, DTOs)                │
├─────────────────────────────────────────────┤
│ Domain (Entities, Business Rules)             │
├─────────────────────────────────────────────┤
│ Infrastructure (DB, APIs, Cache, Mail)       │
└─────────────────────────────────────────────┘

Las dependencias siempre apuntan hacia el dominio

```

---

## 🏗️ Estructura Recomendada de Inventario

Arquitectura **modular**, sin CQRS, clara y mantenible:

```
app/
├── Modules/
│   ├── Inventory/
│   │   ├── Domain/
│   │   │   ├── Models/
│   │   │   ├── ValueObjects/
│   │   │   ├── Repositories/
│   │   │   ├── Events/
│   │   │   └── Services/
│   │   ├── Application/
│   │   │   ├── UseCases/
│   │   │   └── DTOs/
│   │   ├── Infrastructure/
│   │   │   └── Persistence/
│   │   └── Http/
│   │       ├── Controllers/
│   │       └── Requests/
│   ├── Sales/
│   ├── CRM/
│   └── Invoicing/

```

---

## 🏗️ Estructura Recomendada Core

```jsx

   app/Core/
   ├── Domain/              # Core Domain - Funcionalidad compartida
   │   ├── Shared/          # Elementos compartidos por todos los módulos
   │   │   ├── ValueObjects/
   │   │   │   ├── Email.php
   │   │   │   ├── Phone.php
   │   │   │   └── Money.php
   │   │   ├── Exceptions/
   │   │   │   ├── DomainException.php
   │   │   │   └── ValidationException.php
   │   │   └── Contracts/
   │   │       └── AggregateRoot.php
   │   │
   │   ├── Inventory/       # Módulo específico
   │   ├── Sales/
   │   └── CRM/
   │
   ├── Application/         # Core Application - Casos de uso compartidos
   │   ├── Shared/
   │   │   ├── DTOs/
   │   │   ├── Services/
   │   │   └── Contracts/
   │   │
   │   └── UseCases/
   │       ├── Inventory/
   │       ├── Sales/
   │       └── CRM/
   │
   └── Infrastructure/      # Core Infrastructure - Implementaciones técnicas
       ├── Shared/
       │   ├── Database/
       │   ├── Cache/
       │   ├── Queue/
       │   └── Logging/
       │
       └── Persistence/
           ├── Eloquent/
           └── Redis/
```

## 🧩 Componentes Principales

---

### 1️⃣ Entidades (Domain Models)

Representan conceptos centrales del negocio y **contienen lógica de negocio**.

**Características**

- Tienen identidad
- Mantienen invariantes
- Cambian de estado
- No dependen de infraestructura

```php
class Product
{
    public function hasStock(int $quantity): bool
    {
        return $this->stock_quantity >= $quantity;
    }

    public function decreaseStock(int $quantity): void
    {
        if (!$this->hasStock($quantity)) {
            throw new InsufficientStockException();
        }

        $this->stock_quantity -= $quantity;
    }
}

```

---

### 2️⃣ Value Objects

Objetos **inmutables**, comparables por valor.

**Usar cuando**

- No hay identidad
- Hay validaciones
- Representan conceptos del dominio

```php
final class SKU
{
    private string $value;

    public function __construct(string $value)
    {
        if (!preg_match('/^[A-Z0-9\-]+$/', strtoupper($value))) {
            throw new InvalidArgumentException('SKU inválido');
        }

        $this->value = strtoupper($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}

```

---

### 3️⃣ Repositories

Abstraen el acceso a datos.

- Interface en Domain
- Implementación en Infrastructure
- Facilitan testing

```php
interface ProductRepositoryInterface
{
    public function find(int $id): ?Product;
    public function save(Product $product): Product;
}

```

```php
class EloquentProductRepository implements ProductRepositoryInterface
{
    public function find(int $id): ?Product
    {
        return Product::find($id);
    }

    public function save(Product $product): Product
    {
        $product->save();
        return $product;
    }
}

```

---

### 4️⃣ DTOs (Data Transfer Objects)

Objetos simples para transportar datos entre capas.

```php
class ProductDTO
{
    public int $id;
    public string $name;
    public string $sku;
    public float $price;

    public static function fromModel(Product $product): self
    {
        $dto = new self();
        $dto->id = $product->id;
        $dto->name = $product->name;
        $dto->sku = $product->sku;
        $dto->price = $product->price;
        return $dto;
    }
}

```

---

### 5️⃣ Use Cases (Casos de Uso)

Orquestan el flujo de la aplicación.

- Una clase por acción
- Coordinan dominio e infraestructura
- No contienen lógica de negocio profunda

```php
class CreateProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    public function execute(array $data): ProductDTO
    {
        $product = new Product($data);
        $this->repository->save($product);

        event(new ProductCreated($product));

        return ProductDTO::fromModel($product);
    }
}

```

---

### 6️⃣ Domain Events

Representan hechos importantes del dominio.

```php
class ProductCreated
{
    public function __construct(
        public readonly Product $product
    ) {}
}

```

Permiten:

- Desacoplar módulos
- Auditoría
- Side effects (emails, cache, logs)

---

### 7️⃣ Domain Services

Lógica de negocio que no pertenece a una sola entidad.

```php
class StockCalculationService
{
    public function shouldReorder(Product $product, int $limit): bool
    {
        return $product->stock_quantity <= $limit;
    }
}

```

---

## 🏢 Multi-Tenancy en Laravel

**Multi-Tenancy** permite que una sola aplicación sirva a múltiples clientes (tenants) con aislamiento total de datos.

---

## 🧠 Estrategias de Multi-Tenancy

### ✅ Elegida: **Database por Tenant**

```
tenant1_db
tenant2_db
tenant3_db

```

**Ventajas**

- Máximo aislamiento
- Backups independientes
- Escalado por cliente
- Cumplimiento normativo

---

## 📦 Paquete: stancl/tenancy

Paquete recomendado para Laravel.

### Instalación

```bash
composer require stancl/tenancy
php artisan tenancy:install
php artisan migrate

```

---

### Funcionamiento

1. Request llega por dominio
2. Middleware detecta tenant
3. Cambia conexión de base de datos
4. Eloquent opera solo en la BD del tenant
5. La app continúa normalmente

---

### Modelo Tenant

```php
class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;
}

```

---

## 🔄 Flujo Completo: Crear Producto

```
HTTP Request
→ Middleware Tenancy
→ Controller
→ Use Case
→ Domain
→ Repository
→ Tenant Database
→ Response

```

---

## 🧪 Testing con Multi-Tenancy

```php
$tenant = Tenant::create(['name' => 'Test']);
tenancy()->initialize($tenant);

Product::create([...]);

$this->assertDatabaseHas('products', [...]);

```

---

## 🔐 Seguridad y Aislamiento

- Cada tenant tiene su propia BD
- Cache y storage aislados
- Jobs tenant-aware
- Sin riesgo de filtrado cruzado

---

## 📚 Referencias

- Clean Architecture – Robert C. Martin
- Domain-Driven Design – Eric Evans
- Martin Fowler – Enterprise Patterns
- Laravel Documentation
- Tenancy for Laravel (stancl)

---

## 🎯 Conclusión

Esta arquitectura:

- Es limpia y mantenible
- Escala correctamente
- Evita sobreingeniería (sin CQRS)
- Se adapta bien a ERPs reales
- Mantiene bajo acoplamiento

> **La arquitectura es una guía, no una prisión.**
>
> Empieza simple, diseña para evolucionar.

---

Si quieres, en el siguiente paso puedo:

- Extraer esto a **plantilla Notion**
- Reducirlo a **versión ejecutiva**
- Crear una **checklist de implementación**
- O adaptarlo a **tu estructura exacta actual**

Dime cómo seguimos.

[https://tenancyforlaravel.com/](https://tenancyforlaravel.com/)
