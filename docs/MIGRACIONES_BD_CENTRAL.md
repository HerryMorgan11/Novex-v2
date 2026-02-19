# Documentación de Migraciones - Base de Datos Central

## Resumen de Correcciones Realizadas

### ✅ Problemas Corregidos

1. **Duplicado en `create_tenants_table.php`**
    - Eliminada la columna `json('data')` duplicada (había dos declaraciones)
    - Agregado `soft deletes` a nivel de creación de tabla

2. **Foreign Key Faltante en `users.current_tenant_id`**
    - Agregada relación FK hacia `tenants.id`
    - Permite integridad referencial

3. **Soft Deletes en `domains`**
    - Agregado `softDeletes()` para consistencia con resto de tablas

4. **Migración Redundante Neutralizada**
    - `2026_02_19_150700_add_fields_to_tenants_table.php` ahora está vacía (reservada para campos adicionales futuros)
    - Los campos core ya estaban en `create_tenants_table`

---

## 📋 Migraciones Existentes (Del Paquete Stancl/Tenancy)

### 1. `0001_01_01_000001_create_cache_table.php`

- Tabla `cache` - almacenamiento de cache en BD
- Tabla `cache_locks` - locks distribuidos

### 2. `2019_09_15_000010_create_tenants_table.php` ✅ CORREGIDA

```
Campos:
  - id (string, PK)
  - name, slug (unique), status (enum), db_name
  - created_by_user_id (FK -> users.id, nullable)
  - data (JSON), deleted_at (soft delete)
  - timestamps
```

### 3. `2019_09_15_000020_create_domains_table.php` ✅ CORREGIDA

```
Campos:
  - id (increments)
  - domain (string, unique)
  - tenant_id (FK -> tenants.id)
  - timestamps, deleted_at (soft delete)
```

---

## 🆕 Nuevas Migraciones Agregadas

### 4. `2026_02_19_150311_create_users_table.php` ✅ CORREGIDA

```
Tabla: users (central, no tenant-specific)
Campos:
  - id (ULID, PK)
  - name, email (unique), password (nullable)
  - email_verified_at
  - is_active (boolean)
  - current_tenant_id (FK -> tenants.id, nullable) ✅ AGREGADA FK
  - last_login_at, last_login_ip
  - remember_token, timestamps, soft_deletes
```

### 5. `2026_02_19_150312_create_permission_tables.php`

```
Tablas:
  - roles (con PK bigIncrement)
  - permissions (con PK bigIncrement)
  - role_has_permissions (tabla pivote)
  - model_has_roles (para asignar roles a usuarios/modelos)
  - model_has_permissions (para permisos directos)
Nota: Compatible con Laravel Spatie (model_id es CHAR(26) para ULID)
```

### 6. `2026_02_19_150312_create_social_accounts_table.php`

```
Para OAuth/SSO (Google, GitHub, etc.)
Campos:
  - user_id (ULID, FK -> users.id)
  - provider, provider_user_id
  - access_token, refresh_token, token_expires_at
  - Índice único: (provider, provider_user_id)
```

### 7. `2026_02_19_150312_create_tenant_memberships_table.php`

```
Relación usuario-tenant (central)
Campos:
  - user_id (ULID, unique) ⚠️ Nota: Permite 1 usuario = 1 tenant
  - tenant_id (string, FK)
  - is_owner (boolean)
  - status (enum: active, invited, disabled)
  - joined_at, timestamps, soft_deletes
⚠️ Si necesitas múltiples tenants por usuario, quita el UNIQUE en user_id
```

### 8. `2026_02_19_150312_create_tenant_provisionings_table.php`

```
Seguimiento del setup de nuevos tenants
Campos:
  - tenant_id (string, unique, FK)
  - status (pending, running, failed, completed)
  - step, progress (0-100)
  - error_message
  - started_at, finished_at, timestamps
```

### 9. `2026_02_19_160000_create_password_reset_tokens_table.php` ⭐ NUEVA

```
Reemplaza tabla password_resets antigua (Laravel moderno)
Campos:
  - email (string, PK)
  - token
  - created_at
Nota: Más eficiente que password_resets
```

### 10. `2026_02_19_160001_create_tenant_invitations_table.php` ⭐ NUEVA

```
Para invitar usuarios a tenants
Campos:
  - tenant_id, email, token (unique)
  - status (pending, accepted, rejected, expired)
  - invited_by_user_id (FK -> users.id)
  - timestamps, soft_deletes
Índice único compuesto: (tenant_id, email, status)
```

### 11. `2026_02_19_160002_create_tenant_audit_logs_table.php` ⭐ NUEVA

```
Auditoría de acciones en tenants
Campos:
  - tenant_id, user_id (FK)
  - action (created, updated, suspended, etc.)
  - model_type, model_id (qué se modificó)
  - old_values, new_values (JSON)
  - ip_address, user_agent
  - timestamps
Índices compuestos para búsquedas rápidas
```

### 12. `2026_02_19_160003_create_tenant_settings_table.php` ⭐ NUEVA

```
Configuración por tenant
Campos:
  - tenant_id (unique, FK)
  - timezone, locale
  - max_users, max_storage_gb (cuotas)
  - enabled_features (JSON)
  - timestamps
```

### 13. `2026_02_19_160004_add_performance_indexes.php` ⭐ NUEVA

```
Índices compuestos para optimización:
  - tenant_memberships: (tenant_id, status), (tenant_id, is_owner), (user_id, tenant_id)
  - domains: (tenant_id)
  - users: (email), (current_tenant_id, is_active)
```

---

## 🏗️ Diagrama de Relaciones

```
┌─────────────────┐
│     users       │ (central)
│  (id: ULID)     │
└────────┬────────┘
         │
    ┌────┴────────────────────────┐
    │                             │
    ▼                             ▼
┌──────────────────┐      ┌──────────────────────┐
│  social_accounts │      │ password_reset_tokens│
│  (OAuth/SSO)     │      └──────────────────────┘
└──────────────────┘
         │
    ┌────┴──────────────────┐
    │                       │
    ▼                       ▼
┌─────────────────────────────────────┐
│ tenant_memberships (1 user:1 tenant)│
└────┬────────────────────────────────┘
     │
     ▼
┌──────────────────┐
│ tenants (string) │ (central)
└────┬─────────────┘
     │
 ┌───┴───┬──────────┬──────────┬──────────┐
 │       │          │          │          │
 ▼       ▼          ▼          ▼          ▼
domains tenant_   tenant_     tenant_    tenant_
        invit.   provision    audit_logs settings
        -ations  -ing
```

---

## ⚠️ Consideraciones Importantes

### Sobre `tenant_memberships.user_id` UNIQUE

- **Actual**: 1 usuario = 1 tenant
- **Si necesitas**: 1 usuario en múltiples tenants → Quita `->unique()` en user_id
- **Cambio necesario**: Agregar migración con `$table->dropUnique(['user_id'])`

### Sobre `users.current_tenant_id`

- Almacena el tenant "activo" del usuario
- Usado para atajos en UI ("cambiar a tenant X")
- Se actualiza al cambiar de contexto

### Sobre Soft Deletes

- ✅ tenants, users, domains, tenant_memberships, tenant_invitations, tenant_audit_logs
- ❌ Password reset, roles/permissions, social_accounts, tenant_provisionings, tenant_settings
- Ajusta según necesidad de recuperación de datos

### Índices de Rendimiento

- Agregados para FKs y búsquedas frecuentes
- Importante para multi-tenancy con miles de registros
- Revisa `EXPLAIN` en queries críticas

---

## 🚀 Próximos Pasos Recomendados

1. ✅ **Hacer** - Revisar el UNIQUE en `tenant_memberships.user_id` si necesitas multi-tenant
2. ✅ **Hacer** - Crear Models para cada tabla (+ relationships)
3. ✅ **Hacer** - Agregar eventos/listeners para auditoría automática
4. ✅ **Hacer** - Implementar políticas (Policies) para acceso multi-tenancy
5. ✅ **Hacer** - Seeders para datos de prueba (roles, permissions)
6. ✅ **Considerar** - Tabla de `activity_logs` si necesitas auditoría más detallada
7. ✅ **Considerar** - Tabla de `failed_jobs` si usas queue (para async tasks)

---

## 📝 Comandos Útiles

```bash
# Ver estructura de una tabla
php artisan migrate:status

# Rollback última migración
php artisan migrate:rollback

# Rollback todo y remigrar
php artisan migrate:refresh

# Ver queries ejecutadas
php artisan migrate:install --seed

# Para debugging
php artisan tinker
# En tinker:
> DB::table('tenants')->get();
> DB::table('users')->get();
```
