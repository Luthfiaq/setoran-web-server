# Dokumentasi Pengujian API - Acara 23

Pengujian manual dengan Postman/curl terhadap API Sistem Informasi Akademik (MVC). Pengujian pertama dijalankan pada kode
**sebelum perbaikan (acara 22)**, lalu diulang setelah perbaikan (acara 23).

Base URL: `http://localhost/setoran-web/acara_23/sistem-informasi-akademik/public`

## 1. Daftar test case dan hasil

| TC | Skenario | Method + Endpoint | Hasil yang diharapkan | Hasil aktual SEBELUM perbaikan | Status | Hasil aktual SESUDAH perbaikan | Status |
|----|----------|-------------------|-----------------------|--------------------------------|--------|--------------------------------|--------|
| TC-01 | Login valid | POST /login | 200 | 200 | **PASS** | 200 | **PASS** |
| TC-02 | Login password salah | POST /login | 401 | 401 | **PASS** | 401 | **PASS** |
| TC-03 | GET semua mahasiswa | GET /mahasiswa | 200 | 200 | **PASS** | 200 | **PASS** |
| TC-04 | GET mahasiswa id valid | GET /mahasiswa?id=1 | 200 | 200 | **PASS** | 200 | **PASS** |
| TC-05 | GET mahasiswa id tidak ada | GET /mahasiswa?id=99999 | 404 | 404 | **PASS** | 404 | **PASS** |
| TC-06 | POST valid + token | POST /mahasiswa | 201 | 201 | **PASS** | 201 | **PASS** |
| TC-07 | POST NIM kosong | POST /mahasiswa | 400 | 400 | **PASS** | 400 | **PASS** |
| TC-08 | POST NIM duplikat | POST /mahasiswa | 422 | 422 | **PASS** | 422 | **PASS** |
| TC-09 | POST NIM bukan 9 digit | POST /mahasiswa | 422 | 422 | **PASS** | 422 | **PASS** |
| TC-10 | POST tanpa token | POST /mahasiswa | 401 | 401 | **PASS** | 401 | **PASS** |
| TC-11 | POST token kedaluwarsa | POST /mahasiswa | 401 | 401 | **PASS** | 401 | **PASS** |
| TC-12 | PUT valid | PUT /mahasiswa?id=N | 200 | 200 | **PASS** | 200 | **PASS** |
| TC-13 | PUT id tidak ada | PUT /mahasiswa?id=99999 | 404 | 200 | **FAIL** | 404 | **PASS** |
| TC-14 | PUT body kosong | PUT /mahasiswa?id=N | 400 | 500 | **FAIL** | 400 | **PASS** |
| TC-15 | DELETE id tidak ada | DELETE /mahasiswa?id=99999 | 404 | 200 | **FAIL** | 404 | **PASS** |
| TC-16 | DELETE valid | DELETE /mahasiswa?id=N | 200 | 200 | **PASS** | 200 | **PASS** |

Ringkasan: sebelum perbaikan **13 PASS / 3 FAIL**, setelah perbaikan **16 PASS / 0 FAIL**.

## 2. Bug yang ditemukan dan perbaikannya

| TC | Gejala | Penyebab | Perbaikan |
|----|--------|----------|-----------|
| TC-13 | PUT ke id yang tidak ada membalas 200 "berhasil diperbarui" | Controller langsung menjalankan UPDATE tanpa memeriksa apakah data ada (UPDATE yang tidak mengenai baris tidak menimbulkan error) | `MahasiswaController::update()` memanggil `Mahasiswa::find()` terlebih dahulu, jika tidak ada balas 404 |
| TC-14 | PUT dengan body kosong membalas 500 | `json_decode` mengembalikan `null`, lalu `null` dikirim ke `Mahasiswa::update(int, array)` sehingga PHP melempar TypeError | Method `bacaBody()` memvalidasi body, jika kosong/bukan JSON balas 400. Validasi field + NIM dipakai bersama oleh store() dan update() |
| TC-15 | DELETE id yang tidak ada membalas 200 | Sama seperti TC-13 | `destroy()` memeriksa keberadaan data dulu, balas 404 |

Perbaikan yang sama diterapkan pada `BukuController`.

## 3. Pengujian otomatis di Postman (tugas mandiri 1)

File `postman/API_SIA_Acara23.postman_collection.json` sudah berisi tab **Tests** pada tiap request, mencakup:
- `pm.response.to.have.status(...)` (status code sesuai)
- `pm.response.to.be.json` (response berformat JSON)
- `success` bernilai sesuai (true untuk 2xx, false untuk 4xx)

Cara menjalankan: Import collection + environment **Local**, pilih environment Local, jalankan request **POST login (admin)** terlebih dahulu (token otomatis tersimpan), lalu klik **Run collection**.

## 4. Pengujian keamanan dasar

| Pengujian | Cara | Hasil |
|-----------|------|-------|
| SQL Injection pada parameter id | `GET /mahasiswa?id=1 OR 1=1` | Hanya mengembalikan data id 1 (nilai di-cast integer, query memakai prepared statement) |
| Token dimanipulasi | Mengubah 1 karakter pada token | 401 (signature tidak cocok) |
| Token kedaluwarsa | Token dengan `exp` di masa lalu | 401 |
| Akses tanpa token | POST/PUT/DELETE tanpa header Authorization | 401 |

> Catatan: hasil di atas diperoleh dari eksekusi nyata pada Apache + PHP 8.3 + MariaDB. Saat setoran, jalankan ulang di Postman Laragon untuk screenshot bukti.