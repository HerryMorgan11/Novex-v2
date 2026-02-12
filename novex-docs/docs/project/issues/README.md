# Índice de Issues del Proyecto Novex v2

Este directorio contiene archivos individuales de issues listos para ser copiados a GitHub Issues.

## Estructura

```
docs/issues/
├── fase-1/          # Infraestructura y Core (4 issues) ✅
├── fase-2/          # Auth + Multi-Tenancy (4 issues) ✅
├── fase-3/          # Landing Page (3 issues) ✅
├── fase-4/          # Dashboard Foundation (3 issues) ✅
├── fase-5/          # Módulo Inventario (5 issues) ✅
└── README.md        # Este archivo
```

## Issues Creados

### ✅ Fase 1: Infraestructura y Core (4 issues)

| #   | Issue                                                                                    | Estimación | Prioridad | Labels                                     |
| --- | ---------------------------------------------------------------------------------------- | ---------- | --------- | ------------------------------------------ |
| 1.1 | [Crear Estructura Clean Architecture](fase-1/issue-1.1-estructura-clean-architecture.md) | 3 días     | Alta      | fase-1, infrastructure, clean-architecture |
| 1.2 | [Configuración Base de Datos](fase-1/issue-1.2-configuracion-base-datos.md)              | 1 día      | Alta      | fase-1, database, configuration            |
| 1.3 | [Configurar Herramientas de Desarrollo](fase-1/issue-1.3-herramientas-desarrollo.md)     | 1 día      | Media     | fase-1, devtools, configuration            |
| 1.4 | [Ajustar Docker Compose](fase-1/issue-1.4-docker-compose.md)                             | 1 día      | Media     | fase-1, docker, infrastructure             |

**Total Fase 1:** ~6 días

---

### ✅ Fase 2: Autenticación y Multi-Tenancy (4 issues)

| #   | Issue                                                                                | Estimación | Prioridad | Labels                                         |
| --- | ------------------------------------------------------------------------------------ | ---------- | --------- | ---------------------------------------------- |
| 2.1 | [Instalar y Configurar Multi-Tenancy](fase-2/issue-2.1-instalar-multi-tenancy.md)    | 2 días     | Alta      | fase-2, multi-tenancy, critical                |
| 2.2 | [Crear Migraciones BD Central](fase-2/issue-2.2-migraciones-bd-central.md)           | 2 días     | Alta      | fase-2, database, migrations                   |
| 2.3 | [Implementar Sistema de Autenticación](fase-2/issue-2.3-sistema-autenticacion.md)    | 3 días     | Alta      | fase-2, authentication, frontend, backend      |
| 2.4 | [Implementar Flujo Multi-Tenant Login](fase-2/issue-2.4-flujo-multi-tenant-login.md) | 2 días     | Alta      | fase-2, multi-tenancy, authentication, complex |

**Total Fase 2:** ~9 días

---

### ✅ Fase 3: Landing Page (3 issues)

| #   | Issue                                                                          | Estimación | Prioridad | Labels                             |
| --- | ------------------------------------------------------------------------------ | ---------- | --------- | ---------------------------------- |
| 3.1 | [Layout y Componentes Landing](fase-3/issue-3.1-layout-componentes-landing.md) | 2 días     | Media     | fase-3, landing, frontend, ui-ux   |
| 3.2 | [Implementar Home Page Completa](fase-3/issue-3.2-home-page.md)                | 2 días     | Alta      | fase-3, landing, frontend, content |
| 3.3 | [Implementar Pricing Page](fase-3/issue-3.3-pricing-page.md)                   | 1 día      | Alta      | fase-3, landing, pricing, frontend |

**Total Fase 3:** ~5 días

---

### ✅ Fase 4: Dashboard Foundation (3 issues)

| #   | Issue                                                                              | Estimación | Prioridad | Labels                        |
| --- | ---------------------------------------------------------------------------------- | ---------- | --------- | ----------------------------- |
| 4.1 | [Layout y Navegación del Dashboard](fase-4/issue-4.1-layout-dashboard.md)          | 2-3 días   | Alta      | fase-4, dashboard, frontend   |
| 4.2 | [Componentes Compartidos del Dashboard](fase-4/issue-4.2-componentes-dashboard.md) | 2-3 días   | Alta      | fase-4, dashboard, components |
| 4.3 | [Dashboard Home con Widgets](fase-4/issue-4.3-dashboard-home.md)                   | 2-3 días   | Alta      | fase-4, dashboard, widgets    |

**Total Fase 4:** ~6-9 días

---

### ✅ Fase 5: Módulo Inventario (5 issues)

| #   | Issue                                                                             | Estimación | Prioridad | Labels                                |
| --- | --------------------------------------------------------------------------------- | ---------- | --------- | ------------------------------------- |
| 5.1 | [Estructura Clean Architecture del Módulo](fase-5/issue-5.1-estructura-modulo.md) | 3-4 días   | Alta      | fase-5, inventory, clean-architecture |
| 5.2 | [Migraciones y Modelos Base](fase-5/issue-5.2-migraciones-modelos.md)             | 2-3 días   | Alta      | fase-5, inventory, database           |
| 5.3 | [Domain Layer del Módulo](fase-5/issue-5.3-domain-layer.md)                       | 3-4 días   | Alta      | fase-5, inventory, backend            |
| 5.4 | [Application & Presentation Layer](fase-5/issue-5.4-application-layer.md)         | 3-4 días   | Alta      | fase-5, inventory, backend            |
| 5.5 | [CRUD Productos con Livewire](fase-5/issue-5.5-crud-productos.md)                 | 3-4 días   | Alta      | fase-5, inventory, frontend           |

**Total Fase 5:** ~14-19 días

---

## Cómo Usar Estos Issues

### Método 1: Copiar y Pegar Manualmente

1. Abre el archivo del issue que quieres crear
2. Copia todo el contenido
3. Ve a GitHub Issues: https://github.com/HerryMorgan11/Novex-v2/issues/new
4. Pega el contenido
5. GitHub automáticamente parseará el frontmatter (title, labels, etc.)
6. Haz click en "Submit new issue"

### Método 2: Usar GitHub CLI (Recomendado)

Si tienes `gh` CLI instalado:

```bash
# Instalar GitHub CLI (si no lo tienes)
# macOS: brew install gh
# Ubuntu: sudo apt install gh
# Windows: choco install gh

# Autenticarte
gh auth login

# Crear issues desde el directorio raíz
cd /ruta/al/proyecto

# Crear un issue individual
gh issue create --title "[Fase 1.1] Crear Estructura Clean Architecture" \
                --body-file docs/issues/fase-1/issue-1.1-estructura-clean-architecture.md \
                --label "fase-1,infrastructure,clean-architecture,priority-high"

# O usar el script auxiliar (ver abajo)
./docs/issues/create-issues.sh fase-1
```

### Método 3: Usar el Script Auxiliar

Hemos creado un script bash que facilita la creación de todos los issues de una fase:

```bash
# Hacer el script ejecutable
chmod +x docs/issues/create-issues.sh

# Crear todos los issues de Fase 1
./docs/issues/create-issues.sh fase-1

# Crear todos los issues de Fase 2
./docs/issues/create-issues.sh fase-2

# Crear todos los issues de Fase 3
./docs/issues/create-issues.sh fase-3

# Crear TODOS los issues
./docs/issues/create-issues.sh all
```

---

## Sistema de Labels

### Por Fase

- `fase-1` - Infraestructura y Core
- `fase-2` - Auth + Multi-Tenancy
- `fase-3` - Landing Page
- `fase-4` - Dashboard Foundation
- `fase-5` - Módulo Inventario

### Por Área

- `frontend` - Trabajo de frontend
- `backend` - Trabajo de backend
- `database` - Base de datos
- `testing` - Tests
- `documentation` - Documentación

### Por Prioridad

- `priority-high` - Prioridad alta
- `priority-medium` - Prioridad media
- `priority-low` - Prioridad baja
- `critical` - Crítico/bloqueante

### Por Tecnología

- `livewire` - Componentes Livewire
- `tailwind` - Estilos Tailwind
- `clean-architecture` - Clean Architecture
- `multi-tenancy` - Multi-tenancy

### Por Tipo

- `infrastructure` - Infraestructura
- `configuration` - Configuración
- `authentication` - Autenticación
- `ui-ux` - Diseño UI/UX

---

## Estructura de un Issue

Cada archivo de issue contiene:

### 1. Frontmatter (YAML)

```yaml
---
title: '[Fase X.Y] Título del Issue'
labels: fase-x, area, priority
assignees:
milestone: Fase X - Nombre
---
```

### 2. Descripción

Breve descripción del objetivo del issue

### 3. Objetivos

Lista de tareas específicas a completar

### 4. Implementación

Código de ejemplo y guías de implementación

### 5. Criterios de Aceptación

Lista verificable de lo que debe estar completo

### 6. Testing

Ejemplos de tests a crear

### 7. Referencias

Links a documentación relevante

### 8. Metadata

- **Estimación**: Tiempo estimado
- **Dependencias**: Issues que deben completarse primero
- **Notas**: Información adicional importante

---

## Orden Recomendado de Creación

### Sprint 1 (Semana 1-2)

1. Crear todos los issues de **Fase 1** (1.1 - 1.4)
2. Crear todos los issues de **Fase 2** (2.1 - 2.4)

### Sprint 2 (Semana 3)

3. Crear todos los issues de **Fase 3** (3.1 - 3.3)

### Sprint 3 (Semana 4+)

4. Crear issues de **Fase 4** cuando estés listo para trabajar en dashboard
5. Crear issues de **Fase 5** cuando estés listo para el módulo inventario

---

## Tips

1. **Asigna issues a ti mismo** después de crearlos en GitHub
2. **Usa milestones** para agrupar issues por fase
3. **Actualiza el progreso** marcando checkboxes en el issue
4. **Cierra issues** cuando estén completados
5. **Linkea PRs** a los issues correspondientes
6. **Comenta en issues** para tracking de progreso

---

## Links Útiles

- [Documentación del Proyecto](../phases.md)
- [Roadmap General](../roadmap.md)
- [Quick Start Guide](../../getting-started/quick-start.md)
- [Templates Completos](./templates.md)

---

## FAQ

**P: ¿Puedo modificar los issues después de crearlos?**
R: Sí, estos son plantillas. Modifica según necesites.

**P: ¿Debo crear todos los issues de una vez?**
R: No, recomendamos crear por fases según vayas avanzando.

**P: ¿Los issues están en orden de dependencia?**
R: Sí, cada issue lista sus dependencias en la sección correspondiente.

**P: ¿Puedo trabajar issues en paralelo?**
R: Sí, si no tienen dependencias entre sí. Revisa la sección "Dependencias".

---

**Última actualización:** 2026-02-09
