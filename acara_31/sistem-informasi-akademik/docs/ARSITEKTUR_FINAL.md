# Arsitektur Akhir - Sistem Informasi Akademik (MVC, PHP Native)

## 1. Alur request

```
Browser (views/*.html + JS fetch)  atau  Postman
              |  HTTPS + header Authorization: Bearer <token>
              v
public/.htaccess  ->  paksa HTTPS, teruskan header Authorization, rewrite ke index.php
              v
public/index.php  (Front Controller: CORS, preflight OPTIONS, muat config)
              v
Router            memilih Controller dari URL + method (PUT/DELETE via _method)
              v
Controller        Auth::requireAuth()/requireAdmin(), validasi, memanggil Model
              v
Model (PDO)       prepared statement ke MySQL (db_workshop)
              v
Response::json()  format seragam {success, message, data, errors, timestamp}
```

## 2. Struktur folder (hasil nyata dari project)

```
sistem-informasi-akademik/
|-- app/                      (di luar folder web saat produksi)
|   |-- config/   Database.php  config.php  env.php  env.example.php
|   |-- core/     autoload.php  Router.php  Response.php  Auth.php  Request.php  Upload.php
|   |-- models/   Mahasiswa.php  Buku.php  User.php
|   `-- controllers/  MahasiswaController.php  BukuController.php  AuthController.php
|-- public/                   (document root)
|   |-- index.php  .htaccess
|   |-- uploads/              foto mahasiswa
|   `-- views/                index.html login.html dashboard.html form_tambah.html form_edit.html
|                             js/ (config.js app.js login.js dashboard.js form.js)  css/style.css
|-- database/                 SQL, export, backup
|-- docs/                     dokumentasi API, OpenAPI, pengujian, deployment
|-- postman/                  collection dan environment
|-- scripts/                  backup_db.bat / backup_db.sh / restore_db.sh
|-- deploy/                   build_package.sh, contoh konfigurasi produksi
`-- storage/logs/             log aplikasi
```

## 3. Peran tiap lapisan

| Lapisan | Folder | Tanggung jawab |
|---------|--------|----------------|
| Model | app/models | Akses database (SQL + PDO), aturan data seperti cek NIM duplikat |
| View | public/views | Antarmuka HTML + JavaScript yang memanggil API lewat fetch |
| Controller | app/controllers | Menerima request, otorisasi, validasi, memilih response |
| Core | app/core | Router, Response, Auth (JWT), Upload, Request |
| Config | app/config | Koneksi DB dan pengaturan lingkungan |

## 4. Alur autentikasi (JWT)

1. `POST /login` -> `AuthController::login()` memeriksa `password_verify()` terhadap hash bcrypt di tabel `users`.
2. Server membuat token `header.payload.signature` (HS256, `hash_hmac` dengan `JWT_SECRET`), payload memuat `id`, `role`, `exp` (1 jam).
3. Client menyimpan token di `localStorage`, mengirimnya pada header `Authorization: Bearer <token>`.
4. `Auth::requireAuth()` memeriksa signature (dengan `hash_equals`) dan `exp`. `Auth::requireAdmin()` menambah pemeriksaan role.
5. Logout = membuang token di client (JWT stateless, server tidak menyimpan sesi).

## 5. Perjalanan proyek acara 17 - 30

| Acara | Hasil |
|-------|-------|
| 17 | Fondasi MVC: Router, Controller, Model, CRUD mahasiswa + buku |
| 18 | Response envelope, kode status 400/404/422, `errors` per field |
| 19 | Tabel users, register, password hash |
| 20 | Login + JWT, proteksi endpoint |
| 21-22 | Dokumentasi Markdown + OpenAPI/Swagger |
| 23 | 16 test case, 3 bug ditemukan dan diperbaiki |
| 24 | CORS + View daftar mahasiswa (fetch) |
| 25 | Login/dashboard, role admin vs user, ringkasan |
| 26 | Upload foto aman, backup/restore database |
| 27 | Form tambah/edit, notifikasi, validasi view |
| 28 | Paket deployment, pemisahan app/ dan public_html/ |
| 29 | Loading state, escaping XSS, retry, error feedback |
| 30 | HTTPS, kredensial terpisah, display_errors off, disaster recovery |
