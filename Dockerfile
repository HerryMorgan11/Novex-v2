# --- ETAPA 1: Construcción y Dependencias ---
FROM php:8.4-fpm-alpine as build

# Argumentos para Flux UI (se pasan desde GitHub Actions)
ARG FLUX_USER
ARG FLUX_KEY

# Instalar dependencias del sistema y extensiones PHP
RUN apk add --no-cache \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    icu-dev \
    $PHPIZE_DEPS

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql zip gd mbstring intl bcmath pcntl

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

# Configurar credenciales de Flux e instalar dependencias de producción
RUN composer config http-basic.composer.fluxui.dev ${FLUX_USER} ${FLUX_KEY} \
    && composer install --no-dev --optimize-autoloader --no-interaction

# --- ETAPA 2: Imagen de Producción Final ---
FROM php:8.4-fpm-alpine

# Instalar solo librerías de tiempo de ejecución
RUN apk add --no-cache \
    libzip \
    libpng \
    libjpeg-turbo \
    freetype \
    oniguruma \
    icu-libs

# Copiar extensiones y binarios desde la etapa de build
COPY --from=build /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=build /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d
COPY --from=build /var/www /var/www

WORKDIR /var/www

# Ajustar permisos para Amazon Linux (ec2-user / www-data)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]