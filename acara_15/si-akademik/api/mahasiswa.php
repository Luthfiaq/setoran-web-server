<?php

// Endpoint API mahasiswa (versi sederhana, Acara 15)
//   GET  api/mahasiswa.php          -> semua mahasiswa
//   GET  api/mahasiswa.php?id=1     -> satu mahasiswa
//   POST api/mahasiswa.php          -> tambah mahasiswa (body JSON)

require __DIR__ . '/koneksi.php';

// Semua response berupa JSON
header('Content-Type: application/json; charset=utf-8');

// Fungsi kecil untuk mengirim response terstruktur lalu berhenti
function kirim(int $kode, bool $success, string $message, $data = null): void
{
    http_response_code($kode);
    echo json_encode(
        ['success' => $success, 'message' => $message, 'data' => $data],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
    exit;
}

try {
    $pdo    = getKoneksi();
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        // GET dengan ?id=
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare('SELECT id, nim, nama, email FROM mahasiswa WHERE id = :id');
            $stmt->execute(['id' => (int) $_GET['id']]);
            $mhs = $stmt->fetch();

            if (!$mhs) {
                kirim(404, false, 'Data mahasiswa tidak ditemukan');
            }
            kirim(200, true, 'Data berhasil diambil', $mhs);
        }

        // GET semua data
        $data = $pdo->query('SELECT id, nim, nama, email FROM mahasiswa ORDER BY id')->fetchAll();
        kirim(200, true, 'Data berhasil diambil', $data);
    }

    if ($method === 'POST') {
        // Membaca body JSON dari client
        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input) || empty($input['nim']) || empty($input['nama']) || empty($input['email'])) {
            kirim(400, false, 'Field nim, nama, dan email wajib diisi');
        }

        $stmt = $pdo->prepare('INSERT INTO mahasiswa (nim, nama, email) VALUES (:nim, :nama, :email)');
        $stmt->execute([
            'nim'   => $input['nim'],
            'nama'  => $input['nama'],
            'email' => $input['email'],
        ]);

        kirim(201, true, 'Data mahasiswa berhasil ditambahkan', [
            'id'    => (int) $pdo->lastInsertId(),
            'nim'   => $input['nim'],
            'nama'  => $input['nama'],
            'email' => $input['email'],
        ]);
    }

    kirim(405, false, 'Method tidak diizinkan');
} catch (PDOException $e) {
    // NIM duplikat (UNIQUE) -> 409, selain itu 500 tanpa membocorkan detail
    if ($e->getCode() === '23000') {
        kirim(409, false, 'NIM sudah terdaftar');
    }
    kirim(500, false, 'Terjadi kesalahan pada server');
}
