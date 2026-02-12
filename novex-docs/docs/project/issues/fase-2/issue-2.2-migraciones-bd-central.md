---
title: '[Fase 2.2] Crear Migraciones Base de Datos Central'
labels: fase-2, database, migrations, priority-high
assignees:
milestone: Fase 2 - Auth + Multi-Tenancy
---

## Tarea: Crear Migraciones Base de Datos Central (Landlord)

### Descripción

Crear migraciones y modelos para la base de datos central (landlord) que gestiona tenants, usuarios globales, planes y suscripciones

### Objetivos

- [ ] Crear migración `tenants` table (extender stancl)
- [ ] Crear migración `domains` table (extender stancl)
- [ ] Crear migración `users` central table
- [ ] Crear migración `plans` table
- [ ] Crear migración `subscriptions` table
- [ ] Crear modelos Eloquent
- [ ] Crear factories para testing
- [ ] Crear seeders para datos de prueba

### Migraciones a Crear

#### 1. Extender Tenants Table

`database/migrations/xxxx_extend_tenants_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('admin_email')->after('name');
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->enum('status', ['active', 'suspended', 'trial', 'cancelled'])
                  ->default('trial');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->integer('max_users')->default(5);
            $table->integer('max_storage_mb')->default(1000);
            $table->json('settings')->nullable();
        });
    }

    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'name', 'admin_email', 'phone', 'plan_id',
                'status', 'trial_ends_at', 'subscription_ends_at',
                'max_users', 'max_storage_mb', 'settings'
            ]);
        });
    }
};
```

#### 2. Users Table (Central)

`database/migrations/xxxx_create_central_users_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_global_admin')->default(false);
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('tenant_id')
                  ->references('id')
                  ->on('tenants')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
```

#### 3. Plans Table

`database/migrations/xxxx_create_plans_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price_monthly', 10, 2)->nullable();
            $table->decimal('price_yearly', 10, 2)->nullable();
            $table->integer('max_users')->nullable();
            $table->integer('max_storage_mb')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plans');
    }
};
```

#### 4. Subscriptions Table

`database/migrations/xxxx_create_subscriptions_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->unsignedBigInteger('plan_id');
            $table->enum('status', ['active', 'cancelled', 'expired', 'suspended'])
                  ->default('active');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')
                  ->references('id')
                  ->on('tenants')
                  ->onDelete('cascade');
            $table->foreign('plan_id')
                  ->references('id')
                  ->on('plans');
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};
```

### Modelos a Crear

#### `app/Models/Plan.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name', 'slug', 'description',
        'price_monthly', 'price_yearly',
        'max_users', 'max_storage_mb',
        'features', 'is_active'
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
    ];

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
```

#### `app/Models/Subscription.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'tenant_id', 'plan_id', 'status',
        'starts_at', 'ends_at', 'cancelled_at'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active'
            && $this->starts_at <= now()
            && ($this->ends_at === null || $this->ends_at >= now());
    }
}
```

### Seeders

#### `database/seeders/PlanSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run()
    {
        Plan::create([
            'name' => 'Basic',
            'slug' => 'basic',
            'description' => 'Plan básico para pequeñas empresas',
            'price_monthly' => 29.99,
            'price_yearly' => 299.99,
            'max_users' => 5,
            'max_storage_mb' => 1000,
            'features' => [
                'Inventario básico',
                'Ventas',
                'Reportes básicos'
            ],
            'is_active' => true,
        ]);

        Plan::create([
            'name' => 'Pro',
            'slug' => 'pro',
            'description' => 'Plan profesional con todas las funciones',
            'price_monthly' => 79.99,
            'price_yearly' => 799.99,
            'max_users' => null, // ilimitado
            'max_storage_mb' => 10000,
            'features' => [
                'Todo de Basic',
                'CRM avanzado',
                'Contabilidad',
                'API access',
                'Soporte prioritario'
            ],
            'is_active' => true,
        ]);

        Plan::create([
            'name' => 'Enterprise',
            'slug' => 'enterprise',
            'description' => 'Plan empresarial personalizado',
            'price_monthly' => null,
            'price_yearly' => null,
            'max_users' => null,
            'max_storage_mb' => null,
            'features' => [
                'Todo de Pro',
                'Soporte dedicado',
                'Customización',
                'Onboarding',
                'SLA'
            ],
            'is_active' => true,
        ]);
    }
}
```

### Comandos de Ejecución

```bash
# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders
php artisan db:seed --class=PlanSeeder

# Verificar tablas
php artisan db:table tenants
php artisan db:table plans
```

### Criterios de Aceptación

- [ ] Todas las migraciones creadas y ejecutadas sin errores
- [ ] Tablas creadas en la BD central
- [ ] Modelos Eloquent funcionando
- [ ] Relaciones entre modelos correctas
- [ ] Seeders creando datos de prueba
- [ ] Factories creadas para testing
- [ ] Tests de modelos pasando

### Testing

```php
public function test_can_create_plan()
{
    $plan = Plan::factory()->create();

    $this->assertDatabaseHas('plans', [
        'slug' => $plan->slug
    ]);
}

public function test_tenant_can_have_subscription()
{
    $tenant = Tenant::create(['id' => 'test']);
    $plan = Plan::factory()->create();

    $subscription = Subscription::create([
        'tenant_id' => $tenant->id,
        'plan_id' => $plan->id,
        'starts_at' => now(),
        'status' => 'active',
    ]);

    $this->assertTrue($subscription->isActive());
}
```

### Referencias

- `/docs/baseDeDatos.md` - Sección "BD CENTRAL"
- `/docs/PROJECT_PHASES.md`

### Estimación

**2 días**

### Dependencias

- Issue 2.1 (Multi-Tenancy) debe estar completada
- Issue 1.2 (Configuración BD) debe estar completada

### Notas

Estas migraciones son para la BD central (landlord). Las migraciones de tenant (inventario, etc.) se crearán en fases posteriores.
