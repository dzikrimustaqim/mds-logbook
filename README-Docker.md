# MDS Logbook - Docker Setup

Dokumentasi lengkap untuk menjalankan aplikasi MDS Logbook menggunakan Docker.

## 📦 Komponen Docker

- **Dockerfile** - Multi-stage build dengan PHP 8.3-FPM, Node.js untuk asset building
- **docker-compose.yml** - Orchestrasi 4 services: app, web, db, dan node
- **docker-entrypoint.sh** - Automation untuk setup Laravel (migrations, permissions, dll)
- **docker/nginx/default.conf** - Konfigurasi Nginx untuk serve Laravel
- **.env.docker** - Template environment variables

## 🚀 Quick Start (Development)

### Prasyarat
- Docker & Docker Compose terinstall
- Git untuk clone repository

### Langkah Setup

```bash
# 1. Clone repository
git clone https://github.com/yourusername/mds-logbook.git
cd mds-logbook

# 2. Copy environment file
cp .env.docker .env

# 3. Start Docker containers
docker-compose up -d

# 4. Akses aplikasi
# Browser: http://localhost:18080
```

### Perintah Umum

```bash
# Melihat status containers
docker-compose ps

# Melihat logs
docker-compose logs -f app
docker-compose logs -f web

# Restart services
docker-compose restart

# Stop containers
docker-compose down

# Rebuild containers
docker-compose up --build -d
```

## 🏗️ Arsitektur Services

```
┌──────────────────────────────────────┐
│  Browser: http://localhost:18080    │
└──────────────┬───────────────────────┘
               │
┌──────────────▼───────────────────────┐
│  web (Nginx:stable-alpine)           │
│  Port: 18080:80                      │
└──────────────┬───────────────────────┘
               │
┌──────────────▼───────────────────────┐
│  app (PHP 8.3-FPM)                   │
│  Laravel Application                 │
└──────────────┬───────────────────────┘
               │
┌──────────────▼───────────────────────┐
│  db (MySQL 8.1)                      │
│  Port: 13306:3306                    │
│  Database: logbook                   │
└──────────────────────────────────────┘

┌──────────────────────────────────────┐
│  node (Node 20-bullseye)             │
│  Build Vite assets                   │
└──────────────────────────────────────┘
```

## 🔧 Konfigurasi

### Database
- **Host**: db (dalam Docker network) / localhost:13306 (dari host)
- **Database**: logbook
- **Username**: mds
- **Password**: mdspass
- **Root Password**: root

### Ports
- **Web**: 18080 (HTTP)
- **MySQL**: 13306 (external access)

### Volumes (Persistent Data)
- `dbdata` - MySQL database
- `node_modules` - NPM packages
- `bootstrap_cache` - Laravel cache
- `storage_data` - Laravel storage

## 📝 Development Workflow

### Update Frontend Assets

```bash
# Setelah mengubah CSS/JS
docker-compose exec node npm run build
```

### Run Migrations

```bash
docker-compose exec app php artisan migrate
```

### Run Seeders

```bash
docker-compose exec app php artisan db:seed
```

### Access Container Shell

```bash
# PHP container
docker-compose exec app bash

# Node container  
docker-compose exec node bash

# MySQL container
docker-compose exec db bash
```

## 🌐 Production / VPS Deployment

### 1. Setup Docker di VPS

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Install Docker Compose
sudo apt install docker-compose-plugin -y

# Verify installation
docker --version
docker compose version
```

### 2. Deploy Aplikasi

```bash
# Clone repository
git clone https://github.com/yourusername/mds-logbook.git
cd mds-logbook

# Setup environment
cp .env.docker .env
nano .env  # Edit untuk production values

# Update konfigurasi production:
# - APP_ENV=production
# - APP_DEBUG=false
# - APP_URL=https://your-domain.com
# - Generate APP_KEY: base64:...
# - Update database credentials jika perlu

# Start containers
docker-compose up -d

# Check status
docker-compose ps
docker-compose logs -f
```

### 3. Setup Domain & SSL

Untuk production dengan domain, gunakan Nginx reverse proxy di host dengan Certbot untuk SSL:

```bash
# Install Nginx di host
sudo apt install nginx certbot python3-certbot-nginx -y

# Konfigurasi proxy pass (lihat nginx-proxy.conf)
sudo nano /etc/nginx/sites-available/your-domain

# Enable site
sudo ln -s /etc/nginx/sites-available/your-domain /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx

# Request SSL certificate
sudo certbot --nginx -d your-domain.com
```

**Alternatif**: Gunakan Cloudflare dengan Flexible SSL mode untuk SSL termination.

### 4. Update Aplikasi (Production)

```bash
# Pull latest code
git pull origin main

# Rebuild containers jika ada perubahan Dockerfile
docker-compose up --build -d

# Atau hanya restart jika perubahan code saja
docker-compose restart app
```

## 🐛 Troubleshooting

### Container Gagal Start

```bash
# Cek logs detail
docker-compose logs app
docker-compose logs db

# Cek status health check
docker-compose ps
```

### Permission Errors

```bash
# Fix permission di VPS
sudo chown -R 33:33 storage/ bootstrap/cache/
sudo chmod -R 775 storage/ bootstrap/cache/
```

### Database Connection Error

```bash
# Pastikan database container healthy
docker-compose ps db

# Test koneksi dari app container
docker-compose exec app nc -zv db 3306
```

### Frontend Assets Missing

```bash
# Rebuild assets
docker-compose exec node npm run build

# Verify build output
docker-compose exec app ls -la public/build/
```

### Port Already in Use

```bash
# Cek port yang digunakan
sudo netstat -tulpn | grep :18080
sudo netstat -tulpn | grep :13306

# Stop service yang konflik atau ubah port di docker-compose.yml
```

## 📚 File Penting

### Dockerfile
Multi-stage build yang menghasilkan production-ready image:
1. **node_builder** - Build Vite assets
2. **composer_builder** - Install PHP dependencies
3. **Final stage** - PHP-FPM dengan semua dependencies

### docker-entrypoint.sh
Automation script yang:
- Menunggu database ready
- Install/update dependencies
- Set permissions
- Generate APP_KEY (non-production)
- Run migrations (non-production)

### docker-compose.yml
Definisi 4 services dengan health checks dan volume persistence.

## 🔒 Security Notes

- **JANGAN commit `.env`** ke repository
- Gunakan strong passwords untuk production
- Set `APP_DEBUG=false` di production
- Gunakan HTTPS/SSL untuk production
- Backup database secara regular
- Update Docker images secara berkala

## 📞 Support

Untuk issues dan pertanyaan:
- GitHub Issues: [Repository Issues](https://github.com/yourusername/mds-logbook/issues)
- Documentation: [README.md](README.md)
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
