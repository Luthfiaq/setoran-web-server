<?php

// RESTful API mahasiswa (Acara 16)
//   GET    /api/mahasiswa        semua data
//   GET    /api/mahasiswa/{id}   satu data
//   POST   /api/mahasiswa        tambah
//   PUT    /api/mahasiswa/{id}   ubah
//   DELETE /api/mahasiswa/{id}   hapus

require __DIR__ . '/koneksi.php';
require __DIR__ . '/response.php';

// Mengambil bagian URL setelah folder api, contoh: ['mahasiswa', '5']
$path     = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
$path     = trim(substr($path, strlen($basePath)), '/');
$segmen   = $path === '' ? [] : explode('/', $path);

$resource = $segmen[0] ?? '';
$id       = isset($segmen[1]) ? (int) $segmen[1] : null;
$method   = $_SERVER['REQUEST_METHOD'];

if ($resource !== 'mahasiswa') {
    kirim(404, false, 'Endpoint tidak ditemukan');
}

// Validasi data mahasiswa -> mengembalikan daftar error per field
function validasi(array $d): array
{
    $err = [];
    if (empty(trim($d['nim'] ?? '')))   { $err['nim']   = 'NIM wajib diisi'; }
    if (empty(trim($d['nama'] ?? '')))  { $err['nama']  = 'Nama wajib diisi'; }
    if (empty(trim($d['email'] ?? ''))) {
        $err['email'] = 'Email wajib diisi';
    } elseif (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
        $err['email'] = 'Format email tidak valid';
    }
    return $err;
}

// Data kosong/tidak lengkap -> 400 Bad Request.
// Data lengkap tetapi format email salah -> 422 Unprocessable Entity.
function kodeValidasi(array $err): int
{
    return (count($err) === 1 && ($err['email'] ?? '') === 'Format email tidak valid') ? 422 : 400;
}

try {
    $pdo = getKoneksi();

    switch ($method) {
        case 'GET':
            if ($id !== null) {
                $stmt = $pdo->prepare('SELECT id, nim, nama, email FROM mahasiswa WHERE id = :id');
                $stmt->execute(['id' => $id]);
                $mhs = $stmt->fetch();
                $mhs ? kirim(200, true, 'Data berhasil diambil', $mhs)
                     : kirim(404, false, 'Data mahasiswa tidak ditemukan');
            }
            kirim(200, true, 'Data berhasil diambil',
                $pdo->query('SELECT id, nim, nama, email FROM mahasiswa ORDER BY id')->fetchAll());
            break;

        case 'POST':
            $input = bacaJson();
            if ($err = validasi($input)) {
                kirim(kodeValidasi($err), false, 'Data tidak valid: ' . implode(', ', $err));
            }
            $stmt = $pdo->prepare('INSERT INTO mahasiswa (nim, nama, email) VALUES (:nim, :nama, :email)');
            $stmt->execute(['nim' => $input['nim'], 'nama' => $input['nama'], 'email' => $input['email']]);
            kirim(201, true, 'Data mahasiswa berhasil ditambahkan', [
                'id' => (int) $pdo->lastInsertId(), 'nim' => $input['nim'], 'nama' => $input['nama'],
            ]);
            break;

        case 'PUT':
            if ($id === null) {
                kirim(400, false, 'ID mahasiswa wajib ada pada URL');
            }
            $input = bacaJson();
            if ($err = validasi($input)) {
                kirim(kodeValidasi($err), false, 'Data tidak valid: ' . implode(', ', $err));
            }
            $stmt = $pdo->prepare('UPDATE mahasiswa SET nim = :nim, nama = :nama, email = :email WHERE id = :id');
            $stmt->execute(['nim' => $input['nim'], 'nama' => $input['nama'], 'email' => $input['email'], 'id' => $id]);

            // rowCount bisa 0 jika datanya sama persis, jadi cek keberadaan data
            $cek = $pdo->prepare('SELECT COUNT(*) FROM mahasiswa WHERE id = :id');
            $cek->execute(['id' => $id]);
            $cek->fetchColumn() ? kirim(200, true, 'Data mahasiswa berhasil diperbarui')
                                : kirim(404, false, 'Data mahasiswa tidak ditemukan');
            break;

        case 'DELETE':
            if ($id === null) {
                kirim(400, false, 'ID mahasiswa wajib ada pada URL');
            }
            $stmt = $pdo->prepare('DELETE FROM mahasiswa WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $stmt->rowCount() ? kirim(200, true, 'Data mahasiswa berhasil dihapus')
                              : kirim(404, false, 'Data mahasiswa tidak ditemukan');
            break;

        default:
            kirim(405, false, 'Method tidak diizinkan');
    }
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        kirim(409, false, 'NIM sudah terdaftar');
    }
    kirim(500, false, 'Terjadi kesalahan pada server');
}
