#!/bin/bash
# Menyusun paket siap-upload untuk hosting cPanel:
#   hasil/app/          -> unggah SEJAJAR public_html (di luar folder web)
#   hasil/public_html/  -> isi folder public/ (unggah ke public_html)
#   hasil/db_workshop.sql -> import lewat phpMyAdmin cPanel
# Jalankan dari folder project:  bash deploy/build_package.sh
set -e
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
OUT="$ROOT/deploy/hasil"
rm -rf "$OUT"; mkdir -p "$OUT/app" "$OUT/public_html"

cp -r "$ROOT/app/." "$OUT/app/"
cp -r "$ROOT/public/." "$OUT/public_html/"
cp "$ROOT/database/export/db_workshop.sql" "$OUT/db_workshop.sql"

# Foto contoh/uji tidak ikut diunggah (isi folder uploads dikosongkan)
find "$OUT/public_html/uploads" -type f ! -name '.gitkeep' ! -name '.htaccess' -delete

# Pada struktur hosting, uploads berada di public_html/uploads
sed -i "s#/../../public/uploads/#/../../public_html/uploads/#" "$OUT/app/config/config.php"
sed -i "s#define('APP_ENV', 'development')#define('APP_ENV', 'production')#" "$OUT/app/config/config.php"

echo "Paket siap di: $OUT"
echo "Sebelum diunggah, edit hasil/app/config/Database.php (kredensial cPanel) dan JWT_SECRET di hasil/app/config/config.php"
