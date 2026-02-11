#!/bin/bash
set -e

echo "Configurando entorno de desarrollo Novex-v2..."

   # 1. Instalar dependencias PHP
   echo "Instalando dependencias PHP con Composer..."
   composer install

   # 2. Instalar dependencias JavaScript
   echo "Instalando dependencias JavaScript con npm..."
   npm install

   # 3. Copiar .env.example a .env si no existe
   echo "Configurando .env..."
   if [ ! -f .env ]; then
       cp .env.example .env
       echo "✓ .env creado desde .env.example"
   else
       echo "✓ .env ya existe"
   fi

   # 4. Levantar contenedores
   echo "Levantando contenedores con Sail..."
   ./vendor/bin/sail up -d

   # 5. Esperar a que MySQL esté disponible
   echo "Esperando a que MySQL esté listo..."
   for i in {1..30}; do
       if ./vendor/bin/sail exec -T mysql mysqladmin ping -ppassword --silent 2>/dev/null; then
           echo "✓ MySQL disponible"
           break
       fi
       echo -n "."
       sleep 2
   done

   # 6. Generar APP_KEY
   echo "Generando APP_KEY..."
   ./vendor/bin/sail artisan key:generate

   # 7. Ejecutar migraciones
   echo "Ejecutando migraciones..."
   ./vendor/bin/sail artisan migrate

   # 8. Información final
   echo ""
   echo "¡Entorno listo!"
   echo ""
   echo " URL de la aplicación: http://localhost"
   echo ""
   echo "Comandos útiles:"
   echo "  ./vendor/bin/sail up -d       # Levantar entorno"
   echo "  ./vendor/bin/sail down        # Detener entorno"
   echo "  ./vendor/bin/sail bash        # Acceder al contenedor"
   echo "  ./vendor/bin/sail artisan ... # Ejecutar comandos Artisan"
   echo ""