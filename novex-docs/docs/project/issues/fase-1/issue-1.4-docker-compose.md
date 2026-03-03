---
title: '[Fase 1.4] Ajustar Docker Compose'
labels: fase-1, docker, infrastructure, priority-medium
assignees:
milestone: Fase 1 - Infraestructura y Core
---

## Tarea: Ajustar Docker Compose

### Descripción

Revisar y optimizar la configuración de Docker Compose para el entorno de desarrollo local

### Objetivos

- [ ] Revisar archivo `compose.yaml` existente
- [ ] Agregar servicios necesarios (Redis, MailHog)
- [ ] Configurar volúmenes correctamente
- [ ] Optimizar configuración para desarrollo
- [ ] Documentar comandos de Docker
- [ ] Crear scripts de inicio rápido

### Servicios Requeridos

#### Servicios Actuales a Verificar

- Laravel App (PHP 8.2)
- MySQL 8
- phpMyAdmin (opcional)

#### Servicios a Agregar

- Redis (para cache y queues)
- MailHog (para testing de emails)

### Configuración Sugerida

```yaml
services:
    app:
        build:
            context: .
            dockerfile: Dockerfile
        ports:
            - '8000:8000'
        volumes:
            - .:/var/www/html
        environment:
            - DB_HOST=mysql
            - REDIS_HOST=redis
            - MAIL_HOST=mailhog
        depends_on:
            - mysql
            - redis

    mysql:
        image: mysql:8
        ports:
            - '3306:3306'
        environment:
            MYSQL_ROOT_PASSWORD: root
            MYSQL_DATABASE: novex_central
        volumes:
            - mysql_data:/var/lib/mysql

    redis:
        image: redis:alpine
        ports:
            - '6379:6379'
        volumes:
            - redis_data:/data

    mailhog:
        image: mailhog/mailhog
        ports:
            - '1025:1025'
            - '8025:8025'

volumes:
    mysql_data:
    redis_data:
```

### Scripts de Inicio Rápido

Crear `scripts/dev-setup.sh`:

```bash
#!/bin/bash

echo " Iniciando entorno de desarrollo..."

# Iniciar servicios
docker-compose up -d

# Esperar a que MySQL esté listo
echo " Esperando a MySQL..."
sleep 10

# Instalar dependencias
docker-compose exec app composer install
docker-compose exec app npm install

# Copiar .env si no existe
if [ ! -f .env ]; then
    cp .env.example .env
    docker-compose exec app php artisan key:generate
fi

# Ejecutar migraciones
docker-compose exec app php artisan migrate

echo " Entorno listo!"
echo " App: http://localhost:8000"
echo " MailHog: http://localhost:8025"
```

### Comandos Útiles a Documentar

```bash
# Iniciar servicios
docker-compose up -d

# Ver logs
docker-compose logs -f

# Detener servicios
docker-compose down

# Reiniciar servicio específico
docker-compose restart app

# Ejecutar comandos en contenedor
docker-compose exec app php artisan migrate
docker-compose exec app composer install

# Acceder a shell
docker-compose exec app bash

# Ver estado de servicios
docker-compose ps
```

### Criterios de Aceptación

- [ ] `compose.yaml` optimizado y funcionando
- [ ] Todos los servicios necesarios incluidos
- [ ] Volúmenes configurados correctamente
- [ ] Script de inicio rápido creado y probado
- [ ] README actualizado con comandos Docker
- [ ] Documentación de troubleshooting básico
- [ ] Variables de entorno configuradas correctamente

### Documentación README

Agregar sección:

````markdown
## 🐳 Docker Development

### Quick Start

```bash
# Primera vez
./scripts/dev-setup.sh

# Días siguientes
docker-compose up -d
```
````

### Services

- App: http://localhost:8000
- MySQL: localhost:3306
- Redis: localhost:6379
- MailHog: http://localhost:8025

### Common Commands

```bash
# Start
docker-compose up -d

# Stop
docker-compose down

# Logs
docker-compose logs -f app

# Execute commands
docker-compose exec app php artisan migrate
```

```

### Referencias
- Docker Compose Documentation
- Laravel Sail (similar setup)
- `/docs/QUICK_START.md`

### Estimación
**1 día**

### Dependencias
- Issue 1.2 (Configuración BD) debe estar completada

### Notas
Si el proyecto ya usa Laravel Sail, adaptar esta configuración para aprovechar Sail en lugar de Docker Compose manual.
```
