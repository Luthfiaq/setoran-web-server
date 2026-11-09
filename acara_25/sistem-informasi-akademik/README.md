# Sistem Informasi Akademik (PHP Native MVC + REST API)

## Cara menjalankan (Laragon)
1. Letakkan folder di `C:\laragon\www\setoran-web\acara_N\sistem-informasi-akademik`, lalu **Start All**.
2. Import database, berurutan sesuai nomor yang ada di folder `database/`:
   - `db_workshop.sql` (selalu), `02_users.sql` (mulai acara 19), `03_foto.sql` (mulai acara 26)
   - Cara cepat: phpMyAdmin -> Import, atau `mysql -u root < database/db_workshop.sql`
3. Uji API dengan Postman:
   `http://localhost/setoran-web/acara_N/sistem-informasi-akademik/public/mahasiswa`
4. Mulai acara 24 tersedia tampilan web:
   `http://localhost/setoran-web/acara_N/sistem-informasi-akademik/public/views/index.html`
   (acara 25+: `views/login.html`)

## Akun uji (acara 19+)
| Username | Password | Role |
|----------|----------|------|
| admin | rahasia123 | admin |
| budi | user12345 | user |

## Struktur
`app/` (config, core, models, controllers) - `public/` (index.php, views, uploads) - `database/` - `docs/` - `postman/`
