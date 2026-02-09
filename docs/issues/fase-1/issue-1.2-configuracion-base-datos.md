---
title: "[Fase 1.2] Configuración Base de Datos"
labels: fase-1, database, configuration, priority-high
assignees: 
milestone: Fase 1 - Infraestructura y Core
---

## 🗄️ Tarea: Configuración Base de Datos

### Descripción
Configurar las conexiones de base de datos para soportar multi-tenancy (BD central + BDs por tenant)

### Objetivos
- [ ] Configurar conexión BD central (landlord) en `.env`
- [ ] Configurar conexión BD tenant template
- [ ] Extender `config/database.php` para multi-tenancy
- [ ] Crear archivo `.env.example` actualizado
- [ ] Probar conexiones de BD
- [ ] Documentar configuración en README

### Configuración Requerida

#### `.env`
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

#### `config/database.php`
Extender configuración para incluir conexión de tenant:
```php
'connections' => [
    // ... conexiones existentes
    
    'tenant' => [
        'driver' => 'mysql',
        'host' => env('TENANT_DB_HOST', '127.0.0.1'),
        'port' => env('TENANT_DB_PORT', '3306'),
        'database' => null, // Se establece dinámicamente
        'username' => env('TENANT_DB_USERNAME', 'root'),
        'password' => env('TENANT_DB_PASSWORD', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => null,
    ],
],
```

### Pasos de Implementación
1. Actualizar `.env` con variables de BD
2. Modificar `config/database.php`
3. Actualizar `.env.example` para documentar
4. Crear bases de datos en MySQL:
   ```sql
   CREATE DATABASE novex_central;
   ```
5. Probar conexión con artisan:
   ```bash
   php artisan db:show
   ```

### Criterios de Aceptación
- [ ] Variables de entorno configuradas en `.env`
- [ ] Configuración extendida en `config/database.php`
- [ ] `.env.example` actualizado y documentado
- [ ] Conexión a BD central funcionando
- [ ] Tests de conexión exitosos
- [ ] Documentación actualizada en README.md

### Comandos de Prueba
```bash
# Verificar conexión
php artisan db:show

# Verificar tablas (después de migraciones)
php artisan db:table users
```

### Referencias
- `/docs/baseDeDatos.md`
- Laravel Database Documentation
- `/docs/arquitectura.md` - Sección Multi-Tenancy

### Estimación
**1 día**

### Dependencias
Ninguna

### Notas
Esta configuración es fundamental para el resto del proyecto. Asegúrate de que las credenciales sean correctas antes de continuar.
