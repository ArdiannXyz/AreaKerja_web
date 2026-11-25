# 🚀 Panduan Lengkap Deployment AreaKerja ke VPS (Ubuntu / Debian)

Dokumen ini berisi panduan langkah demi langkah untuk mengonfigurasi dan mendeploy aplikasi Laravel **AreaKerja** pada server VPS Linux (Ubuntu 22.04 / 24.04 LTS), termasuk konfigurasi **Cron Job otomatis**, **Nginx**, **PHP-FPM**, dan **Supervisor Queue Worker**.

---

## 📋 Daftar Isi
1. [Kebutuhan Server (Prerequisites)](#1-kebutuhan-server-prerequisites)
2. [Instalasi Stack (Nginx, PHP 8.2, MySQL, Composer)](#2-instalasi-stack)
3. [Setup Proyek & File .env](#3-setup-proyek--file-env)
4. [Konfigurasi Web Server Nginx](#4-konfigurasi-web-server-nginx)
5. [Konfigurasi Cron Job (Pembersihan Notifikasi & Jadwal Otomatis)](#5-konfigurasi-cron-job)
6. [Konfigurasi Supervisor Queue Worker](#6-konfigurasi-supervisor-queue-worker)
7. [Pemasangan SSL Gratis (HTTPS Certbot)](#7-pemasangan-ssl-gratis-https)
8. [Script Auto-Deploy (deploy.sh)](#8-script-auto-deploy)

---

## 1. Kebutuhan Server (Prerequisites)
- Server VPS: Minimal 1 vCPU, 2 GB RAM (disarankan 2 vCPU 4 GB RAM untuk performa optimal).
- Sistem Operasi: **Ubuntu 22.04 LTS** atau **Ubuntu 24.04 LTS**.
- Domain yang sudah diarahkan DNS A-Record ke IP publik VPS Anda.

---

## 2. Instalasi Stack

Jalankan perintah berikut di terminal server:

```bash
# Update repository server
sudo apt update && sudo apt upgrade -y

# Install dependensi dasar
sudo apt install -y software-properties-common curl git unzip zip certbot python3-certbot-nginx supervisor

# Tambahkan repository PHP Sury (PHP 8.2 / 8.3)
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP 8.2 dan ekstensi yang dibutuhkan Laravel
sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-bcmath \
    php8.2-curl php8.2-zip php8.2-gd php8.2-imagick php8.2-intl php8.2-soap

# Install Nginx
sudo apt install -y nginx

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

---

## 3. Setup Proyek & File .env

```bash
# Pindah ke direktori web server
cd /var/www

# Clone repository proyek (atau salin folder proyek Anda)
git clone https://github.com/ArdiannXyz/AreaKerja_web.git areakerja
cd areakerja

# Salin file environment
cp .env.example .env

# Sesuaikan isi .env (Database, URL, dan Konfigurasi Retensi Notifikasi)
nano .env
```

Pastikan variabel berikut diatur pada `.env`:
```ini
APP_NAME="AreaKerja"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://areakerja.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=areakerja_db
DB_USERNAME=areakerja_user
DB_PASSWORD=PasswordDatabaseAman123!

# Token keamanan untuk trigger cronjob internal
CRON_TOKEN=areakerja-super-secret-cron-token-2026

# Retensi Notifikasi: Otomatis hapus notifikasi lebih lama dari X bulan (misal: 3 atau 1)
NOTIFICATION_RETENTION_MONTHS=3
```

Install dependensi & migrasi database:
```bash
# Install dependensi PHP tanpa dev dependencies
composer install --no-dev --optimize-autoloader

# Generate application key & storage link
php artisan key:generate
php artisan storage:link

# Jalankan migrasi database
php artisan migrate --force

# Atur permission hak akses direktori storage dan cache
sudo chown -R www-data:www-data /var/www/areakerja
sudo chmod -R 775 /var/www/areakerja/storage /var/www/areakerja/bootstrap/cache
```

---

## 4. Konfigurasi Web Server Nginx

Salin file konfigurasi Nginx yang telah disediakan:
```bash
sudo cp deploy/nginx/areakerja.conf /etc/nginx/sites-available/areakerja.conf

# Sesuaikan nama domain dan path jika berbeda
sudo nano /etc/nginx/sites-available/areakerja.conf

# Aktifkan konfigurasi
sudo ln -s /etc/nginx/sites-available/areakerja.conf /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default

# Uji konfigurasi sintaks dan reload Nginx
sudo nginx -t
sudo systemctl reload nginx
```

---

## 5. Konfigurasi Cron Job (Pembersihan Notifikasi & Jadwal Otomatis)

Laravel menggunakan **satu baris cron master** yang mengeksekusi `php artisan schedule:run` setiap menit. Dari satu baris ini, Laravel secara otomatis mengeksekusi semua jadwal tugas:
- **Pembersihan Notifikasi Expired & Usia > 3 Bulan**: Setiap hari jam 02:00 WIB.
- **Pengecekan Transaksi Cash Expired**: Setiap jam.
- **Pengecekan Status Langganan Perusahaan**: Setiap hari jam 00:00 WIB.
- **Pengiriman Newsletter Mingguan**: Setiap Senin jam 08:00 WIB.

### Cara Memasang:
1. Buka editor cron server:
   ```bash
   crontab -e
   ```
2. Tempelkan baris berikut pada baris paling bawah:
   ```cron
   * * * * * cd /var/www/areakerja && php artisan schedule:run >> /dev/null 2>&1
   ```
3. Simpan dan keluar (Ctrl+O, Enter, Ctrl+X untuk nano).
4. Verifikasi bahwa cronjob sudah terdaftar:
   ```bash
   crontab -l
   ```

*Anda juga dapat menjalankan pembersihan notifikasi secara manual kapan saja dengan perintah:*
```bash
# Default (3 bulan & lewat expired_at):
php artisan notifikasi:hapus-expired

# Opsi khusus (misal 1 bulan):
php artisan notifikasi:hapus-expired --months=1
```

---

## 6. Konfigurasi Supervisor Queue Worker

Supervisor memastikan antrean tugas email, notifikasi, dan background jobs terus berjalan tanpa terhenti:

```bash
# Salin konfigurasi supervisor
sudo cp deploy/supervisor/areakerja-worker.conf /etc/supervisor/conf.d/areakerja-worker.conf

# Muat dan jalankan service
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start areakerja-worker:*

# Periksa status worker
sudo supervisorctl status
```

---

## 7. Pemasangan SSL Gratis (HTTPS)

Pasang sertifikat SSL dari Let's Encrypt untuk keamanan transaksi:
```bash
sudo certbot --nginx -d areakerja.com -d www.areakerja.com
```
Pilih opsi **Redirect HTTP ke HTTPS secara otomatis**.

---

## 8. Script Auto-Deploy (`deploy.sh`)

Untuk pembaruan kode selanjutnya, Anda tidak perlu menjalankan semua langkah manual di atas. Cukup jalankan script auto-deploy:

```bash
# Berikan izin eksekusi sekali saja
chmod +x deploy/deploy.sh

# Jalankan setiap kali ada update kode di git
./deploy/deploy.sh
```

Script `deploy.sh` akan otomatis:
1. Menyalakan halaman maintenance sementara.
2. Melakukan `git pull`.
3. Menjalankan `composer install --no-dev`.
4. Menjalankan migrasi database baru (`php artisan migrate --force`).
5. Membersihkan & mengoptimasi cache Laravel (`php artisan optimize`).
6. Me-restart queue worker supervisor.
7. Membuka kembali aplikasi dari mode maintenance.
