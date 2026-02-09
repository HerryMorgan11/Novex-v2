# 🎫 Cómo Crear Issues en GitHub - Guía Rápida

## 📋 Resumen

Ahora tienes **11 issues individuales** listos para crear en GitHub, organizados en 3 fases:

- ✅ **Fase 1**: 4 issues (Infraestructura y Core)
- ✅ **Fase 2**: 4 issues (Auth + Multi-Tenancy)  
- ✅ **Fase 3**: 3 issues (Landing Page)

---

## 🚀 Opción 1: Script Automatizado (RECOMENDADO)

### Prerequisitos
```bash
# Instalar GitHub CLI
# macOS
brew install gh

# Ubuntu/Debian
sudo apt install gh

# Windows
choco install gh

# Autenticarse
gh auth login
```

### Uso del Script

```bash
# Navegar al proyecto
cd /ruta/al/proyecto

# Dar permisos de ejecución (solo primera vez)
chmod +x docs/issues/create-issues.sh

# Ver issues disponibles
./docs/issues/create-issues.sh list

# Crear issues de Fase 1
./docs/issues/create-issues.sh fase-1

# Crear issues de Fase 2
./docs/issues/create-issues.sh fase-2

# Crear issues de Fase 3
./docs/issues/create-issues.sh fase-3

# Crear TODOS los issues a la vez
./docs/issues/create-issues.sh all
```

### Ejemplo de Output
```
ℹ Creando issues de fase-1...

ℹ Creando issue: [Fase 1.1] Crear Estructura Clean Architecture
✓ Issue creado: [Fase 1.1] Crear Estructura Clean Architecture

ℹ Creando issue: [Fase 1.2] Configuración Base de Datos
✓ Issue creado: [Fase 1.2] Configuración Base de Datos

ℹ Creando issue: [Fase 1.3] Configurar Herramientas de Desarrollo
✓ Issue creado: [Fase 1.3] Configurar Herramientas de Desarrollo

ℹ Creando issue: [Fase 1.4] Ajustar Docker Compose
✓ Issue creado: [Fase 1.4] Ajustar Docker Compose

✓ 4 de 4 issues creados exitosamente en fase-1

✓ ¡Listo! Revisa los issues en GitHub:
https://github.com/HerryMorgan11/Novex-v2/issues
```

---

## 📝 Opción 2: Manual (GitHub Web Interface)

Si prefieres crear los issues manualmente:

### Paso 1: Abre un archivo de issue
```bash
# Ejemplo: Abrir issue 1.1
cat docs/issues/fase-1/issue-1.1-estructura-clean-architecture.md
```

### Paso 2: Copia el contenido completo

### Paso 3: Crea el issue en GitHub
1. Ve a: https://github.com/HerryMorgan11/Novex-v2/issues/new
2. Pega el contenido completo
3. GitHub parseará automáticamente:
   - Título (desde el frontmatter)
   - Labels (desde el frontmatter)
   - Cuerpo del issue
4. Click en "Submit new issue"

### Paso 4: Repite para cada issue
Repite el proceso para los 11 issues.

---

## 🎯 Opción 3: GitHub CLI Manual

Si quieres más control:

```bash
# Crear un issue específico
gh issue create \
  --title "[Fase 1.1] Crear Estructura Clean Architecture" \
  --body-file docs/issues/fase-1/issue-1.1-estructura-clean-architecture.md \
  --label "fase-1,infrastructure,clean-architecture,priority-high"

# Asignar a ti mismo
gh issue create \
  --title "[Fase 1.1] Crear Estructura Clean Architecture" \
  --body-file docs/issues/fase-1/issue-1.1-estructura-clean-architecture.md \
  --label "fase-1,infrastructure,clean-architecture,priority-high" \
  --assignee @me

# Con milestone
gh issue create \
  --title "[Fase 1.1] Crear Estructura Clean Architecture" \
  --body-file docs/issues/fase-1/issue-1.1-estructura-clean-architecture.md \
  --label "fase-1,infrastructure,clean-architecture,priority-high" \
  --milestone "Fase 1 - Infraestructura"
```

---

## 📊 Lista de Issues a Crear

### Fase 1: Infraestructura y Core

| # | Archivo | Título |
|---|---------|--------|
| 1.1 | `issue-1.1-estructura-clean-architecture.md` | Crear Estructura Clean Architecture |
| 1.2 | `issue-1.2-configuracion-base-datos.md` | Configuración Base de Datos |
| 1.3 | `issue-1.3-herramientas-desarrollo.md` | Configurar Herramientas de Desarrollo |
| 1.4 | `issue-1.4-docker-compose.md` | Ajustar Docker Compose |

### Fase 2: Auth + Multi-Tenancy

| # | Archivo | Título |
|---|---------|--------|
| 2.1 | `issue-2.1-instalar-multi-tenancy.md` | Instalar y Configurar Multi-Tenancy |
| 2.2 | `issue-2.2-migraciones-bd-central.md` | Crear Migraciones BD Central |
| 2.3 | `issue-2.3-sistema-autenticacion.md` | Implementar Sistema de Autenticación |
| 2.4 | `issue-2.4-flujo-multi-tenant-login.md` | Implementar Flujo Multi-Tenant Login |

### Fase 3: Landing Page

| # | Archivo | Título |
|---|---------|--------|
| 3.1 | `issue-3.1-layout-componentes-landing.md` | Layout y Componentes Landing |
| 3.2 | `issue-3.2-home-page.md` | Implementar Home Page Completa |
| 3.3 | `issue-3.3-pricing-page.md` | Implementar Pricing Page |

---

## ✅ Después de Crear los Issues

### 1. Organizar con Milestones

Crea milestones en GitHub:
- Milestone: "Fase 1 - Infraestructura y Core"
- Milestone: "Fase 2 - Auth + Multi-Tenancy"
- Milestone: "Fase 3 - Landing Page"

Asigna cada issue a su milestone correspondiente.

### 2. Asignar Issues

Asígnate los issues en los que vas a trabajar:
```bash
gh issue edit <issue-number> --add-assignee @me
```

### 3. Crear Project Board (Opcional)

Crea un project board para tracking visual:
1. Ve a Projects en GitHub
2. Crea nuevo project "Novex v2 Development"
3. Agrega columnas: Backlog, In Progress, Review, Done
4. Arrastra issues a las columnas apropiadas

### 4. Comenzar a Trabajar

Sigue el orden recomendado:
1. Comienza con Fase 1 (issues 1.1 - 1.4)
2. Continúa con Fase 2 (issues 2.1 - 2.4)
3. Finaliza con Fase 3 (issues 3.1 - 3.3)

---

## 🔗 Links Útiles

- **Issues en GitHub**: https://github.com/HerryMorgan11/Novex-v2/issues
- **Documentación**: [../PROJECT_PHASES.md](../PROJECT_PHASES.md)
- **Roadmap**: [../ROADMAP.md](../ROADMAP.md)
- **Quick Start**: [../QUICK_START.md](../QUICK_START.md)

---

## 💡 Tips

1. **Crea issues por fase**: No crees todos de una vez, hazlo por fases
2. **Usa el script**: Es más rápido y evita errores
3. **Revisa antes**: Lee cada issue antes de crearlo
4. **Personaliza si necesitas**: Modifica los archivos según tu necesidad
5. **Actualiza progreso**: Marca checkboxes conforme avanzas

---

## ❓ FAQ

**P: ¿Debo crear los 11 issues de una vez?**
R: No es necesario. Recomendamos crear por fase según avances.

**P: ¿Puedo modificar los issues después de crearlos?**
R: Sí, puedes editar título, descripción, labels, etc. en GitHub.

**P: ¿El script crea duplicados si lo ejecuto dos veces?**
R: Sí, creará issues nuevos cada vez. Ten cuidado.

**P: ¿Necesito GitHub CLI obligatoriamente?**
R: No, puedes crear manualmente desde la web interface.

**P: ¿Los labels se crean automáticamente?**
R: Solo si ya existen en el repo. Si no, GitHub te pedirá crearlos.

---

## 🎉 ¡Listo!

Ahora tienes todo lo necesario para crear tus issues y comenzar a trabajar en el proyecto de forma organizada.

**Siguiente paso**: Ejecuta el script o crea los issues manualmente, y ¡comienza a desarrollar!

```bash
./docs/issues/create-issues.sh fase-1
```

---

**Fecha:** 2026-02-09  
**Versión:** 1.0
