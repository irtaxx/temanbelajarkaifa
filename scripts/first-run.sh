#!/bin/bash
# Dijalankan SEKALI lewat cPanel Cron Jobs saat setup pertama kali
# (tidak butuh Terminal). Setelah berhasil, hapus cron job ini.
set -e

cd "$(dirname "$0")/.."

PHP_BIN="$(bash scripts/find-php.sh)"
echo "==> Pakai PHP CLI: $PHP_BIN ($($PHP_BIN -v | head -n1))"

echo "==> Generate application key..."
$PHP_BIN artisan key:generate --force

echo "==> Menjalankan migrasi database..."
$PHP_BIN artisan migrate --force

echo "==> Menghubungkan folder storage publik..."
[ -L public/storage ] || $PHP_BIN artisan storage:link

# Hanya dibersihkan, tidak di-cache ulang. Cache route yang basi pernah membuat
# Edit/Hapus gagal diam-diam, sementara manfaatnya untuk aplikasi sekecil ini tipis.
echo "==> Membersihkan cache..."
$PHP_BIN artisan optimize:clear

echo "==> Menyalin aset statis ke public_html..."
bash scripts/sync-public.sh

echo "==> Mengatur izin folder storage..."
chmod -R 775 storage bootstrap/cache

echo "==> Selesai. Cek halaman login di browser, lalu hapus cron job ini."
