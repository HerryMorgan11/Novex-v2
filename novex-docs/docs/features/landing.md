# Arquitectura: Landing Pública + Dashboard Privado

## Índice

1. [Visión General](https://file+.vscode-resource.vscode-cdn.net/Users/davidjacobocastillo/Documents/Programacion/LANDING_DASHBOARD_ARCHITECTURE.md#visi%C3%B3n-general)
2. [Arquitectura de Dominios](https://file+.vscode-resource.vscode-cdn.net/Users/davidjacobocastillo/Documents/Programacion/LANDING_DASHBOARD_ARCHITECTURE.md#arquitectura-de-dominios)
3. [Estructura de Directorios](https://file+.vscode-resource.vscode-cdn.net/Users/davidjacobocastillo/Documents/Programacion/LANDING_DASHBOARD_ARCHITECTURE.md#estructura-de-directorios)
4. [Sistema de Autenticación](https://file+.vscode-resource.vscode-cdn.net/Users/davidjacobocastillo/Documents/Programacion/LANDING_DASHBOARD_ARCHITECTURE.md#sistema-de-autenticaci%C3%B3n)
5. [Rutas y Middleware](https://file+.vscode-resource.vscode-cdn.net/Users/davidjacobocastillo/Documents/Programacion/LANDING_DASHBOARD_ARCHITECTURE.md#rutas-y-middleware)
6. [Conexión via Endpoints](https://file+.vscode-resource.vscode-cdn.net/Users/davidjacobocastillo/Documents/Programacion/LANDING_DASHBOARD_ARCHITECTURE.md#conexi%C3%B3n-via-endpoints)
7. [Flujos Completos](https://file+.vscode-resource.vscode-cdn.net/Users/davidjacobocastillo/Documents/Programacion/LANDING_DASHBOARD_ARCHITECTURE.md#flujos-completos)
8. [Implementación Paso a Paso](https://file+.vscode-resource.vscode-cdn.net/Users/davidjacobocastillo/Documents/Programacion/LANDING_DASHBOARD_ARCHITECTURE.md#implementaci%C3%B3n-paso-a-paso)
9. [Best Practices](https://file+.vscode-resource.vscode-cdn.net/Users/davidjacobocastillo/Documents/Programacion/LANDING_DASHBOARD_ARCHITECTURE.md#best-practices)

---

## ** Visión General**

### **Concepto**

Nuestra aplicación tiene **dos contextos claramente separados**:

```
┌─────────────────────────────────────────────────────────────┐
│                    LANDING PAGE (Pública)                    │
│  • Marketing y presentación del producto                     │
│  • Pricing y planes                                          │
│  • Registro de nuevos tenants                                │
│  • Login de usuarios existentes                              │
│  • Sin autenticación requerida                               │
│  Dominio: www.miapp.com                                      │
└─────────────────────────────────────────────────────────────┘

                            ↕ Endpoints de Autenticación

┌─────────────────────────────────────────────────────────────┐
│                   DASHBOARD (Privado)                        │
│  • Módulos del ERP (Inventario, Ventas, CRM, etc.)          │
│  • Datos específicos del tenant                              │
│  • Requiere autenticación                                    │
│  • Multi-tenancy activo                                      │
│  Dominio: \{tenant\}.miapp.com o miapp.com/\{tenant\}           │
└─────────────────────────────────────────────────────────────┘

```

### **Características Clave**

✅ **Landing pública**: Accesible sin autenticación

✅ **Dashboard privado**: Requiere login

✅ **Redirección inteligente**: Si estás autenticado en landing → dashboard

✅ **Sesión compartida**: Mismo sistema de autenticación

✅ **Endpoints REST**: Comunicación entre contextos

✅ **Multi-tenancy**: Solo en dashboard

**_Workflow_**

1. Usuario hace click en Login en la landing
2. Redirección a: [https://auth.miapp.com/login](https://auth.miapp.com/login)
3. Usuario ingresa email + password
4. ERP valida credenciales (SIN crear sesión tenant)
5. ERP detecta a qué tenant pertenece
6. ERP genera redirección segura
7. Usuario es redirigido a:
   https://{tenant}.miapp.com/auth/consume
8. El tenant:
    - Inicializa tenancy
    - Crea sesión Laravel
9. Usuario entra al dashboard

---

## **🌐 Arquitectura de Dominios**

### **Opción 1: Subdominio por Tenant (Recomendada)**

```
www.miapp.com           → Landing pública
acme.miapp.com          → Dashboard de Acme Corp
techcorp.miapp.com      → Dashboard de TechCorp
innovate.miapp.com      → Dashboard de Innovate Inc

```

**Ventajas:**

- ✅ Separación clara entre landing y tenants
- ✅ Fácil identificar el tenant
- ✅ Mejor para branding (cada cliente su subdominio)
- ✅ Certificados SSL wildcard

### **Opción 2: Path-based Tenancy**

```
www.miapp.com           → Landing pública
www.miapp.com/acme      → Dashboard de Acme Corp
www.miapp.com/techcorp  → Dashboard de TechCorp

```

**Ventajas:**

- ✅ Un solo dominio
- ✅ Más simple de configurar

### **Nuestra Implementación (Opción 1)**

```
┌────────────────────────────────────────────────────────┐
│                    www.miapp.com                        │
│                  (Landlord Context)                     │
│  ┌──────────────────────────────────────────────┐      │
│  │  Landing Routes                               │      │
│  │  • GET  /                → home               │      │
│  │  • GET  /pricing         → pricing            │      │
│  │  • GET  /features        → features           │      │
│  │  • GET  /login           → login form         │      │
│  │  • POST /login           → authenticate       │      │
│  │  • GET  /register        → register form      │      │
│  │  • POST /register        → create tenant      │      │
│  └──────────────────────────────────────────────┘      │
└────────────────────────────────────────────────────────┘

                            ↓ Redirect after login

┌────────────────────────────────────────────────────────┐
│                 \{tenant\}.miapp.com                      │
│                  (Tenant Context)                       │
│  ┌──────────────────────────────────────────────┐      │
│  │  Dashboard Routes (Protected)                 │      │
│  │  • GET  /dashboard       → dashboard          │      │
│  │  • GET  /inventory       → inventory module   │      │
│  │  • GET  /sales           → sales module       │      │
│  │  • GET  /crm             → crm module         │      │
│  │  • POST /logout          → destroy session    │      │
│  └──────────────────────────────────────────────┘      │
└────────────────────────────────────────────────────────┘

```

---

## **📁 Estructura de Directorios**

### **Estructura Propuesta**

```
app/
├── Core/                              # Clean Architecture (ya existente)
│   ├── Domain/
│   ├── Application/
│   └── Infrastructure/
│
├── Contexts/                          # Nuevos contextos separados
│   ├── Landing/                       # Contexto público (Landlord)
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   ├── HomeController.php
│   │   │   │   ├── PricingController.php
│   │   │   │   ├── AuthController.php
│   │   │   │   └── RegisterController.php
│   │   │   ├── Requests/
│   │   │   │   ├── LoginRequest.php
│   │   │   │   └── RegisterTenantRequest.php
│   │   │   └── Middleware/
│   │   │       └── RedirectIfAuthenticated.php
│   │   ├── Resources/
│   │   │   └── Views/
│   │   │       ├── layouts/
│   │   │       │   └── landing.blade.php
│   │   │       ├── home.blade.php
│   │   │       ├── pricing.blade.php
│   │   │       ├── login.blade.php
│   │   │       └── register.blade.php
│   │   ├── Routes/
│   │   │   └── web.php
│   │   └── Services/
│   │       ├── TenantRegistrationService.php
│   │       └── AuthenticationService.php
│   │
│   └── Dashboard/                     # Contexto privado (Tenant)
│       ├── Http/
│       │   ├── Controllers/
│       │   │   ├── DashboardController.php
│       │   │   └── ProfileController.php
│       │   ├── Requests/
│       │   └── Middleware/
│       │       ├── EnsureAuthenticated.php
│       │       └── InitializeTenancy.php
│       ├── Resources/
│       │   └── Views/
│       │       ├── layouts/
│       │       │   └── dashboard.blade.php
│       │       └── dashboard.blade.php
│       └── Routes/
│           └── web.php
│
├── Modules/                           # Módulos del ERP (Tenant context)
│   ├── Inventory/
│   ├── Sales/
│   ├── CRM/
│   └── Invoicing/
│
├── Tenancy/                           # Multi-tenancy (ya existente)
│   ├── Models/
│   │   ├── Tenant.php
│   │   └── User.php
│   └── Services/
│       └── TenantManager.php
│
└── Http/
    └── Middleware/
        ├── Authenticate.php
        └── RedirectIfAuthenticated.php

routes/
├── web.php                            # Rutas principales
├── landing.php                        # Rutas de landing (landlord)
├── dashboard.php                      # Rutas de dashboard (tenant)
└── api.php                            # API endpoints

resources/
└── views/
    ├── landing/                       # Vistas de landing
    │   ├── layouts/
    │   ├── home.blade.php
    │   ├── pricing.blade.php
    │   └── login.blade.php
    └── dashboard/                     # Vistas de dashboard
        ├── layouts/
        └── dashboard.blade.php

config/
├── tenancy.php                        # Configuración de tenancy
└── domains.php                        # Configuración de dominios

.env
LANDLORD_DOMAIN=www.miapp.com          # Dominio de la landing
TENANT_DOMAIN_SUFFIX=.miapp.com        # Sufijo para tenants
APP_URL=https://miapp.com

```

---

## ** Sistema de Autenticación**

### **Guards de Laravel**

Laravel permite definir múltiples **guards** para diferentes contextos.

```php
// config/auth.php
return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        // Guard para landing (landlord)
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // Guard para dashboard (tenant)
        'tenant' => [
            'driver' => 'session',
            'provider' => 'tenant_users',
        ],

        // API guard (opcional)
        'api' => [
            'driver' => 'token',
            'provider' => 'users',
            'hash' => false,
        ],
    ],

    'providers' => [
        // Provider para usuarios del landlord
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Tenancy\Models\User::class,
        ],

        // Provider para usuarios del tenant
        'tenant_users' => [
            'driver' => 'eloquent',
            'model' => App\Tenancy\Models\TenantUser::class,
        ],
    ],
];

```

### **Modelos de Usuario**

### **Usuario del Landlord (Central)**

```php
// app/Tenancy/Models/User.php
namespace App\Tenancy\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'landlord'; // Base de datos central

    protected $fillable = [
        'name',
        'email',
        'password',
        'tenant_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function tenant()
{
        return $this->belongsTo(Tenant::class);
    }
}

```

### **Usuario del Tenant**

```php
// app/Tenancy/Models/TenantUser.php
namespace App\Tenancy\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TenantUser extends Authenticatable
{
    use Notifiable;

    protected $connection = 'tenant'; // Base de datos del tenant

    protected $table = 'users'; // Tabla en la BD del tenant

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}

```

---

## ** Rutas y Middleware**

### **Configuración de Rutas**

```php
// routes/web.php (Archivo principal)
<?php

use Illuminate\Support\Facades\Route;

// Cargar rutas según el dominio
if (request()->getHost() === config('app.landlord_domain')) {
    // Estamos en la landing (landlord)
    require __DIR__ . '/landing.php';
} else {
    // Estamos en un tenant
    require __DIR__ . '/dashboard.php';
}

```

### **Rutas de Landing (Públicas)**

```php
// routes/landing.php
<?php

use App\Contexts\Landing\Http\Controllers\HomeController;
use App\Contexts\Landing\Http\Controllers\PricingController;
use App\Contexts\Landing\Http\Controllers\AuthController;
use App\Contexts\Landing\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::get('/', [HomeController::class, 'index'])->name('landing.home');
Route::get('/pricing', [PricingController::class, 'index'])->name('landing.pricing');
Route::get('/features', [HomeController::class, 'features'])->name('landing.features');
Route::get('/about', [HomeController::class, 'about'])->name('landing.about');

// Autenticación (con redirect si ya está autenticado)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
});

// Logout (requiere autenticación)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirección automática al dashboard si está autenticado
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $tenant = $user->tenant;

        // Redirigir al subdominio del tenant
        return redirect()->away('https://' . $tenant->domain . '/dashboard');
    })->name('landing.redirect-to-dashboard');
});

```

### **Rutas de Dashboard (Privadas)**

```php
// routes/dashboard.php
<?php

use App\Contexts\Dashboard\Http\Controllers\DashboardController;
use App\Contexts\Dashboard\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

// Todas las rutas del dashboard requieren:
// 1. Inicializar tenancy
// 2. Estar autenticado
Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'auth:tenant',
])->group(function () {

    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Módulos del ERP
    Route::prefix('inventory')->name('inventory.')->group(function () {
        require __DIR__ . '/../app/Modules/Inventory/Routes/web.php';
    });

    Route::prefix('sales')->name('sales.')->group(function () {
        require __DIR__ . '/../app/Modules/Sales/Routes/web.php';
    });

    Route::prefix('crm')->name('crm.')->group(function () {
        require __DIR__ . '/../app/Modules/CRM/Routes/web.php';
    });

    // Logout
    Route::post('/logout', [DashboardController::class, 'logout'])->name('logout');
});

// Redirección desde raíz al dashboard
Route::middleware([
    InitializeTenancyByDomain::class,
    'auth:tenant',
])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
});

```

### **Middleware Personalizado**

### **RedirectIfAuthenticated**

```php
// app/Contexts/Landing/Http/Middleware/RedirectIfAuthenticated.php
namespace App\Contexts\Landing\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards)
{
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                $tenant = $user->tenant;

                // Redirigir al dashboard del tenant
                if ($tenant) {
                    return redirect()->away('https://' . $tenant->domain);
                }

                return redirect('/dashboard');
            }
        }

        return $next($request);
    }
}

```

### **EnsureAuthenticated**

```php
// app/Contexts/Dashboard/Http/Middleware/EnsureAuthenticated.php
namespace App\Contexts\Dashboard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAuthenticated
{
    public function handle(Request $request, Closure $next)
{
        if (!Auth::guard('tenant')->check()) {
            // Redirigir al login de la landing
            return redirect()->away('https://' . config('app.landlord_domain') . '/login');
        }

        return $next($request);
    }
}

```

---

## ** Conexión via Endpoints**

### **API Endpoints**

```php
// routes/api.php
<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TenantController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Autenticación
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::middleware('auth:api')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/user', [AuthController::class, 'user']);
    });

    // Información del tenant
    Route::get('/tenant/{domain}', [TenantController::class, 'getByDomain']);
    Route::middleware('auth:api')->group(function () {
        Route::get('/tenant', [TenantController::class, 'current']);
    });
});

```

### **Controlador de API de Autenticación**

```php
// app/Http/Controllers/Api/AuthController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Contexts\Landing\Services\AuthenticationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private AuthenticationService $authService
){}

    /**
     * Login via API
     */
    public function login(Request $request): JsonResponse
{
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $result = $this->authService->attemptLogin($credentials);

        if (!$result['success']) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'user' => $result['user'],
            'tenant' => $result['tenant'],
            'redirect_url' => 'https://' . $result['tenant']->domain . '/dashboard'
        ]);
    }

    /**
     * Obtener usuario autenticado
     */
    public function user(Request $request): JsonResponse
{
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    /**
     * Logout via API
     */
    public function logout(Request $request): JsonResponse
{
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful'
        ]);
    }
}

```

### **Servicio de Autenticación**

```php
// app/Contexts/Landing/Services/AuthenticationService.php
namespace App\Contexts\Landing\Services;

use App\Tenancy\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticationService
{
    /**
     * Intentar login
     */
    public function attemptLogin(array $credentials): array
{
        if (!Auth::attempt($credentials)) {
            return ['success' => false];
        }

        $user = Auth::user();
        $tenant = $user->tenant;

        return [
            'success' => true,
            'user' => $user,
            'tenant' => $tenant,
        ];
    }

    /**
     * Verificar si el usuario tiene acceso al tenant
     */
    public function userHasAccessToTenant(User $user, string $tenantDomain): bool
{
        return $user->tenant && $user->tenant->domain === $tenantDomain;
    }

    /**
     * Obtener URL de redirección después del login
     */
    public function getRedirectUrlAfterLogin(User $user): string
{
        $tenant = $user->tenant;

        if (!$tenant) {
            return route('landing.home');
        }

        return 'https://' . $tenant->domain . '/dashboard';
    }
}

```

---

## ** Flujos Completos**

### **Flujo 1: Usuario Nuevo - Registro**

```
1. Usuario visita www.miapp.com
   ↓
2. Hace clic en "Registrarse"
   ↓
3. Llena formulario de registro:
   - Nombre de la empresa
   - Email
   - Password
   - Plan seleccionado
   ↓
4. POST /register → RegisterController
   ↓
5. TenantRegistrationService:
   - Crea el tenant en BD central
   - Crea la base de datos del tenant
   - Ejecuta migraciones del tenant
   - Crea el usuario administrador
   - Asocia usuario con tenant
   ↓
6. Autenticación automática
   ↓
7. Redirección a https://\{tenant\}.miapp.com/dashboard
   ↓
8. InitializeTenancyByDomain detecta el tenant
   ↓
9. Muestra dashboard del tenant

```

**Código del flujo:**

```php
// app/Contexts/Landing/Http/Controllers/RegisterController.php
namespace App\Contexts\Landing\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Contexts\Landing\Services\TenantRegistrationService;
use App\Contexts\Landing\Http\Requests\RegisterTenantRequest;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
    public function __construct(
        private TenantRegistrationService $registrationService
){}

    public function showRegistrationForm()
{
        return view('landing.register');
    }

    public function register(RegisterTenantRequest $request): RedirectResponse
{
        try {
            $result = $this->registrationService->registerNewTenant([
                'tenant_name' => $request->company_name,
                'tenant_domain' => $request->subdomain,
                'user_name' => $request->name,
                'user_email' => $request->email,
                'user_password' => $request->password,
                'plan' => $request->plan,
            ]);

            // Login automático
            auth()->login($result['user']);

            // Redirigir al dashboard del tenant
            $dashboardUrl = 'https://' . $result['tenant']->domain . '/dashboard';

            return redirect()->away($dashboardUrl)
                ->with('success', '¡Bienvenido! Tu cuenta ha sido creada exitosamente.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al crear la cuenta: ' . $e->getMessage());
        }
    }
}

```

```php
// app/Contexts/Landing/Services/TenantRegistrationService.php
namespace App\Contexts\Landing\Services;

use App\Tenancy\Models\Tenant;
use App\Tenancy\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TenantRegistrationService
{
    public function registerNewTenant(array $data): array
{
        return DB::transaction(function () use ($data) {
            // 1. Crear el tenant
            $tenant = Tenant::create([
                'name' => $data['tenant_name'],
                'email' => $data['user_email'],
                'plan' => $data['plan'],
                'status' => true,
            ]);

            // 2. Crear el dominio
            $tenant->domains()->create([
                'domain' => $data['tenant_domain'] . config('tenancy.domain_suffix', '.miapp.com'),
            ]);

            // 3. Crear la base de datos del tenant
            // (stancl/tenancy hace esto automáticamente con eventos)

            // 4. Crear el usuario administrador en la BD central
            $user = User::create([
                'name' => $data['user_name'],
                'email' => $data['user_email'],
                'password' => Hash::make($data['user_password']),
                'tenant_id' => $tenant->id,
            ]);

            // 5. Crear el usuario en la BD del tenant
            tenancy()->initialize($tenant);

            \App\Tenancy\Models\TenantUser::create([
                'name' => $data['user_name'],
                'email' => $data['user_email'],
                'password' => Hash::make($data['user_password']),
                'role' => 'admin',
            ]);

            tenancy()->end();

            return [
                'tenant' => $tenant,
                'user' => $user,
            ];
        });
    }
}

```

### **Flujo 2: Usuario Existente - Login**

```
1. Usuario visita www.miapp.com
   ↓
2. Hace clic en "Iniciar sesión"
   ↓
3. Ingresa email y password
   ↓
4. POST /login → AuthController
   ↓
5. AuthenticationService valida credenciales
   ↓
6. Busca tenant asociado al usuario
   ↓
7. Crea sesión de autenticación
   ↓
8. Redirección a https://\{tenant\}.miapp.com/dashboard
   ↓
9. InitializeTenancyByDomain detecta el tenant
   ↓
10. Middleware auth:tenant valida sesión
    ↓
11. Muestra dashboard del tenant

```

**Código del flujo:**

```php
// app/Contexts/Landing/Http/Controllers/AuthController.php
namespace App\Contexts\Landing\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Contexts\Landing\Services\AuthenticationService;
use App\Contexts\Landing\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        private AuthenticationService $authService
){}

    public function showLoginForm(): View
{
        return view('landing.login');
    }

    public function login(LoginRequest $request): RedirectResponse
{
        $result = $this->authService->attemptLogin([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if (!$result['success']) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Las credenciales no coinciden con nuestros registros.');
        }

        // Regenerar sesión por seguridad
        $request->session()->regenerate();

        // Obtener URL de redirección
        $redirectUrl = $this->authService->getRedirectUrlAfterLogin($result['user']);

        return redirect()->away($redirectUrl)
            ->with('success', '¡Bienvenido de nuevo!');
    }

    public function logout(Request $request): RedirectResponse
{
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing.home')
            ->with('success', 'Sesión cerrada exitosamente.');
    }
}

```

### **Flujo 3: Usuario Autenticado Visita Landing**

```
1. Usuario autenticado visita www.miapp.com
   ↓
2. Middleware RedirectIfAuthenticated detecta sesión activa
   ↓
3. Obtiene el tenant del usuario
   ↓
4. Redirección automática a https://\{tenant\}.miapp.com/dashboard
   ↓
5. Usuario llega directamente al dashboard

```

**Código del flujo:**

```php
// app/Contexts/Landing/Http/Middleware/RedirectIfAuthenticated.php
namespace App\Contexts\Landing\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Manejar request entrante
     */
    public function handle(Request $request, Closure $next, string ...$guards)
{
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Si el usuario tiene un tenant, redirigir a su dashboard
                if ($user->tenant) {
                    $dashboardUrl = 'https://' . $user->tenant->domain . '/dashboard';
                    return redirect()->away($dashboardUrl);
                }

                // Si no tiene tenant, redirigir a una página de error o configuración
                return redirect()->route('landing.setup-tenant');
            }
        }

        return $next($request);
    }
}

```

### **Flujo 4: Acceso No Autorizado al Dashboard**

```
1. Usuario NO autenticado intenta acceder a acme.miapp.com/dashboard
   ↓
2. Middleware InitializeTenancyByDomain detecta el tenant
   ↓
3. Middleware auth:tenant verifica autenticación
   ↓
4. No hay sesión activa
   ↓
5. Redirección a www.miapp.com/login
   ↓
6. Muestra formulario de login

```

---

## ** Implementación Paso a Paso**

### **Paso 1: Configurar .env**

```
# Dominios
APP_URL=https://miapp.com
LANDLORD_DOMAIN=www.miapp.com
TENANT_DOMAIN_SUFFIX=.miapp.com

# Base de datos central (landlord)
DB_CONNECTION=landlord
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=erp_landlord
DB_USERNAME=root
DB_PASSWORD=

# Sesiones
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_DOMAIN=.miapp.com

# Cache
CACHE_DRIVER=redis
REDIS_CLIENT=phpredis

```

### **Paso 2: Configurar config/database.php**

```php
// config/database.php
return [
    'default' => env('DB_CONNECTION', 'landlord'),

    'connections' => [
        // Conexión central (landlord)
        'landlord' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'erp_landlord'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
        ],

        // Conexión para tenants (dinámica)
        'tenant' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => null, // Se establece dinámicamente
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
        ],
    ],
];

```

### **Paso 3: Configurar config/tenancy.php**

```php
// config/tenancy.php
return [
    'tenant_model' => \App\Tenancy\Models\Tenant::class,

    'database' => [
        'prefix' => 'tenant_',
        'suffix' => '',
        'template_tenant_connection' => 'tenant',
    ],

    'redis' => [
        'prefix_base' => 'tenant',
    ],

    'filesystem' => [
        'suffix_base' => 'tenant',
        'disks' => ['public'],
    ],

    'session' => [
        'domain' => env('SESSION_DOMAIN', '.miapp.com'),
    ],
];

```

### **Paso 4: Configurar Rutas con Detección de Dominio**

```php
// app/Providers/RouteServiceProvider.php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
{
        $this->routes(function () {
            // API Routes
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            // Determinar si estamos en landlord o tenant
            $domain = request()->getHost();
            $landlordDomain = config('app.landlord_domain', 'www.miapp.com');

            if ($domain === $landlordDomain) {
                // Rutas de Landing (Landlord)
                Route::middleware('web')
                    ->group(base_path('routes/landing.php'));
            } else {
                // Rutas de Dashboard (Tenant)
                Route::middleware(['web', 'tenant'])
                    ->group(base_path('routes/dashboard.php'));
            }
        });
    }
}

```

### **Paso 5: Registrar Middleware**

```php
// app/Http/Kernel.php
namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        // Grupo para tenants
        'tenant' => [
            \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
            \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
        ],
    ];

    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.tenant' => \App\Contexts\Dashboard\Http\Middleware\EnsureAuthenticated::class,
        'guest' => \App\Contexts\Landing\Http\Middleware\RedirectIfAuthenticated::class,
        'tenant' => \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
        // ... otros middleware
    ];
}

```

### **Paso 6: Crear Vistas de Landing**

```
{{-- resources/views/landing/layouts/landing.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Mi ERP - Landing')</title>

    @vite(['resources/css/landing.css', 'resources/js/landing.js'])
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <a href="{{ route('landing.home') }}" class="logo">Mi ERP</a>
            <ul class="nav-links">
                <li><a href="{{ route('landing.home') }}">Inicio</a></li>
                <li><a href="{{ route('landing.pricing') }}">Precios</a></li>
                <li><a href="{{ route('landing.features') }}">Características</a></li>

                @auth
                    <li><a href="{{ route('landing.redirect-to-dashboard') }}">Dashboard</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit">Cerrar Sesión</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}">Iniciar Sesión</a></li>
                    <li><a href="{{ route('register') }}" class="btn-primary">Registrarse</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <!-- Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; {{ date('Y') }} Mi ERP. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

```

```
{{-- resources/views/landing/login.blade.php --}}
@extends('landing.layouts.landing')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="login-container">
    <div class="login-card">
        <h1>Iniciar Sesión</h1>

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                >
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="remember"> Recordarme
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </form>

        <p class="text-center mt-4">
            ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a>
        </p>
    </div>
</div>
@endsection

```

### **Paso 7: Crear Vistas de Dashboard**

```
{{-- resources/views/dashboard/layouts/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard - ' . tenant('name'))</title>

    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <h2>{{ tenant('name') }}</h2>
        </div>

        <nav>
            <ul>
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('inventory.products.index') }}">Inventario</a></li>
                <li><a href="{{ route('sales.orders.index') }}">Ventas</a></li>
                <li><a href="{{ route('crm.customers.index') }}">CRM</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="header">
            <h1>@yield('page-title', 'Dashboard')</h1>

            <div class="user-menu">
                <span>{{ auth('tenant')->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Cerrar Sesión</button>
                </form>
            </div>
        </header>

        <!-- Content -->
        <main class="content">
            @yield('content')
        </main>
    </div>
</body>
</html>

```

---

## **✅ Best Practices**

### **1. Seguridad**

✅ **HTTPS siempre**: Usar SSL para landing y tenants

✅ **CSRF Protection**: Activado en todos los formularios

✅ **Rate Limiting**: Limitar intentos de login

✅ **Session Security**: Regenerar sesión después del login

✅ **Password Hashing**: Usar bcrypt (por defecto en Laravel)

### **2. Sesiones Compartidas**

```php
// config/session.php
'domain' => env('SESSION_DOMAIN', '.miapp.com'),

```

Esto permite compartir la sesión entre `www.miapp.com` y `tenant.miapp.com`.

### **3. Caché por Contexto**

```php
// Landing (landlord)
Cache::driver('redis')->tags(['landlord'])->put('key', 'value');

// Dashboard (tenant)
Cache::driver('redis')->tags(['tenant_' . tenant('id')])->put('key', 'value');

```

### **4. Logging Separado**

```php
// config/logging.php
'channels' => [
    'landlord' => [
        'driver' => 'daily',
        'path' => storage_path('logs/landlord/laravel.log'),
    ],
    'tenant' => [
        'driver' => 'daily',
        'path' => storage_path('logs/tenants/' . (tenant('id') ?? 'unknown') . '/laravel.log'),
    ],
],

```

### **5. Testing**

```php
// tests/Feature/Landing/LoginTest.php
public function test_can_login_and_redirect_to_dashboard()
{
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('https://' . $tenant->domain . '/dashboard');
    $this->assertAuthenticatedAs($user);
}

```

---

## ** Resumen**

| Característica | Landing | Dashboard |
| --- | --- | --- |
| **Dominio** | www.miapp.com | {tenant}.miapp.com |
| **Contexto** | Landlord (Central) | Tenant (Separado) |
| **Autenticación** | Opcional | Requerida |
| **Base de Datos** | Landlord DB | Tenant DB (separada) |
| **Guard** | `web` | `tenant` |
| **Middleware** | `guest`, `RedirectIfAuthenticated` | `tenant`, `auth:tenant` |
| **Propósito** | Marketing, registro, login | Operación del ERP |

---

## ** Conclusión**

Esta arquitectura proporciona:

✅ **Separación clara** entre landing pública y dashboard privado

✅ **Multi-tenancy robusto** con aislamiento de datos

✅ **Experiencia fluida** con redirecciones inteligentes

✅ **Escalabilidad** para agregar nuevos tenants fácilmente

✅ **Seguridad** con autenticación y autorización por capas

✅ **Mantenibilidad** con código organizado por contextos

La clave está en usar el sistema de **guards múltiples** de Laravel y el **middleware de tenancy** para mantener todo separado y seguro.
