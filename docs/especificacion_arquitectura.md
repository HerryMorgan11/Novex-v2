# Especificación de Arquitectura ERP Laravel

## Modular + Service + Repository (con Multi-Tenancy)

**Versión:** 1.0  
**Última actualización:** Febrero 2026

Esta guía especifica **cómo está organizado el ERP en Laravel** usando una arquitectura **Modular + Service + Repository**, y **cómo se integra el multi-tenant**. Está escrita de forma clara y directa con reglas específicas de **qué va dónde**, **qué NO se permite**, y **cómo fluyen los datos**.

---

## Índice

1. [¿Qué problema resuelve esta arquitectura?](#1-qué-problema-resuelve-esta-arquitectura)
2. [Principios clave](#2-principios-clave-los-3-mandamientos)
3. [Estructura de carpetas](#3-estructura-de-carpetas-recomendada)
   - 3.1 [Funcionalidad Core: app/Core](#31-funcionalidad-core-appcore)
4. [Responsabilidades de cada capa](#4-responsabilidades-de-cada-capa)
5. [Flujo completo: Request → Response](#5-flujo-completo-request--response)
6. [Reglas de dependencias (no saltos de capa)](#6-reglas-de-dependencias-no-saltos-de-capa)
7. [Qué NO puede hacer cada capa](#7-qué-no-puede-hacer-cada-capa-prohibiciones-oficiales)
8. [Multi-Tenancy: integración completa en toda la arquitectura](#8-multi-tenancy-integración-completa-en-toda-la-arquitectura)
   - 8.1 [Componentes del sistema de Multi-Tenancy](#81-componentes-del-sistema-de-multi-tenancy)
   - 8.2 [Aislamiento por capa](#82-aislamiento-por-capa)
   - 8.3 [Reglas tenant-aware obligatorias](#83-reglas-tenant-aware-obligatorias)
9. [Comunicación entre módulos](#9-comunicación-entre-módulos)
   - 9.1 [Reglas de comunicación](#91-reglas-de-comunicación)
   - 9.2 [Vía contratos y eventos](#92-vía-contratos-para-llamadas-síncronas)
   - 9.3 [Namespaces y estructura de módulos](#93-namespaces-y-estructura-de-módulos)
   - 9.4 [Convenciones de módulos de negocio](#94-convenciones-de-módulos-de-negocio)
10. [Testing recomendado](#10-testing-recomendado)
11. [Resumen ejecutivo](#11-resumen-ejecutivo-para-tontos)

---

## 1) ¿Qué problema resuelve esta arquitectura?

Un ERP crece rápido y se vuelve un caos si:

- Se mezclan reglas de negocio con controllers
- Cada módulo toca tablas de otros módulos directamente
- El multi-tenant se aplica "a veces" o de forma inconsistente
- No hay separación clara de responsabilidades

**Esta arquitectura busca:**

✅ Separar responsabilidades (cada parte hace lo suyo)  
✅ Mantener módulos aislados (cada área del ERP vive en su casa)  
✅ Usar servicios como "jefes de obra" (orquestan casos de uso)  
✅ Encapsular acceso a datos con repositorios (DB aislada del negocio)  
✅ Garantizar que **todo** respete el tenant de forma automática  

---

## 2) Principios clave (los 3 mandamientos)

### 2.1 Separación de responsabilidades

**Idea simple:** cada capa tiene un trabajo. Si haces el trabajo de otra capa, rompes el orden.

- **Controllers (HTTP)**: reciben la petición y devuelven respuesta. *Nada de lógica de negocio aquí.*
- **Services (Aplicación)**: ejecutan el caso de uso (la operación real del ERP). *Aquí vive la lógica de negocio.*
- **Repositories (Infraestructura)**: hablan con la base de datos y devuelven datos. *Nada de reglas de negocio aquí.*
- **Models (Persistencia)**: representan tablas, relaciones y casts. *Sin orquestación de procesos.*
- **Domain (Reglas puras)**: conceptos del negocio (Money, TaxId, reglas). *Sin DB y sin HTTP.*

**Regla "para tontos":**

> **Controller** = mensajero  
> **Service** = cerebro  
> **Repository** = archivador  
> **Model** = estructura de datos  
> **Domain** = reglas del juego  

---

### 2.2 Módulos aislados

**Idea simple:** Ventas no debe meterse en el código interno de Contabilidad.

Cada módulo:

- Tiene sus rutas, controllers, services, repos, modelos
- Se comunica con otros módulos:
    1. Por **servicios/contratos** (interfaces), o
    2. Por **eventos** (recomendado para menos acoplamiento)

**Prohibido:**

❌ Usar modelos/repositorios de otro módulo directamente  
❌ Hacer queries a tablas de otro módulo "porque es fácil"  
❌ Importar clases internas de otro módulo (excepto contratos públicos)  

---

### 2.3 Servicios como orquestadores

**Idea simple:** el Service es quien coordina todo el caso de uso.

Un Service típico:

1. Lee el tenant actual (TenantContext)
2. Valida reglas del negocio
3. Coordina repositorios
4. Abre transacciones si hace falta
5. Emite eventos para otros módulos
6. Devuelve un resultado (DTO o modelo)

**Prohibido en Services:**

❌ Recibir `Request` o devolver `Response`  
❌ Escribir SQL/queries (eso es Repository)  
❌ Inventarse el tenant (tenant se obtiene del contexto)  
❌ Lógica de presentación (formateo JSON, HTTP status)  

---

## 3) Estructura de carpetas recomendada

```
app/
  Modules/
    Sales/                          # Módulo de Ventas
      Http/
        Controllers/                # Controladores HTTP
          InvoiceController.php
        Requests/                   # Form Requests (validación)
          CreateInvoiceRequest.php
        Resources/                  # API Resources (transformación JSON)
          InvoiceResource.php
      Domain/
        ValueObjects/               # Objetos de valor (Money, TaxId)
        Enums/                      # Estados, tipos enumerados
        Rules/                      # Reglas de negocio puras
        Policies/                   # Políticas de autorización
      Application/
        DTOs/                       # Data Transfer Objects
          CreateInvoiceDTO.php
        Services/                   # Servicios de aplicación
          InvoiceService.php
        UseCases/                   # Casos de uso (opcional)
      Infrastructure/
        Persistence/
          Models/                   # Modelos Eloquent
            Invoice.php
          Repositories/             # Implementaciones de repositorios
            InvoiceRepository.php
        Providers/                  # Service Providers del módulo
          SalesServiceProvider.php
        Migrations/                 # Migraciones de BD
        Seeders/                    # Seeders
        Factories/                  # Factories
      Routes/
        api.php                     # Rutas API del módulo
        web.php                     # Rutas web del módulo
      Tests/
        Feature/                    # Tests de integración
        Unit/                       # Tests unitarios
        
    Accounting/                     # Módulo de Contabilidad
      # ... misma estructura
      
    Inventory/                      # Módulo de Inventario
      # ... misma estructura
      
  Shared/                           # Código compartido
    Tenancy/
      Middleware/
        IdentifyTenant.php          # Middleware de identificación
      Resolvers/
        TenantResolver.php          # Resolvedor de tenant
      Context/
        TenantContext.php           # Contexto global del tenant
      Scopes/
        TenantScope.php             # Global scope para modelos
    Auth/                           # Autenticación compartida
    Exceptions/                     # Excepciones globales
    Support/                        # Helpers y utilidades
```

**Qué significa esta estructura:**

- `Modules/` contiene los dominios del ERP (Ventas, Inventario, Contabilidad, etc.)
- Cada módulo se organiza por capas (Http / Domain / Application / Infrastructure)
- `Shared/` guarda lo común para todos (especialmente Tenancy)
- Cada módulo es **autocontenido**: tiene todo lo necesario para funcionar

---

## 3.1) Funcionalidad Core: app/Core

La carpeta `app/Core` alberga **funcionalidad fundamental y reutilizable** que no pertenece a ningún módulo específico, pero es necesaria para el funcionamiento general del sistema. Se divide en dos subcarpetas principales: **Core/Shared** y **Core/Tenant**.

### 3.1.1 Core/Shared - Utilidades transversales

Contiene elementos que cualquier módulo puede usar sin restricciones de tenant.

#### **Estructura recomendada:**

```
app/
  Core/
    Shared/
      Services/               # Servicios compartidos sin lógica de negocio
        FileStorageService.php
        NotificationService.php
        LoggingService.php
      Traits/                 # Comportamientos reutilizables
        HasUuid.php
        Auditable.php
        SoftDeletesWithTenant.php
      Helpers/                # Funciones auxiliares globales
        helpers.php
        formatters.php
      Contracts/              # Interfaces/contratos compartidos
        Cacheable.php
        Exportable.php
        Notifiable.php
      Exceptions/             # Excepciones base del sistema
        DomainException.php
        ValidationException.php
        UnauthorizedException.php
        TenantException.php
```

#### **Responsabilidades de Core/Shared:**

**Services:**
- ✅ Operaciones técnicas reutilizables (almacenamiento, logs, notificaciones)
- ✅ Integraciones con servicios externos (email, SMS, almacenamiento cloud)
- ❌ NO debe contener lógica de negocio específica de módulos
- ❌ NO debe acceder directamente a repositorios de módulos

**Traits:**
- ✅ Comportamientos comunes para modelos (UUID, auditoría, soft deletes)
- ✅ Funcionalidad cross-cutting (timestamps, tracking de cambios)
- ❌ NO debe tener lógica de negocio compleja
- ❌ NO debe orquestar múltiples operaciones

**Helpers:**
- ✅ Funciones puras de utilidad (formateo, conversiones, cálculos matemáticos)
- ✅ Shortcuts para operaciones repetitivas
- ❌ NO debe tener estado
- ❌ NO debe acceder a base de datos directamente

**Contracts:**
- ✅ Interfaces que definen contratos entre módulos
- ✅ Abstracciones para inversión de dependencias
- ❌ NO debe contener implementaciones

**Exceptions:**
- ✅ Excepciones base para todo el sistema
- ✅ Jerarquía de errores comunes (validación, autenticación, autorización)
- ✅ Códigos de error estandarizados

### 3.1.2 Core/Tenant - Funcionalidad multi-tenant

Contiene toda la lógica relacionada con el sistema de multi-tenancy.

#### **Estructura recomendada:**

```
app/
  Core/
    Tenant/
      Middleware/
        IdentifyTenant.php      # Identifica tenant de la petición
        EnsureTenant.php        # Valida que existe tenant activo
      Resolvers/
        TenantResolver.php      # Resuelve tenant desde dominio/header/token
      Context/
        TenantContext.php       # Singleton con tenant actual
      Scopes/
        TenantScope.php         # Global scope automático para modelos
      Models/
        Tenant.php              # Modelo de tenant
      Contracts/
        TenantAware.php         # Interface para modelos tenant-scoped
```

#### **Responsabilidades de Core/Tenant:**

- ✅ Resolver tenant de cada petición
- ✅ Mantener contexto global del tenant activo
- ✅ Aplicar filtrado automático por tenant_id
- ✅ Validar permisos de acceso entre tenants
- ❌ NO debe contener lógica de negocio de módulos
- ❌ NO debe gestionar datos específicos de módulos

### 3.1.3 Excepciones base compartidas

Todas las excepciones del sistema deben heredar de una base común para manejo centralizado:

```php
// app/Core/Shared/Exceptions/DomainException.php
namespace App\Core\Shared\Exceptions;

use Exception;

abstract class DomainException extends Exception
{
    protected $statusCode = 500;
    protected $errorCode;
    
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    
    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }
}

// Excepciones específicas
class ValidationException extends DomainException
{
    protected $statusCode = 422;
    protected $errorCode = 'VALIDATION_ERROR';
}

class UnauthorizedException extends DomainException
{
    protected $statusCode = 401;
    protected $errorCode = 'UNAUTHORIZED';
}

class TenantException extends DomainException
{
    protected $statusCode = 403;
    protected $errorCode = 'TENANT_ERROR';
}
```

### 3.1.4 Cuándo usar Core vs Módulos

#### **Usar app/Core/Shared cuando:**

✅ Es funcionalidad **técnica** sin lógica de negocio  
✅ Es reutilizable por **3 o más módulos**  
✅ No depende de reglas específicas de un dominio  
✅ Es infraestructura pura (logging, storage, cache)  

**Ejemplos válidos:**
- Servicio de envío de emails
- Trait para añadir UUID a modelos
- Helper para formatear monedas
- Excepción base de validación

#### **Usar Módulos cuando:**

✅ Contiene **lógica de negocio** específica del dominio  
✅ Es parte de un **caso de uso** concreto  
✅ Orquesta operaciones de negocio  
✅ Gestiona entidades del dominio  

**Ejemplos válidos:**
- InvoiceService (lógica de facturación)
- ProductRepository (acceso a productos)
- OrderValidator (reglas de pedidos)

#### **🚨 Regla de oro: Evitar lógica de negocio en Core**

**Prohibido en Core/Shared:**

❌ Servicios que implementen casos de uso completos  
❌ Repositorios de entidades de negocio  
❌ Validadores con reglas de dominio  
❌ Orquestación de procesos de negocio  
❌ Acceso directo a modelos de módulos  

**Si algo en Core empieza a crecer y tener lógica compleja → muévelo a un módulo.**

### 3.1.5 Checklist: Validación de Core

✅ **Definir estructura de Core/Shared**
   - Services (técnicos, sin negocio)
   - Traits (comportamientos reutilizables)
   - Helpers (funciones puras)
   - Contracts (interfaces compartidas)
   - Exceptions (jerarquía de errores)

✅ **Definir estructura de Core/Tenant**
   - Middleware de identificación
   - Resolvers de tenant
   - Contexto global
   - Scopes automáticos

✅ **Definir excepciones base compartidas**
   - DomainException base
   - ValidationException
   - UnauthorizedException
   - TenantException

✅ **Documentar cuándo usar Core vs Módulos**
   - Core = técnico, reutilizable, sin negocio
   - Módulos = lógica de negocio, casos de uso

✅ **Evitar lógica de negocio en Core**
   - Core no orquesta procesos
   - Core no conoce entidades de negocio
   - Si crece → migrar a módulo

---

## 4) Responsabilidades de cada capa

### 4.1 📥 HTTP Layer (Controllers, Requests, Resources)

#### **Controllers**

**Responsabilidades:**

✅ Recibir peticiones HTTP  
✅ Validar input (delegando a FormRequests)  
✅ Llamar al Service correspondiente  
✅ Transformar respuesta (usando Resources)  
✅ Devolver códigos HTTP apropiados  
✅ Manejar errores de forma genérica (try-catch básico)  

**Ejemplo mental:**

```php
// ✅ CORRECTO
public function store(CreateInvoiceRequest $request, InvoiceService $service)
{
    $dto = CreateInvoiceDTO::fromRequest($request);
    $invoice = $service->create($dto);
    return InvoiceResource::make($invoice)->response()->setStatusCode(201);
}
```

#### **FormRequests**

**Responsabilidades:**

✅ Validar tipos de datos, formatos, existencia  
✅ Autorización a nivel de request (si aplica)  
✅ Preparar datos para el DTO  

#### **Resources**

**Responsabilidades:**

✅ Transformar modelos/DTOs a formato JSON estable  
✅ Ocultar campos internos  
✅ Formatear fechas, monedas, relaciones  

---

### 4.2 🧠 Application Layer (Services, DTOs, UseCases)

#### **Services**

**Responsabilidades:**

✅ **Implementar casos de uso del negocio** (crear factura, aprobar orden, etc.)  
✅ **Validar reglas de negocio** (estados, permisos, cálculos)  
✅ **Coordinar múltiples repositorios**  
✅ **Manejar transacciones** (DB::transaction)  
✅ **Emitir eventos de dominio** (InvoiceCreated, OrderApproved)  
✅ **Obtener tenant del contexto** (TenantContext::id())  
✅ **Orquestar llamadas a otros módulos** (via contratos o eventos)  

**Ejemplo mental:**

```php
// ✅ CORRECTO
public function create(CreateInvoiceDTO $dto): Invoice
{
    $tenantId = TenantContext::id();
    
    // Validación de negocio
    if (!$this->canCreateInvoice($dto->customerId)) {
        throw new BusinessException('Customer not allowed');
    }
    
    return DB::transaction(function () use ($dto, $tenantId) {
        $invoice = $this->invoiceRepo->create($dto->toArray());
        $this->inventoryService->reserveStock($invoice->items);
        event(new InvoiceCreated($invoice));
        return $invoice;
    });
}
```

#### **DTOs (Data Transfer Objects)**

**Responsabilidades:**

✅ Transportar datos entre capas  
✅ Validación básica de tipos (PHP 8+ typed properties)  
✅ Métodos de construcción (fromRequest, fromArray)  
✅ Métodos de transformación (toArray)  

**Características:**

- Son inmutables (readonly en PHP 8.1+)
- No tienen lógica de negocio compleja
- No acceden a DB

---

### 4.3 📦 Domain Layer (ValueObjects, Rules, Enums, Policies)

#### **ValueObjects**

**Responsabilidades:**

✅ Encapsular conceptos del negocio con validación  
✅ Garantizar invariantes (ej: Money nunca negativo)  
✅ Igualdad por valor, no por identidad  

**Ejemplos:** Money, TaxId, Email, Quantity, ProductCode

#### **Rules (Reglas de negocio puras)**

**Responsabilidades:**

✅ Validar reglas sin acceso a DB  
✅ Recibir datos como parámetros  
✅ Devolver bool o lanzar excepciones de dominio  

**Ejemplo:** "Una factura solo puede cancelarse si no tiene pagos asociados"

#### **Enums**

**Responsabilidades:**

✅ Definir estados, tipos, categorías del dominio  
✅ Métodos helper (labels, colores, transiciones válidas)  

#### **Policies**

**Responsabilidades:**

✅ Autorización de acciones del usuario  
✅ Reglas de permisos (can user X do Y?)  

---

### 4.4 🗄️ Infrastructure Layer (Models, Repositories)

#### **Models (Eloquent)**

**Responsabilidades:**

✅ Mapear tablas a objetos PHP  
✅ Definir relaciones (hasMany, belongsTo)  
✅ Casts de atributos  
✅ Scopes simples (activos, del mes, etc.)  
✅ Aplicar TenantScope (global scope)  

**Características:**

- Son "bolsas de datos" con comportamiento mínimo
- No orquestan procesos de negocio
- No llaman a servicios o repositorios

#### **Repositories**

**Responsabilidades:**

✅ **Abstracción de acceso a datos**  
✅ CRUD básico (find, create, update, delete)  
✅ Queries complejas (filtros, joins, paginación)  
✅ Aplicar tenant si no hay global scope  
✅ Devolver modelos o colecciones  

**Ejemplo mental:**

```php
// ✅ CORRECTO
public function findByCustomer(int $customerId): Collection
{
    return Invoice::where('customer_id', $customerId)
        ->with('items', 'customer')
        ->orderBy('created_at', 'desc')
        ->get();
    // TenantScope se aplica automáticamente
}
```

**Repositorio implementa una interfaz (contrato):**

```php
interface InvoiceRepositoryInterface
{
    public function find(int $id): ?Invoice;
    public function create(array $data): Invoice;
    public function findByCustomer(int $customerId): Collection;
}
```

---

## 5) Flujo completo: Request → Response

### 5.1 Diagrama de flujo

```
┌─────────────────────────────────────────────────────────────────┐
│                    1. REQUEST ENTRANTE                          │
│                    POST /api/invoices                           │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│               2. MIDDLEWARE (IdentifyTenant)                    │
│   - Resuelve tenant (subdominio/header/JWT)                     │
│   - Inicializa TenantContext::set($tenant)                      │
│   - (Opcional) Cambia conexión DB                               │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│           3. CONTROLLER (InvoiceController@store)               │
│   ✅ Recibe CreateInvoiceRequest (ya validado)                  │
│   ✅ Construye CreateInvoiceDTO desde request                   │
│   ✅ Llama a InvoiceService->create($dto)                       │
│   ❌ NO hace lógica de negocio                                  │
│   ❌ NO accede a DB directamente                                │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│          4. SERVICE (InvoiceService@create)                     │
│   ✅ Obtiene tenant: TenantContext::id()                        │
│   ✅ Valida reglas de negocio                                   │
│   ✅ Abre transacción DB::transaction()                         │
│   ✅ Llama a InvoiceRepository->create()                        │
│   ✅ Coordina con otros repos si necesario                      │
│   ✅ Emite evento: InvoiceCreated                               │
│   ✅ Devuelve Invoice (modelo)                                  │
│   ❌ NO recibe Request                                          │
│   ❌ NO escribe SQL                                             │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│       5. REPOSITORY (InvoiceRepository@create)                  │
│   ✅ Crea instancia de Invoice (Eloquent)                       │
│   ✅ invoice->fill($data)                                       │
│   ✅ invoice->save()                                            │
│   ✅ TenantScope se aplica automáticamente                      │
│   ✅ Devuelve Invoice con relaciones cargadas                   │
│   ❌ NO decide reglas de negocio                                │
│   ❌ NO emite eventos de dominio                                │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│              6. MODEL (Invoice - Eloquent)                      │
│   ✅ Representa tabla 'invoices'                                │
│   ✅ Aplica casts (dates, decimals)                             │
│   ✅ TenantScope añade: where('tenant_id', $tenantId)          │
│   ✅ Relaciones: belongsTo(Customer), hasMany(InvoiceItem)     │
│   ❌ NO orquesta procesos                                       │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│          7. RESPUESTA (Controller devuelve)                     │
│   ✅ InvoiceResource::make($invoice)                            │
│   ✅ ->response()->setStatusCode(201)                           │
│   ✅ JSON transformado y estructurado                           │
└─────────────────────────────────────────────────────────────────┘
```

### 5.2 Ejemplo paso a paso

#### Paso 1: Request

```http
POST /api/invoices
Authorization: Bearer <token>
X-Tenant: acme-corp
Content-Type: application/json

{
  "customer_id": 123,
  "items": [
    {"product_id": 45, "quantity": 2, "price": 100.00}
  ],
  "due_date": "2026-03-15"
}
```

#### Paso 2: Middleware

```php
// IdentifyTenant.php
public function handle($request, Closure $next)
{
    $tenantId = $this->resolver->resolve($request); // desde header/subdomain/JWT
    TenantContext::set($tenantId);
    
    // Si DB por tenant
    if (config('tenancy.database_per_tenant')) {
        DB::setConnection("tenant_{$tenantId}");
    }
    
    return $next($request);
}
```

#### Paso 3: Controller

```php
// InvoiceController.php
public function store(CreateInvoiceRequest $request, InvoiceService $service)
{
    $dto = CreateInvoiceDTO::fromRequest($request);
    
    try {
        $invoice = $service->create($dto);
        return InvoiceResource::make($invoice)
            ->response()
            ->setStatusCode(201);
    } catch (BusinessException $e) {
        return response()->json(['error' => $e->getMessage()], 422);
    }
}
```

#### Paso 4: Service

```php
// InvoiceService.php
public function create(CreateInvoiceDTO $dto): Invoice
{
    $tenantId = TenantContext::id();
    
    // Validación de negocio
    $customer = $this->customerRepo->find($dto->customerId);
    if (!$customer || !$customer->can_invoice) {
        throw new BusinessException('Customer cannot be invoiced');
    }
    
    return DB::transaction(function () use ($dto, $tenantId) {
        // Crear factura
        $invoice = $this->invoiceRepo->create([
            'tenant_id' => $tenantId, // explícito o por scope
            'customer_id' => $dto->customerId,
            'due_date' => $dto->dueDate,
            'total' => $this->calculateTotal($dto->items),
            'status' => InvoiceStatus::DRAFT,
        ]);
        
        // Crear ítems
        foreach ($dto->items as $item) {
            $this->invoiceItemRepo->create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
        
        // Reservar stock (coordinación con otro módulo)
        $this->inventoryService->reserve($invoice->items);
        
        // Evento
        event(new InvoiceCreated($invoice));
        
        return $invoice->load('items', 'customer');
    });
}
```

#### Paso 5: Repository

```php
// InvoiceRepository.php
public function create(array $data): Invoice
{
    $invoice = new Invoice();
    $invoice->fill($data);
    $invoice->save(); // TenantScope se aplica automáticamente en queries
    return $invoice;
}
```

#### Paso 6: Model

```php
// Invoice.php (Eloquent Model)
class Invoice extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'tenant_id', 'customer_id', 'due_date', 
        'total', 'status'
    ];
    
    protected $casts = [
        'due_date' => 'date',
        'total' => 'decimal:2',
        'status' => InvoiceStatus::class,
    ];
    
    // Global scope para tenant
    protected static function booted()
    {
        static::addGlobalScope(new TenantScope());
    }
    
    // Relaciones
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
```

#### Paso 7: Response

```json
HTTP/1.1 201 Created
Content-Type: application/json

{
  "data": {
    "id": 789,
    "customer": {
      "id": 123,
      "name": "ACME Inc."
    },
    "items": [
      {
        "product_id": 45,
        "quantity": 2,
        "price": "100.00",
        "subtotal": "200.00"
      }
    ],
    "total": "200.00",
    "status": "draft",
    "due_date": "2026-03-15",
    "created_at": "2026-02-11T08:10:57Z"
  }
}
```

---

## 6) Reglas de dependencias (no saltos de capa)

### 6.1 Principio de dependencia unidireccional

```
┌─────────────────────────────────────────────────────────────┐
│                     DIRECCIÓN DE DEPENDENCIAS                │
└─────────────────────────────────────────────────────────────┘

         HTTP (Controllers)
                │
                │ depende de ↓
                ▼
        APPLICATION (Services, DTOs)
                │
                │ depende de ↓
                ▼
           DOMAIN (Rules, VOs)
                │
                │ depende de ↓
                ▼
    INFRASTRUCTURE (Repos, Models)
```

### 6.2 Reglas estrictas

#### ✅ PERMITIDO

- Controller → Service ✅
- Service → Repository ✅
- Service → Service (mismo módulo o contrato) ✅
- Service → Domain (VOs, Rules) ✅
- Repository → Model ✅
- Model → Model (relaciones) ✅

#### ❌ PROHIBIDO (saltos de capa)

- Controller → Repository ❌ *debe pasar por Service*
- Controller → Model ❌ *debe pasar por Service*
- Repository → Service ❌ *repos no deciden flujos*
- Model → Service ❌ *modelos no orquestan*
- Domain → Infrastructure ❌ *dominio es puro*
- Controller directo → Eloquent/DB ❌ *nunca*

### 6.3 Excepciones controladas

**Único caso válido de "salto":**

- Controller → Repository **solo para queries de lectura simple** (ej: listar con filtros básicos)
  
  **Condición:** si no hay lógica de negocio ni coordinación.

```php
// ✅ Aceptable para listar sin lógica
public function index(InvoiceRepository $repo)
{
    return InvoiceResource::collection(
        $repo->paginate(request('per_page', 15))
    );
}

// ❌ Prohibido si hay reglas de negocio
public function store(Request $request, InvoiceRepository $repo)
{
    // ❌ NUNCA - debe ir a Service
    $invoice = $repo->create($request->all());
}
```

### 6.4 Inversión de dependencias (interfaces)

Para evitar acoplamiento:

```php
// Service depende de INTERFAZ, no de implementación concreta
class InvoiceService
{
    public function __construct(
        private InvoiceRepositoryInterface $repo  // ← interface
    ) {}
}

// Binding en ServiceProvider
$this->app->bind(
    InvoiceRepositoryInterface::class,
    InvoiceRepository::class
);
```

---

## 7) Qué NO puede hacer cada capa (prohibiciones oficiales)

### 7.1 ❌ Controllers

**PROHIBIDO:**

1. ❌ **Lógica de negocio** (cálculos, validaciones complejas, estados)
   ```php
   // ❌ MAL
   $total = 0;
   foreach ($items as $item) {
       $total += $item['price'] * $item['quantity'];
       if ($item['tax_exempt']) $total -= $total * 0.16;
   }
   ```

2. ❌ **Acceso directo a DB** (Eloquent, Query Builder, DB::table)
   ```php
   // ❌ MAL
   $invoices = Invoice::where('status', 'pending')->get();
   ```

3. ❌ **Transacciones**
   ```php
   // ❌ MAL
   DB::transaction(function() { ... });
   ```

4. ❌ **Emitir eventos de dominio** (solo puede emitir eventos HTTP si acaso)

5. ❌ **Llamar a múltiples servicios y coordinarlos** (eso es trabajo de otro Service)

**PERMITIDO:**

✅ Validar input (via FormRequest)  
✅ Llamar a UN Service  
✅ Transformar respuesta (via Resource)  
✅ Manejo de excepciones genérico (try-catch)  

---

### 7.2 ❌ Services

**PROHIBIDO:**

1. ❌ **Depender de HTTP** (Request, Response, Session, Cookies)
   ```php
   // ❌ MAL
   public function create(Request $request) { ... }
   ```

2. ❌ **Escribir SQL o queries directamente**
   ```php
   // ❌ MAL
   DB::table('invoices')->where(...)->get();
   Invoice::where(...)->first(); // debe ir en Repository
   ```

3. ❌ **"Inventarse" el tenant** (siempre obtenerlo del contexto)
   ```php
   // ❌ MAL
   $tenantId = auth()->user()->tenant_id; // puede estar vacío o falso
   
   // ✅ BIEN
   $tenantId = TenantContext::id();
   ```

4. ❌ **Formatear respuestas HTTP** (JSON, status codes)

5. ❌ **Acceder a modelos de otro módulo directamente**

**PERMITIDO:**

✅ Validar reglas de negocio  
✅ Coordinar repositorios  
✅ Transacciones  
✅ Emitir eventos  
✅ Llamar a contratos de otros módulos  

---

### 7.3 ❌ Repositories

**PROHIBIDO:**

1. ❌ **Reglas de negocio** (validaciones, cálculos, estados)
   ```php
   // ❌ MAL
   public function create(array $data): Invoice
   {
       if ($data['total'] > 10000) {
           throw new Exception('Requires approval'); // lógica de negocio
       }
       // ...
   }
   ```

2. ❌ **Side effects** (enviar emails, webhooks, eventos de dominio)
   ```php
   // ❌ MAL
   public function create(array $data): Invoice
   {
       $invoice = Invoice::create($data);
       Mail::to($invoice->customer)->send(new InvoiceCreated($invoice)); // ❌
       return $invoice;
   }
   ```

3. ❌ **Decidir flujos o coordinar otros repositorios**

4. ❌ **Transacciones** (eso lo maneja el Service)

**PERMITIDO:**

✅ CRUD básico  
✅ Queries complejas (filtros, joins, paginación)  
✅ Aplicar tenant (scope o manual)  
✅ Eager loading de relaciones  

---

### 7.4 ❌ Models (Eloquent)

**PROHIBIDO:**

1. ❌ **Orquestar procesos de negocio**
   ```php
   // ❌ MAL
   class Invoice extends Model
   {
       public function approve()
       {
           $this->status = 'approved';
           $this->save();
           $this->notifyCustomer(); // ❌ side effect
           $this->reserveInventory(); // ❌ coordinación
       }
   }
   ```

2. ❌ **Llamar a servicios o repositorios**

3. ❌ **Lógica de negocio compleja en observers/events** (debe ser en Service)

4. ❌ **Setear tenant manualmente si hay TenantScope**

**PERMITIDO:**

✅ Relaciones (hasMany, belongsTo)  
✅ Casts y mutators simples  
✅ Scopes de query simples  
✅ Aplicar TenantScope  

---

### 7.5 ❌ Domain (ValueObjects, Rules)

**PROHIBIDO:**

1. ❌ **Depender de Eloquent o DB**
   ```php
   // ❌ MAL
   class Money
   {
       public function convertTo(string $currency): Money
       {
           $rate = ExchangeRate::where('currency', $currency)->first(); // ❌ DB
           // ...
       }
   }
   ```

2. ❌ **Conocer HTTP o tenancy**

3. ❌ **Side effects** (logs extensivos, eventos)

**PERMITIDO:**

✅ Validación pura  
✅ Cálculos con datos proporcionados  
✅ Igualdad por valor  
✅ Inmutabilidad  

---

### 7.6 ❌ Multi-Tenant (reglas globales)

**PROHIBIDO:**

1. ❌ **Leer/escribir datos tenant-scoped sin tenant aplicado**
   ```php
   // ❌ MAL (sin tenant en contexto)
   $invoices = Invoice::all(); // puede traer de otros tenants si no hay scope
   ```

2. ❌ **Bypass del tenant sin ruta/admin explícito**
   ```php
   // ❌ MAL
   Invoice::withoutGlobalScope(TenantScope::class)->get(); // peligroso
   
   // ✅ BIEN (solo en rutas admin con permiso)
   if (auth()->user()->isSuperAdmin()) {
       Invoice::withoutGlobalScope(TenantScope::class)->get();
   }
   ```

3. ❌ **Jobs que operan por tenant sin TenantContext**
   ```php
   // ❌ MAL
   class ProcessInvoices implements ShouldQueue
   {
       public function handle()
       {
           Invoice::all()->each(...); // ¿de qué tenant?
       }
   }
   
   // ✅ BIEN
   class ProcessInvoices implements ShouldQueue
   {
       public function __construct(public int $tenantId) {}
       
       public function handle()
       {
           TenantContext::set($this->tenantId);
           Invoice::all()->each(...);
       }
   }
   ```

4. ❌ **Unique constraints sin `tenant_id`**
   ```php
   // ❌ MAL
   $table->unique('email'); // colisiona entre tenants
   
   // ✅ BIEN
   $table->unique(['tenant_id', 'email']);
   ```

---

## 8) Multi-Tenancy: integración completa en toda la arquitectura

El multi-tenancy es una **preocupación transversal** que atraviesa todas las capas del sistema. Esta sección define **cómo cada componente de la arquitectura garantiza el aislamiento de tenants** sin que los desarrolladores tengan que pensarlo constantemente.

### 8.1 Componentes del sistema de Multi-Tenancy

#### 8.1.1 TenantMiddleware - Puerta de entrada

**Ubicación:** `App\Core\Tenant\Middleware\IdentifyTenant.php`

**Responsabilidades:**

✅ **Resolver tenant** desde la petición (dominio/subdomain/header/token)  
✅ **Inicializar TenantContext** con el tenant identificado  
✅ **Validar existencia** del tenant  
✅ **Cambiar conexión** de BD si usa estrategia database-per-tenant  
✅ **Limpiar contexto** al finalizar la petición  

**Reglas:**

- ✅ Se ejecuta PRIMERO en el stack de middleware (excepto CORS/Security)
- ✅ Si no puede identificar tenant → 403 Forbidden
- ✅ NUNCA permite continuar sin tenant válido
- ❌ NO contiene lógica de negocio
- ❌ NO accede a repositorios de módulos

**Ubicación en kernel:**

```php
// app/Http/Kernel.php
protected $middlewareGroups = [
    'api' => [
        'throttle:api',
        \App\Core\Tenant\Middleware\IdentifyTenant::class,  // ← PRIMERO
        'auth:sanctum',
        // ... resto de middleware
    ],
];
```

#### 8.1.2 TenantService - Operaciones de gestión

**Ubicación:** `App\Core\Tenant\Services\TenantService.php`

**Responsabilidades:**

✅ **Crear nuevos tenants** (provisioning completo)  
✅ **Activar/Desactivar** tenants  
✅ **Configurar límites** (usuarios, módulos, storage)  
✅ **Migrar datos** entre tenants (si aplica)  
✅ **Generar subdominios** automáticamente  

**Reglas:**

- ✅ Es el ÚNICO servicio que puede manipular la tabla `tenants`
- ✅ Coordina provisioning de recursos (BD, storage, cache keys)
- ❌ NO se usa en peticiones normales (solo en admin/setup)
- ❌ NO contiene lógica de módulos de negocio

**Ejemplo de uso:**

```php
// Solo en contexto administrativo
$tenantService->createTenant([
    'name' => 'Acme Corp',
    'subdomain' => 'acme',
    'plan' => 'enterprise',
]);
```

#### 8.1.3 Modelo Tenant - Entidad central

**Ubicación:** `App\Core\Tenant\Models\Tenant.php`

**Responsabilidades:**

✅ Representar la entidad Tenant en base de datos  
✅ Relaciones con configuraciones y límites  
✅ Scopes para buscar por dominio/slug  
✅ Métodos para verificar estado (activo, suspendido, trial)  

**Estructura de tabla:**

```php
Schema::create('tenants', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->string('subdomain')->unique()->nullable();
    $table->string('custom_domain')->unique()->nullable();
    $table->foreignId('plan_id')->nullable()->constrained();
    $table->enum('status', ['active', 'suspended', 'trial', 'cancelled'])->default('trial');
    $table->timestamp('trial_ends_at')->nullable();
    $table->json('settings')->nullable();
    $table->json('limits')->nullable(); // {max_users: 50, max_storage_mb: 5000}
    $table->timestamps();
    $table->softDeletes();
});
```

**Reglas:**

- ✅ NO tiene `tenant_id` (es la tabla raíz)
- ✅ NO usa TenantScope
- ❌ NO se accede desde módulos de negocio directamente
- ❌ NO contiene lógica de negocio de otros módulos

### 8.2 Aislamiento por capa

#### 8.2.1 Base de datos - Aislamiento de queries

**Estrategia A: Single Database + tenant_id (RECOMENDADO)**

✅ Todas las tablas tenant-scoped tienen columna `tenant_id`  
✅ TenantScope aplica `WHERE tenant_id = ?` automáticamente  
✅ Índices y constraints incluyen `tenant_id`  
✅ Queries manuales SIEMPRE filtran por `tenant_id`  

**Reglas obligatorias:**

```php
// ✅ CORRECTO: TenantScope aplicado automáticamente
$invoices = Invoice::where('status', 'pending')->get();
// SQL: SELECT * FROM invoices WHERE tenant_id = 1 AND status = 'pending'

// ✅ CORRECTO: Query manual con tenant
$results = DB::table('invoices')
    ->where('tenant_id', TenantContext::id())
    ->where('status', 'pending')
    ->get();

// ❌ PROHIBIDO: Query sin tenant_id
$invoices = DB::table('invoices')
    ->where('status', 'pending')  // ← FUGA DE DATOS
    ->get();
```

**Estrategia B: Database-per-tenant**

✅ Cada tenant tiene su propia base de datos  
✅ TenantMiddleware cambia conexión dinámicamente  
✅ NO necesita `tenant_id` en tablas (aislamiento por BD)  
✅ Migraciones se ejecutan por cada tenant  

**Cambio de conexión:**

```php
// En TenantMiddleware
config([
    'database.connections.tenant.database' => "tenant_{$tenantId}"
]);
DB::purge('tenant');
DB::reconnect('tenant');
DB::setDefaultConnection('tenant');
```

#### 8.2.2 Cache - Aislamiento de keys

**Problema:** Cache global puede mezclar datos de tenants.

**Solución:** Prefijos automáticos por tenant.

```php
// App\Core\Tenant\Services\TenantCacheService.php
class TenantCacheService
{
    public function get(string $key, mixed $default = null): mixed
    {
        return Cache::get($this->tenantKey($key), $default);
    }
    
    public function put(string $key, mixed $value, $ttl = null): bool
    {
        return Cache::put($this->tenantKey($key), $value, $ttl);
    }
    
    public function remember(string $key, $ttl, Closure $callback): mixed
    {
        return Cache::remember($this->tenantKey($key), $ttl, $callback);
    }
    
    private function tenantKey(string $key): string
    {
        return "tenant:" . TenantContext::id() . ":{$key}";
    }
    
    public function flush(): void
    {
        // Eliminar SOLO las keys de este tenant
        $pattern = "tenant:" . TenantContext::id() . ":*";
        Cache::store()->getRedis()->del(
            Cache::store()->getRedis()->keys($pattern)
        );
    }
}
```

**Reglas obligatorias:**

✅ SIEMPRE usar `TenantCacheService` en lugar de `Cache` directamente  
✅ Keys automáticas: `tenant:1:invoices:123`  
❌ PROHIBIDO: `Cache::put('invoice:123', $data)` sin prefijo tenant  

#### 8.2.3 Storage - Aislamiento de archivos

**Problema:** Archivos mezclados entre tenants en disco.

**Solución:** Estructura de carpetas por tenant.

```php
// App\Core\Shared\Services\FileStorageService.php
class FileStorageService
{
    public function store(UploadedFile $file, string $path): string
    {
        $tenantPath = $this->tenantPath($path);
        return $file->store($tenantPath, 'private');
    }
    
    public function get(string $path): ?string
    {
        $tenantPath = $this->tenantPath($path);
        return Storage::disk('private')->get($tenantPath);
    }
    
    public function url(string $path): string
    {
        $tenantPath = $this->tenantPath($path);
        return Storage::disk('private')->url($tenantPath);
    }
    
    private function tenantPath(string $path): string
    {
        $tenantId = TenantContext::id();
        return "tenants/{$tenantId}/{$path}";
    }
    
    public function deleteTenantFiles(): void
    {
        $tenantId = TenantContext::id();
        Storage::disk('private')->deleteDirectory("tenants/{$tenantId}");
    }
}
```

**Estructura de storage:**

```
storage/
  app/
    tenants/
      1/                    # Tenant 1
        invoices/
          2024/
            invoice-001.pdf
        documents/
        images/
      2/                    # Tenant 2
        invoices/
        documents/
```

**Reglas obligatorias:**

✅ SIEMPRE usar `FileStorageService`  
✅ Rutas automáticas: `tenants/1/invoices/invoice-001.pdf`  
❌ PROHIBIDO: `Storage::put('invoices/001.pdf')` sin prefijo tenant  

#### 8.2.4 Jobs - Aislamiento en background

**Problema:** Jobs asíncronos no tienen tenant en el contexto.

**Solución:** Serializar tenant_id en el job.

```php
// App\Core\Tenant\Traits\TenantAware.php
trait TenantAware
{
    public int $tenantId;
    
    public function __construct()
    {
        $this->tenantId = TenantContext::id();
    }
    
    public function handle()
    {
        TenantContext::set($this->tenantId);
        
        try {
            $this->handleJob();
        } finally {
            TenantContext::clear();
        }
    }
    
    abstract protected function handleJob();
}

// Uso en jobs
class SendInvoiceEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, TenantAware;
    
    public function __construct(
        private int $invoiceId
    ) {
        parent::__construct(); // ← Captura tenant_id
    }
    
    protected function handleJob()
    {
        // Aquí TenantContext ya está inicializado
        $invoice = Invoice::find($this->invoiceId);
        Mail::to($invoice->customer->email)->send(new InvoiceMail($invoice));
    }
}
```

**Reglas obligatorias:**

✅ TODOS los jobs deben usar `TenantAware` trait  
✅ Tenant se serializa en el job al despacharlo  
✅ Tenant se restaura automáticamente al ejecutarse  
❌ PROHIBIDO: Jobs sin tenant (causarán excepciones)  

#### 8.2.5 Commands (Artisan) - Contexto manual

**Problema:** Comandos de consola no tienen tenant automático.

**Solución:** Iterar tenants o recibir tenant_id como argumento.

```php
// app/Console/Commands/GenerateMonthlyReports.php
class GenerateMonthlyReports extends Command
{
    protected $signature = 'reports:monthly {--tenant=}';
    
    public function handle(TenantService $tenantService)
    {
        $tenants = $this->option('tenant')
            ? [Tenant::find($this->option('tenant'))]
            : Tenant::where('status', 'active')->get();
        
        foreach ($tenants as $tenant) {
            TenantContext::set($tenant->id);
            
            try {
                $this->info("Generando reportes para {$tenant->name}...");
                $this->generateReports();
            } catch (\Exception $e) {
                $this->error("Error en tenant {$tenant->id}: {$e->getMessage()}");
            } finally {
                TenantContext::clear();
            }
        }
    }
    
    private function generateReports()
    {
        // Aquí TenantContext está activo
        $invoices = Invoice::whereMonth('created_at', now()->month)->get();
        // ...
    }
}
```

**Reglas obligatorias:**

✅ Comandos que operan en tenants DEBEN inicializar TenantContext  
✅ SIEMPRE limpiar contexto con `finally`  
✅ Soportar `--tenant=` para testing  
❌ PROHIBIDO: Comandos que asumen tenant global  

### 8.3 Reglas tenant-aware obligatorias

#### ✅ Checklist de validación

**Para TODOS los modelos tenant-scoped:**

- [ ] Tiene columna `tenant_id` (índice + foreign key)
- [ ] Aplica `TenantScope` en método `booted()`
- [ ] Unique constraints incluyen `tenant_id`
- [ ] Fillable/guarded NO incluyen `tenant_id` (se asigna automáticamente)

**Para TODOS los services:**

- [ ] Obtienen tenant de `TenantContext::id()` (nunca por parámetro)
- [ ] NO validan existencia de tenant (ya validado en middleware)
- [ ] Queries manuales incluyen `tenant_id`

**Para TODOS los jobs:**

- [ ] Usan trait `TenantAware`
- [ ] Serializan `tenant_id` en constructor
- [ ] NO asumen tenant en propiedades

**Para TODOS los tests:**

- [ ] Inicializan `TenantContext` en setUp
- [ ] Limpian contexto en tearDown
- [ ] Factories asignan `tenant_id` automáticamente

#### 🚨 Validaciones en producción

**Middleware de auditoría (opcional pero recomendado):**

```php
// App\Core\Tenant\Middleware\EnsureTenantScope.php
class EnsureTenantScope
{
    public function handle($request, Closure $next)
    {
        DB::listen(function ($query) {
            if ($this->isTenantTable($query->sql) && !$this->hasTenantFilter($query)) {
                Log::critical('Query sin tenant_id detectada', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                ]);
                
                if (config('tenancy.strict_mode')) {
                    throw new TenantScopeException('Query sin filtro de tenant');
                }
            }
        });
        
        return $next($request);
    }
}
```

### 8.4 Estrategias soportadas

**A) Una sola BD + `tenant_id` en tablas (recomendado para ERP)**

- Cada registro tenant-scoped tiene `tenant_id`
- Índices y unique constraints incluyen `tenant_id`
- Global scope automático en modelos

**B) Base de datos por tenant**

- Cada tenant tiene su DB
- Se cambia conexión dinámicamente en middleware
- No necesita `tenant_id` en tablas

> Ambas funcionan. La clave es que *la arquitectura no cambia*.

### 8.2 TenantContext (pieza central)

**Ubicación:** `Shared/Tenancy/Context/TenantContext.php`

```php
class TenantContext
{
    private static ?int $tenantId = null;
    private static ?Tenant $tenant = null;

    public static function set(int $tenantId): void
    {
        self::$tenantId = $tenantId;
        self::$tenant = Tenant::find($tenantId);
    }

    public static function id(): int
    {
        if (self::$tenantId === null) {
            throw new TenantNotSetException();
        }
        return self::$tenantId;
    }

    public static function get(): Tenant
    {
        if (self::$tenant === null) {
            throw new TenantNotSetException();
        }
        return self::$tenant;
    }

    public static function clear(): void
    {
        self::$tenantId = null;
        self::$tenant = null;
    }
}
```

**Regla de oro:**

> Nadie "adivina" el tenant. Todos lo obtienen del TenantContext.

### 8.3 Middleware de Tenancy

**Ubicación:** `Shared/Tenancy/Middleware/IdentifyTenant.php`

```php
class IdentifyTenant
{
    public function __construct(
        private TenantResolver $resolver
    ) {}

    public function handle($request, Closure $next)
    {
        // 1. Resolver tenant (subdomain/header/JWT)
        $tenantId = $this->resolver->resolve($request);
        
        if (!$tenantId) {
            return response()->json(['error' => 'Tenant not identified'], 403);
        }

        // 2. Inicializar contexto
        TenantContext::set($tenantId);

        // 3. (Opcional) Cambiar conexión si DB-per-tenant
        if (config('tenancy.database_per_tenant')) {
            $this->switchDatabase($tenantId);
        }

        // 4. Continuar
        $response = $next($request);

        // 5. Limpiar contexto después de response
        TenantContext::clear();

        return $response;
    }

    private function switchDatabase(int $tenantId): void
    {
        config([
            'database.connections.tenant.database' => "tenant_{$tenantId}"
        ]);
        DB::purge('tenant');
        DB::reconnect('tenant');
        DB::setDefaultConnection('tenant');
    }
}
```

### 8.4 TenantScope (Global Scope)

**Ubicación:** `Shared/Tenancy/Scopes/TenantScope.php`

```php
class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (TenantContext::id()) {
            $builder->where($model->getTable().'.tenant_id', TenantContext::id());
        }
    }

    public function extend(Builder $builder)
    {
        // Añadir macros si necesario (withoutTenant, etc.)
    }
}
```

**Aplicar a modelos:**

```php
class Invoice extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new TenantScope());
    }
}
```

### 8.5 Tablas: globales vs tenant-scoped

#### **Globales (sin tenant_id):**

- `tenants`
- `plans`
- `users` (si auth es global)

#### **Tenant-scoped (con tenant_id):**

- `customers`
- `invoices`
- `products`
- `inventory_movements`
- `accounting_entries`

**Migración ejemplo:**

```php
Schema::create('invoices', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
    $table->foreignId('customer_id')->constrained();
    $table->date('due_date');
    $table->decimal('total', 10, 2);
    $table->string('status');
    $table->timestamps();
    
    // Índices con tenant
    $table->unique(['tenant_id', 'invoice_number']);
    $table->index(['tenant_id', 'status']);
});
```

---

## 9) Comunicación entre módulos

### 9.1 Reglas de comunicación

**PROHIBIDO:**

❌ Módulo A usa repositorios/modelos de Módulo B directamente  
❌ Queries cross-module  
❌ Imports de clases internas  

**PERMITIDO:**

✅ **Contratos (interfaces):** Módulo A depende de interface de B  
✅ **Eventos:** Módulo A emite evento, Módulo B escucha  

### 9.2 Vía contratos (para llamadas síncronas)

**Ejemplo:** Ventas necesita reservar inventario

```php
// Inventory/Application/Contracts/InventoryServiceInterface.php
interface InventoryServiceInterface
{
    public function reserve(Collection $items): bool;
    public function release(Collection $items): bool;
}

// Inventory/Application/Services/InventoryService.php
class InventoryService implements InventoryServiceInterface
{
    public function reserve(Collection $items): bool
    {
        // lógica de reserva
    }
}

// Sales/Application/Services/InvoiceService.php
class InvoiceService
{
    public function __construct(
        private InventoryServiceInterface $inventoryService // ← interface
    ) {}

    public function create(CreateInvoiceDTO $dto): Invoice
    {
        // ...
        $this->inventoryService->reserve($invoice->items);
        // ...
    }
}
```

### 9.3 Vía eventos (para desacoplamiento máximo)

**Ejemplo:** Ventas crea factura → Contabilidad crea asiento

```php
// Sales/Domain/Events/InvoiceCreated.php
class InvoiceCreated
{
    public function __construct(
        public Invoice $invoice
    ) {}
}

// Sales/Application/Services/InvoiceService.php
public function create(CreateInvoiceDTO $dto): Invoice
{
    // ...
    event(new InvoiceCreated($invoice));
    return $invoice;
}

// Accounting/Listeners/CreateAccountingEntry.php
class CreateAccountingEntry
{
    public function handle(InvoiceCreated $event)
    {
        $this->accountingService->createEntryFromInvoice($event->invoice);
    }
}

// Accounting/Providers/AccountingServiceProvider.php
Event::listen(InvoiceCreated::class, CreateAccountingEntry::class);
```

**Ventajas:**

- Módulos no se conocen entre sí
- Fácil añadir nuevos listeners
- Mejor para auditoría y extensiones

---

## 9.3) Namespaces y estructura de módulos

### 9.3.1 Definición de Namespaces

El sistema utiliza dos namespaces raíz claramente diferenciados:

#### **App\Core** - Funcionalidad fundamental

```
App\Core\
  Shared\               # Utilidades transversales
    Services\
    Traits\
    Helpers\
    Contracts\
    Exceptions\
  Tenant\               # Sistema de multi-tenancy
    Middleware\
    Services\
    Models\
    Context\
    Scopes\
```

**Reglas:**

✅ Todo en `App\Core` es infraestructura técnica  
✅ Cualquier módulo puede usar clases de `App\Core`  
✅ `App\Core` NUNCA depende de `App\Modules`  
❌ NO contiene lógica de negocio  
❌ NO conoce entidades de módulos  

#### **App\Modules** - Módulos de negocio

```
App\Modules\
  Sales\
    Http\Controllers\
    Domain\
    Application\Services\
    Infrastructure\Persistence\Models\
  Accounting\
  Inventory\
  Purchasing\
  CRM\
```

**Reglas:**

✅ Cada módulo es independiente y autocontenido  
✅ Puede depender de contratos de otros módulos  
✅ Puede usar servicios de `App\Core`  
❌ NO puede importar clases internas de otros módulos  
❌ NO puede acceder a modelos de otros módulos  

### 9.3.2 Patrón de ServiceProvider por módulo

Cada módulo DEBE tener su propio `ServiceProvider` para:

1. Registrar servicios del módulo
2. Configurar rutas
3. Registrar eventos y listeners
4. Publicar assets/configs
5. Ejecutar migraciones

**Estructura estándar:**

```php
// App\Modules\Sales\Providers\SalesServiceProvider.php
namespace App\Modules\Sales\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Sales\Application\Contracts\InvoiceServiceInterface;
use App\Modules\Sales\Application\Services\InvoiceService;

class SalesServiceProvider extends ServiceProvider
{
    /**
     * Namespace para los controllers del módulo
     */
    protected string $namespace = 'App\Modules\Sales\Http\Controllers';
    
    /**
     * Ruta base del módulo
     */
    protected string $modulePath = __DIR__ . '/..';
    
    /**
     * Registrar servicios del contenedor
     */
    public function register(): void
    {
        // 1. Registrar contratos (interfaces)
        $this->app->bind(
            InvoiceServiceInterface::class,
            InvoiceService::class
        );
        
        // 2. Registrar servicios singleton si aplica
        $this->app->singleton(InvoiceService::class);
        
        // 3. Cargar configuración del módulo (opcional)
        $this->mergeConfigFrom(
            $this->modulePath . '/config/sales.php',
            'sales'
        );
    }
    
    /**
     * Bootstrap de servicios
     */
    public function boot(): void
    {
        // 1. Registrar rutas
        $this->registerRoutes();
        
        // 2. Registrar migraciones
        $this->loadMigrationsFrom($this->modulePath . '/Infrastructure/Migrations');
        
        // 3. Registrar traducciones (opcional)
        $this->loadTranslationsFrom($this->modulePath . '/lang', 'sales');
        
        // 4. Registrar vistas (opcional)
        $this->loadViewsFrom($this->modulePath . '/resources/views', 'sales');
        
        // 5. Registrar eventos
        $this->registerEvents();
        
        // 6. Publicar assets (opcional)
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->modulePath . '/config/sales.php' => config_path('sales.php'),
            ], 'sales-config');
        }
    }
    
    /**
     * Registrar rutas del módulo
     */
    protected function registerRoutes(): void
    {
        // Rutas API
        Route::group([
            'middleware' => ['api', 'auth:sanctum'],
            'prefix' => 'api/sales',
            'namespace' => $this->namespace,
        ], function () {
            require $this->modulePath . '/Routes/api.php';
        });
        
        // Rutas Web (si aplica)
        Route::group([
            'middleware' => ['web'],
            'prefix' => 'sales',
            'namespace' => $this->namespace,
        ], function () {
            require $this->modulePath . '/Routes/web.php';
        });
    }
    
    /**
     * Registrar eventos y listeners
     */
    protected function registerEvents(): void
    {
        Event::listen(
            InvoiceCreated::class,
            UpdateInventoryListener::class
        );
        
        Event::listen(
            InvoiceCreated::class,
            SendInvoiceEmailListener::class
        );
    }
}
```

### 9.3.3 Cómo se registra un módulo

**Paso 1: Crear el ServiceProvider del módulo** (ver estructura arriba)

**Paso 2: Registrar en `config/app.php`**

```php
// config/app.php
return [
    'providers' => [
        // ... providers de Laravel
        
        /*
         * Application Service Providers
         */
        App\Providers\AppServiceProvider::class,
        
        /*
         * Module Service Providers
         */
        App\Modules\Sales\Providers\SalesServiceProvider::class,
        App\Modules\Accounting\Providers\AccountingServiceProvider::class,
        App\Modules\Inventory\Providers\InventoryServiceProvider::class,
        App\Modules\Purchasing\Providers\PurchasingServiceProvider::class,
        App\Modules\CRM\Providers\CRMServiceProvider::class,
    ],
];
```

**Paso 3: Validar autoloading en `composer.json`**

```json
{
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "App\\Core\\": "app/Core/",
            "App\\Modules\\": "app/Modules/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/"
        },
        "files": [
            "app/Core/Shared/Helpers/helpers.php"
        ]
    }
}
```

**Paso 4: Ejecutar `composer dump-autoload`**

```bash
composer dump-autoload
```

**Paso 5: Verificar registro**

```bash
php artisan about
# Debe mostrar el módulo en la lista de providers
```

### 9.3.4 Validaciones de autoloading (sin lógica de negocio)

**Checklist de validación:**

- [ ] Namespace correcto en todas las clases del módulo
- [ ] ServiceProvider registrado en `config/app.php`
- [ ] PSR-4 configurado correctamente en `composer.json`
- [ ] `composer dump-autoload` ejecutado
- [ ] Rutas accesibles (`php artisan route:list | grep sales`)
- [ ] Migraciones detectadas (`php artisan migrate:status`)
- [ ] NO hay lógica de negocio en el ServiceProvider (solo registro/configuración)

---

## 9.4) Convenciones de módulos de negocio

Todos los módulos del ERP deben seguir estas convenciones para mantener consistencia.

### 9.4.1 Estructura estándar de un módulo

```
App/Modules/{ModuleName}/
  
  Http/                             # Capa de presentación HTTP
    Controllers/                    # Controladores REST/Web
      {Entity}Controller.php        # CRUD básico
    Requests/                       # Validación de entrada
      Create{Entity}Request.php
      Update{Entity}Request.php
    Resources/                      # Transformación de salida (JSON)
      {Entity}Resource.php
      {Entity}Collection.php
    Middleware/                     # Middleware específico del módulo
  
  Domain/                           # Lógica de negocio pura
    ValueObjects/                   # Objetos de valor (Money, Email, etc.)
      Money.php
      TaxId.php
    Enums/                          # Estados y tipos enumerados
      InvoiceStatus.php
      PaymentMethod.php
    Rules/                          # Reglas de validación de negocio
      CustomerCanBuyRule.php
    Policies/                       # Autorización de Laravel
      InvoicePolicy.php
    Events/                         # Eventos de dominio
      InvoiceCreated.php
      InvoiceApproved.php
    Exceptions/                     # Excepciones del dominio
      InvoiceException.php
  
  Application/                      # Capa de aplicación (casos de uso)
    Services/                       # Servicios de aplicación (orquestadores)
      InvoiceService.php
    DTOs/                           # Data Transfer Objects
      CreateInvoiceDTO.php
      UpdateInvoiceDTO.php
    Contracts/                      # Interfaces públicas del módulo
      InvoiceServiceInterface.php
    UseCases/                       # Casos de uso explícitos (opcional)
      CreateInvoiceUseCase.php
  
  Infrastructure/                   # Capa de infraestructura
    Persistence/
      Models/                       # Modelos Eloquent
        Invoice.php
        InvoiceLine.php
      Repositories/                 # Implementación de repositorios
        InvoiceRepository.php
    Providers/                      # Service Provider del módulo
      SalesServiceProvider.php
    Migrations/                     # Migraciones de BD
      2024_01_01_000000_create_invoices_table.php
    Seeders/                        # Seeders
      InvoiceSeeder.php
    Factories/                      # Factories para testing
      InvoiceFactory.php
  
  Routes/                           # Rutas del módulo
    api.php                         # Rutas API
    web.php                         # Rutas web (si aplica)
  
  Tests/                            # Tests del módulo
    Feature/                        # Tests de integración
      InvoiceControllerTest.php
    Unit/                           # Tests unitarios
      InvoiceServiceTest.php
  
  config/                           # Configuración del módulo (opcional)
    sales.php
  
  lang/                             # Traducciones (opcional)
    es/
      messages.php
```

### 9.4.2 Naming Conventions

#### **Controllers**

```php
// Singular, terminan en "Controller"
InvoiceController.php
CustomerController.php
ProductController.php

// Métodos REST estándar
index()     // GET /invoices
store()     // POST /invoices
show()      // GET /invoices/{id}
update()    // PUT/PATCH /invoices/{id}
destroy()   // DELETE /invoices/{id}
```

#### **Services**

```php
// Singular, terminan en "Service"
InvoiceService.php
CustomerService.php

// Métodos descriptivos del caso de uso
create(CreateInvoiceDTO $dto): Invoice
approve(int $id): Invoice
cancel(int $id, string $reason): Invoice
generatePdf(int $id): string
```

#### **Repositories**

```php
// Singular, terminan en "Repository"
InvoiceRepository.php

// Métodos de acceso a datos
find(int $id): ?Invoice
findByNumber(string $number): ?Invoice
getByCustomer(int $customerId): Collection
getPending(): Collection
save(Invoice $invoice): Invoice
delete(int $id): bool
```

#### **Models**

```php
// Singular, sin sufijos
Invoice.php
Customer.php
Product.php

// Tabla en plural (Laravel convention)
protected $table = 'invoices';
```

#### **DTOs**

```php
// Acción + Entidad + "DTO"
CreateInvoiceDTO.php
UpdateInvoiceDTO.php
FilterInvoiceDTO.php
```

#### **Requests**

```php
// Acción + Entidad + "Request"
CreateInvoiceRequest.php
UpdateInvoiceRequest.php
ApproveInvoiceRequest.php
```

#### **Resources**

```php
// Entidad + "Resource" o "Collection"
InvoiceResource.php
InvoiceCollection.php
```

#### **Events**

```php
// Entidad + Pasado (lo que pasó)
InvoiceCreated.php
InvoiceApproved.php
PaymentReceived.php
```

#### **Exceptions**

```php
// Entidad + "Exception" o descripción específica
InvoiceException.php
InvoiceNotFoundException.php
InvoiceAlreadyApprovedException.php
```

### 9.4.3 Reglas de validación (FormRequest)

**Ubicación:** `Http/Requests/`

**Responsabilidades:**

✅ Validar tipos de datos, formatos, obligatoriedad  
✅ Validar existencia de relaciones (exists:tabla,id)  
✅ Reglas de autorización básicas  
❌ NO contiene lógica de negocio compleja  
❌ NO accede a servicios para validar reglas de dominio  

**Ejemplo:**

```php
// App\Modules\Sales\Http\Requests\CreateInvoiceRequest.php
namespace App\Modules\Sales\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Autorización simple (delegado a Policy si es complejo)
        return true;
    }
    
    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'due_date' => ['required', 'date', 'after:today'],
            'currency' => ['required', 'string', 'in:USD,EUR,MXN'],
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'lines.*.quantity' => ['required', 'integer', 'min:1'],
            'lines.*.price' => ['required', 'numeric', 'min:0'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'customer_id.exists' => 'El cliente seleccionado no existe.',
            'due_date.after' => 'La fecha de vencimiento debe ser futura.',
            'lines.min' => 'La factura debe tener al menos una línea.',
        ];
    }
}
```

**Reglas de validación de negocio complejas → Services**

```php
// ❌ MAL: Validación compleja en FormRequest
public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $customer = Customer::find($this->customer_id);
        if ($customer->credit_limit < $this->total) {
            $validator->errors()->add('customer_id', 'Límite de crédito excedido');
        }
    });
}

// ✅ BIEN: Validación compleja en Service
class InvoiceService
{
    public function create(CreateInvoiceDTO $dto): Invoice
    {
        // Validación de negocio
        if (!$this->customerCanBuy($dto->customerId, $dto->total)) {
            throw new CustomerCreditExceededException();
        }
        
        // ... crear factura
    }
}
```

### 9.4.4 Uso de eventos y excepciones

#### **Eventos de dominio**

**Ubicación:** `Domain/Events/`

**Cuándo emitir:**

✅ Después de operaciones exitosas (factura creada, pedido aprobado)  
✅ Cuando otros módulos necesiten reaccionar  
✅ Para auditoría y logs  

**Estructura:**

```php
// App\Modules\Sales\Domain\Events\InvoiceCreated.php
namespace App\Modules\Sales\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Modules\Sales\Infrastructure\Persistence\Models\Invoice;

class InvoiceCreated
{
    use Dispatchable, SerializesModels;
    
    public function __construct(
        public Invoice $invoice
    ) {}
}
```

**Emisión en Service:**

```php
public function create(CreateInvoiceDTO $dto): Invoice
{
    DB::transaction(function () use ($dto) {
        $invoice = $this->repository->save($dto);
        
        // Emitir evento
        event(new InvoiceCreated($invoice));
        
        return $invoice;
    });
}
```

#### **Excepciones de dominio**

**Ubicación:** `Domain/Exceptions/`

**Jerarquía:**

```php
// App\Core\Shared\Exceptions\DomainException.php
abstract class DomainException extends Exception
{
    protected $statusCode = 500;
    protected $errorCode;
    
    public function render($request)
    {
        return response()->json([
            'error' => $this->getMessage(),
            'code' => $this->errorCode,
        ], $this->statusCode);
    }
}

// App\Modules\Sales\Domain\Exceptions\InvoiceException.php
class InvoiceException extends DomainException
{
    protected $statusCode = 422;
    protected $errorCode = 'INVOICE_ERROR';
}

// Excepciones específicas
class InvoiceNotFoundException extends InvoiceException
{
    protected $statusCode = 404;
    protected $errorCode = 'INVOICE_NOT_FOUND';
    
    public function __construct(int $invoiceId)
    {
        parent::__construct("Factura #{$invoiceId} no encontrada");
    }
}

class InvoiceAlreadyApprovedException extends InvoiceException
{
    protected $errorCode = 'INVOICE_ALREADY_APPROVED';
    
    public function __construct()
    {
        parent::__construct("La factura ya está aprobada");
    }
}
```

**Uso en Services:**

```php
public function approve(int $id): Invoice
{
    $invoice = $this->repository->find($id);
    
    if (!$invoice) {
        throw new InvoiceNotFoundException($id);
    }
    
    if ($invoice->status === InvoiceStatus::APPROVED) {
        throw new InvoiceAlreadyApprovedException();
    }
    
    $invoice->status = InvoiceStatus::APPROVED;
    $invoice->approved_at = now();
    
    return $this->repository->save($invoice);
}
```

### 9.4.5 Ejemplo completo: Módulo Inventory

**Estructura del módulo:**

```
App/Modules/Inventory/
  Http/
    Controllers/
      ProductController.php
      StockMovementController.php
    Requests/
      CreateProductRequest.php
      UpdateProductRequest.php
      RegisterMovementRequest.php
    Resources/
      ProductResource.php
      StockMovementResource.php
  
  Domain/
    Enums/
      MovementType.php              # IN, OUT, ADJUSTMENT
      StockStatus.php               # AVAILABLE, RESERVED, DAMAGED
    Events/
      ProductCreated.php
      StockMovementRegistered.php
      LowStockAlert.php
    Exceptions/
      InsufficientStockException.php
      ProductNotFoundException.php
  
  Application/
    Services/
      ProductService.php
      StockMovementService.php
    DTOs/
      CreateProductDTO.php
      RegisterMovementDTO.php
    Contracts/
      InventoryServiceInterface.php
  
  Infrastructure/
    Persistence/
      Models/
        Product.php
        StockMovement.php
        Warehouse.php
      Repositories/
        ProductRepository.php
        StockMovementRepository.php
    Providers/
      InventoryServiceProvider.php
    Migrations/
      2024_01_01_000000_create_products_table.php
      2024_01_01_000001_create_stock_movements_table.php
      2024_01_01_000002_create_warehouses_table.php
  
  Routes/
    api.php
```

**Ejemplo de Service:**

```php
// App\Modules\Inventory\Application\Services\StockMovementService.php
namespace App\Modules\Inventory\Application\Services;

use App\Core\Tenant\Context\TenantContext;
use App\Modules\Inventory\Application\DTOs\RegisterMovementDTO;
use App\Modules\Inventory\Infrastructure\Persistence\Models\StockMovement;
use App\Modules\Inventory\Infrastructure\Persistence\Repositories\StockMovementRepository;
use App\Modules\Inventory\Infrastructure\Persistence\Repositories\ProductRepository;
use App\Modules\Inventory\Domain\Events\StockMovementRegistered;
use App\Modules\Inventory\Domain\Events\LowStockAlert;
use App\Modules\Inventory\Domain\Exceptions\InsufficientStockException;
use Illuminate\Support\Facades\DB;

class StockMovementService
{
    public function __construct(
        private StockMovementRepository $movementRepository,
        private ProductRepository $productRepository
    ) {}
    
    public function register(RegisterMovementDTO $dto): StockMovement
    {
        return DB::transaction(function () use ($dto) {
            $product = $this->productRepository->find($dto->productId);
            
            // Validar stock suficiente para salidas
            if ($dto->type === 'OUT' && $product->stock < $dto->quantity) {
                throw new InsufficientStockException($product->name, $dto->quantity);
            }
            
            // Registrar movimiento
            $movement = new StockMovement([
                'tenant_id' => TenantContext::id(),
                'product_id' => $dto->productId,
                'warehouse_id' => $dto->warehouseId,
                'type' => $dto->type,
                'quantity' => $dto->quantity,
                'reference' => $dto->reference,
                'notes' => $dto->notes,
            ]);
            
            $movement = $this->movementRepository->save($movement);
            
            // Actualizar stock del producto
            $this->updateProductStock($product, $dto->type, $dto->quantity);
            
            // Emitir eventos
            event(new StockMovementRegistered($movement));
            
            if ($product->stock < $product->min_stock) {
                event(new LowStockAlert($product));
            }
            
            return $movement;
        });
    }
    
    private function updateProductStock($product, string $type, int $quantity): void
    {
        if ($type === 'IN') {
            $product->stock += $quantity;
        } elseif ($type === 'OUT') {
            $product->stock -= $quantity;
        } elseif ($type === 'ADJUSTMENT') {
            $product->stock = $quantity; // Ajuste absoluto
        }
        
        $this->productRepository->save($product);
    }
}
```

---

## 10) Testing recomendado

### 10.1 Unit Tests (Domain)

**Qué testear:**

- ValueObjects: validación, igualdad, inmutabilidad
- Rules: lógica pura sin DB

```php
// Tests/Unit/Domain/ValueObjects/MoneyTest.php
test('money cannot be negative', function () {
    expect(fn() => new Money(-100, 'USD'))
        ->toThrow(InvalidArgumentException::class);
});
```

### 10.2 Unit Tests (Services)

**Qué testear:**

- Lógica de negocio con repositorios mockeados

```php
// Tests/Unit/Application/Services/InvoiceServiceTest.php
test('creates invoice with correct data', function () {
    $repo = Mockery::mock(InvoiceRepositoryInterface::class);
    $repo->shouldReceive('create')->once()->andReturn(new Invoice());
    
    $service = new InvoiceService($repo);
    $dto = new CreateInvoiceDTO(...);
    
    $invoice = $service->create($dto);
    
    expect($invoice)->toBeInstanceOf(Invoice::class);
});
```

### 10.3 Feature Tests (HTTP)

**Qué testear:**

- Endpoints completos (request → response)
- Con tenant aplicado

```php
// Tests/Feature/Http/Controllers/InvoiceControllerTest.php
test('creates invoice via API', function () {
    TenantContext::set(1);
    
    $response = $this->postJson('/api/invoices', [
        'customer_id' => 123,
        'items' => [...]
    ]);
    
    $response->assertStatus(201)
        ->assertJsonStructure(['data' => ['id', 'total']]);
        
    $this->assertDatabaseHas('invoices', [
        'tenant_id' => 1,
        'customer_id' => 123
    ]);
});
```

### 10.4 Integration Tests (Repositories)

**Qué testear:**

- Repositorios contra DB real
- Con tenant aplicado

```php
test('repository filters by tenant', function () {
    TenantContext::set(1);
    
    Invoice::factory()->create(['tenant_id' => 1]);
    Invoice::factory()->create(['tenant_id' => 2]);
    
    $repo = app(InvoiceRepositoryInterface::class);
    $invoices = $repo->all();
    
    expect($invoices)->toHaveCount(1)
        ->and($invoices->first()->tenant_id)->toBe(1);
});
```

---

## 11) Resumen ejecutivo "para tontos"

### Lo mínimo que debes recordar:

#### **Capas y responsabilidades:**

- **Controller** = mensajero (recibe y responde)
- **Service** = cerebro (decide y orquesta el negocio)
- **Repository** = archivador (lee/escribe en DB)
- **Model** = estructura de datos (tabla + relaciones)
- **Domain** = reglas puras (sin DB, sin HTTP)

#### **Flujo obligatorio:**

```
Request → Middleware (Tenant) → Controller → Service → Repository → Model
```

#### **Prohibiciones principales:**

❌ Controller NO hace lógica de negocio  
❌ Controller NO accede a DB directamente  
❌ Service NO recibe Request ni devuelve Response  
❌ Service NO escribe SQL  
❌ Repository NO decide reglas de negocio  
❌ Model NO orquesta procesos  
❌ Ningún módulo usa internals de otro módulo  

#### **Multi-tenancy:**

✅ Tenant se resuelve en middleware  
✅ TenantContext es la única fuente de verdad  
✅ TenantScope se aplica automáticamente  
✅ Tablas tenant-scoped tienen `tenant_id`  
✅ Jobs y comandos inicializan TenantContext  

#### **Comunicación entre módulos:**

✅ Por contratos (interfaces)  
✅ Por eventos (recomendado)  
❌ NUNCA acceso directo a modelos/repos de otro módulo  

---

## Apéndices

### A) Checklist de validación de código

**Antes de hacer commit, verifica:**

- [ ] ¿El Controller solo llama a Service?
- [ ] ¿El Service NO recibe Request ni devuelve Response?
- [ ] ¿El Repository solo hace queries sin lógica de negocio?
- [ ] ¿El tenant está aplicado automáticamente (scope o contexto)?
- [ ] ¿No hay saltos de capa (Controller → Repository)?
- [ ] ¿Los módulos se comunican por contratos/eventos?
- [ ] ¿Las migraciones incluyen `tenant_id` en unique constraints?

### B) Plantilla de módulo nuevo

**Próximo paso recomendado:**

Crear una plantilla "copiar/pegar" con:

- Estructura de carpetas completa
- Clases ejemplo (Controller, Service, Repository, DTO, Model)
- Configuración de rutas y providers
- Tests básicos
- Integración de tenancy

---

**Fin de la especificación de arquitectura ERP Laravel v1.0**

---

*Última actualización: Febrero 2026*  
*Mantenido por: Equipo de Arquitectura*
