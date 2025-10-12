#!/bin/sh
set -e

## Basic sanity checks
if [ ! -f artisan ]; then
  echo "Error: artisan file not found in workdir; did you mount the app directory?"
  exit 1
fi

# CLEANUP: Remove potentially problematic backup files that can cause key duplication
echo "Cleaning up backup and temporary files..."
rm -f .env.backup .env.bak .env.tmp .env.old 2>/dev/null || true
rm -f bootstrap/cache/config.php bootstrap/cache/packages.php bootstrap/cache/services.php 2>/dev/null || true
rm -f storage/framework/cache/data/* 2>/dev/null || true

# Wait for DB if DB_HOST provided
if [ -n "$DB_HOST" ]; then
  echo "Waiting for database at ${DB_HOST}:${DB_PORT:-3306}..."
  timeout=60
  count=0
  until nc -z ${DB_HOST} ${DB_PORT:-3306} || [ $count -eq $timeout ]; do
    printf '.'
    sleep 1
    count=$((count + 1))
  done
  
  if [ $count -eq $timeout ]; then
    echo " Error: Database connection timeout after ${timeout}s"
    exit 1
  fi
  echo " database is available"
fi

# If composer/vendor is missing (host mount), install dependencies into the mounted volume
if [ ! -f vendor/autoload.php ]; then
  echo "vendor not found — running composer install (this may take a while)"
  composer install --no-interaction --prefer-dist --optimize-autoloader || {
    echo "Error: composer install failed"
    exit 1
  }
fi

# Ensure permissions for storage and bootstrap cache
if [ -d storage ] || [ -d bootstrap/cache ]; then
  echo "Setting up directory permissions..."
  chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
  chmod -R 775 storage bootstrap/cache 2>/dev/null || true
fi

# ALWAYS run setup commands for containerized environment
echo "=== CONTAINERIZED LARAVEL SETUP ==="

# CRITICAL: APP_KEY MUST be validated FIRST before any Laravel operations
echo "=== STEP 1: APP_KEY VALIDATION & GENERATION ==="

# Check if .env file exists
if [ ! -f .env ]; then
  echo "No .env file found, copying from .env.docker..."
  cp .env.docker .env || {
    echo "Error: Failed to copy .env.docker to .env"
    exit 1
  }
fi

# Ensure APP_KEY line exists in .env
if ! grep -q "^APP_KEY=" .env 2>/dev/null; then
  echo "Adding APP_KEY line to .env..."
  echo "APP_KEY=" >> .env
fi

# Check if APP_KEY is empty or invalid - ALWAYS GENERATE for containers
APP_KEY_VALUE=$(grep "^APP_KEY=" .env 2>/dev/null | cut -d'=' -f2- | tr -d '"' | tr -d "'" || echo "")

if [ -z "$APP_KEY_VALUE" ] || [ "$APP_KEY_VALUE" = "base64:" ]; then
  echo "🔑 Generating APP_KEY using Laravel command..."
  php artisan key:generate --force
  echo "✅ APP_KEY generated successfully"
else
  echo "✅ Valid APP_KEY found"
fi

echo "=== STEP 2: CACHE MANAGEMENT ==="
# Clear all caches BEFORE any Laravel operations to prevent conflicts
echo "Clearing all Laravel caches..."
php artisan config:clear 2>/dev/null || echo "Warning: config clear failed"
php artisan cache:clear 2>/dev/null || echo "Warning: cache clear failed (cache table may not exist yet)"  
php artisan view:clear 2>/dev/null || echo "Warning: view clear failed"
php artisan route:clear 2>/dev/null || echo "Warning: route clear failed"

echo "=== STEP 3: PACKAGE DISCOVERY ==="
# Regenerate package discovery to fix ServiceProvider issues
echo "Regenerating package discovery..."
php artisan package:discover --ansi 2>/dev/null || echo "Warning: package discovery failed"

echo "=== STEP 4: CONFIGURATION CACHE ==="
echo "Caching Laravel configuration..."
php artisan config:cache 2>/dev/null || echo "Warning: config cache failed"

echo "=== STEP 5: DATABASE OPERATIONS ==="
# Run migrations for containerized setup
echo "Running database migrations..."
php artisan migrate --force || echo "Warning: migration failed"

echo "=== LARAVEL SETUP COMPLETED ==="

echo "Starting PHP-FPM..."
exec php-fpm