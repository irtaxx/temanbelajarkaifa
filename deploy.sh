#!/bin/bash
# Jalankan script ini di server setiap kali ada update.
#   Lewat Terminal : cd ~/temanbelajarkaifa && ./deploy.sh
#   Lewat Cron Jobs: bash /home/NAMAUSER/temanbelajarkaifa/deploy.sh > /home/NAMAUSER/deploy.log 2>&1

set -e

echo "==> Menarik update terbaru dari GitHub..."
git pull origin main

PHP_BIN="$(bash scripts/find-php.sh)"
echo "==> Pakai PHP CLI: $PHP_BIN"

# Cache dibersihkan SEBELUM migrasi. Kalau migrasi gagal dan script berhenti di
# situ, aplikasi tetap memakai route & config terbaru — bukan versi cache lama
# yang bikin route model binding meleset tanpa pesan error.
echo "==> Membersihkan cache lama..."
$PHP_BIN artisan optimize:clear

echo "==> Menyalin aset statis ke public_html..."
bash scripts/sync-public.sh

echo "==> Menjalankan migrasi database yang belum berjalan..."
$PHP_BIN artisan migrate --force

# Sengaja tidak menjalankan "artisan optimize" (cache route/config/view).
# Untuk aplikasi sekecil ini bedanya tidak terasa, sementara cache route yang
# basi pernah menyebabkan Edit/Hapus gagal diam-diam meski kodenya sudah benar.
echo "==> Selesai! Aplikasi sudah pakai kode terbaru."
