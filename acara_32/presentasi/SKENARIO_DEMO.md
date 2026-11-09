# Skenario Demo Presentasi (±15 menit)

Siapkan sebelum mulai: Laragon **Start All**, database sudah diimport (`database/db_workshop.sql`, `02_users.sql`, `03_foto.sql`),
Postman terbuka dengan Collection + Environment **Local** dari folder `postman/`, browser terbuka di halaman login.

URL dasar: `http://localhost/setoran-web/acara_31/sistem-informasi-akademik/public`

| Menit | Bagian | Yang dilakukan | Yang dijelaskan |
|-------|--------|----------------|-----------------|
| 0-2 | Pembuka | Tampilkan `docs/ARSITEKTUR_FINAL.md` (alur request + struktur folder) | Tema: Sistem Informasi Akademik, PHP Native MVC, REST API + View |
| 2-4 | Arsitektur | Buka `public/index.php`, `app/core/Router.php`, satu Controller, satu Model | Front Controller, Router memilih Controller, Controller tidak menulis SQL, Model memakai prepared statement |
| 4-6 | Autentikasi | Postman: **POST login (admin)** -> tunjukkan token. Salin token ke jwt.io (atau jelaskan 3 bagian token) | password_verify, token HS256, `exp`, token disimpan otomatis di `{{token}}` |
| 6-8 | CRUD + proteksi | **POST tanpa token** -> 401. **POST dengan token** -> 201. **DELETE sebagai user biasa (budi)** -> 403 | 401 (belum login) vs 403 (login tapi bukan admin) |
| 8-10 | Validasi | POST NIM kosong -> 400, NIM 7 digit -> 422, NIM duplikat -> 422 | Beda 400 dan 422, field `errors` per input |
| 10-13 | Tampilan web | `views/login.html` login admin -> dashboard (ringkasan, tabel) -> Tambah mahasiswa + foto -> Edit -> Hapus | FormData upload, toast notifikasi, tombol Hapus hanya untuk admin |
| 13-14 | Keamanan | Tambah mahasiswa dengan nama `<b>Coba</b>` -> tampil sebagai teks | escapeHtml mencegah XSS |
| 14-15 | Bukti & penutup | Tunjukkan `docs/TEST_CASE.md` (16 test case, 3 bug diperbaiki), Postman Run Collection, `docs/DEPLOYMENT.md` | Pengujian, deployment, HTTPS, backup & disaster recovery |

## Cadangan jika ada masalah saat demo
- API tidak jalan: periksa Apache aktif dan `mod_rewrite` (Laragon: Menu -> Apache -> Modules). Coba `.../public/mahasiswa` di browser.
- Selalu 401 setelah login: token kedaluwarsa (berlaku 1 jam) atau variabel `{{token}}` kosong -> login ulang.
- Foto gagal diunggah: ukuran > 2MB atau bukan JPG/PNG.
- Halaman view kosong: periksa `BASE_URL` di `views/js/config.js` (otomatis dari alamat halaman) dan buka lewat `http://localhost/...`, bukan `file://`.

## Project pendamping (acara 6-14): si-akademik (aplikasi web server-rendered)
`http://localhost/setoran-web/acara_14/si-akademik/public/` -> login **admin / admin123** -> CRUD Mahasiswa/Prodi/Mata Kuliah.
Tunjukkan: Repository + Dependency Injection, Service layer (validasi NIM duplikat), CSRF token, log di `storage/logs/app.log`.
