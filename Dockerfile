## Multi-stage Dockerfile
## Stage 1: Node builder for Vite assets
FROM node:20-bullseye AS node_builder
WORKDIR /app
COPY package*.json ./
COPY resources ./resources
COPY vite.config.js postcss.config.js tailwind.config.js ./
RUN npm ci --silent
RUN npm run build

## Stage 2: Composer install (production vendor)
FROM composer:latest AS composer_builder
WORKDIR /app
COPY composer.json composer.lock ./
# Avoid running scripts (like artisan commands) in build stage because application files are not copied yet
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader --no-scripts

## Final stage: PHP-FPM
FROM php:8.3-fpm

# System dependencies and PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        curl \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
        zip \
        netcat-openbsd \
        ca-certificates \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql mbstring exif pcntl bcmath gd zip xml \
    && pecl install redis && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

    # Copy composer binary into final image so entrypoint can run composer for mounted dev volumes
    COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

    # Copy vendor produced by composer_builder
    COPY --from=composer_builder /app/vendor /var/www/html/vendor

# Copy built assets from node builder
COPY --from=node_builder /app/public/build /var/www/html/public/build

# Set working directory
WORKDIR /var/www/html

# Copy the rest of application source
COPY . /var/www/html

# Clean any problematic cache files that might exist in source
RUN rm -rf /var/www/html/bootstrap/cache/* \
    && rm -f /var/www/html/.env.backup /var/www/html/.env.bak /var/www/html/.env.tmp /var/www/html/.env.old

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Copy and set entrypoint
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 9000
USER www-data
CMD ["docker-entrypoint.sh"]