# MDS Logbook - Docker Setup````markdown# MDS Logbook - Docker Setup



Konfigurasi Docker yang disederhanakan dan production-ready untuk aplikasi MDS Logbook Laravel.Konfigurasi Docker yang disederhanakan dan production-ready untuk aplikasi MDS Logbook Laravel.



## 📁 Struktur File Docker## 📦 Komponen Docker



```## 📁 Struktur File

mds-logbook/

├── Dockerfile              # PHP 8.3 FPM Alpine + Node.js- **Dockerfile** - Multi-stage build dengan PHP 8.3-FPM, Node.js untuk asset building

├── docker-compose.yml      # Orchestration 3 services

├── docker-entrypoint.sh    # Initialization script```- **docker-compose.yml** - Orchestrasi 4 services: app, web, db, dan node

├── .env.docker             # Environment template

└── docker/mds-logbook/- **docker-entrypoint.sh** - Automation untuk setup Laravel (migrations, permissions, dll)

    └── nginx/

        └── default.conf    # Nginx configuration├── Dockerfile              # Docker image configuration- **docker/nginx/default.conf** - Konfigurasi Nginx untuk serve Laravel

```

├── docker-compose.yml      # Container orchestration- **.env.docker** - Template environment variables

## 🏗️ Arsitektur Containers

├── docker-entrypoint.sh    # Initialization script

| Container | Image | Fungsi | Port |

|-----------|-------|--------|------|├── .env.docker             # Environment template## 🚀 Quick Start (Development)

| `mds_logbook_app` | Custom (PHP 8.3 FPM Alpine) | Laravel application | Internal |

| `mds_logbook_web` | nginx:1.27-alpine | Web server | 18080 |└── docker/

| `mds_logbook_db` | mysql:8.0 | Database (isolated) | Internal only |

    └── nginx/### Prasyarat

**Network:** Semua container terhubung di `mds_network` (bridge, isolated)

        └── default.conf    # Nginx configuration- Docker & Docker Compose terinstall

**Volumes:**

- `mds_mysql_data` - Persistent MySQL data```- Git untuk clone repository

- `mds_app_public` - Shared public folder (app ↔ web)



## 🚀 Quick Start

## 🚀 Quick Start### Langkah Setup

### 1. Setup Environment

```bash

# Copy template

cp .env.docker .env### 1. Setup Environment```bash



# Edit jika perlu (opsional, sudah ada default values)```bash# 1. Clone repository

# nano .env

```# Copy environment templategit clone https://github.com/yourusername/mds-logbook.git



### 2. Build & Startcp .env.docker .envcd mds-logbook

```bash

# Build image dan start semua containers

docker-compose up -d --build

# Edit .env jika perlu (APP_KEY akan di-generate otomatis)# 2. Copy environment file

# Tunggu hingga semua healthy (±1-2 menit)

docker-compose ps```cp .env.docker .env

```



### 3. Akses Aplikasi

- **Web:** http://localhost:18080### 2. Deploy# 3. Start Docker containers

- **Health Check:** http://localhost:18080/health

```bashdocker-compose up -d

## ⚙️ Proses Startup Otomatis

# Satu perintah untuk build dan start

Saat container start, `docker-entrypoint.sh` akan:

docker-compose up -d# 4. Akses aplikasi

1. ✅ Menunggu database connection (max 30 retries)

2. ✅ Generate `APP_KEY` jika belum ada```# Browser: http://localhost:18080

3. ✅ Run `php artisan migrate --force`

4. ✅ Cache config, routes, views```

5. ✅ Create storage symlink

6. ✅ Set permissionsSelesai! Aplikasi akan:

7. ✅ Start PHP-FPM

- Build Docker image### Perintah Umum

**Semua otomatis, tidak perlu intervensi manual!**

- Start containers (app + nginx)

## 📋 Common Commands

- Generate APP_KEY otomatis```bash

### Container Management

```bash- Run database migrations# Melihat status containers

# Start

docker-compose up -d- Optimize untuk productiondocker-compose ps



# Stop

docker-compose down

### 3. Access Application# Melihat logs

# Restart semua

docker-compose restart- **Web**: http://localhost:18080docker-compose logs -f app



# Restart service tertentu- **Health Check**: http://localhost:18080/healthdocker-compose logs -f web

docker-compose restart app

docker-compose restart web



# Rebuild setelah update code### 4. Deploy dengan Database (Optional)# Restart services

docker-compose up -d --build

Jika ingin menggunakan MySQL container (bukan external database):docker-compose restart

# Lihat status

docker-compose ps```bash



# Lihat logs# Start dengan database profile# Stop containers

docker-compose logs -f app

docker-compose logs -f webdocker-compose --profile db up -ddocker-compose down

docker-compose logs --tail=50 app

``````



### Laravel Artisan# Rebuild containers

```bash

# Run migration## 📋 Common Commandsdocker-compose up --build -d

docker-compose exec app php artisan migrate

```

# Rollback migration

docker-compose exec app php artisan migrate:rollback```bash



# Seed database# Start containers## 🏗️ Arsitektur Services

docker-compose exec app php artisan db:seed

docker-compose up -d

# Clear cache

docker-compose exec app php artisan cache:clear```

docker-compose exec app php artisan config:clear

docker-compose exec app php artisan view:clear# Stop containers┌──────────────────────────────────────┐



# Database infodocker-compose down│  Browser: http://localhost:18080    │

docker-compose exec app php artisan db:show

└──────────────┬───────────────────────┘

# Access container shell

docker-compose exec app sh# View logs               │

```

docker-compose logs -f app┌──────────────▼───────────────────────┐

### Cleanup

```bash│  web (Nginx:stable-alpine)           │

# Stop dan hapus containers

docker-compose down# Restart│  Port: 18080:80                      │



# Stop, hapus containers + imagesdocker-compose restart└──────────────┬───────────────────────┘

docker-compose down --rmi local

               │

# Stop, hapus containers + volumes (HATI-HATI: database akan terhapus!)

docker-compose down -v# Rebuild┌──────────────▼───────────────────────┐



# Full cleanupdocker-compose up -d --build│  app (PHP 8.3-FPM)                   │

docker-compose down --rmi all -v

```│  Laravel Application                 │



## 🔄 Update Deployment# Clean everything (including volumes)└──────────────┬───────────────────────┘



### Scenario 1: Update Kode Aplikasi Sajadocker-compose down --rmi all -v               │

```bash

# Pull dari GitHub```┌──────────────▼───────────────────────┐

git pull origin main

│  db (MySQL 8.1)                      │

# Rebuild app container

docker-compose up -d --build app## 🔧 Environment Variables│  Port: 13306:3306                    │



# Web akan auto-restart karena depends_on app│  Database: logbook                   │

```

Edit file `.env` untuk konfigurasi:└──────────────────────────────────────┘

### Scenario 2: Update dengan Migration Baru

```bash

git pull origin main

docker-compose up -d --build app```env┌──────────────────────────────────────┐



# Migration otomatis dijalankan saat container startAPP_PORT=18080              # Application port│  node (Node 20-bullseye)             │

# Atau manual:

docker-compose exec app php artisan migrate --forceDB_HOST=db                  # Database host (atau external host)│  Build Vite assets                   │

```

DB_PORT_INTERNAL=3306       # Database port internal└──────────────────────────────────────┘

### Scenario 3: Update Frontend (CSS/JS)

```bashDB_DATABASE=mds_logbook_db  # Database name```

git pull origin main

DB_USERNAME=mds_user        # Database user

# Frontend assets di-build saat image build

docker-compose up -d --build appDB_PASSWORD=mds_secure_2025 # Database password## 🔧 Konfigurasi



# Restart web untuk load assets baruDB_ROOT_PASSWORD=root_secure_2025  # MySQL root password

docker-compose restart web

``````### Database



### Scenario 4: Update Environment Variables- **Host**: db (dalam Docker network) / localhost:13306 (dari host)

```bash

# Edit .env## 📊 Container Info- **Database**: logbook

nano .env

- **Username**: mds

# Restart tanpa rebuild

docker-compose restart app| Container | Service | Port | Description |- **Password**: mdspass

```

|-----------|---------|------|-------------|- **Root Password**: root

## 🔧 Konfigurasi Environment

| mds_logbook_app | PHP-FPM 8.3 Alpine | - | Laravel application |

File `.env` variables penting:

| mds_logbook_nginx | Nginx 1.27 Alpine | 18080 | Web server |### Ports

```env

# Application| mds_logbook_db | MySQL 8.0 | - | Database (optional, with --profile db) |- **Web**: 18080 (HTTP)

APP_NAME="MDS Logbook"

APP_ENV=production- **MySQL**: 13306 (external access)

APP_KEY=                        # Auto-generated saat pertama start

APP_DEBUG=false## 💾 Data Persistence

APP_URL=http://localhost:18080

APP_PORT=18080                  # Port web server### Volumes (Persistent Data)



# DatabaseData berikut disimpan secara permanen:- `dbdata` - MySQL database

DB_CONNECTION=mysql

DB_HOST=db                      # Hostname internal Docker- **mds_mysql_data**: Database MySQL (Docker volume, jika menggunakan profile db)- `node_modules` - NPM packages

DB_PORT_INTERNAL=3306

DB_DATABASE=mds_logbook_db- **./storage**: File uploads dan logs (bind mount)- `bootstrap_cache` - Laravel cache

DB_USERNAME=mds_user

DB_PASSWORD=mds_secure_2025- **./bootstrap/cache**: Laravel bootstrap cache (bind mount)- `storage_data` - Laravel storage

DB_ROOT_PASSWORD=root_secure_2025

```



**⚠️ Untuk production, ubah:**## ⚙️ What Happens on Startup## 📝 Development Workflow

- `APP_ENV=production`

- `APP_DEBUG=false`

- Password yang kuat untuk `DB_PASSWORD` dan `DB_ROOT_PASSWORD`

- `APP_URL` sesuai domain Anda1. Wait for database connection### Update Frontend Assets



## 🔒 Keamanan & Network Isolation2. Generate APP_KEY (jika belum ada)



### Network Isolation3. Run database migrations```bash

- ✅ Semua containers di network `mds_network` (isolated bridge)

- ✅ MySQL **tidak expose port** ke host4. Cache configs, routes, views# Setelah mengubah CSS/JS

- ✅ MySQL hanya accessible dari dalam Docker network

- ✅ Hanya Nginx yang expose port 18080 ke host5. Create storage symlinkdocker-compose exec node npm run build



### Akses Database6. Fix permissions```



**Dari host:**7. Start PHP-FPM

```bash

# ❌ TIDAK BISA (by design, untuk keamanan)### Run Migrations

mysql -h localhost -P 3306 -u mds_user -p

## 🔍 Troubleshooting

# ✅ Akses via container

docker-compose exec db mysql -u mds_user -pmds_secure_2025 mds_logbook_db```bash

```

### Container tidak startdocker-compose exec app php artisan migrate

**Dari aplikasi:**

```php```bash```

// ✅ Gunakan hostname 'db' (internal Docker DNS)

DB_HOST=dbdocker-compose logs app

DB_PORT=3306

```docker-compose logs nginx### Run Seeders



## 🐛 Troubleshooting```



### Container tidak start```bash

```bash

# Cek logs### Reset semuadocker-compose exec app php artisan db:seed

docker-compose logs app

docker-compose logs web```bash```

docker-compose logs db

docker-compose down --rmi all -v

# Cek health status

docker-compose pscp .env.docker .env### Access Container Shell

```

docker-compose up -d

### Frontend assets tidak muncul (tampilan plain HTML)

```bash``````bash

# Cek apakah assets ada di container

docker-compose exec app ls -la public/build/# PHP container

docker-compose exec web ls -la /var/www/html/public/build/

### Access container shelldocker-compose exec app bash

# Rebuild jika tidak ada

docker-compose up -d --build app```bash

docker-compose restart web

docker-compose exec app sh# Node container  

# Test akses manifest

curl http://localhost:18080/build/manifest.json```docker-compose exec node bash

```



### Database connection error

```bash### Run artisan commands# MySQL container

# Pastikan DB healthy

docker-compose ps db```bashdocker-compose exec db bash



# Test koneksi dari appdocker-compose exec app php artisan migrate```

docker-compose exec app php artisan db:show

docker-compose exec app php artisan cache:clear

# Lihat logs DB

docker-compose logs dbdocker-compose exec app php artisan config:clear## 🌐 Production / VPS Deployment

```

```

### Port 18080 sudah digunakan

```bash### 1. Setup Docker di VPS

# Cek port yang digunakan

netstat -ano | findstr :18080    # Windows### Database connection issues

sudo netstat -tulpn | grep 18080 # Linux

Jika menggunakan external database, pastikan:```bash

# Ubah port di .env

APP_PORT=18081- Database sudah running# Update system



# Restart- DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD benarsudo apt update && sudo apt upgrade -y

docker-compose down

docker-compose up -d- Database user memiliki akses dari Docker network

```

# Install Docker

### Permission errors (storage/logs)

```bash## 🏗️ Architecturecurl -fsSL https://get.docker.com -o get-docker.sh

# Fix permission dari dalam container

docker-compose exec app chown -R www-data:www-data storage bootstrap/cachesudo sh get-docker.sh

docker-compose exec app chmod -R 775 storage bootstrap/cache

```### Docker Image



## 📊 Monitoring & Health Checks- Base: PHP 8.3 FPM Alpine (lightweight)# Install Docker Compose



### Health Check Endpoints- Extensions: pdo_mysql, mbstring, gd, zip, intl, opcachesudo apt install docker-compose-plugin -y



Semua services punya health checks otomatis:- Includes: Node.js, npm untuk build frontend assets



```yaml- Size: ~150MB (vs ~500MB multi-stage Debian)# Verify installation

# App (PHP-FPM)

test: php-fpm -t || exit 1docker --version

interval: 30s

### Containersdocker compose version

# Web (Nginx)

test: wget --quiet --tries=1 --spider http://localhost/health- **app**: Laravel application (PHP-FPM)```

interval: 10s

- **nginx**: Web server dan reverse proxy

# DB (MySQL)

test: mysqladmin ping -h localhost -u root -p${DB_ROOT_PASSWORD}- **db**: MySQL database (optional)### 2. Deploy Aplikasi

interval: 10s

```



### Cek Health Status### Networking```bash

```bash

# Via docker-composeSemua containers terhubung dalam `mds_network` (bridge network).# Clone repository

docker-compose ps

git clone https://github.com/yourusername/mds-logbook.git

# Via docker

docker inspect mds_logbook_app --format='{{.State.Health.Status}}'### Health Checkscd mds-logbook

docker inspect mds_logbook_web --format='{{.State.Health.Status}}'

docker inspect mds_logbook_db --format='{{.State.Health.Status}}'- **app**: PHP-FPM configuration test



# Web health endpoint- **nginx**: HTTP health endpoint check# Setup environment

curl http://localhost:18080/health

# Response: healthy- **db**: MySQL ping testcp .env.docker .env

```

nano .env  # Edit untuk production values

## 🚀 Production Deployment (VPS)

## 🔒 Production Notes

### 1. Persiapan Server

```bash# Update konfigurasi production:

# Update sistem

sudo apt update && sudo apt upgrade -yUntuk production, update `.env`:# - APP_ENV=production



# Install Docker- Set `APP_ENV=production`# - APP_DEBUG=false

curl -fsSL https://get.docker.com -o get-docker.sh

sudo sh get-docker.sh- Set `APP_DEBUG=false`# - APP_URL=https://your-domain.com



# Install Docker Compose- Set strong passwords untuk `DB_PASSWORD` dan `DB_ROOT_PASSWORD`# - Generate APP_KEY: base64:...

sudo apt install docker-compose-plugin -y

- Set `APP_URL` ke domain Anda# - Update database credentials jika perlu

# Verifikasi

docker --version- Gunakan reverse proxy (Nginx/Caddy) dengan SSL

docker compose version

```- Set `LOG_LEVEL=error` untuk mengurangi log# Start containers



### 2. Deploy Aplikasidocker-compose up -d

```bash

# Clone repository## 🆚 Perbedaan dengan Konfigurasi Sebelumnya

git clone https://github.com/dzikrimustaqim/mds-logbook.git

cd mds-logbook# Check status



# Setup environment### Sebelumnya (Multi-stage)docker-compose ps

cp .env.docker .env

nano .env  # Edit untuk production- 3 containers (app, nginx, node)docker-compose logs -f



# PENTING: Update values berikut di .env- Multi-stage Dockerfile (Debian-based)```

# APP_ENV=production

# APP_DEBUG=false- Image size: ~500MB

# APP_URL=https://your-domain.com

# DB_PASSWORD=<strong-password>- Build time: ~5-10 menit### 3. Setup Domain & SSL

# DB_ROOT_PASSWORD=<strong-password>

- Node container terpisah untuk build

# Build dan start

docker-compose up -d --buildUntuk production dengan domain, gunakan Nginx reverse proxy di host dengan Certbot untuk SSL:



# Monitor logs### Sekarang (Simplified)

docker-compose logs -f

```- 2 containers (app, nginx)```bash



### 3. Setup Domain & SSL (Opsional)- Single-stage Dockerfile (Alpine-based)# Install Nginx di host



Gunakan reverse proxy di host dengan SSL:- Image size: ~150MBsudo apt install nginx certbot python3-certbot-nginx -y



```bash- Build time: ~2-3 menit

# Install Nginx di host

sudo apt install nginx certbot python3-certbot-nginx -y- Node build dalam image# Konfigurasi proxy pass (lihat nginx-proxy.conf)



# Konfigurasi reverse proxysudo nano /etc/nginx/sites-available/your-domain

sudo nano /etc/nginx/sites-available/mds-logbook

```### Keuntungan



Konfigurasi Nginx:✅ Image lebih kecil dan cepat  # Enable site

```nginx

server {✅ Deploy lebih sederhana  sudo ln -s /etc/nginx/sites-available/your-domain /etc/nginx/sites-enabled/

    listen 80;

    server_name your-domain.com;✅ Resource usage lebih efisien  sudo nginx -t



    location / {✅ Healthchecks lebih robust  sudo systemctl reload nginx

        proxy_pass http://localhost:18080;

        proxy_set_header Host $host;✅ Environment variables lebih terstruktur  

        proxy_set_header X-Real-IP $remote_addr;

        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;✅ Auto-generate APP_KEY  # Request SSL certificate

        proxy_set_header X-Forwarded-Proto $scheme;

    }✅ Profile untuk database (optional)  sudo certbot --nginx -d your-domain.com

}

``````



```bash## 📝 Notes

# Enable site

sudo ln -s /etc/nginx/sites-available/mds-logbook /etc/nginx/sites-enabled/**Alternatif**: Gunakan Cloudflare dengan Flexible SSL mode untuk SSL termination.

sudo nginx -t

sudo systemctl reload nginx- APP_KEY akan di-generate otomatis saat pertama kali start



# Setup SSL (Let's Encrypt)- Database migrations akan dijalankan otomatis### 4. Update Aplikasi (Production)

sudo certbot --nginx -d your-domain.com

```- Optimasi cache akan dilakukan otomatis



### 4. Backup Database- Frontend assets (Vite/npm) di-build dalam image```bash

```bash

# Backup- Tidak perlu setup manual, semua otomatis# Pull latest code

docker-compose exec db mysqldump -u root -p${DB_ROOT_PASSWORD} mds_logbook_db > backup.sql

- Database container bersifat optional (gunakan --profile db)git pull origin main

# Restore

docker-compose exec -T db mysql -u root -p${DB_ROOT_PASSWORD} mds_logbook_db < backup.sql



# Automated backup (crontab)---# Rebuild containers jika ada perubahan Dockerfile

0 2 * * * cd /path/to/mds-logbook && docker-compose exec -T db mysqldump -u root -pYOURPASSWORD mds_logbook_db > /backup/mds_$(date +\%Y\%m\%d).sql

```docker-compose up --build -d



## 🆚 Keunggulan Konfigurasi IniSimple, clean, dan production-ready! 🚀



| Fitur | Sebelumnya | Sekarang |````# Atau hanya restart jika perubahan code saja

|-------|------------|----------|

| Base Image | Debian (~500MB) | Alpine (~150MB) |docker-compose restart app

| Build Time | 5-10 menit | 2-3 menit |```

| Containers | 4 (app, web, node, db) | 3 (app, web, db) |

| Node Assets | Container terpisah | Built-in di image |## 🐛 Troubleshooting

| MySQL Port | Exposed (13306) | Internal only (secure) |

| Frontend | Mount dari host | Docker volume (consistent) |### Container Gagal Start

| Dependencies | Runtime install | Baked in image |

| Health Checks | Partial | Comprehensive |```bash

| APP_KEY | Manual | Auto-generated |# Cek logs detail

| Migrations | Manual | Auto-run |docker-compose logs app

docker-compose logs db

## 📝 Notes

# Cek status health check

- Image size: **~150MB** (Alpine-based, optimized)docker-compose ps

- Build time: **~2-3 menit** (single-stage, efficient)```

- **Tidak perlu** Node.js di host, semua di dalam container

- **Tidak perlu** setup manual, semua otomatis### Permission Errors

- Database **terisolasi**, tidak bisa diakses dari luar Docker network

- Frontend assets sudah ter-build dan siap pakai```bash

# Fix permission di VPS

## 📞 Supportsudo chown -R 33:33 storage/ bootstrap/cache/

sudo chmod -R 775 storage/ bootstrap/cache/

- **Issues:** [GitHub Issues](https://github.com/dzikrimustaqim/mds-logbook/issues)```

- **Documentation:** [README.md](../README.md)

- **Repository:** [mds-logbook](https://github.com/dzikrimustaqim/mds-logbook)### Database Connection Error



---```bash

# Pastikan database container healthy

**Production-ready, secure, dan efficient! 🚀**docker-compose ps db


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
