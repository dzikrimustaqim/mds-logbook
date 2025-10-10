# Docker setup for mds-logbook

This repository includes a minimal Docker setup to run the Laravel application with MySQL and Nginx.

Files added:
- `Dockerfile` — PHP-FPM image with required PHP extensions and Composer.
- `docker-entrypoint.sh` — simple entrypoint that waits for DB and runs `composer install` / migrations in non-production.
- `docker-compose.yml` — orchestrates `app` (php-fpm), `web` (nginx), `db` (mysql) and `node` (for building assets).
- `docker/nginx/default.conf` — nginx site config pointing to Laravel `public` folder.
- `.env.docker` — template env with DB credentials (logbook / mds / mdspass).

Quick start (Windows PowerShell):

```powershell
# copy env template to .env if you want the container to pick it up from project root
cp .env.docker .env

# build and start
docker compose up --build -d

# build frontend assets (via container if you don't have node locally)
docker compose run --rm node sh -lc "npm ci && npm run build"

# view logs
docker compose logs -f web

# stop
docker compose down
```

Notes:
- The web server is exposed on http://localhost:8080 and serves the Laravel `public` folder.
- MySQL is preconfigured with database `logbook`, user `mds`, password `mdspass` and root password `root` on port 3306.
- The Dockerfile uses PHP 8.3 FPM and installs common extensions required by Laravel. Composer binary is copied from the official Composer image.

## Summary of changes

Prepared a minimal but practical Docker environment and fixed issues encountered while running the app:

- Created `Dockerfile` using `php:8.3-fpm` with required PHP extensions and Composer.
- Added `docker-entrypoint.sh` that waits for database, runs `composer install` inside container if `vendor` missing, sets permissions for `storage` and `bootstrap/cache`, generates APP_KEY (non-production) and runs migrations (non-production).
- Wrote a `docker-compose.yml` that defines these services: `app` (php-fpm), `web` (nginx), `db` (mysql:8.1) and `node` (node:20) for building assets. I added `env_file: .env` for the `app` service and a basic `healthcheck` for `db`.
- Added `docker/nginx/default.conf` to serve Laravel public folder and forward PHP to the `app` container.
- Added `.dockerignore` to keep build context small and `.env.docker` as an env template with DB credentials.
- Added automation during setup to run `composer install` when `vendor` missing so a fresh checkout boots quickly.

## Production / VPS deployment guide (concise, practical)

This guide assumes a fresh Linux VPS (Ubuntu 22.04+ recommended) with root or sudo access. It keeps things simple and reproducible using Docker Compose.

1) Install Docker & Compose on the VPS (example for Ubuntu):

```bash
# run as root or prefix with sudo
apt update && apt install -y ca-certificates curl gnupg lsb-release
mkdir -p /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | gpg --dearmor -o /etc/apt/keyrings/docker.gpg
echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" > /etc/apt/sources.list.d/docker.list
apt update
apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin
```

2) Copy project to VPS (git clone or rsync). Ensure `.env` exists on the VPS (do NOT commit `.env` to git). Set production env values; set `APP_KEY` (generate locally with `php artisan key:generate --show` and paste it into `.env`), `APP_ENV=production`, `APP_DEBUG=false`, and proper `APP_URL`.

3) On VPS, in project folder:

```bash
docker compose up --build -d
# build frontend assets (only needed if not built in image)
docker compose run --rm node sh -lc "npm ci && npm run build"
# run migrations
docker compose exec app php artisan migrate --force
```

4) Production recommendations (short):
- Use persistent volumes for MySQL and backups.
- Use a reverse proxy / load balancer with TLS (Traefik or Nginx + certbot). Traefik can automatically manage Let's Encrypt certificates.
- Consider multi-stage Docker build to bake in `composer install` and `npm run build` so the VPS doesn't need Node at runtime.
- Configure log shipping or monitoring (Sentry for exceptions, Prometheus/Grafana for metrics).

# Docker setup for mds-logbook

This repository includes a minimal Docker setup to run the Laravel application with MySQL and Nginx.

Files added:
- `Dockerfile` — PHP-FPM image with required PHP extensions and Composer.
- `docker-entrypoint.sh` — simple entrypoint that waits for DB and runs migrations in non-production.
- `docker-compose.yml` — orchestrates `app` (php-fpm), `web` (nginx) and `db` (mysql).
- `docker/nginx/default.conf` — nginx site config pointing to Laravel `public` folder.
- `.env.docker` — template env with DB credentials (logbook / mds / mdspass).

Quick start (Windows PowerShell):

```powershell
# copy env template to .env if you want the container to pick it up from project root
cp .env.docker .env

# build and start
docker compose up --build -d

# view logs
docker compose logs -f web

# stop
docker compose down
```

Notes:
- The web server is exposed on http://localhost:8080 and serves the Laravel `public` folder.
- MySQL is preconfigured with database `logbook`, user `mds`, password `mdspass` and root password `root` on port 3306.
- The Dockerfile uses PHP 8.2 FPM and installs common extensions required by Laravel. Composer binary is copied from the official Composer image.
- For production use, you should harden credentials, set APP_KEY, disable APP_DEBUG and tune PHP and Nginx settings.
