# Latihan Tanya Jawab (jawaban sesuai kode yang dibuat)

## Arsitektur & dasar
**Apa itu MVC dan bagaimana di project ini?**
Model mengurus data (SQL via PDO), View adalah tampilan (HTML/JS di `public/views` atau file PHP di si-akademik), Controller menerima request dan mengatur alurnya. Pemisahan ini membuat kode mudah dirawat dan diuji.

**Kenapa semua request lewat `index.php` (Front Controller)?**
Supaya ada satu pintu masuk untuk menjalankan hal yang sama pada tiap request (CORS, autoload, Router). `.htaccess` mengalihkan semua URL yang bukan file asli ke `index.php`, sehingga URL bersih seperti `/mahasiswa`.

**Bagaimana Router bekerja?**
Mengambil segmen terakhir URL (`mahasiswa`, `buku`, `login`...) untuk memilih Controller, lalu memilih method berdasarkan HTTP method: GET -> index, POST -> store, PUT -> update, DELETE -> destroy.

**Kenapa Controller tidak boleh menulis SQL?**
Agar tanggung jawab terpisah: kalau struktur tabel berubah, cukup ubah Model, Controller tidak ikut berubah.

**Apa fungsi autoloader?**
`spl_autoload_register` memuat file class otomatis saat class dipakai dengan `new`, jadi tidak perlu `require` satu per satu.

## Database & keamanan
**Kenapa memakai prepared statement?**
Input dikirim terpisah dari perintah SQL sehingga tidak bisa mengubah struktur query (mencegah SQL injection). Saya mengujinya: `?id=1 OR 1=1` hanya mengembalikan data id 1.

**Kenapa password di-hash, bukan disimpan biasa atau MD5?**
Kalau database bocor, password asli tidak terbaca. `password_hash()` memakai bcrypt dengan salt otomatis dan lambat disengaja; MD5 cepat dan mudah dipecahkan. Verifikasi memakai `password_verify()`.

**Bagaimana JWT bekerja?**
Token terdiri dari header.payload.signature. Signature dibuat dengan HMAC-SHA256 memakai kunci rahasia server. Saat request, server menghitung ulang signature dan membandingkan dengan `hash_equals`; payload juga memuat `exp` (kedaluwarsa 1 jam) dan `role`.

**Kenapa logout hanya menghapus token di client?**
JWT bersifat stateless, server tidak menyimpan sesi. Konsekuensinya token yang sudah dikeluarkan tetap valid sampai `exp`, makanya masa berlakunya dibatasi. Pengembangan: refresh token atau blacklist.

**Beda 401 dan 403?**
401: belum terautentikasi (token tidak ada / tidak valid / kedaluwarsa). 403: sudah login tetapi tidak punya hak (user biasa mencoba DELETE yang khusus admin).

**Beda 400 dan 422?**
400: request kurang/rusak (field wajib kosong, body bukan JSON). 422: data lengkap tetapi melanggar aturan (NIM bukan 9 karakter, NIM duplikat, email salah).

**Bagaimana pengamanan upload foto?**
Berlapis: batas 2MB, ekstensi jpg/jpeg/png, isi berkas diperiksa dengan `exif_imagetype()` (bukan percaya nama/`type`), nama diganti acak, dan folder `uploads` memblokir eksekusi PHP lewat `.htaccess`. Saya menguji skrip PHP yang menyamar jadi `.png`: ditolak 422.

**Apa itu XSS dan pencegahannya?**
Menyisipkan skrip lewat data yang ditampilkan. Di View semua data dinamis lewat `escapeHtml()` sebelum masuk `innerHTML`, jadi `<b>` tampil sebagai teks. Di si-akademik (PHP) memakai `e()` / `htmlspecialchars`.

**Apa itu CSRF dan di mana dipakai?**
Serangan yang membuat browser korban mengirim form tanpa sadar. Di si-akademik (berbasis session/cookie) setiap form membawa token acak yang diverifikasi untuk semua POST. API JWT tidak memakai cookie sehingga tidak rentan CSRF dengan cara yang sama.

**Apa itu CORS dan preflight?**
Browser memblokir request lintas origin kecuali server mengizinkan lewat header `Access-Control-Allow-*`. Untuk request berformat JSON/Authorization, browser mengirim `OPTIONS` dulu (preflight); server menjawab 204 tanpa isi.

## Implementasi khusus
**Kenapa edit dengan foto memakai `POST ?_method=PUT`?**
PHP tidak mengisi `$_POST`/`$_FILES` untuk request PUT bertipe multipart. Method override (juga dipakai Laravel) mengirim POST dan Router menganggapnya PUT.

**Kenapa `NIM` hanya diperiksa panjang 9?**
Sesuai spesifikasi modul. NIM bisa berisi huruf (mis. E41251516), jadi tidak dipaksa angka. Bisa ditambah aturan sendiri bila kampus mensyaratkan.

**Bug apa yang ditemukan saat pengujian?**
3 dari 16 test case gagal: PUT ke id tidak ada membalas 200 (harusnya 404), PUT body kosong membalas 500 (TypeError), DELETE id tidak ada membalas 200. Diperbaiki dengan memeriksa keberadaan data dan validasi body, lalu 16/16 PASS.

**Kenapa folder `app/` di luar `public_html`?**
Agar kode PHP dan konfigurasi (kredensial) tidak bisa diakses langsung dari browser. Hanya `public/` yang menjadi document root. Diuji: `/app/config/env.php` -> 404.

**Kenapa `display_errors` dimatikan di produksi?**
Pesan error PHP bisa membocorkan path server dan query. Error tetap dicatat ke log (`log_errors`).

**Bagaimana backup dan pemulihannya?**
`mysqldump` (script `scripts/backup_db.bat`) dijadwalkan di akhir sesi. Pemulihan dengan import berkas SQL. Saya mensimulasikan tabel `mahasiswa` dihapus lalu dipulihkan dari backup (3 baris kembali).

## Proyek si-akademik (acara 6-14)
**Apa itu Dependency Injection?**
Objek tidak membuat dependensinya sendiri, tetapi menerimanya dari luar (constructor). Controller menerima Repository, Repository menerima PDO. Perakitannya di `public/index.php` (composition root). Manfaat: mudah diganti/diuji.

**Repository vs Service vs Controller?**
Repository: semua SQL satu tabel. Service: aturan bisnis (validasi, NIM duplikat, prodi harus ada). Controller: terima request, panggil Service, tentukan redirect/pesan.

**Apa itu PRG (Post-Redirect-Get)?**
Setelah POST berhasil, server redirect ke halaman GET agar refresh tidak mengirim ulang form (mencegah data ganda).

**Git: apa beda merge dan conflict?**
Merge menggabungkan branch. Conflict terjadi saat dua branch mengubah baris yang sama; Git menandai `<<<<<<<`, `=======`, `>>>>>>>` dan kita pilih manual lalu commit.
