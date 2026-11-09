# Backup dan Restore Database - Acara 26

Database: `db_workshop` (tabel `users`, `mahasiswa`, `buku`).

## A. Backup lewat phpMyAdmin
1. Buka `http://localhost/phpmyadmin` (Laragon: menu Database -> phpMyAdmin).
2. Klik database **db_workshop** -> tab **Export**.
3. Metode **Quick**, format **SQL** -> **Export** (berkas `.sql` terunduh).

## B. Restore ke database percobaan (verifikasi cadangan)
1. phpMyAdmin -> **New** -> buat database `db_workshop_restore_test` (collation `utf8mb4_unicode_ci`).
2. Pilih database tersebut -> tab **Import** -> pilih berkas `.sql` -> **Import**.
3. Periksa tabel `users`, `mahasiswa`, `buku` sudah ada dan jumlah barisnya sama dengan database asal.
4. Setelah terverifikasi, database percobaan boleh dihapus.

## C. Hasil verifikasi (dijalankan dari command line)
Berkas cadangan: `database/backup/db_workshop_backup_acara26.sql`

| Tabel | Baris di db_workshop | Baris di db_workshop_restore_test |
|-------|---------------------|-----------------------------------|
| users | 2 | 2 |
| mahasiswa | 3 | 3 |
| buku | 2 | 2 |

Hasil: seluruh tabel dan data pulih identik.

## D. Backup lewat script (otomatis)
- Windows/Laragon: `scripts\backup_db.bat` (hasil: `database\backup\db_workshop_TANGGAL_JAM.sql`)
- Git Bash/Linux: `scripts/backup_db.sh`
- Restore: `scripts/restore_db.sh berkas.sql nama_db_tujuan`

## E. Penjadwalan backup (tugas mandiri 2)
Jadwal: **setiap akhir sesi praktikum**, contoh setiap hari pukul 17.00 (atau sesuai jadwal praktikum).

Windows Task Scheduler (jalankan Command Prompt sebagai Administrator):
```
schtasks /create /sc daily /st 17:00 /tn "Backup DB Workshop" /tr "C:\laragon\www\setoran-web\acara_26\sistem-informasi-akademik\scripts\backup_db.bat"
```
Cek: `schtasks /query /tn "Backup DB Workshop"`. Hapus: `schtasks /delete /tn "Backup DB Workshop"`.

Linux (cron), pukul 17.00 setiap hari:
```
0 17 * * * /path/ke/scripts/backup_db.sh
```

Kebijakan sederhana: simpan 7 backup terakhir, salin satu berkas ke penyimpanan lain (flashdisk/Google Drive) karena backup di laptop yang sama tidak melindungi dari laptop rusak.

## F. Catatan keamanan upload (terkait acara 26)
- Berkas foto di `public/uploads` **tidak ikut** database backup. Backup folder ini terpisah (salin foldernya).
- Folder uploads memiliki `.htaccess` yang memblokir eksekusi file PHP.
