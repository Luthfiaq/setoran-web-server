#!/bin/bash
# Menyusun paket siap-upload untuk hosting cPanel (versi acara 30: kredensial terpisah di env.php)
#   hasil/app/          -> unggah SEJAJAR public_html (di luar folder web)
#   hasil/public_html/  -> isi folder public/
#   hasil/db_workshop.sql -> import lewat phpMyAdmin cPanel
# Jalankan dari folder project:  bash deploy/build_package.sh
set -e
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
OUT="$ROOT/deploy/hasil"
rm -rf "$OUT"; mkdir -p "$OUT/app" "$OUT/public_html" "$OUT/app/../storage/logs"

cp -r "$ROOT/app/." "$OUT/app/"
cp -r "$ROOT/public/." "$OUT/public_html/"
cp "$ROOT/database/export/db_workshop.sql" "$OUT/db_workshop.sql"
mkdir -p "$OUT/storage/logs"; touch "$OUT/storage/logs/.gitkeep"

# env.php LOKAL tidak boleh ikut. Yang dibawa: env.example.php sebagai env.php untuk diisi di server.
rm -f "$OUT/app/config/env.php"
cp "$ROOT/app/config/env.example.php" "$OUT/app/config/env.php"
sed -i "s#// 'upload_path'#'upload_path'#" "$OUT/app/config/env.php"

# Foto uji tidak ikut diunggah
find "$OUT/public_html/uploads" -type f ! -name '.gitkeep' ! -name '.htaccess' -delete

echo "Paket siap di: $OUT"
echo "WAJIB sebelum/sesudah unggah: isi hasil/app/config/env.php (database cPanel, jwt_secret acak, cors_origin domain)."
echo "Folder hasil/storage (log) diunggah sejajar app/ dan public_html/."
