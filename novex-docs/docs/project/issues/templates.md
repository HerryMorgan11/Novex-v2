---
sidebar_position: 2
---

# GitHub Issues Template - Novex v2 ERP

Este documento contiene templates para crear issues en GitHub para cada fase del proyecto.

---

## FASE 1: Infraestructura y Core

### Issue 1.1: Estructura Clean Architecture

```markdown
## Tarea: Crear Estructura Clean Architecture

### Descripción

Implementar la estructura de directorios base para Clean Architecture según la documentación en `/docs/arquitectura.md`

### Objetivos

- [ ] Crear estructura de directorios `app/Core/`
- [ ] Crear estructura Domain (Shared, ValueObjects, Exceptions, Contracts)
- [ ] Crear estructura Application (Shared, DTOs, Services)
- [ ] Crear estructura Infrastructure (Shared, Database, Cache, Logging)
- [ ] Crear Value Objects base (Email, Phone, Money)
- [ ] Crear excepciones personalizadas del dominio
- [ ] Configurar Service Providers para Core

### Archivos a Crear
```

app/Core/
├── Domain/
│ └── Shared/
│ ├── ValueObjects/
│ │ ├── Email.php
│ │ ├── Phone.php
│ │ └── Money.php
│ ├── Exceptions/
│ │ ├── DomainException.php
│ │ └── ValidationException.php
│ └── Contracts/
│ └── AggregateRoot.php
├── Application/
│ └── Shared/
│ ├── DTOs/
│ ├── Services/
│ └── Contracts/
└── Infrastructure/
└── Shared/
├── Database/
├── Cache/
└── Logging/

```

### Criterios de Aceptación
- [ ] Estructura de directorios creada
- [ ] Value Objects implementados con validación
- [ ] Excepciones personalizadas funcionando
- [ ] Service Provider registrado
- [ ] Tests unitarios para Value Objects

### Referencias
- `/docs/arquitectura.md`
- Clean Architecture - Robert C. Martin

### Etiquetas
`fase-1` `infrastructure` `clean-architecture` `priority-high`

### Estimación
3 días
```

---

### Issue 1.2: Configuración Base de Datos

````markdown
## Tarea: Configuración Base de Datos

### Descripción

Configurar las conexiones de base de datos para soportar multi-tenancy (BD central + BDs por tenant)

### Objetivos

- [ ] Configurar conexión BD central (landlord) en `.env`
- [ ] Configurar conexión BD tenant template
- [ ] Extender `config/database.php` para multi-tenancy
- [ ] Crear archivo `.env.example` actualizado
- [ ] Documentar configuración en README

### Configuración Requerida

**`.env`**

```env
# Central Database (Landlord)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=novex_central
DB_USERNAME=root
DB_PASSWORD=

# Tenant Database Template
TENANT_DB_HOST=127.0.0.1
TENANT_DB_PORT=3306
TENANT_DB_USERNAME=root
TENANT_DB_PASSWORD=
```
````

### Criterios de Aceptación

- [ ] Variables de entorno configuradas
- [ ] Conexiones de BD funcionando
- [ ] Tests de conexión exitosos
- [ ] Documentación actualizada

### Referencias

- `/docs/baseDeDatos.md`
- Laravel Database Documentation

### Etiquetas

`fase-1` `database` `configuration` `priority-high`

### Estimación

1 día

````

---

### Issue 1.3: Herramientas de Desarrollo

```markdown
##  Tarea: Configurar Herramientas de Desarrollo

### Descripción
Configurar y validar herramientas de análisis de código y formateo

### Objetivos
- [ ] Configurar Laravel Pint (PHP formatting)
- [ ] Configurar PHPStan (análisis estático)
- [ ] Configurar ESLint (JavaScript)
- [ ] Configurar Prettier (JavaScript/CSS)
- [ ] Configurar Git hooks con Husky
- [ ] Documentar uso de herramientas

### Comandos a Validar
```bash
# PHP
./vendor/bin/pint
./vendor/bin/phpstan analyse

# JavaScript
npm run lint
npm run format

# Git hooks
npm run prepare
````

### Criterios de Aceptación

- [ ] Todas las herramientas instaladas
- [ ] Configuraciones optimizadas para el proyecto
- [ ] Git hooks funcionando (pre-commit)
- [ ] Scripts en `package.json` documentados
- [ ] README con guía de herramientas

### Etiquetas

`fase-1` `devtools` `configuration` `priority-medium`

### Estimación

1 día

````

---

##  FASE 2: Autenticación y Multi-Tenancy

### Issue 2.1: Instalación Multi-Tenancy

```markdown
## Tarea: Instalar y Configurar Multi-Tenancy

### Descripción
Instalar y configurar el paquete `stancl/tenancy` para soportar multi-tenancy con database por tenant

### Objetivos
- [ ] Instalar paquete `stancl/tenancy`
- [ ] Ejecutar comando de instalación
- [ ] Configurar `config/tenancy.php`
- [ ] Ejecutar migraciones de tenancy
- [ ] Configurar identificación por subdominio
- [ ] Configurar middleware de tenancy

### Comandos
```bash
composer require stancl/tenancy
php artisan tenancy:install
php artisan migrate
````

### Configuración

```php
// config/tenancy.php
'tenant_route_namespace' => 'App\Http\Controllers\Tenant',
'home_url' => env('APP_URL'),
```

### Criterios de Aceptación

- [ ] Paquete instalado correctamente
- [ ] Migraciones ejecutadas
- [ ] Configuración de subdominios funcionando
- [ ] Middleware registrado
- [ ] Tests de tenancy básicos

### Referencias

- https://tenancyforlaravel.com/docs/
- `/docs/arquitectura.md`

### Etiquetas

`fase-2` `multi-tenancy` `priority-high` `critical`

### Estimación

2 días

````

---

### Issue 2.2: Base de Datos Central (Landlord)

```markdown
##  Tarea: Implementar Base de Datos Central

### Descripción
Crear migraciones y modelos para la base de datos central (landlord) que gestiona tenants, usuarios y suscripciones

### Objetivos
- [ ] Crear migración `tenants`
- [ ] Crear migración `domains`
- [ ] Crear migración `users` (central)
- [ ] Crear migración `plans`
- [ ] Crear migración `subscriptions`
- [ ] Crear modelo Tenant con traits
- [ ] Crear seeders de prueba
- [ ] Crear factories

### Migraciones Requeridas
1. `create_tenants_table`
2. `create_domains_table`
3. `create_users_table`
4. `create_plans_table`
5. `create_subscriptions_table`

### Modelo Tenant
```php
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;
}
````

### Criterios de Aceptación

- [ ] Todas las migraciones creadas y ejecutadas
- [ ] Modelo Tenant funcionando
- [ ] Seeders generando datos de prueba
- [ ] Tests de modelo pasando

### Referencias

- `/docs/baseDeDatos.md` - Sección "BD CENTRAL"

### Etiquetas

`fase-2` `database` `multi-tenancy` `priority-high`

### Estimación

2 días

````

---

### Issue 2.3: Sistema de Autenticación

```markdown
##  Tarea: Implementar Sistema de Autenticación Completo

### Descripción
Implementar sistema completo de autenticación con login, registro, reset de contraseña y tenant detection

### Objetivos
#### Controllers
- [ ] Implementar `AuthController::showLogin()`
- [ ] Implementar `AuthController::login()`
- [ ] Implementar `AuthController::showRegister()`
- [ ] Implementar `AuthController::register()`
- [ ] Implementar `AuthController::logout()`
- [ ] Implementar `AuthController::showForgotPassword()`
- [ ] Implementar `AuthController::sendResetLink()`

#### Form Requests
- [ ] Crear `LoginRequest` con validación
- [ ] Crear `RegisterRequest` con validación

#### Vistas
- [ ] Diseñar `auth/login.blade.php`
- [ ] Diseñar `auth/register.blade.php`
- [ ] Diseñar `auth/forgot-password.blade.php`
- [ ] Diseñar `auth/reset-password.blade.php`
- [ ] Crear layout `auth/layout.blade.php`

#### Testing
- [ ] Tests de login (éxito y error)
- [ ] Tests de registro
- [ ] Tests de logout
- [ ] Tests de reset password

### Rutas
```php
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
````

### Criterios de Aceptación

- [ ] Login funcionando con tenant detection
- [ ] Registro creando tenant nuevo
- [ ] Reset de contraseña enviando emails
- [ ] Vistas con diseño profesional (Tailwind CSS)
- [ ] Tests de autenticación pasando

### Etiquetas

`fase-2` `authentication` `priority-high` `frontend` `backend`

### Estimación

3 días

````

---

### Issue 2.4: Flujo Multi-Tenant Login

```markdown
##  Tarea: Implementar Flujo de Login Multi-Tenant

### Descripción
Implementar el flujo completo de autenticación tenant-aware con detección automática de tenant y redirección

### Objetivos
- [ ] Crear middleware `InitializeTenancy`
- [ ] Implementar lógica de detección de tenant por subdominio
- [ ] Crear ruta `/auth/consume` para tokens
- [ ] Configurar sesiones por tenant
- [ ] Configurar cache por tenant
- [ ] Configurar storage por tenant
- [ ] Implementar redirección post-login

### Flujo de Login
````

1. Usuario hace click en Login
2. Redirección a: auth.miapp.com/login
3. Usuario ingresa credenciales
4. Sistema valida y detecta tenant
5. Genera token de sesión
6. Redirige a: \{tenant\}.miapp.com/auth/consume?token=xxx
7. Tenant inicializa sesión
8. Usuario accede al dashboard

````

### Middleware
```php
Route::middleware(['tenant'])->group(function () {
    // Rutas del dashboard
});
````

### Criterios de Aceptación

- [ ] Middleware tenant funcionando
- [ ] Detección de tenant por subdominio
- [ ] Sesiones aisladas por tenant
- [ ] Cache aislado por tenant
- [ ] Redirección automática funcionando
- [ ] Tests de flujo completo

### Referencias

- `/docs/landingPublica.md` - Sección "Workflow"

### Etiquetas

`fase-2` `multi-tenancy` `authentication` `priority-high` `complex`

### Estimación

2 días

````

---

##  FASE 3: Landing Page

### Issue 3.1: Layout y Componentes Landing

```markdown
##  Tarea: Completar Layout y Componentes de Landing

### Descripción
Completar el layout principal de la landing page y crear componentes compartidos reutilizables

### Objetivos
#### Layout
- [ ] Completar `landing/layout/app.blade.php`
- [ ] Implementar navbar responsive
- [ ] Implementar footer completo
- [ ] Configurar Tailwind CSS con tema personalizado

#### Componentes
- [ ] Crear componente de navbar con menú mobile
- [ ] Crear componente de footer con enlaces
- [ ] Crear componentes de botones CTA
- [ ] Crear componente de cards
- [ ] Implementar dark mode toggle (opcional)

#### Navegación
```blade
- Home
- Pricing
- Features
- About
- Contact
- Login (CTA)
- Get Started (CTA)
````

### Diseño

- Usar Tailwind CSS
- Responsive mobile-first
- Animaciones suaves con Alpine.js
- Accesible (WCAG 2.1)

### Criterios de Aceptación

- [ ] Layout responsive en todos los dispositivos
- [ ] Navbar con menú hamburguesa en mobile
- [ ] Footer con enlaces funcionando
- [ ] Componentes reutilizables documentados
- [ ] Dark mode funcionando (si implementado)

### Etiquetas

`fase-3` `landing` `frontend` `ui-ux` `priority-medium`

### Estimación

2 días

````

---

### Issue 3.2: Home Page Landing

```markdown
## Tarea: Implementar Home Page Completa

### Descripción
Crear home page de la landing con todas las secciones principales

### Objetivos
#### Secciones
- [ ] Hero section con CTA principal
- [ ] Features section (3-4 features destacadas)
- [ ] Benefits section (por qué elegir Novex)
- [ ] Social proof / Testimonios
- [ ] Pricing preview (llamado a acción)
- [ ] Final CTA section

#### Contenido
- [ ] Copywriting para hero
- [ ] Descripciones de features
- [ ] Testimonios de clientes
- [ ] Imágenes/ilustraciones

#### Interactividad
- [ ] Scroll animations
- [ ] Hover effects en cards
- [ ] CTA buttons con estados
- [ ] Smooth scrolling

### Estructura Hero
```blade
<section class="hero">
    <h1>Gestiona tu negocio con Novex ERP</h1>
    <p>La solución todo-en-uno para inventario, ventas, CRM y más</p>
    <div class="cta-buttons">
        <a href="/register">Empezar Gratis</a>
        <a href="/demo">Ver Demo</a>
    </div>
</section>
````

### Criterios de Aceptación

- [ ] Todas las secciones implementadas
- [ ] Contenido de calidad (textos e imágenes)
- [ ] Animaciones funcionando
- [ ] CTAs llamativos y claros
- [ ] Performance optimizado (LCP < 2.5s)

### Etiquetas

`fase-3` `landing` `frontend` `content` `priority-high`

### Estimación

2 días

````

---

### Issue 3.3: Pricing Page

```markdown
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
````

Basic - $X/mes

- X usuarios
- X productos
- Soporte email

Pro - $Y/mes [POPULAR]

- Usuarios ilimitados
- Productos ilimitados
- Soporte prioritario
- API access

Enterprise - Contactar

- Todo de Pro
- Soporte dedicado
- Customización
- Onboarding

```

### Componentes
- [ ] PricingCard component
- [ ] FeatureComparison component
- [ ] PricingToggle component (monthly/yearly)
- [ ] FAQ accordion

### Criterios de Aceptación
- [ ] 3 planes claramente diferenciados
- [ ] Toggle mensual/anual funcionando
- [ ] Tabla de comparación responsive
- [ ] FAQ con preguntas comunes
- [ ] CTAs direccionando a /register

### Etiquetas
`fase-3` `landing` `pricing` `frontend` `priority-high`

### Estimación
1 día
```

---

## FASE 4: Dashboard Foundation

### Issue 4.1: Layout Dashboard

```markdown
## Tarea: Implementar Layout del Dashboard

### Descripción

Crear el layout base del dashboard que será usado por todos los módulos del ERP

### Objetivos

#### Layout Principal

- [ ] Crear `dashboard/layouts/app.blade.php`
- [ ] Implementar sidebar con navegación
- [ ] Implementar navbar superior
- [ ] Implementar breadcrumbs
- [ ] Implementar área de contenido principal

#### Sidebar

- [ ] Logo y nombre del tenant
- [ ] Menú de navegación con iconos
- [ ] Indicador de página activa
- [ ] Botón de colapsar sidebar
- [ ] Links a módulos:
    - Dashboard Home
    - Inventario
    - Ventas
    - CRM
    - Contabilidad
    - RRHH
    - Configuración

#### Navbar

- [ ] Breadcrumb navigation
- [ ] Buscador global
- [ ] Notificaciones dropdown
- [ ] Perfil usuario dropdown
    - Ver perfil
    - Configuración
    - Logout

#### Responsive

- [ ] Mobile menu (hamburger)
- [ ] Sidebar colapsable
- [ ] Responsive en tablets

### Criterios de Aceptación

- [ ] Layout responsive en todos los dispositivos
- [ ] Navegación intuitiva y clara
- [ ] Sidebar colapsable funcionando
- [ ] Mobile menu funcionando
- [ ] Transiciones suaves

### Etiquetas

`fase-4` `dashboard` `layout` `ui-ux` `priority-high`

### Estimación

3 días
```

---

### Issue 4.2: Componentes Compartidos Dashboard

````markdown
## Tarea: Crear Componentes Compartidos del Dashboard

### Descripción

Crear biblioteca de componentes Blade y Livewire reutilizables para todo el dashboard

### Objetivos

#### Componentes Blade

- [ ] `dashboard/shared/card.blade.php`
- [ ] `dashboard/shared/button.blade.php`
- [ ] `dashboard/shared/badge.blade.php`
- [ ] `dashboard/shared/alert.blade.php`
- [ ] `dashboard/shared/table.blade.php`
- [ ] `dashboard/shared/form-group.blade.php`
- [ ] `dashboard/shared/modal.blade.php`

#### Componentes Livewire

- [ ] `Modal.php` - Modal reutilizable
- [ ] `ConfirmDelete.php` - Confirmación de eliminación
- [ ] `FlashMessage.php` - Mensajes flash
- [ ] `Pagination.php` - Paginación custom

### Variantes de Componentes

```blade
Button variants: primary, secondary, danger, success
Alert variants: info, success, warning, error
Badge variants: default, primary, success, warning, danger
```
````

### Criterios de Aceptación

- [ ] Todos los componentes creados
- [ ] Componentes con variantes
- [ ] Props documentadas
- [ ] Ejemplos de uso en Storybook/docs
- [ ] Tests de componentes Livewire

### Etiquetas

`fase-4` `dashboard` `components` `livewire` `priority-high`

### Estimación

2 días

````

---

### Issue 4.3: Dashboard Home

```markdown
## Tarea: Implementar Dashboard Home con Widgets

### Descripción
Crear la página principal del dashboard con widgets de estadísticas y actividad reciente

### Objetivos
#### Widgets de Estadísticas
- [ ] Total Productos widget
- [ ] Ventas del Mes widget
- [ ] Stock Bajo widget
- [ ] Clientes Activos widget

#### Gráficos
- [ ] Gráfico de ventas (últimos 30 días)
- [ ] Gráfico de productos más vendidos
- [ ] Configurar Chart.js o similar

#### Actividad Reciente
- [ ] Lista de últimas actividades del tenant
- [ ] Filtros por tipo de actividad
- [ ] Paginación

### Estructura
```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <x-stat-widget icon="box" label="Productos" :value="$totalProducts" />
    <x-stat-widget icon="dollar" label="Ventas" :value="$totalSales" />
    <x-stat-widget icon="alert" label="Stock Bajo" :value="$lowStock" />
    <x-stat-widget icon="users" label="Clientes" :value="$totalCustomers" />
</div>
````

### Criterios de Aceptación

- [ ] Widgets mostrando datos reales
- [ ] Gráficos interactivos
- [ ] Actividad reciente actualizada
- [ ] Responsive en todos los dispositivos
- [ ] Performance optimizado

### Etiquetas

`fase-4` `dashboard` `widgets` `charts` `priority-medium`

### Estimación

2 días

````

---

## FASE 5: Módulo Inventario

### Issue 5.1: Estructura Módulo Inventario

```markdown
## Tarea: Crear Estructura Clean Architecture del Módulo Inventario

### Descripción
Implementar la estructura completa del módulo Inventario siguiendo Clean Architecture

### Objetivos
- [ ] Crear estructura de directorios del módulo
- [ ] Configurar namespace del módulo
- [ ] Crear Service Provider del módulo
- [ ] Registrar rutas del módulo
- [ ] Documentar estructura

### Estructura
````

app/Modules/Inventory/
├── Domain/
│ ├── Models/
│ ├── ValueObjects/
│ ├── Repositories/
│ ├── Events/
│ └── Services/
├── Application/
│ ├── UseCases/
│ └── DTOs/
├── Infrastructure/
│ └── Persistence/
│ └── Eloquent/
└── Http/
├── Controllers/
├── Requests/
└── Livewire/

````

### Service Provider
```php
class InventoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind repositories
        $this->app->bind(
            ProductRepositoryInterface::class,
            EloquentProductRepository::class
        );
    }

    public function boot()
    {
        // Load routes, views, migrations
    }
}
````

### Criterios de Aceptación

- [ ] Estructura de directorios creada
- [ ] Service Provider registrado
- [ ] Namespaces configurados correctamente
- [ ] Autoload funcionando
- [ ] Documentación de módulo creada

### Etiquetas

`fase-5` `inventory` `clean-architecture` `module` `priority-high`

### Estimación

1 día

````

---

### Issue 5.2: Migraciones del Módulo Inventario

```markdown
## Tarea: Crear Migraciones del Módulo Inventario

### Descripción
Crear todas las migraciones necesarias para el módulo de inventario según diseño en `/docs/baseDeDatos.md`

### Objetivos
Crear migraciones para las siguientes tablas:
- [ ] `categories` - Categorías de productos
- [ ] `brands` - Marcas
- [ ] `products` - Productos principales
- [ ] `product_variants` - Variantes de productos
- [ ] `warehouses` - Almacenes
- [ ] `stock_locations` - Ubicaciones en almacén
- [ ] `stock_movements` - Movimientos de inventario
- [ ] `stock_transfers` - Transferencias entre almacenes
- [ ] `stock_transfer_items` - Items de transferencia
- [ ] `suppliers` - Proveedores
- [ ] `product_supplier` - Relación productos-proveedores (pivot)

### Consideraciones
- Todas las migraciones deben ser para tenant database
- Incluir índices para optimización
- Incluir foreign keys con cascadas apropiadas
- Soporte para soft deletes donde corresponda

### Criterios de Aceptación
- [ ] Todas las migraciones creadas
- [ ] Migraciones ejecutándose sin errores
- [ ] Índices creados correctamente
- [ ] Foreign keys con cascadas apropiadas
- [ ] Rollback funcionando correctamente

### Referencias
- `/docs/baseDeDatos.md` - Sección "Módulo Inventario"

### Etiquetas
`fase-5` `inventory` `database` `migrations` `priority-high`

### Estimación
2 días
````

---

### Issue 5.3: Domain Layer - Value Objects y Events

````markdown
## Tarea: Implementar Domain Layer del Módulo Inventario

### Descripción

Crear Value Objects, Domain Events y Domain Services para el módulo de inventario

### Objetivos

#### Value Objects

- [ ] `SKU.php` - Código único del producto
- [ ] `Barcode.php` - Código de barras
- [ ] `StockQuantity.php` - Cantidad de stock
- [ ] `ProductPrice.php` - Precio del producto

#### Domain Events

- [ ] `ProductCreated` - Producto creado
- [ ] `ProductUpdated` - Producto actualizado
- [ ] `ProductDeleted` - Producto eliminado
- [ ] `StockAdjusted` - Stock ajustado
- [ ] `LowStockDetected` - Stock bajo detectado
- [ ] `StockTransferInitiated` - Transferencia iniciada

#### Domain Services

- [ ] `StockCalculationService` - Cálculos de stock
- [ ] `PriceCalculationService` - Cálculos de precios
- [ ] `ReorderPointService` - Punto de reorden

#### Repository Interfaces

- [ ] `ProductRepositoryInterface`
- [ ] `CategoryRepositoryInterface`
- [ ] `StockRepositoryInterface`

### Ejemplo Value Object

```php
final class SKU
{
    private string $value;

    public function __construct(string $value)
    {
        if (!preg_match('/^[A-Z0-9\-]+$/', strtoupper($value))) {
            throw new InvalidSKUException();
        }
        $this->value = strtoupper($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
```
````

### Criterios de Aceptación

- [ ] Todos los Value Objects con validación
- [ ] Todos los Events definidos
- [ ] Domain Services con lógica de negocio
- [ ] Repository Interfaces definidas
- [ ] Tests unitarios para todos los componentes

### Etiquetas

`fase-5` `inventory` `domain` `clean-architecture` `priority-high`

### Estimación

3 días

````

---

### Issue 5.4: Application Layer - Use Cases y DTOs

```markdown
## Tarea: Implementar Application Layer del Módulo Inventario

### Descripción
Crear Use Cases y DTOs para orquestar la lógica del módulo de inventario

### Objetivos
#### Use Cases - Productos
- [ ] `CreateProductUseCase`
- [ ] `UpdateProductUseCase`
- [ ] `DeleteProductUseCase`
- [ ] `GetProductUseCase`
- [ ] `ListProductsUseCase`

#### Use Cases - Categorías
- [ ] `CreateCategoryUseCase`
- [ ] `UpdateCategoryUseCase`
- [ ] `DeleteCategoryUseCase`

#### Use Cases - Stock
- [ ] `AdjustStockUseCase`
- [ ] `TransferStockUseCase`
- [ ] `CheckLowStockUseCase`

#### DTOs
- [ ] `ProductDTO`
- [ ] `CategoryDTO`
- [ ] `StockMovementDTO`
- [ ] `StockTransferDTO`

### Ejemplo Use Case
```php
class CreateProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    public function execute(array $data): ProductDTO
    {
        $product = Product::create($data);
        $this->repository->save($product);

        event(new ProductCreated($product));

        return ProductDTO::fromModel($product);
    }
}
````

### Criterios de Aceptación

- [ ] Todos los Use Cases implementados
- [ ] Todos los DTOs creados
- [ ] Use Cases sin lógica de negocio (solo orquestación)
- [ ] Events disparados correctamente
- [ ] Tests de Use Cases

### Etiquetas

`fase-5` `inventory` `application` `use-cases` `priority-high`

### Estimación

3 días

````

---

### Issue 5.5: CRUD Productos con Livewire

```markdown
## Tarea: Implementar CRUD de Productos con Livewire

### Descripción
Crear interfaz completa para gestión de productos usando Livewire

### Objetivos
#### Controller
- [ ] `ProductController` con métodos REST

#### Vistas Blade
- [ ] `inventory/products/index.blade.php` - Listado
- [ ] `inventory/products/create.blade.php` - Crear
- [ ] `inventory/products/edit.blade.php` - Editar
- [ ] `inventory/products/show.blade.php` - Detalle

#### Componentes Livewire
- [ ] `ProductTable.php` - Tabla con búsqueda y filtros
- [ ] `ProductForm.php` - Formulario de producto
- [ ] `ProductVariantManager.php` - Gestión de variantes
- [ ] `StockAdjuster.php` - Ajuste de stock

#### Funcionalidades
- [ ] Búsqueda en tiempo real
- [ ] Filtros (categoría, marca, estado)
- [ ] Ordenamiento de columnas
- [ ] Paginación
- [ ] Validación en tiempo real
- [ ] Upload de imágenes
- [ ] Gestión de variantes
- [ ] Ajuste de stock rápido

### Rutas
```php
Route::prefix('inventory')->middleware(['tenant'])->group(function () {
    Route::resource('products', ProductController::class);
});
````

### Criterios de Aceptación

- [ ] CRUD completo funcionando
- [ ] Búsqueda y filtros operativos
- [ ] Upload de imágenes funcionando
- [ ] Validación en tiempo real
- [ ] UI responsive y profesional
- [ ] Tests de Feature

### Etiquetas

`fase-5` `inventory` `products` `livewire` `crud` `priority-high`

### Estimación

4 días

```

---

## Notas para Creación de Issues

### Template General
Cada issue debe incluir:
1. **Título claro**: `[Fase X.Y] Descripción breve`
2. **Descripción**: Qué se va a hacer y por qué
3. **Objetivos**: Lista de tareas específicas
4. **Criterios de Aceptación**: Cómo sabemos que está completo
5. **Referencias**: Links a documentación relevante
6. **Etiquetas**: Para organización y filtrado
7. **Estimación**: Tiempo estimado de desarrollo

### Etiquetas Recomendadas
- Por fase: `fase-1`, `fase-2`, `fase-3`, etc.
- Por área: `frontend`, `backend`, `database`, `testing`
- Por prioridad: `priority-high`, `priority-medium`, `priority-low`
- Por tecnología: `livewire`, `tailwind`, `clean-architecture`
- Por módulo: `inventory`, `sales`, `crm`, `accounting`

### Relaciones entre Issues
- Usar "Depends on #XX" para dependencias
- Usar "Blocks #XX" cuando bloquea otro issue
- Usar "Related to #XX" para relacionados

---

**Última Actualización**: 2026-02-09
```
