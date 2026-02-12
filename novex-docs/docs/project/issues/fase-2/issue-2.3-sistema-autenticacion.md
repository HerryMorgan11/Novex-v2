---
title: '[Fase 2.3] Implementar Sistema de Autenticación'
labels: fase-2, authentication, priority-high, frontend, backend
assignees:
milestone: Fase 2 - Auth + Multi-Tenancy
---

## Tarea: Implementar Sistema de Autenticación Completo

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
- [ ] Crear `ForgotPasswordRequest` con validación

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

### Implementación

#### 1. AuthController

`app/Http/Controllers/AuthController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Detectar tenant del usuario
            $user = Auth::user();
            if ($user->tenant_id) {
                $tenant = Tenant::find($user->tenant_id);
                $domain = $tenant->domains()->first();

                // Redirigir al subdominio del tenant
                return redirect()->away("http://{$domain->domain}/dashboard");
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        // Crear tenant
        $tenant = Tenant::create([
            'id' => \Str::slug($request->company_name),
            'name' => $request->company_name,
            'admin_email' => $request->email,
        ]);

        // Crear dominio
        $tenant->domains()->create([
            'domain' => \Str::slug($request->company_name) . '.localhost',
        ]);

        // Crear usuario
        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Login automático
        Auth::login($user);

        return redirect()->away("http://{$tenant->domains->first()->domain}/dashboard");
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        // Implementar lógica de reset password
        // ...
    }
}
```

#### 2. Form Requests

`app/Http/Requests/LoginRequest.php`

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'El email es requerido',
            'email.email' => 'Ingresa un email válido',
            'password.required' => 'La contraseña es requerida',
        ];
    }
}
```

`app/Http/Requests/RegisterRequest.php`

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'company_name' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es requerido',
            'email.required' => 'El email es requerido',
            'email.unique' => 'Este email ya está registrado',
            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'company_name.required' => 'El nombre de la empresa es requerido',
        ];
    }
}
```

#### 3. Vistas

`resources/views/auth/login.blade.php`

```blade
@extends('auth.layout')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Inicia sesión en tu cuenta
            </h2>
        </div>
        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf

            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="Email" value="{{ old('email') }}">
                </div>
                <div>
                    <label for="password" class="sr-only">Contraseña</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="Contraseña">
                </div>
            </div>

            @error('email')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">
                        Recordarme
                    </label>
                </div>

                <div class="text-sm">
                    <a href="{{ route('forgot-password') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Iniciar sesión
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                    ¿No tienes cuenta? Regístrate
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
```

#### 4. Rutas

`routes/web.php`

```php
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
```

### Criterios de Aceptación

- [ ] Login funcionando con tenant detection
- [ ] Registro creando tenant nuevo automáticamente
- [ ] Reset de contraseña enviando emails
- [ ] Vistas con diseño profesional (Tailwind CSS)
- [ ] Validaciones funcionando correctamente
- [ ] Mensajes de error en español
- [ ] Redirección correcta después de login
- [ ] Tests de autenticación pasando (>90% cobertura)

### Testing

`tests/Feature/Auth/LoginTest.php`

```php
<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Tenant;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_users_can_authenticate()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
    }

    public function test_users_cannot_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
```

### Referencias

- Laravel Authentication Documentation
- `/docs/landingPublica.md` - Sección "Workflow"
- `/docs/PROJECT_PHASES.md`

### Estimación

**3 días**

### Dependencias

- Issue 2.1 (Multi-Tenancy) completada
- Issue 2.2 (Migraciones BD Central) completada

### Notas

Este sistema de auth debe ser compatible con multi-tenancy. Los usuarios pueden pertenecer a un tenant específico o ser administradores globales.
