# Simulasi Pemulihan Bencana (Disaster Recovery) - Acara 30

**Skenario:** salah satu tabel (`mahasiswa`) terhapus tidak sengaja pada basis data. Data dipulihkan dari berkas backup acara 26
(`database/backup/db_workshop_backup_acara26.sql`). Simulasi dilakukan pada **database percobaan** (`db_workshop_dr`), bukan database asli.

## Langkah
1. Buat database percobaan, isi dari backup (kondisi awal sehat).
2. Hapus tabel: `DROP TABLE mahasiswa;`
3. Buktikan data hilang (query ke tabel mahasiswa menghasilkan error 1146 *table doesn't exist*).
4. Pulihkan: `mysql -u root db_workshop_dr < db_workshop_backup_acara26.sql`
   (berkas backup memuat `DROP TABLE IF EXISTS` + `CREATE TABLE` + `INSERT` sehingga tabel dibangun ulang lengkap dengan datanya).
5. Verifikasi jumlah baris dan isi data.
6. Hapus database percobaan.

## Hasil eksekusi nyata
```
$ mysql -u root -e "CREATE DATABASE db_workshop_dr"
$ mysql -u root db_workshop_dr < db_workshop_backup_acara26.sql   (database percobaan diisi dari backup)

--- KONDISI AWAL
+-----------+-------+
| tabel     | baris |
+-----------+-------+
| users     |     2 |
| mahasiswa |     3 |
| buku      |     2 |
+-----------+-------+

--- BENCANA: tabel mahasiswa terhapus
$ DROP TABLE mahasiswa;
$ SHOW TABLES;
+--------------------------+
| Tables_in_db_workshop_dr |
+--------------------------+
| buku                     |
| users                    |
+--------------------------+
$ SELECT * FROM mahasiswa;
--------------

--- PEMULIHAN dari backup acara 26
$ mysql -u root db_workshop_dr < db_workshop_backup_acara26.sql

--- KONDISI SETELAH PEMULIHAN
+-----------+-------+
| tabel     | baris |
+-----------+-------+
| users     |     2 |
| mahasiswa |     3 |
| buku      |     2 |
+-----------+-------+
+-----------+--------------+--------------------+
| nim       | nama         | prodi              |
+-----------+--------------+--------------------+
| 220100101 | Andi Saputra | Teknik Informatika |
| 220100102 | Siti Rahma   | Sistem Informasi   |
| 220100103 | Budi Hartono | Teknik Komputer    |
+-----------+--------------+--------------------+
```

## Kesimpulan
- Tabel `mahasiswa` pulih dengan 3 baris data yang sama seperti sebelum bencana.
- Pemulihan hanya sebaik backup terakhir: data yang dibuat setelah backup akan hilang -> backup rutin (lihat `docs/BACKUP_RESTORE.md`).
- Foto di `public/uploads` tidak ada di backup SQL, sehingga folder ini perlu dicadangkan terpisah.
