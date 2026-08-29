#!/bin/bash
# Dijalankan SEKALI lewat cPanel Cron Jobs saat setup pertama kali
# (tidak butuh Terminal). Setelah berhasil, hapus cron job ini.
set -e

cd "$(dirname "$0")/.."

echo "==> Generate application key..."
php artisan key:generate --force

echo "==> Menjalankan migrasi database..."
php artisan migrate --force

echo "==> Menghubungkan folder storage publik..."
[ -L public/storage ] || php artisan storage:link

echo "==> Menyusun cache..."
php artisan optimize:clear
php artisan optimize

echo "==> Mengatur izin folder storage..."
chmod -R 775 storage bootstrap/cache

echo "==> Selesai. Cek halaman login di browser, lalu hapus cron job ini."
