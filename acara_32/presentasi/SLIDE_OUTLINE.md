# Kerangka Slide Presentasi (10-12 slide)

1. **Judul**: Sistem Informasi Akademik - PHP Native MVC + REST API (nama, NIM, mata kuliah)
2. **Latar belakang & tujuan**: kebutuhan mengelola data akademik; target: backend lengkap (CRUD, autentikasi, upload, deployment)
3. **Teknologi**: PHP 8 native, MySQL (PDO), Apache (.htaccess rewrite), Bootstrap 5, Postman, Git/GitHub
4. **Arsitektur MVC**: diagram alur (Client -> .htaccess -> index.php -> Router -> Controller -> Model -> DB -> JSON). Sertakan struktur folder `app/` dan `public/`
5. **Database**: tabel `users`, `mahasiswa`, `buku` (ERD sederhana) dan alasan prepared statement
6. **Autentikasi & otorisasi**: alur JWT (login -> token -> Bearer), password hash, role admin/user, 401 vs 403
7. **Fitur utama**: CRUD, validasi 400/422, upload foto aman, dashboard + ringkasan (screenshot)
8. **Keamanan**: prepared statement, password_hash, JWT signature, upload (exif_imagetype, rename, 2MB), escape XSS, CORS, CSRF (si-akademik), HTTPS, kredensial terpisah
9. **Pengujian**: tabel 16 test case, 3 bug ditemukan (PUT/DELETE id tidak ada, PUT body kosong) lalu diperbaiki, hasil 16/16 PASS
10. **Deployment**: struktur `app/` di luar `public_html/`, paket deploy, HTTPS, display_errors Off, backup & disaster recovery
11. **Demo langsung** (lihat SKENARIO_DEMO.md)
12. **Kesimpulan & pengembangan**: pelajaran, keterbatasan (token tanpa refresh/blacklist, belum ada rate limit), rencana (pagination API, refresh token, unit test otomatis)
