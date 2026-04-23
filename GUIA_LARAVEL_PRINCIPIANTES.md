# Laravel para Principiantes

## Explicación sencilla de las piezas principales de Laravel y de cómo funciona el patrón MVC

**Última actualización:** 23 de abril de 2026

---

## Tabla de Contenidos

1. [Qué es Laravel](#1-qué-es-laravel)
2. [Qué es MVC y cómo funciona](#2-qué-es-mvc-y-cómo-funciona)
3. [Flujo típico de una petición web](#3-flujo-típico-de-una-petición-web)
4. [Piezas principales de Laravel](#4-piezas-principales-de-laravel)
5. [Cómo encaja todo junto](#5-cómo-encaja-todo-junto)
6. [Resumen rápido](#6-resumen-rápido)

---

## 1. Qué es Laravel

Laravel es un **framework de PHP**. Dicho de forma simple: **es una caja de herramientas ya preparada para crear aplicaciones web sin tener que construir todo desde cero**.

### ¿Por qué Laravel?

- **Te da una forma ordenada de trabajar.** En vez de mezclar todo el código en cualquier sitio, Laravel te empuja a separar responsabilidades.
- **Eso hace que el proyecto sea más fácil de entender, mantener y ampliar.**
- En lugar de inventar la rueda cada vez, Laravel ya te proporciona soluciones probadas para problemas comunes.

### Analogía útil

Piensa en Laravel como un edificio con planos bien definidos:

- Cada habitación tiene su propósito específico.
- Sabes dónde buscar las cosas.
- Si otro arquitecto viene después, sabe inmediatamente dónde mirar.

Sin Laravel sería como construir un edificio sin planos: caos total.

---

## 2. Qué es MVC y cómo funciona

**MVC significa Model - View - Controller**. Es una forma de organizar el código para que cada parte haga una tarea concreta.

### 2.1 Model (Modelo)

**Definición:** El Model representa los datos y la lógica relacionada con esos datos. En Laravel, normalmente un Model representa una tabla de la base de datos.

**En la práctica:**

- Un Model es como una clase que habla con la base de datos.
- Ejemplo: si tienes una tabla llamada `users`, normalmente tendrás un modelo `User`.
- El modelo sabe cómo guardar, obtener, actualizar y borrar usuarios.

**Ejemplo real:**

```php
// Un modelo User sabe cómo trabajar con usuarios
$user = User::find(1);           // Obtener usuario con ID 1
$users = User::all();            // Obtener todos los usuarios
$user->name = "Juan";
$user->save();                   // Guardar cambios
```

**Lo importante:** El Model **no sabe nada de vistas ni de controladores**. Solo sabe de datos.

---

### 2.2 View (Vista)

**Definición:** La View es la parte visual. Es lo que ve el usuario en pantalla: HTML, texto, botones, tablas, formularios, etc.

**En la práctica:**

- En Laravel, las vistas suelen hacerse con **Blade**, que es el motor de plantillas de Laravel.
- Blade permite mezclar HTML con PHP de forma elegante.
- Las vistas **reciben datos del controlador y los muestran**.

**Ejemplo real:**

```blade
<!-- Archivo: resources/views/users/index.blade.php -->
<h1>Lista de usuarios</h1>
<table>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
        </tr>
    @endforeach
</table>
```

**Lo importante:** La View **no hace consultas a base de datos**. Solo muestra lo que le pasa el controlador.

---

### 2.3 Controller (Controlador)

**Definición:** El Controller actúa como intermediario. Recibe la petición, decide qué hacer, habla con modelos o servicios y devuelve una respuesta.

**En la práctica:**

- Un controlador es una clase PHP normal.
- Tiene métodos que responden a acciones del usuario.
- **El controlador coordina el trabajo**: pide datos al modelo, los procesa si hace falta y se los pasa a la vista.

**Ejemplo real:**

```php
// Archivo: app/Http/Controllers/UserController.php
class UserController extends Controller
{
    public function index()
    {
        // Pedir todos los usuarios al modelo
        $users = User::all();

        // Pasar los datos a la vista
        return view('users.index', ['users' => $users]);
    }

    public function store(Request $request)
    {
        // Validar datos
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);

        // Crear un usuario nuevo
        User::create($validated);

        // Redirigir
        return redirect('/users');
    }
}
```

**Lo importante:** El Controller **coordina**, no hace todo directamente. Es el director de orquesta.

---

### 2.4 Ejemplo mental de MVC en acción

**Escenario:** Un usuario entra en una tienda online y mira la lista de productos.

```
1. Usuario entra en /productos
   ↓
2. Laravel recibe la URL
   ↓
3. La Route dice: "esto va al ProductController"
   ↓
4. El ProductController hace:
   - Pide al modelo Product todos los productos
   ↓
5. El modelo consulta la base de datos
   ↓
6. El controlador recibe los datos y llama a la vista
   ↓
7. La vista pinta una tabla HTML con los productos
   ↓
8. El usuario ve una página bonita
```

**Analicemos cada paso:**

| Paso | Quién actúa   | Qué hace                                 |
| ---- | ------------- | ---------------------------------------- |
| 1-3  | Laravel/Route | Recibe URL y la dirige al lugar correcto |
| 4    | Controller    | Decide qué datos necesita                |
| 5    | Model         | Consulta la base de datos                |
| 6    | Controller    | Recibe los datos y los prepara           |
| 7    | View          | Muestra los datos en HTML                |
| 8    | Navegador     | Pinta la página en pantalla              |

---

### 2.5 Resumen de MVC en una frase

> **Model = datos**
> **View = interfaz (lo que ve el usuario)**
> **Controller = coordinación (el jefe que manda)**

---

## 3. Flujo típico de una petición web

Cuando alguien accede a tu aplicación Laravel, sucede una serie de pasos muy ordenados. Entender este flujo es clave:

### Paso a paso completo:

1. **El usuario hace una petición desde el navegador**
    - Escribe una URL o hace clic en un enlace.
    - El navegador envía una petición HTTP.

2. **Laravel recibe la URL**
    - El servidor recibe la petición.
    - Laravel se activa.

3. **La Route decide a qué parte del sistema mandar esa petición**
    - Busca en los archivos de rutas (web.php, api.php, etc.).
    - Encuentra una coincidencia.
    - Ejemplo: `/usuarios` → `UserController@index`

4. **Antes de llegar al destino, puede pasar por Middleware**
    - El middleware es como un guardaespaldas que revisa todo.
    - Comprueba: ¿está autenticado? ¿tiene permiso?
    - Si algo falla, detiene la petición aquí mismo.

5. **Después llega a un Controller o a una Action**
    - El controlador empieza a ejecutarse.
    - Es el responsable de orquestar todo.

6. **Se validan datos con Requests si hace falta**
    - Si el usuario envía un formulario, se valida.
    - Ejemplo: ¿el email es válido? ¿la contraseña tiene suficientes caracteres?

7. **Se usa un Model o un Service para trabajar con datos o lógica de negocio**
    - El controlador pide datos al modelo.
    - O usa un servicio para hacer algo más complejo.

8. **Se devuelve una respuesta**
    - Una vista HTML.
    - Un JSON (para APIs).
    - Una redirección.
    - Un error 404.

### Diagrama visual del flujo:

```
Usuario hace click
        ↓
   Petición HTTP
        ↓
   Laravel recibe
        ↓
   Route busca coincidencia
        ↓
   Middleware (filtros)
        ↓
   Controller (coordinación)
        ↓
   Request (validación)
        ↓
   Model/Service (lógica)
        ↓
   Base de datos
        ↓
   Controller devuelve respuesta
        ↓
   View renderiza HTML
        ↓
   Usuario ve la página
```

---

## 4. Piezas principales de Laravel

Cada pieza de Laravel tiene un propósito específico. Vamos a conocerlas una a una.

### Routes (Rutas)

**Definición teórica:** Las rutas son las reglas que dicen: "si entra una petición a esta URL, ejecútame este código".

**En Laravel:**

- Se suelen definir en archivos como `web.php` o `api.php`.
- Sirven de puerta de entrada.
- Conectan una URL con un Controller, una Action o una función.

**Ubicación típica:** `routes/web.php` o `routes/api.php`

**Ejemplo práctico:**

```php
// routes/web.php
Route::get('/usuarios', [UserController::class, 'index']);
Route::post('/usuarios', [UserController::class, 'store']);
Route::get('/usuarios/{id}', [UserController::class, 'show']);
Route::put('/usuarios/{id}', [UserController::class, 'update']);
Route::delete('/usuarios/{id}', [UserController::class, 'destroy']);
```

**¿Qué significan?**

- `Route::get` = cuando el usuario hace una petición GET (normalmente navegar).
- `Route::post` = cuando el usuario envía un formulario (POST).
- `Route::put/patch` = cuando actualiza datos.
- `Route::delete` = cuando borra algo.

**Lo importante:** Las rutas son el primer guardián. Si no hay ruta, la petición muere aquí.

---

### Controllers (Controladores)

**Definición teórica:** Un controller es una clase que organiza la respuesta a una petición.

**En Laravel:**

- Un controller agrupa acciones relacionadas.
- Por ejemplo, un `UserController` puede tener métodos para listar, crear, actualizar y borrar usuarios.
- Evita meter demasiada lógica en las rutas.
- Hace el código más ordenado.
- Suele ser el punto donde coordinas requests, models, services y responses.

**Ubicación típica:** `app/Http/Controllers/`

**Estructura típica:**

```php
class UserController extends Controller
{
    // Listar todos
    public function index()
    {
        return view('users.index', ['users' => User::all()]);
    }

    // Mostrar formulario de crear
    public function create()
    {
        return view('users.create');
    }

    // Guardar nuevo registro
    public function store(Request $request)
    {
        User::create($request->validated());
        return redirect('/users');
    }

    // Mostrar un registro
    public function show($id)
    {
        return view('users.show', ['user' => User::find($id)]);
    }

    // Mostrar formulario de editar
    public function edit($id)
    {
        return view('users.edit', ['user' => User::find($id)]);
    }

    // Actualizar registro
    public function update(Request $request, $id)
    {
        User::find($id)->update($request->validated());
        return redirect('/users');
    }

    // Borrar registro
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect('/users');
    }
}
```

**Lo importante:** Un controller debería ser como un director de cine: coordinador, pero no actúa en la película.

---

### Actions (Acciones)

**Definición teórica:** Una action suele ser una clase o una unidad muy concreta de trabajo que hace una sola cosa.

**En Laravel:**

- Laravel no obliga a usar actions, pero mucha gente las usa para sacar lógica de los controllers.
- Ejemplos: `CreateUserAction`, `SendInvoiceAction`, `CancelOrderAction`.

**Ubicación típica:** `app/Actions/`

**Ventajas principales:**

- Una responsabilidad clara.
- Hace que el código sea más reutilizable.
- Más fácil de testear.

**Ejemplo práctico:**

```php
// app/Actions/CreateUserAction.php
class CreateUserAction
{
    public function execute(array $data)
    {
        // Validar
        $validated = Validator::validate($data, [
            'name' => 'required',
            'email' => 'required|email|unique:users'
        ]);

        // Crear usuario
        $user = User::create($validated);

        // Enviar email de bienvenida
        Mail::send(new WelcomeMail($user));

        // Registrar en log
        Log::info("Usuario creado: {$user->email}");

        return $user;
    }
}

// Luego en el controller:
class UserController extends Controller
{
    public function store(Request $request)
    {
        $user = app(CreateUserAction::class)->execute($request->all());
        return redirect('/users');
    }
}
```

**Regla fácil:** Si el controller empieza a tener demasiada lógica, parte de esa lógica puede ir a una Action.

---

### Console / Commands (Comandos de Consola)

**Definición teórica:** Son tareas que se ejecutan por consola en vez de desde una página web.

**En Laravel:**

- Se usan con Artisan, la herramienta de comandos de Laravel.
- Sirven para automatizar trabajos: importar datos, limpiar registros, enviar tareas programadas, etc.

**Ubicación típica:** `app/Console/Commands/`

**Ejemplo práctico:**

```php
// app/Console/Commands/SyncUsersFromApi.php
class SyncUsersFromApi extends Command
{
    protected $signature = 'app:sync-users';

    public function handle()
    {
        $this->info('Sincronizando usuarios...');

        $users = Http::get('https://api.example.com/users')->json();

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                ['name' => $userData['name']]
            );
        }

        $this->info('¡Sincronización completada!');
    }
}
```

**Cómo usarlo:**

```bash
php artisan app:sync-users
```

**Lo importante:** No están pensados para que los llame un navegador, sino el desarrollador o el sistema (mediante cron jobs).

---

### Middleware (Intermediarios)

**Definición teórica:** Un middleware es un filtro que se ejecuta antes o después de una petición.

**En Laravel:**

- Sirve para revisar condiciones antes de permitir el acceso a una ruta o a un controlador.
- Ejemplos típicos: comprobar si el usuario ha iniciado sesión, verificar permisos, registrar actividad, cambiar idioma.

**Ubicación típica:** `app/Http/Middleware/`

**Idea simple:** "Antes de entrar, pasa este control".

**Ejemplo práctico:**

```php
// app/Http/Middleware/CheckIfAdmin.php
class CheckIfAdmin
{
    public function handle($request, $next)
    {
        // Comprobar si el usuario es admin
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        // Si no es admin, rechazamos
        return redirect('/');
    }
}

// En routes/web.php:
Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/admin/users', [AdminController::class, 'users']);
});
```

**Flujo con middleware:**

```
Petición llega
    ↓
Middleware 1 (autenticación)
    ↓
Middleware 2 (permisos)
    ↓
Middleware 3 (custom)
    ↓
Controller
    ↓
Response
```

**Lo importante:** Los middleware son guardianes. Protegen el acceso.

---

### Requests (Solicitudes)

**Definición teórica:** Una request representa los datos que llegan en una petición.

**En Laravel:**

- Además de la request general, es muy común usar **Form Requests** para validar datos.
- Ejemplo: validar que el email sea obligatorio y que la contraseña tenga mínimo 8 caracteres.
- Sirven para sacar la validación del controller y dejarlo más limpio.

**Ubicación típica:** `app/Http/Requests/`

**Ejemplo práctico:**

```php
// app/Http/Requests/StoreUserRequest.php
class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        // Aquí puedes comprobar permisos
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'email.unique' => 'Este email ya está registrado'
        ];
    }
}

// En el controller:
class UserController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        // Los datos ya están validados aquí
        User::create($request->validated());
        return redirect('/users');
    }
}
```

**Lo importante:** Las Form Requests validan ANTES de llegar al controller. Mantienen el controller limpio.

---

### Models (Modelos)

**Definición teórica:** Un model representa datos y normalmente está conectado a una tabla de base de datos.

**En Laravel:**

- Laravel usa **Eloquent ORM**. Eso permite trabajar con datos como si fueran objetos.
- No necesitas escribir SQL directamente (aunque puedas si quieres).

**Ubicación típica:** `app/Models/`

**Ejemplo práctico:**

```php
// app/Models/User.php
class User extends Model
{
    protected $fillable = ['name', 'email', 'password'];

    // Relación: Un usuario tiene muchos posts
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Acceso: obtener todos los posts del usuario
    public function allPosts()
    {
        return $this->posts()->get();
    }
}

// Cómo usarlo:
$user = User::find(1);           // Obtener usuario con ID 1
$users = User::all();            // Obtener todos
$users = User::where('email', 'juan@example.com')->first();  // Buscar
$user->name = "Juan";
$user->save();                   // Guardar cambios
$user->delete();                 // Borrar

// Con relaciones:
$user = User::find(1);
$posts = $user->posts;           // Obtener posts del usuario
```

**Ventajas de Eloquent:**

- No escribes SQL crudo.
- El código es más legible.
- Menos errores de seguridad.

**Lo importante:** El modelo es tu traductor entre PHP y la base de datos.

---

### Notifications (Notificaciones)

**Definición teórica:** Una notificación es un aviso que el sistema envía a alguien.

**En Laravel:**

- Laravel permite enviar notificaciones por distintos canales: email, SMS, Slack, base de datos, etc.
- Ejemplo: "tu pedido ha sido enviado".
- Sirven para centralizar cómo se generan y envían esos avisos.

**Ubicación típica:** `app/Notifications/`

**Ejemplo práctico:**

```php
// app/Notifications/OrderShipped.php
class OrderShipped extends Notification
{
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Tu pedido ha sido enviado!')
            ->action('Ver pedido', url('/orders/1'))
            ->line('¡Gracias por tu compra!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'order_id' => 1,
            'message' => 'Tu pedido ha sido enviado'
        ];
    }
}

// Cómo usarlo:
$user = User::find(1);
$user->notify(new OrderShipped());
```

**Lo importante:** Las notificaciones centralizan cómo comunicas con los usuarios.

---

### Policies (Políticas de Autorización)

**Definición teórica:** Una policy define qué acciones puede hacer un usuario sobre un recurso.

**En Laravel:**

- Se usa para autorización. **No es lo mismo autenticación que autorización:**
    - **Autenticación** = saber quién eres.
    - **Autorización** = saber qué te dejan hacer.

**Ubicación típica:** `app/Policies/`

**Ejemplo práctico:**

```php
// app/Policies/PostPolicy.php
class PostPolicy
{
    public function view(User $user, Post $post)
    {
        return true; // Cualquiera puede ver posts
    }

    public function create(User $user)
    {
        return $user->is_verified; // Solo usuarios verificados
    }

    public function update(User $user, Post $post)
    {
        return $user->id === $post->user_id; // Solo el autor
    }

    public function delete(User $user, Post $post)
    {
        return $user->id === $post->user_id;
    }
}

// En el controller:
class PostController extends Controller
{
    public function edit($id)
    {
        $post = Post::find($id);

        // Comprobar autorización
        if (!auth()->user()->can('update', $post)) {
            abort(403, 'No tienes permiso');
        }

        return view('posts.edit', ['post' => $post]);
    }
}
```

**Lo importante:** Las policies protegen tus datos. No dejes que un usuario edite el post de otro.

---

### Providers (Proveedores)

**Definición teórica:** Un provider es una clase que arranca o registra partes importantes del sistema.

**En Laravel:**

- Los service providers son una pieza central del arranque de Laravel.
- Registran servicios en el contenedor.
- Configuran comportamientos globales.

**Ubicación típica:** `app/Providers/`

**Dicho fácil:** "Preparan el entorno para que la aplicación funcione".

**Ejemplo práctico:**

```php
// app/Providers/AppServiceProvider.php
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Registrar servicios en el contenedor
        $this->app->singleton('pdf-generator', function () {
            return new PdfGenerator();
        });
    }

    public function boot()
    {
        // Código que se ejecuta después de registrar
        Model::preventLazyLoading(!app()->isProduction());

        // Macros globales
        Str::macro('toInitials', function () {
            return collect(explode(' ', $this))
                ->map(fn($s) => $s[0])
                ->implode('');
        });
    }
}
```

**Lo importante:** Los providers son como el acta constitucional de tu app. Se ejecutan al arrancar.

---

### Services (Servicios)

**Definición teórica:** Un service es una clase donde metes lógica de negocio o procesos que no encajan bien ni en controllers ni en models.

**En Laravel:**

- Laravel no obliga a tener carpeta Services, pero es una práctica muy común.
- Ejemplos: `PaymentService`, `InvoiceService`, `UserImportService`.
- Sirven para mantener el código limpio y repartir responsabilidades.

**Ubicación típica:** `app/Services/`

**Ejemplo práctico:**

```php
// app/Services/PaymentService.php
class PaymentService
{
    public function processPayment(Order $order, array $paymentData)
    {
        // Conectar a API de pagos
        $response = Http::post('https://api.stripe.com/...', [
            'amount' => $order->total,
            'currency' => 'EUR'
        ]);

        if ($response->successful()) {
            $order->update(['status' => 'paid']);

            // Notificar al usuario
            $order->user->notify(new PaymentReceived());

            return true;
        }

        throw new PaymentException('El pago falló');
    }
}

// En el controller:
class OrderController extends Controller
{
    public function pay(Order $order, Request $request)
    {
        $paymentService = app(PaymentService::class);
        $paymentService->processPayment($order, $request->all());
        return redirect('/orders');
    }
}
```

**Lo importante:** Los services encapsulan lógica compleja. El controller solo los coordina.

---

### Factories (Fabricas de Datos)

**Definición teórica:** Una factory sirve para generar datos de ejemplo o datos falsos de forma rápida.

**En Laravel:**

- Se usan mucho en tests y durante el desarrollo.
- Ejemplo: crear 50 usuarios falsos para probar una pantalla.

**Ubicación típica:** `database/factories/`

**Ejemplo práctico:**

```php
// database/factories/UserFactory.php
class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ];
    }
}

// Cómo usarlo:
// Crear 1 usuario
User::factory()->create();

// Crear 50 usuarios
User::factory()->count(50)->create();

// Crear un usuario con datos específicos
User::factory()->create([
    'name' => 'Juan',
    'email' => 'juan@example.com'
]);

// En tests:
public function test_user_can_login()
{
    $user = User::factory()->create();
    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password'
    ])->assertRedirect('/dashboard');
}
```

**Lo importante:** Las factories no son parte de la lógica real. Son herramientas de desarrollo.

---

### Migrations (Migraciones)

**Definición teórica:** Una migration es un archivo que define cambios en la estructura de la base de datos.

**En Laravel:**

- Sirven para crear tablas, añadir columnas, borrar campos, etc.
- Idea simple: **es como el historial controlado de la base de datos.**
- Así todos los desarrolladores pueden aplicar los mismos cambios de forma ordenada.

**Ubicación típica:** `database/migrations/`

**Ejemplo práctico:**

```php
// database/migrations/2026_04_23_000001_create_users_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
```

**Cómo usarla:**

```bash
php artisan migrate              # Ejecutar todas las migraciones
php artisan migrate:rollback     # Deshacer la última
php artisan migrate:reset        # Deshacer todas
```

**Ventajas:**

- Control de versiones de la base de datos.
- Facilita el trabajo en equipo.
- Reversibilidad.

**Lo importante:** Las migrations son el changelog de tu base de datos.

---

### Seeders (Sembradores)

**Definición teórica:** Un seeder mete datos iniciales o de prueba en la base de datos.

**En Laravel:**

- Se usan para cargar información básica o de ejemplo.
- Ejemplo: crear roles iniciales como admin, editor y usuario.
- Suelen trabajar junto con factories.

**Ubicación típica:** `database/seeders/`

**Ejemplo práctico:**

```php
// database/seeders/RoleSeeder.php
class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'editor']);
        Role::create(['name' => 'viewer']);
    }
}

// database/seeders/DatabaseSeeder.php
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Ejecutar todos los seeders
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            PostSeeder::class,
        ]);
    }
}

// En UserSeeder.php:
class UserSeeder extends Seeder
{
    public function run()
    {
        // Crear 1 admin
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin'
        ]);

        // Crear 50 usuarios normales
        User::factory()->count(50)->create();
    }
}
```

**Cómo usarlo:**

```bash
php artisan db:seed                    # Ejecutar todos los seeders
php artisan db:seed --class=RoleSeeder # Ejecutar un seeder específico
```

**Lo importante:** Los seeders populan la base de datos con datos iniciales.

---

## 5. Cómo encaja todo junto

Ya hemos aprendido cada pieza por separado. Ahora vamos a ver cómo trabajan juntas en un ejemplo completo y realista.

### Ejemplo completo: Sistema de carrito de compra

**Escenario:** Un usuario quiere crear una orden de compra.

#### Paso 1: El usuario accede a `/orders/create`

```
URL: /orders/create
```

#### Paso 2: La ruta lo dirige al controlador

```php
// routes/web.php
Route::get('/orders/create', [OrderController::class, 'create'])->middleware('auth');
```

**El middleware `auth` comprueba:** ¿el usuario está autenticado? Si no, lo redirige a login.

#### Paso 3: El controlador prepara la vista

```php
// app/Http/Controllers/OrderController.php
class OrderController extends Controller
{
    public function create()
    {
        // El usuario debe estar autenticado (middleware garantiza esto)
        $user = auth()->user();

        // Obtener los productos disponibles
        $products = Product::all();

        // Pasar datos a la vista
        return view('orders.create', [
            'user' => $user,
            'products' => $products
        ]);
    }
}
```

#### Paso 4: La vista muestra el formulario

```blade
<!-- resources/views/orders/create.blade.php -->
<form action="/orders" method="POST">
    @csrf

    <h1>Crear orden</h1>

    <label>Usuario: {{ auth()->user()->name }}</label>

    <table>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>${{ $product->price }}</td>
                <td>
                    <input type="number" name="products[{{ $product->id }}]" min="0">
                </td>
            </tr>
        @endforeach
    </table>

    <button type="submit">Crear orden</button>
</form>
```

#### Paso 5: El usuario envía el formulario

```
POST /orders
Datos: { "products": { "1": "2", "3": "1" } }
```

#### Paso 6: La ruta recibe el POST

```php
// routes/web.php
Route::post('/orders', [OrderController::class, 'store'])->middleware('auth');
```

#### Paso 7: Una Form Request valida los datos

```php
// app/Http/Requests/StoreOrderRequest.php
class StoreOrderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'products' => 'required|array',
            'products.*' => 'integer|min:1'
        ];
    }
}
```

#### Paso 8: El controlador recibe los datos validados

```php
// app/Http/Controllers/OrderController.php
class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        // Los datos ya están validados

        // Crear la orden
        $order = Order::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'total' => 0
        ]);

        // Usar un service para procesar la lógica compleja
        $orderService = app(OrderService::class);
        $orderService->addProductsToOrder($order, $request->products);

        // Una Policy comprueba permisos (¿puede crear órdenes?)
        $this->authorize('create', $order);

        // Guardar
        $order->save();

        // Enviar notificación
        auth()->user()->notify(new OrderCreated($order));

        // Redirigir
        return redirect('/orders/' . $order->id);
    }
}
```

#### Paso 9: El service hace la lógica compleja

```php
// app/Services/OrderService.php
class OrderService
{
    public function addProductsToOrder(Order $order, array $products)
    {
        $total = 0;

        foreach ($products as $productId => $quantity) {
            $product = Product::find($productId);

            // Crear línea de orden
            $order->items()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price
            ]);

            // Actualizar total
            $total += $product->price * $quantity;
        }

        $order->update(['total' => $total]);
    }
}
```

#### Paso 10: Se guarda en base de datos (Model)

```php
// El Model sabe cómo guardar
$order->save();

// SQL ejecutado:
// INSERT INTO orders (user_id, status, total) VALUES (1, 'pending', 150.00)
```

#### Paso 11: Se envía una notificación

```php
// app/Notifications/OrderCreated.php
class OrderCreated extends Notification
{
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Tu orden ha sido creada')
            ->line('¡Tu orden está en proceso!')
            ->action('Ver orden', url('/orders/' . $this->order->id));
    }
}
```

#### Paso 12: Se devuelve la respuesta

```php
return redirect('/orders/' . $order->id);
```

El usuario es redirigido a la página de detalles de la orden.

### Resumen visual del flujo completo:

```
Usuario relleña formulario
        ↓
POST /orders
        ↓
Route busca /orders
        ↓
Middleware auth (¿está logado?)
        ↓
OrderController@store
        ↓
StoreOrderRequest valida
        ↓
Policy (@authorize)
        ↓
OrderService procesa lógica
        ↓
Model Order guarda en BD
        ↓
Notification enviada por email
        ↓
Redirect /orders/{id}
        ↓
OrderController@show prepara datos
        ↓
View orders.show renderiza HTML
        ↓
Usuario ve su orden creada
```

---

## 6. Resumen rápido

### Todas las piezas en una tabla

| Pieza             | Qué hace                                            | Ubicación típica        |
| ----------------- | --------------------------------------------------- | ----------------------- |
| **Routes**        | Reciben la URL y deciden a dónde va                 | `routes/web.php`        |
| **Middleware**    | Filtran el acceso o modifican la petición/respuesta | `app/Http/Middleware/`  |
| **Controllers**   | Coordinan la petición                               | `app/Http/Controllers/` |
| **Form Requests** | Validan datos de entrada                            | `app/Http/Requests/`    |
| **Models**        | Representan y manejan datos                         | `app/Models/`           |
| **Actions**       | Hacen tareas concretas                              | `app/Actions/`          |
| **Services**      | Agrupan lógica de negocio                           | `app/Services/`         |
| **Notifications** | Envían avisos                                       | `app/Notifications/`    |
| **Policies**      | Controlan permisos                                  | `app/Policies/`         |
| **Providers**     | Registran partes del sistema                        | `app/Providers/`        |
| **Commands**      | Tareas por consola                                  | `app/Console/Commands/` |
| **Views**         | Muestran HTML                                       | `resources/views/`      |
| **Factories**     | Crean datos falsos                                  | `database/factories/`   |
| **Migrations**    | Modifican la base de datos                          | `database/migrations/`  |
| **Seeders**       | Insertan datos iniciales                            | `database/seeders/`     |

### MVC en una frase

> **Model = datos**
> **View = interfaz**
> **Controller = coordinación**

### Orden típico de ejecución

1. **Route** → Recibe URL
2. **Middleware** → Valida acceso
3. **Controller** → Coordina
4. **Form Request** → Valida datos
5. **Model/Service** → Procesa lógica
6. **Database** → Guarda/obtiene datos
7. **Notification** → Notifica si es necesario
8. **View** → Renderiza HTML
9. **Response** → Usuario ve resultado

### Preguntas clave para saber dónde poner código

- ¿Es una regla de URL? → **Routes**
- ¿Necesita verificar permisos? → **Middleware** o **Policies**
- ¿Necesita validar datos? → **Form Request**
- ¿Es una operación simple sobre datos? → **Model**
- ¿Es lógica de negocio compleja? → **Service** o **Action**
- ¿Necesita coordinación de varias piezas? → **Controller**
- ¿Debe verse en la interfaz? → **View**
- ¿Necesita notificar a alguien? → **Notification**
- ¿Es una tarea administrativa? → **Command**

---

## Idea final

Laravel no es "muchas carpetas porque sí". **Cada pieza existe para que el proyecto sea más ordenado**.

Cuando entiendes qué responsabilidad tiene cada parte:

- El framework deja de parecer complicado.
- Empieza a tener sentido.
- Tu código es más limpio.
- Es más fácil de mantener.
- Otros desarrolladores lo entienden rápido.

### Analogía final: La orquesta

Imagina Laravel como una orquesta sinfónica:

- **Routes** = La puerta por donde entra la gente
- **Middleware** = Los acomodadores que comprueban entradas
- **Controller** = El director de orquesta
- **Models** = Los violinistas (datos)
- **Services** = Los ensayadores especializados
- **Views** = El escenario donde ocurre la magia
- **Notifications** = El programa que recibe el público

Cada uno tiene su rol. Cuando todos hacen su trabajo bien, el resultado es hermoso.

---

## Siguientes pasos

Una vez entiendas estos conceptos, estás listo para:

1. **Crear tu primer CRUD** (Create, Read, Update, Delete)
2. **Trabajar con relaciones** entre modelos
3. **Usar eventos** para desacoplar lógica
4. **Testear** tu código
5. **Entender el routing avanzado**
6. **Construir APIs REST**

**¡Bienvenido al mundo de Laravel! 🚀**

---

_Última actualización: 23 de abril de 2026_
_Guía creada para el proyecto Novex v2_
