# Docker setup for mds-logbook

This repository includes a minimal but practical Docker setup to run the Laravel application with MySQL and Nginx. The repository currently uses a multi-stage Dockerfile that builds frontend assets and PHP dependencies into the final image (so production does not need Node or Composer at runtime).
Overview of what is included

- `Dockerfile` — multi-stage: `node_builder` (build Vite assets), `composer_builder` (install vendor without running project scripts), final `php:8.3-fpm` stage that copies `vendor` and `public/build`.
- `docker-entrypoint.sh` — waits for DB, runs `composer install` when `vendor` missing (useful for local mounted volumes), sets permissions, runs migrations/key generation in non-production, then starts php-fpm.
- `docker-compose.yml` — defines services: `app` (php-fpm), `web` (nginx), `db` (mysql:8.1) and `node` (container you can use to build assets or run Vite dev server).
- `docker/nginx/default.conf` — nginx config serving `public` and proxying PHP to `app:9000`.
- `.env.docker` — example environment file for local development. Use `cp .env.docker .env` to create your working `.env` (do not commit `.env`).

Ports and runtime

- The web server is exposed on http://localhost:18080 and serves the Laravel `public` folder (host -> container: `18080:80`).
- MySQL is exposed on port `3306` (host -> container: `3306:3306`) — change as needed for your environment.

Quick start (Windows PowerShell)

1) Create a local `.env` from the included template (only for local dev):

```powershell
cp .env.docker .env
```
2) Build and start the stack (development):

```powershell
# build images (multi-stage will bake in vendor and built assets for production-ready image)
docker compose up --build -d

# build frontend assets (if you prefer to do it manually via the node service)
docker compose run --rm node sh -lc "npm ci --silent && npm run build --silent"

# view web logs
docker compose logs -f web

# stop and remove containers
docker compose down
```
Development workflows

- Static-build workflow (fast for serving static built assets): run the `node` build command above once after code changes that touch JS/CSS. That produces `public/build/manifest.json` and assets that Nginx will serve.
- Hot-reload workflow: run the Vite dev server inside the node container:

```powershell
docker compose exec node bash -lc "npm run dev -- --host 0.0.0.0"
```
then open http://localhost:18080 in the browser. Laravel will detect the Vite dev server and load assets from it.

Notes about entrypoint and mounted volumes

- The image includes a `docker-entrypoint.sh` which will attempt to run `composer install` if `vendor/autoload.php` is missing. This is helpful when you mount your project source as a volume in `app` (development mode) because the image's baked `vendor` directory is not used in that case. If you prefer the image to always contain `vendor`, avoid mounting the whole project into the `app` container in production.
- In the final image composer binary is present so entrypoint can run composer for mounted volumes.

Production / VPS deployment suggestions

- Use the `docker-compose.yml` or create a `docker-compose.prod.yml` that references the built image (or build on the VPS) — the multi-stage Dockerfile already copies built assets and vendor into the final image, so Node is not required on the host for runtime.
- Ensure `.env` with production values exists on the VPS (do NOT commit `.env` in git). Generate and set `APP_KEY`, set `APP_ENV=production`, `APP_DEBUG=false` and correct `APP_URL`.
- Use a reverse proxy (Traefik or Nginx) with TLS in front of the containers for production. Consider automated backups for MySQL.

Troubleshooting

- 502 Bad Gateway from nginx: usually means the `app` (php-fpm) is not ready yet — check `docker compose logs app` and `docker compose logs web`.
- Vite manifest missing: run the node build command to create `public/build/manifest.json` or ensure the multi-stage build included it in the image.
- Composer/network errors: ensure the host has network access to packagist.org; you can run composer commands inside the `app` container via `docker compose exec app composer ...`.

If you'd like, I can also:

- Add a small `Makefile` with targets like `make up`, `make down`, `make assets` and `make build-prod`.
- Add a CI workflow to build and push the multi-stage image to a registry.

---

Current repo status notes (automatically generated):
- PHP base image: `php:8.3-fpm`.
- Node for builds: `node:20-bullseye`.
- MySQL: `mysql:8.1` with DB `logbook` / user `mds` / password `mdspass` (see `.env.docker`).
- Host web port: `18080`.

Please tell me if Anda ingin saya commit & push README ini ke `origin/main` (saya akan commit hanya file README-Docker.md).
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
- The web server is exposed on http://localhost:18080 and serves the Laravel `public` folder.
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
- The web server is exposed on http://localhost:80811 and serves the Laravel `public` folder.
- MySQL is preconfigured with database `logbook`, user `mds`, password `mdspass` and root password `root` on port 3306.
- The Dockerfile uses PHP 8.2 FPM and installs common extensions required by Laravel. Composer binary is copied from the official Composer image.
- For production use, you should harden credentials, set APP_KEY, disable APP_DEBUG and tune PHP and Nginx settings.
