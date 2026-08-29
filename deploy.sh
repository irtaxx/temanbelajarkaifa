#!/bin/bash
# Jalankan script ini di server (lewat Terminal cPanel) setiap kali ada update.
# Cukup: cd ~/temanbelajarkaifa && ./deploy.sh

set -e

echo "==> Menarik update terbaru dari GitHub..."
git pull origin main

echo "==> Menginstal/memperbarui dependency PHP..."
composer install --no-dev --optimize-autoloader

echo "==> Menjalankan migrasi database yang belum berjalan..."
php artisan migrate --force

echo "==> Membersihkan dan menyusun ulang cache..."
php artisan optimize:clear
php artisan optimize

echo "==> Selesai! Aplikasi sudah pakai kode terbaru."
