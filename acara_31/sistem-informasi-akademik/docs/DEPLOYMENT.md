# Panduan Deployment ke Hosting cPanel - Acara 28

Proyek: Sistem Informasi Akademik (PHP Native MVC + API JSON + View HTML).

## Struktur di hosting (pemisahan MVC)

```
/home/namacpanel/
 |-- app/                <- Model, Controller, Core, config (DI LUAR folder web)
 |     |-- config/  core/  models/  controllers/
 |-- public_html/        <- isi folder public/ (Front Controller + View)
       |-- index.php  .htaccess
       |-- views/        (HTML, JS, CSS)
       |-- uploads/      (foto mahasiswa)
```
`public_html/index.php` memanggil `../app/...`, sehingga kode PHP inti tidak bisa dibuka dari browser.

## Checklist pra-deployment (Langkah 1)

| No | Item | Status | Bukti |
|----|------|--------|-------|
| 1 | Seluruh test case acara 23 berstatus Pass | OK | 16/16 PASS (dijalankan ulang pada kode acara 28) |
| 2 | Tidak ada kredensial sensitif ter-hardcode di luar `app/config/` | OK | `Auth::SECRET` dipindah ke `JWT_SECRET` pada `app/config/config.php` |
| 3 | `.htaccess` penolak akses pada `app/`, `database/`, `scripts/`, `deploy/` | OK | akses langsung ke `app/config/Database.php` -> 403 (lokal) / 404 (struktur hosting) |
| 4 | Berkas SQL siap import | OK | `database/export/db_workshop.sql` |
| 5 | Mode aplikasi production | OK | `deploy/build_package.sh` mengubah `APP_ENV` menjadi `production` |
| 6 | Foto uji tidak ikut diunggah | OK | folder `uploads` dikosongkan oleh skrip paket |

## Langkah deployment

### 1. Ekspor database (Langkah 2)
Sudah tersedia: `database/export/db_workshop.sql` (hasil `mysqldump`, berisi struktur + data tabel `users`, `mahasiswa`, `buku`).
Cara manual: phpMyAdmin lokal -> database `db_workshop` -> Export -> SQL.

### 2. Susun paket upload
Dari folder project (Git Bash):
```
bash deploy/build_package.sh
```
Hasil di `deploy/hasil/`: `app/`, `public_html/`, `db_workshop.sql`.
Jika tidak ada Git Bash: salin manual `app/` -> folder `app`, isi `public/` -> folder `public_html`, lalu ubah
`UPLOAD_PATH` pada `app/config/config.php` menjadi `__DIR__ . '/../../public_html/uploads/'` dan `APP_ENV` menjadi `production`.

### 3. Buat database di cPanel (Langkah 3)
1. cPanel -> **MySQL Databases** -> buat database (mis. `namacpanel_dbworkshop`), buat user + password, **Add User To Database** -> **All Privileges**.
2. cPanel -> **phpMyAdmin** -> pilih database baru -> **Import** -> pilih `db_workshop.sql`.

### 4. Unggah berkas (Langkah 4)
1. cPanel -> **File Manager**.
2. Unggah isi `hasil/app/` ke `/home/namacpanel/app/` (sejajar `public_html`, bukan di dalamnya).
3. Unggah isi `hasil/public_html/` ke `public_html/` (pastikan `.htaccess` ikut: aktifkan *Show Hidden Files*).
4. Jika hosting mendukung Document Root per domain, arahkan ke folder `public` hasil unggahan.
5. Beri permission folder `uploads` = 755 (atau 775 bila upload gagal).

### 5. Konfigurasi produksi (Langkah 5)
Edit `app/config/Database.php` di server (contoh di `deploy/Database.production.example.php`):
```php
$host = 'localhost';
$db   = 'namacpanel_dbworkshop';
... new PDO(..., 'namacpanel_dbuser', 'password_sesuai_cpanel', $options)
```
Edit `app/config/config.php`: ganti `JWT_SECRET` dengan string acak panjang (mis. hasil `bin2hex(random_bytes(32))`).

`BASE_URL` pada `views/js/config.js` dihitung otomatis dari alamat halaman, jadi otomatis mengarah ke domain hosting.
Jika frontend dipisah domain, ganti baris tersebut dengan `const BASE_URL = 'https://namadomain.com';`.

### 6. Pengujian pasca-deployment (Langkah 6)
1. Buka `https://namadomain.com/views/index.html` -> data mahasiswa tampil.
2. Postman: Environment **Production** (`base_url` = domain hosting) -> jalankan login, GET/POST/PUT/DELETE.
3. Coba `https://namadomain.com/app/config/Database.php` -> harus 404/403 (bukti pemisahan MVC).

## Hasil simulasi struktur hosting (dijalankan sendiri)
Paket `deploy/hasil` dipasang pada Apache dengan struktur `app/` sejajar `public_html/` (DocumentRoot = public_html):

| Pengujian | Hasil |
|-----------|-------|
| GET /mahasiswa | 200, data tampil |
| GET /views/index.html | 200 |
| POST /login lalu upload foto | berhasil, foto tersimpan di `public_html/uploads`, URL gambar 200 |
| GET /app/config/Database.php | **404** (app di luar document root) |
| Mode production | JSON satu baris (tanpa pretty print) |

> Catatan: simulasi ini membuktikan paketnya benar, tetapi bukan deployment di hosting sungguhan.
> Untuk setoran, lakukan langkah 3-6 pada hosting/cPanel milik sendiri lalu ambil screenshot.
