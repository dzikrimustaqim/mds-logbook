#!/bin/sh
set -e

## Basic sanity checks
if [ ! -f artisan ]; then
  echo "artisan file not found in workdir; did you mount the app directory?"
fi

# Wait for DB if DB_HOST provided
if [ -n "$DB_HOST" ]; then
  echo "Waiting for database at ${DB_HOST}:${DB_PORT:-3306}..."
  until nc -z ${DB_HOST} ${DB_PORT:-3306}; do
    printf '.'; sleep 1
  done
  echo " database is available"
fi

# If composer/vendor is missing (host mount), install dependencies into the mounted volume
if [ ! -f vendor/autoload.php ]; then
  echo "vendor not found — running composer install (this may take a while)"
  composer install --no-interaction --prefer-dist --optimize-autoloader || echo "composer install failed, continuing"
fi

# Ensure permissions for storage and bootstrap cache
if [ -d storage ] || [ -d bootstrap/cache ]; then
  chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
  chmod -R 775 storage bootstrap/cache 2>/dev/null || true
fi

# Run migrations & cache clears only if APP_ENV is not production
if [ "$APP_ENV" != 'production' ]; then
  php artisan key:generate --force || true
  php artisan migrate --force || true
fi

exec php-fpm
