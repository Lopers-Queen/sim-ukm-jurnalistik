# PANDUAN DEPLOYMENT — SIM UKM Jurnalistik
## Platform Gratis (Railway, Oracle Cloud, Fly.io)

---

## Daftar Isi

1. [Opsi Platform Gratis](#1-opsi-platform-gratis)
2. [Persiapan Sebelum Deploy](#2-persiapan-sebelum-deploy)
3. [Opsi A: Deploy ke Railway (Paling Mudah)](#3-opsi-a-deploy-ke-railway)
4. [Opsi B: Deploy ke Oracle Cloud Always Free (Paling Powerful)](#4-opsi-b-deploy-ke-oracle-cloud)
5. [Opsi C: Deploy ke Fly.io (Docker)](#5-opsi-c-deploy-ke-flyio)
6. [Konfigurasi Environment Production](#6-konfigurasi-environment)
7. [Verifikasi & Troubleshooting](#7-verifikasi)

---

## 1. Opsi Platform Gratis

| Platform | Gratisnya | Database | Kelebihan | Kekurangan |
|----------|-----------|----------|-----------|-----------|
| **Railway** | $5 credit/bulan | MySQL included | Paling mudah, auto-deploy dari GitHub | Credit bisa habis jika traffic tinggi |
| **Oracle Cloud** | Always Free VM (4 CPU, 24GB RAM) | MySQL/SQLite sendiri | Sangat powerful, benar-benar gratis selamanya | Setup lebih rumit |
| **Fly.io** | 3 shared VM gratis | SQLite/MySQL | Docker-based, global | Butuh credit card untuk verifikasi |

**Rekomendasi:**
- Pemula / ingin cepat → **Railway**
- Ingin gratis selamanya + powerful → **Oracle Cloud**
- Suka Docker → **Fly.io**

---

## 2. Persiapan Sebelum Deploy

### 2.1 Push Kode ke GitHub

```bash
# Pastikan sudah punya akun GitHub & repo
git init
git add .
git commit -m "Initial commit - SIM UKM Jurnalistik"
git branch -M main
git remote add origin https://github.com/USERNAME/sim-ukm-jurnalistik.git
git push -u origin main
```

### 2.2 Pastikan File Penting Sudah Ada

File yang sudah disiapkan di project ini:

| File | Fungsi | Untuk Platform |
|------|--------|---------------|
| `nixpacks.toml` | Build config (install deps + build assets) | Railway |
| `Dockerfile` | Multi-stage Docker build | Fly.io, Oracle (Docker) |
| `docker/nginx.conf` | Nginx web server config | Docker |
| `docker/php-fpm.conf` | PHP-FPM process manager | Docker |
| `docker/php.ini` | PHP production settings | Docker |
| `docker/supervisord.conf` | Process supervisor (nginx+fpm+queue) | Docker |
| `docker/entrypoint.sh` | Startup script (migrate, cache, seed) | Docker |
| `.dockerignore` | Exclude files from Docker image | Docker |

---

## 3. Opsi A: Deploy ke Railway

### Langkah-langkah:

**Step 1: Buat Akun Railway**
- Buka https://railway.app
- Login dengan GitHub

**Step 2: Buat Project Baru**
- Klik "New Project" → "Deploy from GitHub repo"
- Pilih repo `sim-ukm-jurnalistik`

**Step 3: Tambah Database MySQL**
- Klik "+ Add" → "Database" → "MySQL"
- Railway akan otomatis membuat MySQL instance dan inject environment variables:
  - `MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, `MYSQLPASSWORD`

**Step 4: Konfigurasi Environment Variables**

Di Settings → Variables, tambahkan:

```
APP_NAME=SIM UKM Jurnalistik
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.up.railway.app
APP_TIMEZONE=Asia/Makassar

# Database (map Railway MySQL ke Laravel format)
DB_CONNECTION=mysql
DB_HOST=${{MYSQLHOST}}
DB_PORT=${{MYSQLPORT}}
DB_DATABASE=${{MYSQLDATABASE}}
DB_USERNAME=${{MYSQLUSER}}
DB_PASSWORD=${{MYSQLPASSWORD}}

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# Mail (gunakan Mailtrap free atau Gmail SMTP)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_user
MAIL_PASSWORD=your_mailtrap_pass
MAIL_FROM_ADDRESS=noreply@ukmjurnalistik.com
MAIL_FROM_NAME=SIM UKM Jurnalistik

# Seed database (set true sekali saat pertama deploy)
SEED_DATABASE=true

# Nixpacks PHP version
NIXPACKS_PHP_VERSION=8.2
```

**Step 5: Deploy Settings**

Di Settings → Deploy:
- Branch: `main`
- Auto-deploy: Yes
- Root directory: `/` (default)

**Step 6: Tunggu Build Selesai**

Railway akan otomatis:
1. Detect `nixpacks.toml`
2. Install PHP 8.2, Composer, Node.js 20
3. `composer install --no-dev`
4. `npm ci && npm run build`
5. Start server dengan `php artisan serve`

**Step 7: Jalankan Migration**

Setelah deploy berhasil, buka Railway Terminal:
```bash
# Di Railway dashboard → Your Service → Terminal
php artisan migrate --force
php artisan db:seed --force    # jika perlu seed data
php artisan storage:link
```

**Step 8: Generate Domain**

Di Settings → Networking → "Generate Domain"
- Akan dapat domain seperti: `sim-ukm-jurnalistik-production.up.railway.app`

### Estimasi Penggunaan Credit Railway:

| Komponen | Estimasi/Bulan |
|----------|---------------|
| App (512MB RAM) | ~$3-4 |
| MySQL (shared) | ~$0-1 |
| **Total** | ~$3-5 (masih dalam $5 free) |

---

## 4. Opsi B: Deploy ke Oracle Cloud

Oracle Cloud memberikan **Always Free**:
- 2 AMD VM (1/8 OCPU, 1GB RAM masing-masing)
- 4 ARM VM (total 4 OCPU, 24GB RAM!) ← **paling worth it**
- 200GB storage
- 10TB outbound bandwidth/bulan

### Langkah Singkat:

**Step 1: Daftar Oracle Cloud Free Tier**
- https://www.oracle.com/cloud/free/
- Butuh credit card untuk verifikasi (TIDAK di-charge)

**Step 2: Buat VM Instance (ARM Ampere A1)**
- Pilih: VM → ARM Ampere A1 (Always Free eligible)
- OS: Ubuntu 22.04
- Shape: VM.Standard.A1.Flex (4 OCPU, 24GB RAM)

**Step 3: SSH ke Server & Install Dependencies**
```bash
ssh ubuntu@IP_SERVER -i your-key.pem

# Update
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-mysql \
  php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip \
  php8.2-gd php8.2-bcmath php8.2-sqlite3 php8.2-intl

# Install Nginx, MySQL, Node.js, Composer
sudo apt install -y nginx mysql-server
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

**Step 4: Setup MySQL**
```bash
sudo mysql_secure_installation
sudo mysql -u root -p
```
```sql
CREATE DATABASE sim_ukm_jurnalistik;
CREATE USER 'simukm'@'localhost' IDENTIFIED BY 'PasswordKuat123!';
GRANT ALL ON sim_ukm_jurnalistik.* TO 'simukm'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**Step 5: Deploy Aplikasi**
```bash
cd /var/www
sudo git clone https://github.com/USERNAME/sim-ukm-jurnalistik.git
cd sim-ukm-jurnalistik

sudo composer install --optimize-autoloader --no-dev
sudo npm install && sudo npm run build

sudo cp .env.example .env
sudo nano .env    # edit sesuai konfigurasi production

sudo php artisan key:generate
sudo php artisan migrate --force
sudo php artisan db:seed --force    # jika perlu
sudo php artisan storage:link
sudo php artisan config:cache
sudo php artisan route:cache
sudo php artisan view:cache

sudo chown -R www-data:www-data /var/www/sim-ukm-jurnalistik
sudo chmod -R 775 storage bootstrap/cache
```

**Step 6: Setup Nginx**
```bash
sudo nano /etc/nginx/sites-available/sim-ukm
```

```nginx
server {
    listen 80;
    server_name your-domain.com;  # atau IP server
    root /var/www/sim-ukm-jurnalistik/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;
    client_max_body_size 10M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/sim-ukm /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
```

**Step 7: SSL (HTTPS Gratis)**
```bash
# Butuh domain yang diarahkan ke IP server
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
```

**Step 8: Setup Cron (Scheduler)**
```bash
sudo crontab -e
# Tambahkan:
* * * * * cd /var/www/sim-ukm-jurnalistik && php artisan schedule:run >> /dev/null 2>&1
```

**Step 9: Buka Firewall**
```bash
sudo iptables -I INPUT 6 -m state --state NEW -p tcp --dport 80 -j ACCEPT
sudo iptables -I INPUT 6 -m state --state NEW -p tcp --dport 443 -j ACCEPT
sudo netfilter-persistent save
```

---

## 5. Opsi C: Deploy ke Fly.io

**Step 1: Install Fly CLI**
```bash
# Windows PowerShell
irm https://fly.io/install.ps1 | iex

# Login
fly auth login
```

**Step 2: Launch App**
```bash
cd "c:\Users\Paulina\UKM Jurnalistik"
fly launch --no-deploy
```

Saat ditanya:
- App name: `sim-ukm-jurnalistik`
- Region: `sin` (Singapore, terdekat)
- Database: Pilih MySQL (atau skip, setup manual)

**Step 3: Set Secrets**
```bash
fly secrets set APP_KEY="base64:xxx" \
  APP_ENV=production \
  APP_DEBUG=false \
  DB_CONNECTION=mysql \
  DB_HOST=your-mysql-host \
  DB_DATABASE=sim_ukm_jurnalistik \
  DB_USERNAME=user \
  DB_PASSWORD=password
```

**Step 4: Deploy**
```bash
fly deploy
```

**Step 5: Jalankan Migration**
```bash
fly ssh console -C "php artisan migrate --force"
fly ssh console -C "php artisan storage:link"
```

---

## 6. Konfigurasi Environment

### .env Production (Wajib Diubah)

```env
# ── App ──────────────────────────────────────────────────
APP_NAME="SIM UKM Jurnalistik"
APP_ENV=production
APP_DEBUG=false                    # WAJIB false!
APP_URL=https://your-domain.com
APP_TIMEZONE=Asia/Makassar

# ── Database ─────────────────────────────────────────────
# Opsi 1: SQLite (simple, cocok untuk Oracle Cloud)
DB_CONNECTION=sqlite

# Opsi 2: MySQL (Railway / production serius)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=sim_ukm_jurnalistik
# DB_USERNAME=user
# DB_PASSWORD=password

# ── Session & Cache ──────────────────────────────────────
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# ── Mail (untuk reset password) ──────────────────────────
# Opsi gratis: Mailtrap (https://mailtrap.io) — 100 email/bulan gratis
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@ukmjurnalistik.com
MAIL_FROM_NAME="SIM UKM Jurnalistik"
```

---

## 7. Verifikasi

### Checklist Setelah Deploy

| No | Cek | Cara |
|----|-----|------|
| 1 | Homepage redirect ke login | Buka `https://your-app.com` |
| 2 | Login berfungsi | Login dengan NIM + password |
| 3 | Database migration OK | `php artisan migrate:status` |
| 4 | Storage link aktif | Upload foto profil, cek muncul |
| 5 | Queue berjalan | Generate surat pernyataan, cek PDF |
| 6 | Mail terkirim | Test reset password |
| 7 | HTTPS aktif | Cek gembok di browser |
| 8 | Error tidak bocor | Buka URL random, harus 404 bukan stack trace |

### Troubleshooting Umum

| Masalah | Solusi |
|---------|--------|
| 500 Internal Server Error | Cek `storage/logs/laravel.log` |
| Foto tidak muncul | Jalankan `php artisan storage:link` |
| Migration gagal | Cek koneksi database di `.env` |
| CSS/JS tidak load | Jalankan `npm run build` di server |
| Permission denied | `chmod -R 775 storage bootstrap/cache` |
| APP_KEY error | `php artisan key:generate` |
| Queue tidak jalan | Cek supervisor/queue worker berjalan |

---

## File Konfigurasi yang Sudah Dibuat

```
sim-ukm-jurnalistik/
├── nixpacks.toml              ← Railway build config
├── Dockerfile                 ← Docker multi-stage build
├── .dockerignore              ← Exclude files dari Docker
├── docker/
│   ├── nginx.conf             ← Nginx web server
│   ├── php-fpm.conf           ← PHP-FPM process manager
│   ├── php.ini                ← PHP production settings
│   ├── supervisord.conf       ← Process supervisor
│   └── entrypoint.sh          ← Startup script
└── docs/
    └── PANDUAN-DEPLOYMENT.md  ← Dokumen ini
```

---

**Dokumen ini adalah panduan deployment untuk SIM UKM Jurnalistik.**
*Pilih platform yang sesuai dengan kebutuhan dan kemampuan teknis tim.*
