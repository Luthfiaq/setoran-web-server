# SI Akademik (PHP Native MVC + MySQL)

Project praktikum BKPM Workshop SI Web Server.

## Cara menjalankan (Laragon)
1. Letakkan folder di `C:\laragon\www\setoran-web\acara_N\si-akademik`, lalu **Start All**.
2. Import database berurutan lewat phpMyAdmin (Import) atau command line:
   ```
   mysql -u root < database/01_schema.sql
   mysql -u root < database/02_seed.sql
   mysql -u root < database/03_tugas_mandiri_status.sql
   mysql -u root < database/04_data_tambahan_opsional.sql   (opsional, agar pagination terlihat)
   ```
3. Buka `http://localhost/setoran-web/acara_N/si-akademik/public/`
4. Login: **admin / admin123**

Halaman: `/mahasiswa`, `/prodi`, `/matakuliah` (setelah login).
