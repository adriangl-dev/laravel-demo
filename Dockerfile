# Etapa 1: build
FROM php:8.4-fpm-alpine AS build

# Instalar dependencias necesarias para composer y extensiones
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    icu-dev \
    bash \
    shadow \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring bcmath intl gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /srv

# Copiar solo los archivos de Composer primero (para cache)
COPY --chown=www-data:www-data src/. .

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Ajustar permisos
RUN chown -R www-data:www-data /srv/storage /srv/bootstrap/cache

# =========================
# Etapa 2: runtime
FROM php:8.4-fpm-alpine

# Instalar solo extensiones necesarias para correr Laravel
RUN apk add --no-cache \
    libpng-dev libjpeg-turbo-dev freetype-dev icu-dev libpq-dev oniguruma-dev zlib-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring bcmath intl gd

WORKDIR /srv

# Copiar la app ya construida desde la etapa de build
COPY --from=build /srv /srv

# Ajustar usuario
USER www-data

EXPOSE 9000
CMD ["php-fpm"]