# 🚨 IMPORTANTE: Cómo Ver las Issues en GitHub

## ⚠️ Las Issues NO están creadas todavía

Los archivos de issues están en el repositorio (`docs/issues/`), pero **NO están creados en GitHub todavía**.

Necesitas crearlas manualmente. Aquí te explico cómo de la forma MÁS SIMPLE:

---

## 📝 Método 1: Crear Issues Manualmente (MÁS FÁCIL)

### Paso 1: Abre un archivo de issue

Ve a la carpeta `docs/issues/fase-1/` y abre cualquier archivo, por ejemplo:
- `issue-1.1-estructura-clean-architecture.md`

### Paso 2: Copia TODO el contenido del archivo

Abre el archivo en tu editor o en GitHub y copia TODO el texto (desde la primera línea hasta la última).

### Paso 3: Ve a GitHub Issues

Abre tu navegador y ve a:
```
https://github.com/HerryMorgan11/Novex-v2/issues/new
```

O haz click aquí: [Crear Nueva Issue](https://github.com/HerryMorgan11/Novex-v2/issues/new)

### Paso 4: Pega el contenido

Pega TODO el contenido que copiaste en el cuadro de texto grande.

**GitHub automáticamente detectará:**
- El título (desde `title:`)
- Los labels (desde `labels:`)
- El contenido del issue

### Paso 5: Click en "Submit new issue"

¡Listo! Ahora verás tu issue en la pestaña Issues de GitHub.

### Paso 6: Repite para cada issue

Repite los pasos 1-5 para cada archivo `.md` en:
- `docs/issues/fase-1/` (4 issues)
- `docs/issues/fase-2/` (4 issues)
- `docs/issues/fase-3/` (3 issues)

---

## 🤖 Método 2: Usar el Script Automatizado (Avanzado)

Si prefieres crear todas las issues de una vez:

### Requisito: GitHub CLI

Primero instala GitHub CLI:
```bash
# macOS
brew install gh

# Ubuntu/Linux
sudo apt install gh

# Windows
choco install gh
```

### Autenticarse

```bash
gh auth login
```

Sigue las instrucciones en pantalla.

### Crear todas las issues de Fase 1

```bash
cd /ruta/al/proyecto
./docs/issues/create-issues.sh fase-1
```

O crear TODAS a la vez:
```bash
./docs/issues/create-issues.sh all
```

---

## 📋 Lista de Issues a Crear

### Fase 1: Infraestructura (4 issues)
1. ✅ `docs/issues/fase-1/issue-1.1-estructura-clean-architecture.md`
2. ✅ `docs/issues/fase-1/issue-1.2-configuracion-base-datos.md`
3. ✅ `docs/issues/fase-1/issue-1.3-herramientas-desarrollo.md`
4. ✅ `docs/issues/fase-1/issue-1.4-docker-compose.md`

### Fase 2: Auth + Multi-Tenancy (4 issues)
1. ✅ `docs/issues/fase-2/issue-2.1-instalar-multi-tenancy.md`
2. ✅ `docs/issues/fase-2/issue-2.2-migraciones-bd-central.md`
3. ✅ `docs/issues/fase-2/issue-2.3-sistema-autenticacion.md`
4. ✅ `docs/issues/fase-2/issue-2.4-flujo-multi-tenant-login.md`

### Fase 3: Landing Page (3 issues)
1. ✅ `docs/issues/fase-3/issue-3.1-layout-componentes-landing.md`
2. ✅ `docs/issues/fase-3/issue-3.2-home-page.md`
3. ✅ `docs/issues/fase-3/issue-3.3-pricing-page.md`

---

## ❓ Preguntas Frecuentes

**P: ¿Por qué no veo las issues en GitHub?**
R: Porque solo existen como archivos `.md` en el repo. Necesitas crearlas siguiendo los pasos de arriba.

**P: ¿Debo crear las 11 issues de una vez?**
R: No necesariamente. Puedes empezar con las de Fase 1 (4 issues) y crear las demás cuando las necesites.

**P: ¿El método manual es seguro?**
R: Sí, copiar y pegar es totalmente seguro y no puede romper nada.

**P: ¿Puedo modificar las issues después de crearlas?**
R: Sí, puedes editar, agregar comentarios, cerrar, etc. Las issues son tuyas.

---

## 🎯 Recomendación

**Empieza con el Método 1 (Manual)** para crear las primeras 4 issues de Fase 1:
1. Abre `docs/issues/fase-1/issue-1.1-estructura-clean-architecture.md`
2. Copia TODO
3. Ve a https://github.com/HerryMorgan11/Novex-v2/issues/new
4. Pega
5. Click "Submit new issue"

¡Es súper fácil! Solo toma 30 segundos por issue.

---

## 📞 ¿Necesitas más ayuda?

Lee la guía completa en: `docs/COMO_CREAR_ISSUES.md`

---

**¡Después de crear las issues, las verás en GitHub!** 🎉
