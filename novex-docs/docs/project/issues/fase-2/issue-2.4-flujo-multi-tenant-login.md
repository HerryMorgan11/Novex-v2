---
title: '[Fase 2.4] Implementar Flujo Multi-Tenant Login'
labels: fase-2, multi-tenancy, authentication, priority-high, complex
assignees:
milestone: Fase 2 - Auth + Multi-Tenancy
---

## Tarea: Implementar Flujo de Login Multi-Tenant

### Descripción

Implementar el flujo completo de autenticación tenant-aware con detección automática de tenant, sesiones aisladas y redirección

### Objetivos

- [ ] Crear middleware `InitializeTenancy`
- [ ] Implementar lógica de detección de tenant por subdominio
- [ ] Crear ruta `/auth/consume` para tokens
- [ ] Configurar sesiones por tenant
- [ ] Configurar cache por tenant
- [ ] Configurar storage por tenant
- [ ] Implementar redirección post-login tenant-aware

### Flujo de Login Esperado

```
1. Usuario accede a www.miapp.com/login
2. Usuario ingresa credenciales
3. Sistema valida y detecta tenant del usuario
4. Sistema genera token temporal
5. Redirige a \{tenant\}.miapp.com/auth/consume?token=xxx
6. Tenant inicializa contexto (BD, cache, sesión)
7. Usuario accede al dashboard del tenant
```

### Implementación

#### 1. Middleware de Tenancy

`app/Http/Middleware/InitializeTenancy.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

class InitializeTenancy extends InitializeTenancyByDomain
{
    public function handle(Request $request, Closure $next)
    {
        $this->initializeTenancy($request);

        return $next($request);
    }

    protected function initializeTenancy(Request $request)
    {
        $domain = $request->getHost();

        // Si es dominio central, no inicializar tenancy
        if (in_array($domain, config('tenancy.central_domains'))) {
            return;
        }

        // Buscar tenant por dominio
        $tenant = \App\Models\Tenant::whereHas('domains', function ($query) use ($domain) {
            $query->where('domain', $domain);
        })->first();

        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        // Inicializar contexto del tenant
        tenancy()->initialize($tenant);
    }
}
```

#### 2. Token System para Login

`app/Services/TenantLoginService.php`

```php
<?php

namespace App\Services;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TenantLoginService
{
    public function generateLoginToken(User $user): string
    {
        $token = Str::random(64);

        // Guardar token en cache por 5 minutos
        Cache::put("login_token:{$token}", [
            'user_id' => $user->id,
            'tenant_id' => $user->tenant_id,
        ], now()->addMinutes(5));

        return $token;
    }

    public function consumeLoginToken(string $token): ?User
    {
        $data = Cache::pull("login_token:{$token}");

        if (!$data) {
            return null;
        }

        return User::find($data['user_id']);
    }

    public function getTenantLoginUrl(User $user): string
    {
        $tenant = Tenant::find($user->tenant_id);
        $domain = $tenant->domains()->first();
        $token = $this->generateLoginToken($user);

        return "http://{$domain->domain}/auth/consume?token={$token}";
    }
}
```

#### 3. AuthController Actualizado

Modificar `app/Http/Controllers/AuthController.php`:

```php
public function login(LoginRequest $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $user = Auth::user();

        if ($user->tenant_id) {
            $loginService = new TenantLoginService();
            $loginUrl = $loginService->getTenantLoginUrl($user);

            // Logout de la sesión central
            Auth::logout();

            // Redirigir al tenant con token
            return redirect()->away($loginUrl);
        }

        $request->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    return back()->withErrors([
        'email' => 'Las credenciales no coinciden.',
    ])->onlyInput('email');
}
```

#### 4. Consume Token Controller

`app/Http/Controllers/Tenant/AuthConsumeController.php`

```php
<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\TenantLoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthConsumeController extends Controller
{
    public function __invoke(Request $request, TenantLoginService $loginService)
    {
        $token = $request->get('token');

        if (!$token) {
            return redirect('/login')->withErrors([
                'token' => 'Token inválido o expirado'
            ]);
        }

        $user = $loginService->consumeLoginToken($token);

        if (!$user) {
            return redirect('/login')->withErrors([
                'token' => 'Token inválido o expirado'
            ]);
        }

        // Verificar que el usuario pertenece a este tenant
        if ($user->tenant_id !== tenant('id')) {
            abort(403, 'Acceso no autorizado');
        }

        // Login en el contexto del tenant
        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/dashboard');
    }
}
```

#### 5. Rutas

`routes/tenant.php`

```php
<?php

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Tenant\AuthConsumeController;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    // Consumir token de login
    Route::get('/auth/consume', AuthConsumeController::class)->name('auth.consume');

    // Rutas protegidas del tenant
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard.home');
        })->name('dashboard');
    });
});
```

#### 6. Configurar Sesiones por Tenant

`config/tenancy.php`

```php
'features' => [
    // Aislamiento de sesiones por tenant
    Stancl\Tenancy\Features\TenantConfig::class,
],

'tenant_config' => [
    'session' => [
        'domain' => null, // Se establece dinámicamente
    ],
],
```

#### 7. Configurar Cache por Tenant

`config/cache.php`

```php
'prefix' => env('CACHE_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_cache_') . (tenancy()->initialized ? tenant('id') . '_' : ''),
```

### Configuración de Subdominios Local

Para testing local, agregar a `/etc/hosts`:

```
127.0.0.1 test.localhost
127.0.0.1 demo.localhost
127.0.0.1 acme.localhost
```

### Criterios de Aceptación

- [ ] Middleware tenant funcionando correctamente
- [ ] Detección de tenant por subdominio operativa
- [ ] Sistema de tokens funcionando (creación y consumo)
- [ ] Sesiones aisladas por tenant
- [ ] Cache aislado por tenant
- [ ] Storage aislado por tenant
- [ ] Redirección automática post-login funcionando
- [ ] Tests de flujo completo pasando
- [ ] Manejo de errores (tenant no encontrado, token inválido)

### Testing

`tests/Feature/TenantLoginFlowTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tenant;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TenantLoginFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_redirected_to_tenant_with_token()
    {
        $tenant = Tenant::factory()->create(['id' => 'test']);
        $tenant->domains()->create(['domain' => 'test.localhost']);

        $user = User::factory()->create([
            'tenant_id' => 'test',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $this->assertTrue(str_contains($response->headers->get('Location'), 'test.localhost'));
        $this->assertTrue(str_contains($response->headers->get('Location'), 'token='));
    }

    public function test_token_can_be_consumed_in_tenant_context()
    {
        $tenant = Tenant::factory()->create(['id' => 'test']);
        $tenant->domains()->create(['domain' => 'test.localhost']);

        tenancy()->initialize($tenant);

        $user = User::factory()->create([
            'tenant_id' => 'test',
        ]);

        $service = new \App\Services\TenantLoginService();
        $token = $service->generateLoginToken($user);

        $response = $this->get("/auth/consume?token={$token}");

        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
    }
}
```

### Referencias

- `/docs/landingPublica.md` - Sección "Workflow"
- https://tenancyforlaravel.com/docs/authentication
- `/docs/arquitectura.md`

### Estimación

**2 días**

### Dependencias

- Issue 2.1 (Multi-Tenancy) completada
- Issue 2.3 (Sistema de Auth) completada

### Notas

⚠️ **IMPORTANTE**: Este es el flujo más complejo del sistema de auth. Asegúrate de probar exhaustivamente con múltiples tenants.

### Troubleshooting

**Problema**: Sesiones compartidas entre tenants
**Solución**: Verificar que `session.domain` esté configurado correctamente por tenant

**Problema**: Token expira muy rápido
**Solución**: Ajustar tiempo en `generateLoginToken` (actualmente 5 minutos)

**Problema**: Redirección infinita
**Solución**: Verificar que dominios centrales estén correctamente configurados en `config/tenancy.php`
