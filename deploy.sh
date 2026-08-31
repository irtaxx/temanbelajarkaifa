#!/bin/bash
# Jalankan script ini di server (lewat Terminal cPanel, kalau tersedia) setiap kali ada update.
# Cukup: cd ~/temanbelajarkaifa && ./deploy.sh

set -e

echo "==> Menarik update terbaru dari GitHub..."
git pull origin main

PHP_BIN="$(bash scripts/find-php.sh)"

echo "==> Menjalankan migrasi database yang belum berjalan..."
$PHP_BIN artisan migrate --force

echo "==> Membersihkan dan menyusun ulang cache..."
$PHP_BIN artisan optimize:clear
$PHP_BIN artisan optimize

echo "==> Menyalin aset statis ke public_html..."
bash scripts/sync-public.sh

echo "==> Selesai! Aplikasi sudah pakai kode terbaru."
