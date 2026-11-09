# Kendala Deployment dan Solusinya (Tugas Mandiri 2 - Acara 28)

## A. Kendala teknis yang ditemukan saat menyiapkan paket deployment

| No | Kendala | Penyebab | Solusi |
|----|---------|----------|--------|
| 1 | Foto tidak tersimpan pada struktur hosting | `Upload.php` menulis ke `app/../../public/uploads`, padahal di hosting folder bernama `public_html` | Path dipindah ke konstanta `UPLOAD_PATH` di `config.php`; `build_package.sh` menyesuaikannya |
| 2 | `Auth::SECRET` tertulis di kode Auth | Kredensial ter-hardcode di luar folder config | Dipindah menjadi `JWT_SECRET` pada `app/config/config.php` |
| 3 | Folder `app/` bisa dibuka lewat URL ketika dijalankan lokal (Laragon) | Seluruh project berada di dalam document root | Ditambah `.htaccess` `Require all denied` pada `app/`, `database/`, `scripts/`, `deploy/` |
| 4 | Foto uji ikut terunggah ke hosting | Folder `uploads` berisi berkas sisa pengujian | `build_package.sh` mengosongkan isi `uploads` pada paket |
| 5 | Nama database dan user di cPanel berbeda dengan lokal | cPanel menambah awalan nama akun | Kredensial diganti di `Database.php` server (contoh: `Database.production.example.php`) |

## B. Kendala yang saya temui saat deployment ke hosting sungguhan
(isi bagian ini sesuai pengalaman sendiri saat praktik di cPanel, contoh kolom: kendala - penyebab - solusi - bukti screenshot)

| No | Kendala | Penyebab | Solusi |
|----|---------|----------|--------|
| 1 | | | |
| 2 | | | |

## C. Daftar kendala yang umum terjadi di cPanel (untuk antisipasi)
- Error 500 setelah upload: periksa versi PHP di **MultiPHP Manager** (pakai PHP 8.x) dan permission folder (755) / file (644).
- `.htaccess` tidak ikut terunggah karena *Show Hidden Files* belum diaktifkan di File Manager.
- Endpoint selain `/mahasiswa` menghasilkan 404: `mod_rewrite` tidak aktif atau `AllowOverride` tidak mengizinkan `.htaccess`.
- Header `Authorization` tidak terbaca (selalu 401): pastikan baris `SetEnvIf Authorization ...` ada di `.htaccess`.
- CORS error di browser: frontend dan API beda domain, sesuaikan header `Access-Control-Allow-Origin`.
- `Access denied for user`: nama database/user/password di `Database.php` belum memakai awalan cPanel.
