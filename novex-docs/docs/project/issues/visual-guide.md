# Guía Visual: Cómo Crear una Issue en GitHub

## Objetivo

Convertir los archivos `.md` en Issues reales de GitHub

---

## Paso a Paso (5 minutos)

### 1️Abre un archivo de issue

En tu computadora, ve a la carpeta del proyecto:

```
/docs/issues/fase-1/issue-1.1-estructura-clean-architecture.md
```

O abre el archivo directamente en GitHub:

```
https://github.com/HerryMorgan11/Novex-v2/blob/copilot/add-project-phases-issues/docs/issues/fase-1/issue-1.1-estructura-clean-architecture.md
```

---

### 2️Copia TODO el contenido

Selecciona TODO el texto del archivo (Ctrl+A o Cmd+A) y copia (Ctrl+C o Cmd+C).

El archivo empieza así:

```markdown
---
title: '[Fase 1.1] Crear Estructura Clean Architecture'
labels: fase-1, infrastructure, clean-architecture, priority-high
assignees:
milestone: Fase 1 - Infraestructura y Core
---

## Tarea: Crear Estructura Clean Architecture

...
```

**IMPORTANTE:** Copia DESDE la primera línea (`---`) hasta la última línea del archivo.

---

### 3️Ve a crear una nueva issue

Abre tu navegador y ve a esta URL:

```
https://github.com/HerryMorgan11/Novex-v2/issues/new
```

Verás una página con:

- Un campo "Add a title"
- Un cuadro de texto grande para el contenido
- Botones para agregar labels, assignees, etc.

---

### 4️Pega el contenido

Haz click en el cuadro de texto grande y pega (Ctrl+V o Cmd+V).

** GitHub hace magia:**

- El campo "title" se llena automáticamente con `[Fase 1.1] Crear Estructura Clean Architecture`
- Los labels aparecen automáticamente (`fase-1`, `infrastructure`, etc.)
- El contenido se formatea correctamente

---

### 5️Click en "Submit new issue"

Haz click en el botón verde "Submit new issue" abajo a la derecha.

** ¡Listo!** Ahora verás tu issue en:

```
https://github.com/HerryMorgan11/Novex-v2/issues
```

---

## Repetir para más issues

Para crear la segunda issue:

1. Abre `docs/issues/fase-1/issue-1.2-configuracion-base-datos.md`
2. Copia TODO
3. Ve a https://github.com/HerryMorgan11/Novex-v2/issues/new
4. Pega
5. Submit

Y así sucesivamente...

---

## ¿Cuántas issues crear?

**Recomendación:**

- Empieza con **Fase 1** (4 issues) - Son las más urgentes
- Luego **Fase 2** (4 issues) - Cuando estés listo
- Finalmente **Fase 3** (3 issues) - Para landing page

**Total:** 11 issues disponibles

---

## Ejemplo Real

Imagina que estás en GitHub:

```
┌─────────────────────────────────────────────────────┐
│ GitHub Issues                                        │
├─────────────────────────────────────────────────────┤
│                                                      │
│  [New issue]  (← click aquí)                        │
│                                                      │
│  ┌──────────────────────────────────────────────┐  │
│  │ Add a title                                   │  │
│  │ [Fase 1.1] Crear Estructura Clean Architect... │  │ (← se llena solo)
│  └──────────────────────────────────────────────┘  │
│                                                      │
│  ┌──────────────────────────────────────────────┐  │
│  │ Add a description                             │  │
│  │                                               │  │
│  │ ## Tarea: Crear Estructura Clean Archit...│  │ (← pegas aquí)
│  │                                               │  │
│  │ ### Descripción                               │  │
│  │ Implementar la estructura de directorios...  │  │
│  │                                               │  │
│  └──────────────────────────────────────────────┘  │
│                                                      │
│  Labels: fase-1, infrastructure, priority-high      │ (← se agregan solos)
│                                                      │
│  [Submit new issue]  (← click para crear)           │
│                                                      │
└─────────────────────────────────────────────────────┘
```

---

## ✅ Verificar que funcionó

Después de crear la issue, ve a:

```
https://github.com/HerryMorgan11/Novex-v2/issues
```

Deberías ver tu issue con:

- ✅ Título correcto
- ✅ Labels de colores
- ✅ Contenido formateado
- ✅ Estado "Open"

---

## Problemas Comunes

### ❌ "No veo las issues"

**Solución:** Verifica que estás en la pestaña "Issues" del repositorio y que las creaste.

### ❌ "El título no se llena automáticamente"

**Solución:** Asegúrate de copiar DESDE la primera línea (`---`) del archivo.

### ❌ "Los labels no aparecen"

**Solución:** GitHub los agregará cuando presiones Submit. Si no existen, te pedirá crearlos.

---

## Tip Pro

Si vas a crear muchas issues, usa el script automatizado:

```bash
./docs/issues/create-issues.sh fase-1
```

Pero necesitas instalar GitHub CLI primero (ver read-me-issues.md).

---

## Más Ayuda

- **Guía completa:** [./read-me-issues.md](./read-me-issues.md)
- **Guía detallada:** [./creating-issues.md](./creating-issues.md)
- **Script:** [./create-issues.sh](./create-issues.sh)

---

**¡Es súper fácil! Solo copiar y pegar.**
