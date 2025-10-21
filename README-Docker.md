````markdown# MDS Logbook - Docker Setup

# MDS Logbook Docker Setup

Dokumentasi lengkap untuk menjalankan aplikasi MDS Logbook menggunakan Docker.

Simplified Docker configuration untuk aplikasi MDS Logbook Laravel.

## 📦 Komponen Docker

## 📁 Struktur File

- **Dockerfile** - Multi-stage build dengan PHP 8.3-FPM, Node.js untuk asset building

```- **docker-compose.yml** - Orchestrasi 4 services: app, web, db, dan node

mds-logbook/- **docker-entrypoint.sh** - Automation untuk setup Laravel (migrations, permissions, dll)

├── Dockerfile              # Docker image configuration- **docker/nginx/default.conf** - Konfigurasi Nginx untuk serve Laravel

├── docker-compose.yml      # Container orchestration- **.env.docker** - Template environment variables

├── docker-entrypoint.sh    # Initialization script

├── .env.docker             # Environment template## 🚀 Quick Start (Development)

└── docker/

    └── nginx/### Prasyarat

        └── default.conf    # Nginx configuration- Docker & Docker Compose terinstall

```- Git untuk clone repository



## 🚀 Quick Start### Langkah Setup



### 1. Setup Environment```bash

```bash# 1. Clone repository

# Copy environment templategit clone https://github.com/yourusername/mds-logbook.git

cp .env.docker .envcd mds-logbook



# Edit .env jika perlu (APP_KEY akan di-generate otomatis)# 2. Copy environment file

```cp .env.docker .env



### 2. Deploy# 3. Start Docker containers

```bashdocker-compose up -d

# Satu perintah untuk build dan start

docker-compose up -d# 4. Akses aplikasi

```# Browser: http://localhost:18080

```

Selesai! Aplikasi akan:

- Build Docker image### Perintah Umum

- Start containers (app + nginx)

- Generate APP_KEY otomatis```bash

- Run database migrations# Melihat status containers

- Optimize untuk productiondocker-compose ps



### 3. Access Application# Melihat logs

- **Web**: http://localhost:18080docker-compose logs -f app

- **Health Check**: http://localhost:18080/healthdocker-compose logs -f web



### 4. Deploy dengan Database (Optional)# Restart services

Jika ingin menggunakan MySQL container (bukan external database):docker-compose restart

```bash

# Start dengan database profile# Stop containers

docker-compose --profile db up -ddocker-compose down

```

# Rebuild containers

## 📋 Common Commandsdocker-compose up --build -d

```

```bash

# Start containers## 🏗️ Arsitektur Services

docker-compose up -d

```

# Stop containers┌──────────────────────────────────────┐

docker-compose down│  Browser: http://localhost:18080    │

└──────────────┬───────────────────────┘

# View logs               │

docker-compose logs -f app┌──────────────▼───────────────────────┐

│  web (Nginx:stable-alpine)           │

# Restart│  Port: 18080:80                      │

docker-compose restart└──────────────┬───────────────────────┘

               │

# Rebuild┌──────────────▼───────────────────────┐

docker-compose up -d --build│  app (PHP 8.3-FPM)                   │

│  Laravel Application                 │

# Clean everything (including volumes)└──────────────┬───────────────────────┘

docker-compose down --rmi all -v               │

```┌──────────────▼───────────────────────┐

│  db (MySQL 8.1)                      │

## 🔧 Environment Variables│  Port: 13306:3306                    │

│  Database: logbook                   │

Edit file `.env` untuk konfigurasi:└──────────────────────────────────────┘



```env┌──────────────────────────────────────┐

APP_PORT=18080              # Application port│  node (Node 20-bullseye)             │

DB_HOST=db                  # Database host (atau external host)│  Build Vite assets                   │

DB_PORT_INTERNAL=3306       # Database port internal└──────────────────────────────────────┘

DB_DATABASE=mds_logbook_db  # Database name```

DB_USERNAME=mds_user        # Database user

DB_PASSWORD=mds_secure_2025 # Database password## 🔧 Konfigurasi

DB_ROOT_PASSWORD=root_secure_2025  # MySQL root password

```### Database

- **Host**: db (dalam Docker network) / localhost:13306 (dari host)

## 📊 Container Info- **Database**: logbook

- **Username**: mds

| Container | Service | Port | Description |- **Password**: mdspass

|-----------|---------|------|-------------|- **Root Password**: root

| mds_logbook_app | PHP-FPM 8.3 Alpine | - | Laravel application |

| mds_logbook_nginx | Nginx 1.27 Alpine | 18080 | Web server |### Ports

| mds_logbook_db | MySQL 8.0 | - | Database (optional, with --profile db) |- **Web**: 18080 (HTTP)

- **MySQL**: 13306 (external access)

## 💾 Data Persistence

### Volumes (Persistent Data)

Data berikut disimpan secara permanen:- `dbdata` - MySQL database

- **mds_mysql_data**: Database MySQL (Docker volume, jika menggunakan profile db)- `node_modules` - NPM packages

- **./storage**: File uploads dan logs (bind mount)- `bootstrap_cache` - Laravel cache

- **./bootstrap/cache**: Laravel bootstrap cache (bind mount)- `storage_data` - Laravel storage



## ⚙️ What Happens on Startup## 📝 Development Workflow



1. Wait for database connection### Update Frontend Assets

2. Generate APP_KEY (jika belum ada)

3. Run database migrations```bash

4. Cache configs, routes, views# Setelah mengubah CSS/JS

5. Create storage symlinkdocker-compose exec node npm run build

6. Fix permissions```

7. Start PHP-FPM

### Run Migrations

## 🔍 Troubleshooting

```bash

### Container tidak startdocker-compose exec app php artisan migrate

```bash```

docker-compose logs app

docker-compose logs nginx### Run Seeders

```

```bash

### Reset semuadocker-compose exec app php artisan db:seed

```bash```

docker-compose down --rmi all -v

cp .env.docker .env### Access Container Shell

docker-compose up -d

``````bash

# PHP container

### Access container shelldocker-compose exec app bash

```bash

docker-compose exec app sh# Node container  

```docker-compose exec node bash



### Run artisan commands# MySQL container

```bashdocker-compose exec db bash

docker-compose exec app php artisan migrate```

docker-compose exec app php artisan cache:clear

docker-compose exec app php artisan config:clear## 🌐 Production / VPS Deployment

```

### 1. Setup Docker di VPS

### Database connection issues

Jika menggunakan external database, pastikan:```bash

- Database sudah running# Update system

- DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD benarsudo apt update && sudo apt upgrade -y

- Database user memiliki akses dari Docker network

# Install Docker

## 🏗️ Architecturecurl -fsSL https://get.docker.com -o get-docker.sh

sudo sh get-docker.sh

### Docker Image

- Base: PHP 8.3 FPM Alpine (lightweight)# Install Docker Compose

- Extensions: pdo_mysql, mbstring, gd, zip, intl, opcachesudo apt install docker-compose-plugin -y

- Includes: Node.js, npm untuk build frontend assets

- Size: ~150MB (vs ~500MB multi-stage Debian)# Verify installation

docker --version

### Containersdocker compose version

- **app**: Laravel application (PHP-FPM)```

- **nginx**: Web server dan reverse proxy

- **db**: MySQL database (optional)### 2. Deploy Aplikasi



### Networking```bash

Semua containers terhubung dalam `mds_network` (bridge network).# Clone repository

git clone https://github.com/yourusername/mds-logbook.git

### Health Checkscd mds-logbook

- **app**: PHP-FPM configuration test

- **nginx**: HTTP health endpoint check# Setup environment

- **db**: MySQL ping testcp .env.docker .env

nano .env  # Edit untuk production values

## 🔒 Production Notes

# Update konfigurasi production:

Untuk production, update `.env`:# - APP_ENV=production

- Set `APP_ENV=production`# - APP_DEBUG=false

- Set `APP_DEBUG=false`# - APP_URL=https://your-domain.com

- Set strong passwords untuk `DB_PASSWORD` dan `DB_ROOT_PASSWORD`# - Generate APP_KEY: base64:...

- Set `APP_URL` ke domain Anda# - Update database credentials jika perlu

- Gunakan reverse proxy (Nginx/Caddy) dengan SSL

- Set `LOG_LEVEL=error` untuk mengurangi log# Start containers

docker-compose up -d

## 🆚 Perbedaan dengan Konfigurasi Sebelumnya

# Check status

### Sebelumnya (Multi-stage)docker-compose ps

- 3 containers (app, nginx, node)docker-compose logs -f

- Multi-stage Dockerfile (Debian-based)```

- Image size: ~500MB

- Build time: ~5-10 menit### 3. Setup Domain & SSL

- Node container terpisah untuk build

Untuk production dengan domain, gunakan Nginx reverse proxy di host dengan Certbot untuk SSL:

### Sekarang (Simplified)

- 2 containers (app, nginx)```bash

- Single-stage Dockerfile (Alpine-based)# Install Nginx di host

- Image size: ~150MBsudo apt install nginx certbot python3-certbot-nginx -y

- Build time: ~2-3 menit

- Node build dalam image# Konfigurasi proxy pass (lihat nginx-proxy.conf)

sudo nano /etc/nginx/sites-available/your-domain

### Keuntungan

✅ Image lebih kecil dan cepat  # Enable site

✅ Deploy lebih sederhana  sudo ln -s /etc/nginx/sites-available/your-domain /etc/nginx/sites-enabled/

✅ Resource usage lebih efisien  sudo nginx -t

✅ Healthchecks lebih robust  sudo systemctl reload nginx

✅ Environment variables lebih terstruktur  

✅ Auto-generate APP_KEY  # Request SSL certificate

✅ Profile untuk database (optional)  sudo certbot --nginx -d your-domain.com

```

## 📝 Notes

**Alternatif**: Gunakan Cloudflare dengan Flexible SSL mode untuk SSL termination.

- APP_KEY akan di-generate otomatis saat pertama kali start

- Database migrations akan dijalankan otomatis### 4. Update Aplikasi (Production)

- Optimasi cache akan dilakukan otomatis

- Frontend assets (Vite/npm) di-build dalam image```bash

- Tidak perlu setup manual, semua otomatis# Pull latest code

- Database container bersifat optional (gunakan --profile db)git pull origin main



---# Rebuild containers jika ada perubahan Dockerfile

docker-compose up --build -d

Simple, clean, dan production-ready! 🚀

````# Atau hanya restart jika perubahan code saja

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
