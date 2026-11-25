#!/usr/bin/env bash
# ==============================================================================
# AUTOMATED DEPLOYMENT SCRIPT - AREA KERJA (VPS)
# ==============================================================================
# Script ini mengotomatiskan update kode, migrasi database, dan optimasi cache.
#
# Cara menjalankan di VPS:
#   chmod +x deploy/deploy.sh
#   ./deploy/deploy.sh
# ==============================================================================

set -e

echo "🚀 [1/8] Memulai proses deployment Area Kerja..."

# Aktifkan maintenance mode sementara
echo "🔒 [2/8] Mengaktifkan mode maintenance..."
php artisan down --render="errors::503" --secret="areakerja-bypass-secret" || true

# Tarik perubahan kode terbaru dari Git
echo "📥 [3/8] Mengambil pembaruan dari Git..."
git pull origin develop

# Install / update dependency PHP produksi
echo "📦 [4/8] Menginstall dependensi Composer (No-Dev Optimized)..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Jalankan migrasi database
echo "🗄️ [5/8] Menjalankan migrasi database..."
php artisan migrate --force

# Pastikan symbolic link storage aktif
echo "🔗 [6/8] Memverifikasi storage link..."
php artisan storage:link || true

# Optimasi cache konfigurasi, route, dan view Laravel
echo "⚡ [7/8] Mengoptimasi cache Laravel..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize

# Restart queue worker dan nonaktifkan maintenance mode
echo "🔄 [8/8] Me-restart Queue Worker & Mematikan maintenance mode..."
php artisan queue:restart || true
sudo supervisorctl restart areakerja-worker:* 2>/dev/null || true
sudo systemctl reload php8.2-fpm 2>/dev/null || true

php artisan up

echo "✅ Deployment Area Kerja Berhasil Selesai!"
