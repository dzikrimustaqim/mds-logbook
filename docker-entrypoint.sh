#!/bin/sh
set -e

[ -f artisan ] || { echo "Error: artisan not found in workdir"; exit 1; }

echo "Cleaning up cache files..."
rm -f bootstrap/cache/config.php bootstrap/cache/packages.php bootstrap/cache/services.php 2>/dev/null || true
rm -f storage/framework/cache/data/* 2>/dev/null || true

echo "Ensuring writable directories..."
mkdir -p \
  storage/logs \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/framework/views \
  storage/app \
  bootstrap/cache

touch storage/logs/laravel.log 2>/dev/null || true

# <<< Tambahkan baris ini: pastikan path cache Blade selalu valid >>>
export VIEW_COMPILED_PATH=${VIEW_COMPILED_PATH:-/var/www/html/storage/framework/views}

# Tunggu DB (jika ada)
if [ -n "$DB_HOST" ]; then
  echo "Waiting for database at ${DB_HOST}:${DB_PORT:-3306}..."
  timeout=60; c=0
  until nc -z "${DB_HOST}" "${DB_PORT:-3306}" || [ $c -eq $timeout ]; do
    printf '.'; sleep 1; c=$((c+1))
  done
  [ $c -lt $timeout ] || { echo " Error: DB timeout"; exit 1; }
  echo " database is available"
fi

echo "=== CONTAINERIZED LARAVEL SETUP ==="

# ====== APP_KEY AUTO (persist via storage/app/app_key) ======
echo "=== STEP 1: APP_KEY AUTO-PROVISION ==="
KEY_FILE="storage/app/app_key"
if [ -n "$APP_KEY" ] && [ "$APP_KEY" != "base64:" ]; then
  echo "✅ APP_KEY provided via ENV"
else
  if [ -s "$KEY_FILE" ]; then
    export APP_KEY="$(tr -d '\r\n' < "$KEY_FILE")"
    echo "✅ APP_KEY loaded from $KEY_FILE"
  else
    echo "🔑 Generating APP_KEY..."
    export APP_KEY="$(php -r 'echo "base64:".base64_encode(random_bytes(32));')"
    printf "%s\n" "$APP_KEY" > "$KEY_FILE" 2>/dev/null || echo "⚠️  Warning: cannot write $KEY_FILE (key will be ephemeral)"
    chmod 640 "$KEY_FILE" 2>/dev/null || true
    echo "✅ APP_KEY generated and stored to $KEY_FILE"
  fi
fi

echo "=== STEP 2: CACHE MANAGEMENT ==="
php artisan config:clear  2>/dev/null || echo "Warning: config clear failed"
php artisan cache:clear   2>/dev/null || echo "Warning: cache clear failed"
php artisan view:clear    2>/dev/null || echo "Warning: view clear failed"
php artisan route:clear   2>/dev/null || echo "Warning: route clear failed"

echo "=== STEP 3: PACKAGE DISCOVERY ==="
php artisan package:discover --ansi 2>/dev/null || echo "Warning: package discovery failed"

echo "=== STEP 4: CONFIG CACHE ==="
php artisan config:cache  2>/dev/null || echo "Warning: config cache failed"

echo "=== STEP 5: DATABASE MIGRATIONS ==="
php artisan migrate --force || echo "Warning: migration failed"

echo "=== LARAVEL SETUP COMPLETED ==="
echo "Starting PHP-FPM..."
exec php-fpm
