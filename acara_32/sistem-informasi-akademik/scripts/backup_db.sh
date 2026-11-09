#!/bin/bash
# Backup database db_workshop (Linux/Mac/Git Bash). Hasil: database/backup/db_workshop_TANGGAL_JAM.sql
set -e
FOLDER="$(dirname "$0")/../database/backup"
mkdir -p "$FOLDER"
FILE="$FOLDER/db_workshop_$(date +%Y-%m-%d_%H%M).sql"
mysqldump -u root --routines db_workshop > "$FILE"
echo "Backup berhasil: $FILE"
