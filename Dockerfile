# Set master image
FROM php:8.4-fpm-alpine

# Instalar dependencias del sistema necesarias para extensiones PHP y composer
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
    shadow

# Instalar extensiones PHP necesarias para Laravel
RUN docker-php-ext-install pdo pdo_mysql mbstring bcmath intl gd

# Instalar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /srv

# Copy composer.lock and composer.json
COPY src/composer.lock src/composer.json ./

# Copiar el resto del proyecto
COPY --chown=www-data:www-data src/. .

# Change current user to www
USER www-data

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Ajustar permisos para storage y bootstrap/cache
RUN chown -R www-data:www-data /srv/storage /srv/bootstrap/cache

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]