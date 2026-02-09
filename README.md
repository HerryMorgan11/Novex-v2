# 🚀 Novex v2 - ERP Multi-Tenant

Sistema ERP completo con multi-tenancy, construido con Laravel 12 y Clean Architecture.

> ## 🚨 ¿No ves las Issues en GitHub?
> **Las issues NO están creadas todavía.** Lee [LEEME_ISSUES.md](LEEME_ISSUES.md) para instrucciones simples de cómo crearlas.
> 
> **TL;DR:** Copia el contenido de `docs/issues/fase-1/issue-1.1-....md` y pégalo en https://github.com/HerryMorgan11/Novex-v2/issues/new

## 📋 Descripción

Novex v2 es un sistema ERP (Enterprise Resource Planning) moderno y escalable que permite a múltiples empresas (tenants) gestionar sus operaciones de forma independiente y segura. Incluye módulos de:

- 📦 **Inventario**: Gestión de productos, categorías, stock y almacenes
- 💰 **Ventas**: Órdenes, facturas y punto de venta
- 👥 **CRM**: Gestión de clientes y relaciones
- 📊 **Contabilidad**: Cuentas, transacciones y reportes
- 👔 **RRHH**: Empleados, departamentos y nómina

## 🏗️ Arquitectura

El proyecto está construido siguiendo los principios de **Clean Architecture** para mantener el código:
- ✅ Independiente de frameworks
- ✅ Altamente testeable
- ✅ Independiente de la UI
- ✅ Independiente de la base de datos

### Multi-Tenancy

Utilizamos el paquete [stancl/tenancy](https://tenancyforlaravel.com/) con estrategia de **database por tenant** para:
- Máximo aislamiento de datos
- Backups independientes por cliente
- Escalado por cliente
- Cumplimiento normativo (GDPR, etc.)

## 🛠️ Stack Tecnológico

- **Backend**: Laravel 12
- **Frontend**: Livewire 4, Alpine.js, Tailwind CSS
- **Database**: MySQL 8
- **Cache**: Redis
- **Testing**: PHPUnit, Laravel Dusk
- **Code Quality**: Laravel Pint, PHPStan, ESLint

## 📚 Documentación

### 🗺️ Planificación del Proyecto
- **[QUICK START](docs/QUICK_START.md)** - 🔥 **EMPIEZA AQUÍ** - Guía de inicio rápido
- **[ROADMAP](docs/ROADMAP.md)** - Resumen ejecutivo y progreso general
- **[PROJECT PHASES](docs/PROJECT_PHASES.md)** - Plan detallado de todas las fases
- **[GITHUB ISSUES TEMPLATES](docs/GITHUB_ISSUES_TEMPLATES.md)** - Templates para crear issues

### 🏛️ Arquitectura y Diseño
- **[Arquitectura](docs/arquitectura.md)** - Clean Architecture y Multi-Tenancy
- **[Base de Datos](docs/baseDeDatos.md)** - Esquema completo de la BD
- **[Landing Design](docs/landingDesign.md)** - Estructura de vistas
- **[Landing Pública](docs/landingPublica.md)** - Arquitectura landing + dashboard

## 🚀 Getting Started

### Prerrequisitos

- PHP 8.2+
- Composer
- Node.js 18+
- Docker y Docker Compose (para Laravel Sail)

### Instalación

```bash
# 1. Clonar el repositorio
git clone https://github.com/HerryMorgan11/Novex-v2.git
cd Novex-v2

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias JavaScript
npm install

# 4. Configurar variables de entorno
cp .env.example .env
# Editar .env con tus credenciales

# 5. Generar key de aplicación
php artisan key:generate

# 6. Iniciar servicios con Sail (Docker)
./vendor/bin/sail up -d

# 7. Ejecutar migraciones
./vendor/bin/sail artisan migrate

# 8. Compilar assets
npm run dev

# 9. Acceder a la aplicación
# http://localhost
```

### Instalación de Multi-Tenancy (Fase 2)

```bash
# 1. Instalar paquete
composer require stancl/tenancy

# 2. Publicar configuración
php artisan tenancy:install

# 3. Ejecutar migraciones de tenancy
php artisan migrate

# 4. Crear tenant de prueba
php artisan tinker
$tenant = Tenant::create(['id' => 'test']);
$tenant->domains()->create(['domain' => 'test.localhost']);
```

## 📊 Estado del Proyecto

### Progreso General: 6% (Semana 1 de 16)

#### ✅ Completado (Fase 0)
- [x] Proyecto Laravel 12 inicializado
- [x] Livewire 4 instalado
- [x] Estructura básica de vistas
- [x] Rutas básicas configuradas
- [x] Planificación completa del proyecto

#### 🔄 En Progreso (Fase 1)
- [ ] Estructura Clean Architecture (40%)
- [ ] Configuración de Base de Datos (20%)
- [ ] Landing page básica (20%)

#### ⏳ Pendiente
- [ ] Multi-tenancy (Fase 2)
- [ ] Sistema de autenticación (Fase 2)
- [ ] Dashboard foundation (Fase 4)
- [ ] Módulo Inventario (Fase 5)
- [ ] Módulos adicionales (Fase 6)

Ver [ROADMAP.md](docs/ROADMAP.md) para detalles completos.

## 🎯 Próximos Pasos

### Esta Semana (Fase 1)
1. ✅ Completar planificación
2. ⏳ Configurar entorno de desarrollo
3. ⏳ Instalar multi-tenancy
4. ⏳ Crear estructura Clean Architecture
5. ⏳ Configurar herramientas de desarrollo

### Próxima Semana (Fase 2)
1. Implementar sistema de autenticación
2. Configurar flujo multi-tenant
3. Crear migraciones de BD central
4. Tests de autenticación

Ver [QUICK_START.md](docs/QUICK_START.md) para guía detallada.

## 🧪 Testing

```bash
# Ejecutar todos los tests
./vendor/bin/sail test

# Tests específicos
./vendor/bin/sail test --filter=AuthTest

# Con cobertura
./vendor/bin/sail test --coverage

# Tests de Feature
./vendor/bin/sail test tests/Feature

# Tests Unit
./vendor/bin/sail test tests/Unit
```

## 🔧 Comandos Útiles

### Desarrollo

```bash
# Iniciar servidor
./vendor/bin/sail up -d

# Ver logs
./vendor/bin/sail logs -f

# Acceder al contenedor
./vendor/bin/sail shell

# Detener servicios
./vendor/bin/sail down
```

### Code Quality

```bash
# Formatear código PHP (Laravel Pint)
./vendor/bin/pint

# Análisis estático PHP (PHPStan)
./vendor/bin/phpstan analyse

# Lint JavaScript
npm run lint

# Formatear JavaScript/CSS
npm run format
```

### Base de Datos

```bash
# Ejecutar migraciones
./vendor/bin/sail artisan migrate

# Rollback
./vendor/bin/sail artisan migrate:rollback

# Fresh + seed
./vendor/bin/sail artisan migrate:fresh --seed

# Crear migración
./vendor/bin/sail artisan make:migration create_products_table
```

## 📁 Estructura del Proyecto

```
novex-v2/
├── app/
│   ├── Core/                      # Clean Architecture Core
│   │   ├── Domain/               # Lógica de negocio
│   │   ├── Application/          # Casos de uso
│   │   └── Infrastructure/       # Implementaciones técnicas
│   ├── Modules/                  # Módulos del ERP
│   │   ├── Inventory/
│   │   ├── Sales/
│   │   ├── CRM/
│   │   └── Accounting/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Livewire/            # Componentes Livewire
│   │   ├── Middleware/
│   │   └── Requests/
│   └── Models/
├── resources/
│   ├── views/
│   │   ├── landing/             # Landing page pública
│   │   ├── auth/                # Autenticación
│   │   └── dashboard/           # Dashboard privado
│   ├── js/
│   └── css/
├── routes/
│   ├── web.php                  # Rutas públicas
│   ├── tenant.php               # Rutas tenant
│   └── api.php                  # API
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── tests/
│   ├── Feature/
│   └── Unit/
└── docs/                        # 📚 Documentación del proyecto
    ├── QUICK_START.md          # Inicio rápido
    ├── ROADMAP.md              # Roadmap y progreso
    ├── PROJECT_PHASES.md       # Plan detallado
    ├── arquitectura.md         # Arquitectura
    ├── baseDeDatos.md          # Diseño de BD
    └── ...
```

## 🤝 Contribuir

Este es un proyecto en desarrollo activo. Si quieres contribuir:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

### Guías de Contribución

- Sigue los principios de Clean Architecture
- Escribe tests para nuevas funcionalidades
- Usa Laravel Pint para formatear código
- Documenta cambios importantes

## 📝 Convenciones de Código

### PHP
- PSR-12 (enforced by Laravel Pint)
- Type hints en todos los métodos
- PHPDoc para métodos públicos
- Use statements ordenados alfabéticamente

### JavaScript
- ESLint Standard config
- Prettier para formateo
- Comentarios JSDoc para funciones complejas

### Git Commits
```
[Fase X.Y] Título del commit

Descripción detallada de los cambios
- Punto 1
- Punto 2

Refs: #123
```

## 📄 Licencia

Este proyecto es privado y propietario.

## 👥 Equipo

- **Desarrollo**: [Tu Nombre]
- **Arquitectura**: [Tu Nombre]
- **Diseño**: [Diseñador]

## 🔗 Enlaces Útiles

### Documentación Externa
- [Laravel 12 Docs](https://laravel.com/docs/12.x)
- [Livewire 4 Docs](https://livewire.laravel.com/docs)
- [Tenancy for Laravel](https://tenancyforlaravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Alpine.js](https://alpinejs.dev/start-here)

### Clean Architecture
- [Clean Architecture - Uncle Bob](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)
- [Domain-Driven Design](https://martinfowler.com/bliki/DomainDrivenDesign.html)

## 📞 Soporte

Para preguntas o problemas:
1. Revisa la [documentación](docs/)
2. Busca en [Issues](https://github.com/HerryMorgan11/Novex-v2/issues)
3. Crea un nuevo Issue si es necesario

---

**Última actualización**: 2026-02-09

**Versión**: 1.0.0

**Estado**: 🔄 En Desarrollo Activo
