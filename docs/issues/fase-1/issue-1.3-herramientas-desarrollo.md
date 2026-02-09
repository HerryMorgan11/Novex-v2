---
title: "[Fase 1.3] Configurar Herramientas de Desarrollo"
labels: fase-1, devtools, configuration, priority-medium
assignees: 
milestone: Fase 1 - Infraestructura y Core
---

## 🛠️ Tarea: Configurar Herramientas de Desarrollo

### Descripción
Configurar y validar herramientas de análisis de código y formateo para mantener calidad del código

### Objetivos
- [ ] Configurar Laravel Pint (PHP formatting)
- [ ] Configurar PHPStan (análisis estático)
- [ ] Configurar ESLint (JavaScript)
- [ ] Configurar Prettier (JavaScript/CSS)
- [ ] Configurar Git hooks con Husky
- [ ] Agregar scripts a `package.json`
- [ ] Documentar uso de herramientas en README

### Herramientas a Configurar

#### 1. Laravel Pint (Ya instalado)
Verificar archivo `pint.json`:
```json
{
    "preset": "laravel",
    "rules": {
        "simplified_null_return": true,
        "braces": false,
        "new_with_braces": true
    }
}
```

#### 2. PHPStan (Ya instalado)
Verificar archivo `phpstan.neon.dist`:
```neon
parameters:
    level: 5
    paths:
        - app
        - tests
    excludePaths:
        - app/Core/Infrastructure/Persistence/Eloquent
```

#### 3. ESLint
Verificar `eslint.config.js`:
```javascript
export default [
    {
        files: ['resources/js/**/*.js'],
        rules: {
            'no-console': 'warn',
            'no-unused-vars': 'error'
        }
    }
];
```

#### 4. Prettier
Verificar `.prettierrc`:
```json
{
    "trailingComma": "es5",
    "tabWidth": 4,
    "semi": true,
    "singleQuote": true
}
```

#### 5. Husky (Ya instalado)
Configurar pre-commit hook:
```bash
#!/bin/sh
. "$(dirname "$0")/_/husky.sh"

npm run lint
./vendor/bin/pint
```

### Scripts en package.json
Agregar estos scripts:
```json
{
  "scripts": {
    "lint": "eslint resources/js",
    "lint:fix": "eslint resources/js --fix",
    "format": "prettier --write 'resources/**/*.{js,css,vue}'",
    "format:check": "prettier --check 'resources/**/*.{js,css,vue}'",
    "pint": "./vendor/bin/pint",
    "phpstan": "./vendor/bin/phpstan analyse",
    "quality": "npm run lint && npm run pint && npm run phpstan"
  }
}
```

### Comandos a Validar
```bash
# PHP
./vendor/bin/pint
./vendor/bin/phpstan analyse

# JavaScript
npm run lint
npm run format

# Git hooks
npm run prepare

# Todo junto
npm run quality
```

### Criterios de Aceptación
- [ ] Laravel Pint ejecutando sin errores
- [ ] PHPStan ejecutando con nivel 5
- [ ] ESLint ejecutando sin errores
- [ ] Prettier formateando correctamente
- [ ] Git hooks funcionando (pre-commit)
- [ ] Scripts en `package.json` documentados
- [ ] README actualizado con guía de herramientas
- [ ] CI/CD puede ejecutar estas herramientas

### Documentación README
Agregar sección en README:
```markdown
## 🔧 Code Quality

### PHP
```bash
# Formatear código
./vendor/bin/pint

# Análisis estático
./vendor/bin/phpstan analyse
```

### JavaScript
```bash
# Lint
npm run lint

# Format
npm run format
```
```

### Referencias
- Laravel Pint: https://laravel.com/docs/pint
- PHPStan: https://phpstan.org/
- ESLint: https://eslint.org/
- Prettier: https://prettier.io/

### Estimación
**1 día**

### Dependencias
Ninguna

### Notas
Estas herramientas ayudarán a mantener un código limpio y consistente a lo largo del proyecto. Configura tu IDE para usar estas herramientas automáticamente.
