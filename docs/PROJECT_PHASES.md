# 🚀 Plan de Fases del Proyecto Novex v2 ERP

## 📋 Índice
1. [Resumen Ejecutivo](#resumen-ejecutivo)
2. [Fase 0: Configuración Inicial](#fase-0-configuración-inicial)
3. [Fase 1: Infraestructura y Core](#fase-1-infraestructura-y-core)
4. [Fase 2: Autenticación y Multi-Tenancy](#fase-2-autenticación-y-multi-tenancy)
5. [Fase 3: Landing Page](#fase-3-landing-page)
6. [Fase 4: Dashboard Foundation](#fase-4-dashboard-foundation)
7. [Fase 5: Módulo Inventario](#fase-5-módulo-inventario)
8. [Fase 6: Módulos Adicionales](#fase-6-módulos-adicionales)
9. [Fase 7: Testing y QA](#fase-7-testing-y-qa)
10. [Fase 8: Deploy y Producción](#fase-8-deploy-y-producción)

---

## 📊 Resumen Ejecutivo

### Estado Actual
- ✅ Proyecto Laravel 12 inicializado
- ✅ Livewire 4 instalado
- ✅ Estructura básica de vistas (landing, auth, dashboard)
- ✅ Rutas básicas configuradas
- ⚠️ Auth controller creado pero no implementado
- ⚠️ Multi-tenancy no instalado

### Objetivo
Construir un ERP multi-tenant con Clean Architecture, siguiendo los principios documentados en `/docs/`.

### Estrategia Recomendada
**Comenzar con Fase 1 (Infraestructura) → Fase 2 (Auth + Multi-Tenancy) → Fase 3 (Landing)**

---

## 🏗️ Fase 0: Configuración Inicial
**Estado: ✅ COMPLETADA**
**Duración estimada: 1 día**

### Tareas
- [x] Inicializar proyecto Laravel
- [x] Instalar dependencias básicas (Livewire)
- [x] Configurar estructura de directorios
- [x] Configurar Git y repositorio

---

## 🔧 Fase 1: Infraestructura y Core
**Estado: 🔄 EN PROGRESO**
**Duración estimada: 3-5 días**
**Prioridad: ALTA**

### 1.1 Estructura Clean Architecture
**Subtareas:**
- [ ] Crear estructura de directorios Core
  ```
  app/Core/
  ├── Domain/
  │   ├── Shared/
  │   │   ├── ValueObjects/
  │   │   ├── Exceptions/
  │   │   └── Contracts/
  ├── Application/
  │   ├── Shared/
  │   │   ├── DTOs/
  │   │   └── Services/
  └── Infrastructure/
      ├── Shared/
      │   ├── Database/
      │   ├── Cache/
      │   └── Logging/
  ```
- [ ] Crear Value Objects base (Email, Phone, Money)
- [ ] Crear excepciones personalizadas del dominio
- [ ] Configurar Service Providers para Core

### 1.2 Configuración Base de Datos
**Subtareas:**
- [ ] Configurar conexión BD central (landlord)
- [ ] Configurar variables de entorno (.env)
- [ ] Crear archivo de configuración database.php extendido
- [ ] Probar conexiones de BD

### 1.3 Herramientas de Desarrollo
**Subtareas:**
- [ ] Configurar Laravel Pint (code style)
- [ ] Configurar PHPStan (análisis estático)
- [ ] Configurar ESLint para JavaScript
- [ ] Configurar Prettier
- [ ] Configurar Git hooks (Husky ya instalado)

### 1.4 Docker & Entorno Local
**Subtareas:**
- [ ] Revisar y ajustar compose.yaml
- [ ] Configurar servicios necesarios (MySQL, Redis, MailHog)
- [ ] Documentar comandos de desarrollo
- [ ] Crear scripts de inicio rápido

**Entregables:**
- Estructura Core creada
- Base de datos configurada
- Herramientas de desarrollo funcionando
- Docker compose listo

---

## 🔐 Fase 2: Autenticación y Multi-Tenancy
**Estado: ⏳ PENDIENTE**
**Duración estimada: 5-7 días**
**Prioridad: ALTA**

### 2.1 Instalación Multi-Tenancy
**Subtareas:**
- [ ] Instalar paquete `stancl/tenancy`
  ```bash
  composer require stancl/tenancy
  php artisan tenancy:install
  ```
- [ ] Configurar archivo `config/tenancy.php`
- [ ] Ejecutar migraciones de tenancy
- [ ] Configurar identificación de tenants (por subdominio)

### 2.2 Base de Datos Central (Landlord)
**Subtareas:**
- [ ] Crear migración `tenants` table
- [ ] Crear migración `domains` table
- [ ] Crear migración `users` central table
- [ ] Crear migración `plans` table
- [ ] Crear migración `subscriptions` table
- [ ] Crear modelo Tenant con traits necesarios
- [ ] Crear seeders para datos de prueba

### 2.3 Sistema de Autenticación
**Subtareas:**
- [ ] Implementar AuthController completo
  - [ ] Método login (GET + POST)
  - [ ] Método register (GET + POST)
  - [ ] Método logout
  - [ ] Método forgot-password
  - [ ] Método reset-password
- [ ] Crear Form Requests para validación
  - [ ] LoginRequest
  - [ ] RegisterRequest
- [ ] Crear vistas de autenticación con Tailwind
  - [ ] login.blade.php
  - [ ] register.blade.php
  - [ ] forgot-password.blade.php
  - [ ] reset-password.blade.php

### 2.4 Flujo Multi-Tenant Login
**Subtareas:**
- [ ] Crear middleware para detección de tenant
- [ ] Implementar lógica de redirección tenant-aware
- [ ] Crear ruta `/auth/consume` para tokens
- [ ] Configurar sesiones por tenant
- [ ] Configurar cache por tenant
- [ ] Configurar storage por tenant

### 2.5 Testing de Auth
**Subtareas:**
- [ ] Tests unitarios para Value Objects
- [ ] Tests de integración para login
- [ ] Tests de integración para registro
- [ ] Tests de multi-tenancy (aislamiento de datos)

**Entregables:**
- Multi-tenancy funcionando
- Sistema de auth completo
- Tests de autenticación pasando

---

## 🎨 Fase 3: Landing Page
**Estado: 🔄 EN PROGRESO (PARCIAL)**
**Duración estimada: 4-5 días**
**Prioridad: MEDIA**

### 3.1 Layout y Estructura
**Subtareas:**
- [ ] Completar layout principal (`landing/layout/app.blade.php`)
- [ ] Crear componentes compartidos
  - [ ] Navbar con menú responsive
  - [ ] Footer con enlaces y redes sociales
  - [ ] Botones CTA reutilizables
- [ ] Configurar Tailwind CSS con tema personalizado
- [ ] Implementar dark mode (opcional)

### 3.2 Páginas Principales
**Subtareas:**
- [ ] Home page
  - [ ] Hero section con CTA
  - [ ] Features section
  - [ ] Benefits section
  - [ ] Social proof / testimonios
  - [ ] Final CTA
- [ ] Pricing page
  - [ ] Cards de planes
  - [ ] Comparación de features
  - [ ] FAQ de precios
- [ ] Features page (opcional)
- [ ] About page (opcional)
- [ ] Contact page (opcional)

### 3.3 Componentes Interactivos
**Subtareas:**
- [ ] Crear componente Livewire para newsletter
- [ ] Crear componente Livewire para formulario de contacto
- [ ] Animaciones con Alpine.js
- [ ] Scroll animations
- [ ] Mobile menu toggle

### 3.4 SEO y Performance
**Subtareas:**
- [ ] Meta tags dinámicos
- [ ] Open Graph tags
- [ ] Sitemap.xml
- [ ] Optimización de imágenes
- [ ] Lazy loading

**Entregables:**
- Landing page completa y responsive
- Componentes interactivos funcionando
- SEO optimizado

---

## 🖥️ Fase 4: Dashboard Foundation
**Estado: ⏳ PENDIENTE**
**Duración estimada: 5-7 días**
**Prioridad: ALTA**

### 4.1 Layout Dashboard
**Subtareas:**
- [ ] Crear layout principal (`dashboard/layouts/app.blade.php`)
- [ ] Implementar sidebar con navegación
  - [ ] Menú colapsable
  - [ ] Iconos para cada módulo
  - [ ] Indicadores activos
- [ ] Implementar navbar superior
  - [ ] Breadcrumbs
  - [ ] Notificaciones
  - [ ] Perfil de usuario con dropdown
- [ ] Implementar diseño responsive (mobile menu)

### 4.2 Componentes Compartidos
**Subtareas:**
- [ ] Crear componentes Blade reutilizables
  - [ ] Cards
  - [ ] Tables
  - [ ] Forms
  - [ ] Modals
  - [ ] Alerts/Flash messages
  - [ ] Badges
  - [ ] Buttons
- [ ] Crear componentes Livewire compartidos
  - [ ] Modal.php
  - [ ] ConfirmDelete.php
  - [ ] FlashMessage.php
  - [ ] Pagination.php

### 4.3 Dashboard Home
**Subtareas:**
- [ ] Crear página principal del dashboard
- [ ] Implementar widgets de estadísticas
  - [ ] Total productos
  - [ ] Ventas del mes
  - [ ] Stock bajo
  - [ ] Clientes activos
- [ ] Gráficos básicos con Chart.js
- [ ] Lista de actividad reciente

### 4.4 Gestión de Perfil
**Subtareas:**
- [ ] Página de perfil de usuario
- [ ] Editar información personal
- [ ] Cambiar contraseña
- [ ] Subir avatar
- [ ] Configuración de notificaciones

### 4.5 Configuración Tenant
**Subtareas:**
- [ ] Página de configuración de empresa
- [ ] Información de la empresa
- [ ] Logo y branding
- [ ] Gestión de plan y suscripción
- [ ] Límites y uso de recursos

**Entregables:**
- Dashboard layout completo
- Componentes compartidos funcionando
- Home dashboard con widgets
- Perfil y configuración básica

---

## 📦 Fase 5: Módulo Inventario (Primer Módulo ERP)
**Estado: ⏳ PENDIENTE**
**Duración estimada: 10-14 días**
**Prioridad: ALTA**

### 5.1 Estructura del Módulo
**Subtareas:**
- [ ] Crear estructura de directorios según Clean Architecture
  ```
  app/Modules/Inventory/
  ├── Domain/
  │   ├── Models/
  │   ├── ValueObjects/
  │   ├── Repositories/
  │   ├── Events/
  │   └── Services/
  ├── Application/
  │   ├── UseCases/
  │   └── DTOs/
  ├── Infrastructure/
  │   └── Persistence/
  └── Http/
      ├── Controllers/
      └── Requests/
  ```

### 5.2 Base de Datos del Módulo
**Subtareas:**
- [ ] Crear migraciones para tenant database
  - [ ] categories
  - [ ] brands
  - [ ] products
  - [ ] product_variants
  - [ ] warehouses
  - [ ] stock_locations
  - [ ] stock_movements
  - [ ] stock_transfers
  - [ ] stock_transfer_items
  - [ ] suppliers
  - [ ] product_supplier
- [ ] Crear modelos Eloquent
- [ ] Crear factories para testing
- [ ] Crear seeders de ejemplo

### 5.3 Domain Layer
**Subtareas:**
- [ ] Crear Value Objects
  - [ ] SKU.php
  - [ ] Barcode.php
  - [ ] Money.php
  - [ ] StockQuantity.php
- [ ] Crear Domain Events
  - [ ] ProductCreated
  - [ ] ProductUpdated
  - [ ] StockAdjusted
  - [ ] LowStockDetected
- [ ] Crear Domain Services
  - [ ] StockCalculationService
  - [ ] PriceCalculationService
- [ ] Crear Repository Interfaces

### 5.4 Application Layer
**Subtareas:**
- [ ] Crear Use Cases
  - [ ] CreateProductUseCase
  - [ ] UpdateProductUseCase
  - [ ] DeleteProductUseCase
  - [ ] AdjustStockUseCase
  - [ ] TransferStockUseCase
  - [ ] CreateCategoryUseCase
- [ ] Crear DTOs
  - [ ] ProductDTO
  - [ ] CategoryDTO
  - [ ] StockMovementDTO
- [ ] Registrar bindings en Service Provider

### 5.5 Infrastructure Layer
**Subtareas:**
- [ ] Implementar Repository Eloquent
  - [ ] EloquentProductRepository
  - [ ] EloquentCategoryRepository
  - [ ] EloquentStockRepository
- [ ] Crear Event Listeners
  - [ ] SendLowStockNotification
  - [ ] UpdateProductSearchIndex
  - [ ] LogStockMovement

### 5.6 Presentation Layer - Productos
**Subtareas:**
- [ ] Crear ProductController
- [ ] Crear vistas de productos
  - [ ] index.blade.php (listado)
  - [ ] create.blade.php (crear)
  - [ ] edit.blade.php (editar)
  - [ ] show.blade.php (detalle)
- [ ] Crear componentes Livewire
  - [ ] ProductTable.php (tabla con filtros y búsqueda)
  - [ ] ProductForm.php (formulario)
  - [ ] ProductVariantManager.php
  - [ ] StockAdjuster.php
- [ ] Implementar búsqueda y filtrado
- [ ] Implementar paginación

### 5.7 Presentation Layer - Categorías
**Subtareas:**
- [ ] Crear CategoryController
- [ ] Crear vistas de categorías
- [ ] Componente Livewire CategoryTree (árbol jerárquico)
- [ ] CRUD completo de categorías

### 5.8 Presentation Layer - Stock
**Subtareas:**
- [ ] Crear StockController
- [ ] Vista de movimientos de stock
- [ ] Vista de transferencias entre almacenes
- [ ] Reporte de stock actual por almacén
- [ ] Alertas de stock bajo

### 5.9 Testing del Módulo
**Subtareas:**
- [ ] Tests unitarios de Value Objects
- [ ] Tests unitarios de Domain Services
- [ ] Tests de Use Cases
- [ ] Tests de integración de Repository
- [ ] Tests de Feature para endpoints
- [ ] Tests de Livewire components

**Entregables:**
- Módulo Inventario completo
- CRUD de productos funcionando
- Gestión de stock básica
- Tests del módulo pasando

---

## 🚀 Fase 6: Módulos Adicionales
**Estado: ⏳ PENDIENTE**
**Duración estimada: 30-40 días**
**Prioridad: MEDIA-BAJA**

### 6.1 Módulo CRM
**Subtareas:**
- [ ] Estructura del módulo
- [ ] Migraciones (customers, contacts, interactions)
- [ ] Domain layer
- [ ] Application layer
- [ ] Presentation layer
- [ ] Testing

### 6.2 Módulo Ventas
**Subtareas:**
- [ ] Estructura del módulo
- [ ] Migraciones (orders, order_items, invoices)
- [ ] Domain layer
- [ ] Application layer
- [ ] Presentation layer (POS básico)
- [ ] Integración con Inventario
- [ ] Testing

### 6.3 Módulo Contabilidad
**Subtareas:**
- [ ] Estructura del módulo
- [ ] Migraciones (accounts, transactions, journal_entries)
- [ ] Domain layer
- [ ] Application layer
- [ ] Presentation layer
- [ ] Reportes básicos
- [ ] Testing

### 6.4 Módulo RRHH
**Subtareas:**
- [ ] Estructura del módulo
- [ ] Migraciones (employees, departments, payroll)
- [ ] Domain layer
- [ ] Application layer
- [ ] Presentation layer
- [ ] Testing

**Entregables:**
- Módulos adicionales completos
- Integración entre módulos
- Tests pasando

---

## 🧪 Fase 7: Testing y QA
**Estado: ⏳ PENDIENTE**
**Duración estimada: 5-7 días**
**Prioridad: ALTA**

### 7.1 Testing Completo
**Subtareas:**
- [ ] Revisión de cobertura de tests (objetivo: >80%)
- [ ] Tests end-to-end con Laravel Dusk
- [ ] Tests de performance (queries N+1)
- [ ] Tests de seguridad (SQL injection, XSS)
- [ ] Tests de multi-tenancy (aislamiento de datos)

### 7.2 Revisión de Código
**Subtareas:**
- [ ] Code review de todos los módulos
- [ ] Refactoring de código duplicado
- [ ] Optimización de queries
- [ ] Documentación de código (PHPDoc)

### 7.3 Testing Manual
**Subtareas:**
- [ ] Testing de UI/UX en diferentes navegadores
- [ ] Testing responsive en diferentes dispositivos
- [ ] Testing de flujos completos de usuario
- [ ] Identificación y fix de bugs

### 7.4 Documentación
**Subtareas:**
- [ ] Documentación de API
- [ ] Guía de usuario
- [ ] Documentación técnica para desarrolladores
- [ ] README actualizado

**Entregables:**
- Suite de tests completa
- Bugs identificados y resueltos
- Documentación completa

---

## 🌐 Fase 8: Deploy y Producción
**Estado: ⏳ PENDIENTE**
**Duración estimada: 3-5 días**
**Prioridad: ALTA**

### 8.1 Preparación para Deploy
**Subtareas:**
- [ ] Configurar variables de entorno de producción
- [ ] Optimizar assets (build de producción)
- [ ] Configurar cache de aplicación
- [ ] Configurar cache de rutas y configuración
- [ ] Configurar queue workers
- [ ] Configurar scheduled tasks (cron)

### 8.2 Infraestructura
**Subtareas:**
- [ ] Configurar servidor de producción
- [ ] Configurar base de datos de producción
- [ ] Configurar Redis para cache y queues
- [ ] Configurar certificados SSL
- [ ] Configurar backup automático
- [ ] Configurar monitoreo (Laravel Telescope/Horizon)

### 8.3 CI/CD
**Subtareas:**
- [ ] Configurar GitHub Actions para tests
- [ ] Configurar deploy automático
- [ ] Configurar rollback automático en caso de error
- [ ] Configurar notificaciones de deploy

### 8.4 Monitoreo y Logs
**Subtareas:**
- [ ] Configurar logging centralizado
- [ ] Configurar alertas de errores
- [ ] Configurar métricas de performance
- [ ] Configurar uptime monitoring

**Entregables:**
- Aplicación en producción
- CI/CD funcionando
- Monitoreo activo

---

## 📊 Orden Recomendado de Implementación

### Sprint 1 (Semana 1-2): Fundación
1. **Fase 1: Infraestructura y Core** (3-5 días)
2. **Fase 2: Autenticación y Multi-Tenancy** (5-7 días)

### Sprint 2 (Semana 3): Landing
3. **Fase 3: Landing Page** (4-5 días)

### Sprint 3 (Semana 4): Dashboard Base
4. **Fase 4: Dashboard Foundation** (5-7 días)

### Sprint 4-5 (Semana 5-7): Primer Módulo
5. **Fase 5: Módulo Inventario** (10-14 días)

### Sprint 6-10 (Semana 8-15): Expansión
6. **Fase 6: Módulos Adicionales** (30-40 días)

### Sprint 11 (Semana 16): QA
7. **Fase 7: Testing y QA** (5-7 días)

### Sprint 12 (Semana 17): Launch
8. **Fase 8: Deploy y Producción** (3-5 días)

---

## 🎯 Priorización Actual

### HACER AHORA (Prioridad Alta)
1. ✅ **Fase 1.2**: Configuración Base de Datos
2. ✅ **Fase 1.3**: Herramientas de Desarrollo
3. 🔄 **Fase 2.1**: Instalación Multi-Tenancy
4. 🔄 **Fase 2.2**: Base de Datos Central
5. 🔄 **Fase 2.3**: Sistema de Autenticación

### HACER DESPUÉS (Prioridad Media)
6. **Fase 3**: Completar Landing Page
7. **Fase 4**: Dashboard Foundation
8. **Fase 5**: Módulo Inventario

### HACER MÁS ADELANTE (Prioridad Baja)
9. **Fase 6**: Módulos Adicionales
10. **Fase 7**: Testing Completo
11. **Fase 8**: Deploy a Producción

---

## 📝 Notas Importantes

### Decisiones Arquitecturales
- **Multi-Tenancy**: Database por tenant (máximo aislamiento)
- **Arquitectura**: Clean Architecture sin CQRS (evitar sobreingeniería)
- **Frontend**: Livewire 4 + Alpine.js + Tailwind CSS
- **Testing**: PHPUnit + Laravel Dusk

### Dependencias Críticas
- **Fase 2** debe completarse antes de **Fase 4 y 5**
- **Fase 4** debe completarse antes de **Fase 5 y 6**
- **Fase 5** sirve como plantilla para **Fase 6**

### Riesgos Identificados
- Multi-tenancy puede ser complejo (mitigar con documentación y tests)
- Clean Architecture puede tener curva de aprendizaje (mitigar con ejemplos)
- Módulos muy grandes (dividir en sub-fases más pequeñas)

---

## 🤝 Próximos Pasos

1. **Revisar y aprobar este plan**
2. **Crear issues en GitHub para cada fase**
3. **Asignar estimaciones de tiempo**
4. **Comenzar con Fase 1.2 y 1.3**
5. **Setup semanal de revisión de progreso**

---

**Fecha de Creación**: 2026-02-09
**Versión**: 1.0
**Última Actualización**: 2026-02-09
