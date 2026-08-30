#!/bin/bash
# Mencari lokasi PHP CLI yang sebenarnya (bukan wrapper CGI untuk web,
# yang sering ikut terpanggil kalau cron cuma memanggil "php" begitu saja).
# Dipakai otomatis oleh first-run.sh, deploy.sh, dan .cpanel.yml — tidak perlu dijalankan manual.

for candidate in \
    /opt/cpanel/ea-php83/root/usr/bin/php \
    /opt/cpanel/ea-php82/root/usr/bin/php \
    /opt/cpanel/ea-php81/root/usr/bin/php \
    /opt/cpanel/ea-php80/root/usr/bin/php \
    /usr/local/bin/php \
    /usr/bin/php; do
    if [ -x "$candidate" ] && "$candidate" -v 2>/dev/null | grep -qi "cli"; then
        echo "$candidate"
        exit 0
    fi
done

# Tidak ketemu kandidat yang cocok — pakai "php" polos sebagai jalan terakhir.
echo "php"
