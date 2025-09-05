# Etapa 1: build
FROM php:8.4-fpm-alpine AS build

# Variables de entorno para Laravel
ENV APP_URL=https://laravel-app-latest-2.onrender.com

# Instalar dependencias necesarias para compilar la app
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
    nodejs \
    npm
    
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring bcmath intl gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /srv

# Copiar solo los archivos de Composer primero (para cache)
COPY --chown=www-data:www-data src/. .

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

# Limpiar caches de Laravel
RUN php artisan config:clear \
    && php artisan cache:clear \
    && php artisan route:clear \
    && php artisan view:clear

# =========================
# Etapa 2: runtime
FROM php:8.4-fpm-alpine

# Instalar solo extensiones necesarias para correr Laravel
RUN apk add --no-cache \
    libpng-dev libjpeg-turbo-dev freetype-dev icu-dev libpq-dev oniguruma-dev zlib-dev caddy \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring bcmath intl gd

WORKDIR /srv

# Copiar la app ya construida desde la etapa de build
COPY --from=build /srv /srv

# Copiar configuración de Caddy
COPY Caddyfile /etc/caddy/Caddyfile

# Ajustar usuario
USER www-data

EXPOSE 80

CMD ["sh", "-c", "php-fpm -F & exec caddy run --config /etc/caddy/Caddyfile --adapter caddyfile"]