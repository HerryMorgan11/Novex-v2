# ✅ Verificación de Multi-Tenancy

## 📋 Pasos para verificar la implementación

### 1. Verificar migraciones centrales

```bash
php artisan migrate
```

Debe crear en tu base de datos central:

- `tenants`
- `domains`
- `users`
- `cache`
- `jobs`

### 2. Crear un tenant de prueba

Desde `php artisan tinker`:

```php
$tenant = \Stancl\Tenancy\Database\Models\Tenant::create([
    'id' => 'empresa1',
]);

$tenant->domains()->create([
    'domain' => 'empresa1.test', // o 'empresa1.localhost'
]);
```

Esto creará automáticamente:

- Base de datos: `tenant_empresa1`
- Ejecutará migraciones en esa BD

### 3. Configurar /etc/hosts (solo para pruebas locales)

```bash
sudo nano /etc/hosts
```

Agregar:

```
127.0.0.1 empresa1.test
127.0.0.1 empresa1.localhost
```

### 4. Probar rutas centrales

**URL:** `http://localhost` o `http://127.0.0.1`

```bash
curl http://localhost
# Debe mostrar la landing page (home)

curl http://localhost/health
# Debe responder: CENTRAL HEALTH OK
```

✅ **Criterio:** El dominio central NO debe inicializar tenancy.

### 5. Probar rutas de tenant

**URL:** `http://empresa1.test` o `http://empresa1.localhost`

```bash
curl http://empresa1.test
# Debe mostrar: Tenant: empresa1 | Database: tenant_empresa1

curl http://empresa1.test/__tenancy
# Debe responder JSON con info del tenant
```

✅ **Criterio:** El tenant debe usar su propia base de datos.

### 6. Verificar aislamiento

Crear otro tenant:

```php
$tenant2 = \Stancl\Tenancy\Database\Models\Tenant::create(['id' => 'empresa2']);
$tenant2->domains()->create(['domain' => 'empresa2.test']);
```

Agregar a `/etc/hosts`:

```
127.0.0.1 empresa2.test
```

Verificar:

```bash
curl http://empresa1.test/__tenancy
# database: tenant_empresa1

curl http://empresa2.test/__tenancy
# database: tenant_empresa2
```

✅ **Criterio:** Cada tenant debe tener su propia BD separada.

### 7. Verificar que central NO inicia tenancy

```bash
curl http://localhost/__tenancy
# Debe dar 404 (la ruta no existe en central)
```

✅ **Criterio:** Las rutas tenant NO deben estar disponibles en dominios centrales.

---

## 🔍 Checklist Final

- [ ] `php artisan migrate` ejecuta sin errores
- [ ] Tabla `tenants` existe en BD central
- [ ] Tabla `domains` existe en BD central
- [ ] Puedo crear tenant desde tinker
- [ ] Se crea automáticamente base de datos `tenant_*`
- [ ] Dominio central (`localhost`) muestra landing sin inicializar tenancy
- [ ] Dominio tenant (`empresa1.test`) inicializa tenancy correctamente
- [ ] Cada tenant usa su propia base de datos
- [ ] No hay fugas de datos entre tenants
- [ ] El endpoint `/__tenancy` muestra el tenant correcto
- [ ] Las rutas centrales NO están disponibles en dominios tenant
- [ ] Las rutas tenant NO están disponibles en dominios centrales

---

## 🐛 Solución de problemas

### Error: "Base de datos no existe"

```bash
# Verificar que el usuario MySQL pueda crear bases de datos
GRANT ALL ON *.* TO 'sail'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
```

### Error: "No se inicializa tenancy"

- Verificar que el dominio esté en `domains` table
- Verificar que el dominio NO esté en `central_domains` (config/tenancy.php)
- Limpiar caché: `php artisan config:clear`

### Error: "Tenancy se inicializa en dominio central"

- Verificar `central_domains` en config/tenancy.php
- Verificar que `PreventAccessFromCentralDomains` middleware esté activo

---

## 📊 Comandos útiles

```bash
# Ver todos los tenants
php artisan tinker
>>> \Stancl\Tenancy\Database\Models\Tenant::with('domains')->get()

# Ejecutar comando en todos los tenants
php artisan tenants:run migrate

# Ejecutar comando en un tenant específico
php artisan tenants:run migrate --tenants=empresa1

# Verificar conexiones de BD
php artisan tinker
>>> DB::connection()->getDatabaseName()  // en central
>>> tenancy()->initialize('empresa1');
>>> DB::connection()->getDatabaseName()  // en tenant
```
