# Dokumentasi API - Sistem Informasi Akademik (MVC)

Base URL (lokal, Laragon): `http://localhost/setoran-web/acara_26/sistem-informasi-akademik/public`

## 1. Ringkasan seluruh endpoint

| Method | Endpoint | Controller::method | Akses |
|--------|----------|--------------------|-------|
| POST | /register | AuthController::register | Publik |
| POST | /login | AuthController::login | Publik |
| GET | /mahasiswa | MahasiswaController::index | Publik |
| GET | /mahasiswa?id={id} | MahasiswaController::index | Publik |
| POST | /mahasiswa | MahasiswaController::store | Bearer token |
| PUT | /mahasiswa?id={id} | MahasiswaController::update | Bearer token |
| DELETE | /mahasiswa?id={id} | MahasiswaController::destroy | Bearer token (**admin**) |
| GET | /mahasiswa/summary | MahasiswaController::summary | Bearer token |
| GET | /buku | BukuController::index | Publik |
| POST/PUT/DELETE | /buku | BukuController::store/update/destroy | Bearer token |

## 2. Peta Router

| Resource (URL) | Controller | Keterangan |
|----------------|-----------|------------|
| /mahasiswa | MahasiswaController | CRUD data mahasiswa (index/store/update/destroy) |
| /buku | BukuController | CRUD data buku |
| /login | AuthController::login | Autentikasi pengguna, menghasilkan token |
| /register | AuthController::register | Pendaftaran pengguna baru |

Router memilih Controller dari segmen terakhir URL, lalu memilih method berdasarkan HTTP method:
GET -> index(), POST -> store(), PUT -> update($id), DELETE -> destroy($id).

## 3. Format response (envelope)

```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": {},
  "timestamp": "2026-11-09T10:00:00+07:00",
  "errors": { "field": "pesan" }
}
```
`errors` hanya muncul jika ada kesalahan validasi per field.

| Kode | Arti | Contoh |
|------|------|--------|
| 200 | OK | GET/PUT/DELETE berhasil, login berhasil |
| 201 | Created | Data/akun baru dibuat |
| 400 | Bad Request | Field wajib kosong |
| 401 | Unauthorized | Token tidak ada / tidak valid / kedaluwarsa, login salah |
| 403 | Forbidden | Token valid tetapi role bukan admin (DELETE) |
| 404 | Not Found | Endpoint atau data tidak ditemukan |
| 405 | Method Not Allowed | Method tidak didukung |
| 409 | Conflict | Username / email sudah dipakai |
| 422 | Unprocessable Entity | Format data salah (NIM bukan 9 digit, NIM duplikat, email salah) |
| 500 | Internal Server Error | Kesalahan di server |

## 4. Autentikasi

Endpoint terproteksi memerlukan header:
```
Authorization: Bearer <token>
```
Token diperoleh dari `POST /login`, berlaku 1 jam. Token memuat `id` dan `role` (admin/user).

| Role | Hak akses |
|------|-----------|
| user | GET, POST, PUT |
| admin | GET, POST, PUT, DELETE |

---

## POST /login
Controller: `AuthController::login()` - Akses: publik

**Headers:** `Content-Type: application/json`

**Request body**
```json
{ "username": "admin", "password": "rahasia123" }
```
**Response 200 OK**
```json
{ "success": true, "message": "Login berhasil", "data": { "token": "eyJhbGciOi...", "role": "admin" } }
```
**Response 401 Unauthorized**
```json
{ "success": false, "message": "Username atau password salah", "data": null }
```

## POST /register
Controller: `AuthController::register()` - Akses: publik

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| username | string | ya | unik |
| email | string | ya | format email valid, unik |
| password | string | ya | minimal 6 karakter (disimpan sebagai hash) |

**Response 201**
```json
{ "success": true, "message": "Registrasi berhasil", "data": { "id": "3" } }
```
**Response gagal:** 400 (field kosong), 422 (email tidak valid / password pendek), 409 (username/email sudah dipakai).

## GET /mahasiswa
Controller: `MahasiswaController::index()` - Akses: publik

**Response 200**
```json
{
  "success": true, "message": "Data berhasil diambil",
  "data": [ { "id": 1, "nim": "220100101", "nama": "Andi Saputra", "prodi": "Teknik Informatika", "created_at": "2026-11-09 10:00:00" } ]
}
```

## GET /mahasiswa?id=1
**Response 200:** satu objek mahasiswa pada `data`.
**Response 404:** `{ "success": false, "message": "Data tidak ditemukan" }`

## POST /mahasiswa
Controller: `MahasiswaController::store()` - Akses: **Bearer token**

**Headers:** `Content-Type: application/json`, `Authorization: Bearer <token>`

| Field | Tipe | Wajib | Aturan |
|-------|------|-------|--------|
| nim | string | ya | tepat 9 karakter, unik |
| nama | string | ya | |
| prodi | string | ya | |
| foto | file | tidak | JPG/PNG maksimal 2MB (hanya lewat `multipart/form-data`) |

**Request body**
```json
{ "nim": "220100123", "nama": "Andi Saputra", "prodi": "Teknik Informatika" }
```
**Response 201** `{ "success": true, "message": "Data berhasil ditambahkan", "data": { "id": "4" } }`
**Response gagal:** 400 (kosong, beserta `errors`), 401 (tanpa token), 422 (NIM bukan 9 digit / NIM duplikat).

**Upload foto (multipart/form-data):** kirim field `nim`, `nama`, `prodi`, dan `foto` (file). Validasi: ekstensi jpg/jpeg/png,
signature file diperiksa dengan `exif_imagetype()`, ukuran maks 2MB, file diberi nama acak. Gagal -> **422** dengan `errors.foto`.
Response GET menyertakan `foto` (nama file) dan `foto_url` (alamat gambar, `null` jika belum ada foto).

## PUT /mahasiswa?id=1
Controller: `MahasiswaController::update($id)` - Akses: **Bearer token**
Body sama seperti POST. Untuk mengirim file, PHP tidak membaca multipart pada method PUT, sehingga kirim `POST /mahasiswa?id=1&_method=PUT`
(method override). Foto lama otomatis dihapus dari server saat foto baru diunggah. **Response 200:** `{ "success": true, "message": "Data berhasil diperbarui" }`

## DELETE /mahasiswa?id=1
Controller: `MahasiswaController::destroy($id)` - Akses: **Bearer token**
**Response 200:** `{ "success": true, "message": "Data berhasil dihapus" }`
File foto mahasiswa ikut dihapus. **Response 403:** role bukan admin. **Response 404:** data tidak ditemukan.

## GET /mahasiswa/summary
Controller: `MahasiswaController::summary()` - Akses: **Bearer token** (semua role)
**Response 200**
```json
{ "success": true, "message": "Ringkasan berhasil diambil",
  "data": { "total": 3, "per_prodi": [ { "prodi": "Sistem Informasi", "jumlah": 1 }, { "prodi": "Teknik Informatika", "jumlah": 1 } ] } }
```

## GET /buku
Controller: `BukuController::index()` - Akses: publik. Mengembalikan daftar buku (`id, judul, penulis, tahun_terbit`).
