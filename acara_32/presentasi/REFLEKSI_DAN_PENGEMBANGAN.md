# Evaluasi dan Rencana Pengembangan (untuk sesi tanya jawab akhir)

## Kelebihan
- Arsitektur MVC jelas dan berlapis; controller tipis; SQL hanya di Model.
- Keamanan berlapis: prepared statement, password hash, JWT + role, upload aman, escape XSS, CORS, kredensial terpisah, HTTPS.
- Terdokumentasi: Markdown, OpenAPI/Swagger, Postman collection, test case, panduan deployment/backup.
- Teruji: 16 test case; bug yang ditemukan sudah diperbaiki.

## Keterbatasan (jujur)
| Keterbatasan | Dampak | Perbaikan yang dapat dilakukan |
|--------------|--------|--------------------------------|
| Token JWT tidak bisa dicabut sebelum kedaluwarsa | Token yang dicuri berlaku sampai `exp` | Refresh token pendek + blacklist |
| Belum ada pembatasan percobaan login (rate limiting) | Rentan tebak password | Batasi percobaan per IP/akun |
| GET /mahasiswa belum berpaginasi | Lambat bila data ribuan | `?page=&per_page=` |
| Token disimpan di localStorage | Bisa dicuri bila ada XSS | Cookie `HttpOnly` + CSRF token |
| Pengujian masih manual/Postman | Regresi tidak otomatis | PHPUnit / newman di CI |
| Tidak ada migrasi database | Perubahan skema manual | Skrip migrasi berversi |

## Rencana pengembangan
1. Pagination dan pencarian pada API.
2. Resource Prodi dan Mata Kuliah pada API (seperti di si-akademik).
3. Refresh token, rate limiting login.
4. Otomatisasi pengujian (newman) dan CI sederhana di GitHub Actions.
5. Backup otomatis termasuk folder `uploads`.

## Pelajaran
- Validasi dan pengecekan keberadaan data harus dipikirkan sejak awal (3 bug ditemukan karena hal ini).
- Pemisahan lapisan membuat perbaikan bug cukup di satu tempat.
- Konfigurasi dan kredensial harus terpisah dari kode agar aman dan mudah dipindah lingkungan.
