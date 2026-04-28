FROM php:8.1-fpm-alpine

# Instalar dependencias del sistema
RUN apk add --no-cache \
    bash \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    freetype-dev \
    fontconfig \
    ttf-freefont \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    zip \
    exif \
    gd \
    opcache

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar archivos de dependencias primero (cache de capas)
COPY composer.json composer.lock ./

# Instalar dependencias de Composer
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copiar el resto de la aplicación
COPY . .

# Ejecutar scripts post-install
RUN composer run-script post-root-package-install || true

# Permisos
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache 2>/dev/null || true

EXPOSE 9000

CMD ["php-fpm"]
