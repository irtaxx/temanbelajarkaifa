#!/bin/bash
# Menyalin aset statis (images, css, js, dll) dari public/ aplikasi ke public_html,
# karena di hosting ini public_html hanya berisi index.php + .htaccess penunjuk.
#
# index.php dan .htaccess di public_html SENGAJA tidak ditimpa — keduanya versi
# khusus hosting yang menunjuk ke aplikasi di luar public_html.
#
# Dipanggil otomatis oleh deploy.sh dan first-run.sh.

WEB_ROOT="${WEB_ROOT:-$HOME/public_html}"

if [ ! -d "$WEB_ROOT" ]; then
    echo "    (lewati: $WEB_ROOT tidak ditemukan)"
    exit 0
fi

# Kalau public_html adalah symlink ke public/, tidak perlu disalin apa pun.
if [ -L "$WEB_ROOT" ]; then
    echo "    (lewati: public_html berupa symlink, aset sudah otomatis ikut)"
    exit 0
fi

shopt -s nullglob
for item in public/*; do
    name="$(basename "$item")"
    case "$name" in
        index.php|.htaccess) continue ;;
    esac
    cp -r "$item" "$WEB_ROOT/"
    echo "    disalin: $name"
done
