---
title: "[Fase 2.1] Instalar y Configurar Multi-Tenancy"
labels: fase-2, multi-tenancy, priority-high, critical
assignees: 
milestone: Fase 2 - Auth + Multi-Tenancy
---

## 🏢 Tarea: Instalar y Configurar Multi-Tenancy

### Descripción
Instalar y configurar el paquete `stancl/tenancy` para soportar multi-tenancy con database por tenant

### Objetivos
- [ ] Instalar paquete `stancl/tenancy`
- [ ] Ejecutar comando de instalación
- [ ] Configurar `config/tenancy.php`
- [ ] Ejecutar migraciones de tenancy
- [ ] Configurar identificación por subdominio
- [ ] Configurar middleware de tenancy
- [ ] Crear tenant de prueba

### Pasos de Instalación

#### 1. Instalar el Paquete
```bash
composer require stancl/tenancy
```

#### 2. Publicar Configuración
```bash
php artisan tenancy:install
```

Este comando creará:
- `config/tenancy.php`
- Migraciones de tenancy
- Modelo Tenant
- Routes tenant

#### 3. Ejecutar Migraciones
```bash
php artisan migrate
```

Esto creará las tablas:
- `tenants`
- `domains`

#### 4. Configurar `config/tenancy.php`

```php
<?php

return [
    'tenant_model' => App\Models\Tenant::class,
    
    'tenant_route_namespace' => 'App\Http\Controllers\Tenant',
    
    'home_url' => env('APP_URL'),
    
    // Identificación por subdominio
    'identification_middleware' => [
        Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
    ],
    
    'database' => [
        'central_connection' => 'mysql',
        'template_tenant_connection' => 'tenant',
    ],
];
```

#### 5. Configurar Variables de Entorno

Agregar a `.env`:
```env
# Multi-Tenancy
TENANCY_CENTRAL_DOMAINS=localhost,127.0.0.1
```

#### 6. Crear Tenant de Prueba

```bash
php artisan tinker
```

```php
$tenant = Tenant::create([
    'id' => 'test',
]);

$tenant->domains()->create([
    'domain' => 'test.localhost',
]);
```

### Archivos a Modificar

#### `routes/tenant.php`
Crear o modificar:
```php
<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return 'This is your tenant: ' . tenant('id');
    });
});
```

#### `app/Models/Tenant.php`
```php
<?php

namespace App\Models;

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
    ];
}
```

### Comandos de Prueba

```bash
# Listar tenants
php artisan tenants:list

# Crear tenant
php artisan tenants:create test

# Ejecutar comando en contexto tenant
php artisan tenants:run migrate --tenants=test
```

### Criterios de Aceptación
- [ ] Paquete `stancl/tenancy` instalado
- [ ] Migraciones de tenancy ejecutadas exitosamente
- [ ] Tablas `tenants` y `domains` creadas
- [ ] Configuración de subdominios funcionando
- [ ] Middleware registrado correctamente
- [ ] Tenant de prueba creado
- [ ] Rutas tenant funcionando
- [ ] Tests básicos de tenancy pasando

### Testing

Crear test `tests/Feature/TenancyTest.php`:
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;

class TenancyTest extends TestCase
{
    public function test_can_create_tenant()
    {
        $tenant = Tenant::create(['id' => 'test-tenant']);
        
        $this->assertDatabaseHas('tenants', [
            'id' => 'test-tenant'
        ]);
    }
    
    public function test_tenant_has_database()
    {
        $tenant = Tenant::create(['id' => 'test-db']);
        
        $this->assertNotNull($tenant->database()->getName());
    }
}
```

### Referencias
- https://tenancyforlaravel.com/docs/
- `/docs/arquitectura.md` - Sección Multi-Tenancy
- `/docs/PROJECT_PHASES.md`

### Estimación
**2 días**

### Dependencias
- Issue 1.2 (Configuración BD) debe estar completada

### Notas
⚠️ **IMPORTANTE**: Esta es la base fundamental de todo el sistema. Asegúrate de entender bien cómo funciona el multi-tenancy antes de continuar con otras tareas.

### Troubleshooting

**Problema**: Error "Table tenants doesn't exist"
**Solución**: Ejecutar `php artisan migrate`

**Problema**: Subdominios no funcionan en local
**Solución**: Agregar entradas en `/etc/hosts`:
```
127.0.0.1 test.localhost
127.0.0.1 demo.localhost
```
