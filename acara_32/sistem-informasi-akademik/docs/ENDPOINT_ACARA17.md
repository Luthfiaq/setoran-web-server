# Dokumentasi Endpoint - Acara 17 (Fondasi MVC)

Base URL (Laragon): `http://localhost/setoran-web/acara_17/sistem-informasi-akademik/public`

## Diagram alur Router -> Controller -> Model

```
Client (Postman)
      |  HTTP Request  (GET/POST/PUT/DELETE /mahasiswa)
      v
public/.htaccess        semua request non-file diarahkan ke index.php
      v
public/index.php        Front Controller (satu-satunya pintu masuk)
      v
Router::dispatch()      membaca URL + method, memilih Controller
      v
MahasiswaController     tidak menulis SQL, hanya memanggil Model
      v
Mahasiswa (Model)       prepared statement PDO
      v
MySQL db_workshop
      v
JSON Response  ---------> Client
```

## Tabel endpoint

| Method | Endpoint | Controller::method | Model | Keterangan |
|--------|----------|--------------------|-------|------------|
| GET | /mahasiswa | MahasiswaController::index | getAll() | Semua mahasiswa |
| GET | /mahasiswa?id=1 | MahasiswaController::index | find() | Satu mahasiswa |
| POST | /mahasiswa | MahasiswaController::store | existsByNim(), create() | Tambah (body JSON) |
| PUT | /mahasiswa?id=1 | MahasiswaController::update | update() | Ubah (body JSON) |
| DELETE | /mahasiswa?id=1 | MahasiswaController::destroy | delete() | Hapus |
| GET/POST/PUT/DELETE | /buku | BukuController | Buku | Resource kedua (tugas mandiri 1) |

## Contoh request dan response

POST /mahasiswa
```json
{ "nim": "220100123", "nama": "Andi Saputra", "prodi": "Teknik Informatika" }
```
Response 201 Created
```json
{ "success": true, "message": "Data berhasil ditambahkan", "data": { "id": "4" } }
```
NIM sudah ada (validasi di Model) -> 422
```json
{ "success": false, "message": "NIM sudah terdaftar" }
```
Field kurang -> 400
```json
{ "success": false, "message": "Field nim, nama, dan prodi wajib diisi" }
```
