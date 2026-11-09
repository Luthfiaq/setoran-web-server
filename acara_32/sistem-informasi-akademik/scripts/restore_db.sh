#!/bin/bash
# Restore backup ke database lain (untuk percobaan). Pemakaian: ./restore_db.sh berkas.sql nama_db_tujuan
set -e
BERKAS="$1"; TUJUAN="${2:-db_workshop_restore_test}"
mysql -u root -e "CREATE DATABASE IF NOT EXISTS \`$TUJUAN\` CHARACTER SET utf8mb4"
mysql -u root "$TUJUAN" < "$BERKAS"
echo "Restore ke database $TUJUAN selesai"
